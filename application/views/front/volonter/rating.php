<div class="container main" role="main">

    <h1>Лучшие волонтёры</h1>

        <?php if(!empty($items)) { 
            foreach ($items as $item) { ?>
                <div class="row" style="border: 1px solid #999; background-color:#fefefe; margin: 5px 0; padding:5px; border-radius: 10px; font-size:10px"> 
                    <div class="col-md-2" style="font-size:20px"><?=$item->rating_year;?><br><?=monthes($item->rating_month, 'ru2');?></div> 
                    <div class="col-md-2"><a href="/<?=$item->u1_username;?>" title="<?=$item->u1_last_name;?> <?=$item->u1_first_name;?>"><img src="/content/<?=$item->u1_avatar;?>" width="120" style="opacity:1.0; -webkit-filter: grayscale(0%)"><br><?=$item->u1_last_name;?> <?=$item->u1_first_name;?></a><br><?=$item->u1_value;?> стр. </div> 
                    <div class="col-md-2"><a href="/<?=$item->u2_username;?>" title="<?=$item->u2_last_name;?> <?=$item->u2_first_name;?>"><img src="/content/<?=$item->u2_avatar;?>" width="115" style="opacity:0.8; -webkit-filter: grayscale(20%)"><br><?=$item->u2_last_name;?> <?=$item->u2_first_name;?></a><br><?=$item->u2_value;?> стр. </div> 
                    <div class="col-md-2"><a href="/<?=$item->u3_username;?>" title="<?=$item->u3_last_name;?> <?=$item->u3_first_name;?>"><img src="/content/<?=$item->u3_avatar;?>" width="110" style="opacity:0.6; -webkit-filter: grayscale(40%)"><br><?=$item->u3_last_name;?> <?=$item->u3_first_name;?></a><br><?=$item->u3_value;?> стр. </div> 
                    <div class="col-md-2"><a href="/<?=$item->u4_username;?>" title="<?=$item->u4_last_name;?> <?=$item->u4_first_name;?>"><img src="/content/<?=$item->u4_avatar;?>" width="105" style="opacity:0.4; -webkit-filter: grayscale(60%)"><br><?=$item->u4_last_name;?> <?=$item->u4_first_name;?></a><br><?=$item->u4_value;?> стр. </div> 
                    <div class="col-md-2"><a href="/<?=$item->u5_username;?>" title="<?=$item->u5_last_name;?> <?=$item->u5_first_name;?>"><img src="/content/<?=$item->u5_avatar;?>" width="100" style="opacity:0.2; -webkit-filter: grayscale(80%)"><br><?=$item->u5_last_name;?> <?=$item->u5_first_name;?></a><br><?=$item->u5_value;?> стр. </div> 
                </div>

            <?php }
        } ?>

</div>