<div class="profile_info" style="padding-top:10px;">
    <span style="font-weight:bold;font-size:18px;"><a href="/user/orders/<?= $order->id ?>">Заказ №<?= $order->id ?></a></span><br/>
    от
    <script type="text/javascript">
        getLocalDateTime("<?= $order->created_on ?>");
    </script><br/>
    заказчик: <a href="/user/profile/<?= $order->client_user_id ?>"><?= $this->users_model->getUserName($order->client_user_id) ?></a><br/>
    менеджер: <div style="display:inline;" id="manager"><?php if($order->manager_user_id) echo '<a href="/user/profile/'.$order->manager_user_id.'">'.$this->users_model->getUserName($order->manager_user_id).'</a>'; ?></div><br/>
    статус: <div id="order_status" style="display:inline-block;"><?php
        if($order->date_out) {
            echo "<div class='status order done' style='margin: 0 auto;' title='Выполнен'></div> выполнен";
        } else if($order->date_in) {
            echo "<a href='#' class='orderStatusEditButton' data-toggle='modal' data-target='#changeOrderStatus' type='button' data-newstatus='done' data-translation='".$order->id."'><div class='status order in_process' style='margin: 0 auto;cursor:pointer;' title='В работе'></div> в работе</a> <span style='font-style:italic;font-weight:normal;font-size:12px;color:#ccc;'>(нажмите, чтобы изменить)</span>";
        } else {
            echo "<a href='#' class='orderStatusEditButton' data-toggle='modal' data-target='#changeOrderStatus' type='button' data-newstatus='in_process' data-translation='".$order->id."'><div class='status order new' style='margin: 0 auto;cursor:pointer;' title='Новый'></div> новый</a>";
        }
        ?></div>
</div>
<div id="error_block"></div>
<div class="row user_form order_edit">
    <?php
    $attributes = array("name" => "edit_order", "enctype" => "multipart/form-data");
    echo form_open("user/orders/update", $attributes);
    ?>
    <input type="number" name="order_id" class="form-control" style="display:none;" value="<?= $order->id ?>">
    <input type="number" name="client_user_id" class="form-control" style="display:none;" value="<?= $order->client_user_id ?>">
    <div class="form-item">
        <label class="control-label">Перевести <span class="required">*</span></label>
        <div class="form-inline">
            <div class="input-group">
                <span class="input-group-addon">с</span>
                <select name="language_in" class="form-control" style="width:246px;">
                    <option value="">Выберите язык...</option>
                    <?php if(isset($languages) && !empty($languages)){
                        foreach($languages as $value){
                            echo "<option value='".$value->code."' ".($value->code == $order->language_in ? "selected" : "").">".$value->name_ru."</option>";
                        }
                    }?>
                </select>
                <span class="input-group-addon">на</span>
                <select name="language_out" class="form-control" style="width:247px;">
                    <option value="">Выберите язык...</option>
                    <?php if(isset($languages) && !empty($languages)){
                        foreach($languages as $value){
                            echo "<option value='".$value->code."' ".($value->code == $order->language_out ? "selected" : "").">".$value->name_ru."</option>";
                        }
                    }?>
                </select>
            </div>
        </div>
    </div>
    <div class="form-item">
        <b>Файлы: </b><br/>
        <table class="table">
            <thead>
            <th>№</th>
            <th>Файл оригинала</th>
            <th>Кол-во знаков</th>
            <th>Файл перевода</th>
            <th>Кол-во знаков</th>
            <th>Переводчик</th>
            <th>Статус</th>
            <th></th>
            </thead>
            <tbody>
            <?php
            $files = $this->orders_model->getFiles($order->id);
            for($i = 0; $i < count($files); $i++){
                ?>
                <tr id="translation_<?= $files[$i]['id'] ?>">
                    <td><?= $i+1 ?></td>
                    <td>
                        <?php $ext = pathinfo($files[$i]["file_in"])["extension"]; ?>
                        <div class="input-group">
                            <input name="translations[<?= $files[$i]['id'] ?>][name_in]" type="text" class="form-control" style="width:100px;" value="<?= explode(".".$ext,$files[$i]["name_in"])[0] ?>">
                            <span class="input-group-addon">.<?= $ext ?></span>
                            <input name="translations[<?= $files[$i]['id'] ?>][name_in_ext]" type="text" style="display:none" value="<?= $ext ?>">
                        </div>
                        &nbsp;<a href="/<?= $files[$i]["file_in"] ?>" download="<?= $files[$i]["name_in"] ?>"><i class="fa fa-download"></i></a>
                    </td>
                    <td><input name="translations[<?= $files[$i]['id'] ?>][volume_in]" type="number" value="<?= $files[$i]["volume_in"] ?>" style="width:80px;"></td>
                    <td>
                        <?php if($files[$i]["file_out"]){
                            $ext = pathinfo($files[$i]["file_out"])["extension"];
                            ?>
                            <div class="input-group">
                                <input name="translations[<?= $files[$i]['id'] ?>][name_out]" type="text" class="form-control name_out_name" style="width:100px;" value="<?php if($files[$i]["name_out"]) echo explode(".".$ext,$files[$i]["name_out"])[0]; ?>">
                                <span class="input-group-addon name_out_ext"><?php echo $ext ? ".".$ext : ""; ?></span>
                                <input name="translations[<?= $files[$i]['id'] ?>][name_out_ext]" type="text" style="display:none" value="<?php echo $ext ? $ext : ""; ?>">
                            </div>
                            &nbsp;<a class="file_out_href" href="/<?php if($files[$i]["name_out"]) echo $files[$i]["name_out"]; ?>" download="<?php if($files[$i]["name_out"]) echo $files[$i]["name_out"]; ?>" style="<?php echo $files[$i]["name_out"] ? "" : "display:none" ?>"><i class="fa fa-download"></i></a>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if($files[$i]["file_out"]){?>
                        <input name="translations[<?= $files[$i]['id'] ?>][volume_out]" type="number" value="<?= $files[$i]["volume_out"] ?>" style="width:80px;"></td>
                    <?php } ?>
                    <td>
                        <?php if($files[$i]["date_in"]) {
                            echo '<input type="number" name="translations['.$files[$i]['id'].'][translator_user_id]" value="'.$files[$i]["translator_user_id"].'" style="display:none;"><a href="/user/profile/'.$files[$i]["translator_user_id"].'" target="_blank">'.$this->users_model->getUserName($files[$i]["translator_user_id"]).'</a>';
                        } else {
                            echo '<select name="translations['.$files[$i]['id'].'][translator_user_id]" class="form-control" style="width:210px;">
                                  <option value="">Выберите переводчика...</option>';
                            if(isset($translators) && !empty($translators)){
                                foreach($translators as $value){
                                    echo "<option value='".$value->id."' ".($value->id == $files[$i]["translator_user_id"] ? "selected" : "").">".$value->last_name." ".$value->first_name." ".$value->patro_name." (".$value->email.")"."</option>";
                                }
                            }
                            echo '</select>';
                        }?>
                    </td>
                    <td class="td_status" style="vertical-align: middle;"><?php
                        if($files[$i]["date_out"]) {
                            echo "<div class='status done' style='margin: 0 auto;' title='Выполнен'></div>";
                        } else if($files[$i]["date_in"]) {
                            echo "<div class='status in_process' style='margin: 0 auto;' title='В работе'></div>";
                        } else {
                            echo "<div class='status new' style='margin: 0 auto;' title='Новый'></div>";
                        }
                        ?>
                    </td>
                    <td style="vertical-align: middle;">
                        <?php if($files[$i]["date_in"] || $files[$i]["date_out"]){ ?>
                            <i class='fa fa-remove non-active'></i>
                        <?php } else { ?>
                            <a href='#' class="fileRemoveButton" data-toggle="modal" data-target="#removeFileIn" type="button" data-translation="<?= $files[$i]["id"] ?>" title="Удалить"><i class='fa fa-remove'></i></a>
                        <?php } ?>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
    <div class="form-item">
        <label class="control-label">Добавить файлы оригиналов</label>
        <span class="text-danger" data-error="files[]"></span>
        <div class="input-group">
            <label class="input-group-btn">
                                <span class="btn btn-default">
                                    Обзор&hellip; <input type="file" name="files[]" id="uploadFiles" style="display: none;" multiple>
                                </span>
            </label>
            <input type="text" class="form-control" readonly>
        </div>
                        <span class="help-block">
                            Файлы должны быть формата .doc, .docx, .xls, .xlsx, .pdf, .rtf, .jpg, .jpeg, .png не более 5МБ.
                        </span>
    </div>
    <div class="form-item">
        <label class="control-label">Назначение перевода <span class="required">*</span></label>
        <textarea type="textarea" rows="5" name="purpose" class="form-control"><?= $order->purpose ?></textarea>
    </div>
    <div class="form-item">
        <label class="control-label" style="font-weight:bold;">Получатель перевода (организация, контакты) <span class="required">*</span></label>
        <textarea type="textarea" rows="3" name="receiver" class="form-control"><?= $order->receiver ?></textarea>
    </div>
    <hr>
    <div class="form-group form-item">
        <div class="col-sm-12 col-md-6">
            <label style="display: inline-block;margin-bottom: 5px;font-size: 15px;" for="index_file">Текущее изображение</label>
            <div class="cropit-image-preview">
                <img alt="" src="<?= $order->photo_thumb ?>">
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <label style="display: inline-block;margin-bottom: 5px;font-size: 15px;" for="index_file">Изменить изображение в списке переводов</label>
            <span class="text-danger" data-error="photo_origin"></span>
            <div id="image-cropper">
                <div class="cropit-image-preview"></div>
                <br>
                <input type="range" class="cropit-image-zoom-input" />
                <br>
                <div class="input-group">
                    <label class="input-group-btn">
                                    <span class="btn btn-default">
                                        Обзор&hellip; <input type="file" name="photo_origin" style="display: none;" class="cropit-image-input"/>
                                        <input type="hidden" name="photo" id="photo">
                                    </span>
                    </label>
                    <input type="text" class="form-control" readonly>
                </div>
            </div>
                <span class="help-block">
                    Фото должно быть формата .jpg или .png не более 5МБ
                </span>
        </div>
    </div>
    <hr>
    <div>
        <span class="text-danger" data-error="total"></span>
    </div>
    <div class="col-sm-12 center-block">
        <script type="text/javascript">
            var h = $('#image-cropper').cropit();
            $('.select-image-btn').click(function() {
                $('.cropit-image-input').click();
            });
            function based64() {
                var imageData = h.cropit('export');
                $('#photo').val(imageData);
            }
        </script>
        <button type="submit" id="submitEditOrder" onclick="return based64();" class="btn btn-success center-block">Сохранить</button>
    </div>
    <?php echo form_close(); ?>

</div>
</div>