<div class="container main" role="main">

    <h1>Города</h1>

    <div style="height:400px; width: 100%" id="google-map"></div>
    <p>
    <?php if(!empty($items)) { 
        foreach ($items as $item) { ?>
            <nobr><a href="/<?=$item->meta->url;?>" class="gorod_status_<?=$item->city_status;?>"><?=$item->title?></a></nobr> 
        <?php }
    } ?>
    </p>
    <small>
    <p><span class="gorod_status_3">&nbsp;</span> — города с нашими офисами</p>
    <p><span class="gorod_status_1">&nbsp;</span> — города с партнерами</p>
    <p><span class="gorod_status_2">&nbsp;</span> — города с представителями</p>
    <p><span class="gorod_status_0">&nbsp;</span> — города, в которых мы ищем партнёров</p>
    </small>

</div>
<div class="clearfix"></div>
<style>
    #google-map img {
        border: 0 none;
        height: auto;
        max-width: none;
    }
</style>