<?php

namespace app\Models;


use app\Config\Database;

class User{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function AddUser ($email, $password, $token, $created_at) {

        $params = [$email, $password, $token, $created_at];

        $query = "INSERT INTO users values(NULL, ?, ?, ?, 0, ?, 2)";

        $this->database->insert_update($query, $params);
    }

    public function ConfirmRegister ($token) {
        $params = [$token];

        $query = "UPDATE users SET active = 1 WHERE token = ?";

        $this->database->insert_update($query, $params);
    }
    public function login ($email, $password) {
        $params = [$email, $password];

        $query = "SELECT * FROM users WHERE email = ? AND password = ? AND active = 1";

        $data = $this->database->executeAll($query, $params);
        if(!count($data)) {
            return null;
        }
        return $data[0];

    }
}