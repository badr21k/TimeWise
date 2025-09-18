// Connects the page to the Socket.IO server on :3001 (same hostname)
(function () {
  var CHAT_PORT = 3001;
  var socketURL = location.protocol + '//' + location.hostname + ':' + CHAT_PORT;

  function load(src, done) {
    var s = document.createElement('script');
    s.src = src;
    s.crossOrigin = 'anonymous';
    s.onload = done;
    document.head.appendChild(s);
  }

  function connect() {
    var socket = window.io(socketURL, {
      transports: ["websocket", "polling"],
      withCredentials: true,
      reconnection: true,
      reconnectionAttempts: 10,
      reconnectionDelay: 1000
    });

    // Expose so your page code can use it
    window.chatSocket = socket;

    // Auto-join a default room per user (uses globals set by PHP below)
    if (window.CHAT_CFG?.userId) {
      socket.emit("join", "user:" + window.CHAT_CFG.userId);
    }
    if (window.CHAT_CFG?.room) {
      socket.emit("join", window.CHAT_CFG.room);
    }

    // Example: wire basic incoming messages to console
    socket.on("message", function (msg) {
      console.log("[chat] incoming:", msg);
    });
  }

  if (!window.io) {
    load("https://cdn.socket.io/4.7.5/socket.io.min.js", connect);
  } else {
    connect();
  }
})();
