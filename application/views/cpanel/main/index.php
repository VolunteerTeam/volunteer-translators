<div id="wrapper">

    <?php $this->load->view('cpanel/sidebar'); ?>

    <!-- Page content -->
    <div id="page-content-wrapper">
        <div class="content-header">
            <div class="col-md-10 col-sm-10 col-xs-10">
                <a id="menu-toggle" href="#" class="btn btn-primary"><i class="glyphicon glyphicon-fire"></i></a>
                <ol class="breadcrumb">
                    <li class="active">Главная</li>
                </ol>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-2">
                <a href="<?php echo base_url('login/logout'); ?>" class="log-out pull-right">Выйти</a>
            </div>
        </div>

        <?php echo form_open_multipart('',array('id'=>'news_add'));?>
        <div class="page-content inset">
            <?php echo $message; ?>

            <div class="bestmedia-h15"></div>
            <div class="row bestmedia-tabs">
                <div class="col-md-12">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#main" data-toggle="tab">Общие настройки</a></li>


                    </ul>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane active" id="main">
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Общий телефон</label>
                            <?php echo form_input($phone); ?>
                        </div>
                    </div>
                    <div class="row bestmedia-input">
                        <div class="col-md-10">
                            <label class="control-label">Общий email</label>
                            <?php echo form_input($email); ?>
                        </div>
                    </div>

                </div>

            </div>
            <div class="row bestmedia-save-cancel">

                <div class="col-md-7 col-md-offset-3">
                    <input type="submit" name="do" value="Сохранить" class="btn btn-primary pull-right"/>

                </div>
            </div>
        </div>
        </form>

    </div>

</div>