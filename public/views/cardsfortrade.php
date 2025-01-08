<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cards for Trade</title>
    <link rel="stylesheet" href="public/css/common.css">
    <link rel="stylesheet" href="public/css/cardlist.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;900&display=swap" rel="stylesheet">
    <script src="public/js/menu.js"></script>
    <script src="public/js/cardsfortrade.js" defer></script>
    <script src="https://kit.fontawesome.com/4dc72001e9.js" crossorigin="anonymous"></script>
</head>
<body>
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
            <li><a href="logout">LOGOUT</a></li>
        </ul>
    </div>

    <main class="content">
        <header class="page-header">
            <h1>Cards for Trade</h1>
        </header>

        <section class="form-container">
            <form id="trade-form">
                <label for="cardCode">Card Code *</label>
                <input type="text" id="cardCode" name="cardCode" required>

                <label for="collectionName">Collection *</label>
                <input type="text" id="collectionName" name="collectionName" required>

                <label for="parallel">Parallel (optional)</label>
                <input type="text" id="parallel" name="parallel" placeholder="e.g. Blue Crystal">

                <label for="quantity">Quantity *</label>
                <input type="number" id="quantity" name="quantity" min="1" value="1" required>

                <button type="submit" class="submit-button">Add Card</button>
            </form>

            <div class="messages" id="messages"></div>
        </section>

        <section class="card-list">
            <header class="page-header">
                <h1>Your Cards</h1>
            </header>
            <ul id="trade-list">
                <!-- Dynamically from JS -->
            </ul>
        </section>
    </main>
</div>
</body>
</html>
