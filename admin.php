<?php

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/classes/User.php';
require_once __DIR__ . '/classes/Item.php';

if (!User::isUser()) {
    header('Location: /login.php');
}

$items = Item::getItems();
?>
    <div class="container">
        <h1 class="mt-3">Структура данных (управление)</h1>

        <button class="btn btn-primary my-2" data-action="add">Добавить</button>

        <div class="row">
            <div class="col">
                <?= renderItems($items, 0, 'edit') ?>
            </div>
            <div class="col-12 col-lg-4 pt-4 pt-lg-0 form_container d-none">
                <div class="card">
                    <div class="card-body">
                        <form id="edit_form">
                            <div class="mb-3">
                                <label for="name" class="form-label">Название</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Описание</label>
                                <textarea id="description" class="form-control" name="description" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="parents" class="form-label">Родитель</label>
                                <select id="parents" class="form-select" aria-label="Родитель" name="parent"></select>
                            </div>
                            <input type="hidden" name="id" id="id">
                            <div class="d-flex form_buttons">
                                <button type="submit" class="btn btn-primary" id="submit">Сохранить</button>
                                <button type="button" class="btn btn-danger d-none" data-action="delete" data-id="0">
                                    Удалить
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
require_once __DIR__ . '/includes/footer.php';
