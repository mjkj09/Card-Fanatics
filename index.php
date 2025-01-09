<?php
session_start();

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Routing::get('', 'DefaultController');
Routing::get('register', 'DefaultController');
Routing::get('cardsearch', 'DefaultController');
Routing::get('personaldata', 'DefaultController');
Routing::get('cardsfortrade', 'DefaultController');
Routing::get('wishlist', 'DefaultController');

Routing::get('getUserData', 'UserDataController');
Routing::post('updatePersonalData', 'UserDataController');
Routing::post('addCardForTrade', 'CardController');
Routing::post('removeCardForTrade', 'CardController');
Routing::post('addCardToWishlist', 'CardController');
Routing::post('removeCardFromWishlist', 'CardController');
Routing::get('getTradeCards', 'CardController');
Routing::get('getWishlistCards', 'CardController');
Routing::post('updateTradeQuantity', 'CardController');

Routing::post('login', 'SecurityController');
Routing::get('registerUser', 'SecurityController');
Routing::post('registerUser', 'SecurityController');

Routing::get('logout', 'SecurityController');

Routing::run($path);
