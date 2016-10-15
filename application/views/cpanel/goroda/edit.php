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
                <div class="col-md-12">
                    <ul class="pager">
                        <li class="previous"><a href="<?=base_url($this->side.'/'.$this->controller);?>">&larr; Назад</a></li>
                    </ul>
                </div>
            </div>
            <div class="bestmedia-h15"></div>
            <div class="row bestmedia-tabs">
                <div class="col-md-12">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#main" data-toggle="tab">Основное содержимое</a></li>
                    </ul>
                </div>
            </div>
            <div class="row bestmedia-save-cancel">

                <div class="col-md-7 col-md-offset-3">
                    <input type="submit" name="do" value="Сохранить" class="btn btn-primary pull-right"/>
                    <a href="<?php echo base_url($this->side.'/'.$this->controller) ?>" class="btn btn-primary pull-right">Отмена</a>
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
                            <label class="control-label">Название</label>
                            <?php echo form_input($title); ?>
                        </div>
                    </div>
                    <input type="hidden" id="kladr_city_id" name="kladr_city_id" value=""/>
                    <input type="hidden" id="kladr_region_id" name="kladr_region_id" value=""/>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Название на английском</label>
                            <?php echo form_input($en_title); ?>
                        </div>
                    </div>

                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Интро</label>
                            <?php echo form_textarea($intro); ?>
                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Руководитель</label>
                            <?php echo form_dropdown('user_id', $user_id['options'], $user_id['default'], $user_id['additional']); ?>
                        </div>
                    </div>
                    <div class="bestmedia-h20"></div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <select data-placeholder="Выберите адреса"  multiple name="addresses[]" class="chosen form-control">
                                    <?php
                                    foreach ($addresses as $address) {
                                        $selected = in_array($address->id,$current_address) ? ' selected="selected"  ':'';
                                        echo '<option '.$selected.' value="'.$address->id.'">'.$address->title.'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Должность</label>
                            <?php echo form_input($post); ?>
                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Email</label>
                            <?php echo form_input($shared_email); ?>
                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Телефон</label>
                            <?php echo form_input($shared_phone); ?>
                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">График работы</label>
                            <?php echo form_input($work_hours); ?>
                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Ссылка вконтакте</label>
                            <?php echo form_input($vk_link); ?>
                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Фейсбук</label>
                            <?php echo form_input($fb_link); ?>
                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Google+</label>
                            <?php echo form_input($google_link); ?>
                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Одноклассники</label>
                            <?php echo form_input($od_link); ?>
                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Наши клиенты</label>
                            <?php echo form_textarea($our_clients); ?>
                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Наши партнеры</label>
                            <?php echo form_textarea($our_partners); ?>
                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">HTML код онлайн консультанта</label>
                            <?php echo form_textarea($online_consult); ?>
                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Реквизиты</label>
                            <?php echo form_textarea($details); ?>
                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Население</label>
                            <?php echo form_input($city_population); ?>
                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Координаты</label>
                            <?php echo form_input($coordinate); ?>
                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Статус приоритета</label>
                            <?php echo form_input($city_status); ?>
                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-4">
                            <label class="control-label">Показывать на главной?</label>
                            <?php echo form_checkbox($show_on_index); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row bestmedia-save-cancel">

                <div class="col-md-7 col-md-offset-3">
                    <input type="submit" name="do" value="Сохранить" class="btn btn-primary pull-right"/>
                    <a href="<?php echo base_url($this->side.'/'.$this->controller) ?>" class="btn btn-primary pull-right">Отмена</a>
                    <a class="btn btn-danger pull-right i_delete"  href="<?=base_url($this->side.'/'.$this->controller.'/remove/'.$item->id);?>"><i class="pull-left glyphicons remove_2"></i>Удалить</a>
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