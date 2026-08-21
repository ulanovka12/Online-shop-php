<?php

namespace Controller;

use Model\Product;
use Model\User_products;
use Request\ProductRequest;

class ProductController extends BaseController
{

    private Product $productModel;
    private User_Products $user_productsModel;

    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
        $this->user_productsModel = new User_products();
    }

    public function getProducts()
    {
        // БАГ: условие было перевёрнуто. check() возвращает true для уже вошедшего пользователя,
        // а форма "Добавление продукта" ниже требует залогиненного юзера (в product() без него сразу
        // редирект на /login). БЫЛО: if ($this->authService->check()) — блокировало как раз тех, кто
        // вошёл, а гостей пускало на форму, которая им всё равно бесполезна. СТАЛО: if (!check())
        // — на форму пускаем только вошедших, гостей отправляем логиниться.
        if (!$this->authService->check()) {
            header("Location: /login");
            exit();
        }
        require_once '../Views/add_product_form.php';
    }

    public function product(ProductRequest $request)
    {
        $user = $this->authService->getCurrentUser();
        if (!$user) {
            header('Location: /login');
            exit;
        }

        // БАГ: результат validateProduct() нигде не сохранялся и не проверялся, поэтому форма
        // могла отправить несуществующий product_id или amount <= 0, и код всё равно продолжал бы
        // работать дальше. БЫЛО: $request->validateProduct(); (результат отбрасывался) -> СТАЛО:
        // сохраняем ошибки и, если они есть, прерываемся и возвращаем пользователя в каталог.
        $errors = $request->validateProduct();
        if (!empty($errors)) {
            header("Location: /catalog");
            exit();
        }

        // БАГ: была объявлена переменная $amount = 1 с комментарием "всегда добавляем одну единицу",
        // но нигде не использовалась — вместо неё код читал $request->getAmount() (правильно) для новой
        // записи, а для уже существующей записи чуть ниже жёстко прибавлял "+ 1" вместо количества
        // из запроса. Из-за этого, например, форма add_product_form.php (где можно ввести любое amount)
        // при повторном добавлении того же товара игнорировала введённое число и всегда прибавляла 1.
        // БЫЛО: $newAmount = $existing->getAmount() + 1; -> СТАЛО: прибавляем именно $request->getAmount().
        $amount = $request->getAmount();

        // Проверяем, есть ли уже этот товар у пользователя
        $existing = $this->user_productsModel->getUserProduct($request->getProductId(), $user->getId());

        if ($existing === null) {
            // Если нет – вставляем новую запись с указанным количеством
            $this->user_productsModel->insertUserProduct($user->getId(), $request->getProductId(), $amount);
        } else {
            // Если есть – увеличиваем количество на amount из запроса
            $newAmount = $existing->getAmount() + $amount;
            $this->user_productsModel->updateUserProduct($newAmount, $user->getId(), $request->getProductId());
        }

        header("Location: /catalog");
        exit();
    }

    public function catalog()
    {
        $user = $this->authService->getCurrentUser();

        // Получаем все товары
        $products = $this->productModel->getAll();

        // БАГ (утечка данных): было "$user = $this->authService->getCurrentUser() ?? 1;" — если сессии
        // нет, $user становился числом 1 (как будто это ID пользователя), а не null. Дальше стояла
        // проверка "if (!$user)", но число 1 в PHP всегда true, поэтому этот код никогда не выполнялся,
        // а гостю (!) подставлялась корзина РЕАЛЬНОГО пользователя с id=1 — то есть любой незалогиненный
        // посетитель видел количество товаров в каталоге, как будто оно из чужой корзины.
        // БЫЛО: гость получал корзину user_id=1 -> СТАЛО: у гостя (нет объекта $user) корзина просто
        // пустая, поэтому у всех товаров в каталоге будет amount = 0, как и должно быть для незалогиненных.
        if ($user) {
            $userProducts = $this->user_productsModel->getAllUserProductByUserId($user->getId());
        } else {
            $userProducts = [];
        }

        // Превращаем корзину в массив [product_id => amount]
        $amounts = [];
        foreach ($userProducts as $up) {
            $amounts[$up->getProductId()] = $up->getAmount();
        }

        // Проставляем количество каждому товару
        foreach ($products as $product) {
            $productId = $product->getId();
            $amount = $amounts[$productId] ?? 0;
            $product->setAmount($amount);
        }

        // Передаём в представление
        require_once '../Views/catalog_page.php';
    }

}