import http from "http";
import { Server } from "socket.io";
import sqlite3 from "sqlite3";
import mysql from "mysql2/promise";
import crypto from "crypto";

const CHAT_PORT = Number(process.env.CHAT_PORT || 3001);

// --- Database connections ---------------------------------------------------
// SQLite for chat data
sqlite3.verbose();
const db = new sqlite3.Database("./database.db");

// MySQL for authentication (using same config as PHP)
const mysqlConfig = {
  host: process.env.DB_HOST || 'e7eh7.h.filess.io',
  user: process.env.DB_USER || 'TimeWise_bushnearby',
  password: process.env.DB_PASS,
  database: process.env.DB_DATABASE || 'TimeWise_bushnearby',
  port: Number(process.env.DB_PORT || 3305),
  connectTimeout: 20000,
  acquireTimeout: 20000,
};

// Promise helpers for SQLite
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

// Create SQLite tables if they don't exist
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

// --- Authentication Functions -----------------------------------------------
async function validateAuthToken(token) {
  if (!token) return null;
  
  try {
    const connection = await mysql.createConnection(mysqlConfig);
    
    // Hash the token and check if it exists and is not expired
    const tokenHash = crypto.createHash('sha256').update(token).digest('hex');
    
    const [rows] = await connection.execute(
      `SELECT ct.user_id, u.username, u.full_name, u.is_admin
       FROM chat_tokens ct 
       JOIN users u ON ct.user_id = u.id 
       WHERE ct.token_hash = ? AND ct.expires_at > NOW()`,
      [tokenHash]
    );
    
    await connection.end();
    
    if (rows.length === 0) return null;
    
    const user = rows[0];
    return {
      id: user.user_id,
      username: user.username,
      full_name: user.full_name,
      is_admin: user.is_admin
    };
  } catch (error) {
    console.error("Token validation error:", error.message);
    return null;
  }
}

async function verifyRoomMembership(userId, roomId) {
  try {
    const member = await get(
      `SELECT 1 FROM chat_members WHERE room_id = ? AND user_id = ?`,
      [roomId, userId]
    );
    return !!member;
  } catch (error) {
    console.error("Membership verification error:", error.message);
    return false;
  }
}

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
    origin: (_origin, cb) => cb(null, true),
    credentials: true
  }
});

io.on("connection", async (socket) => {
  const auth = socket.handshake.auth || {};
  const chatToken = auth.chat_token;
  
  // Validate authentication token
  const user = await validateAuthToken(chatToken);
  if (!user) {
    console.log("Invalid or missing chat token, disconnecting");
    socket.emit("fatal", { error: "Authentication failed" });
    socket.disconnect(true);
    return;
  }
  
  const userId = user.id;
  const username = user.full_name || user.username;
  
  console.log(`User ${username} (${userId}) authenticated successfully`);
  
  // Send initial room list
  sendRooms(userId, socket);

  socket.on("rooms:refresh", () => sendRooms(userId, socket));

  socket.on("room:join", async (roomId, ack) => {
    try {
      roomId = Number(roomId);
      
      // Verify user is a member of the room before allowing join
      const isMember = await verifyRoomMembership(userId, roomId);
      if (!isMember) {
        console.log(`User ${userId} attempted to join room ${roomId} without membership`);
        return ack?.({ error: "Access denied" });
      }
      
      // Join socket.io room
      socket.join(`room:${roomId}`);
      
      // Fetch and send message history
      const history = await all(
        `SELECT id, room_id, user_id, username, body, created_at
         FROM chat_messages WHERE room_id = ? ORDER BY id ASC LIMIT 200`,
        [roomId]
      );
      ack?.(history || []);
    } catch (e) {
      console.error("Room join error:", e.message);
      ack?.({ error: "Join failed" });
    }
  });

  socket.on("message:send", async (payload, ack) => {
    try {
      const roomId = Number(payload?.room_id);
      const body = String(payload?.body || "").trim().slice(0, 5000);
      if (!roomId || !body) return ack?.({ ok: false, error: "Invalid message" });

      // Verify membership before allowing message send
      const isMember = await verifyRoomMembership(userId, roomId);
      if (!isMember) {
        console.log(`User ${userId} attempted to send message to room ${roomId} without membership`);
        return ack?.({ ok: false, error: "Access denied" });
      }

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
      console.error("Message send error:", e.message);
      ack?.({ ok: false, error: "Send failed" });
    }
  });

  socket.on("room:create", async (name, memberIds, ack) => {
    try {
      name = String(name || "").trim().slice(0, 120);
      if (!name) return ack?.({ ok: false, error: "Group name required" });
      
      // Validate that all member IDs are valid users
      const ids = Array.from(new Set([userId, ...(memberIds || [])]))
        .map((x) => Number(x))
        .filter(Boolean);

      if (ids.length < 2) {
        return ack?.({ ok: false, error: "At least one other member required" });
      }

      // Verify all member IDs exist in users table
      try {
        const connection = await mysql.createConnection(mysqlConfig);
        const placeholders = ids.map(() => '?').join(',');
        const [userRows] = await connection.execute(
          `SELECT id FROM users WHERE id IN (${placeholders})`,
          ids
        );
        await connection.end();
        
        if (userRows.length !== ids.length) {
          return ack?.({ ok: false, error: "Invalid user selected" });
        }
      } catch (dbError) {
        console.error("User validation error:", dbError.message);
        return ack?.({ ok: false, error: "Validation failed" });
      }

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
      console.error("Room creation error:", e.message);
      ack?.({ ok: false, error: "Creation failed" });
    }
  });

  socket.on("dm:open", async (otherUserId, ack) => {
    try {
      const otherId = Number(otherUserId);
      if (!otherId || otherId === userId) return ack?.({ ok: false, error: "Invalid user" });

      // Verify the other user exists
      try {
        const connection = await mysql.createConnection(mysqlConfig);
        const [userRows] = await connection.execute(
          `SELECT id FROM users WHERE id = ?`,
          [otherId]
        );
        await connection.end();
        
        if (userRows.length === 0) {
          return ack?.({ ok: false, error: "User not found" });
        }
      } catch (dbError) {
        console.error("User verification error:", dbError.message);
        return ack?.({ ok: false, error: "Verification failed" });
      }

      const a = Math.min(userId, otherId);
      const b = Math.max(userId, otherId);

      // Look for existing DM with exactly these two members
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
      console.error("DM creation error:", e.message);
      ack?.({ ok: false, error: "DM failed" });
    }
  });
});

async function sendRooms(userId, socket) {
  try {
    const rows = await all(
      `SELECT r.id, r.name, r.is_group
         FROM chat_rooms r
         JOIN chat_members m ON m.room_id = r.id
        WHERE m.user_id = ?
        ORDER BY r.created_at DESC, r.id DESC`,
      [userId]
    );
    socket.emit("rooms:list", rows || []);
  } catch (error) {
    console.error("Error sending rooms:", error.message);
    socket.emit("rooms:list", []);
  }
}

httpServer.listen(CHAT_PORT, "0.0.0.0", () => {
  console.log(`Secure Socket.IO chat server running on port ${CHAT_PORT}`);
});