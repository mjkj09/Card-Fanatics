<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="public/css/common.css">
    <link rel="stylesheet" href="public/css/cardlist.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;900&display=swap" rel="stylesheet">
    <script src="public/js/menu.js"></script>
    <script src="https://kit.fontawesome.com/4dc72001e9.js" crossorigin="anonymous"></script>
</head>
<body>
<?php
use repository\UserRepository;

session_start();
$isAdmin = false;
if (isset($_SESSION['user_id'])) {
    $isAdmin = UserRepository::isUserAdmin($_SESSION['user_id']);
}
?>
<div class="main-page">
    <nav class="navbar">
        <i class="fa-solid fa-bars" id="menu-toggle" style="color: #f6fcdf;"></i>
        <img src="public/img/logo.svg" class="navbar__logo" alt="Logo">
    </nav>

    <div id="fullscreen-menu" class="fullscreen-menu">
        <i class="fa-solid fa-times" id="close-menu" style="color: #f6fcdf;"></i>
        <ul class="menu-options">
            <li><a href="cardsearch">CARD SEARCH</a></li>
            <li><a href="personaldata">MY PERSONAL DATA</a></li>
            <li><a href="cardsfortrade">CARDS FOR TRADE</a></li>
            <li><a href="wishlist">WISHLIST</a></li>
            <?php if ($isAdmin):?>
                <li><a href="admindashboard">ADMIN DASHBOARD</a></li>
            <?php endif; ?>
            <li><a href="logout">LOGOUT</a></li>
        </ul>
    </div>

    <main class="content">
        <section class="user-info">
            <header class="page-header">
                <h1>Contact Info</h1>
            </header>
            <?php
            if (!empty($user)) {
                $name = $user->getName() ?: 'No info';
                $surname = $user->getSurname() ?: 'No info';
                $email = $user->getEmail() ?: 'No info';
                $phone = $user->getPhone() ?: 'No info';
                $instagram = $user->getInstagram() ?: 'No info';

                echo "<p>Name: $name $surname</p>";
                echo "<p>Email: $email</p>";
                echo "<p>Phone: $phone</p>";
                echo "<p>Instagram: $instagram</p>";
            } else {
                echo "<p>No contact info.</p>";
            }
            ?>
        </section>

        <section class="user-tradecards card-list">
            <header class="page-header">
                <h1>Cards for Trade</h1>
            </header>
            <ul>
                <?php if (!empty($tradeCards)): ?>
                    <?php foreach ($tradeCards as $card): ?>
                        <li>
                            <?php
                            echo "{$card['code']} - {$card['collection']} - {$card['player_name']} {$card['player_surname']} "
                                . ($card['parallel'] ? ' - (' . $card['parallel'] . ')' : '')
                                . " x{$card['quantity']}";
                            ?>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No cards for trade.</li>
                <?php endif; ?>
            </ul>
        </section>

        <section class="user-wishlist card-list">
            <header class="page-header">
                <h1>Wishlist</h1>
            </header>
            <ul>
                <?php if (!empty($wishlist)): ?>
                    <?php foreach ($wishlist as $card): ?>
                        <li>
                            <?php
                            echo "{$card['code']} - {$card['collection']} - {$card['player_name']} {$card['player_surname']} "
                                . ($card['parallel'] ? ' - (' . $card['parallel'] . ')' : '');
                            ?>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No wishlist items.</li>
                <?php endif; ?>
            </ul>
        </section>

    </main>
</div>
</body>
</html>
