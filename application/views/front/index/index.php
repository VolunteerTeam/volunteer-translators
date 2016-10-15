<div class="container main" role="main">
    <div class="row">
        <div class="col-md-12">

            <div class="row" style="margin-top:30px; margin-bottom:30px; ">
                <div class="col-md-9">
                    <b>Волонтёры Переводов — сообщество, в рамках которого переводчики-волонтёры со всего мира выполняют бесплатные языковые переводы в благотворительных целях.</b>
                </div>
                <div class="col-md-3" style="text-align:right">
                    <a href="/donate" class="btn btn-warning">Пожертвовать 100 р</a>
                </div>
            </div>

            <div class="carousel slide" data-ride="carousel" id="carousel-homepage">
            <ol class="carousel-indicators">
            	<li class="active" data-slide-to="0" data-target="#carousel-homepage">&nbsp;</li>
            	<li data-slide-to="1" data-target="#carousel-homepage">&nbsp;</li>
            	<li data-slide-to="2" data-target="#carousel-homepage">&nbsp;</li>
            	<li data-slide-to="3" data-target="#carousel-homepage">&nbsp;</li>
            </ol>
            <div class="carousel-inner" role="listbox">
            <div class="item active">
                <a href="/news/2015-02-25"><img alt="Фото" src="/images/front/slide_novy_site.png" /></a>
            </div>
            <div class="item">
                <a href="/translators/join"><img alt="Фото" src="/images/front/homepage-01.jpg" /></a>
                <div class="p-slider-h" style="top:220px; right:0%; text-align: right; padding-right: 5%;">Станьте переводчиком-волонтёром!</div>
                <div class="p-slider-p" style="top:260px; right:0%; text-align: right; padding-right: 5%;">Перевод лишь страницы — уже большая помощь</div>
            </div>
            <div class="item">
                <a href="/translations/add"><img alt="Фото" src="/images/front/homepage-03.jpg" /></a>
                <div class="p-slider-h" style="top:150px; right:0%; text-align: right; padding-right: 5%;">Закажите перевод документов!</div>
                <div class="p-slider-p" style="top:190px; right:0%; text-align: right; padding-right: 5%;">Мы поможем бесплатно</div>
            </div>
            <div class="item">
                <a href="/managers/join"><img alt="Фото" src="/images/front/homepage-05.jpg" /></a>
                <div class="p-slider-h" style="top:90px; right:0%; text-align: right; padding-right: 5%;">Станьте менеджером сообщества!</div>
                <div class="p-slider-p" style="top:130px; right:0%; text-align: right; padding-right: 5%;">Помогите искать свободных волонтёров</div>
            </div>
            </div>
            <a class="left carousel-control" data-slide="prev" href="#carousel-homepage" role="button"><span class="sr-only">Previous</span></a>
            <a class="right carousel-control" data-slide="next" href="#carousel-homepage" role="button"><span class="sr-only">Next</span></a>
            </div>

            <h2><a href="/news">Новости</a></h2>

                    <div class="row">
                    <?php
                    if(!empty($news))
                    { foreach ($news as $item) {
                        echo '<div class="col-md-4">';
                        echo '<h3><a href="/news/'.date('Y-m-d',$item->news_date).'">'.$item->title.'</a></h3>';
                        echo '<p><small>'.date('j',$item->news_date).' '.monthes(date('n',$item->news_date)).' '.date('Y',$item->news_date).'</small></p>';
                        if(!empty($item->image)) { 
                            echo '<img src="/content/'.$item->image->filename.'" class="img-thumbnail">'; 
                        }
                        echo '</div>';
                        }
                    }
                    ?>
                    </div>
                    <div class="clearfix"></div>

            <p><a href="/news">Все новости...</a></p>

            <h2>Отзывы о нас &mdash; добавьте свой!</h2>
            
            <div class="fb-comments" data-href="http://volontery.perevodov.info/" data-width="900" data-numposts="5" data-colorscheme="light"></div>

            <h2><a href="/translators">Карта волонтёров</a></h2>

            <div style="height:400px; width: 100%" id="google-map"></div>
            
        </div>
    </div>
</div>
<div class="clearfix"></div>