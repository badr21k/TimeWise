<?php
class Home extends Controller
{
    public function index()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['auth'])) { header('Location: /login'); exit; }

        $me = [
            'id'        => $_SESSION['id'] ?? $_SESSION['user_id'] ?? null,
            'username'  => $_SESSION['username'] ?? null,
            'full_name' => $_SESSION['full_name'] ?? null,
            'is_admin'  => (int)($_SESSION['is_admin'] ?? 0),
        ];

        // Optional sample DB call, wrapped so it can't blank the page
        $sample = null;
        try {
            $userModel = $this->model('User');
            $sample = $userModel->test(); // safe if DB ok; ignored if not
        } catch (\Throwable $e) {
            error_log('Home::index test() failed: '.$e->getMessage());
        }

        $this->view('home/index', ['me' => $me, 'data' => $sample]);
    }
}
