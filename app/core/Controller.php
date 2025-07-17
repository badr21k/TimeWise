<?php

class Controller {

    public function model ($model) {
        require_once 'app/models/' .$model . '.php';
        return new $model();
    }

    public function view ($view, $data = []) {
        // Extract data array so variables are available to the view
        extract($data);
        require_once 'app/views/' . $view .'.php';
    }

}
