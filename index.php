<?php

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Routing::get('login', 'DefaultController');
Routing::get('register', 'DefaultController');
Routing::get('cardfinder', 'DefaultController');
Routing::get('personaldata', 'DefaultController');
Routing::get('cardsfortrade', 'DefaultController');
Routing::get('wishlist', 'DefaultController');

Routing::run($path);