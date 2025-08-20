<?php
/**
 * Minimal front controller / router
 * - Strips query string from the method segment (so /schedule/api?a=... works)
 * - Defaults to Home@index when no controller/method
 */
class App
{
    protected $controller = 'home';
    protected $method     = 'index';
    protected $params     = [];

        public function __construct()
        {
            // === Quick API hook for roles ===
            if (isset($_GET['a']) && $_GET['a'] === 'roles.list') {
                require_once 'app/controllers/schedule.php';
                (new Schedule)->listRoles();
                exit;
            }

            if (session_status() !== PHP_SESSION_ACTIVE) session_start();

        $url = $this->parseUrl(); // ['schedule','api'] for /schedule/api?a=...

        // Controller
        if (!empty($url[0])) {
            $this->controller = strtolower($url[0]);
            unset($url[0]);
        }

        $ctrlPath = 'app/controllers/' . $this->controller . '.php';
        if (!is_file($ctrlPath)) {
            // fallback
            $this->controller = 'home';
            $ctrlPath = 'app/controllers/home.php';
        }
        require_once $ctrlPath;
        $this->controller = new $this->controller;

        // Method (strip ?query from segment)
        if (!empty($url[1])) {
            $candidate = explode('?', $url[1], 2)[0];
            if (method_exists($this->controller, $candidate)) {
                $this->method = $candidate;
                unset($url[1]);
            }
        }

        // Params
        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl(): array
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
        $path = trim($path, '/');
        return $path === '' ? [] : explode('/', $path);
    }
}
