<?php
// проверка работы коммита
namespace Controller;

use Model\User;

class UserController extends BaseController
{
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }

    public function getRegistrate()
    {
        if ($this->authService->check()) {
            header('Location: /catalog');
            exit();
        }
        require_once '../Views/registration_form.php';
    }

    public function registrate()
    {

        $errors = $this->validateRegistrate($_POST);

        if (empty($errors)) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Создаём пользователя
            $userId = $this->userModel->getByUsername($name, $email, $password);
            if ($userId) {
                // Автоматический вход после регистрации (опционально)
                $this->authService->auth($_POST['email'], $_POST['password']);
                header('Location: /catalog');
                exit();
            } else {
                $errors['auth'] = 'Неверный email или пароль';
            }
        }
        require_once '../Views/registration_form.php';
    }

    private function validateRegistrate(array $data): array
    {
        $errors = [];

        $errorName = $this->validateName($data);

        if (!empty($errorName)) {
            $errors['name'] = $errorName;
        }
        if (isset($data['email'])) {
            $email = $data['email'];
            if (strlen($email) < 3) {
                $errors['email'] = "Email не может содержать меньше 3 символов";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'incorrect email';
            } else {

                $user = $this->userModel->getByEmail($email);

                if ($user !== null) {
                    $errors['email'] = 'Этот email уже существует';
                }
            }
        } else {
            $errors['email'] = 'Этот email должен быть заполнен!';
        }

        if (isset($data['password'])) {
            $password = $data['password'];
            if (strlen($password) < 5) {
                $errors['password'] = 'пароль не должен быть меньше 5 символов';
            }
            $passwordRepeat = $data['psw'];
            if ($password !== $passwordRepeat) {
                $errors['psw'] = 'Пароли не совпадают!';
            }
        } else {
            $errors['psw'] = 'Пароль должен быть заполнен!';
        }
        return $errors;
    }

    private function validateName(array $data): null|string
    {
        if (isset($data['name'])) {
            $name = $data['name'];
            if (strlen($name) < 3) {
                return 'имя не может содержать меньше 3 символов';
            }
            return null;
        } else {
            return 'имя должно быть заполнено';
        }
    }


    public function getLogin()
    {

        $this->authService->check();
        require_once '../Views/login_form.php';
    }

    public function login()
    {

        $errors = $this->validateLogin($_POST);

        if (empty($errors)) {

            $result = $this->authService->auth($_POST['email'], $_POST['password']);

            if ($result) {
                header('Location: /catalog');
                exit();
            } else {
                $errors['auth'] = 'Неверный email или пароль';
            }
        }
        require_once '../Views/login_form.php';
    }

    public function validateLogin(array $data): array
    {
        $errors = [];

        if (!isset($data['email'])) {
            $errors['email'] = 'поле @email должен быть заполнен';
        }
        if (!isset($data['password'])) {
            $errors['password'] = 'поле pass должен быть заполнен';
        }
        return $errors;
    }

    public function profile()
    {
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();

            $user = $this->userModel->getByIdProfile($user->getId());

//            print_r($user);

            require_once '../Views/profile_form.php';
        } else {
            header('Location: /login');
        }
    }

    public function editProfile()
    {
        if ($this->authService->check()) {
            $user = $this->authService->getCurrentUser();
            $user = $this->userModel->getByIdProfile($user->getId());

            // Забираем ошибки и старые данные из сессии (если есть)
            $errors = $_SESSION['form_errors'] ?? [];
            $old = $_SESSION['old_input'] ?? [];
            $flash = $_SESSION['flash_message'] ?? null;
            unset($_SESSION['form_errors'], $_SESSION['old_input'], $_SESSION['flash_message']);

            require_once '../Views/edit_handle_profile.php';
        } else {
            header('location: /login');
        }
    }

    public function updateProfile()
    {
        if (!$this->authService->check()) {
            header('Location: /login');
            exit();
        }

        $user = $this->authService->getCurrentUser();

        $userId = $user->getId(); //(!)

        // Загружаем текущие данные из БД для сравнения
        $currentUser = $this->userModel->getByIdProfile($userId);

        $newName = trim($_POST['name'] ?? '');
        $newEmail = trim($_POST['email'] ?? '');
        $newPassword = $_POST['password'] ?? '';

        // ---- Проверка на изменения ----
        $hasChanges = false;
        if ($newName !== $currentUser->getName()) {
            $hasChanges = true;
        }
        if ($newEmail !== $currentUser->getEmail()) {
            $hasChanges = true;
        }
        if (!empty($newPassword)) {
            $hasChanges = true;
        }

        if (!$hasChanges) {
            $_SESSION['flash_message'] = 'Никаких изменений не обнаружено.';
            header('Location: /profile');
            exit();
        }

        // ---- Валидация
        $errors = $this->validateProfileUpdate($_POST, $userId);

        if (empty($errors)) {
            // Если пароль не пуст – хешируем, иначе оставляем NULL (не меняем)
            $passwordHash = empty($newPassword) ? null : password_hash($newPassword, PASSWORD_DEFAULT);

            // Обновляем профиль (передаём только те поля, которые нужно изменить)
            $this->userModel->updateProfile($userId, $newName, $newEmail, $passwordHash);

            $_SESSION['flash_message'] = 'Профиль успешно обновлён!';
            header('Location: /profile');
            exit();
        }

        // Если есть ошибки – сохраняем их и введённые данные в сессию,
        // Отображение формы
        $_SESSION['form_errors'] = $errors;
        $_SESSION['old_input'] = ['name' => $newName, 'email' => $newEmail];

        // Возвращаемся на страницу редактирования
        header('Location: /profile/edit');
        exit();
    }

    private function validateProfileUpdate(array $data, int $userId): array
    {
        $errors = [];

        $errorName = $this->validateName($data);
        if (!empty($errorName)) {
            $errors['name'] = $errorName;
        }
        if (isset($data['email'])) {
            $email = $data['email'];
            if (strlen($email) < 3) {
                $errors['email'] = 'Email не может содержать меньше 3 символов';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Неправильный email';
            } else {
                $user = $this->userModel->getByEmail($email);
                if ($user !== null && $user->getId() !== $userId) {
                    $errors['email'] = 'Этот Email уже существует';
                }
            }
        } else {
            $errors['email'] = 'Этот email должен быть заполнен!';
        }
        if (isset($data['password']) && trim($data['password']) !== '' && strlen(trim($data['password'])) < 5) {
            $errors['password'] = 'пароль не должен быть меньше 5 символов';
        }
        return $errors;
    }

    public function logout()
    {
        $this->authService->logout();
        header('location: /login');
        exit;
    }
}

