<?php

namespace Controller;

use Model\Product;
use Model\Reviews;
use Request\ReviewsRequest;

class ReviewsController extends BaseController
{
    private Product $productModel;
    private Reviews $ReviewsModel;


    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
        $this->ReviewsModel = new Reviews();
    }

    public function getReviews()
    {
        if (!$this->authService->check()) {
            header('Location: /login');
            exit;
        }

        $productId = (int)($_GET['product_id'] ?? 0);
        if ($productId <= 0) {
            http_response_code(404);
            echo 'Товар не указан';
            exit;
        }

        $product = $this->productModel->validateProductData($productId);
        if (!$product) {
            http_response_code(404);
            echo 'Товар не найден';
            exit;
        }

        $reviews = $this->ReviewsModel->getByProductId($productId);
        $currentUser = $this->authService->getCurrentUser();

        require_once '../Views/reviews.php';
    }

    public function Reviews(ReviewsRequest $request)
    {
        if (!$this->authService->check()) {
            header('Location: /login');
            exit;
        }

        $productId = $request->validate();
        if ($productId === null) {
            http_response_code(400);
            echo 'Неверный ID товара';
            exit;
        }

        // 3. Проверка существования товара
        $product = $this->productModel->validateProductData($productId);
        if ($product === null) {
            echo 'Товар не найден';
            exit;
        }

        $description = $request->validateDescription();
        if ($description === null) {
            $_SESSION['review_error'] = 'Отзыв должен содержать минимум 3 символа';
            header('Location: /reviews?product_id=' . $productId);
            exit;
        }
        $user = $this->authService->getCurrentUser();
        if (!$user) {
            $_SESSION['review_error'] = 'Вы не авторизованы';
            header('Location: /reviews?product_id=' . $productId);
            exit;
        }

        $success = $this->ReviewsModel->create(
            $productId,
            $user->getId(),
            $user->getName(),
            $description
        );

        if ($success) {
            $_SESSION['review_success'] = 'Спасибо за отзыв!';
        } else {
            $_SESSION['review_error'] = 'Не удалось сохранить отзыв';
        }

        header('Location: /reviews?product_id=' . $productId);
        exit;
    }
}