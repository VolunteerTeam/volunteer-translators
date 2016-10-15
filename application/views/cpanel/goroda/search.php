<div id="wrapper">
    <?php $this->load->view('cpanel/sidebar'); ?>
    <!-- Page content -->
    <div id="page-content-wrapper">
        <?php $this->load->view('cpanel/breadcrumbs'); ?>
        <div class="page-content inset">
            <?php echo $message; ?>
            <div class="bestmedia-h5"></div>

            <div class="row">
                <div class="col-md-5">
                    <?php echo form_open('cpanel/'.$this->controller.'/search'); ?>
                    <div class="input-group">
                        <input name="q" type="text" value="<?php echo $query; ?>" class="form-control">
                        <span class="input-group-btn">
                            <input type="submit"  name="search" value="Поиск" class="btn btn-default"/>
                        </span>
                    </div>
                    </form>
                </div>
            </div>

            <div class="bestmedia-h20"></div>
            <div class="row">
                <div class="col-md-12">
                    <?php if(!empty($items)) { ?>

                        <table class="table table-striped bestmedia-table">
                            <thead>
                            <th>Название</th>
                            <th class="col-xs-1">Действия</th>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($items as $item) {
                                ?>
                                <tr>

                                    <td><?=$item->title;?></td>
                                    <td>
                                        <div class="dropdown">
                                            <i class="status glyphicons <?php echo $item->status ==1?'':'eye_close'; ?> pull-right"></i>
                                            <a href="#" data-toggle="dropdown" class="glyphicons edit pull-right"></a>
                                            <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu1">
                                                <li><a role="menuitem" tabindex="-1" href="<?=base_url('cpanel/'.$this->controller.'/edit/'.$item->id);?>">Редактировать</a></li>
                                                <li><a data-show="Показывать" data-hide="Скрыть"
                                                       data-module="<?php echo $this->controller; ?>" data-id="<?php echo $item->id; ?>" class="toggle_status"
                                                       role="menuitem" tabindex="-1" href="#"><?php echo $item->status ==1?'Скрыть':'Показывать'; ?></a></li>
                                                <li class="divider"></li>
                                                <li><a role="menuitem" class="i_delete" tabindex="-1" href="<?=base_url('cpanel/'.$this->controller.'/remove/'.$item->id);?>"><i class="pull-left glyphicons remove_2"></i>Удалить</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php
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