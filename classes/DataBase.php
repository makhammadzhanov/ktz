<?php

class DataBase
{
    public static $instance = null;
    private $hostname = 'localhost';
    private $username = 'devel_ktz';
    private $password = 'L>dWKIFkWKdYeFdBAHG5T<Oe';
    private $database = 'devel_ktz';

    public function __construct()
    {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO("mysql:host=$this->hostname;dbname=$this->database;charset=utf8", $this->username, $this->password);
            } catch (Exception $e) {
                self::$instance = null;
            }
        }
    }

    public function insertAdmin()
    {
        $sql = 'INSERT INTO `users` (`username`, `password`, `role`) VALUES (:username, :password, :role)';
        $stmt = self::$instance->prepare($sql);
        return $stmt->execute([
            ':username' => 'admin',
            ':password' => password_hash('admin', PASSWORD_DEFAULT),
            ':role' => 10
        ]);
    }
}