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
            echo form_open("user/auth/ajax", $attributes);
        ?>
            <h2 class="form-signin-heading">Вход в Личный кабинет</h2>
            <div class="alert alert-danger text-center" style="display:none;">Неверный логин или пароль!</div>
            <input type="text" class="form-control" name="mail" placeholder="Email" required="" autofocus="" />
            <input type="password" class="form-control" name="password" placeholder="Пароль" required=""/>
            <label class="checkbox">
                <input type="checkbox" value="remember-me" id="rememberMe" name="rememberMe"> Запомнить меня
            </label>
            <button class="btn btn-success btn-block" type="submit" name="submit">Войти</button>
            <a href="/user/forgot">Забыли пароль?</a>
        <?php echo form_close(); ?>
    </div>

</div>

<script>
    $(document).ready(function(){
        // this is the id of the form
        $("#auth").submit(function() {
            var url = "/user/auth/ajax"; // the script where you handle the form input.

            $.ajax({
                type: "POST",
                url: url,
                data: $("#auth").serialize(), // serializes the form's elements.
                dataType: 'json'
            })
                .done(function(data) {
                    if(data.success) {
                        window.location = "/user/profile";
                    } else {
                        $(".alert-danger").show();
                    }
                });
            event.preventDefault();
        });
    });
</script>
