<div class="user-orders">


    <a href="/profile">Мой профиль</a>
    <a href="/cart">Добавить в корзину</a>

    <h3>Catalog</h3>
    <div class="card-deck">
        <?php foreach ($userOrders as $userOrder): ?>
            <div class="order-card">
                <h2> Заказ № <?php echo $userOrder['id']?> </h2>
                <p><?php echo $userOrder['contact_name']?> </p>
                <p><?php echo $userOrder['contact_phone']?> </p>
                <p><?php echo $userOrder['comment']?> </p>
                <p><?php echo $userOrder['address']?> </p>
                <table>
                    <thead>
                    <tr>
                        <th>Наименование</th>
                        <th>Количество</th>
                        <th>Стоимость</th>
                        <th>Сумма</th>
                    </tr>
                    </thead>
                    <tbody>
                         <?php foreach ($newUserOrder['OrderProducts'] as $newOrderProduct): ?>
                    <tr>
                        <td> <?php echo $newOrderProduct['name']?></td>
                        <td> <?php echo $newOrderProduct['amount']?></td>
                        <td> <?php echo $newOrderProduct['price']?></td>
                        <td> <?php echo $newOrderProduct['totalSum']?></td>
                    </tr>
                    <?endforeach;?>
                    </tbody>
                </table>
                <p> Сумма заказа <?php echo $userOrder['total']; ?> </p>
            </div>
        <?php endforeach;?>
                    <form action="/add-product" method="POST">
                        <div class="container">
                            <h1>AddProduct</h1>

                            <input type="hidden" placeholder="Enter product_id" name="product_id" value="<?php echo $product->getId(); ?>" id="product_id" required>

                            <label for="amount"><b>Amount</b></label>
                            <?php if (isset($errors['amount'])): ?>
                                <label style="color: #ff0000"><?php echo $errors['amount']; ?></label>
                            <?php endif; ?>
                            <input  placeholder="Enter amount" name="amount" id="amount" required>

                            <button type="submit" class="registerbtn">AddProduct</button>

                        </div>
                    </form>
                </a>
            </div>
    </div>
</div>



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

    .registerbtn {
        background-color: #04AA6D;
        color: white;
        padding: 16px 20px;
        margin: 8px 0;
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


<style>
    body {
        font-style: sans-serif;
    }

    a {
        text-decoration: none;
    }

    a:hover {
        text-decoration: none;
    }

    h3 {
        line-height: 3em;
    }

    .card {
        max-width: 16rem;
        width: 100%;
        height: 100%; /* Растягиваем карточки */
        display: flex;
        flex-direction: column;
    }

    .card-img-top {
        width: 100%;
        height: 200px; /* Фиксированная высота */
        object-fit: cover; /* Обрезает изображение, сохраняя пропорции */
        object-position: center; /* Центрирует изображение */
    }

    .card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    /* Чтобы карточки в ряду были одинаковой высоты */
    .card-deck {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .card-deck .card {
        flex: 1 0 auto;
        min-width: 250px;
    }
</style>