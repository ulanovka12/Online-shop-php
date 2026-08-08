<?php

namespace Controller;

use Model\Product;
use Model\Reviews;

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

        $productId = (int) ($_GET['product_id'] ?? 0);
        if ($productId <= 0) {
            http_response_code(404);
            echo 'Товар не указан';
            exit;
        }

        $product = $this->productModel->validateProductData($productId);
        if ($product === null) {
            http_response_code(404);
            echo 'Товар не найден';
            exit;
        }

        // Получаем отзывы
        $reviews = $this->ReviewsModel->getByProductId($productId);
        $currentUser = $this->authService->getCurrentUser();

        require_once '../Views/reviews.php';
    }

    // добавление нового отзыва
    public function Reviews()
    {
        // Разрешаем только POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Method Not Allowed';
            exit;
        }

        // Проверка авторизации
        if (!$this->authService->check()) {
            header('Location: /login');
            exit;
        }

        $productId = (int) ($_GET['product_id'] ?? 0);
        if ($productId <= 0) {
            http_response_code(400);
            echo 'Неверный ID товара';
            exit;
        }

        // Проверяем, существует ли товар
        $product = $this->productModel->validateProductData($productId);
        if ($product === null) {
            http_response_code(404);
            echo 'Товар не найден';
            exit;
        }

        // Обрабатываем добавление
        $this->handleAddReview($productId);

        // После успешного добавления – редирект на страницу отзывов (чтобы избежать повторной отправки)
        header('Location: /reviews?product_id=' . $productId);
        exit;
    }

    // --- Вспомогательный метод для добавления ---
    private function handleAddReview(int $productId)
    {
        $user = $this->authService->getCurrentUser();
        if (!$user) {
            $_SESSION['review_error'] = 'Вы не авторизованы';
            return;
        }

        $description = trim($_POST['description'] ?? '');
        if (mb_strlen($description) < 3) {
            $_SESSION['review_error'] = 'Отзыв должен содержать минимум 3 символа';
            return;
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
    }
}