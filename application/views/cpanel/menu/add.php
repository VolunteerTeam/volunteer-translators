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
                    <a href="<?php echo base_url($this->side.'/'.$this->controller) ?>" class="btn btn-primary pull-right">Отмена</a>
                    <div class="span3 checkbox pull-right">
                        <?php echo form_checkbox($status); ?>
                        Опубликовано
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane active" id="main">

                    <div class="bestmedia-h10"></div>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Позиция в иерархии</label>
                            <?php echo form_dropdown('pid', $pid['options'], $pid['default'], $pid['additional']); ?>
                        </div>
                    </div>
                    <div class="bestmedia-h10"></div>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Название</label>
                            <?php echo form_input($title); ?>
                        </div>
                    </div>
                    <div class="bestmedia-h10"></div>

                        <div class="row bestmedia-input">
                            <div class="col-md-10">
                                <label class="control-label">Адрес (относительный или абсолютный)</label>
                                <?php echo form_input($url); ?>
                            </div>
                        </div>

                    <div class="bestmedia-h10"></div>

                    <div class="row bestmedia-input">
                        <div class="col-md-2">
                            <label class="control-label">Позиция в списке</label>
                            <?php echo form_input($position); ?>
                        </div>
                    </div>

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
        </div>
        </form>
    </div>
</div>