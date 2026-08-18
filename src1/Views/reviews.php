<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 style="color: #fff; text-align: center;">Отзывы о «<?= $productName ?>»</h1>
        </div>
    </div>
</div>

<!-- Сообщения -->
<div class="alert-container">
    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
</div>

<!-- Карусель Мощных отзывов -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div id="testimonial-slider" class="owl-carousel">
                <?php if (empty($reviews)): ?>
                    <div class="testimonial">
                        <div class="testimonial-content">
                            <div class="testimonial-icon">
                                <i class="fa fa-quote-left"></i>
                            </div>
                            <p class="description">Пока нет отзывов. Будьте первым!</p>
                        </div>
                        <h3 class="title">—</h3>
                        <span class="post">&nbsp;</span>
                    </div>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="testimonial">
                            <div class="testimonial-content">
                                <div class="testimonial-icon">
                                    <i class="fa fa-quote-left"></i>
                                </div>
                                <p class="description">
                                    <?= nl2br(htmlspecialchars($review->getDescription())) ?>
                                </p>
                            </div>
                            <h3 class="title"><?= htmlspecialchars($review->getName()) ?></h3>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Форма добавления отзыва (только для тех кто авторизовался) -->
<?php if ($currentUser): ?>
    <div class="container review-form">
        <form method="POST" action="/reviews?product_id=<?= $productId ?>">
            <input type="hidden" name="product_id" value="<?= $productId ?>">
            <input type="hidden" name="add_review" value="1">
            <div class="form-group">
                <label for="name">Ваше имя</label>
                <input type="text" id="name" class="form-control" value="<?= htmlspecialchars($currentUser->getName()) ?>" readonly>
            </div>
            <div class="form-group">
                <label for="description">Текст отзыва</label>
                <textarea id="description" name="description" rows="4" required placeholder="Напишите, что вы думаете о товаре..."></textarea>
            </div>
            <button type="submit">Отправить отзыв</button>
        </form>
    </div>
<?php else: ?>
    <div class="container" style="text-align: center; color: #fff; margin-top: 30px;">
        <p><a href="/login" style="color: #0CCA4A;">Войдите</a>, чтобы оставить отзыв.</p>
    </div>
<?php endif; ?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Отзывы о товаре «<?= $productName ?>»</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css">
    <style>
        body {
            background: #000 !important;
            margin-top: 100px !important;
        }
        .testimonial {
            margin: 0 20px 40px;
        }
        .testimonial .testimonial-content {
            padding: 35px 25px 35px 50px;
            margin-bottom: 35px;
            background: #fff;
            position: relative;
        }
        .testimonial .testimonial-content:before {
            content: "";
            position: absolute;
            bottom: -30px;
            left: 0;
            border-top: 15px solid #718076;
            border-left: 15px solid transparent;
            border-bottom: 15px solid transparent;
        }
        .testimonial .testimonial-content:after {
            content: "";
            position: absolute;
            bottom: -30px;
            right: 0;
            border-top: 15px solid #718076;
            border-right: 15px solid transparent;
            border-bottom: 15px solid transparent;
        }
        .testimonial-content .testimonial-icon {
            width: 50px;
            height: 45px;
            background: #0CCA4A;
            text-align: center;
            font-size: 22px;
            color: #fff;
            line-height: 42px;
            position: absolute;
            top: 37px;
            left: -19px;
        }
        .testimonial-content .testimonial-icon:before {
            content: "";
            border-bottom: 16px solid #05A739;
            border-left: 18px solid transparent;
            position: absolute;
            top: -16px;
            left: 1px;
        }
        .testimonial .description {
            font-size: 15px;
            font-style: italic;
            color: #8a8a8a;
            line-height: 23px;
            margin: 0;
        }
        .testimonial .title {
            display: block;
            font-size: 18px;
            font-weight: 700;
            color: #525252;
            text-transform: capitalize;
            letter-spacing: 1px;
            margin: 0 0 5px 0;
        }
        .testimonial .post {
            display: block;
            font-size: 14px;
            color: #0CCA4A;
        }
        .owl-theme .owl-controls {
            margin-top: 20px;
        }
        .owl-theme .owl-controls .owl-page span {
            background: #ccc;
            opacity: 1;
            transition: all 0.4s ease 0s;
        }
        .owl-theme .owl-controls .owl-page.active span,
        .owl-theme .owl-controls.clickable .owl-page:hover span {
            background: #0CCA4A;
        }
        .alert-container {
            max-width: 1170px;
            margin: 0 auto 30px;
            padding: 0 15px;
        }
        .review-form {
            max-width: 1170px;
            margin: 40px auto 0;
            padding: 20px 15px;
            background: #f9f9f9;
            border-radius: 8px;
        }
        .review-form label {
            font-weight: bold;
            color: #333;
        }
        .review-form textarea {
            width: 100%;
            min-height: 100px;
            border-radius: 4px;
            border: 1px solid #ccc;
            padding: 10px;
        }
        .review-form button {
            background: #0CCA4A;
            border: none;
            color: #fff;
            padding: 10px 30px;
            border-radius: 4px;
            font-size: 16px;
        }
        .review-form button:hover {
            background: #09a83a;
        }
        .review-form .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<!-- Подключение скриптов -->
<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js"></script>
<script>
    $(document).ready(function(){
        $("#testimonial-slider").owlCarousel({
            items:3,
            itemsDesktop:[1000,3],
            itemsDesktopSmall:[980,2],
            itemsTablet:[768,2],
            itemsMobile:[650,1],
            pagination:true,
            navigation:false,
            slideSpeed:1000,
            autoPlay:true
        });
    });
</script>

</body>
</html>