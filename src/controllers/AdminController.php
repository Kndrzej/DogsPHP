<?php

require_once 'AppController.php';
require_once __DIR__ .'/../models/User.php';
require_once __DIR__.'/../repository/UserRepository.php';

class AdminController extends AppController {
    private $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    public function admin()
    {
        if (!$this->userRepository->getUser($_SESSION['email'])->isAdmin()) {
            return $this->render('login', ['messages' => ['Only admin can do this!']]);
        }

        $this->render('admin');
    }

    public function showusers()
    {
        if (!$this->userRepository->getUser($_SESSION['email'])->isAdmin()) {
            return $this->render('login', ['messages' => ['Only admin can do this!']]);
        }

        $this->userRepository->createUsersView();
        $users = $this->userRepository->selectUsersView();
var_dump($users[0]);
        return $this->render('showusers', ['users' => [$users]]);
    }
}