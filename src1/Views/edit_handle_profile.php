<?php
// Принимаем переменные из контроллера
$user = $user ?? null;
$errors = $errors ?? [];
$old = $old ?? [];
$flash = $_SESSION['flash_message'] ?? null;
unset($_SESSION['flash_message']);
?>

<body>

<div class="container">
    <h1>✏️ Редактирование профиля</h1>

    <?php if ($flash): ?>
        <div class="flash-message"><?= htmlspecialchars($flash) ?></div>
    <?php endif; ?>

    <form action="/profile-change" method="POST" novalidate>
        <!-- Имя -->
        <div class="form-group">
            <label for="name">Новое имя</label>
            <input type="text"
                   id="name"
                   name="name"
                   placeholder="Введите имя"
                   value="<?= htmlspecialchars($old['name'] ?? $user->getName()) ?>"
                   class="<?= isset($errors['name']) ? 'error' : '' ?>">
            <?php if (isset($errors['name'])): ?>
                <span class="error-text"><?= htmlspecialchars($errors['name']) ?></span>
            <?php endif; ?>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email">Новый email</label>
            <input type="email"
                   id="email"
                   name="email"
                   placeholder="Введите email"
                   value="<?= htmlspecialchars($old['email'] ?? $user->getEmail()) ?>"
                   class="<?= isset($errors['email']) ? 'error' : '' ?>">
            <?php if (isset($errors['email'])): ?>
                <span class="error-text"><?= htmlspecialchars($errors['email']) ?></span>
            <?php endif; ?>
        </div>

        <!-- Пароль -->
        <div class="form-group">
            <label for="password">Новый пароль (оставьте пустым, чтобы не менять)</label>
            <input type="password"
                   id="password"
                   name="password"
                   placeholder="Введите новый пароль"
                   class="<?= isset($errors['password']) ? 'error' : '' ?>">
            <?php if (isset($errors['password'])): ?>
                <span class="error-text"><?= htmlspecialchars($errors['password']) ?></span>
            <?php endif; ?>
            <small style="color: #95a5a6; display: block; margin-top: 4px;">Минимум 6 символов</small>
        </div>

        <button type="submit" class="registerbtn">Сохранить изменения</button>
    </form>

    <div class="note">
        <a href="/profile">← Вернуться в профиль</a>
    </div>
</div>

</body>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование профиля</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7fc;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .container {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 40px 35px;
            max-width: 500px;
            width: 100%;
            transition: 0.3s;
        }
        h1 {
            text-align: center;
            font-weight: 600;
            font-size: 26px;
            color: #2c3e50;
            margin-bottom: 25px;
            letter-spacing: -0.5px;
        }
        .flash-message {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
            text-align: center;
            border-left: 5px solid #4caf50;
        }
        .flash-message.error {
            background: #ffebee;
            color: #c62828;
            border-left-color: #f44336;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: 500;
            color: #34495e;
            margin-bottom: 6px;
            font-size: 14px;
        }
        .error-text {
            color: #e74c3c;
            font-size: 13px;
            margin-top: 4px;
            display: block;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #dcdfe6;
            border-radius: 10px;
            font-size: 15px;
            transition: 0.25s;
            background: #fafbfc;
        }
        input:focus {
            border-color: #3498db;
            outline: none;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.15);
        }
        input.error {
            border-color: #e74c3c;
        }
        button.registerbtn {
            width: 100%;
            padding: 14px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            margin-top: 10px;
        }
        button.registerbtn:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(52, 152, 219, 0.3);
        }
        button.registerbtn:active {
            transform: translateY(0);
        }
        .note {
            text-align: center;
            color: #7f8c8d;
            font-size: 13px;
            margin-top: 20px;
        }
        .note a {
            color: #3498db;
            text-decoration: none;
        }
        .note a:hover {
            text-decoration: underline;
        }
    </style>
</head>
</html>