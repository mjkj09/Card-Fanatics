<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="public/css/common.css">
    <link rel="stylesheet" href="public/css/admindashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;900&display=swap" rel="stylesheet">
    <script src="public/js/menu.js"></script>
    <script src="public/js/admindashboard.js" defer></script>
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
            <?php if ($isAdmin): ?>
                <li><a href="admindashboard">ADMIN DASHBOARD</a></li>
            <?php endif; ?>
            <li><a href="logout">LOGOUT</a></li>
        </ul>
    </div>

    <main class="content">
        <header class="page-header">
            <h1>Welcome to Admin Dashboard</h1>
        </header>

        <section>
            <p>Here you can manage users, cards, etc.</p>
            <div class="table-wrapper">
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th class="actions-header">Actions</th>
                    </tr>
                    </thead>
                    <tbody id="users-table-body">
                    <?php if (isset($allUsers) && is_array($allUsers)): ?>
                        <?php foreach ($allUsers as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= $user['name'] . ' ' . $user['surname'] ?></td>
                                <td><?= $user['email'] ?></td>
                                <td class="actions-cell">
                                    <div class="action-buttons-container">
                                        <button class="profile-button small-button"
                                                onclick="window.open('userProfile?userId=<?= $user['id'] ?>','_blank')">
                                            View Profile
                                        </button>

                                        <?php if (!$user['is_banned']): ?>
                                            <button class="ban-btn small-button"
                                                    data-user-id="<?= $user['id'] ?>">
                                                Ban
                                            </button>
                                        <?php else: ?>
                                            <button class="ban-btn small-button"
                                                    disabled
                                                    data-user-id="<?= $user['id'] ?>">
                                                Ban
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No users found.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <div id="ban-popup" class="ban-popup">
        <h3>Ban User</h3>
        <input type="hidden" id="banUserId" value="">
        <label for="banReason">Reason:</label>
        <br>
        <textarea name="banReason" id="banReason" rows="3" cols="50"></textarea>
        <br><br>
        <button class="ban-btn small-button" id="banConfirmBtn">Confirm Ban</button>
        <button class="small-button" id="banCancelBtn">Cancel</button>
    </div>
</div>
</body>
</html>
