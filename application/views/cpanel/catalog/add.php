<div id="wrapper">
    <?php $this->load->view('cpanel/sidebar'); ?>
    <!-- Page content -->
    <div id="page-content-wrapper">

        <?php $this->load->view('cpanel/breadcrumbs'); ?>
        <?php echo form_open_multipart('',array('id'=>'catalog_add'));
        echo form_hidden('pid',$parent_id);
        ?>
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
                        <li class="active"><a href="#main" data-toggle="tab">Контент</a></li>
                        <li><a href="#seo" data-toggle="tab">SEO модуль</a></li>
                        <li><a href="#other" data-toggle="tab">Дополнительно</a></li>

                    </ul>
                </div>
            </div>
            <div class="row bestmedia-save-cancel">

                <div class="col-md-7 col-md-offset-5">
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
                    <!--HERE-->
                    <div class="row bestmedia-input">
                        <div class="col-md-6 col-sm-8 col-xs-12 col-lg-4">
                            <label class="control-label">Название раздела</label>
                            <?php echo form_input($title); ?>
                        </div>
                    </div>

                    <div class="bestmedia-h20"></div>
                    <div class="row">
                        <div class="col-xs-12">
                            <label class="control-label">Превью</label>
                            <?php echo form_textarea($catalog_content); ?>
                        </div>
                    </div>


                    <div class="row bestmedia-input">
                        <div class="col-md-6 col-sm-8 col-xs-12  col-lg-4">
                            <?php echo form_dropdown('type', $type['options'], $type['default'], $type['additional']); ?>
                        </div>
                    </div>
                    <div id="constructed" style="margin-bottom: 50px;">Для создания раздела - выберите тип шаблона</div>
                    <!--/HERE-->

                </div>
                <div class="tab-pane" id="seo">
                    <div class="row bestmedia-input">
                        <div class="col-md-12">
                            <label class="control-label">URL</label>
                            <?php echo form_input($seo_url); ?>

                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-12">
                            <label class="control-label">Title</label>
                            <div class="bestmedia-callout bestmedia-callout-info">Рекомендуется не более 70 символов</div>

                            <?php echo form_input($seo_title); ?>
                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-12">
                            <label class="control-label">Description</label>
                            <div class="bestmedia-callout bestmedia-callout-info">Рекомендуется не более 160 символов</div>

                            <?php echo form_textarea($seo_description); ?>
                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-12">
                            <label class="control-label">Keywords</label>
                            <div class="bestmedia-callout bestmedia-callout-info">Добавляйте слова через запятую</div>

                            <?php echo form_textarea($seo_keywords); ?>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="other">
                    <div class="row bestmedia-input">
                        <div class="col-md-6 col-sm-8 col-xs-12 col-lg-4">
                            <label class="control-label">css класс li элемента</label>
                            <?php echo form_input($class); ?>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        ckInit("catalog_content","<?php echo base_url();?>");
    });
</script>
