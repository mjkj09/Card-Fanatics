<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Data</title>
    <link rel="stylesheet" href="public/css/common.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;900&display=swap" rel="stylesheet">
    <script src="public/js/menu.js"></script>
    <script src="public/js/personaldata.js" defer></script>
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
            <h1>My Personal Data</h1>
        </header>

        <section class="form-container">
            <form id="personal-data-form">
                <label for="name">First Name *</label>
                <input type="text" id="name" name="name" required>

                <label for="surname">Last Name *</label>
                <input type="text" id="surname" name="surname" required>

                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" readonly>

                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" placeholder="Enter your phone number">

                <label for="instagram">Instagram Handle</label>
                <input type="text" id="instagram" name="instagram" placeholder="Enter your Instagram handle">

                <button type="submit" class="submit-button">Save Changes</button>
            </form>
            <div class="messages" id="messages"></div>
        </section>
    </main>
</div>
</body>
</html>
