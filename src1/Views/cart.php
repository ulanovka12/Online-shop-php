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
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="mb-0"><i class="fas fa-shopping-cart text-primary me-2"></i>Текущая корзина</h3>
        <span class="badge bg-primary rounded-pill fs-6" id="cart-count">
            <?php echo !empty($products) ? count(array_filter($products, fn($p) => $p->getAmount() > 0)) : 0; ?>
        </span>
    </div>

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

    <?php if (empty($products) || array_reduce($products, fn($carry, $p) => $carry + ($p->getAmount() > 0 ? 1 : 0), 0) === 0): ?>
        <div class="alert alert-info text-center py-5 shadow-sm rounded-4">
            <i class="fas fa-cart-plus fa-3x d-block mb-3 text-muted"></i>
            <h4 class="fw-light">Корзина пуста</h4>
            <p class="text-muted">Добавьте товары из каталога, чтобы оформить заказ.</p>
            <a href="/catalog" class="btn btn-outline-primary rounded-pill px-4">Перейти в каталог</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle shadow-sm rounded-4 overflow-hidden cart-table">
                <thead class="table-light">
                <tr>
                    <th style="width: 40%;">Товар</th>
                    <th style="width: 15%;">Цена</th>
                    <th style="width: 20%;">Количество</th>
                    <th style="width: 15%;">Сумма</th>
                    <th style="width: 10%;">Действия</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($products as $item): ?>
                    <?php if ($item->getAmount() > 0): ?>
                        <tr data-product-id="<?php echo $item->getId(); ?>">
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="product-icon bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                        <i class="fas fa-box text-secondary"></i>
                                    </div>
                                    <div>
                                        <strong class="d-block"><?php echo htmlspecialchars($item->getName()); ?></strong>
                                        <small class="text-muted">Артикул: #<?php echo str_pad($item->getId(), 6, '0', STR_PAD_LEFT); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-semibold">Р<?php echo number_format($item->getPrice(), 2); ?></td>
                            <td>
                                <form action="/add-product" method="POST" class="d-flex align-items-center gap-2">
                                    <input type="hidden" name="product_id" value="<?php echo $item->getId(); ?>">
                                    <input type="number"
                                           name="amount"
                                           value="<?php echo $item->getAmount(); ?>"
                                           min="1"
                                           class="form-control form-control-sm quantity-input"
                                           style="width: 80px; text-align: center;">
                                    <button type="submit" class="btn btn-outline-primary btn-sm rounded-circle" style="width: 32px; height: 32px; padding: 0;">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="fw-bold text-success">Р<?php echo number_format($item->getPrice() * $item->getAmount(), 2); ?></td>
                            <td>
                                <a href="/remove-from-cart?product_id=<?php echo $item->getId(); ?>"
                                   class="btn btn-outline-danger btn-sm rounded-circle"
                                   style="width: 32px; height: 32px; padding: 0;"
                                   title="Удалить товар">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr class="table-active fw-bold">
                    <td colspan="3" class="text-end fs-5">Итого:</td>
                    <td colspan="2" class="fs-4 text-success">Р<?php echo number_format($cart_total, 2); ?></td>
                </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex flex-wrap justify-content-end align-items-center gap-3 mt-4">
            <a href="/clear-cart" class="btn btn-outline-danger rounded-pill px-4">
                <i class="fas fa-trash-alt me-1"></i> Очистить корзину
            </a>
            <a href="/create-order" class="btn btn-success rounded-pill px-5 py-2 shadow-sm">
                <i class="fas fa-credit-card me-2"></i> Оформить заказ
            </a>
        </div>
    <?php endif; ?>
</div>

<style>
    body {
        padding-top: 80px;
        background-color: #f4f6f9;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }

    .navbar-brand {
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .cart-table {
        background-color: #fff;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
        border-collapse: separate;
        border-spacing: 0;
    }

    .cart-table thead th {
        border-bottom: 2px solid #e9ecef;
        font-weight: 600;
        color: #495057;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        padding: 16px 12px;
    }

    .cart-table tbody tr {
        transition: background-color 0.2s ease;
    }

    .cart-table tbody tr:hover {
        background-color: #f8faff;
    }

    .cart-table tbody td {
        vertical-align: middle;
        padding: 16px 12px;
        border-bottom: 1px solid #f1f3f5;
    }

    .cart-table tbody tr:last-child td {
        border-bottom: none;
    }

    .product-icon {
        transition: transform 0.2s ease;
    }
    .product-icon:hover {
        transform: scale(1.05);
    }

    .quantity-input {
        -moz-appearance: textfield;
        appearance: textfield;
        font-weight: 600;
        font-size: 0.95rem;
        border-radius: 30px;
        border: 1px solid #ced4da;
        padding: 0.375rem 0.75rem;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .quantity-input::-webkit-outer-spin-button,
    .quantity-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .quantity-input:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .btn-outline-primary.btn-sm.rounded-circle {
        transition: all 0.2s ease;
    }
    .btn-outline-primary.btn-sm.rounded-circle:hover {
        background-color: #0d6efd;
        color: #fff;
        border-color: #0d6efd;
        transform: rotate(45deg);
    }

    .btn-outline-danger.btn-sm.rounded-circle {
        transition: all 0.2s ease;
    }
    .btn-outline-danger.btn-sm.rounded-circle:hover {
        background-color: #dc3545;
        color: #fff;
        border-color: #dc3545;
        transform: scale(1.1);
    }

    .table-active {
        background-color: #f8f9fa;
    }
    .table-active td {
        padding: 18px 12px;
    }

    .btn-success {
        background: linear-gradient(145deg, #2b8c5e, #1e6b48);
        border: none;
        transition: transform 0.15s ease, box-shadow 0.2s ease;
    }
    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(40, 167, 69, 0.25);
        background: linear-gradient(145deg, #2b8c5e, #1e6b48);
    }

    .btn-outline-danger {
        transition: all 0.2s ease;
    }
    .btn-outline-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(220, 53, 69, 0.15);
    }

    .badge.bg-primary {
        background: linear-gradient(135deg, #4e73df, #224abe) !important;
        font-size: 0.9rem;
        padding: 0.4rem 0.8rem;
        border-radius: 30px;
    }

    /* адаптация для мобильных */
    @media (max-width: 768px) {
        .cart-table thead {
            display: none;
        }
        .cart-table tbody tr {
            display: block;
            margin-bottom: 1.2rem;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            background: #fff;
            padding: 1rem;
            border: 1px solid #eee;
        }
        .cart-table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px dashed #e9ecef;
            flex-wrap: wrap;
        }
        .cart-table tbody td:last-child {
            border-bottom: none;
        }
        .cart-table tbody td:before {
            content: attr(data-label);
            font-weight: 600;
            color: #6c757d;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-right: 10px;
        }
        .cart-table tbody td:first-child:before {
            content: "Товар";
        }
        .cart-table tbody td:nth-child(2):before {
            content: "Цена";
        }
        .cart-table tbody td:nth-child(3):before {
            content: "Количество";
        }
        .cart-table tbody td:nth-child(4):before {
            content: "Сумма";
        }
        .cart-table tbody td:nth-child(5):before {
            content: "Действия";
        }
        .cart-table tbody td .d-flex {
            flex: 1;
            justify-content: flex-end;
        }
        .product-icon {
            width: 40px !important;
            height: 40px !important;
        }
        .table-active td {
            display: flex;
            justify-content: space-between;
        }
        .table-active td:first-child {
            font-weight: 600;
        }
        .table-active td:last-child {
            font-size: 1.2rem;
        }
    }

    /* Анимация появления */
    .cart-table tbody tr {
        animation: fadeInUp 0.4s ease both;
    }
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(12px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .cart-table tbody tr:nth-child(1) { animation-delay: 0.05s; }
    .cart-table tbody tr:nth-child(2) { animation-delay: 0.10s; }
    .cart-table tbody tr:nth-child(3) { animation-delay: 0.15s; }
    .cart-table tbody tr:nth-child(4) { animation-delay: 0.20s; }
    .cart-table tbody tr:nth-child(5) { animation-delay: 0.25s; }
</style>