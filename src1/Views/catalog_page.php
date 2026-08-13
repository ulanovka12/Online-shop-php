<body>

<div class="container">

    <div class="page-header">
        <h3>🛍️ Catalog</h3>
        <span class="subtitle">✨ new arrivals</span>
        <div class="card-footer">
            <a href="/profile">Профиль</a>
        </div>
        <div class="card-footer">
            <a href="/profile-change">Изменить профиль</a>
        </div>
        <div class="card-footer">
            <a href="/users-orders">Мои заказы</a>
        </div>
    </div>

    <div class="card-deck">
        <?php foreach ($products as $product): ?>
            <?php $productId = $product->getId(); ?>
            <div class="card">

                <div class="card-header">🔥 Hit!</div>

                <div class="card-img-wrapper">
                    <img
                            class="card-img-top"
                            src="<?= htmlspecialchars($product->getImageUrl()); ?>"
                            alt="<?= htmlspecialchars($product->getName()); ?>"
                            loading="lazy"
                    >
                </div>


                <div class="card-body">
                    <p class="card-text"><?= htmlspecialchars($product->getName()); ?></p>
                    <h5 class="card-title"><?= htmlspecialchars($product->getDescription()); ?></h5>

                    <div class="controls-wrapper">
                        <!-- add -->
                        <form action="/add-product" method="POST">
                            <input type="hidden" name="product_id" value="<?= $productId; ?>">
                            <input type="hidden" name="amount" value="1">
                            <button type="submit" class="btn-qty add" aria-label="Add one">＋</button>
                        </form>


                        <span class="amount-badge">
                                <?= $product->getAmount(); ?>
                            </span>

                        <form action="/decrease-product" method="POST">
                            <input type="hidden" name="product_id" value="<?= $productId; ?>">
                            <input type="hidden" name="amount" value="1">
                            <button type="submit" class="btn-qty remove" aria-label="Remove one">−</button>
                        </form>
                    </div>

                    <a href="/reviews?product_id=<?= $product->getId(); ?>" class="btn-open">
                        💬 Открыть отзывы
                    </a>
                </div>

                <div class="card-footer">
                    Already have an account? <a href="/logout">Sign in</a>
                </div>

            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalog</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f5f7fb;
            padding: 2rem 1.5rem;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }

        .container {
            max-width: 1280px;
            width: 100%;
            margin: 0 auto;
        }

        .page-header {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem 1.5rem;
            margin-bottom: 2rem;
        }

        .page-header h3 {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: #0f172a;
            position: relative;
        }

        .page-header h3::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 0;
            width: 48px;
            height: 4px;
            border-radius: 4px;
            background: linear-gradient(135deg, #0ea5e9, #06b6d4);
        }

        .page-header .subtitle {
            font-size: 0.9rem;
            color: #64748b;
            background: #eef2f6;
            padding: 0.4rem 1rem;
            border-radius: 100px;
            font-weight: 500;
        }

        .card-deck {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.75rem;
        }

        .card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.03);
            transition: transform 0.25s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border: 1px solid rgba(226, 232, 240, 0.6);
            position: relative;
            height: 100%;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15), 0 4px 18px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            position: absolute;
            top: 14px;
            left: 14px;
            z-index: 2;
            background: linear-gradient(135deg, #f43f5e, #e11d48);
            color: #fff;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 0.3rem 0.9rem;
            border-radius: 100px;
            box-shadow: 0 4px 10px rgba(244, 63, 94, 0.3);
            border: none;
            line-height: 1.4;
        }
        .card-img-top {
            width: 100%;
            height: 220px;
            object-fit: cover;
            object-position: center;
            background: #f1f5f9;
            display: block;
            transition: transform 0.4s ease;
        }

        .card:hover .card-img-top {
            transform: scale(1.02);
        }

        .card-img-wrapper {
            overflow: hidden;
            position: relative;
            background: #f8fafc;
        }

        .card-body {
            padding: 1.25rem 1.25rem 1rem;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .card-text {
            font-size: 0.8rem;
            font-weight: 500;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 0.25rem;
        }

        .card-title {
            font-size: 1.05rem;
            font-weight: 600;
            color: #0f172a;
            line-height: 1.4;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .controls-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            background: #f1f5f9;
            border-radius: 60px;
            padding: 0.35rem 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #e2e8f0;
            transition: background 0.2s;
        }

        .controls-wrapper:hover {
            background: #e9eef4;
        }

        .controls-wrapper form {
            display: inline-flex;
            margin: 0;
        }

        .controls-wrapper .btn-qty {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: none;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
            background: transparent;
            color: #475569;
            padding: 0;
            line-height: 1;
        }

        .controls-wrapper .btn-qty:hover {
            background: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            color: #0f172a;
        }

        .controls-wrapper .btn-qty:active {
            transform: scale(0.92);
        }

        .controls-wrapper .btn-qty.add {
            color: #0ea5e9;
        }

        .controls-wrapper .btn-qty.add:hover {
            background: #e0f2fe;
            color: #0284c7;
        }

        .controls-wrapper .btn-qty.remove {
            color: #f43f5e;
        }

        .controls-wrapper .btn-qty.remove:hover {
            background: #ffe4e8;
            color: #be123c;
        }

        .amount-badge {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0f172a;
            min-width: 28px;
            text-align: center;
            font-variant-numeric: tabular-nums;
            letter-spacing: 0.02em;
            background: #ffffff;
            padding: 0.1rem 0.2rem;
            border-radius: 8px;
            min-height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e2e8f0;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.02);
        }

        .btn-open {
            display: block;
            width: 100%;
            text-align: center;
            padding: 0.6rem 0;
            border-radius: 60px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #0f172a;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
            text-decoration: none !important;
            margin-top: 0.25rem;
        }

        .btn-open:hover {
            background: #e2e8f0;
            border-color: #cbd5e1;
            color: #0f172a;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
        }

        .btn-open:active {
            transform: scale(0.98);
        }

        .card-footer {
            padding: 0.85rem 1.25rem 1rem;
            background: #f8fafc;
            border-top: 1px solid #eef2f6;
            text-align: center;
            font-size: 0.8rem;
            color: #64748b;
            margin-top: auto;
        }

        .card-footer a {
            color: #0ea5e9;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }

        .card-footer a:hover {
            color: #0284c7;
            text-decoration: underline;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 1rem;
            color: #94a3b8;
        }

        .empty-state svg {
            opacity: 0.3;
            margin-bottom: 1rem;
        }

        @media (max-width: 640px) {
            body {
                padding: 1rem;
            }

            .page-header h3 {
                font-size: 1.6rem;
            }

            .card-deck {
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }

            .card-img-top {
                height: 180px;
            }

            .controls-wrapper {
                padding: 0.3rem 0.4rem;
            }

            .controls-wrapper .btn-qty {
                width: 32px;
                height: 32px;
                font-size: 1rem;
            }

            .amount-badge {
                min-width: 24px;
                font-size: 1rem;
                min-height: 26px;
            }

            .card-body {
                padding: 1rem 1rem 0.75rem;
            }

            .card-footer {
                padding: 0.7rem 1rem 0.85rem;
            }
        }

        @media (min-width: 641px) and (max-width: 900px) {
            .card-deck {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .card {
            opacity: 0;
            animation: fadeUp 0.5s ease forwards;
        }

        .card:nth-child(1) {
            animation-delay: 0.02s;
        }
        .card:nth-child(2) {
            animation-delay: 0.06s;
        }
        .card:nth-child(3) {
            animation-delay: 0.10s;
        }
        .card:nth-child(4) {
            animation-delay: 0.14s;
        }
        .card:nth-child(5) {
            animation-delay: 0.18s;
        }
        .card:nth-child(6) {
            animation-delay: 0.22s;
        }
        .card:nth-child(7) {
            animation-delay: 0.26s;
        }
        .card:nth-child(8) {
            animation-delay: 0.30s;
        }

        @keyframes fadeUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
</html>