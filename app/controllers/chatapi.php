<?php
class chatapi extends Controller
{
    private $db;

    public function __construct() {
        $this->db = db_connect();
        header('Content-Type: application/json');
    }

    public function validateToken() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['valid' => false, 'error' => 'Method not allowed']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $token = $input['token'] ?? '';
        $claimedUserId = (int)($input['userId'] ?? 0);

        if (!$token || !$claimedUserId) {
            echo json_encode(['valid' => false, 'error' => 'Missing token or userId']);
            return;
        }

        try {
            $this->db->prepare("DELETE FROM chat_tokens WHERE expires_at < NOW()")->execute();
            
            $tokenHash = hash('sha256', $token);
            
            $stmt = $this->db->prepare("
                SELECT user_id, expires_at 
                FROM chat_tokens 
                WHERE token_hash = ? AND expires_at > NOW()
            ");
            $stmt->execute([$tokenHash]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                echo json_encode(['valid' => false, 'error' => 'Invalid or expired token']);
                return;
            }

            if ((int)$result['user_id'] !== $claimedUserId) {
                echo json_encode(['valid' => false, 'error' => 'Token mismatch']);
                return;
            }

            $userStmt = $this->db->prepare("
                SELECT id, username, COALESCE(NULLIF(TRIM(full_name),''), username) AS display_name 
                FROM users 
                WHERE id = ?
            ");
            $userStmt->execute([$claimedUserId]);
            $user = $userStmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                echo json_encode(['valid' => false, 'error' => 'User not found']);
                return;
            }

            echo json_encode([
                'valid' => true,
                'userId' => (int)$user['id'],
                'username' => $user['display_name']
            ]);
        } catch (Exception $e) {
            error_log("Token validation error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['valid' => false, 'error' => 'Server error']);
        }
    }
}
