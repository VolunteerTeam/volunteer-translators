<div id="wrapper">
    <?php $this->load->view('cpanel/sidebar'); ?>
    <!-- Page content -->
    <div id="page-content-wrapper">
        <?php $this->load->view('cpanel/breadcrumbs'); ?>
        <?php echo form_open_multipart('');?>
        <div class="page-content inset">
            <div class="bestmedia-h5"></div>
            <div class="row bestmedia-back-button">
                <div class="col-md-12">
                    <ul class="pager">
                        <li class="previous"><a href="<?=base_url($this->side.'/'.$this->controller);?>">&larr; Назад</a></li>
                    </ul>
                </div>
            </div>
            <?php echo $message; ?>
            <div class="bestmedia-h10"></div>
            <div class="row bestmedia-save-cancel">
                <div class="col-md-12">
                    <a href="<?php echo base_url($this->side.'/'.$this->controller) ?>" class="btn-link btn-primary">Отмена</a>
                    <input type="submit" name="do" value="Сохранить" class="btn btn-primary"/>
                </div>
            </div>
            <div class="bestmedia-h10"></div>

            <div class="row bestmedia-input">
                <div class="col-md-4"><label class="control-label">Фамилия</label><?php echo form_input($last_name); ?></div>
                <div class="col-md-4"><label class="control-label">Имя</label><?php echo form_input($first_name); ?></div>
                <div class="col-md-4"><label class="control-label">Отчество</label><?php echo form_input($patro_name); ?></div>
            </div>
            <div class="row bestmedia-input">
                <div class="col-md-12"><label class="control-label">Представление</label><?php echo form_input($intro); ?></div>
            </div>
             <div class="row bestmedia-input">
                <div class="col-md-6"><label class="control-label">Должность</label><?php echo form_input($job_post); ?></div>
                <div class="col-md-6"><label class="control-label">Логин (url)</label><?php echo form_input($username); ?></div>
            </div>
            <div class="row bestmedia-input">
                <div class="col-md-4"><label class="control-label">Дата рождения</label><?php echo form_input($dob); ?></div>
                <div class="col-md-4"><label class="control-label">Пол</label><?php echo form_input($sex); ?></div>
                <div class="col-md-4"><label class="control-label">Координаты</label><?php echo form_input($coordinates); ?> </div>
            </div>
            <div class="row bestmedia-input">
                <div class="col-md-4"><label class="control-label">Сотовый телефон</label><?php echo form_input($phone); ?></div>
                <div class="col-md-4"><label class="control-label">Электронная почта</label><?php echo form_input($email); ?></div>
                <div class="col-md-4"><label class="control-label">Скайп</label><?php echo form_input($skype); ?> </div>
            </div>

            <div class="bestmedia-h50"></div>
            <div class="row bestmedia-input">
                <div class="col-md-6"><label class="control-label">Фейсбук</label><?php echo form_input($fb_profile); ?></div>
                <div class="col-md-6"><label class="control-label">ВКонтакте</label><?php echo form_input($vk_profile); ?></div>
            </div>
            <div class="row bestmedia-input">
                <div class="col-md-6"><label class="control-label">Одноклассники</label><?php echo form_input($od_profile); ?> </div>
                <div class="col-md-6"><label class="control-label">Гуг+</label><?php echo form_input($gp_profile); ?> </div>
            </div>
            <div class="row bestmedia-input">
                <div class="col-md-6"><label class="control-label">Твиттер</label><?php echo form_input($tw_profile); ?></div>
                <div class="col-md-6"><label class="control-label">Инстаграм</label><?php echo form_input($in_profile); ?></div>
            </div>
            <div class="row bestmedia-input">
                <div class="col-md-6"><label class="control-label">Живой журнал</label><?php echo form_input($lj_profile); ?></div>
                <div class="col-md-6"><label class="control-label">ЛинкедИн</label><?php echo form_input($li_profile); ?></div>
            </div>

            <div class="bestmedia-h50"></div>
            <div class="row">
                <div class="col-md-12">
                <label class="control-label">Группы</label>
                    <div class="form-group">
                        <select data-placeholder="Выберите группы."  multiple name="groups[]" class="chosen form-control">
                            <?php
                            foreach ($groups as $group) {

                                echo '<option '.( isset($current_groups[$group['id']])? ' selected="selected" ':''  ).' value="'.$group['id'].'">'.$group['description'].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row bestmedia-input">
                <div class="col-md-6"><label class="control-label">Пароль</label><?php echo form_input($new_password); ?></div>
                <div class="col-md-6"><label class="control-label">Повторите пароль</label><?php echo form_input($password_confirm); ?></div>
            </div>            
            
            
            
            <div class="bestmedia-h20"></div>
            <div class="row">
                <div class="panel dop_panel" style="margin-left: 15px; margin-right: 15px">
                    <div style="color: #333333;background-color: #f5f5f5;border-color: #dddddd; padding: 10px 15px;">Аватар</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label style="font-weight: bold; display: inline-block;margin-bottom: 5px;font-size: 15px;" for="index_file">Выберите одно изображение для загрузки</label>
                            <label style="font-weight: bold; display: inline-block;margin-bottom: 5px;font-size: 15px; float: right;" for="index_file">Текущее фото</label><br >

                            <div style="float:right;" class="cropit-image-preview">
                                <img src="<?php echo $avatar_image; ?>">
                            </div>

                            <div id="image-cropper" style="width: 119px;">
                              <div class="cropit-image-preview"></div>
                              <br>
                              <input type="range" class="cropit-image-zoom-input" />
                              <br>
                              <!-- The actual file input will be hidden -->
                              <input type="file" class="cropit-image-input"/>
                              <input type="hidden" name="uploadfile" id="uploadfile">
                            </div>

                        <div class="botstrapPoln-stroka">
                            Выберите файлы .JPG или .PNG не более 5 мегабайт
                            <br>
                        </div>
                        

                    </div>
                </div>
        </div>

        <script>
            var h = $('#image-cropper').cropit();
            $('.select-image-btn').click(function() {
                $('.cropit-image-input').click();
            });

            function based64() {
                var imageData = h.cropit('export');
                $('#uploadfile').val(imageData);
            };
        </script>

            <div class="bestmedia-h12"></div>
            <div class="row bestmedia-save-cancel">
                <div class="col-md-12">
                    <a href="<?php echo base_url($this->side.'/'.$this->controller) ?>" class="btn-link btn-primary">Отмена</a>
                    <a class="btn btn-danger  i_delete"  href="<?=base_url($this->side.'/'.$this->controller.'/remove/'.$user_id);?>"><i class="pull-left glyphicons remove_2"></i>Удалить</a>
                    <input type="submit" name="do" value="Сохранить" class="btn btn-primary" onclick="return based64();" />
                </div>
            </div>
        </div>
        </form>
    </div>
</div>