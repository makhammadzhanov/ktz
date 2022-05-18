<?php

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/classes/User.php';

if (!User::isUser()) {
    header('Location: /');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post = filter($_POST);

    if ($post['new_password'] !== $post['rep_password']) {
        $error = 'Пароли не совпадают.';
    } elseif (User::setPassword($post['old_password'], $post['new_password'])) {
        User::logout();
    } else {
        $error = 'Старый пароль неправильно.';
    }

}

?>
<div class="container">
    <h1 class="mt-3">Изменить пароль</h1>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?= $error ?>
        </div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label for="old_password" class="form-label">Текущий пароль</label>
            <input type="password" class="form-control" id="old_password" name="old_password">
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">Новый пароль</label>
            <input type="text" class="form-control" id="new_password" name="new_password">
        </div>
        <div class="mb-3">
            <label for="rep_password" class="form-label">Подтвердите пароль</label>
            <input type="text" class="form-control" id="rep_password" name="rep_password">
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
</div>
