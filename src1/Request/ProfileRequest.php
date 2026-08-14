<?php

namespace Request;

use Model\User;

class ProfileRequest
{

    private User $userModel;


    public function __construct(private array $data)
    {
        $this->userModel = new User();
    }

    public function getName(): string
    {
        return $this->data['name'];
    }
    public function getEmail(): string
    {
        return $this->data['email'];
    }
    public function getPassword(): string
    {
        return $this->data['password'];
    }

    public function validateProfileUpdate(): array
    {
        $errors = [];

        $errorName = $this->validateName();
        if (!empty($errorName)) {
            $errors['name'] = $errorName;
        }
        if (isset($this->data['email'])) {
            $email = $this->data['email'];
            if (strlen($email) < 3) {
                $errors['email'] = 'Email не может содержать меньше 3 символов';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Неправильный email';
            } else {
                $user = $this->userModel->getByEmail($email);
                if ($user !== null && $user->getId()) {
                    $errors['email'] = 'Этот Email уже существует';
                }
            }
        } else {
            $errors['email'] = 'Этот email должен быть заполнен!';
        }
        if (isset($this->data['password']) && trim($this->data['password']) !== '' && strlen(trim($this->data['password'])) < 5) {
            $errors['password'] = 'пароль не должен быть меньше 5 символов';
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