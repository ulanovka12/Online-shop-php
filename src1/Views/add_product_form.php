<form action="/add-product" method="POST">
    <div class="container">
        <h1>Добавление продукта</h1>

        <label for="name"><b>Product-id</b></label>
        <?php if (isset($errors['product_id'])): ?>
            <label style="color: #ff0000"><?php echo $errors['product_id']; ?></label>
        <?php endif; ?>

        <input  placeholder="Enter product_id" name="product_id" id="product_id" required>

        <label for="amount"><b>Amount</b></label>
        <?php if (isset($errors['amount'])): ?>
            <label style="color: #ff0000"><?php echo $errors['amount']; ?></label>
        <?php endif; ?>
        <input placeholder="Enter amount" name="amount" id="amount" required>

        <button type="submit" class="registerbtn">Добавить продукт</button>
    </div>

    <div class="container signin">
    </div>
</form>

<style>
    * {box-sizing: border-box}

    .container {
        padding: 25px;
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

    .registerbtn {
        background-color: #04AA6D;
        color: white;
        padding: 20px 20px;
        margin: 50px 0;
        border: none;
        cursor: pointer;
        width: 100%;
        opacity: 0.9;
    }

    .registerbtn:hover {
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
