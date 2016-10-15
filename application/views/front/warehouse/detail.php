<div class="container main" role="main">

    <!-- ЗАГОЛОВОК -->
    <h1><?=$item->title;?><nobr>
   	<?php if($item->city_status == 0){ ?><span class="gorod_status_0 gorod_sup">Только выездные и удалённые услуги</span><?php } ?>
   	<?php if($item->city_status == 1){ ?><span class="gorod_status_1 gorod_sup">Услуги оказывает наш партнёр</span><?php } ?>
   	<?php if($item->city_status == 2){ ?><span class="gorod_status_2 gorod_sup">В городе нет офиса, но работает наш представитель</span><?php } ?>
   	<?php if($item->city_status == 3){ ?><span class="gorod_status_3 gorod_sup">В этом городе есть офис</span><?php } ?>
    </nobr></h1>
    
    <!-- ВВЕДЕНИЕ -->
    <?php if(!empty($item->intro)){ ?>
	     <p><?=$item->intro;?></p>
	<?php } ?>

    <?php if($item->city_status == 0){ ?>
        <p>В этом городе компания "Переводов" оказывает только выездные и удаленные услуги:</p>
        <ul>
            <li>мы без накладок организуем устный <a href="/sin">cинхронный</a> или <a href="/int">последовательный</a> перевод для вашего мероприятия;</li>
            <li>выполним <a href="/tra">письменный перевод</a> любой документации;</li>
            <li><a href="/loc">локализуем</a> ваш сайт, программу или мобильное приложение;</li>
            <li>мы поможем <a href="/ved">найти за рубежом партнёров</a> для вашего бизнеса;</li>
            <li>выполним <a href="/not">нотариальный перевод</a> любых выших документов, но пока только по почте;</li>
            <li>иностранным языкам <a href="/edu">наши переводчики обучают</a> только по скайпу;</li>
            <li><a href="/gid">экскурсии</a>, а также <a href="/leg">легализацию, апостиль и нострификацию</a> в вашем городе мы пока не оказываем.</li>
        </ul>
    <?php } ?>

    <div class="row">
	    <div class="col-sm-9">

            <!-- КЛИЕНТЫ -->
            <?php if(!empty($item->our_clients)){ ?>
    			<h2>Наши клиенты</h2>
                <?=$item->our_clients;?>
            <?php } ?>
    
            <!-- КОНТАКТЫ ГОРОДА -->
            <h2>Контакты</h2>
            <ul>
                <?php if(!empty($item->shared_phone)){ echo '<li>телефон: '.$item->shared_phone.'</li>'; } ?>
                <?php if(!empty($item->shared_email)){ echo '<li>эл. почта: <a href="mailto:'.$item->shared_email.'">'.$item->shared_email.'</a></li>'; } ?>
                <?php if(!empty($item->work_hours)){ echo   '<li>график работы: '.$item->work_hours.'</li>'; } ?>
            </ul>
    
            <!-- АДРЕСА ГОРОДА -->
            <?php if(!empty($item->addresses)){ ?>
            <h2>Офисы</h2>
                <?php foreach($item->addresses as $address) { ?>
                    <div class="panel panel-default">
                        <div class="panel-heading panel-title" data-toggle="collapse" href="#Office<?=$address->id;?>">
                            <a href="#Office<?=$address->id;?>" style="border-bottom: 1px dashed #999;">Офис "<?=str_replace($item->title.". ", "",$address->title);?>"</a>
                        </div>
                        <div id="Office<?=$address->id;?>" class="panel-body panel-collapse collapse">
                            <ul>
                                <li><?php echo $address->address; ?>
                                <li>т. <?php echo $address->phone; ?>
                                <?php if(!empty($address->work_hours)){ echo   '<li>график работы: '.$address->work_hours.'</li>'; } ?>
                            </ul>
                            <?php
                                if(!empty($address->coordinates)){
                                    $c = explode(",",$address->coordinates);
                                    $c = array_reverse($c);
                                    $reverted = implode(",",$c);
                                    echo '<p><img alt="Карта" src="http://static-maps.yandex.ru/1.x/?lang=ru-RU&amp;ll='.$reverted.'&amp;size=600,400&amp;z=14&amp;l=map&amp;pt='.$reverted.',pm2rdl" /></p>';
                                    if(!empty($address->building_image) || !empty($address->enter_image) || !empty($address->office_image)){
                                        echo '
                                        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" style="width:600px">
                                          <!-- Indicators -->
                                          <ol class="carousel-indicators">
                                            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                                            <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                                            <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                                          </ol>
                                          <!-- Wrapper for slides -->
                                          <div class="carousel-inner" role="listbox">
                                            <div class="item active">
                                              <img src="/content/'.$address->building_image->filename.'" alt="Здание">
                                              <div class="carousel-caption">Здание</div>
                                            </div>
                                            <div class="item">
                                              <img src="/content/'.$address->enter_image->filename.'" alt="Вход в здание">
                                              <div class="carousel-caption">Вход в здание</div>
                                            </div>
                                            <div class="item">
                                              <img src="/content/'.$address->office_image->filename.'" alt="Вход в офис">
                                              <div class="carousel-caption">Вход в офис</div>
                                            </div>
                                          </div>
                                          <!-- Controls -->
                                          <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                          </a>
                                          <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                          </a>
                                        </div>
                                        ';
                                    }
                                }
                            ?>
                            <p><b>Проезд на общественном транспорте</b></p>
                            <?php echo $address->transport; ?>
                            <p><b>Проезд на личном автомобиле</b></p>
                            <?php echo $address->parking; ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>

            <!-- НОВОСТИ -->
            <?php if(!empty($item->news)) { ?>
                <h2>Новости</h2>
                <ul>
                <?php
                foreach($item->news as $news){
                    echo '<li> '.substr('0'.date('n',$news->news_date),-2).'.'.substr(date('Y',$news->news_date),-2).' <a href="/news/'.date('Y-m-d',$news->news_date).'">'.substr($news->title,0,125).'</a>...</li>';
                } ?>
                </ul>
            <?php } ?>

            <!-- СОЦСЕТИ ГОРОДА -->
            <h2>Группы переводчиков города</h2>
            <ul>
                <?php if(!empty($item->vk_link)){ echo      '<li>во ВКонтакте — <a href="'.$item->vk_link.'" target="_blank">'.str_replace('http://','',str_replace('https://','',$item->vk_link)).'</a></li>'; } ?>
                <?php if(!empty($item->fb_link)){ echo      '<li>в Фейсбуке — <a href="'.$item->fb_link.'" target="_blank">'.str_replace('http://','',str_replace('https://','',$item->fb_link)).'</a></li>'; } ?>
                <?php if(!empty($item->google_link)){ echo  '<li>в Гугле — <a href="'.$item->google_link.'" target="_blank">'.str_replace('http://','',str_replace('https://','',$item->google_link)).'</a></li>'; } ?>
                <?php if(!empty($item->od_link)){ echo      '<li>в Одноклассниках — <a href="'.$item->od_link.'" target="_blank">'.str_replace('http://','',str_replace('https://','',$item->od_link)).'</a></li>'; } ?>
            </ul>
    
            <!-- ПАРТНЕРЫ -->
            <?php if(!empty($item->our_partners)){ ?>
                <h2>Наши партнёры</h2>
                <?=$item->our_partners; ?>
            <?php } ?>

            <!-- БАНКОВСКИЕ РЕКВИЗИТЫ -->    
            <?php if(!empty($item->details)){ ?>
                <h2>Банковские реквизиты</h2>
                <?=$item->details;?>
            <?php } ?>

            <?php if($item->city_status == 0){ ?>
                <h2>Станьте нашим партнёром в этом городе</h2>
                <p>Мы: 1) размещаем ваши контактные данные на странице вашего города у нас на сайте, а также представляем вас как нашего партнёра; 2) предоставляем право модератора во всех наших группах переводчиков вашего города во всех основных социальных сетях; 3) переадресуем всю почту с ящика вашего города (например, <u>piter@perevodov.info</u> на вашу электронную почту.</p>
                <p>Ваша обязательство &mdash; отвечать на все запросы, поступающие на опубликованные контакты, а также оказывать заявленные услуги. Первые два месяца сотрудничества бесплатны, далее &mdash; символическая плата по договоренности.</p>
                <p>Чтобы стать партнёром в вашем городе &mdash; напишите письмо на goroda@perevodov.info</p>
            <?php } ?>

	    </div>
	    <div class="col-sm-3">

            <?php

            if(!empty($item->ceo)){
                ?>
                <!-- РУКОВОДИТЕЛЬ -->
                <h2><?=$item->post;?></h2>
                <?php if(!empty($item->ceo->avatar)){echo '<p><img class="img-thumbnail" width="200" src="/content/'.$item->ceo->avatar->filename.'" /></p>';}?>
                <h3><?=$item->ceo->username;?></h3>
                <p>
                    <?php if(!empty($item->ceo->skype))     { echo '<a href="'.$item->ceo->skype.'?chat"><i class="si si-sk"></i></a> ';}  ?>
                    <?php if(!empty($item->ceo->vk_profile)){ echo '<a href="'.$item->ceo->vk_profile.'" target="_blank"><i class="si si-vk"></i></a> ';}?>
                    <?php if(!empty($item->ceo->fb_profile)){ echo '<a href="'.$item->ceo->fb_profile.'" target="_blank"><i class="si si-fb"></i></a> ';}?>
                    <?php if(!empty($item->ceo->gp_profile)){ echo '<a href="'.$item->ceo->gp_profile.'" target="_blank"><i class="si si-gp"></i></a> ';}?>
                    <?php if(!empty($item->ceo->od_profile)){ echo '<a href="'.$item->ceo->od_profile.'" target="_blank"><i class="si si-ok"></i></a> ';}?>
                    <?php if(!empty($item->ceo->in_profile)){ echo '<a href="'.$item->ceo->in_profile.'" target="_blank"><i class="si si-ig"></i></a> ';}?>
                    <?php if(!empty($item->ceo->tw_profile)){ echo '<a href="'.$item->ceo->tw_profile.'" target="_blank"><i class="si si-tw"></i></a> ';}?>
                    <?php if(!empty($item->ceo->li_profile)){ echo '<a href="'.$item->ceo->li_profile.'" target="_blank"><i class="si si-li"></i></a> ';}?>
                </p>
                <div class="clearfix"></div>
                <?php if(!empty($item->ceo->email)){ echo '<p class="small"> <a href="mailto:'.$item->ceo->email.'">'.$item->ceo->email.'</a></p>';}  ?>
                <?php if(!empty($item->ceo->phone)){ echo '<p class="small">'.$item->ceo->phone.'</p>';}  ?>
            <?php
            }

            ?>

	    </div>
	    <div class="clearfix"></div>

    </div>
</div>
<div class="clearfix"></div>