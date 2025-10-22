<?php

class Access_map extends Controller {

    public function index() {
        if (empty($_SESSION['auth'])) { 
            header('Location: /login'); 
            exit; 
        }
        
        if (class_exists('AccessControl')) {
            AccessControl::enforceAccess('access_map', 'index', 'Access Level Map');
        }
        
        $this->view('access_map/index');
    }
}
