<div id="wrapper">
    <?php $this->load->view('cpanel/sidebar'); ?>
    <!-- Page content -->
    <div id="page-content-wrapper">
        <?php $this->load->view('cpanel/breadcrumbs'); ?>
        <?php echo form_open('');?>
        <div class="page-content inset">
            <?php echo $message; ?>
            <div class="bestmedia-h5"></div>
            <div class="row bestmedia-back-button">
                <div class="col-md-12">
                    <ul class="pager">
                        <li class="previous"><a href="<?=base_url($this->side.'/'.$this->controller.'/groups');?>">&larr; Назад</a></li>
                    </ul>
                </div>
            </div>

            <div class="bestmedia-h20"></div>
            <div class="row bestmedia-input">
                <div class="col-md-10">
                    <label class="control-label">Название</label>
                    <?php echo form_input($group_name); ?>
                </div>
            </div>
            <div class="bestmedia-h10"></div>
            <div class="row bestmedia-input">
                <div class="col-md-10">
                    <label class="control-label">Описание</label>
                    <?php echo form_input($description); ?>
                </div>
            </div>
            <div class="bestmedia-h10"></div>
            <div class="row bestmedia-save-cancel">
                <div class="col-md-10">
                    <a href="<?php echo base_url($this->side.'/'.$this->controller.'/groups') ?>" class="btn-link btn-primary">Отмена</a>
                    <input type="submit" name="do" value="Сохранить" class="btn btn-primary"/>
                </div>
            </div>
        </div>
        </form>
    </div>

</div>