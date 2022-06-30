<?php

require_once 'AppController.php';

class DefaultController extends AppController {

    public function index()
    {
        if (isset($_SESSION['email'])) {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/projects");
        }

        $this->render('login');
    }
}