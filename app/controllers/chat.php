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
        $stmt = $this->db->query("SELECT id, COALESCE(NULLIF(TRIM(full_name),''), username) AS label FROM users ORDER BY label ASC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Generate secure token for chat authentication
        $chatToken = $this->generateChatToken($me);
        
        $this->view('chat/index', [
            'users' => $users, 
            'me' => $me,
            'chat_token' => $chatToken
        ]);
    }

    private function generateChatToken($userId) {
        try {
            // Clean up expired tokens
            $this->db->prepare("DELETE FROM chat_tokens WHERE expires_at < NOW()")->execute();
            
            // Generate a cryptographically secure token
            $token = bin2hex(random_bytes(32));
            $tokenHash = hash('sha256', $token);
            $expiresAt = date('Y-m-d H:i:s', time() + 3600); // 1 hour expiry
            
            // Store token hash in database
            $stmt = $this->db->prepare("
                INSERT INTO chat_tokens (user_id, token_hash, expires_at) 
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                token_hash = VALUES(token_hash), 
                expires_at = VALUES(expires_at)
            ");
            $stmt->execute([$userId, $tokenHash, $expiresAt]);
            
            return $token;
        } catch (Exception $e) {
            error_log("Failed to generate chat token: " . $e->getMessage());
            return null;
        }
    }
}
