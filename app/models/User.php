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

      $username = strtolower(trim($username));
      $db = db_connect();

      //count bad attempts in last 60s
      $lockStmt = $db->prepare("
          SELECT 
            COUNT(*) AS bad_count,
            MAX(timestamp) AS last_bad
          FROM login_logs
          WHERE username = :u
            AND status = 'bad'
            AND timestamp > DATE_SUB(NOW(), INTERVAL 60 SECOND)
      ");
      $lockStmt->bindValue(':u', $username);
      $lockStmt->execute();
      $lockInfo = $lockStmt->fetch(PDO::FETCH_ASSOC);

      if ($lockInfo['bad_count'] >= 3) {
          $elapsed = time() - strtotime($lockInfo['last_bad']);
          $wait    = 60 - $elapsed;
          $_SESSION['toast'] = [
              'type' => 'error',
              'title' => 'Account Locked',
              'message' => "Too many failed attempts. Try again in {$wait}s."
          ];
          $_SESSION['login_error'] = "Too many failed attempts. Try again in {$wait}s.";
          header('Location: /login');
          exit;
      }

      //FETCH USER RECORD
      $stmt = $db->prepare("SELECT id, password FROM users WHERE username = :u");
      $stmt->bindValue(':u', $username);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      //VERIFY PASSWORD
      $isGood = $row && password_verify($password, $row['password']);

      //LOG THE ATTEMPT
      $logStmt = $db->prepare("
          INSERT INTO login_logs (username, status)
          VALUES (:u, :s)
      ");
      $logStmt->bindValue(':u', $username);
      $logStmt->bindValue(':s', $isGood ? 'good' : 'bad');
      $logStmt->execute();

      //REDIRECT BASED ON RESULT
      if ($isGood) {
          $_SESSION['auth']     = 1;
          $_SESSION['username'] = ucwords($username);
          $_SESSION['user_id'] = $row['id'];

          // Set success toast
          $_SESSION['toast'] = [
              'type' => 'success',
              'title' => 'Welcome Back!',
              'message' => 'You have successfully logged in.'
          ];

          // Check for intended URL (for redirects after login)
          if (isset($_SESSION['intended_url']) && !empty($_SESSION['intended_url'])) {
              $intendedUrl = $_SESSION['intended_url'];
              unset($_SESSION['intended_url']); // Clear it after use

              // Ensure the intended URL is safe (prevent open redirects)
              if (strpos($intendedUrl, '/') === 0 && !preg_match('/^\/\//', $intendedUrl)) {
                  header('Location: ' . $intendedUrl);
                  exit;
              }
          }

          header('Location: /home');
          exit;
      }

      // Set error toast
      $_SESSION['toast'] = [
          'type' => 'error',
          'title' => 'Login Failed',
          'message' => 'Invalid username or password.'
      ];
      $_SESSION['login_error'] = 'Invalid username or password.';
      header('Location: /login');
      exit;
  }



    public function create_user($username, $password)
    {
        $username = strtolower(trim($username));
        $db = db_connect();

        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        if ($stmt->fetch()) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'title' => 'Registration Failed',
                'message' => 'Username already taken.'
            ];
            return "Username already taken.";
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $insert = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $insert->bindValue(':username', $username);
        $insert->bindValue(':password', $hashedPassword);
        if ($insert->execute()) {
            $_SESSION['toast'] = [
                'type' => 'success',
                'title' => 'Account Created!',
                'message' => 'Your account has been created successfully. You can now log in.'
            ];
            return "Account created successfully.";
        } else {
            $_SESSION['toast'] = [
                'type' => 'error',
                'title' => 'Registration Failed',
                'message' => 'Error creating account. Please try again.'
            ];
            return "Error creating account.";
        }
    }
}
