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

    <?php $title = array_shift($meta); ?>
    <title><?php echo $title['content'];?></title>
    <?php
    //Разбор meta
    if(isset($meta) and is_array($meta)) {
        foreach($meta as $type=> $content) {
            echo '<meta name="'.$type.'" content="'.$content['content'].'" />
';
        } }
    ?>
    <?php
    //Разбор css
    if(isset($css) and is_array($css)) {
        foreach($css as $item) {
            echo '<link href="'.$item['src'].'" rel="stylesheet" type="text/css" />
';
        } }
    ?>

    <style type="text/css">
        <?php
        //Разбор inline_css
        if(isset($inline_css) and is_array($inline_css)) {
        foreach($inline_css as $item) {
        echo $item;
        } } ?>
    </style>

    <?php
    //Разбор js
    if(isset($js['header']) and is_array($js['header'])) {
        foreach($js['header'] as $item) {
            echo '<script type="text/javascript" src="'.$item['src'].'"></script>
';
        } } ?>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
</head>
<body>
<!-- FACEBOOK JavaScript SDK -->
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.0";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
<!-- /FACEBOOK JavaScript SDK -->
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
	                	<a href="/user/reg">Регистрация</a>
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
                            <?php
                            if(!empty($navigation))
                            {
                                foreach($navigation as $item){
                                    $url_parts = explode("/",trim($item->url,'/'));

                                    if(!empty($item->submenu)){
                                        $active = ($url_parts[0]==$current_uri) ?  'active' : '';
                                        $url = preg_match('/http:/',$item->url) ? $item->url : base_url($item->url);
                                        echo '
                                        <li class="dropdown '.$active.'">
                                            <a href="'.$item->url.'" role="button" aria-expanded="false">'.$item->title.'</a>
                                            <ul class="dropdown-menu" role="menu">';
                                                foreach($item->submenu as $sub_item){
                                                    $sub_url = preg_match('/http:/',$sub_item->url) ? $sub_item->url : base_url($sub_item->url);
                                                    echo '<li><a href="'.$sub_url.'">'.$sub_item->title.'</a></li>';
                                                }
                                            echo '</ul>
                                        </li>
                                        ';
                                    } else {
                                        $active = ($url_parts[0]==$current_uri) ?  'class="active"' : '';
                                        echo ' <li '.$active.' ><a href="'.base_url($item->url).'">'.$item->title.' <span class="sr-only">(current)</span></a></li>';
                                    }
                                }
                            }

                            ?>
							
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
    <div class="site-warning container">Внимание! Это новый сайт сообщества. Мы постепенно на него переезжаем. Старый сайт тоже действует - <a href="http://volontery.perevodov.info" target="_blank">volontery.perevodov.info</a></div>
</header>

<div class="clearfix"></div>