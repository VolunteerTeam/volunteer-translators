<div id="wrapper">
    <?php $this->load->view('cpanel/sidebar'); ?>
    <!-- Page content -->
    <div id="page-content-wrapper">

        <?php $this->load->view('cpanel/breadcrumbs'); ?>
        <?php echo form_open_multipart('',array('id'=>'news_add'));?>
        <div class="page-content inset">
            <?php echo $message; ?>
        </div>
        </form>
    </div>
</div>