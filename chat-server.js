import http from "http";
import { Server } from "socket.io";
import sqlite3 from "sqlite3";

const CHAT_PORT = Number(process.env.CHAT_PORT || 3001);

// --- SQLite setup -----------------------------------------------------------
sqlite3.verbose();
const db = new sqlite3.Database("./database.db");

// Promise helpers
const run = (sql, params = []) =>
  new Promise((resolve, reject) => {
    db.run(sql, params, function (err) {
      if (err) reject(err);
      else resolve(this);
    });
  });

const get = (sql, params = []) =>
  new Promise((resolve, reject) => {
    db.get(sql, params, (err, row) => (err ? reject(err) : resolve(row)));
  });

const all = (sql, params = []) =>
  new Promise((resolve, reject) => {
    db.all(sql, params, (err, rows) => (err ? reject(err) : resolve(rows)));
  });

// Create tables if they don’t exist
await run(`
CREATE TABLE IF NOT EXISTS chat_rooms (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT,
  is_group INTEGER DEFAULT 1,
  created_by INTEGER,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)`);

await run(`
CREATE TABLE IF NOT EXISTS chat_members (
  room_id INTEGER,
  user_id INTEGER,
  PRIMARY KEY (room_id, user_id)
)`);

await run(`
CREATE TABLE IF NOT EXISTS chat_messages (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  room_id INTEGER,
  user_id INTEGER,
  username TEXT,
  body TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)`);

// --- HTTP + Socket.IO -------------------------------------------------------
const httpServer = http.createServer((req, res) => {
  if (req.url === "/healthz") {
    res.writeHead(200, { "content-type": "text/plain" });
    return res.end("ok");
  }
  res.writeHead(200, { "content-type": "text/plain" });
  res.end("Socket.IO chat running");
});

const io = new Server(httpServer, {
  path: "/socket.io",
  transports: ["websocket", "polling"],
  cors: {
    // accept all origins; Replit preview hosts rotate
    origin: (_origin, cb) => cb(null, true),
    credentials: true
  }
});

io.on("connection", (socket) => {
  const auth = socket.handshake.auth || {};
  const userId = Number(auth.user_id || 0);
  const username = String(auth.username || "User").slice(0, 80);

  if (!userId) {
    socket.disconnect(true);
    return;
  }

  // immediately send rooms
  sendRooms(userId, socket);

  socket.on("rooms:refresh", () => sendRooms(userId, socket));

  socket.on("room:join", async (roomId, ack) => {
    try {
      roomId = Number(roomId);
      // join socket.io room
      socket.join(`room:${roomId}`);
      // fetch history
      const history = await all(
        `SELECT id, room_id, user_id, username, body, created_at
         FROM chat_messages WHERE room_id = ? ORDER BY id ASC LIMIT 200`,
        [roomId]
      );
      ack?.(history || []);
    } catch (e) {
      ack?.([]);
    }
  });

  socket.on("message:send", async (payload, ack) => {
    try {
      const roomId = Number(payload?.room_id);
      const body = String(payload?.body || "").trim().slice(0, 5000);
      if (!roomId || !body) return ack?.({ ok: false });

      // ensure membership
      const m = await get(
        `SELECT 1 FROM chat_members WHERE room_id=? AND user_id=?`,
        [roomId, userId]
      );
      if (!m) return ack?.({ ok: false, error: "Not a member" });

      const rs = await run(
        `INSERT INTO chat_messages (room_id, user_id, username, body)
         VALUES (?, ?, ?, ?)`,
        [roomId, userId, username, body]
      );

      const msg = await get(
        `SELECT id, room_id, user_id, username, body, created_at
           FROM chat_messages WHERE id = ?`,
        [rs.lastID]
      );

      io.to(`room:${roomId}`).emit("message:new", msg);
      ack?.({ ok: true, id: rs.lastID });
    } catch (e) {
      ack?.({ ok: false, error: "send failed" });
    }
  });

  socket.on("room:create", async (name, memberIds, ack) => {
    try {
      name = String(name || "").trim().slice(0, 120);
      if (!name) return ack?.({ ok: false, error: "Bad name" });
      const ids = Array.from(new Set([userId, ...(memberIds || [])]))
        .map((x) => Number(x))
        .filter(Boolean);

      const ins = await run(
        `INSERT INTO chat_rooms (name, is_group, created_by) VALUES (?, 1, ?)`,
        [name, userId]
      );
      const roomId = ins.lastID;

      const ps = ids.map((id) =>
        run(`INSERT OR IGNORE INTO chat_members (room_id, user_id) VALUES (?,?)`, [
          roomId,
          id
        ])
      );
      await Promise.all(ps);

      sendRooms(userId, socket);
      ack?.({ ok: true, room_id: roomId });
    } catch (e) {
      ack?.({ ok: false, error: "create failed" });
    }
  });

  socket.on("dm:open", async (otherUserId, ack) => {
    try {
      const a = Math.min(userId, Number(otherUserId));
      const b = Math.max(userId, Number(otherUserId));
      if (!a || !b || a === b) return ack?.({ ok: false });

      // look for existing DM with exactly these two members
      const existing = await get(
        `SELECT r.id
           FROM chat_rooms r
           JOIN chat_members m1 ON m1.room_id = r.id AND m1.user_id = ?
           JOIN chat_members m2 ON m2.room_id = r.id AND m2.user_id = ?
          WHERE r.is_group = 0
          LIMIT 1`,
        [a, b]
      );

      if (existing?.id) {
        sendRooms(userId, socket);
        return ack?.({ ok: true, room_id: existing.id });
      }

      const ins = await run(
        `INSERT INTO chat_rooms (name, is_group, created_by)
         VALUES (?, 0, ?)`,
        [`DM:${a}-${b}`, userId]
      );
      const roomId = ins.lastID;

      await run(
        `INSERT OR IGNORE INTO chat_members (room_id, user_id) VALUES (?,?)`,
        [roomId, a]
      );
      await run(
        `INSERT OR IGNORE INTO chat_members (room_id, user_id) VALUES (?,?)`,
        [roomId, b]
      );

      sendRooms(userId, socket);
      ack?.({ ok: true, room_id: roomId });
    } catch (e) {
      ack?.({ ok: false, error: "dm failed" });
    }
  });
});

async function sendRooms(userId, socket) {
  const rows = await all(
    `SELECT r.id, r.name, r.is_group
       FROM chat_rooms r
       JOIN chat_members m ON m.room_id = r.id
      WHERE m.user_id = ?
      ORDER BY r.created_at DESC, r.id DESC`,
    [userId]
  );
  socket.emit("rooms:list", rows || []);
}

httpServer.listen(CHAT_PORT, "0.0.0.0", () => {
  console.log(`Socket.IO chat running on ${CHAT_PORT}`);
});
