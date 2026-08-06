<?php

namespace Controller;

use Service\AuthService;

class ReviewsController extends BaseController
{
    public function getReviews()
    {
        if (!$this->authService->check()) {
            header('Location: /login');
            exit;
        }

        require_once '../Views/reviews.php';
    }

}