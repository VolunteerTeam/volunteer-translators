<div id="wrapper">
    <?php $this->load->view('cpanel/sidebar'); ?>
    <!-- Page content -->
    <div id="page-content-wrapper">

        <?php $this->load->view('cpanel/breadcrumbs'); ?>
        <?php echo form_open_multipart('');?>
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
            <div class="bestmedia-h10"></div>
            <div class="row bestmedia-save-cancel">
                <div class="col-md-12">
                    <a href="<?php echo base_url($this->side.'/'.$this->controller) ?>" class="btn-link btn-primary">Отмена</a>
                    <input type="submit" name="do" value="Сохранить" class="btn btn-primary"/>
                </div>
            </div>
 
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

            <div class="row bestmedia-input">
                <div class="col-md-6"><label class="control-label">Пароль</label><?php echo form_input($password); ?></div>
                <div class="col-md-6"><label class="control-label">Повторите пароль</label><?php echo form_input($password_confirm); ?></div>
            </div> 
            
            <div class="bestmedia-h20"></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <select data-placeholder="Выберите группы." multiple name="groups[]" class="chosen form-control">
                        <?php
                            foreach ($groups as $group) {
                                echo '<option '.( in_array($group['id'],$this->input->post('groups'))?' selected="selected"':'' ).' value="'.$group['id'].'">'.$group['description'].'</option>';
                            }
                        ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bestmedia-h20"></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Аватар</div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="index_file">Выберите одно изображение для загрузки</label>
                                <input type="file" name="avatar[]" id="index_file" >
                            </div>
                            <div class="bestmedia-callout bestmedia-callout-info">
                                Выберите файлы .JPG или .PNG не более 5 мегабайт<br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div class="bestmedia-h10"></div>
            <div class="row bestmedia-save-cancel">
                <div class="col-md-12">
                    <a href="<?php echo base_url($this->side.'/'.$this->controller) ?>" class="btn-link btn-primary">Отмена</a>
                    <input type="submit" name="do" value="Сохранить" class="btn btn-primary"/>
                </div>
            </div>
        </div>
        </form>
    </div>

</div>