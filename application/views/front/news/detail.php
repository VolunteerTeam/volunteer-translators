
<div class="container main" role="main">
    <div class="row-fluid">
        <div class="col-md-12">

            <h1><?php echo $item->title; ?></h1>
            <p><?=date('j',$item->news_date).' '.monthes(date('n',$item->news_date)).' '.date('Y',$item->news_date);?></p>
            <?=$item->full_content;?>
            <?php
            if(!empty($item->image)){echo '<p><img src="/content/'.$item->image->filename.'" class="img-thumbnail"></p>';}
            ?>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div class="clearfix"></div>