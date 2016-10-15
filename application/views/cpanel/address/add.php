<div id="wrapper">
    <?php $this->load->view('cpanel/sidebar'); ?>
    <!-- Page content -->
    <div id="page-content-wrapper">
        <?php $this->load->view('cpanel/breadcrumbs'); ?>
        <?php echo form_open_multipart('',array('id'=>'news_add'));?>
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
                            <label class="control-label">Название</label>
                            <?php echo form_input($title); ?>
                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Адрес</label>
                            <?php echo form_input($address); ?>
                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Контактный телефон</label>
                            <?php echo form_input($phone); ?>
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
                            <label class="control-label">Общественный транспорт</label>
                            <?php echo form_textarea($transport); ?>
                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Парковка</label>
                            <?php echo form_textarea($parking); ?>
                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Координаты</label>
                            <?php echo form_input($coordinates); ?>
                        </div>
                    </div>
                    <div class="bestmedia-h20"></div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="panel panel-default">
                                <div class="panel-heading">Изображение здания</div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="index_file">Выберите одно изображение для загрузки</label>
                                        <input type="file" name="building[]" id="index_file" >
                                    </div>

                                    <div class="bestmedia-callout bestmedia-callout-info">
                                        Выберите файлы .JPG или .PNG не более 5 мегабайт<br>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bestmedia-h20"></div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="panel panel-default">
                                <div class="panel-heading">Изображение входа</div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="enter_file">Выберите одно изображение для загрузки</label>
                                        <input type="file" name="enter[]" id="enter_file" >
                                    </div>

                                    <div class="bestmedia-callout bestmedia-callout-info">
                                        Выберите файлы .JPG или .PNG не более 5 мегабайт<br>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bestmedia-h20"></div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="panel panel-default">
                                <div class="panel-heading">Изображение офиса</div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="office_file">Выберите одно изображение для загрузки</label>
                                        <input type="file" name="office[]" id="office_file" >
                                    </div>

                                    <div class="bestmedia-callout bestmedia-callout-info">
                                        Выберите файлы .JPG или .PNG не более 5 мегабайт<br>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
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
        </div>
        </form>
    </div>
</div>
