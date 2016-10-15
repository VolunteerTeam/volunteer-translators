<div class="row bestmedia-input">
    <div class="col-md-12">
        <label class="control-label">Заголовок</label>
             <?php echo form_input($title); ?>
    </div>
</div>
<div class="row bestmedia-input">
    <div class="col-md-12">
        <label class="control-label">Содержимое</label>
        <?php echo form_textarea($content);?>
    </div>
</div>
<div class="bestmedia-h20"></div>

<script type="text/javascript">
    $(function(){
        ckInit("ru_content","<?php echo base_url();?>");
    });
</script>