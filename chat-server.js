// chat-server.js — Socket.IO server with in-memory rooms/messages for Replit

const http = require("http");
const { Server } = require("socket.io");

const CHAT_PORT = process.env.CHAT_PORT || 3001;

// ---- In-memory store (dev) ----
let nextRoomId = 1;
const users = new Map();   // userId -> { username, sockets:Set<sid> }
const sockets = new Map(); // sid -> { userId, username }
const rooms = new Map();   // roomId -> { id, is_group, name, members:Set<userId>, history:Array }

// DMs
function dmKey(a, b) {
  const [x, y] = [Number(a), Number(b)].sort((m, n) => m - n);
  return `dm:${x}-${y}`;
}
const dmIndex = new Map(); // dmKey -> roomId

function ensureDM(u1, u2) {
  const key = dmKey(u1, u2);
  if (dmIndex.has(key)) return dmIndex.get(key);
  const id = String(nextRoomId++);
  rooms.set(id, { id, is_group: 0, name: null, members: new Set([+u1, +u2]), history: [] });
  dmIndex.set(key, id);
  return id;
}

function listRoomsFor(userId) {
  const out = [];
  for (const r of rooms.values()) {
    if (r.members.has(+userId)) out.push({ id: r.id, is_group: r.is_group, name: r.name });
  }
  return out;
}

function historyOf(roomId) {
  const r = rooms.get(String(roomId));
  return r ? r.history.slice(-200) : [];
}

// ---- HTTP + IO ----
const httpServer = http.createServer((req, res) => {
  res.writeHead(200, { "Content-Type": "text/plain" });
  res.end("Socket.IO chat running\n");
});

const io = new Server(httpServer, {
  cors: {
    origin: (origin, cb) => cb(null, true), // allow all origins in Replit dev
    methods: ["GET", "POST"],
    credentials: true
  },
  transports: ["websocket", "polling"],
  allowEIO3: false
});

// ---- Socket handlers ----
io.on("connection", (socket) => {
  console.log("[chat] client connected:", socket.id, "origin:", socket.handshake.headers.origin);

  socket.on("auth", (payload = {}) => {
    const userId = Number(payload.userId || 0);
    const username = String(payload.username || "User");
    if (!userId) {
      socket.emit("fatal", { error: "Missing userId" });
      return;
    }
    sockets.set(socket.id, { userId, username });
    if (!users.has(userId)) users.set(userId, { username, sockets: new Set() });
    users.get(userId).username = username;
    users.get(userId).sockets.add(socket.id);
    console.log("[chat] authed:", userId, username);
    socket.emit("rooms:list", listRoomsFor(userId));
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
    cb && cb(historyOf(roomId));
  });

  socket.on("room:create", (name, memberIds = [], cb) => {
    const meta = sockets.get(socket.id);
    if (!meta) return cb && cb({ ok: false, error: "Unauthenticated" });
    const id = String(nextRoomId++);
    const members = new Set([meta.userId, ...memberIds.map(Number)]);
    rooms.set(id, { id, is_group: 1, name: String(name || `Room ${id}`), members, history: [] });
    cb && cb({ ok: true, room_id: id });
    // notify members to refresh room list
    for (const uid of members) {
      const u = users.get(uid);
      if (u) for (const sid of u.sockets) io.to(sid).emit("rooms:list", listRoomsFor(uid));
    }
  });

  socket.on("message:send", (payload = {}, cb) => {
    const meta = sockets.get(socket.id);
    const { room_id, body } = payload || {};
    if (!meta) return cb && cb({ ok: false, error: "Unauthenticated" });
    const r = rooms.get(String(room_id));
    if (!r || !r.members.has(meta.userId)) return cb && cb({ ok: false, error: "No access" });

    const msg = {
      id: Date.now() + ":" + Math.random().toString(36).slice(2),
      room_id: String(room_id),
      user_id: meta.userId,
      username: meta.username,
      body: String(body || ""),
      created_at: Date.now()
    };
    r.history.push(msg);
    if (r.history.length > 500) r.history.shift();
    io.to(String(room_id)).emit("message:new", msg);
    cb && cb({ ok: true });
  });

  socket.on("disconnect", (reason) => {
    const meta = sockets.get(socket.id);
    if (meta) {
      const u = users.get(meta.userId);
      if (u) u.sockets.delete(socket.id);
      sockets.delete(socket.id);
    }
    console.log("[chat] disconnected:", socket.id, reason);
  });
});

httpServer.listen(CHAT_PORT, "0.0.0.0", () => {
  console.log(`Secure Socket.IO chat server running on port ${CHAT_PORT}`);
});
