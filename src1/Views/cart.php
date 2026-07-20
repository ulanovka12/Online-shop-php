<!-- Nav -->
<nav class="navbar navbar-inverse bg-inverse fixed-top bg-faded">
    <div class="container">
        <div class="row w-100">
            <div class="col">
                <a href="/catalog" class="btn btn-primary">В каталог</a>
                <a href="/users-orders" class="btn btn-secondary">Мои заказы</a>
            </div>
        </div>
    </div>
</nav>
<!-- Отображение текущей корзины -->
<div class="container mt-5">
    <h3 Текущая корзина</h3>
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
            <a href="/clear-cart" class="btn btn-danger">Clear Cart</a>
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