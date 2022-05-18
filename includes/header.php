<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/../classes/User.php';

$menus = [];
$menus[] = [
    'title' => 'Управление',
    'link' => '/admin.php'
];

if (User::isUser()) {
    $menus[] = [
        'title' => 'Изменить пароль',
        'link' => '/password.php'
    ];
    $menus[] = [
        'title' => 'Выход',
        'link' => '/logout.php'
    ];
}

?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/main.css" rel="stylesheet">

    <title>Структура данных</title>
</head>
<body>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">Главная</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#main_menu" aria-controls="main_menu"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="main_menu">
            <ul class="navbar-nav mb-2 mb-lg-0 w-100 justify-content-end">
                <?php foreach ($menus as $menu): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $menu['link'] ?>"><?= $menu['title'] ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</nav>
