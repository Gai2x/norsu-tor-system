<?php

require_once __DIR__ . '/../../model/loginSignup/HomeModel.php';

class HomeController {
    private $model;

    public function __construct() {
        $this->model = new HomeModel();
    }

    public function index() {
        return [
            'services' => $this->model->getServices()
        ];
    }
}
