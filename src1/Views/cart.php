<!-- Nav -->
<nav class="navbar navbar-inverse bg-inverse fixed-top bg-faded">
    <div class="row">
        <div class="col">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cart">
                Cart (<span class="total-count"></span>)
            </button>
            <button class="clear-cart btn btn-danger">Clear Cart</button>
        </div>
    </div>
</nav>

<!-- Main -->
<div class="container">
    <div class="row">
        <?php if (empty($products)): ?>
            <div class="col-12">
                <div class="alert alert-info">Your cart is empty</div>
            </div>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card" style="width: 20rem;">
                        <?php if (!empty($product['image_url'])): ?>
                            <img class="card-img-top" src="<?php echo ($product['image_url']); ?>" alt="<?php echo ($product['name']); ?>">
                        <?php else: ?>
                            <img class="card-img-top" src="/placeholder-image.jpg" alt="No image">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo ($product['name']); ?></h5>
                            <p class="card-text"><?php echo ($product['description']); ?></p>
                            <p class="card-text"><strong>Price: $<?php echo ($product['price']); ?></strong></p>
                            <p class="card-text"><strong>Quantity: <?php echo ($product['amount']); ?></strong></p>
                            <div class="d-flex justify-content-between">
                                <a href="#" data-name="<?php echo ($product['name']); ?>"
                                   data-price="<?php echo ($product['price']); ?>"
                                   class="add-to-cart btn btn-primary">Add more</a>
                                <a href="/remove-from-cart?product_id=<?php echo $product['id']; ?>"
                                   class="btn btn-danger">Remove</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Форма для добавления товаров -->
<?php if (!empty($products)): ?>
    <form action="/add-product" method="POST" class="mt-4">
        <div class="container">
            <h2>Add More Products</h2>

            <div class="form-group">
                <label for="product_id"><b>Product</b></label>
                <select class="form-control" name="product_id" id="product_id" required>
                    <option value="">Select a product</option>
                    <?php foreach ($products as $product): ?>
                        <option value="<?php echo $product['id']; ?>">
                            <?php echo ($product['name']); ?> - $<?php echo ($product['price']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="amount"><b>Amount</b></label>
                <?php if (isset($errors['amount'])): ?>
                    <div class="alert alert-danger"><?php echo ($errors['amount']); ?></div>
                <?php endif; ?>
                <input type="number" class="form-control" placeholder="Enter amount" name="amount" id="amount" min="1" required>
            </div>

            <button type="submit" class="btn btn-success">Add to Cart</button>
        </div>
    </form>
<?php endif; ?>




<style>
    body {
        padding-top: 80px;
    }

    .show-cart li {
        display: flex;
    }
    .card {
        margin-bottom: 20px;
    }
    .card-img-top {
        width: 200px;
        height: 200px;
        align-self: center;
    }
</style>
