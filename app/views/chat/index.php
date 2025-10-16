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
.chat-app{display:grid;grid-template-columns:320px 1fr;gap:16px;height:calc(100vh - 120px);}
@media(max-width:992px){.chat-app{grid-template-columns:1fr;height:auto;}}
.card{background:#fff;border-radius:.75rem;box-shadow:0 1px 3px rgba(0,0,0,.08);overflow:hidden;}
.sidebar{display:flex;flex-direction:column;height:100%;}
.sidebar-header{padding:1rem;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;gap:.5rem;}
.sidebar-search{padding:.5rem 1rem;}
.sidebar-search input{width:100%;border:1px solid #d1d5db;border-radius:.5rem;padding:.5rem;}
.section-title{font-weight:700;margin:12px 1rem 8px;font-size:.9rem;color:#6b7280;}
.list{flex:1;overflow-y:auto;padding:0 .5rem;}
.item{display:flex;gap:.5rem;align-items:center;cursor:pointer;padding:.65rem;border-radius:.5rem;margin:.15rem 0;position:relative;}
.item:hover{background:#f3f4f6;}
.item.active{background:#eef2ff;border:1px solid #c7d2fe;}
.item .info{flex:1;min-width:0;}
.item .name{font-weight:600;font-size:.9rem;}
.item .preview{font-size:.8rem;color:#6b7280;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.badge{background:#eef2ff;color:#3730a3;border-radius:999px;padding:.1rem .4rem;font-size:.7rem;font-weight:600;}
.unread-badge{background:#ef4444;color:#fff;border-radius:999px;padding:.1rem .5rem;font-size:.7rem;min-width:20px;text-align:center;}
.status-dot{width:8px;height:8px;border-radius:50%;position:absolute;bottom:4px;right:4px;border:2px solid #fff;}
.status-online{background:#10b981;}.status-away{background:#f59e0b;}.status-offline{background:#6b7280;}
.chat-main{display:flex;flex-direction:column;height:100%;}
.chat-header{padding:1rem;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;justify-content:space-between;}
.chat-header .left{display:flex;align-items:center;gap:.5rem;}
.chat-header .actions{display:flex;gap:.5rem;}
.messages{flex:1;overflow-y:auto;padding:1rem;background:#f9fafb;}
.msg{margin:.5rem 0;display:flex;gap:.5rem;align-items:flex-start;max-width:80%;}
.msg.you{margin-left:auto;flex-direction:row-reverse;}
.msg .avatar{width:32px;height:32px;border-radius:50%;background:#e5e7eb;display:flex;align-items:center;justify-content:center;font-size:.9rem;flex-shrink:0;}
.msg-content{flex:1;min-width:0;}
.bubble{display:inline-block;padding:.65rem .85rem;border-radius:1rem;background:#fff;box-shadow:0 1px 2px rgba(0,0,0,.05);max-width:100%;word-wrap:break-word;}
.msg.you .bubble{background:#3b82f6;color:#fff;}
.msg.you .meta{text-align:right;}
.meta{font-size:.75rem;color:#6b7280;margin-top:.25rem;display:flex;gap:.5rem;align-items:center;}
.msg.edited .meta::after{content:'(edited)';font-style:italic;}
.reactions{display:flex;gap:.25rem;margin-top:.25rem;flex-wrap:wrap;}
.reaction{background:#f3f4f6;border:1px solid #e5e7eb;border-radius:12px;padding:.15rem .4rem;font-size:.75rem;cursor:pointer;display:flex;align-items:center;gap:.25rem;}
.reaction:hover{background:#e5e7eb;}
.reaction.me{background:#dbeafe;border-color:#3b82f6;}
.msg-actions{display:none;gap:.25rem;margin-top:.25rem;}
.msg:hover .msg-actions{display:flex;}
.msg-actions button{background:#f3f4f6;border:none;border-radius:.375rem;padding:.25rem .5rem;font-size:.75rem;cursor:pointer;}
.msg-actions button:hover{background:#e5e7eb;}
.typing{padding:.5rem 1rem;font-size:.85rem;color:#6b7280;font-style:italic;}
.inputbar{padding:1rem;border-top:1px solid #e5e7eb;display:flex;gap:.5rem;align-items:flex-end;}
.inputbar textarea{flex:1;border:1px solid #d1d5db;border-radius:.5rem;padding:.65rem;resize:none;font-family:inherit;min-height:44px;max-height:120px;}
.inputbar .actions{display:flex;gap:.25rem;}
.inputbar button{border:none;border-radius:.5rem;padding:.65rem 1rem;cursor:pointer;font-weight:500;}
.btn-primary{background:#3b82f6;color:#fff;}
.btn-primary:hover{background:#2563eb;}
.btn-secondary{background:#f3f4f6;color:#374151;}
.btn-secondary:hover{background:#e5e7eb;}
.modal{position:fixed;inset:0;background:rgba(0,0,0,.5);display:none;align-items:center;justify-content:center;z-index:1000;}
.modal.show{display:flex;}
.modal-content{background:#fff;border-radius:.75rem;min-width:400px;max-width:600px;max-height:80vh;overflow:hidden;display:flex;flex-direction:column;}
.modal-header{padding:1.25rem;border-bottom:1px solid #e5e7eb;font-weight:700;font-size:1.1rem;}
.modal-body{padding:1.25rem;overflow-y:auto;flex:1;}
.modal-footer{padding:1rem 1.25rem;border-top:1px solid #e5e7eb;display:flex;gap:.5rem;justify-content:flex-end;}
.form-group{margin-bottom:1rem;}
.form-group label{display:block;font-weight:600;margin-bottom:.5rem;font-size:.9rem;}
.form-group input,.form-group textarea,.form-group select{width:100%;border:1px solid #d1d5db;border-radius:.5rem;padding:.65rem;font-family:inherit;}
.member-list{border:1px solid #e5e7eb;border-radius:.5rem;max-height:300px;overflow-y:auto;}
.member-item{display:flex;align-items:center;gap:.5rem;padding:.65rem;border-bottom:1px solid #f3f4f6;}
.member-item:last-child{border-bottom:none;}
.member-item .info{flex:1;}
.member-item .role{font-size:.75rem;color:#6b7280;}
.reconnecting{background:#fef3c7;color:#92400e;padding:.5rem 1rem;text-align:center;font-size:.85rem;}
.error-state{padding:2rem;text-align:center;color:#6b7280;}
.empty-state{padding:3rem;text-align:center;color:#9ca3af;}
.attachment{background:#f3f4f6;border:1px solid #e5e7eb;border-radius:.5rem;padding:.5rem;margin-top:.5rem;display:inline-flex;align-items:center;gap:.5rem;max-width:100%;}
.attachment img{max-width:200px;border-radius:.375rem;}
</style>

<div class="chat-wrap">
  <div class="container-fluid px-4 py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <div>
        <div class="h1">Chat</div>
        <div class="sub">Real-time messaging and collaboration</div>
      </div>
      <button id="btnNewGroup" class="btn primary">New Group</button>
    </div>

    <div id="reconnectingBanner" class="reconnecting" style="display:none;">Reconnecting to chat...</div>

    <div class="chat-app">
      <div class="card sidebar">
        <div class="sidebar-header">
          <span style="font-size:1.2rem;">💬</span>
          <span style="font-weight:600;">Messages</span>
          <div style="margin-left:auto;">
            <select id="statusSelect" style="border:1px solid #d1d5db;border-radius:.375rem;padding:.25rem .5rem;font-size:.85rem;">
              <option value="online">🟢 Online</option>
              <option value="away">🟡 Away</option>
              <option value="busy">🔴 Busy</option>
            </select>
          </div>
        </div>
        
        <div class="sidebar-search">
          <input id="searchInput" type="text" placeholder="Search messages..." />
        </div>

        <div class="section-title">People</div>
        <div id="people" class="list">
          <?php foreach ($USERS as $u): ?>
            <div class="item" data-uid="<?= (int)$u['id'] ?>" data-name="<?= htmlspecialchars($u['label'], ENT_QUOTES) ?>">
              <span>👤</span>
              <div class="info">
                <div class="name"><?= htmlspecialchars($u['label'], ENT_QUOTES) ?><?= (int)$u['id']===$ME ? ' (you)' : '' ?></div>
              </div>
              <div class="status-dot status-offline" data-uid="<?= (int)$u['id'] ?>"></div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="section-title">Rooms</div>
        <div id="rooms" class="list"></div>
      </div>

      <div class="card chat-main">
        <div class="chat-header">
          <div class="left">
            <strong id="roomTitle">Select a conversation</strong>
            <span class="badge" id="roomType" style="display:none;">—</span>
          </div>
          <div class="actions">
            <button id="btnRoomSettings" class="btn-secondary" style="display:none;">⚙️ Settings</button>
            <button id="btnMute" class="btn-secondary" style="display:none;">🔕</button>
          </div>
        </div>

        <div id="messages" class="messages">
          <div class="empty-state">
            <div style="font-size:3rem;margin-bottom:1rem;">💬</div>
            <div>Select a person or room to start chatting</div>
          </div>
        </div>

        <div id="typingIndicator" class="typing" style="display:none;"></div>

        <div class="inputbar">
          <div class="actions">
            <button id="btnAttach" class="btn-secondary" title="Attach file">📎</button>
            <button id="btnEmoji" class="btn-secondary" title="Emoji">😊</button>
          </div>
          <textarea id="msgInput" placeholder="Type a message..." rows="1"></textarea>
          <button id="sendBtn" class="btn-primary">Send</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="groupModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">Create Group</div>
    <div class="modal-body">
      <div class="form-group">
        <label>Group Name</label>
        <input id="groupName" type="text" placeholder="Enter group name" />
      </div>
      <div class="form-group">
        <label>Description (optional)</label>
        <textarea id="groupDesc" rows="2" placeholder="Describe this group"></textarea>
      </div>
      <div class="form-group">
        <label>Select Members</label>
        <div class="member-list">
          <?php foreach ($USERS as $u): if ((int)$u['id'] === $ME) continue; ?>
            <label class="member-item">
              <input type="checkbox" class="grp-user" value="<?= (int)$u['id'] ?>" />
              <div class="info">
                <div><?= htmlspecialchars($u['label'], ENT_QUOTES) ?></div>
              </div>
            </label>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button id="createGroup" class="btn-primary">Create Group</button>
      <button id="closeGroup" class="btn-secondary">Cancel</button>
    </div>
  </div>
</div>

<div id="settingsModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">Room Settings</div>
    <div class="modal-body">
      <div class="form-group">
        <label>Room Name</label>
        <input id="settingsName" type="text" />
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea id="settingsDesc" rows="2"></textarea>
      </div>
      <div class="form-group">
        <label>Avatar URL (optional)</label>
        <input id="settingsAvatar" type="text" placeholder="https://..." />
      </div>
      <div class="form-group">
        <label>Members</label>
        <div id="membersList" class="member-list"></div>
      </div>
      <div class="form-group">
        <label>Add Member</label>
        <select id="addMemberSelect">
          <option value="">-- Select a person --</option>
        </select>
        <button id="btnAddMember" class="btn-secondary" style="margin-top:.5rem;">Add</button>
      </div>
    </div>
    <div class="modal-footer">
      <button id="saveSettings" class="btn-primary">Save Changes</button>
      <button id="btnLeaveRoom" class="btn-secondary" style="margin-right:auto;">Leave Room</button>
      <button id="btnDeleteRoom" class="btn-secondary" style="background:#ef4444;color:#fff;display:none;">Delete Room</button>
      <button id="closeSettings" class="btn-secondary">Close</button>
    </div>
  </div>
</div>

<input type="file" id="fileInput" style="display:none;" accept="image/*,application/pdf,.doc,.docx" />

<?php require 'app/views/templates/footer.php'; ?>

<script src="https://cdn.socket.io/4.7.5/socket.io.min.js" crossorigin="anonymous"></script>
<script>
(function() {
  const UID = Number(document.querySelector('meta[name="tw-user-id"]')?.content || 0);
  const UNAME = document.querySelector('meta[name="tw-user-name"]')?.content || 'User';
  const CHAT_TOKEN = document.querySelector('meta[name="tw-chat-token"]')?.content || '';

  const el = {
    people: document.getElementById('people'),
    rooms: document.getElementById('rooms'),
    messages: document.getElementById('messages'),
    input: document.getElementById('msgInput'),
    title: document.getElementById('roomTitle'),
    type: document.getElementById('roomType'),
    typing: document.getElementById('typingIndicator'),
    reconnecting: document.getElementById('reconnectingBanner'),
    searchInput: document.getElementById('searchInput'),
    statusSelect: document.getElementById('statusSelect'),
    btnSettings: document.getElementById('btnRoomSettings'),
    btnMute: document.getElementById('btnMute')
  };

  class ChatManager {
    constructor() {
      this.socket = null;
      this.currentRoom = null;
      this.currentRoomData = null;
      this.rooms = [];
      this.messageQueue = [];
      this.eventHandlers = new Map();
      this.reconnectAttempts = 0;
      this.maxReconnectDelay = 30000;
      this.typingTimer = null;
      this.isTyping = false;
      this.editingMessage = null;
      this.presence = new Map();
      this.searchQuery = '';
      this.init();
    }

    init() {
      this.connect();
      this.setupEventListeners();
    }

    connect() {
      const url = `${location.protocol}//${location.hostname}:3001`;
      console.log('[ChatManager] Connecting to', url);

      this.socket = io(url, {
        path: '/socket.io',
        transports: ['websocket', 'polling'],
        reconnection: false,
        timeout: 20000,
        auth: CHAT_TOKEN ? { chat_token: CHAT_TOKEN } : undefined
      });

      this.socket.on('connect', () => this.onConnect());
      this.socket.on('connect_error', (e) => this.onConnectError(e));
      this.socket.on('disconnect', (reason) => this.onDisconnect(reason));
      this.setupSocketListeners();
    }

    onConnect() {
      console.log('[ChatManager] Connected');
      el.reconnecting.style.display = 'none';
      this.reconnectAttempts = 0;
      this.socket.emit('auth', { userId: UID, username: UNAME, chat_token: CHAT_TOKEN });
      this.processMessageQueue();
    }

    onConnectError(e) {
      console.warn('[ChatManager] Connection error:', e?.message || e);
      this.scheduleReconnect();
    }

    onDisconnect(reason) {
      console.log('[ChatManager] Disconnected:', reason);
      if (reason === 'io server disconnect' || reason === 'io client disconnect') return;
      el.reconnecting.style.display = 'block';
      this.scheduleReconnect();
    }

    scheduleReconnect() {
      this.reconnectAttempts++;
      const delay = Math.min(1000 * Math.pow(2, this.reconnectAttempts), this.maxReconnectDelay);
      console.log(`[ChatManager] Reconnecting in ${delay}ms (attempt ${this.reconnectAttempts})`);
      setTimeout(() => this.connect(), delay);
    }

    setupSocketListeners() {
      this.on('rooms:list', (rooms) => this.renderRooms(rooms));
      this.on('message:new', (msg) => this.onMessageNew(msg));
      this.on('message:edited', (msg) => this.onMessageEdited(msg));
      this.on('message:deleted', (data) => this.onMessageDeleted(data));
      this.on('message:reaction', (data) => this.onMessageReaction(data));
      this.on('typing:status', (data) => this.onTypingStatus(data));
      this.on('presence:update', (data) => this.onPresenceUpdate(data));
      this.on('message:read_receipt', (data) => this.onReadReceipt(data));
      this.on('fatal', (m) => {
        console.error('[ChatManager] Fatal error:', m?.error);
        alert(m?.error || 'Chat error occurred');
      });
    }

    on(event, handler) {
      if (!this.eventHandlers.has(event)) {
        this.eventHandlers.set(event, new Set());
        this.socket?.on(event, (...args) => {
          this.eventHandlers.get(event)?.forEach(h => h(...args));
        });
      }
      this.eventHandlers.get(event).add(handler);
    }

    emit(event, data, cb) {
      if (!this.socket || !this.socket.connected) {
        if (event === 'message:send') {
          this.messageQueue.push({ event, data, cb });
        }
        return;
      }
      this.socket.emit(event, data, cb);
    }

    processMessageQueue() {
      while (this.messageQueue.length > 0) {
        const { event, data, cb } = this.messageQueue.shift();
        this.socket.emit(event, data, cb);
      }
    }

    setupEventListeners() {
      el.people.addEventListener('click', (e) => {
        const item = e.target.closest('.item');
        if (!item) return;
        const uid = parseInt(item.dataset.uid, 10);
        if (!uid || uid === UID) return;
        this.openDM(uid, item.dataset.name);
      });

      document.getElementById('sendBtn').onclick = () => this.send();
      el.input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
          e.preventDefault();
          this.send();
        }
      });

      el.input.addEventListener('input', () => {
        this.adjustTextareaHeight();
        if (!this.currentRoom) return;
        
        if (!this.isTyping) {
          this.isTyping = true;
          this.emit('typing:start', this.currentRoom);
        }
        
        clearTimeout(this.typingTimer);
        this.typingTimer = setTimeout(() => {
          this.isTyping = false;
          this.emit('typing:stop', this.currentRoom);
        }, 2000);
      });

      el.searchInput.addEventListener('input', (e) => {
        this.searchQuery = e.target.value.toLowerCase();
        this.filterRooms();
      });

      el.statusSelect.addEventListener('change', (e) => {
        this.emit('presence:set', e.target.value);
      });

      document.getElementById('btnNewGroup').onclick = () => {
        document.getElementById('groupModal').classList.add('show');
      };

      document.getElementById('closeGroup').onclick = () => {
        document.getElementById('groupModal').classList.remove('show');
      };

      document.getElementById('createGroup').onclick = () => this.createGroup();

      el.btnSettings.onclick = () => this.openSettings();
      document.getElementById('closeSettings').onclick = () => {
        document.getElementById('settingsModal').classList.remove('show');
      };
      document.getElementById('saveSettings').onclick = () => this.saveSettings();
      document.getElementById('btnAddMember').onclick = () => this.addMember();
      document.getElementById('btnLeaveRoom').onclick = () => this.leaveRoom();
      document.getElementById('btnDeleteRoom').onclick = () => this.deleteRoom();

      el.btnMute.onclick = () => this.toggleMute();

      document.getElementById('btnAttach').onclick = () => {
        document.getElementById('fileInput').click();
      };

      document.getElementById('fileInput').addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
          this.handleFileUpload(e.target.files[0]);
        }
      });

      document.getElementById('btnEmoji').onclick = () => {
        const emojis = ['👍', '❤️', '😂', '😮', '😢', '🎉', '🔥', '👏'];
        const picker = document.createElement('div');
        picker.style.cssText = 'position:absolute;background:#fff;border:1px solid #ddd;border-radius:.5rem;padding:.5rem;display:flex;gap:.25rem;z-index:100;';
        emojis.forEach(emoji => {
          const btn = document.createElement('button');
          btn.textContent = emoji;
          btn.style.cssText = 'border:none;background:none;font-size:1.2rem;cursor:pointer;padding:.25rem;';
          btn.onclick = () => {
            el.input.value += emoji;
            picker.remove();
          };
          picker.appendChild(btn);
        });
        document.getElementById('btnEmoji').parentElement.appendChild(picker);
        setTimeout(() => picker.remove(), 5000);
      });

      el.messages.addEventListener('click', (e) => {
        const btn = e.target.closest('button[data-action]');
        if (!btn) return;
        
        const action = btn.dataset.action;
        const msgId = btn.dataset.msgId;
        
        if (action === 'edit') {
          const bubble = btn.closest('.msg').querySelector('.bubble');
          const body = bubble.dataset.body || bubble.textContent;
          this.editMessage(msgId, body);
        } else if (action === 'delete') {
          this.deleteMessage(msgId);
        } else if (action === 'react') {
          const emoji = btn.dataset.emoji;
          this.quickReact(msgId, emoji);
        }
      });
    }

    adjustTextareaHeight() {
      el.input.style.height = 'auto';
      el.input.style.height = Math.min(el.input.scrollHeight, 120) + 'px';
    }

    openDM(targetUid, targetName) {
      // Add immediate visual feedback
      document.querySelectorAll('.people .item').forEach(i => i.classList.remove('active'));
      const clickedItem = document.querySelector(`.people .item[data-uid="${targetUid}"]`);
      if (clickedItem) clickedItem.classList.add('active');
      
      this.emit('dm:open', targetUid, (res) => {
        if (res?.ok) {
          this.emit('rooms:refresh');
          this.openRoom({ id: res.room_id, is_group: 0, name: targetName || 'Direct Message' });
        } else {
          alert(res?.error || 'Failed to open chat');
        }
      });
    }

    createGroup() {
      const name = document.getElementById('groupName').value.trim();
      const desc = document.getElementById('groupDesc').value.trim();
      const members = Array.from(document.querySelectorAll('.grp-user:checked')).map(c => parseInt(c.value, 10));
      
      if (!name || members.length === 0) {
        alert('Enter a group name and select at least one member');
        return;
      }

      this.emit('room:create', { name, description: desc, members }, (res) => {
        if (res?.ok) {
          document.getElementById('groupModal').classList.remove('show');
          document.getElementById('groupName').value = '';
          document.getElementById('groupDesc').value = '';
          document.querySelectorAll('.grp-user').forEach(c => c.checked = false);
          this.emit('rooms:refresh');
          this.openRoom({ id: res.room_id, is_group: 1, name });
        } else {
          alert(res?.error || 'Failed to create group');
        }
      });
    }

    renderRooms(rooms) {
      this.rooms = rooms;
      this.filterRooms();
    }

    filterRooms() {
      const filtered = this.rooms.filter(r => {
        if (!this.searchQuery) return true;
        const name = (r.name || 'Direct Message').toLowerCase();
        return name.includes(this.searchQuery);
      });

      el.rooms.innerHTML = '';
      filtered.forEach(r => {
        const div = document.createElement('div');
        div.className = 'item' + (String(r.id) === String(this.currentRoom) ? ' active' : '');
        const name = r.is_group ? (r.name || 'Room ' + r.id) : 'Direct Message';
        const icon = r.is_group ? '👥' : '💬';
        const muted = r.muted ? '🔕 ' : '';
        const unread = r.unread > 0 ? `<span class="unread-badge">${r.unread}</span>` : '';
        
        div.innerHTML = `
          <span>${icon}</span>
          <div class="info">
            <div class="name">${muted}${escapeHtml(name)}</div>
          </div>
          ${unread}
        `;
        div.onclick = () => this.openRoom(r);
        el.rooms.appendChild(div);
      });
    }

    openRoom(r) {
      this.currentRoom = r.id;
      this.currentRoomData = r;
      
      el.title.textContent = r.is_group ? (r.name || 'Room ' + r.id) : 'Direct Message';
      el.type.textContent = r.is_group ? 'Group' : 'Direct';
      el.type.style.display = 'inline-block';
      el.btnSettings.style.display = r.is_group ? 'inline-block' : 'none';
      el.btnMute.style.display = 'inline-block';
      el.btnMute.textContent = r.muted ? '🔔 Unmute' : '🔕 Mute';
      
      el.messages.innerHTML = '<div style="text-align:center;padding:2rem;color:#9ca3af;">Loading...</div>';
      
      this.emit('room:join', r.id, (res) => {
        if (res?.ok) {
          el.messages.innerHTML = '';
          this.currentRoomData = res.room;
          (res.messages || []).forEach(msg => this.appendMessage(msg));
          this.scrollToBottom();
          this.emit('message:read', { room_id: r.id });
        }
      });

      this.renderRooms(this.rooms);
    }

    onMessageNew(msg) {
      if (Number(msg.room_id) === Number(this.currentRoom)) {
        this.appendMessage(msg);
        this.scrollToBottom();
        this.emit('message:read', { room_id: msg.room_id, message_id: msg.id });
      }
      this.emit('rooms:refresh');
    }

    onMessageEdited(msg) {
      const el = document.querySelector(`[data-msg-id="${msg.id}"] .bubble`);
      if (el) {
        el.textContent = msg.body;
        const msgEl = el.closest('.msg');
        if (!msgEl.classList.contains('edited')) msgEl.classList.add('edited');
      }
    }

    onMessageDeleted(data) {
      const el = document.querySelector(`[data-msg-id="${data.message_id}"]`);
      if (el) el.remove();
    }

    onMessageReaction(data) {
      const reactionsEl = document.querySelector(`[data-msg-id="${data.message_id}"] .reactions`);
      if (reactionsEl) {
        reactionsEl.innerHTML = '';
        Object.entries(data.reactions).forEach(([emoji, users]) => {
          const btn = document.createElement('button');
          btn.className = 'reaction' + (users.includes(UID) ? ' me' : '');
          btn.innerHTML = `${emoji} ${users.length}`;
          btn.onclick = () => this.toggleReaction(data.message_id, emoji, !users.includes(UID));
          reactionsEl.appendChild(btn);
        });
      }
    }

    onTypingStatus(data) {
      if (Number(data.room_id) !== Number(this.currentRoom)) return;
      if (data.typing) {
        el.typing.textContent = `${data.username} is typing...`;
        el.typing.style.display = 'block';
      } else {
        el.typing.style.display = 'none';
      }
    }

    onPresenceUpdate(updates) {
      updates.forEach(({ userId, status, lastSeen }) => {
        this.presence.set(userId, { status, lastSeen });
        const dot = document.querySelector(`.status-dot[data-uid="${userId}"]`);
        if (dot) {
          dot.className = 'status-dot status-' + status;
        }
      });
    }

    onReadReceipt(data) {
      console.log('Read receipt:', data);
    }

    appendMessage(msg) {
      const isMe = Number(msg.user_id) === UID;
      const div = document.createElement('div');
      div.className = 'msg' + (isMe ? ' you' : '');
      div.setAttribute('data-msg-id', msg.id);
      
      const attachment = msg.attachment ? JSON.parse(msg.attachment) : null;
      const attachmentHtml = attachment ? `<div class="attachment">
        ${attachment.type === 'image' ? `<img src="${attachment.url}" alt="attachment" />` : 
          `📎 <a href="${attachment.url}" target="_blank">${attachment.name}</a>`}
      </div>` : '';
      
      const avatarLetter = (msg.username && msg.username[0]) ? msg.username[0].toUpperCase() : '?';
      
      div.innerHTML = `
        <div class="avatar">${avatarLetter}</div>
        <div class="msg-content">
          <div class="bubble" data-body="${escapeHtml(msg.body)}">${escapeHtml(msg.body)}${attachmentHtml}</div>
          <div class="meta">
            <span>${escapeHtml(msg.username || '')}</span>
            <span>${formatTime(msg.created_at)}</span>
          </div>
          <div class="reactions"></div>
          ${isMe ? `<div class="msg-actions">
            <button data-action="edit" data-msg-id="${msg.id}">✏️ Edit</button>
            <button data-action="delete" data-msg-id="${msg.id}">🗑️ Delete</button>
            <button data-action="react" data-msg-id="${msg.id}" data-emoji="👍">👍</button>
          </div>` : `<div class="msg-actions">
            <button data-action="react" data-msg-id="${msg.id}" data-emoji="👍">👍</button>
            <button data-action="react" data-msg-id="${msg.id}" data-emoji="❤️">❤️</button>
            <button data-action="react" data-msg-id="${msg.id}" data-emoji="😂">😂</button>
          </div>`}
        </div>
      `;
      
      el.messages.appendChild(div);
      
      if (msg.reactions && Object.keys(msg.reactions).length > 0) {
        this.onMessageReaction({ message_id: msg.id, reactions: msg.reactions });
      }
    }

    send() {
      const text = el.input.value.trim();
      if (!text || !this.currentRoom) return;

      if (this.editingMessage) {
        this.emit('message:edit', {
          room_id: this.currentRoom,
          message_id: this.editingMessage,
          body: text
        }, (res) => {
          if (res?.ok) {
            el.input.value = '';
            this.editingMessage = null;
            this.adjustTextareaHeight();
          }
        });
      } else {
        this.emit('message:send', {
          room_id: this.currentRoom,
          body: text
        }, (res) => {
          if (res?.ok) {
            el.input.value = '';
            this.adjustTextareaHeight();
          }
        });
      }

      if (this.isTyping) {
        this.isTyping = false;
        this.emit('typing:stop', this.currentRoom);
      }
    }

    editMessage(msgId, currentBody) {
      this.editingMessage = msgId;
      el.input.value = currentBody;
      el.input.focus();
      this.adjustTextareaHeight();
    }

    deleteMessage(msgId) {
      if (!confirm('Delete this message?')) return;
      this.emit('message:delete', {
        room_id: this.currentRoom,
        message_id: msgId
      });
    }

    quickReact(msgId, emoji) {
      const msg = this.rooms.find(r => r.id === this.currentRoom);
      this.toggleReaction(msgId, emoji, true);
    }

    toggleReaction(msgId, emoji, add) {
      this.emit('message:react', {
        room_id: this.currentRoom,
        message_id: msgId,
        emoji,
        add
      });
    }

    openSettings() {
      if (!this.currentRoomData || !this.currentRoomData.is_group) return;
      
      document.getElementById('settingsName').value = this.currentRoomData.name || '';
      document.getElementById('settingsDesc').value = this.currentRoomData.description || '';
      document.getElementById('settingsAvatar').value = this.currentRoomData.avatar || '';
      
      const canEdit = this.currentRoomData.role === 'owner' || this.currentRoomData.role === 'admin';
      document.getElementById('settingsName').disabled = !canEdit;
      document.getElementById('settingsDesc').disabled = !canEdit;
      document.getElementById('settingsAvatar').disabled = !canEdit;
      document.getElementById('saveSettings').style.display = canEdit ? 'inline-block' : 'none';
      document.getElementById('btnDeleteRoom').style.display = this.currentRoomData.role === 'owner' ? 'inline-block' : 'none';
      
      document.getElementById('settingsModal').classList.add('show');
    }

    saveSettings() {
      this.emit('room:update', {
        room_id: this.currentRoom,
        name: document.getElementById('settingsName').value.trim(),
        description: document.getElementById('settingsDesc').value.trim(),
        avatar: document.getElementById('settingsAvatar').value.trim()
      }, (res) => {
        if (res?.ok) {
          document.getElementById('settingsModal').classList.remove('show');
          this.emit('rooms:refresh');
          alert('Settings saved!');
        }
      });
    }

    addMember() {
      const userId = parseInt(document.getElementById('addMemberSelect').value, 10);
      if (!userId) return;
      
      this.emit('room:add_member', {
        room_id: this.currentRoom,
        user_id: userId
      }, (res) => {
        if (res?.ok) {
          alert('Member added!');
          this.emit('rooms:refresh');
        }
      });
    }

    leaveRoom() {
      if (!confirm('Leave this room?')) return;
      
      this.emit('room:leave', this.currentRoom, (res) => {
        if (res?.ok) {
          document.getElementById('settingsModal').classList.remove('show');
          this.currentRoom = null;
          this.emit('rooms:refresh');
          el.messages.innerHTML = '<div class="empty-state">You left the room</div>';
        } else {
          alert(res?.error || 'Cannot leave room');
        }
      });
    }

    deleteRoom() {
      if (!confirm('Delete this room? This cannot be undone!')) return;
      
      this.emit('room:delete', this.currentRoom, (res) => {
        if (res?.ok) {
          document.getElementById('settingsModal').classList.remove('show');
          this.currentRoom = null;
          this.emit('rooms:refresh');
          el.messages.innerHTML = '<div class="empty-state">Room deleted</div>';
        } else {
          alert(res?.error || 'Cannot delete room');
        }
      });
    }

    toggleMute() {
      if (!this.currentRoomData) return;
      
      this.emit('room:mute', {
        room_id: this.currentRoom,
        muted: !this.currentRoomData.muted
      }, (res) => {
        if (res?.ok) {
          this.emit('rooms:refresh');
        }
      });
    }

    handleFileUpload(file) {
      alert('File upload feature requires backend storage. For now, files are not supported.');
    }

    scrollToBottom() {
      el.messages.scrollTop = el.messages.scrollHeight;
    }
  }

  function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text || '';
    return div.innerHTML;
  }

  function formatTime(timestamp) {
    const d = new Date(timestamp);
    const now = new Date();
    const diff = now - d;
    
    if (diff < 60000) return 'Just now';
    if (diff < 3600000) return Math.floor(diff / 60000) + 'm ago';
    if (diff < 86400000) return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    return d.toLocaleDateString();
  }

  window.chat = new ChatManager();
})();
</script>
