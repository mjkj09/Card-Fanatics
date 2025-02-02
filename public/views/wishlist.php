<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist</title>
    <link rel="stylesheet" href="public/css/common.css">
    <link rel="stylesheet" href="public/css/cardlist.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;900&display=swap" rel="stylesheet">
    <script src="public/js/menu.js"></script>
    <script src="public/js/wishlist.js" defer></script>
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
        <header class="page-header">
            <h1>Wishlist</h1>
        </header>

        <section class="form-container">
            <form id="wishlist-form">
                <label for="cardCode">Card Code *</label>
                <input type="text" id="cardCode" name="cardCode" required>

                <label for="collectionName">Collection *</label>
                <input type="text" id="collectionName" name="collectionName" list="collectionSuggestions" required>
                <datalist id="collectionSuggestions"></datalist>

                <label for="playerName">Player Name *</label>
                <input type="text" id="playerName" name="playerName" required>

                <label for="playerSurname">Player Surname *</label>
                <input type="text" id="playerSurname" name="playerSurname" required>

                <label for="parallel">Parallel (optional)</label>
                <input type="text" id="parallel" name="parallel" placeholder="e.g. Blue Crystal">

                <button type="submit" class="submit-button">Add to Wishlist</button>
            </form>

            <div class="messages" id="messages"></div>
        </section>

        <section class="card-list">
            <header class="page-header">
                <h1>Wishlist Items</h1>
            </header>
            <ul id="wishlist-container">
                <!-- Dynamically from JS -->
            </ul>
        </section>
    </main>
</div>
</body>
</html>
