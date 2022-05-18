<?php

require_once __DIR__ . '/DataBaseTrait.php';

class Item
{
    use DataBaseTrait;

    public static function save($item, $post)
    {
        $db = self::getDb();
        $sql = 'UPDATE `structure` SET `title` = :title,
                       `description` = :description, `parent_id` = :parent_id WHERE `id` = :id';
        $stmt = $db::$instance->prepare($sql);

        return $stmt->execute([
            ':id' => $item['id'],
            ':title' => $post['name'],
            ':description' => $post['description'],
            ':parent_id' => $post['parent'],
        ]);
    }

    public static function add($post)
    {
        $db = self::getDb();
        $sql = 'INSERT INTO `structure` (`title`, `description`, `parent_id`) VALUES (:title, :description, :parent_id)';
        $stmt = $db::$instance->prepare($sql);

        return $stmt->execute([
            ':title' => $post['name'],
            ':description' => $post['description'],
            ':parent_id' => $post['parent'],
        ]);
    }

    public static function delete($item)
    {
        $db = self::getDb();
        $childs = self::getItems($item['id'], false);

        if ($childs !== null) {
            foreach ($childs as $child) {
                self::delete($child);
            }
        }

        $sql = 'DELETE FROM `structure` WHERE `id` = :id';
        $stmt = $db::$instance->prepare($sql);

        return $stmt->execute([
            ':id' => $item['id'],
        ]);
    }

    private static function createBranch(&$parents, $children)
    {
        $tree = [];
        foreach ($children as $child) {
            if (isset($parents[$child['id']])) {
                $child['children'] =
                    self::createBranch($parents, $parents[$child['id']]);
            }
            $tree[] = $child;
        }
        return $tree;
    }

    private static function createTree($flat, $root = 0)
    {
        $parents = [];
        foreach ($flat as $item) {
            $item['children'] = [];
            $parents[$item['parent_id']][] = $item;
        }

        return self::createBranch($parents, $parents[$root]);
    }

    public static function getItems($parent_id = 0, $tree = true)
    {
        $db = self::getDb();
        $sql = 'SELECT * FROM `structure`';
        if ($parent_id !== 0) {
            $sql .= ' WHERE `parent_id` = :parent_id';
        }
        $stmt = $db::$instance->prepare($sql);
        $stmt->execute([':parent_id' => $parent_id]);

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$items) {
            return null;
        }

        if ($tree) {
            return self::createTree($items);
        }

        return $items;
    }

    public static function getById($id)
    {
        $db = self::getDb();
        $stmt = $db::$instance->prepare('SELECT * FROM `structure` WHERE `id` = :id');
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAvailableParents($item = null)
    {
        $db = self::getDb();
        $main_parent = [
            'id' => 0,
            'title' => 'Главное',
            'parent_id' => 0
        ];
        $sql = 'SELECT * FROM `structure`';

        if ($item !== null) {
            $sql .= ' WHERE `id` != :id';
        }

        $stmt = $db::$instance->prepare($sql);

        $stmt->execute([
            ':id' => $item['id']
        ]);

        return array_merge([$main_parent], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

}