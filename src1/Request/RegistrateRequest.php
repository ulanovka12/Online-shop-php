<?php

namespace Request;

use Model\User;

class RegistrateRequest
{
    private User $userModel;

    public function __construct(private array $data)
    {
        $this->userModel = new User();
    }

    public function getName()
    {
        return $this->data['name'];
    }

    public function getEmail()
    {
        return $this->data['email'];
    }
    public function getPassword()
    {
        return $this->data['password'];
    }


    public function validate(): array
    {
        $errors = [];

        $errorName = $this->validateName();

        if (!empty($errorName)) {
            $errors['name'] = $errorName;
        }
        if (isset($this->data['email'])) {
            $email = $this->data['email'];
            if (strlen($email) < 3) {
                $errors['email'] = "Email не может содержать меньше 3 символов";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'incorrect email';
            } else {

                $user = $this->userModel->getByEmail($email);

                if ($user !== null) {
                    $errors['email'] = 'Этот email уже существует';
                }
            }
        } else {
            $errors['email'] = 'Этот email должен быть заполнен!';
        }

        if (isset($this->data['password'])) {
            $password = $this->data['password'];
            if (strlen($password) < 5) {
                $errors['password'] = 'пароль не должен быть меньше 5 символов';
            }
            $passwordRepeat = $this->data['psw'];
            if ($password !== $passwordRepeat) {
                $errors['psw'] = 'Пароли не совпадают!';
            }
        } else {
            $errors['psw'] = 'Пароль должен быть заполнен!';
        }
        return $errors;
    }

    private function validateName(): null|string
    {
        if (isset($this->data['name'])) {
            $name = $this->data['name'];
            if (strlen($name) < 3) {
                return 'имя не может содержать меньше 3 символов';
            }
            return null;
        } else {
            return 'имя должно быть заполнено';
        }
    }
}