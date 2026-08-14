<?php
// проверка работы коммита
namespace Controller;

use Model\User;
use Request\LoginRequest;
use Request\ProfileRequest;
use Request\RegistrateRequest;

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

    public function registrate(RegistrateRequest $request)
    {

        $errors = $request->validate();

        if (empty($errors)) {
            $name = $request->getName();
            $email = $request->getEmail();

            $password = password_hash($request->getPassword(), PASSWORD_DEFAULT);

            // Создаём пользователя
            $userId = $this->userModel->getByUsername($name, $email, $password);
            if ($userId) {
                // Автоматический вход после регистрации (опционально)
                $this->authService->auth($request->getEmail(), $request->getPassword());
                header('Location: /login');
                exit();
            } else {
                $errors['auth'] = 'Неверный email или пароль';
            }
        }
        require_once '../Views/registration_form.php';
    }


    public function getLogin()
    {

        $this->authService->check();
        require_once '../Views/login_form.php';
    }

    public function login(LoginRequest $request)
    {

        $errors = $request->validateLogin();

        if (empty($errors)) {

            $result = $this->authService->auth($request->getEmail(), $request->getPassword());

            if ($result) {
                header('Location: /catalog');
                exit();
            } else {
                $errors['auth'] = 'Неверный email или пароль';
            }
        }
        require_once '../Views/login_form.php';
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

    public function updateProfile(ProfileRequest $request)
    {
        if (!$this->authService->check()) {
            header('Location: /login');
            exit();
        }

        $user = $this->authService->getCurrentUser();

        $userId = $user->getId(); //(!)

        // Загружаем текущие данные из БД для сравнения
        $currentUser = $this->userModel->getByIdProfile($userId);

        $newName = $request->getName();
        $newEmail = $request->getEmail();
        $newPassword = $request->getPassword();

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
        $errors = $request->validateProfileUpdate();

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
        // Отображение формы (????????????)
        $_SESSION['form_errors'] = $errors;
        $_SESSION['old_input'] = ['name' => $newName, 'email' => $newEmail];

        // Возвращаемся на страницу редактирования
        header('Location: /profile/edit');
        exit();
    }


    public function logout()
    {
        $this->authService->logout();
        header('location: /login');
        exit;
    }
}

