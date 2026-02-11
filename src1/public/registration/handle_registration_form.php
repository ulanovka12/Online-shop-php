<?php

function validate(array $data):array
{
    $errors = [];

    if (isset($data['email'])) {
        $email = $data['email'];
        if (strlen($email) < 3) {
            $errors['email'] = "email не может содержать меньше 3 символов";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Некорректный email";
        } else {
            //Соединеие с БД
            $pdo = new PDO ('pgsql:host = postgres;dbname=mydb', 'king', 'qwerty');
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $count = $stmt->fetchColumn();
            if ($count > 0) {
                $errors['email'] = 'Этот email уже зарегестрирован!';
            } else {
                $errors['email'] = "Email должен быть заполнен";
            }

            //Проверка совпдения паролей
            if (isset($data['password'])) {
                $password = $data['password'];
                if (strlen($password) < 3) {
                    $errors['password'] = 'Пароль не может содержать меньше 3 символов';
                }

                $passwordRep = $data['psw'];
                if ($password !== $passwordRep) {
                    $errors['psw-repeat'] = 'Пароли не совпадают';
                }

            } else {
                $errors['password'] = 'Пароль должен быть заполнен';
            }
        }
    }
    return $errors;

}

    function validateName(array $data): null|string
    {
        if (isset($data['name'])) {
            $name = $data['name'];
            if (strlen($name) < 3) {
                return 'Имя не должно содержать меньше 3 символов';
            }

            return null;
        } else {
            return "Имя должно быть заполнено";
        }
    }

    $errors = validate($_POST);

    if (empty($errors)) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordRep = $_POST['psw-repeat'];
        $password = password_hash($password, PASSWORD_DEFAULT);

        $pdo = new PDO ('pgsql:host = postgres;dbname=mydb', 'king', 'qwerty');

        //Добавления пользователя
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute([':name' => $name, ':email' => $email, ':password' => $password]);

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);

        $result = $stmt->fetch();
        print_r($result);
    }

    require_once '/registration_form.php';

?>



