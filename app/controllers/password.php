<?php
// app/controllers/password.php
class password extends Controller
{
    private PDO $db;

    public function __construct() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $this->db = db_connect();
        $this->ensureTables();
    }

    private function ensureTables(): void
    {
        $this->db->exec("CREATE TABLE IF NOT EXISTS password_resets (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            token VARCHAR(128) NOT NULL,
            expires_at DATETIME NOT NULL,
            used TINYINT(1) DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_token (token),
            INDEX idx_user (user_id),
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )");
    }

    // GET /password/forgot
    public function forgot() {
        require 'app/views/password/forgot.php';
    }

    // POST /password/forgot (send link)
    public function send() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /password/forgot'); exit; }
        $username = strtolower(trim($_POST['username'] ?? ''));
        if ($username === '') {
            $_SESSION['toast'] = ['type'=>'error','title'=>'Missing username','message'=>'Please enter your username.'];
            header('Location: /password/forgot'); exit;
        }
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = :u LIMIT 1");
        $stmt->execute([':u'=>$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // For privacy, respond the same whether user exists or not
        if (!$user) {
            $_SESSION['toast'] = ['type'=>'success','title'=>'Check your email','message'=>'If an account exists, you will receive a reset link.'];
            header('Location: /login'); exit;
        }

        $userId = (int)$user['id'];
        $token = bin2hex(random_bytes(32));
        $expires = (new DateTime('+1 hour', new DateTimeZone('UTC')))->format('Y-m-d H:i:s');
        $this->db->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (:uid, :t, :e)")
                 ->execute([':uid'=>$userId, ':t'=>$token, ':e'=>$expires]);

        $link = sprintf('%s/password/reset?token=%s', $this->baseUrl(), urlencode($token));
        error_log('[Password Reset] Link for user '.$username.': '.$link);

        // If you have SMTP configured, send an email here. Placeholder uses session toast.
        $_SESSION['toast'] = ['type'=>'success','title'=>'Reset link created','message'=>'Use the link we sent (or check server logs).'];
        $_SESSION['reset_debug_link'] = $link; // show on the UI for now

        header('Location: /password/forgot'); exit;
    }

    // GET /password/reset?token=...
    public function reset() {
        $token = $_GET['token'] ?? '';
        if (!$this->validateToken($token)) {
            $_SESSION['toast'] = ['type'=>'error','title'=>'Invalid link','message'=>'The password reset link is invalid or expired.'];
            header('Location: /password/forgot'); exit;
        }
        require 'app/views/password/reset.php';
    }

    // POST /password/update
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /password/forgot'); exit; }
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm'] ?? '';

        if (!$this->validateToken($token, $row)) {
            $_SESSION['toast'] = ['type'=>'error','title'=>'Invalid link','message'=>'The password reset link is invalid or expired.'];
            header('Location: /password/forgot'); exit;
        }
        if (strlen($password) < 6) {
            $_SESSION['toast'] = ['type'=>'error','title'=>'Weak password','message'=>'Password must be at least 6 characters.'];
            header('Location: /password/reset?token='.urlencode($token)); exit;
        }
        if ($password !== $confirm) {
            $_SESSION['toast'] = ['type'=>'error','title'=>'Mismatch','message'=>'Password and confirmation do not match.'];
            header('Location: /password/reset?token='.urlencode($token)); exit;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $this->db->prepare("UPDATE users SET password = :p WHERE id = :id")
                 ->execute([':p'=>$hash, ':id'=>(int)$row['user_id']]);
        $this->db->prepare("UPDATE password_resets SET used = 1 WHERE id = :id")
                 ->execute([':id'=>(int)$row['id']]);

        $_SESSION['toast'] = ['type'=>'success','title'=>'Password updated','message'=>'You can now log in with your new password.'];
        header('Location: /login'); exit;
    }

    private function validateToken(string $token, ?array &$rowOut = null): bool
    {
        if (!$token || !preg_match('/^[a-f0-9]{64}$/', $token)) return false;
        $stmt = $this->db->prepare("SELECT * FROM password_resets WHERE token = :t AND used = 0 LIMIT 1");
        $stmt->execute([':t'=>$token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return false;
        $now = new DateTime('now', new DateTimeZone('UTC'));
        $exp = new DateTime($row['expires_at'], new DateTimeZone('UTC'));
        if ($now > $exp) return false;
        if ($rowOut !== null) $rowOut = $row;
        return true;
    }

    private function baseUrl(): string
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $scheme.'://'.$host;
    }
}
