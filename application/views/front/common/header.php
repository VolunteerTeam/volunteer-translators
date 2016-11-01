<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta itemprop="name" content="Волонтёры Переводов — бесплатные переводы в благотворительных целях" />
    <meta property="og:title" content="Волонтёры Переводов — бесплатные переводы в благотворительных целях" />
    <meta itemprop="description" content="Волонтёры Переводов — бесплатные переводы в благотворительных целях" />
    <meta id="meta-tag-description" property="og:description" content="Волонтёры Переводов — бесплатные переводы в благотворительных целях" />
    <meta itemprop="image" content="/images/vk.com_volontery.perevodov_200x200.png" />
    <meta property="og:image" content="/images/vk.com_volontery.perevodov_200x200.png" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />

    <title>Волонтёры Переводов — бесплатные переводы в благотворительных целях</title>
    <meta name="keywords" content="Волонтёры Переводов — бесплатные переводы в благотворительных целях" />
    <meta name="description" content="Волонтёры Переводов — бесплатные переводы в благотворительных целях" />
    <link href="/css/front/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
    <link href="/css/front/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="/css/front/custom.css" rel="stylesheet" type="text/css" />
    <?php
        if(isset($css)){
            foreach($css as $css_link) {
                echo '<link href="'.$css_link.'" rel="stylesheet" type="text/css" />';
            }
        }
    ?>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <!-- фиксим загрузку jQuery - что если CDN не загрузится? -->
    <script type="text/javascript">
        if (typeof jQuery == 'undefined') {
            document.write(unescape("%3Cscript src='/js/front/jquery-2.1.4.min.js' type='text/javascript'%3E%3C/script%3E"));
        }
    </script>
    <script src="/js/front/custom.js"></script>
    <?php
    if(isset($js)){
        foreach($js as $js_link) {
            echo '<script src="'.$js_link.'"></script>';
        }
    }
    ?>

</head>
<body>
    <!-- FACEBOOK JavaScript SDK -->
    <div id="fb-root"></div>
    <script>
        (function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.0";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <!-- /FACEBOOK JavaScript SDK -->
    <div class="test_mode">Внимание! Это новый сайт сообщества. Мы постепенно на него переезжаем. Старый сайт тоже действует - <a href="http://volontery.perevodov.info" target="_blank">volontery.perevodov.info</a></div>
    <header>
        <div class="container header_top_wrap" style="height: 40px;">
            <div class="row">
                <div class="col-xs-12 header_city_wrap">
                    <div class="text-center" style="position: absolute;width:100%;left:0;right:0;z-index:5;">
                            <span style="color:#999"><small>Ваш город: </small></span>
                            <nobr>
                                <a href="#" id="choosecity" data-toggle="modal" data-target="#allCity">
                                    <span id="yourcity"></span>
                                </a>
                            </nobr>
                        </div>

                        <div class="pull-right" style="z-index:6; position: absolute; right:0;">
                            <?php
                                if($this->ion_auth->logged_in()) {
                                    $user_data = $this->ion_auth->user()->row();
                                ?>
                                <a href="/user/profile" role="button" aria-expanded="false"><?php echo $user_data->first_name ." ".$user_data->last_name; ?></a>
                                <a href="/user/logout">(выход)</a>
                            <?php } else { ?>
                                <a href="/user/auth">Вход</a> |
                                <a href="/user/register">Регистрация</a>
                            <?php } ?>
                        </div>
                </div>
            </div>
        </div>
        <div class="perevodov-bg">
            <div class="container">
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-xs-12 header_lang_wrap">
                        <a href="/" class="_ru ir"></a>
                        <a href="/en" class="_en ir"></a>
                        <a href="/cn" class="_cn ir"></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="vertical-align:bottom;padding-bottom:30px;padding-top:15px;">
                        <a href="/"><img src="/images/front/SiteLogo.png" alt="Волонтёры Переводов — бесплатные переводы в благотворительных целях"></a>
                    </div>
                    <div class="col-xs-0 col-sm-6 col-md-6 col-lg-6" style="text-align:right;padding-top:43px;color:#fff">
                        Бесплатные переводы <br>в благотворительных целях.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>

        <div class="container" style="padding-bottom:10px;padding-top:10px;border-bottom: 0px solid #ddd;">
            <div class="row">
                <div class="col-md-9">
                <nav role="navigation">
                    <div class="container-fluid">
                        <!-- Brand and toggle get grouped for better mobile display -->
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-top">
                                <span class="sr-only">Переключить навигации</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>
                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="navbar-collapse-top">
                            <ul class="nav nav-tabs">
                                 <li class="active" ><a href="http://v2.perevodov.info/">Главная <span class="sr-only">(current)</span></a></li>
                                            <li class="dropdown ">
                                                <a href="/clients" role="button" aria-expanded="false">Заказчики</a>
                                                <ul class="dropdown-menu" role="menu"><li><a href="http://v2.perevodov.info/translations">Переводы</a></li><li><a href="http://v2.perevodov.info/translations/add">Заказать перевод</a></li><li><a href="http://v2.perevodov.info/feedbacks">Отзывы</a></li></ul>
                                            </li>

                                            <li class="dropdown ">
                                                <a href="/volonter" role="button" aria-expanded="false">Волонтёры</a>
                                                <ul class="dropdown-menu" role="menu"><li><a href="http://v2.perevodov.info/volonter/join">Стать волонтёром</a></li><li><a href="http://v2.perevodov.info/volonter/rating">Лучшие волонтёры</a></li><li><a href="http://v2.perevodov.info/volonter/rules">Рабочие инструкции</a></li></ul>
                                            </li>

                                            <li class="dropdown ">
                                                <a href="/manager" role="button" aria-expanded="false">Менеджеры</a>
                                                <ul class="dropdown-menu" role="menu"><li><a href="http://v2.perevodov.info/manager/join">Стать менеджером</a></li><li><a href="http://v2.perevodov.info/manager/rating">Лучшие менеджеры</a></li><li><a href="http://v2.perevodov.info/manager/rules">Рабочие инструкции</a></li></ul>
                                            </li>

                                            <li class="dropdown ">
                                                <a href="/about" role="button" aria-expanded="false">О сообществе</a>
                                                <ul class="dropdown-menu" role="menu"><li><a href="http://v2.perevodov.info/news">Новости</a></li><li><a href="http://v2.perevodov.info/sovet">Совет сообщества</a></li><li><a href="http://v2.perevodov.info/ustav">Устав</a></li><li><a href="http://v2.perevodov.info/feedbacks">Отзывы о сообществе</a></li><li><a href="http://v2.perevodov.info/donate">Пожертвования</a></li><li><a href="http://drive.google.com/folderview?hl=ru&id=0BzvOSNcjw1viekVzNVpteXNVZDA#list">Outgoing / Исходящие</a></li><li><a href="http://drive.google.com/folderview?id=0BzNzJKym8oh0ckVPdG9wal9zdzA&usp=sharing">Файловый архив</a></li></ul>
                                            </li>

                                        </ul>

                        </div><!-- /.navbar-collapse -->
                    </div><!-- /.container-fluid -->
                </nav>
                </div>
                <div class="col-md-3>
                    <div header_networks_wrap" style="float:right">
                        <a href="http://vk.com/volontery.perevodov" target="_blank" class="si si-vk"></a>
                        <a href="http://www.facebook.com/volontery.perevodov" target="_blank" class="si si-fb"></a>
                        <a href="https://plus.google.com/109861946097838656833" target="_blank" class="si si-gp"></a>
                        <a href="http://www.odnoklassniki.ru/group/51877914804410" target="_blank" class="si si-ok"></a>
                    </div>
                </div>
            </div>
        </div>
    </header>

        

