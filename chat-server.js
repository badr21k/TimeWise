const http = require("http");
const https = require("https");
const { Server } = require("socket.io");

const CHAT_PORT = process.env.CHAT_PORT || 3001;
const API_BASE = process.env.REPLIT_DEV_DOMAIN 
  ? `https://${process.env.REPLIT_DEV_DOMAIN}` 
  : 'http://localhost:5000';

let nextRoomId = 1;
let nextMsgId = 1;

const users = new Map();
const sockets = new Map();
const rooms = new Map();
const dmIndex = new Map();
const rateLimits = new Map();
const typingStatus = new Map();
const presence = new Map();

function dmKey(a, b) {
  const [x, y] = [Number(a), Number(b)].sort((m, n) => m - n);
  return `dm:${x}-${y}`;
}

function ensureDM(u1, u2) {
  const key = dmKey(u1, u2);
  if (dmIndex.has(key)) return dmIndex.get(key);
  const id = String(nextRoomId++);
  rooms.set(id, {
    id,
    is_group: 0,
    name: null,
    avatar: null,
    description: null,
    members: new Set([+u1, +u2]),
    roles: new Map([[+u1, 'member'], [+u2, 'member']]),
    owner: null,
    history: [],
    reactions: new Map(),
    unread: new Map([[+u1, 0], [+u2, 0]]),
    muted: new Set(),
    settings: {}
  });
  dmIndex.set(key, id);
  return id;
}

function getRoomInfo(room, userId) {
  const info = {
    id: room.id,
    is_group: room.is_group,
    name: room.name,
    avatar: room.avatar,
    description: room.description,
    unread: room.unread.get(userId) || 0,
    muted: room.muted.has(userId)
  };
  if (room.is_group) {
    info.role = room.roles.get(userId) || 'member';
    info.owner = room.owner;
    info.memberCount = room.members.size;
  }
  return info;
}

function listRoomsFor(userId) {
  const out = [];
  for (const r of rooms.values()) {
    if (r.members.has(+userId)) {
      out.push(getRoomInfo(r, userId));
    }
  }
  return out;
}

function historyOf(roomId, limit = 50, before = null) {
  const r = rooms.get(String(roomId));
  if (!r) return [];
  let msgs = r.history;
  if (before) {
    const idx = msgs.findIndex(m => m.id === before);
    if (idx > 0) msgs = msgs.slice(0, idx);
  }
  return msgs.slice(-limit);
}

function checkRole(room, userId, minRole = 'member') {
  if (!room.is_group) return true;
  const role = room.roles.get(userId);
  if (!role) return false;
  if (minRole === 'member') return true;
  if (minRole === 'admin') return role === 'admin' || role === 'owner';
  if (minRole === 'owner') return role === 'owner';
  return false;
}

function rateLimit(key, maxPerMin = 30) {
  const now = Date.now();
  if (!rateLimits.has(key)) rateLimits.set(key, []);
  const times = rateLimits.get(key).filter(t => now - t < 60000);
  times.push(now);
  rateLimits.set(key, times);
  return times.length > maxPerMin;
}

function sanitize(str, maxLen = 5000) {
  return String(str || '').slice(0, maxLen).trim();
}

function validateToken(token, userId) {
  return new Promise((resolve) => {
    const url = `${API_BASE}/chatapi/validateToken`;
    const data = JSON.stringify({ token, userId });
    
    const urlObj = new URL(url);
    const client = urlObj.protocol === 'https:' ? https : http;
    
    const options = {
      hostname: urlObj.hostname,
      port: urlObj.port || (urlObj.protocol === 'https:' ? 443 : 80),
      path: urlObj.pathname,
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Content-Length': Buffer.byteLength(data)
      }
    };

    const req = client.request(options, (res) => {
      let body = '';
      res.on('data', chunk => body += chunk);
      res.on('end', () => {
        try {
          const result = JSON.parse(body);
          resolve(result);
        } catch (e) {
          resolve({ valid: false, error: 'Invalid response' });
        }
      });
    });

    req.on('error', (e) => {
      console.error('[chat] Token validation failed:', e.message);
      resolve({ valid: false, error: 'Validation service unavailable' });
    });

    req.setTimeout(5000, () => {
      req.destroy();
      resolve({ valid: false, error: 'Validation timeout' });
    });

    req.write(data);
    req.end();
  });
}

function forceLeaveRoom(io, roomId, userId) {
  const u = users.get(userId);
  if (u) {
    for (const sid of u.sockets) {
      const socket = io.sockets.sockets.get(sid);
      if (socket) {
        socket.leave(String(roomId));
      }
    }
  }
}

const httpServer = http.createServer((req, res) => {
  res.writeHead(200, { "Content-Type": "text/plain" });
  res.end("Socket.IO chat running\n");
});

const io = new Server(httpServer, {
  cors: {
    origin: (origin, cb) => cb(null, true),
    methods: ["GET", "POST"],
    credentials: true
  },
  transports: ["websocket", "polling"],
  allowEIO3: false,
  pingTimeout: 20000,
  pingInterval: 25000
});

io.on("connection", (socket) => {
  console.log("[chat] client connected:", socket.id);

  socket.on("auth", async (payload = {}) => {
    const claimedUserId = Number(payload.userId || 0);
    const claimedUsername = sanitize(payload.username || "User", 50);
    const token = payload.chat_token || '';

    if (!claimedUserId || !token) {
      socket.emit("fatal", { error: "Missing userId or token" });
      socket.disconnect();
      return;
    }

    const validation = await validateToken(token, claimedUserId);
    
    if (!validation.valid) {
      console.warn(`[chat] Auth failed for user ${claimedUserId}: ${validation.error}`);
      socket.emit("fatal", { error: "Authentication failed" });
      socket.disconnect();
      return;
    }

    const userId = validation.userId;
    const username = validation.username;
    
    sockets.set(socket.id, { userId, username });
    if (!users.has(userId)) users.set(userId, { username, sockets: new Set() });
    users.get(userId).username = username;
    users.get(userId).sockets.add(socket.id);
    presence.set(userId, { status: 'online', lastSeen: Date.now() });
    
    console.log("[chat] authenticated:", userId, username);
    socket.emit("rooms:list", listRoomsFor(userId));
    socket.emit("presence:update", Array.from(presence.entries()).map(([id, p]) => ({ userId: id, ...p })));
  });

  socket.on("rooms:refresh", () => {
    const meta = sockets.get(socket.id);
    if (!meta) return socket.emit("fatal", { error: "Unauthenticated" });
    socket.emit("rooms:list", listRoomsFor(meta.userId));
  });

  socket.on("dm:open", (targetUserId, cb) => {
    const meta = sockets.get(socket.id);
    if (!meta) return cb && cb({ ok: false, error: "Unauthenticated" });
    const roomId = ensureDM(meta.userId, Number(targetUserId));
    cb && cb({ ok: true, room_id: roomId });
  });

  socket.on("room:join", (roomId, cb) => {
    const meta = sockets.get(socket.id);
    if (!meta) return cb && cb([]);
    const r = rooms.get(String(roomId));
    if (!r || !r.members.has(meta.userId)) return cb && cb([]);
    socket.join(String(roomId));
    
    r.unread.set(meta.userId, 0);
    const history = historyOf(roomId, 50);
    cb && cb({ ok: true, messages: history, room: getRoomInfo(r, meta.userId) });
    
    notifyRoomMembers(r, meta.userId);
  });

  socket.on("room:create", (payload = {}, cb) => {
    const meta = sockets.get(socket.id);
    if (!meta) return cb && cb({ ok: false, error: "Unauthenticated" });
    
    const name = sanitize(payload.name, 100);
    const memberIds = (payload.members || []).map(Number).filter(id => id !== meta.userId);
    
    if (!name || memberIds.length === 0) {
      return cb && cb({ ok: false, error: "Name and members required" });
    }

    const id = String(nextRoomId++);
    const members = new Set([meta.userId, ...memberIds]);
    const roles = new Map([[meta.userId, 'owner']]);
    memberIds.forEach(uid => roles.set(uid, 'member'));
    
    rooms.set(id, {
      id,
      is_group: 1,
      name,
      avatar: payload.avatar || null,
      description: payload.description || null,
      members,
      roles,
      owner: meta.userId,
      history: [],
      reactions: new Map(),
      unread: new Map(Array.from(members).map(uid => [uid, 0])),
      muted: new Set(),
      settings: {}
    });
    
    cb && cb({ ok: true, room_id: id });
    
    for (const uid of members) {
      const u = users.get(uid);
      if (u) for (const sid of u.sockets) io.to(sid).emit("rooms:list", listRoomsFor(uid));
    }
  });

  socket.on("room:update", (payload = {}, cb) => {
    const meta = sockets.get(socket.id);
    if (!meta) return cb && cb({ ok: false, error: "Unauthenticated" });
    
    const r = rooms.get(String(payload.room_id));
    if (!r || !checkRole(r, meta.userId, 'admin')) {
      return cb && cb({ ok: false, error: "No permission" });
    }
    
    if (payload.name) r.name = sanitize(payload.name, 100);
    if (payload.avatar !== undefined) r.avatar = sanitize(payload.avatar, 500);
    if (payload.description !== undefined) r.description = sanitize(payload.description, 500);
    
    cb && cb({ ok: true });
    notifyRoomMembers(r);
  });

  socket.on("room:add_member", (payload = {}, cb) => {
    const meta = sockets.get(socket.id);
    if (!meta) return cb && cb({ ok: false, error: "Unauthenticated" });
    
    const r = rooms.get(String(payload.room_id));
    if (!r || !checkRole(r, meta.userId, 'admin')) {
      return cb && cb({ ok: false, error: "No permission" });
    }
    
    const newUserId = Number(payload.user_id);
    r.members.add(newUserId);
    r.roles.set(newUserId, 'member');
    r.unread.set(newUserId, 0);
    
    cb && cb({ ok: true });
    notifyRoomMembers(r);
    
    const u = users.get(newUserId);
    if (u) for (const sid of u.sockets) io.to(sid).emit("rooms:list", listRoomsFor(newUserId));
  });

  socket.on("room:remove_member", (payload = {}, cb) => {
    const meta = sockets.get(socket.id);
    if (!meta) return cb && cb({ ok: false, error: "Unauthenticated" });
    
    const r = rooms.get(String(payload.room_id));
    if (!r || !checkRole(r, meta.userId, 'admin')) {
      return cb && cb({ ok: false, error: "No permission" });
    }
    
    const targetUserId = Number(payload.user_id);
    if (targetUserId === r.owner) {
      return cb && cb({ ok: false, error: "Cannot remove owner" });
    }
    
    r.members.delete(targetUserId);
    r.roles.delete(targetUserId);
    r.unread.delete(targetUserId);
    
    forceLeaveRoom(io, payload.room_id, targetUserId);
    
    cb && cb({ ok: true });
    notifyRoomMembers(r);
    
    const u = users.get(targetUserId);
    if (u) for (const sid of u.sockets) io.to(sid).emit("rooms:list", listRoomsFor(targetUserId));
  });

  socket.on("room:set_role", (payload = {}, cb) => {
    const meta = sockets.get(socket.id);
    if (!meta) return cb && cb({ ok: false, error: "Unauthenticated" });
    
    const r = rooms.get(String(payload.room_id));
    if (!r || !checkRole(r, meta.userId, 'owner')) {
      return cb && cb({ ok: false, error: "Only owner can change roles" });
    }
    
    const targetUserId = Number(payload.user_id);
    const newRole = payload.role;
    
    if (!['admin', 'member'].includes(newRole)) {
      return cb && cb({ ok: false, error: "Invalid role" });
    }
    
    r.roles.set(targetUserId, newRole);
    cb && cb({ ok: true });
    notifyRoomMembers(r);
  });

  socket.on("room:transfer_ownership", (payload = {}, cb) => {
    const meta = sockets.get(socket.id);
    if (!meta) return cb && cb({ ok: false, error: "Unauthenticated" });
    
    const r = rooms.get(String(payload.room_id));
    if (!r || r.owner !== meta.userId) {
      return cb && cb({ ok: false, error: "Only owner can transfer" });
    }
    
    const newOwnerId = Number(payload.new_owner_id);
    if (!r.members.has(newOwnerId)) {
      return cb && cb({ ok: false, error: "User not in room" });
    }
    
    r.roles.set(meta.userId, 'admin');
    r.roles.set(newOwnerId, 'owner');
    r.owner = newOwnerId;
    
    cb && cb({ ok: true });
    notifyRoomMembers(r);
  });

  socket.on("room:leave", (roomId, cb) => {
    const meta = sockets.get(socket.id);
    if (!meta) return cb && cb({ ok: false, error: "Unauthenticated" });
    
    const r = rooms.get(String(roomId));
    if (!r || !r.members.has(meta.userId)) {
      return cb && cb({ ok: false, error: "Not in room" });
    }
    
    if (r.is_group && r.owner === meta.userId) {
      return cb && cb({ ok: false, error: "Owner must transfer ownership first" });
    }
    
    r.members.delete(meta.userId);
    r.roles.delete(meta.userId);
    r.unread.delete(meta.userId);
    
    forceLeaveRoom(io, roomId, meta.userId);
    
    cb && cb({ ok: true });
    socket.emit("rooms:list", listRoomsFor(meta.userId));
    notifyRoomMembers(r);
  });

  socket.on("room:delete", (roomId, cb) => {
    const meta = sockets.get(socket.id);
    if (!meta) return cb && cb({ ok: false, error: "Unauthenticated" });
    
    const r = rooms.get(String(roomId));
    if (!r || r.owner !== meta.userId) {
      return cb && cb({ ok: false, error: "Only owner can delete" });
    }
    
    const memberIds = Array.from(r.members);
    
    for (const uid of memberIds) {
      forceLeaveRoom(io, roomId, uid);
    }
    
    rooms.delete(roomId);
    
    cb && cb({ ok: true });
    
    for (const uid of memberIds) {
      const u = users.get(uid);
      if (u) for (const sid of u.sockets) io.to(sid).emit("rooms:list", listRoomsFor(uid));
    }
  });

  socket.on("room:mute", (payload = {}, cb) => {
    const meta = sockets.get(socket.id);
    if (!meta) return cb && cb({ ok: false, error: "Unauthenticated" });
    
    const r = rooms.get(String(payload.room_id));
    if (!r || !r.members.has(meta.userId)) {
      return cb && cb({ ok: false, error: "Not in room" });
    }
    
    if (payload.muted) {
      r.muted.add(meta.userId);
    } else {
      r.muted.delete(meta.userId);
    }
    
    cb && cb({ ok: true });
    socket.emit("rooms:list", listRoomsFor(meta.userId));
  });

  socket.on("message:send", (payload = {}, cb) => {
    const meta = sockets.get(socket.id);
    const { room_id, body, attachment } = payload || {};
    if (!meta) return cb && cb({ ok: false, error: "Unauthenticated" });
    
    if (rateLimit(`msg:${meta.userId}`, 30)) {
      return cb && cb({ ok: false, error: "Rate limit exceeded" });
    }
    
    const r = rooms.get(String(room_id));
    if (!r || !r.members.has(meta.userId)) {
      return cb && cb({ ok: false, error: "No access" });
    }

    const msg = {
      id: String(nextMsgId++),
      room_id: String(room_id),
      user_id: meta.userId,
      username: meta.username,
      body: sanitize(body, 5000),
      attachment: attachment ? sanitize(JSON.stringify(attachment), 10000) : null,
      created_at: Date.now(),
      edited: false,
      reactions: {}
    };
    
    r.history.push(msg);
    if (r.history.length > 500) r.history.shift();
    
    for (const uid of r.members) {
      if (uid !== meta.userId && !r.muted.has(uid)) {
        r.unread.set(uid, (r.unread.get(uid) || 0) + 1);
      }
    }
    
    io.to(String(room_id)).emit("message:new", msg);
    notifyRoomMembers(r, meta.userId);
    
    cb && cb({ ok: true, message: msg });
  });

  socket.on("message:edit", (payload = {}, cb) => {
    const meta = sockets.get(socket.id);
    if (!meta) return cb && cb({ ok: false, error: "Unauthenticated" });
    
    const r = rooms.get(String(payload.room_id));
    if (!r) return cb && cb({ ok: false, error: "Room not found" });
    
    const msg = r.history.find(m => m.id === payload.message_id);
    if (!msg || msg.user_id !== meta.userId) {
      return cb && cb({ ok: false, error: "Cannot edit" });
    }
    
    msg.body = sanitize(payload.body, 5000);
    msg.edited = true;
    msg.edited_at = Date.now();
    
    io.to(String(payload.room_id)).emit("message:edited", msg);
    cb && cb({ ok: true });
  });

  socket.on("message:delete", (payload = {}, cb) => {
    const meta = sockets.get(socket.id);
    if (!meta) return cb && cb({ ok: false, error: "Unauthenticated" });
    
    const r = rooms.get(String(payload.room_id));
    if (!r) return cb && cb({ ok: false, error: "Room not found" });
    
    const idx = r.history.findIndex(m => m.id === payload.message_id);
    if (idx === -1) return cb && cb({ ok: false, error: "Message not found" });
    
    const msg = r.history[idx];
    const canDelete = msg.user_id === meta.userId || checkRole(r, meta.userId, 'admin');
    
    if (!canDelete) {
      return cb && cb({ ok: false, error: "Cannot delete" });
    }
    
    r.history.splice(idx, 1);
    io.to(String(payload.room_id)).emit("message:deleted", { message_id: payload.message_id });
    cb && cb({ ok: true });
  });

  socket.on("message:react", (payload = {}, cb) => {
    const meta = sockets.get(socket.id);
    if (!meta) return cb && cb({ ok: false, error: "Unauthenticated" });
    
    const r = rooms.get(String(payload.room_id));
    if (!r) return cb && cb({ ok: false, error: "Room not found" });
    
    const msg = r.history.find(m => m.id === payload.message_id);
    if (!msg) return cb && cb({ ok: false, error: "Message not found" });
    
    const emoji = sanitize(payload.emoji, 10);
    if (!msg.reactions[emoji]) msg.reactions[emoji] = [];
    
    if (payload.add) {
      if (!msg.reactions[emoji].includes(meta.userId)) {
        msg.reactions[emoji].push(meta.userId);
      }
    } else {
      msg.reactions[emoji] = msg.reactions[emoji].filter(uid => uid !== meta.userId);
      if (msg.reactions[emoji].length === 0) delete msg.reactions[emoji];
    }
    
    io.to(String(payload.room_id)).emit("message:reaction", {
      message_id: msg.id,
      reactions: msg.reactions
    });
    cb && cb({ ok: true });
  });

  socket.on("typing:start", (roomId) => {
    const meta = sockets.get(socket.id);
    if (!meta) return;
    
    const key = `${roomId}:${meta.userId}`;
    typingStatus.set(key, Date.now());
    
    socket.to(String(roomId)).emit("typing:status", {
      room_id: roomId,
      user_id: meta.userId,
      username: meta.username,
      typing: true
    });
    
    setTimeout(() => {
      if (typingStatus.get(key) && Date.now() - typingStatus.get(key) > 2500) {
        typingStatus.delete(key);
        socket.to(String(roomId)).emit("typing:status", {
          room_id: roomId,
          user_id: meta.userId,
          typing: false
        });
      }
    }, 3000);
  });

  socket.on("typing:stop", (roomId) => {
    const meta = sockets.get(socket.id);
    if (!meta) return;
    
    typingStatus.delete(`${roomId}:${meta.userId}`);
    socket.to(String(roomId)).emit("typing:status", {
      room_id: roomId,
      user_id: meta.userId,
      typing: false
    });
  });

  socket.on("presence:set", (status) => {
    const meta = sockets.get(socket.id);
    if (!meta) return;
    
    presence.set(meta.userId, {
      status: ['online', 'away', 'busy'].includes(status) ? status : 'online',
      lastSeen: Date.now()
    });
    
    io.emit("presence:update", [{
      userId: meta.userId,
      ...presence.get(meta.userId)
    }]);
  });

  socket.on("message:read", (payload = {}) => {
    const meta = sockets.get(socket.id);
    if (!meta) return;
    
    const r = rooms.get(String(payload.room_id));
    if (!r || !r.members.has(meta.userId)) return;
    
    r.unread.set(meta.userId, 0);
    
    socket.to(String(payload.room_id)).emit("message:read_receipt", {
      room_id: payload.room_id,
      user_id: meta.userId,
      message_id: payload.message_id
    });
  });

  socket.on("disconnect", (reason) => {
    const meta = sockets.get(socket.id);
    if (meta) {
      const u = users.get(meta.userId);
      if (u) {
        u.sockets.delete(socket.id);
        if (u.sockets.size === 0) {
          presence.set(meta.userId, { status: 'offline', lastSeen: Date.now() });
          io.emit("presence:update", [{
            userId: meta.userId,
            status: 'offline',
            lastSeen: Date.now()
          }]);
        }
      }
      sockets.delete(socket.id);
    }
    console.log("[chat] disconnected:", socket.id, reason);
  });
});

function notifyRoomMembers(room, excludeUserId = null) {
  for (const uid of room.members) {
    if (uid !== excludeUserId) {
      const u = users.get(uid);
      if (u) for (const sid of u.sockets) io.to(sid).emit("rooms:list", listRoomsFor(uid));
    }
  }
}

httpServer.listen(CHAT_PORT, "0.0.0.0", () => {
  console.log(`Secure Socket.IO chat server running on port ${CHAT_PORT}`);
});
