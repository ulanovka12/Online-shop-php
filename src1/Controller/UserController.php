<?php
// проверка работы коммита
namespace Controller;

use Model\User;

//// cache_clear.php
//if (function_exists('opcache_reset')) {
//    opcache_reset();
//    echo "OpCache очищен<br>";
//}
//if (function_exists('apc_clear_cache')) {
//    apc_clear_cache();
//    echo "APC очищен<br>";
//}
//
//echo "Готово. Теперь обновите страницу /profile";

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
            header('Location: /login');
        }
        require_once '../Views/registration_form.php';
    }

    public function registrate()
    {
        $errors = $this->validateRegistrate($_POST);

        if (!empty($errors)) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $passwordRep = $_POST['psw'];

            $password = password_hash($password, PASSWORD_DEFAULT);


            $result = $this->userModel->getByUsername($name, $email, $password);
//            print_r($result);

            $result = $this->userModel->getByEmail($email);

        }
        require_once '../Views/registration_form.php';
    }

//    private function validateRegistrate(array $data): array
//    {
//        $errors = [];
//
//        $errorName = $this->validateName($data);
//
//        if (!empty($errorName)) {
//            $errors['name'] = $errorName;
//        }
//
//        // Валидация email
//        if (isset($data['email'])) {
//            $email = $data['email'];
//            if (strlen($email) < 3) {
//                $errors['email'] = "Email не может содержать меньше 3 символов";
//            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//                $errors['email'] = 'incorrect email';
//            } else {
//                // Проверяем, существует ли email в базе данных
//                $exitUser = $this->userModel->ValidateCountRegistrate($email);
//
////                 require_once '../Views/registration_form.php';
//
//                if ($exitUser !== false) {
//                    $errors['email'] = 'Этот email уже существует';
//                }
//            }
//        } else {
//            $errors['email'] = 'Этот email должен быть заполнен!';
//        }
//
//        // Валидация пароля
//        if (isset($data['password']) && isset($data['psw'])) {
//            $password = $data['password'];
//            $passwordRepeat = $data['psw'];
//
//            if (strlen($password) < 5) {
//                $errors['password'] = 'пароль не должен быть меньше 5 символов';
//            }
//
//            if ($password !== $passwordRepeat) {
//                $errors['psw'] = 'Пароли не совпадают!';
//            }
//        } else {
//            if (!isset($data['password'])) {
//                $errors['password'] = 'Пароль должен быть заполнен!';
//            }
//            if (!isset($data['psw'])) {
//                $errors['psw'] = 'Подтверждение пароля должно быть заполнено!';
//            }
//        }
//
//        return $errors;
//    }

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

                if ($user !== false) {
                    $errors['email'] = 'Этот email уже существует';
                }
            }
        } else {
            $errors['email'] = 'Этот email должен быть заполнен!';
        }

        if (isset($data['password'])) {
            $password = $user->getPassword();
//            $password = $data['password'];
            if (strlen($password) < 5) {
                $errors['password'] = 'пароль не должен быть меньше 5 символов';
            }

            $passwordRepeat = $user->getPswPassword();
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
                //$passwordDb = $user['password'];
                $passwordDb = $user->getPassword();

                if (password_verify($password, $passwordDb)) {
                    session_start();
                    $_SESSION['userId'] = $user->getId();
                    header('Location: /catalog');
                    exit(); //  exit после header
                } else {
                    $errors['password'] = 'пароль или логин указан неверно';
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
    public function profile()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (isset($_SESSION['userId'])) {
            $userId = $_SESSION['userId'];

            $user = $this->userModel->getByIdProfile($userId);

            print_r($user);

            require_once '../Views/profile_form.php';
        } else {
            header('Location: /login');
        }
    }

    public function editProfile()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (isset($_SESSION['userId'])) {
            $userId = $_SESSION['userId'];

            $user = $this->userModel->getByIdProfile($userId);

            print_r($user);

            require_once '../Views/edit_handle_profile.php';
        } else {
            header('location: /login');
        }
    }

}

