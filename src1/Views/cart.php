<!-- Подключаем FontAwesome для иконок -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="/catalog"><i class="fas fa-store me-2"></i>Магазин</a>
        <div class="ms-auto d-flex align-items-center gap-2">
            <button type="button" class="btn btn-outline-light position-relative" data-toggle="modal" data-target="#cart">
                <i class="fas fa-shopping-cart"></i> Корзина
                <span class="badge bg-danger rounded-pill total-count ms-1">0</span>
            </button>
            <button class="clear-cart btn btn-outline-danger">
                <i class="fas fa-trash-alt"></i> Очистить
            </button>
        </div>
    </div>
</nav>

<!-- каталог товаров -->
<div class="container mt-5 pt-2">
    <h2 class="mb-4 fw-light border-bottom pb-2"><i class="fas fa-tags me-2 text-primary"></i>Каталог товаров</h2>
    <div class="row g-4">
        <?php if (empty($products)): ?>
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-box-open fa-3x d-block mb-3"></i>
                    Нет доступных товаров
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden product-card">
                        <?php if (!empty($product->getImageUrl())): ?>
                            <img class="card-img-top" src="<?php echo htmlspecialchars($product->getImageUrl()); ?>" alt="<?php echo htmlspecialchars($product->getName()); ?>">
                        <?php else: ?>
                            <img class="card-img-top" src="/placeholder-image.jpg" alt="No image">
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column p-3">
                            <h5 class="card-title fw-bold"><?php echo htmlspecialchars($product->getName()); ?></h5>
                            <p class="card-text text-muted small"><?php echo htmlspecialchars($product->getDescription()); ?></p>
                            <p class="card-text fs-5 fw-semibold text-primary">$<?php echo number_format($product->getPrice(), 2); ?></p>

                            <?php if ($product->getAmount()): ?>
                                <p class="card-text text-success small">
                                    <i class="fas fa-check-circle me-1"></i> В корзине: <?php echo $product->getAmount(); ?>
                                </p>
                            <?php endif; ?>

                            <form action="/add-to-cart" method="POST" class="mt-auto">
                                <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>">
                                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product->getName()); ?>">
                                <input type="hidden" name="product_price" value="<?php echo $product->getPrice(); ?>">

                                <div class="row g-2 align-items-end">
                                    <div class="col-5">
                                        <label for="amount_<?php echo $product->getId(); ?>" class="form-label small text-muted mb-0">Кол-во</label>
                                        <input type="number"
                                               class="form-control form-control-sm"
                                               id="amount_<?php echo $product->getId(); ?>"
                                               name="amount"
                                               value="1"
                                               min="1"
                                               max="99"
                                               required>
                                    </div>
                                    <div class="col-7">
                                        <button type="submit" class="btn btn-primary w-100 btn-sm rounded-pill">
                                            <i class="fas fa-cart-plus me-1"></i> В корзину
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Отображение корзины -->
<div class="container mt-5">
    <h3 class="fw-light border-bottom pb-2"><i class="fas fa-shopping-bag me-2 text-primary"></i>Текущая корзина</h3>
    <?php
    $cart_total = 0;
    if (!empty($products)) {
        foreach ($products as $item) {
            if ($item->getAmount() !== null) {
                $cart_total += $item->getPrice() * $item->getAmount();
            }
        }
    }
    ?>

    <?php if (empty($products)): ?>
        <div class="alert alert-info text-center py-4">
            <i class="fas fa-cart-plus fa-2x d-block mb-2"></i>
            Корзина пуста
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle shadow-sm rounded-4 overflow-hidden">
                <thead class="table-dark">
                <tr>
                    <th>Товар</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Сумма</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($products as $item): ?>
                    <?php if ($item->getAmount()): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($item->getName()); ?></strong></td>
                            <td>$<?php echo number_format($item->getPrice(), 2) ?></td>
                            <td>
                                <form action="/add-product" method="POST" class="d-flex align-items-center gap-1">
                                    <input type="hidden" name="product_id" value="<?php echo ($item->getId()) ?>">
                                    <input type="number"
                                           name="amount"
                                           value="<?php echo ($item->getAmount()) ?>"
                                           min="1"
                                           class="form-control form-control-sm"
                                           style="width: 70px;">
                                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </form>
                            </td>
                            <td>$<?php echo number_format($item->getPrice() * $item->getAmount(), 2); ?></td>
                            <td>
                                <a href="/remove-from-cart?product_id=<?php echo $item->getId(); ?>"
                                   class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr class="table-active fw-bold">
                    <td colspan="3" class="text-end">Итого:</td>
                    <td colspan="2">$<?php echo number_format($cart_total, 2); ?></td>
                </tr>
                </tfoot>
            </table>
        </div>order_products
        <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="/create-order" class="btn btn-success rounded-pill px-4">
                <i class="fas fa-credit-card me-1"></i> Оформить заказ
            </a>
            <a href="/cart" class="btn btn-outline-danger rounded-pill px-4">
                <i class="fas fa-trash-alt me-1"></i> Очистить
            </a>
        </div>
    <?php endif; ?>
</div>

<style>
    body {
        padding-top: 80px;
        background-color: #f8f9fa;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }

    .navbar-brand {
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .product-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .product-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12) !important;
    }

    .card-img-top {
        height: 200px;
        object-fit: cover;
        background-color: #e9ecef;
    }

    .table {
        background-color: #fff;
        border-radius: 16px;
        overflow: hidden;
    }

    .table thead th {
        border-bottom: none;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    .btn-outline-secondary:hover {
        background-color: #e9ecef;
        border-color: #ced4da;
    }

    .gap-2 {
        gap: 0.75rem;
    }

    .rounded-pill {
        border-radius: 50rem !important;
    }

    .badge.bg-danger {
        font-size: 0.7rem;
        padding: 0.3em 0.6em;
    }

    /* небольшие правки для мобильных */
    @media (max-width: 576px) {
        .navbar .btn {
            font-size: 0.85rem;
            padding: 0.3rem 0.6rem;
        }
        .card-img-top {
            height: 150px;
        }
    }
</style>