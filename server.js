// server.js — Socket.IO only; PHP runs separately on :8000
const { createServer } = require('http');
const { Server } = require('socket.io');
const sqlite3 = require('sqlite3').verbose();

const PORT = process.env.NODE_PORT || 3001;
const httpServer = createServer();
const io = new Server(httpServer, {
  cors: { origin: "*" },
  transports: ['websocket', 'polling'],
  path: '/socket.io'
});

const db = new sqlite3.Database('database.db');
db.serialize(() => {
  db.run(`CREATE TABLE IF NOT EXISTS chat_rooms (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    is_group INTEGER DEFAULT 1,
    created_by INTEGER,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  )`);
  db.run(`CREATE TABLE IF NOT EXISTS chat_members (
    room_id INTEGER,
    user_id INTEGER,
    PRIMARY KEY (room_id, user_id)
  )`);
  db.run(`CREATE TABLE IF NOT EXISTS chat_messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    room_id INTEGER,
    user_id INTEGER,
    username TEXT,
    body TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  )`);
});

function listRoomsForUser(userId) {
  return new Promise((resolve, reject) => {
    const sql = `
      SELECT r.id, r.name, r.is_group,
             (SELECT body FROM chat_messages m WHERE m.room_id=r.id ORDER BY m.id DESC LIMIT 1) AS last_message,
             (SELECT created_at FROM chat_messages m WHERE m.room_id=r.id ORDER BY m.id DESC LIMIT 1) AS last_time
      FROM chat_rooms r
      JOIN chat_members cm ON cm.room_id=r.id
      WHERE cm.user_id = ?
      ORDER BY COALESCE(last_time, r.created_at) DESC
    `;
    db.all(sql, [userId], (e, rows) => e ? reject(e) : resolve(rows || []));
  });
}
function history(roomId, limit = 80) {
  return new Promise((resolve, reject) => {
    db.all(
      `SELECT id, room_id, user_id, username, body, created_at
       FROM chat_messages
       WHERE room_id=?
       ORDER BY id DESC LIMIT ?`,
      [roomId, limit],
      (e, rows) => e ? reject(e) : resolve((rows||[]).reverse())
    );
  });
}
function createGroup(name, creatorId, memberIds = []) {
  return new Promise((resolve, reject) => {
    db.run(
      `INSERT INTO chat_rooms (name, is_group, created_by) VALUES (?,?,?)`,
      [String(name).slice(0,80), 1, creatorId],
      function (e) {
        if (e) return reject(e);
        const roomId = this.lastID;
        const all = Array.from(new Set([creatorId, ...memberIds.map(n=>parseInt(n,10))]));
        const stmt = db.prepare(`INSERT OR IGNORE INTO chat_members (room_id, user_id) VALUES (?,?)`);
        all.forEach(uid => stmt.run(roomId, uid));
        stmt.finalize(err => err ? reject(err) : resolve(roomId));
      }
    );
  });
}
function ensureDm(userA, userB) {
  return new Promise((resolve, reject) => {
    const min = Math.min(userA, userB);
    const max = Math.max(userA, userB);
    const dmName = `DM:${min}-${max}`;
    db.get(`SELECT id FROM chat_rooms WHERE name=? AND is_group=0`, [dmName], (e, row) => {
      if (e) return reject(e);
      if (row?.id) return resolve(row.id);
      db.run(`INSERT INTO chat_rooms (name, is_group, created_by) VALUES (?,0,?)`, [dmName, min], function (e2) {
        if (e2) return reject(e2);
        const roomId = this.lastID;
        const stmt = db.prepare(`INSERT OR IGNORE INTO chat_members (room_id, user_id) VALUES (?,?)`);
        stmt.run(roomId, min); stmt.run(roomId, max);
        stmt.finalize(err => err ? reject(err) : resolve(roomId));
      });
    });
  });
}

io.on('connection', async (socket) => {
  const auth = socket.handshake.auth || {};
  const user_id = parseInt(auth.user_id, 10);
  const username = String(auth.username || '').trim() || `User${user_id || ''}`;

  if (!user_id) {
    socket.emit('error', 'Not authenticated');
    return socket.disconnect(true);
  }

  socket.data.user = { id: user_id, name: username };
  socket.emit('rooms:list', await listRoomsForUser(user_id));

  socket.on('rooms:refresh', async () => {
    socket.emit('rooms:list', await listRoomsForUser(user_id));
  });

  socket.on('room:join', async (roomId, ack) => {
    roomId = parseInt(roomId, 10);
    socket.join(`room:${roomId}`);
    const hist = await history(roomId, 80);
    ack && ack(hist);
  });

  socket.on('message:send', (payload, ack) => {
    const { room_id, body } = payload || {};
    if (!room_id || !body) return;
    const clean = String(body).slice(0, 2000);
    db.run(
      `INSERT INTO chat_messages (room_id, user_id, username, body) VALUES (?,?,?,?)`,
      [room_id, user_id, username, clean],
      function (e) {
        if (e) return ack && ack({ ok:false, error:e.message });
        const msg = { id:this.lastID, room_id, user_id, username, body: clean, created_at: new Date().toISOString() };
        io.to(`room:${room_id}`).emit('message:new', msg);
        ack && ack({ ok:true, message: msg });
      }
    );
  });

  socket.on('room:create', async (name, memberIds = [], ack) => {
    try {
      const roomId = await createGroup(name, user_id, memberIds);
      socket.emit('rooms:list', await listRoomsForUser(user_id));
      ack && ack({ ok:true, room_id: roomId });
    } catch (e) {
      ack && ack({ ok:false, error: e.message });
    }
  });

  socket.on('dm:open', async (toUserId, ack) => {
    try {
      const roomId = await ensureDm(user_id, parseInt(toUserId,10));
      socket.emit('rooms:list', await listRoomsForUser(user_id));
      ack && ack({ ok:true, room_id: roomId });
    } catch (e) {
      ack && ack({ ok:false, error:e.message });
    }
  });
});

httpServer.listen(PORT, () => {
  console.log(`Socket.IO listening on :${PORT}`);
});
