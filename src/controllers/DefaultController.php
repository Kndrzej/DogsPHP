<?php

require_once 'AppController.php';

class DefaultController extends AppController{

    public function index(){
        //display index.html
       $this->render('login');
    }
    public function projects(){
        //display projects.html
        $this->render('projects');
    }
}