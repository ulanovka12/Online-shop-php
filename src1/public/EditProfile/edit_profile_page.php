<?php

if (isset($_SESSION['userId'])){
    $userId = $_SESSION['userId'];

    $pdo = new PDO ('pgsql:host = postgres;dbname=mydb', 'king', 'qwerty');
    $stmt = $pdo->query("SELECT * FROM users WHERE id = $userId");
    $user = $stmt->fetch();
} else {
    header('location: /login');
}


?>


<form action="/profile-change" method="GET" class="form-example">
    <div class="container">
        <h1>Редактирования профиля</h1>

        <label for="name"><b>Введите новое имя: </b></label>
        <?php if (isset($errors['name'])): ?>
        <label style="color: #ff0000"><?php echo $errors['name']; ?></label>
        <?php endif; ?>

        <input type="text" placeholder="Enter name" name="name" id="name" value="<?php echo $user['name']; ?>"required>

        <label for="email"><b>Введите новый email:</b></label>
        <?php if (isset($errors['email'])): ?>
        <label style="color: #ff0000"><?php echo $errors['email']; ?></label>
        <?php endif; ?>
        <input type="text" placeholder="Enter Email" name="email" id="email" value="<?php echo $user['email']; ?> required>

        <label for="password"><b>Введите новый пароль:</b></label>
        <?php if (isset($errors['password'])): ?>
        <label style="color: #ff0000"><?php echo $errors['password']; ?></label>
        <?php endif; ?>
        <input type="password" placeholder="Enter Password" name="password" id="password" value="<?php echo $user['password']; ?>required>

<!---->
<!--        <label for="image"><b>image</b></label>-->
<!--        <input type="file" name="image" accept="image/*" value="--><?php //echo $user['image']; ?><!-->-->
<!--        <hr>-->

        <button type="submit" class="registerbtn">Изменить</button>
    </div>
</form>