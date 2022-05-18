<?php

require_once __DIR__ . '/../classes/DataBase.php';

$db = new DataBase;

if ($db::$instance === null) {
    echo 'Невозможно подключиться к базе данных.';
    exit;
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function getTemplates() {
    $template_ul = '<ul class="list-group rounded-0{collapse}" id="items_{id}">{items}</ul>';
    $template_li = '<li class="list-group-item d-flex align-items-center">{content}</li>';
    $template_content = <<<HTML
        <div class="text-start w-100">
            <button class="btn text-start w-100" data-action="{action}" data-id="{id}">
                {title}
            </button>
        </div>
        {expander}
HTML;

    $template_expander = <<<HTML
        <button data-bs-toggle="collapse" href="#items_{id}" role="button" aria-expanded="false"
                aria-controls="items_{id}" class="btn btn-primary badge rounded-pill">+</button>
HTML;


    return [
        'ul' => $template_ul,
        'li' => $template_li,
        'content' => $template_content,
        'expander' => $template_expander
    ];
}

function renderItems(array $items, $parent_id = 0, $action = 'open') {
    $html = '';
    $templates = getTemplates();
    $items_html = '';

    foreach ($items as $item) {
        if ((int) $item['parent_id'] === $parent_id) {
            $childs_html = '';

            if (count($item['children']) > 0) {
                $childs_html = renderItems($item['children'], (int) $item['id'], $action);
            }

            $expander = count($item['children']) > 0 ? strtr($templates['expander'], [
                '{id}' => $item['id'],
            ]) : null;

            $items_html .= strtr($templates['li'], [
                '{content}' => strtr($templates['content'], [
                    '{id}' => $item['id'],
                    '{title}' => $item['title'],
                    '{action}' => $action,
                    '{expander}' => $expander
                ])
            ]);
            $items_html .= $childs_html;
        }
    }

    $collapse = (int) $parent_id === 0 ? null : ' collapse';

    if ($action === 'edit') {
        $collapse .= ' show';
    }

    if (!empty($items_html)) {
        $html = strtr($templates['ul'], [
            '{id}' => $parent_id,
            '{collapse}' => $collapse,
            '{items}' => $items_html
        ]);
    }

    return $html;
}

function asJson(array $data) {
    header('Content-Type: application/json');
    return json_encode($data);
}

function filter(array $data) {
    $result = [];

    foreach ($data as $key => $value) {
        $result[$key] = htmlspecialchars($value);
    }

    return $result;
}

function filled(array $post) {
    $name = trim($post['name']);
    $description = trim($post['description']);
    $parent_id = trim($post['parent']);

    if (!empty($name) && !empty($description) && !empty($parent_id)) {
        return true;
    }

    return false;
}
