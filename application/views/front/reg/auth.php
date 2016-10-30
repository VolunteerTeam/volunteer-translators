<style>
    .form-signin {
        max-width: 380px;
        padding: 15px 35px 45px;
        margin: 0 auto;
    }
    .form-control {
        width:100%;
    }
    label.checkbox {
        margin-left:20px;
    }
    .form-signin h2 {
        text-align:center;
    }
</style>
<div class="container main" role="main">
    <div class="row">
        <?php echo $this->session->flashdata('msg'); ?>
    </div>

    <div class="wrapper">
        <?php
            $attributes = array("id" => "auth","class" => "form-signin");
            echo form_open("user/auth", $attributes);
        ?>
            <h2 class="form-signin-heading">Вход в Личный кабинет</h2>
            <?php
                if(isset($msg)) echo $msg;
            ?>
            <input type="text" class="form-control" name="email" placeholder="Email" required="" autofocus="" />
            <input type="password" class="form-control" name="password" placeholder="Пароль" required=""/>
            <label class="checkbox">
                <input type="checkbox" value="remember-me" id="remember" name="remember"> Запомнить меня
            </label>
            <input type="text" name="do_login" value="true" hidden>
            <button class="btn btn-success btn-block" type="submit">Войти</button>
            <a href="/user/forgot">Забыли пароль?</a>
        <?php echo form_close(); ?>
    </div>

</div>

