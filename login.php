<?php

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/classes/User.php';

if (User::isUser()) {
    header('Location: /');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error = 'Имя пользователя или пароль неправильно.';
    if (User::login($_POST['username'], $_POST['password'])) {
        header('Location: /admin.php');
    }
}

?>
<div class="container">
    <h1 class="mt-3">Авторизация</h1>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?= $error ?>
        </div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Имя пользователя</label>
            <input type="text" class="form-control" id="username" name="username">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Пароль</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <button type="submit" class="btn btn-primary">Войти</button>
    </form>
</div>
