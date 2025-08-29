<?php
class chat extends Controller
{
    public function __construct() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['auth'])) { header('Location: /login'); exit; }
        $this->db = db_connect();
    }

    public function index() {
        $me = (int)($_SESSION['id'] ?? 0);
        // list all users for sidebar & group picker
        $stmt = $this->db->query("SELECT id, COALESCE(NULLIF(TRIM(full_name),''), username) AS label FROM users ORDER BY label ASC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->view('chat/index', ['users' => $users, 'me' => $me]);
    }
}
