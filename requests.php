<?php

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/classes/Item.php';
require_once __DIR__ . '/classes/User.php';

$result = [
    'success' => false
];

$post = file_get_contents('php://input');
$post = json_decode($post, true);

if (json_last_error() !== JSON_ERROR_NONE || !isset($post['action'])) {
    echo asJson($result);
    exit;
}

$post = filter($post);

$restricted_actions = [
    'edit_item', 'save_item', 'add_item', 'delete_item'
];

if (in_array($post['action'], $restricted_actions) && !User::isUser()) {
    echo asJson($result);
    exit;
}

if ($post['action'] === 'get_description') {
    $item = Item::getById($post['id']);

    if ($item) {
        $result = [
            'success' => true,
            'data' => [
                'description' => $item['description']
            ]
        ];
    }
    echo asJson($result);
    exit;
}

if ($post['action'] === 'edit_item') {
    $item = Item::getById($post['id']);

    if ($item) {
        $parents = Item::getAvailableParents($item);
        $result = [
            'success' => true,
            'data' => [
                'item' => $item,
                'parents' => $parents
            ]
        ];
    }
    echo asJson($result);
    exit;
}

if ($post['action'] === 'save_item') {
    if (!filled($post)) {
        echo asJson($result);
        exit;
    }
    if ((int) $post['id'] === 0) {
        $result = [
            'success' => Item::add($post),
        ];
        echo asJson($result);
        exit;
    }

    $item = Item::getById($post['id']);

    if ($item) {
        $result = [
            'success' => Item::save($item, $post),
        ];
    }
    echo asJson($result);
    exit;
}

if ($post['action'] === 'add_item') {
    $parents = Item::getAvailableParents();
    $result = [
        'success' => true,
        'data' => [
            'parents' => $parents
        ]
    ];

    echo asJson($result);
    exit;
}

if ($post['action'] === 'delete_item') {
    $item = Item::getById($post['id']);

    if ($item) {
        $result = [
            'success' => Item::delete($item),
        ];
    }
    echo asJson($result);
    exit;
}
