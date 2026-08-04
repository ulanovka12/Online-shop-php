<?php

namespace Controller;

use Service\AuthService;

class BaseController
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
}