<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="public/css/common.css">
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

            <table style="color:#f6fcdf; margin-top:20px;">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Banned?</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody id="users-table-body">
                <?php if(isset($allUsers) && is_array($allUsers)): ?>
                    <?php foreach ($allUsers as $u): ?>
                        <tr>
                            <td><?= $u['id'] ?></td>
                            <td><?= $u['name'] . ' ' . $u['surname'] ?></td>
                            <td><?= $u['email'] ?></td>
                            <td><?= $u['is_banned'] ? 'YES' : 'NO' ?></td>
                            <td>
                                <button class="profile-button" onclick="window.open('userProfile?userId=<?= $u['id'] ?>','_blank')">
                                    View Profile
                                </button>

                                <?php if (!$u['is_banned']): ?>
                                    <button class="ban-btn" data-user-id="<?= $u['id'] ?>">
                                        Ban
                                    </button>
                                <?php else: ?>
                                    (Already banned)
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5">No users found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

    <div id="ban-popup" style="display:none; position:fixed; top:30%; left:30%; background:#333; padding:20px; border:1px solid #777; z-index:999;">
        <h3>Ban User</h3>
        <input type="hidden" id="banUserId" value="">
        <label for="banReason">Reason:</label>
        <textarea id="banReason" rows="3" cols="30"></textarea>
        <br><br>
        <button id="banConfirmBtn">Confirm Ban</button>
        <button id="banCancelBtn">Cancel</button>
    </div>
</div>
</body>
</html>
