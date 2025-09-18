<?php require 'app/views/templates/header.php'; ?>
<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$userId     = (int)($_SESSION['id'] ?? 0);
$userName   = $_SESSION['username'] ?? ($_SESSION['full_name'] ?? 'User');
$USERS      = isset($data['users']) ? $data['users'] : [];
$ME         = isset($data['me']) ? (int)$data['me'] : 0;
$CHAT_TOKEN = isset($data['chat_token']) ? $data['chat_token'] : '';
?>
<meta name="tw-user-id" content="<?= $userId ?>">
<meta name="tw-user-name" content="<?= htmlspecialchars($userName, ENT_QUOTES) ?>">
<meta name="tw-chat-token" content="<?= htmlspecialchars($CHAT_TOKEN, ENT_QUOTES) ?>">

<style>
.chat-wrap{background:#f8fafc;min-height:100vh;}
.chat-app{display:grid;grid-template-columns:280px 1fr;gap:16px;}
@media(max-width:992px){.chat-app{grid-template-columns:1fr}}
.card{background:#fff;border-radius:.75rem;box-shadow:0 1px 3px rgba(0,0,0,.08)}
.p-3{padding:1rem}.p-2{padding:.75rem}.mb-2{margin-bottom:.5rem}.mb-3{margin-bottom:1rem}
.section-title{font-weight:700;margin:8px 0}
.list{max-height:72vh;overflow:auto}
.item{display:flex;gap:.5rem;align-items:center;cursor:pointer;padding:.5rem;border-radius:.5rem}
.item:hover{background:#f3f4f6}
.item.active{background:#eef2ff;border:1px solid #c7d2fe}
.badge{background:#eef2ff;border:1px solid #c7d2fe;color:#3730a3;border-radius:999px;padding:.1rem .5rem;font-size:.75rem}
.header{display:flex;align-items:center;justify-content:space-between}
.messages{height:60vh;overflow:auto;padding-right:.25rem}
.msg{margin:.35rem 0;max-width:70%}
.msg.you{margin-left:auto;text-align:right}
.bubble{display:inline-block;padding:.5rem .7rem;border-radius:1rem;background:#eef2ff}
.msg.you .bubble{background:#dcfce7}
.meta{font-size:.75rem;color:#6b7280;margin-top:.15rem}
.inputbar{display:flex;gap:.5rem}
.inputbar input{flex:1;border:1px solid #d1d5db;border-radius:.5rem;padding:.5rem .65rem}
.inputbar button{border:1px solid #d1d5db;border-radius:.5rem;padding:.5rem .9rem;background:#3b82f6;color:#fff}
.modal{position:fixed;inset:0;background:rgba(0,0,0,.4);display:none;align-items:center;justify-content:center}
.modal .box{background:#fff;border-radius:.75rem;min-width:320px;max-width:520px;padding:1rem}
.modal.show{display:flex}
</style>

<div class="chat-wrap">
  <div class="container-fluid px-4 py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <div>
        <div class="h1">Chat</div>
        <div class="sub">Direct messages and group chats in real time</div>
      </div>
      <button id="btnNewGroup" class="btn primary">New group</button>
    </div>

    <div class="chat-app">
      <div class="card p-3">
        <div class="section-title">People</div>
        <div id="people" class="list mb-3">
          <?php foreach ($USERS as $u): ?>
            <div class="item" data-uid="<?= (int)$u['id'] ?>">
              <span>👤</span>
              <span><?= htmlspecialchars($u['label'], ENT_QUOTES) ?><?= (int)$u['id']===$ME ? ' (you)' : '' ?></span>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="section-title">Rooms</div>
        <div id="rooms" class="list"></div>
      </div>

      <div class="card p-3">
        <div class="header mb-2">
          <div><strong id="roomTitle">Pick a person or room</strong></div>
          <div class="badge" id="roomType">—</div>
        </div>

        <div id="messages" class="messages card p-2 mb-2"></div>

        <div class="inputbar">
          <input id="msgInput" placeholder="Type a message…" />
          <button id="sendBtn">Send</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="groupModal" class="modal">
  <div class="box">
    <div class="h5 mb-2">Create group</div>
    <input id="groupName" class="form-control mb-2" placeholder="Group name" />
    <div style="max-height:220px;overflow:auto;border:1px solid #eee;border-radius:.5rem;padding:.5rem">
      <?php foreach ($USERS as $u): if ((int)$u['id'] === $ME) continue; ?>
        <label style="display:flex;gap:.5rem;align-items:center;margin:.25rem 0">
          <input type="checkbox" class="grp-user" value="<?= (int)$u['id'] ?>" />
          <span><?= htmlspecialchars($u['label'], ENT_QUOTES) ?></span>
        </label>
      <?php endforeach; ?>
    </div>
    <div class="d-flex gap-2 mt-3">
      <button id="createGroup" class="btn primary">Create</button>
      <button id="closeGroup" class="btn">Cancel</button>
    </div>
  </div>
</div>

<?php require 'app/views/templates/footer.php'; ?>

<!-- Socket.IO client -->
<script src="https://cdn.socket.io/4.7.5/socket.io.min.js" crossorigin="anonymous"></script>
<script>
// ---- identity from meta ----
const UID        = Number(document.querySelector('meta[name="tw-user-id"]')?.content || 0);
const UNAME      = document.querySelector('meta[name="tw-user-name"]')?.content || 'User';
const CHAT_TOKEN = document.querySelector('meta[name="tw-chat-token"]')?.content || '';

// ---- elements ----
const peopleEl = document.getElementById('people');
const roomsEl  = document.getElementById('rooms');
const msgsEl   = document.getElementById('messages');
const inputEl  = document.getElementById('msgInput');
const titleEl  = document.getElementById('roomTitle');
const typeEl   = document.getElementById('roomType');
let CURRENT_ROOM = null;

// ---- build proper Socket.IO URL for your Repl: https://<host>:3001 ----
function buildSocketURL () {
  const host  = location.hostname;
  const proto = location.protocol;
  const PORT  = 3001;
  return `${proto}//${host}:${PORT}`;
}
const nodeURL = buildSocketURL();
console.log('[chat] connecting to', nodeURL);

// ---- connect & AUTH (server expects it) ----
const socket = io(nodeURL, {
  path: '/socket.io',
  transports: ['websocket','polling'],
  reconnection: true,
  timeout: 20000,
  auth: CHAT_TOKEN ? { chat_token: CHAT_TOKEN } : undefined
});

socket.on('connect', () => {
  console.log('[chat] connected', socket.id);
  socket.emit('auth', { userId: UID, username: UNAME, chat_token: CHAT_TOKEN });
  socket.emit('rooms:refresh');
});

socket.on('connect_error', (e)=>console.warn('[chat] connect_error', e?.message||e));
socket.on('fatal', (m)=>{ alert(m?.error||'Chat error'); });

socket.on('rooms:list', (rooms)=>renderRooms(rooms));
socket.on('message:new', (m)=>{ if(Number(m.room_id)===Number(CURRENT_ROOM)) appendMsg(m); });

// ---- UI hooks ----
peopleEl.addEventListener('click', (e)=>{
  const item = e.target.closest('.item'); if(!item) return;
  const uid = parseInt(item.dataset.uid,10); if(!uid || uid===UID) return;
  socket.emit('dm:open', uid, (res)=>{
    if(res?.ok){ socket.emit('rooms:refresh'); openRoom({ id: res.room_id, is_group: 0, name: 'Direct Message' }); }
  });
});

function renderRooms(rooms){
  roomsEl.innerHTML = '';
  rooms.forEach(r=>{
    const div = document.createElement('div');
    div.className = 'item' + (String(r.id)===String(CURRENT_ROOM)?' active':'');
    const nm = r.is_group ? (r.name || ('Room '+r.id)) : 'Direct Message';
    div.innerHTML = `<span>${r.is_group?'👥':'💬'}</span><span><strong>${escapeHtml(nm)}</strong></span>`;
    div.onclick = ()=>openRoom(r);
    roomsEl.appendChild(div);
  });
}

function openRoom(r){
  CURRENT_ROOM = r.id;
  titleEl.textContent = r.is_group ? (r.name || ('Room '+r.id)) : 'Direct Message';
  typeEl.textContent  = r.is_group ? 'Group' : 'Direct';
  msgsEl.innerHTML = '<div class="small text-muted">Loading…</div>';
  socket.emit('room:join', r.id, (history)=>{
    msgsEl.innerHTML = '';
    (history||[]).forEach(appendMsg);
    scrollDown();
  });
}

document.getElementById('sendBtn').onclick = send;
inputEl.addEventListener('keydown', (e)=>{ if(e.key==='Enter') send(); });
function send(){
  const text = inputEl.value.trim();
  if(!text || !CURRENT_ROOM) return;
  socket.emit('message:send', { room_id: CURRENT_ROOM, body: text }, (res)=>{
    if(res?.ok) inputEl.value='';
  });
}

function appendMsg(m){
  const me = Number(m.user_id) === UID;
  const el = document.createElement('div');
  el.className = 'msg' + (me ? ' you' : '');
  el.innerHTML = `<div class="bubble">${escapeHtml(m.body)}</div>
                  <div class="meta">${escapeHtml(m.username ?? '')} · ${new Date(m.created_at||Date.now()).toLocaleTimeString()}</div>`;
  msgsEl.appendChild(el); scrollDown();
}
function scrollDown(){ msgsEl.scrollTop = msgsEl.scrollHeight; }
function escapeHtml(t=''){ const d=document.createElement('div'); d.textContent=t; return d.innerHTML; }

// group modal
const modal = document.getElementById('groupModal');
document.getElementById('btnNewGroup').onclick = ()=> modal.classList.add('show');
document.getElementById('closeGroup').onclick  = ()=> modal.classList.remove('show');
document.getElementById('createGroup').onclick = ()=>{
  const name = document.getElementById('groupName').value.trim();
  const ids = Array.from(document.querySelectorAll('.grp-user:checked')).map(c=>parseInt(c.value,10));
  if(!name || ids.length===0){ alert('Pick a name and at least one member'); return; }
  socket.emit('room:create', name, ids, (res)=>{
    if(res?.ok){ modal.classList.remove('show'); socket.emit('rooms:refresh'); openRoom({ id: res.room_id, is_group: 1, name }); }
    else alert(res?.error||'Failed to create');
  });
};
</script>
