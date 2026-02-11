<?php

namespace Controller;

use Model\Profile;


class ProfileController
{
    private Profile $profileModel;

    public function __construct()
    {
        $this->profileModel = new Profile();
    }


    public function Profile()
    {

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (isset($_SESSION['userId'])) {

            $userId = $_SESSION['userId'];
//
//            require_once '../Model/Profile.php';
//            $profileModel = new Profile();

            $user = $this->profileModel->getByProfile($userId);

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

            $user = $this->profileModel->getByEditId($userId);
//            $pdo = new PDO ('pgsql:host = postgres;dbname=mydb', 'king', 'qwerty');
//            $stmt = $pdo->query("SELECT * FROM users WHERE id = $userId");
//            $user = $stmt->fetch();
            print_r($user);
            require_once '../Views/edit_handle_profile.php';
        } else {
            header('location: /login');
        }
    }


}