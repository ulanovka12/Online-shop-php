<?php

namespace Request;


class LoginRequest
{

    public function __construct(private array $data)
    {}
    public function getEmail(): string
    {
        return $this->data['email'];
    }
    public function getPassword(): string
    {
        return $this->data['password'];
    }

    public function validateLogin(): array
    {
        $errors = [];

        if (!isset($this->data['email'])) {
            $errors['email'] = 'поле @email должен быть заполнен';
        }
        if (!isset($this->data['password'])) {
            $errors['password'] = 'поле pass должен быть заполнен';
        }
        return $errors;
    }

}
