<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Page</title>
    <link rel="stylesheet" href="public/css/common.css">
    <link rel="stylesheet" href="public/css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,594;1,594&family=Poppins:ital,wght@0,400;1,600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/4dc72001e9.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="login-page">

    <section class="login-page__left-panel">
        <img src="public/img/logo+text.svg" class="left-panel__logo" alt="Logo">
    </section>

    <section class="login-page__right-panel">
        <div class="login-box">
            <h2 class="login-box__title">Register</h2>
            <p class="login-box__subtitle">Create your account.</p>
            <form class="login-box__form" action="registerUser" method="POST">

                <div class="messages">
                    <?php if (isset($messages) && !empty($messages)): ?>
                        <div class="messages">
                            <?php foreach ($messages as $message): ?>
                                <p><?= $message ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form__input-group">
                    <i class="fa-solid fa-user fa-sm" style="color: #f6fcdf;"></i>
                    <input type="text" name="name" class="form__input" placeholder="First Name" required>
                </div>

                <div class="form__input-group">
                    <i class="fa-solid fa-user fa-sm" style="color: #f6fcdf;"></i>
                    <input type="text" name="surname" class="form__input" placeholder="Last Name" required>
                </div>

                <div class="form__input-group">
                    <i class="fa-solid fa-envelope fa-sm" style="color: #f6fcdf;"></i>
                    <input type="email" name="email" class="form__input" placeholder="Email" required>
                </div>

                <div class="form__input-group">
                    <i class="fa-solid fa-lock fa-sm" style="color: #f6fcdf;"></i>
                    <input type="password" name="password" class="form__input" placeholder="Password" required>
                </div>

                <button type="submit" class="form__button">Sign Up</button>
            </form>

            <p class="login-box__signup-text">
                Already have an account?
                <a href="/" class="login-box__signup-link">Login here</a>
            </p>
        </div>
    </section>

</div>
</body>
</html>
