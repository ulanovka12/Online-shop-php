<?php

function validate(array $data): array
{

    $errors = [];

    if (!isset($data['username'])){
        $errors['username'] = 'поле @email должен быть заполнен';
    }
    if (!isset($data['password'])){
        $errors['password'] = 'поле pass должен быть заполнен';
    }
    return $errors;
}

$errors = validate($_POST);

if (empty($errors)) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $pdo = new PDO ('pgsql:host = postgres;dbname=mydb', 'king', 'qwerty');
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $username]);
    $user = $stmt->fetch();

    if (!empty($user)){
        $passwordDb = $user['password'];

        if (password_verify($password, $passwordDb)){

            session_start();
            $_SESSION['userId'] = $user['id'];

            header('Location: /catalog');
        } else {
            $errors['username'] = 'пароль указан неверно';
        }
    } else{
        $errors['username'] = 'Пользователя с таким логином не существует';
    }
}

require_once './login/login_form.php';


