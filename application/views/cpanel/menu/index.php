<div id="wrapper">
    <?php $this->load->view('cpanel/sidebar'); ?>
    <!-- Page content -->
    <div id="page-content-wrapper">
        <?php $this->load->view('cpanel/breadcrumbs'); ?>
        <div class="page-content inset">
            <?php echo $message; ?>


            <div class="bestmedia-h20"></div>
            <div class="row bestmedia-add-and-pagination">
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <a href="<?=base_url('cpanel/'.$this->controller.'/add');?>" type="button" class="btn btn-primary">Добавить пункт меню</a>
                </div>
            </div>
            <div class="bestmedia-h20"></div>
            <div class="row">
                <div class="col-md-12">
                    <?php if(!empty($items)) { ?>

                        <table class="table bestmedia-table-cat">
                            <thead>
                            <th>Название</th>
                            <th>Адрес</th>
                            <th>Действия</th>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($items as $item) {
                                ?>
                                <tr>
                                    <?php

                                    if(!empty($item->sub_menu))
                                    {
                                        ?>
                                        <td>
                                            <span class="cat" data-toggle="collapse" data-target="#sub-cat<?=$item->id;?>">
                                                <i class="halflings-icon chevron-down"></i><?=$item->title;?>
                                            </span>
                                        </td>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <td><?=$item->title;?></td>
                                        <?php
                                    }

                                    ?>


                                    <td><?=$item->url;?></td>
                                    <td class="col-xs-1">
                                        <div class="dropdown">
                                            <i class="status glyphicons <?php echo $item->status ==1?'':'eye_close'; ?> pull-right"></i>
                                            <a  class="glyphicons edit pull-right" href="<?=base_url('cpanel/'.$this->controller.'/edit/'.$item->id);?>"></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                                if(!empty($item->sub_menu))
                                {
                                    ?>
                                    <tr class="sub-cat">
                                        <td colspan="3">
                                            <div id="sub-cat<?=$item->id;?>" class="panel-collapse collapse in">
                                                <table class="table">
                                                    <tbody>
                                                    <?php
                                                    foreach($item->sub_menu as $sub_item)
                                                    {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $sub_item->title; ?></td>
                                                            <td><?php echo $sub_item->url;?></td>
                                                            <td class="col-xs-1">
                                                                <div class="dropdown">
                                                                    <i class="status glyphicons <?php echo $sub_item->status ==1?'':'eye_close'; ?> pull-right"></i>
                                                                    <a  class="glyphicons edit pull-right" href="<?=base_url('cpanel/'.$this->controller.'/edit/'.$sub_item->id);?>"></a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php

                                                    }
                                                    ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php

                                }
                            }
                            ?>
                            </tbody>
                        </table>

                    <?php } else {
                        echo '<div class="alert alert-warning" role="alert"><p class="text-center">Ничего нет</p> </div>';
                    } ?>



                </div>
            </div>
        </div>
    </div>

</div>