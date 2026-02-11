<?php

namespace Controller;


use Model\User;

class UserController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function getRegistrate()
    {
        session_start();
        if (!isset($_SESSION['userId'])) {
            header('Location: /catalog');
        }
        require_once '../Views/registration_form.php';
    }

    public function registrate()
    {
        $errors = $this->validateRegistrate($_POST);

        if (empty($errors)) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $passwordRep = $_POST['psw'];

            $password = password_hash($password, PASSWORD_DEFAULT);


            $result = $this->userModel->getByUsername($name, $email, $password);

            $result = $this->userModel->getByEmail($email);

            print_r($result);

        }
        require_once '../Views/registration_form.php';
    }

    private function validateRegistrate(array $data): array
    {
        $errors = [];

        $errorName = $this->validateName($data);

        if (!empty($errorName)) {
            $errors['name'] = $errorName;
        }

        if (isset($data['email'])) {
            $email = $data['email'];
            if (strlen($email) < 3) {
                $errors['email'] = "Email не может содержать меньше 3 символов";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'incorrect email';
            } else {

                $user = $this->userModel->ValidateCountRegistrate($email);
                require_once '../Views/registration_form.php';

                if ($user !== 0) {
                    $errors['email'] = 'Этот email уже существует';
                }
            }
        } else {
            $errors['email'] = 'Этот email должен быть заполнен!';
        }

        if (isset($data['password'])) {
            $password = $data['password'];
            if (strlen($password) < 5) {
                $errors['password'] = 'пароль не должен быть меньше 5 символов';
            }

            $passwordRepeat = $data['psw'];
            if ($password !== $passwordRepeat) {
                $errors['psw'] = 'Пароли не совпадают!';
            }
        } else {
            $errors['psw'] = 'Пароль должен быть заполнен!';
        }
        return $errors;
    }

    private function validateName(array $data): null|string
    {
        if (isset($data['name'])) {
            $name = $data['name'];
            if (strlen($name) < 3) {
                return 'имя не может содержать меньше 3 символов';
            }
            return null;
        } else {
            return 'имя должно быть заполнено';
        }
    }


    public function getLogin()
    {

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        require_once '../Views/login_form.php';
    }

    public function login()
    {

        $errors = $this->validateLogin($_POST);

        if (empty($errors)) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // EMAIL LOGIN
            $user = $this->userModel->getByEmailLogin($username);

            if (!empty($user)) {
                $passwordDb = $user['password'];

                if (password_verify($password, $passwordDb)) {
                    session_start();
                    $_SESSION['userId'] = $user['id'];
                    header('Location: /catalog');
                    exit(); //  exit после header
                } else {
                    $errors['password'] = 'пароль указан неверно';
                }
            } else {
                $errors['username'] = 'Пользователя с таким логином не существует';
            }
            return $errors;
        }
        require_once '../Views/login_form.php';
    }

    public function validateLogin(array $data): array
    {
        $errors = [];

        if (!isset($data['username'])) {
            $errors['username'] = 'поле @email должен быть заполнен';
        }
        if (!isset($data['password'])) {
            $errors['password'] = 'поле pass должен быть заполнен';
        }
        return $errors;
    }
}

