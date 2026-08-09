<form method="POST" action="/create-order" enctype="multipart/form-data">
    <div class="container">
        <h1>Order</h1>
        <hr>

        <label for="name"><b>Name</b></label>
        <?php if (isset($errors['name'])): ?>
            <label style="color: #ff0000"><?php echo $errors['contact_name']; ?></label>
        <?php endif; ?>

        <input type="text" placeholder="Enter contact_name" name="contact_name" id="contact_name" required>

        <label for="email"><b>Contact phone</b></label>
        <?php if (isset($errors['contact_phone'])): ?>
            <label style="color: #ff0000"><?php echo $errors['contact_phone']; ?></label>
        <?php endif; ?>
        <input type="text" placeholder="Enter contact phone" name="contact_phone" id="contact_phone" required>

        <label for="password"><b>Address</b></label>
        <?php if (isset($errors['address'])): ?>
            <label style="color: #ff0000"><?php echo $errors['address']; ?></label>
        <?php endif; ?>
        <input type="text" placeholder="Enter address" name="address" id="address" required>

        <label for="psw"><b>Comment</b></label>
        <?php if (isset($errors['comment'])): ?>
            <label style="color: #ff0000"><?php echo $errors['comment']; ?></label>
        <?php endif; ?>
        <input type="text" placeholder="Repeat Comment" name="comment" id="comment" required>

        <!--        <label for="image"><b>image</b></label>-->
        <!--        <input type="file" name="image" accept="image/*">-->
        <!--        <hr>-->

        <button type="submit" class="orderbtn">Оформить заказ</button>
    </div>
</form>

<style>
    * {box-sizing: border-box}


    .container {
        padding: 16px;
    }

    input[type=text], input[type=password] {
        width: 100%;
        padding: 15px;
        margin: 5px 0 22px 0;
        display: inline-block;
        border: none;
        background: #f1f1f1;
    }

    input[type=text]:focus, input[type=password]:focus {
        background-color: #ddd;
        outline: none;
    }

    hr {
        border: 1px solid #f1f1f1;
        margin-bottom: 25px;
    }

    .orderbtn {
        background-color: #04AA6D;
        color: white;
        padding: 16px 20px;
        margin: 8px 0;
        border: none;
        cursor: pointer;
        width: 100%;
        opacity: 0.9;
    }

    .orderbtn:hover {
        opacity:1;
    }


    a {
        color: dodgerblue;
    }
    .signin {
        background-color: #f1f1f1;
        text-align: center;
    }
</style>