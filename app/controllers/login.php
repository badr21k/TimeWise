<?php
class Login extends Controller
{
		public function index()
		{
				$this->view('login/index');
		}

		public function verify()
		{
				if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
						header('Location: /login');
						exit;
				}

				$username = trim($_POST['username']);
				$password = trim($_POST['password']);

				$user = $this->model('User');
				$user->authenticate($username, $password);
		}
}
