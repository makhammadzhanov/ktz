<?php

require_once __DIR__ . '/DataBaseTrait.php';

class User
{
    use DataBaseTrait;

    public static function isUser()
    {
        return isset($_SESSION['authorized']);
    }

    public static function login($username, $password)
    {
        $user = self::getByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['authorized'] = true;
            $_SESSION['user_id'] = $user['id'];
            return true;
        }

        return false;
    }

    public static function getById($id)
    {
        $db = self::getDb();
        $stmt = $db::$instance->prepare('SELECT * FROM `users` WHERE `id` = :id');
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getByUsername($username)
    {
        $db = self::getDb();
        $stmt = $db::$instance->prepare('SELECT * FROM `users` WHERE `username` = :username');
        $stmt->execute([':username' => $username]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function logout()
    {
        session_start();
        session_destroy();
        header('Location: /');
    }

    public static function setPassword($old_password, $new_password)
    {
        $db = self::getDb();
        $user = self::getCurrentUser();

        if ($user && password_verify($old_password, $user['password'])) {
            $stmt = $db::$instance->prepare('UPDATE `users` SET `password` = :password WHERE `id` = :id');
            return $stmt->execute([
                ':id' => $user['id'],
                ':password' => password_hash($new_password, PASSWORD_DEFAULT),
            ]);
        }

        return false;
    }

    public static function getCurrentUser()
    {
        if (isset($_SESSION['user_id'])) {
            return self::getById($_SESSION['user_id']);
        }

        return null;
    }
}