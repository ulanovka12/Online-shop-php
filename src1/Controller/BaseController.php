<?php

namespace Controller;

class BaseController
{
    public function check():bool
    {
        $this->startSession();
        return (!isset($_SESSION['userId']));
    }


    public function getCurrentUserId():int
    {
        $this->startSession();
        return $_SESSION['userId'];
    }


    private function startSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
}