<div class="catalog">


    <a href="/profile">Мой профиль</a>
    <a href="/users-orders">Мои Заказы</a>

  <h3>Catalog</h3>
  <div class="card-deck">
      <?php foreach ($products as $product): ?>
      <div class="card text-center">
          <a href="#">
              <div class="card-header">
              </div>
              <img class="card-img-top" src="<?php echo $product->getImageUrl(); ?>" alt="Card image">
              <div class="card-body">
                  <p class="card-text text-muted"><?php echo $product->getName();?></p>
                  <a href="#"><h5 class="card-title"><?php  echo $product->getDescription() ?></h5></a>
                  <div class="card-footer">
                      <?php echo $product->getPrice();?>
                  </div>
              </div>
              <form action="/add-product" method="POST">
                  <div class="container">

                      <input type="hidden" placeholder="Enter product_id" name="product_id" value="<?php echo $product->getId(); ?>" id="product_id" required>

                      <label for="amount"><b>Количество</b></label>
                      <?php if (isset($errors['amount'])): ?>
                          <label style="color: #ff0000"><?php echo $errors['amount']; ?></label>
                      <?php endif; ?>
                      <input  placeholder="Введите количество" name="amount" id="amount" required>

                      <button type="submit" class="registerbtn">Добавить продукт</button>

                  </div>
              </form>
          </a>
      </div>
      <?php endforeach;?>
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