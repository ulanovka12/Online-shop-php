<div class="catalog">


    <a href="/profile">Мой профиль</a>
    <a href="/cart">Добавить в корзину</a>

  <h3>Catalog</h3>
  <div class="card-deck">
      <?php foreach ($products as $product): ?>
      <div class="card text-center">
          <a href="#">
              <div class="card-header">
              </div>
              <img class="card-img-top" src="<?php echo $product->image_irl; ?>" alt="Card image">
              <div class="card-body">
                  <p class="card-text text-muted"><?php echo $product->name;?></p>
                  <a href="#"><h5 class="card-title"><?php  echo $product->description ?></h5></a>
                  <div class="card-footer">
                      <?php echo $product->price;?>
                  </div>
              </div>
              <form action="/add-product" method="POST">
                  <div class="container">
                      <h1>AddProduct</h1>

                      <input type="hidden" placeholder="Enter product_id" name="product_id" value="<?php echo $product->id; ?>" id="product_id" required>

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
      <?php endforeach;?>
  </div>
</div>



<style>
    * {box-sizing: border-box}

    /* Add padding to containers */
    .container {
        padding: 16px;
    }

    /* Full-width input fields */
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

    /* Overwrite default styles of hr */
    hr {
        border: 1px solid #f1f1f1;
        margin-bottom: 25px;
    }

    /* Set a style for the submit/register button */
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

    /* Add a blue text color to links */
    a {
        color: dodgerblue;
    }

    /* Set a grey background color and center the text of the "sign in" section */
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
    }

    .card:hover {
        box-shadow: 1px 2px 10px lightgray;
        transition: 0.2s;
    }

    .card-header {
        font-size: 13px;
        color: gray;
        background-color: white;
    }

    .text-muted {
        font-size: 11px;
    }

    .card-footer{
        font-weight: bold;
        font-size: 18px;
        background-color: white;
    }
</style>