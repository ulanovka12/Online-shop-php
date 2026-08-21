<?php

namespace Controller;

use DTO\CartCreateDTO;
use Model\Product;
use Model\User_products;
use Request\AddProductRequest;
use Service\CartService;
use Request\CartRequest;

class CartController extends BaseController
{
    private Product $product;
    private User_products $user_productsModel;
    private CartService $cartService;


    public function __construct()
    {
        parent::__construct();
        $this->product = new Product();
        $this->user_productsModel = new User_products();
        $this->cartService = new CartService();

    }
    public function cart()
    {
        if (!$this->authService->check()) {
            header('Location: /login');
            exit();
        }

        $user = $this->authService->getCurrentUser();

        $userProducts = $this->user_productsModel->getAllUserProductByUserId($user->getId());
//        print_r($userId);
//        print_r($userProducts);
        $products = [];

        foreach ($userProducts as $userProduct) {
            $productId = $userProduct->getProductId();

            $product = $this->product->getOneById($productId);
//            print_r($product);

            //проверяем найден ли продукт
            if ($product !== null) {
                $product->setAmount($userProduct->getAmount());
                $products[] = $product;
            } else {
                error_log("продукт не найден " . $productId);
            }
        }
        require_once '../Views/cart.php';
        return $products;
    }

    public function updateCart(CartRequest $request)
    {
        // БАГ: условие было перевёрнуто — if ($this->authService->check()) отправляло на /login
        // именно ЗАЛОГИНЕННОГО пользователя, а гостя (для которого check() === false) пропускало дальше,
        // хотя дальше код обращается к $user->getId() и упал бы с ошибкой для гостя.
        // БЫЛО: if ($this->authService->check()) -> СТАЛО: if (!$this->authService->check()).
        // ПОЧЕМУ: check() возвращает true, если пользователь уже вошёл в систему, значит на /login
        // нужно отправлять как раз тех, у кого check() === false (не вошёл).
        if (!$this->authService->check()) {
            header('Location: /login');
            exit();
        }

        // БАГ: результат CartValidate() игнорировался, поэтому даже при ошибке валидации
        // (например, product_id <= 0) код продолжал выполняться и пытался обновить корзину.
        // БЫЛО: $request->CartValidate(); (без проверки) -> СТАЛО: результат сохраняется в $errors
        // и при наличии ошибок мы прерываем выполнение и возвращаем пользователя обратно в корзину.
        $errors = $request->CartValidate();
        if (!empty($errors)) {
            header('Location: /cart');
            exit();
        }

        $user = $this->authService->getCurrentUser();

        $this->user_productsModel->getUpdateProduct($user->getId(), $request->getProductId(), $request->getAmount());


        header('Location: /cart');
        exit();
    }

    public function removeFromCart()
    {
        // Та же ошибка с перевёрнутым условием, что и в updateCart() — см. комментарий выше.
        if (!$this->authService->check()) {
            header('Location: /login');
            exit();
        }

        $user = $this->authService->getCurrentUser();

        // БАГ: метод объявлял обязательный параметр (int $productId), но роутер (Core\App::run())
        // умеет передавать в контроллер только объект Request (если он указан в маршруте) — параметров
        // из GET-строки он не подставляет. Маршрут '/remove-from-cart' зарегистрирован без Request-класса,
        // значит вызов $controller->removeFromCart() всегда шёл БЕЗ аргументов и падал с ошибкой
        // "Too few arguments to function". БЫЛО: параметр функции -> СТАЛО: читаем product_id
        // напрямую из $_GET, откуда его и передаёт ссылка в cart.php ("/remove-from-cart?product_id=...").
        $productId = (int)($_GET['product_id'] ?? 0);

        if ($productId > 0) {
            $this->user_productsModel->deleteByProductId($user->getId(), $productId);
        }
        header('Location: /cart');
        exit();
    }

    public function clearCart()
    {
        if (!$this->authService->check()) {
            header('Location: /login');
            exit();
        }

        $user = $this->authService->getCurrentUser();
        $this->user_productsModel->deleteByUserId($user->getId());

        header('Location: /cart');
        exit();
    }

    public function addProduct(AddProductRequest $request)
    {
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();
            $errors = $request->validate();
            if (empty($errors)) {
                $dto = new CartCreateDTO($user->getId(), $request->getProductId(), $request->getAmount());
                $this->cartService->addProduct($dto);
            }
            header('Location: /catalog');
        } else{
            header('Location: /catalog');
            exit;
        }
    }

    public function decreaseProductFromCart(AddProductRequest $request)
    {
        if (!$this->authService->check()) {
            header('Location: /catalog');
            exit;
        }
        $user = $this->authService->getCurrentUser();
        $errors = $request->validate();
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: /cart');
            exit;
        }

        $dto = new CartCreateDTO(
            $user->getId(),
            $request->getProductId(),
            $request->getAmount()
        );

        $this->cartService->decreaseProductFromCart($dto);

        header('Location: /catalog');
        exit;
    }
}