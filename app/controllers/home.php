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
            'access_level' => (int)($_SESSION['access_level'] ?? 1),
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
