<?php
/* @var DataBase $db */

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/classes/Item.php';

$items = Item::getItems();
?>
<div class="container">
    <h1 class="mt-3">Структура данных</h1>

    <div class="row">
        <div class="col">
            <?= renderItems($items) ?>
        </div>
        <div class="col-12 col-lg-4 pt-4 pt-lg-0 item_description d-none">
            <div class="card">
                <div class="card-body">

                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
