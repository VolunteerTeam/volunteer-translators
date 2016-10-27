<div class="container main" role="main">
    <?php
        if(isset($status)){
            switch($status){
                case "ok": echo "<p>Спасибо за регистрацию на нашем сайте! На Вашу электронную почту выслано письмо для подтверждения аккаунта.</p>";
                           break;
                case "DB fail": echo "<p>Извините, при сохранении данных произошла ошибка. Попробуйте зарегистрироваться позже.</p>";
                           break;
            }
        }

    ?>
</div>