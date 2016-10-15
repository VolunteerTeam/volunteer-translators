<div id="wrapper">
    <?php $this->load->view('cpanel/sidebar'); ?>
    <!-- Page content -->
    <div id="page-content-wrapper">
        <?php $this->load->view('cpanel/breadcrumbs'); ?>
        <?php echo form_open_multipart('',array('id'=>'news_edit'));?>
        <div class="page-content inset">
            <?php echo $message; ?>
            <div class="bestmedia-h5"></div>
            <div class="row bestmedia-back-button">
                <div class="col-md-10">
                    <ul class="pager">
                        <li class="previous"><a href="<?=base_url($this->side.'/'.$this->controller);?>">&larr; Назад</a></li>
                    </ul>
                </div>
            </div>
            <div class="bestmedia-h15"></div>
            <div class="row bestmedia-tabs">
                <div class="col-md-10">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#main" data-toggle="tab">Контент</a></li>
                        <li><a href="#seo" data-toggle="tab">SEO модуль</a></li>
                    </ul>
                </div>
            </div>
            <div class="row bestmedia-save-cancel">

                <div class="col-md-7 col-md-offset-3">
                    <input type="submit" name="do" value="Сохранить" class="btn btn-primary pull-right"/>
                    <a href="<?php base_url($this->side.'/'.$this->controller) ?>" class="btn btn-primary pull-right">Отмена</a>
                    <div class="span3 checkbox pull-right">
                        <?php echo form_checkbox($status); ?>
                        Опубликовано
                    </div>
                </div>
            </div>

            <div class="tab-content">
                <div class="tab-pane active" id="main">
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Заголовок</label>
                            <?php echo form_input($title); ?>
                        </div>
                    </div>
                    <div class="bestmedia-h20"></div>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Дата публикации</label>
                            <?php echo form_input($news_date); ?>
                        </div>
                    </div>
                    <div class="bestmedia-h20"></div>
                    <div class="row">
                        <div class="col-md-10">
                            <label class="control-label">Привязка к городу</label>
                            <div class="form-group">
                                <select data-placeholder="Выберите города." multiple name="cities[]" class="chosen form-control">
                                    <?php
                                    foreach ($cities as $city) {

                                        echo '<option '.( in_array($city->id,$current_cities) ? ' selected="selected" ':'' ).' value="'.$city->id.'">'.$city->title.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="bestmedia-h20"></div>
                    <div class="row">
                        <div class="col-xs-10">
                            <label class="control-label">Содержание</label>
                            <?php echo form_textarea($full_content); ?>
                        </div>
                    </div>

                    <div class="bestmedia-h20"></div>

                    <div class="row">
                        <div class="col-md-10">
                            <div class="panel panel-default">
                                <div class="panel-heading">Фотография</div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="index_file">Выберите изображение для загрузки</label>
                                        <input type="file" name="index[]" id="index_file" >
                                    </div>

                                    <div class="bestmedia-callout bestmedia-callout-info">
                                        Выберите файлы .JPG или .PNG не более 5 мегабайт<br>

                                    </div>
                                    <div class="bestmedia-h10"></div>

                                    <?php

                                    if(!empty($index_image))
                                    {
                                        ?>
                                        <div class="img-sorting">
                                            <div class="col-xs-5 col-sm-4 col-md-3 col-lg-2">
                                                <div href="#" class="thumbnail">
                                                    <div class="dropup">
                                                        <i class="status glyphicons <?php echo $index_image->status ==1?'':'eye_close'; ?> pull-right"></i>
                                                        <a href="#" data-toggle="dropdown" class="glyphicons edit pull-right"></a>
                                                        <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu1">
                                                            <li><a
                                                                    href="javascript:void(0)"
                                                                    data-show="Показывать"
                                                                    data-hide="Скрыть"
                                                                    data-module="gallery"
                                                                    data-id="<?php echo $index_image->id; ?>"
                                                                    class="toggle_status"
                                                                    role="menuitem" tabindex="-1" href="#"><?php echo $index_image->status ==1?'Скрыть':'Показывать'; ?></a></li>
                                                            <li class="divider"></li>
                                                            <li><a  class="i_delete" role="menuitem" tabindex="-1" href="<?php echo base_url('cpanel/gallery/remove_gallery_item/'.$index_image->id.'/news/'.$item->id); ?>"><i class="pull-left glyphicons remove_2"></i>Удалить</a></li>
                                                        </ul>
                                                    </div>
                                                    <img src="/<?php echo CONTENT_DIR ?>/<?php echo  $index_image->filename; ?>" alt="">
                                                </div>
                                            </div>

                                        </div>
                                    <?php
                                    }
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="seo">
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">URL</label>
                            <?php echo form_input($seo_url); ?>
                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Title</label>
                            <div class="bestmedia-callout bestmedia-callout-info">Рекомендуется не более 70 символов</div>
                            <?php echo form_input($seo_title); ?>
                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Description</label>
                            <div class="bestmedia-callout bestmedia-callout-info">Рекомендуется не более 160 символов</div>
                            <?php echo form_textarea($seo_description); ?>
                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Keywords</label>
                            <div class="bestmedia-callout bestmedia-callout-info">Добавляйте слова через запятую</div>
                            <?php echo form_textarea($seo_keywords); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row bestmedia-save-cancel">

                <div class="col-md-7 col-md-offset-3">
                    <input type="submit" name="do" value="Сохранить" class="btn btn-primary pull-right"/>

                    <a href="<?php base_url($this->side.'/'.$this->controller) ?>" class="btn btn-primary pull-right">Отмена</a>
                    <a role="menuitem" class="btn btn-danger pull-right i_delete" tabindex="-1" href="<?=base_url('cpanel/news/remove/'.$item->id);?>">
                        <i class="pull-left glyphicons remove_2"></i>Удалить</a>
                    <div class="span3 checkbox pull-right">
                        <?php echo form_checkbox($status); ?>
                        Опубликовано
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>

</div>