<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/common.css">
    <link rel="stylesheet" href="public/css/cardsearch.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,594;1,594&family=Poppins:ital,wght@0,400;1,600&display=swap" rel="stylesheet">
    <script src="public/js/menu.js"></script>
    <script src="public/js/search.js" defer></script>
    <script src="https://kit.fontawesome.com/4dc72001e9.js" crossorigin="anonymous"></script>
    <title>Card Finder</title>
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
        <header class="search-bar">
            <form id="search-form">
                <input
                        placeholder="Search by code, collection, or parallel"
                        type="text"
                        name="search"
                        id="search-input"
                >

                <i class="fa-solid fa-search"
                   id="search-icon"
                   style="color: #859f3d;">
                </i>
            </form>
            <button class="filter-button">Filters</button>
        </header>

        <section class="results" id="results-container">
            <!-- Dynamically from JS -->
        </section>
    </main>
</div>
</body>
</html>
