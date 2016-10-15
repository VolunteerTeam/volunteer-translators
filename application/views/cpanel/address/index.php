<div id="wrapper">
    <?php $this->load->view('cpanel/sidebar'); ?>
    <!-- Page content -->
    <div id="page-content-wrapper">
        <?php $this->load->view('cpanel/breadcrumbs'); ?>
        <div class="page-content inset">
            <?php echo $message; ?>
            <div class="bestmedia-h5"></div>
            <div class="form-inline" >

                <div class="form-group col-md-5">
                    <?php echo form_open('cpanel/'.$this->controller.'/search'); ?>
                        <div class="input-group">
                            <input name="q" type="text" class="form-control">
                                <span class="input-group-btn">
                                    <input type="submit"  name="search" value="Поиск" class="btn btn-default"/>
                                </span>
                        </div>
                    </form>
                </div>

                <div class="form-group col-md-5">
                    <?php echo form_open('cpanel/'.$this->controller.'/filter',array("id"=>"do_filter")); ?>
                    <select id="statusFilter" name="filter[status]" class="form_filter form-control">
                        <option>Фильтровать по статусу</option>
                        <option value="all">Все</option>
                        <option value="0">Только скрытые</option>
                        <option value="1">Только опубликованные</option>

                    </select>
                    <input type="submit" name="do_reset" value="Сбросить"  class="btn btn-danger"/>
                    </form>
                </div>

            </div>
            <div class="clearfix"></div>
            <div class="bestmedia-h20"></div>
            <div class="row bestmedia-add-and-pagination">
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <a href="<?=base_url('cpanel/'.$this->controller.'/add');?>" type="button" class="btn btn-primary">Добавить адрес</a>
                </div>
            </div>
            <div class="bestmedia-h20"></div>
            <div class="row">
                <div class="col-md-12">
                    <?php

                    if(!empty($items))
                    {
                        ?>

                        <table class="table table-striped bestmedia-table">
                            <thead>
                            <th>Название</th>
                            <th>Действия</th>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($items as $item) {
                                ?>
                                <tr>
                                    <td><?=$item->title;?></td>

                                    <td class="col-xs-1">
                                        <div class="dropdown">
                                            <i class="status glyphicons <?php echo $item->status ==1?'':'eye_close'; ?> pull-right"></i>
                                            <a  class="glyphicons edit pull-right" tabindex="-1" href="<?=base_url('cpanel/'.$this->controller.'/edit/'.$item->id);?>"></a>

                                        </div>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    <?php
                    }
                    else
                    {
                        echo '<div class="alert alert-warning" role="alert"><p class="text-center">Ничего нет</p> </div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

</div>