<?php

trait DataBaseTrait {

    public static function getDb()
    {
        $db = new DataBase;

        if ($db::$instance === null) {
            echo 'Невозможно подключиться к базе данных.';
            exit;
        }

        return $db;
    }

}
