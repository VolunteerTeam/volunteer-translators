<div class="modal hide fade"  id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Подтвердите свои намерения</h3>
    </div>
    <div class="modal-body">
        <p>Вы уверены что хотите удалить данный материал</p>
    </div>
    <input type="hidden" id="catalog-id" value="0"/>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">нет</button>
        <button class="btn btn-danger delete-catalog">Да, удалить!</button>
    </div>
</div>




<div id="wrapper">
    <?php $this->load->view('cpanel/sidebar'); ?>
    <!-- Page content -->
    <div id="page-content-wrapper">
        <?php $this->load->view('cpanel/breadcrumbs'); ?>
        <div class="page-content inset">
            <?php echo $message; ?>
            <div class="bestmedia-h5"></div>
            <div class="form-inline" >

               <!-- <div class="form-group col-md-5">
                    <div class="input-group">
                        <?php /*echo form_open('cpanel/news/search'); */?>
                        <input name="q" type="text" class="form-control">
                            <span class="input-group-btn">
                                <input type="submit"  name="search" value="Поиск" class="btn btn-default"/>
                            </span>
                        </form>
                    </div>
                </div>-->

     <!--           <div class="form-group col-md-5">
                    <?php /*echo form_open('cpanel/news/filter',array("id"=>"do_filter")); */?>
                    <select id="statusFilter" name="filter[status]" class="form_filter form-control">
                        <option>Фильтровать по статусу</option>
                        <option value="all">Все</option>
                        <option value="0">Только скрытые</option>
                        <option value="1">Только опубликованные</option>

                    </select>
                    <input type="submit" name="do_reset" value="Сбросить"  class="btn btn-danger"/>
                    </form>
                </div>-->

            </div>
            <div class="clearfix"></div>
            <div class="bestmedia-h20"></div>
            <div class="row bestmedia-add-and-pagination">
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <a href="<?=base_url('cpanel/catalog/add');?>" type="button" class="btn btn-primary">Добавить элемент</a>
                </div>
            </div>
            <div class="bestmedia-h20"></div>
            <div class="row">
                <div class="col-md-12">
                    <div  class="dd">
                    <?php

                    if(!empty($tree))
                    {
                       echo $tree;
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

</div>