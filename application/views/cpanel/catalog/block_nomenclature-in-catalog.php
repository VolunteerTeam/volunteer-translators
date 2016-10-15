<div class="row bestmedia-input">
    <div class="col-md-6 col-sm-8 col-xs-12  col-lg-4">
        <label class="control-label">Выберите позицию из номенклатуры:</label>
        <?php echo form_dropdown('nomenclature_id', $nomenclature['options'], $nomenclature['default'], $nomenclature['additional']); ?>
    </div>
</div>

