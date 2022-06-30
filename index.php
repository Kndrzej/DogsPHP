<?php

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url( $path, PHP_URL_PATH);

Router::get('', 'DefaultController');
Router::get('projects', 'ProjectController');
Router::post('login', 'SecurityController');
Router::post('register', 'SecurityController');
Router::post('addproject', 'ProjectController');
Router::get('admin', 'AdminController');
Router::post('grantadmin', 'SecurityController');
Router::get('logout', 'SecurityController');
Router::get('showusers', 'AdminController');

Router::run($path);

