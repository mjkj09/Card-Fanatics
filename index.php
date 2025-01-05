<?php

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Routing::get('', 'DefaultController');
Routing::get('register', 'DefaultController');
Routing::get('cardsearch', 'DefaultController');
Routing::get('personaldata', 'DefaultController');
Routing::get('cardsfortrade', 'DefaultController');
Routing::get('wishlist', 'DefaultController');

Routing::post('login', 'SecurityController');

Routing::run($path);