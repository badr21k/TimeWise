<?php

class User {

    public $username;
    public $password;
    public $auth = false;

    public function __construct() {}

    public function test () {
        $db = db_connect();
        $statement = $db->prepare("SELECT * FROM users;");
        $statement->execute();
        $rows = $statement->fetch(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function authenticate($username, $password) {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();

        $username = strtolower(trim($username));
        $db = db_connect();

        // Count bad attempts in last 60s
        $lockStmt = $db->prepare("
            SELECT COUNT(*) AS bad_count, MAX(timestamp) AS last_bad
            FROM login_logs
            WHERE username = :u AND status = 'bad'
              AND timestamp > DATE_SUB(NOW(), INTERVAL 60 SECOND)
        ");
        $lockStmt->bindValue(':u', $username);
        $lockStmt->execute();
        $lockInfo = $lockStmt->fetch(PDO::FETCH_ASSOC);

        if (($lockInfo['bad_count'] ?? 0) >= 3) {
            $elapsed = time() - strtotime($lockInfo['last_bad']);
            $wait    = max(0, 60 - $elapsed);
            $_SESSION['toast'] = ['type'=>'error','title'=>'Account Locked','message'=>"Too many failed attempts. Try again in {$wait}s."];
            $_SESSION['login_error'] = "Too many failed attempts. Try again in {$wait}s.";
            header('Location: /login'); exit;
        }

        // Fetch user (include is_admin + full_name!)
        $stmt = $db->prepare("
            SELECT id, username, password, is_admin, full_name
            FROM users WHERE username = :u LIMIT 1
        ");
        $stmt->bindValue(':u', $username);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $isGood = $row && password_verify($password, $row['password']);

        // Log the attempt
        $logStmt = $db->prepare("INSERT INTO login_logs (username, status) VALUES (:u, :s)");
        $logStmt->bindValue(':u', $username);
        $logStmt->bindValue(':s', $isGood ? 'good' : 'bad');
        $logStmt->execute();

        if ($isGood) {
            // Normalize session
            $_SESSION['auth']       = 1;
            $_SESSION['user_id']    = (int)$row['id'];
            $_SESSION['username']   = ucwords($row['username'] ?? $username);
            $_SESSION['is_admin']   = (int)($row['is_admin'] ?? 0);
            $_SESSION['full_name']  = $row['full_name'] ?? null;

            $_SESSION['toast'] = ['type'=>'success','title'=>'Welcome Back!','message'=>'You have successfully logged in.'];

            // Intended URL support
            if (!empty($_SESSION['intended_url'])) {
                $intendedUrl = $_SESSION['intended_url'];
                unset($_SESSION['intended_url']);
                if (strpos($intendedUrl, '/') === 0 && !preg_match('/^\/\//', $intendedUrl)) {
                    header('Location: ' . $intendedUrl); exit;
                }
            }
            header('Location: /home'); exit;
        }

        $_SESSION['toast'] = ['type'=>'error','title'=>'Login Failed','message'=>'Invalid username or password.'];
        $_SESSION['login_error'] = 'Invalid username or password.';
        header('Location: /login'); exit;
    }

    public function create_user($username, $password)
    {
        $username = strtolower(trim($username));
        $db = db_connect();

        $stmt = $db->prepare("SELECT 1 FROM users WHERE username = :username");
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        if ($stmt->fetch()) {
            $_SESSION['toast'] = ['type'=>'error','title'=>'Registration Failed','message'=>'Username already taken.'];
            return "Username already taken.";
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insert = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $insert->bindValue(':username', $username);
        $insert->bindValue(':password', $hashedPassword);
        if ($insert->execute()) {
            $_SESSION['toast'] = ['type'=>'success','title'=>'Account Created!','message'=>'Your account has been created successfully. You can now log in.'];
            return "Account created successfully.";
        } else {
            $_SESSION['toast'] = ['type'=>'error','title'=>'Registration Failed','message'=>'Error creating account. Please try again.'];
            return "Error creating account.";
        }
    }
}
