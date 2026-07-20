<div class="users-order">
    <div class="profile-nav">
        <a href="/profile" class="nav-link">👤 Мой профиль</a>
        <a href="/cart" class="nav-link">🛒 Добавить в корзину</a>
    </div>

    <h3>📋 Мои заказы</h3>

    <?php if (empty($resultOrders)): ?>
        <div class="empty-orders">
            <p>У вас пока нет заказов.</p>
            <a href="/catalog" class="btn-primary">Перейти в каталог</a>
        </div>
    <?php else: ?>
        <div class="orders-grid">
            <?php foreach ($resultOrders as $userOrder): ?>
                <div class="order-card">
                    <div class="order-header">
                        <h2>Заказ № <?php echo htmlspecialchars($userOrder->getId()); ?></h2>
                        <span class="order-status">В обработке</span>
                    </div>

                    <div class="order-info">
                        <p><strong>Получатель:</strong> <?php echo htmlspecialchars($userOrder->getContactName()); ?></p>
                        <p><strong>Телефон:</strong> <?php echo htmlspecialchars($userOrder->getContactPhone()); ?></p>
                        <p><strong>Адрес:</strong> <?php echo htmlspecialchars($userOrder->getAddress()); ?></p>
                        <?php if (!empty($userOrder->getComment())): ?>
                            <p><strong>Комментарий:</strong> <?php echo htmlspecialchars($userOrder->getComment()); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="order-products">
                        <table class="products-table">
                            <thead>
                            <tr>
                                <th>Наименование</th>
                                <th>Кол-во</th>
                                <th>Цена</th>
                                <th>Сумма</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($userOrder->getOrderProducts() as $product): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td><?php echo htmlspecialchars($product['amount']); ?></td>
                                    <td><?php echo number_format($product['price'], 2); ?> ₽</td>
                                    <td><?php echo number_format($product['totalSum'], 2); ?> ₽</td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="order-footer">
                        <p class="order-total">Итого: <span><?php echo number_format($userOrder->getTotal(), 2); ?> ₽</span></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        background: #f8f9fa;
        padding: 20px;
    }

    .user-orders {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Навигация */
    .profile-nav {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e0e0e0;
    }

    .nav-link {
        text-decoration: none;
        color: #555;
        font-weight: 500;
        padding: 8px 16px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .nav-link:hover {
        background: #e9ecef;
        color: #212529;
    }

    h3 {
        font-size: 1.8rem;
        color: #333;
        margin-bottom: 30px;
        font-weight: 500;
    }

    /* Сетка заказов */
    .orders-grid {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    /* Карточка заказа */
    .order-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: box-shadow 0.2s ease;
    }

    .order-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px;
        background: #f8f9fa;
        border-bottom: 1px solid #eee;
    }

    .order-header h2 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .order-status {
        background: #28a745;
        color: #fff;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .order-info {
        padding: 20px 24px;
        background: #fff;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
        border-bottom: 1px solid #f0f0f0;
    }

    .order-info p {
        margin: 0;
        font-size: 0.9rem;
        color: #555;
    }

    .order-info p strong {
        color: #333;
        margin-right: 8px;
    }

    /* Таблица товаров */
    .order-products {
        padding: 0 24px;
        overflow-x: auto;
    }

    .products-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .products-table th {
        text-align: left;
        padding: 12px 8px;
        background: #f8f9fa;
        color: #555;
        font-weight: 600;
        border-bottom: 2px solid #e9ecef;
    }

    .products-table td {
        padding: 12px 8px;
        border-bottom: 1px solid #f0f0f0;
        color: #444;
    }

    .products-table tr:last-child td {
        border-bottom: none;
    }

    /* Подвал заказа */
    .order-footer {
        padding: 16px 24px 20px;
        background: #fff;
        border-top: 1px solid #f0f0f0;
        text-align: right;
    }

    .order-total {
        font-size: 1.1rem;
        font-weight: 500;
        color: #333;
    }

    .order-total span {
        font-size: 1.3rem;
        font-weight: 700;
        color: #28a745;
        margin-left: 12px;
    }

    /* Пустое состояние */
    .empty-orders {
        text-align: center;
        padding: 60px 20px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .empty-orders p {
        font-size: 1.2rem;
        color: #666;
        margin-bottom: 20px;
    }

    .btn-primary {
        display: inline-block;
        background: #007bff;
        color: #fff;
        text-decoration: none;
        padding: 12px 28px;
        border-radius: 40px;
        font-weight: 500;
        transition: background 0.2s ease;
    }

    .btn-primary:hover {
        background: #0056b3;
        color: #fff;
    }

    /* Адаптивность */
    @media (max-width: 768px) {
        .order-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .order-info {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .products-table th,
        .products-table td {
            padding: 8px 4px;
            font-size: 0.8rem;
        }

        .order-footer {
            text-align: left;
        }
    }
</style>