<?php

namespace Model;

class Profile extends Model
{
    public function getByProfile(int $userId): array|false
    {

        $stmt = $this->pdo->query("SELECT * FROM users WHERE id = $userId");
        $user = $stmt->fetch();

        return $user;

    }
    public function getByEditId(int $userId): array|false
    {

        $stmt = $this->pdo->query("SELECT * FROM users WHERE id = $userId");
        $user = $stmt->fetch();

        return $user;
    }

}