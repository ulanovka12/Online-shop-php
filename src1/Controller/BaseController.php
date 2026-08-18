<?php

namespace Controller;

use Service\Auth\AuthCookieService;
use Service\Auth\AuthInterface;
use Service\Auth\AuthSessionService;

class BaseController
{
    protected AuthInterface $authService;

    public function __construct()
    {
        $this->authService = new AuthSessionService();
    }
}