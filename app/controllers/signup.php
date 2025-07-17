<?php
class Signup extends Controller
{
    public function index()
    {
        $this->view('Signup/index');
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /signup");
            exit();
        }

        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $confirm  = trim($_POST['confirm']);

        if ($password !== $confirm) {
            $_SESSION['signup_error'] = "Passwords do not match.";
            header("Location: /signup");
            exit();
        }

        $userModel = $this->model('User');
        $result    = $userModel->create_user($username, $password);

        if ($result === "Account created successfully.") {
            $_SESSION['message'] = $result;
            header("Location: /login");
            exit();
        }

        $_SESSION['signup_error'] = $result;
        header("Location: /signup");
        exit();
    }
}
