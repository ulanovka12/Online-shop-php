<form method="POST" action="/create-order" enctype="multipart/form-data">
    <div class="container">
        <h1>Order</h1>
        <hr>

        <label for="name"><b>Name</b></label>
        <?php if (isset($errors['name'])): ?>
            <label style="color: #ff0000"><?php echo $errors['contact_name']; ?></label>
        <?php endif; ?>

        <input type="text" placeholder="Enter contact_name" name="contact_name" id="contact_name" required>

        <label for="email"><b>Contact phone</b></label>
        <?php if (isset($errors['contact_phone'])): ?>
            <label style="color: #ff0000"><?php echo $errors['contact_phone']; ?></label>
        <?php endif; ?>
        <input type="text" placeholder="Enter contact phone" name="contact_phone" id="contact_phone" required>

        <label for="password"><b>Address</b></label>
        <?php if (isset($errors['address'])): ?>
            <label style="color: #ff0000"><?php echo $errors['address']; ?></label>
        <?php endif; ?>
        <input type="text" placeholder="Enter address" name="address" id="address" required>

        <label for="psw"><b>Comment</b></label>
        <?php if (isset($errors['comment'])): ?>
            <label style="color: #ff0000"><?php echo $errors['comment']; ?></label>
        <?php endif; ?>
        <input type="text" placeholder="Repeat Comment" name="comment" id="comment" required>

        <!--        <label for="image"><b>image</b></label>-->
        <!--        <input type="file" name="image" accept="image/*">-->
        <!--        <hr>-->

        <button type="submit" class="orderbtn">Оформить заказ</button>
    </div>
<!---->
<!--    <div class="container signin">-->
<!--    </div>-->
</form>

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
    .orderbtn {
        background-color: #04AA6D;
        color: white;
        padding: 16px 20px;
        margin: 8px 0;
        border: none;
        cursor: pointer;
        width: 100%;
        opacity: 0.9;
    }

    .orderbtn:hover {
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



<!--<form action="/create-order" method="POST">-->
<!--<div id="app"></div>-->
<!--</form>-->
<!---->
<!--<style>-->
<!--    // Fonts-->
<!--    @import 'https://fonts.googleapis.com/css?family=Lato:100,300,400,700,900';-->
<!---->
<!--    // Variables-->
<!--$font: 'Lato', sans-serif;-->
<!---->
<!--    // Colors-->
<!--$onyx: #3E363F;-->
<!--    $cinnabar: #DD403A;-->
<!--    $cosmiclatte: #FFFCE8;-->
<!---->
<!--    // Styles-->
<!--       body {-->
<!--           background-image: url(https://unsplash.it/1400/799);-->
<!--           background-position: center;-->
<!--           background-repeat: no-repeat;-->
<!--           background-size: cover;-->
<!--           color: $onyx;-->
<!--           font-family: $font;-->
<!--           height: 100vh;-->
<!--           overflow: hidden;-->
<!--       }-->
<!---->
<!--    .App {-->
<!--        align-items: center;-->
<!--        display: flex;-->
<!--        height: 100vh;-->
<!--        justify-content: center;-->
<!--        width: 100vw;-->
<!--        position: absolute;-->
<!--        top: 0; left: 0;-->
<!--    }-->
<!---->
<!--    .Modal {-->
<!--        background: $onyx;-->
<!--        border-radius: 2px;-->
<!--        padding: 20px;-->
<!--        width: 200px;-->
<!--    }-->
<!---->
<!--    // Inputs-->
<!--       .Input {-->
<!--           display: flex;-->
<!--           flex-direction: row-reverse;-->
<!--           border-bottom: 1px solid rgba($cosmiclatte, .1);-->
<!--           padding-bottom: 3px;-->
<!--           margin-bottom: 5px;-->
<!---->
<!--           input {-->
<!--           // General styles-->
<!--           outline: none;-->
<!--               border: 0;-->
<!--               color: $cosmiclatte;-->
<!--               background: transparent;-->
<!--               font-family: $font;-->
<!--               flex: 1 0 auto;-->
<!--               font-size: 14px;-->
<!--               font-weight: 300;-->
<!---->
<!--           // Animation triggers-->
<!--           &:focus ~ label {-->
<!--               opacity: 1;-->
<!--           }-->
<!---->
<!--           // Icon abstractions-->
<!--           &[type='text'] {-->
<!--               ~ label {-->
<!--                   &::before {-->
<!--                       content: "\f007";-->
<!--                   }-->
<!--               }-->
<!--           }-->
<!--               &[type='email'] {-->
<!--                   ~ label {-->
<!--                       &::before {-->
<!--                           content: "\f1fa";-->
<!--                       }-->
<!--                   }-->
<!--               }-->
<!--               &[type='password'] {-->
<!--                   ~ label {-->
<!--                       &::before {-->
<!--                           content: "\f023";-->
<!--                       }-->
<!--                   }-->
<!--               }-->
<!--           }-->
<!---->
<!--           label {-->
<!--               font-family: FontAwesome;-->
<!--               font-size: 13px;-->
<!--               opacity: .1;-->
<!--               transition: opacity .5s ease;-->
<!---->
<!--               &::before {-->
<!--                   align-items: center;-->
<!--                   color: $cosmiclatte;-->
<!--                   display: flex;-->
<!--                   height: 30px;-->
<!--                   justify-content: center;-->
<!--                   width: 30px;-->
<!--               }-->
<!--           }-->
<!--       }-->
<!---->
<!--    // Button-->
<!--       button {-->
<!--           align-items: center;-->
<!--           background: $cinnabar;-->
<!--           border: 0;-->
<!--           border-radius: 3px;-->
<!--           color: white;-->
<!--           display: flex;-->
<!---->
<!--           font-family: $font;-->
<!--           font-size: 13px;-->
<!--           font-weight: 500;-->
<!--           justify-content: center;-->
<!--           margin-top: 20px;-->
<!--           outline: none;-->
<!--           padding: 10px 9px 10px 11px;-->
<!--           text-transform: uppercase;-->
<!--           width: 100%;-->
<!---->
<!--           .fa {-->
<!--               font-size: 12px;-->
<!--               margin-left: auto;-->
<!--               position: relative;-->
<!--               top: 1px;-->
<!--           }-->
<!---->
<!--           &:hover {-->
<!--               transform: scale(1.02);-->
<!--           }-->
<!---->
<!--           &:active {-->
<!--               transform: scale(.99);-->
<!--           }-->
<!--       }-->
<!---->
<!--    // Animation Classes-->
<!--       .example-enter {-->
<!--           margin-top: 30px;-->
<!--           opacity: .01;-->
<!---->
<!--           &.example-enter-active {-->
<!--               margin-top: 0px;-->
<!--               opacity: 1;-->
<!--               transition: opacity .5s ease, margin .5s ease;-->
<!--           }-->
<!--       }-->
<!---->
<!--    .example-leave {-->
<!--        margin-top: 0px;-->
<!--        opacity: 1;-->
<!---->
<!--        &.example-leave-active {-->
<!--            margin-top: -30px;-->
<!--            opacity: .01;-->
<!--            transition: opacity .3s ease, margin .5s ease;-->
<!--        }-->
<!--    }-->
<!--</style>-->