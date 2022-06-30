<?php

require_once 'AppController.php';
require_once __DIR__ .'/../models/User.php';
require_once __DIR__.'/../repository/UserRepository.php';

class SecurityController extends AppController {

    private $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    public function login()
    {
        if (!$this->isPost()) {
            if (isset($_SESSION['email'])) {
                $url = "http://$_SERVER[HTTP_HOST]";
                header("Location: {$url}/projects");
            }

            return $this->render('login');
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->userRepository->getUser($email);

        if (!$user) {
            return $this->render('login', ['messages' => ['User not found!']]);
        }

        if ($user->getEmail() !== $email) {
            return $this->render('login', ['messages' => ['User with this email not exist!']]);
        }

        if (!password_verify($password, $user->getPassword())) {
            return $this->render('login', ['messages' => ['Wrong password!']]);
        }

        $_SESSION['email'] = $user->getEmail();
        $_SESSION['is_admin'] = $user->isAdmin();
        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/projects");
    }

    public function register()
    {
        if (!$this->isPost()) {
            return $this->render('register');
        }

        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmedPassword = $_POST['confirmedPassword'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $phone = $_POST['phone'];

        if ($password !== $confirmedPassword) {
            return $this->render('register', ['messages' => ['Please provide proper password']]);
        }

        $user = new User($email, password_hash($password, PASSWORD_DEFAULT), $name, $surname);
        $user->setPhone($phone);

        $this->userRepository->addUser($user);

        return $this->render('login', ['messages' => ['You\'ve been succesfully registrated!']]);
    }

    public function grantadmin() {
        if (!$this->isPost()) {
            return $this->render('login');
        }

        $email = $_POST['email'];
        $post_token = $_POST['token'];
        $admin_email = $_SESSION['email'];
        $token = md5('bardzo-tajny_tokenAdmina');
        $admin = $this->userRepository->getUser($admin_email);
        if (!$admin->isAdmin()) {
            return $this->render('login', ['messages' => ['Only admin can do this!']]);
        }

        if (md5($post_token) != $token){
            return $this->render('admin', ['messages' => ['Wrong token']]);
        }

        $user = $this->userRepository->getUser($email);
        if (!$user) {
            $this->render('admin', ['messages' => ['User with this email not exist!']]);
        }

        $user->setAdmin(true);
        $this->userRepository->updateUser($user);

        $this->render('admin', ['messages' => ['Success!']]);
    }

    public function logout()
    {
        session_destroy();

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/login");
    }
}