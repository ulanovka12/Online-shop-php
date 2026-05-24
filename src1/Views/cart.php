<!-- Nav -->
<nav class="navbar navbar-inverse bg-inverse fixed-top bg-faded">
    <div class="container">
        <div class="row w-100">
            <div class="col">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cart">
                    Cart (<span class="total-count"></span>)
                </button>
                <button class="clear-cart btn btn-danger">Clear Cart</button>
            </div>
        </div>
    </div>
</nav>

<!-- Основной каталог товаров -->
<div class="container mt-4">
    <h2 class="mb-4">Каталог товаров</h2>
    <div class="row">
        <?php if (empty($all_products)): ?>
            <div class="col-12">
                <div class="alert alert-info">Нет доступных товаров</div>
            </div>
        <?php else: ?>
            <?php foreach ($all_products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php if (!empty($product['image_url'])): ?>
                            <img class="card-img-top" src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <?php else: ?>
                            <img class="card-img-top" src="/placeholder-image.jpg" alt="No image">
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                            <p class="card-text"><strong>Price: $<?php echo number_format($product['price'], 2); ?></strong></p>

                            <!-- Форма добавления в корзину -->
                            <form action="/add-to-cart" method="POST" class="mt-auto">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                                <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">

                                <div class="form-group">
                                    <label for="amount_<?php echo $product['id']; ?>">Quantity:</label>
                                    <input type="number"
                                           class="form-control form-control-sm"
                                           id="amount_<?php echo $product['id']; ?>"
                                           name="amount"
                                           value="1"
                                           min="1"
                                           max="99"
                                           required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Отображение текущей корзины -->
<div class="container mt-5">
    <h3>Текущая корзина</h3>
    <?php if (empty($cart_items)): ?>
        <div class="alert alert-info">Корзина пуста</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Товар</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Сумма</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td>
                            <form action="/update-cart" method="POST" class="form-inline">
                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                <input type="number"
                                       name="amount"
                                       value="<?php echo $item['amount']; ?>"
                                       min="1"
                                       class="form-control form-control-sm"
                                       style="width: 70px;">
                                <button type="submit" class="btn btn-sm btn-secondary ml-1">Update</button>
                            </form>
                        </td>
                        <td>$<?php echo number_format($item['price'] * $item['amount'], 2); ?></td>
                        <td>
                            <a href="/remove-from-cart?product_id=<?php echo $item['id']; ?>"
                               class="btn btn-sm btn-danger">Remove</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr class="table-active">
                    <td colspan="3" class="text-right"><strong>Total:</strong></td>
                    <td colspan="2"><strong>$<?php echo number_format($total, 2); ?></strong></td>
                </tr>
                </tfoot>
            </table>
        </div>
        <div class="text-right">
            <a href="/checkout" class="btn btn-success">Checkout</a>
            <a href="/clear-cart" class="btn btn-danger">Clear Cart</a>
        </div>
    <?php endif; ?>
</div>

<style>
    body {
        padding-top: 80px;
    }

    /* Стили для одинаковых карточек */
    .card {
        height: 100%;
        transition: transform 0.2s;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .card-img-top {
        width: 100%;
        height: 200px;
        object-fit: cover;
        object-position: center;
    }

    .card-body {
        display: flex;
        flex-direction: column;
    }

    .btn-block {
        width: 100%;
    }

    /* Стили для таблицы корзины */
    .table td {
        vertical-align: middle;
    }
</style>

<!-- JavaScript для динамической корзины (опционально) -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Функция обновления счетчика корзины
        function updateCartCount() {
            fetch('/get-cart-count')
                .then(response => response.json())
                .then(data => {
                    document.querySelectorAll('.total-count').forEach(el => {
                        el.textContent = data.count;
                    });
                });
        }

        // Обработчики для форм добавления в корзину
        document.querySelectorAll('form[action="/add-to-cart"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                fetch('/add-to-cart', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateCartCount();
                            // Показать уведомление
                            alert('Товар добавлен в корзину!');
                            // Перезагрузить страницу или обновить корзину
                            location.reload();
                        }
                    });
            });
        });
    });
</script>