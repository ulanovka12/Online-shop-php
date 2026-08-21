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
                                <form action="/update-cart" method="POST" class="d-flex align-items-center gap-2">
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
        background: #f5f7fb;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    .container.mt-5 {
        background: #ffffff;
        border-radius: 32px;
        padding: 32px 32px 24px;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.04), 0 2px 8px rgba(0, 0, 0, 0.02);
        margin-top: 2rem !important;
    }

    .d-flex.align-items-center.justify-content-between.mb-4 h3 {
        font-weight: 600;
        font-size: 1.6rem;
        color: #1e293b;
        letter-spacing: -0.01em;
    }
    .d-flex.align-items-center.justify-content-between.mb-4 h3 i {
        color: #3b82f6;
    }
    #cart-count {
        background: #3b82f6 !important;
        color: #fff;
        font-weight: 600;
        font-size: 0.95rem;
        padding: 0.35rem 0.9rem;
        border-radius: 40px;
        box-shadow: none;
    }
    .alert-info {
        background: #f8fafc;
        border: 2px dashed #d1d9e6;
        border-radius: 32px !important;
        padding: 3rem 1.5rem;
    }
    .alert-info i {
        color: #94a3b8 !important;
    }
    .alert-info h4 {
        color: #334155;
        font-weight: 500;
    }
    .alert-info .btn-outline-primary {
        border-color: #3b82f6;
        color: #3b82f6;
        border-radius: 40px;
        padding: 0.5rem 2rem;
    }
    .alert-info .btn-outline-primary:hover {
        background: #3b82f6;
        color: #fff;
    }
    .cart-table {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: none;
    }
    .cart-table thead th {
        background: #f1f5f9;
        color: #475569;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 14px 16px;
        border-bottom: none;
    }
    .cart-table tbody td {
        padding: 16px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #eef2f6;
        background-color: #ffffff;
    }
    .cart-table tbody tr:last-child td {
        border-bottom: none;
    }
    .cart-table tbody tr:hover td {
        background-color: #fafcff;
        transition: background 0.15s ease;
    }
    .product-icon {
        width: 44px !important;
        height: 44px !important;
        background: #eff6ff !important;
        border-radius: 12px !important;
    }
    .product-icon i {
        color: #3b82f6 !important;
        font-size: 1.1rem;
    }
    .d-block strong {
        font-weight: 600;
        color: #0f172a;
    }
    .text-muted small {
        font-size: 0.8rem;
        color: #94a3b8;
    }
    .quantity-input {
        width: 80px !important;
        border-radius: 40px !important;
        border: 1.5px solid #e2e8f0;
        padding: 0.3rem 0.6rem;
        font-weight: 500;
        font-size: 0.95rem;
        background: #fff;
        text-align: center;
        transition: border 0.15s;
    }
    .quantity-input:focus {
        border-color: #3b82f6;
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }
    .btn-outline-primary.btn-sm.rounded-circle {
        width: 34px !important;
        height: 34px !important;
        border-radius: 50% !important;
        border: 1.5px solid #dbeafe;
        color: #3b82f6;
        background: #fff;
        padding: 0 !important;
        font-size: 0.8rem;
        transition: all 0.15s ease;
    }
    .btn-outline-primary.btn-sm.rounded-circle:hover {
        background: #3b82f6;
        color: #fff;
        border-color: #3b82f6;
        transform: scale(1.05);
    }

    .btn-outline-danger.btn-sm.rounded-circle {
        width: 34px !important;
        height: 34px !important;
        border-radius: 50% !important;
        border: 1.5px solid #fee2e2;
        color: #ef4444;
        background: #fff;
        padding: 0 !important;
        font-size: 0.8rem;
        transition: all 0.15s ease;
    }
    .btn-outline-danger.btn-sm.rounded-circle:hover {
        background: #ef4444;
        color: #fff;
        border-color: #ef4444;
        transform: scale(1.05);
    }

    .fw-semibold {
        font-weight: 600;
        color: #0f172a;
    }
    .text-success {
        color: #16a34a !important;
        font-weight: 600;
    }
    .table-active td {
        background: #f8fafc !important;
        padding: 18px 16px !important;
        border-top: 2px solid #e2e8f0;
        font-weight: 600;
    }
    .table-active td:last-child {
        font-size: 1.5rem;
        color: #16a34a;
        font-weight: 700;
    }
    .btn-outline-danger.rounded-pill {
        border: 1.5px solid #fca5a5;
        color: #dc2626;
        background: #fff;
        border-radius: 40px !important;
        padding: 0.5rem 1.8rem;
        transition: all 0.15s ease;
    }
    .btn-outline-danger.rounded-pill:hover {
        background: #dc2626;
        color: #fff;
        border-color: #dc2626;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.15);
    }
    .btn-success.rounded-pill {
        background: #16a34a !important;
        border: none;
        border-radius: 40px !important;
        padding: 0.6rem 2.5rem;
        font-weight: 600;
        transition: all 0.15s ease;
    }
    .btn-success.rounded-pill:hover {
        background: #15803d !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(22, 163, 74, 0.2);
    }
    @media (max-width: 768px) {
        .container.mt-5 {
            padding: 20px 16px;
            border-radius: 24px;
        }
        .cart-table thead {
            display: none;
        }
        .cart-table tbody tr {
            display: block;
            margin-bottom: 1.2rem;
            border-radius: 16px;
            background: #fff;
            padding: 0.8rem 0.8rem 0.4rem;
            border: 1px solid #f1f5f9;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }
        .cart-table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px dashed #f1f5f9;
            flex-wrap: wrap;
        }
        .cart-table tbody td:last-child {
            border-bottom: none;
        }
        .cart-table tbody td:before {
            content: attr(data-label);
            font-weight: 600;
            color: #64748b;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            margin-right: 12px;
            min-width: 70px;
        }
        .cart-table tbody td:first-child:before { content: "Товар"; }
        .cart-table tbody td:nth-child(2):before { content: "Цена"; }
        .cart-table tbody td:nth-child(3):before { content: "Кол-во"; }
        .cart-table tbody td:nth-child(4):before { content: "Сумма"; }
        .cart-table tbody td:nth-child(5):before { content: "Действия"; }
        .cart-table tbody td .d-flex {
            flex: 1;
            justify-content: flex-end;
        }
        .product-icon {
            width: 36px !important;
            height: 36px !important;
        }
        .table-active td {
            display: flex;
            justify-content: space-between;
            padding: 12px 0 !important;
            border-top: 2px solid #e2e8f0;
        }
        .table-active td:last-child {
            font-size: 1.3rem;
        }
        .d-flex.flex-wrap.justify-content-end {
            flex-direction: column;
            align-items: stretch;
            gap: 0.5rem;
        }
        .d-flex.flex-wrap.justify-content-end .btn {
            width: 100%;
            text-align: center;
            padding: 0.6rem 1rem;
        }
    }

    .cart-table tbody tr {
        animation: fadeUp 0.35s ease both;
    }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .cart-table tbody tr:nth-child(1) { animation-delay: 0.02s; }
    .cart-table tbody tr:nth-child(2) { animation-delay: 0.04s; }
    .cart-table tbody tr:nth-child(3) { animation-delay: 0.06s; }
    .cart-table tbody tr:nth-child(4) { animation-delay: 0.08s; }
    .cart-table tbody tr:nth-child(5) { animation-delay: 0.10s; }
</style>