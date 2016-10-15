<div class="content-header">
    <div class="col-md-10 col-sm-10 col-xs-10">
        <a id="menu-toggle" href="<?php echo base_url($this->side); ?>" class="btn btn-primary"><i class="glyphicon glyphicon-align-justify"></i></a>
        <ol class="breadcrumb">
            <?php

            if(!empty($this->breadcrumbs))
            {
                $i = 1;
                $total = count($this->breadcrumbs);
                foreach ($this->breadcrumbs as $bit) {
                    if($i==$total){
                        echo '<li  class="active">'.$bit['title'].'</li>';
                    } else {
                        echo '<li><a href="'.$bit['url'].'">'.$bit['title'].'</a></li>';
                    }

                    $i++;
                }

            }


            ?>

        </ol>
    </div>
    <div class="col-md-2 col-sm-2 col-xs-2">
        <a href="<?php echo base_url('login/logout'); ?>" class="log-out pull-right">Выйти</a>
    </div>
</div>