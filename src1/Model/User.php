<?php

namespace Model;

class User extends Model
{
    public function getByEmail(string $email): array|false
    {

        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);

        $result = $stmt->fetch();

        return $result;

    }

    public function getByUsername(string $name, string $email, string $password): array|false
    {

        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password]);

        $result = $stmt->fetch();

        return $result;
    }

    public function getByEmailLogin(string $username) : array|false
    {

        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $username]);

        $user = $stmt->fetch();

        return $user;
    }

    public function ValidateCountRegistrate(string $email):array|false
    {

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        return $user;

    }
}