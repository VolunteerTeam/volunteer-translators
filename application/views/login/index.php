<!DOCTYPE html>
<html lang="ru">
<head>
    <title><?php echo $title['content'];?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    //Разбор meta
    if(isset($meta) and is_array($meta))
    {
        foreach($meta as $type=> $content)
        {
            echo '<meta name="'.$type.'" content="'.$content['content'].'" />
            ';
        }
    }
    ?>

    <?php
    //Разбор css
    if(isset($css) and is_array($css))
    {
        foreach($css as $item)
        {
            echo '<link href="'.$item['src'].'" rel="stylesheet" type="text/css" />
            ';
        }
    }
    ?>
    <style>
        <?php
    //Разбор inline_js
        if(isset($inline_css) and is_array($inline_css))
        {
            foreach($inline_css as $item)
            {
                echo $item;
            }
        }

        ?>
    </style>
    <?php
    //Разбор js
    if(isset($js['header']) and is_array($js['header']))
    {
        foreach($js['header'] as $item)
        {
            echo '<script type="text/javascript" src="'.$item['src'].'"></script>
            ';
        }
    }


    ?>

</head>
<body>


<div id="wrapper">
    <!-- Sidebar -->
    <div id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <li class="sidebar-brand"><a href="<?php echo base_url('cpanel'); ?>">ПЕРЕВОДОВ</a></li>
            <li><a href="<?php echo base_url('login'); ?>">Вход</a></li>
            <li><a href="<?php echo base_url('login/restore'); ?>">Восстановление доступа</a></li>

        </ul>
    </div>
    <!-- Page content -->
    <div id="page-content-wrapper">
        <div class="content-header">
            <div class="col-md-10 col-sm-10 col-xs-10">
                <a id="menu-toggle" href="#" class="btn btn-primary"><i class="glyphicon glyphicon-fire"></i></a>
                <ol class="breadcrumb">
                    <li class="active">Авторизация</li>
                </ol>
            </div>

        </div>

        <div class="page-content inset">

            <?php echo $message; ?>
            <?php echo form_open('',array('id'=>'login_form')); ?>
                <div class="form-group">
                    <label for="email">Email</label>
                    <?php echo form_input($email);?>

                </div>
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <?php echo form_input($password);?>

                </div>

                <div class="checkbox">
                    <label>
                        <?php echo form_checkbox($remember);?> запомнить меня
                    </label>
                </div>
                <input type="submit" name="do_login" value="Войти" class="btn btn-default"/>
            </form>
        </div>
    </div>
</div>

<?php
//Разбор js
if(isset($js['footer']) and is_array($js['footer']))
{
    foreach($js['footer'] as $item)
    {
        echo '<script type="text/javascript" src="'.$item['src'].'"></script>
            ';
    }
}


?>



<script type="text/javascript">
    $(function() {
        <?php
    //Разбор inline_js
        if(isset($inline_js) and is_array($inline_js))
        {
            foreach($inline_js as $item)
            {
                echo $item;
            }
        }

        ?>
    });
</script>

</body>
</html>