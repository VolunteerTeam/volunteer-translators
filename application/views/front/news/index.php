<div class="container main" role="main">
    <div class="row">
        <div class="col-md-12">
            <h1>Новости</h1>
                <?php
                if(!empty($items))
                {
                    foreach ($items as $item) {
                        echo '
                            <div class="row">
                                <a href="/news/'.date('Y-m-d',$item->news_date).'"><h2><span style="color:#999">'.substr('0'.date('n',$item->news_date),-2).'/'.date('Y',$item->news_date).'.</span> '.$item->title.'</h2></a>
                                <div class="col-md-4">';
                                    if(!empty($item->image)) { echo '<p><img src="/content/'.$item->image->filename.'" class="img-thumbnail"></p>'; }
                                    echo ' 
                                </div>
                                <div class="col-md-8">'.$item->full_content.'</div>
                                <div class="clearfix"></div>
                            </div>
                        ';
                    }
                }
                ?>
            <div class="row bestmedia-pagination">
                <div class="col-md-12">
                    <ul class="pagination pull-right">
                        <?php  echo $pagination; ?>
                    </ul>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div class="clearfix"></div>