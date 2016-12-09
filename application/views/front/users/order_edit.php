<script type="text/javascript">
    function getLocalDateTime(datetime){
        var localTime = moment.utc(datetime).toDate();
        localTime = moment(localTime).format('DD.MM.YYYY HH:mm:ss');
        document.write(localTime);
    }
</script>

<div class="container main" id="order_container" role="main">
    <? if(isset($order)) {?>
        <div class="profile_info" style="padding-top:10px;padding-left:10px;">
            <span style="font-weight:bold;font-size:18px;">Заказ №<?= $order->id ?></span><br/>
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
                    echo "<a href='#' class='orderStatusEditButton' data-toggle='modal' data-target='#changeOrderStatus' type='button' data-newstatus='done' data-translation='".$order->id."'><div class='status order in_process' style='margin: 0 auto;cursor:pointer;' title='В работе'></div> в работе</a>";
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
                    <th></th>
                    <th>Кол-во знаков</th>
                    <th>Переводчик</th>
                    <th>Статус</th>
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
                                <?php $files[$i]["file_out"] ? $ext = pathinfo($files[$i]["file_out"])["extension"] : $ext = ""; ?>
                                <div class="input-group">
                                    <input name="translations[<?= $files[$i]['id'] ?>][name_out]" type="text" class="form-control name_out_name" style="width:100px;" value="<?php if($files[$i]["name_out"]) echo explode(".".$ext,$files[$i]["name_out"])[0]; ?>">
                                    <span class="input-group-addon name_out_ext"><?php echo $ext ? ".".$ext : ""; ?></span>
                                    <input name="translations[<?= $files[$i]['id'] ?>][name_out_ext]" type="text" style="display:none" value="<?php echo $ext ? $ext : ""; ?>">
                                </div>
                                &nbsp;<a class="file_out_href" href="/<?php if($files[$i]["name_out"]) echo $files[$i]["name_out"]; ?>" download="<?php if($files[$i]["name_out"]) echo $files[$i]["name_out"]; ?>" style="<?php echo $files[$i]["name_out"] ? "" : "display:none" ?>"><i class="fa fa-download"></i></a>
                            </td>
                            <td><a href='#' class="fileEditButton" data-toggle="modal" data-target="#editFileOut" type="button" data-translation="<?= $files[$i]["id"] ?>"><i class='fa fa-edit'></i></a></td>
                            <td><input name="translations[<?= $files[$i]['id'] ?>][volume_out]" type="number" value="<?= $files[$i]["volume_out"] ?>" style="width:80px;"></td>
                            <td>
                                <select name="translations[<?= $files[$i]['id'] ?>][translator_user_id]" class="form-control" style="width:210px;">
                                    <option value="">Выберите переводчика...</option>
                                    <?php if(isset($translators) && !empty($translators)){
                                        foreach($translators as $value){
                                            echo "<option value='".$value->id."' ".($value->id == $files[$i]["translator_user_id"] ? "selected" : "").">".$value->last_name." ".$value->first_name." ".$value->patro_name." (".$value->email.")"."</option>";
                                        }
                                    }?>
                                </select>
                            </td>
                            <td class="td_status" style="vertical-align: middle;"><?php
                                if($files[$i]["date_out"]) {
                                    echo "<div class='status done' style='margin: 0 auto;' title='Выполнен'></div>";
                                } else if($files[$i]["date_in"]) {
                                    echo "<div class='status in_process' style='margin: 0 auto;' title='В работе'></div>";
                                } else {
                                    echo "<a href='#' class='statusEditButton' data-toggle='modal' data-target='#changeStatus' type='button' data-translation='".$files[$i]["id"]."'><div class='status new' style='margin: 0 auto;cursor:pointer;' title='Новый'></div></a>";
                                }
                                ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
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
    <? } ?>
</div>

<!-- Modal -->
<div class="modal fade" id="editFileOut" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Добавить/изменить файл перевода</h4>
            </div>
            <div class="modal-body">
                <div class="row user_form">
                    <?php
                    $attributes = array("name" => "edit_fileout", "enctype" => "multipart/form-data");
                    echo form_open("user/orders/add/file_out", $attributes);
                    ?>
                    <input type="number" name="translation_id" class="form-control" style="display:none;">
                    <div class="form-item">
                        <label class="control-label">Выберите файл перевода <span class="required">*</span></label>
                        <span class="text-danger" data-error="file_out"></span>
                        <div class="input-group">
                            <label class="input-group-btn">
                                <span class="btn btn-default">
                                    Обзор&hellip; <input type="file" name="file_out" id="uploadFile" style="display: none;">
                                </span>
                            </label>
                            <input type="text" class="form-control" readonly>
                        </div>
                        <span class="help-block">
                            Файл должен быть формата .doc, .docx, .xls, .xlsx, .pdf, .rtf, .jpg, .jpeg, .png не более 5МБ.
                        </span>
                    </div>
                    <div>
                        <span class="text-danger" data-error="total"></span>
                    </div>
                    <?php echo form_close(); ?>
                    <div id="loading"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="submitEditFileOut" class="btn btn-success">Сохранить</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="changeStatus" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Изменить статус перевода</h4>
            </div>
            <div class="modal-body">
                <div class="row user_form">
                    <?php
                    $attributes = array("name" => "change_status");
                    echo form_open("user/orders/translation/change_status", $attributes);
                    ?>
                    <input type="number" name="translation_id" class="form-control" style="display:none;">
                    <p class="notice">Вы подтверждаете, что хотите взять этот файл для перевода в работу?</p>
                    <?php echo form_close(); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="submitChangeStatus" class="btn btn-success">Да</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="changeOrderStatus" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Изменить статус заказа</h4>
            </div>
            <div class="modal-body">
                <div class="row user_form">
                    <?php
                    $attributes = array("name" => "change_order_status");
                    echo form_open("user/orders/translation/change_order_status", $attributes);
                    ?>
                    <input type="number" name="order_id" value="<?= $order->id ?>" class="form-control" style="display:none;">
                    <input type="text" name="order_status" value="" class="form-control" style="display:none;">
                    <p class="notice"></p>
                    <?php echo form_close(); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="submitOrderChangeStatus" class="btn btn-success">Да</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var accepted_format = {
        "file_out": ["doc", "docx", "xls", "xlsx", "pdf", "rtf", "jpg", "jpeg", "png"],
        "photo_origin": ["png", "jpg", "jpeg"]
    };

    function file_extension(filename){
        var ext = (/[.]/.exec(filename)) ? /[^.]+$/.exec(filename) : null;
        if(ext) return ext[0];
        return ext;
    }

    function validate_form(){
        var files = $("input[name='file_out']").get(0).files;
        if(files.length <= 0) {
            $("span[data-error='file_out']").html('<p>Нужно добавить файл перевода.</p>');
            return false;
        }
        for (i = 0; i < files.length; i++) {
            if (files[i].size > 5*1024*1024) { // 5МБ
                $("span[data-error='file_out']").html('<p>Размер файла "' + files[i].name + '" превышает размер 5МБ.</p>');
                return false;
            }
            if($.inArray(file_extension(files[i].name), accepted_format["file_out"]) == -1) {
                $("span[data-error='file_out']").html('<p>Неверный формат файла "' + files[i].name + '".</p>');
                return false;
            }
        }
        $("span[data-error='file_out']").html('');
        return true;
    }

    $(document).ready(function () {

        $(':file').on('fileselect', function(event, numFiles, label) {

            var input = $(this).parents('.input-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' файла выбрано' : label;

            if( input.length ) {
                input.val(log);
            }

        });

        $('#editFileOut').on('show.bs.modal', function(e) {
            $("form[name='edit_fileout']  input[name='translation_id']").val($(e.relatedTarget).attr("data-translation"));
        });

        $("#submitEditFileOut").click(function(e){
            var form_data = new FormData($("form[name='edit_fileout']")[0]);
            var file_data = $('#uploadFile').prop('files');
            form_data.append('file_out', file_data);
//            console.log(file_data);
//            $("form[name='edit_fileout']").submit();
            e.preventDefault();
            if(validate_form()){
                $.ajax({
                    type: "POST",
                    url: "/user/orders/add/file_out",
                    data: form_data,
                    dataType: "json",
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data){
                        $('#editFileOut').modal('hide');
                        $('#translation_'+data['translation_id']+' input[name="name_out_ext"]').val(data["ext"]);
                        $('#translation_'+data['translation_id']+' .name_out_name').val(data["name_out"]);
                        $('#translation_'+data['translation_id']+' .name_out_ext').text("."+data["ext"]);
                        $('#translation_'+data['translation_id']+' .file_out_href').attr("href","/"+data["file_out"]).attr("download",data["name_out"]+data["ext"]).css("display","inline-block");
                        $('#translation_'+data['translation_id']+' .td_status').html("<div class='status done' style='margin: 0 auto;' title='Выполнен'></div>");
                    },
                    error: function() {
                        console.log("error");
                    }
                });
            }
        });

        $(document).ajaxStart(function(){
            $("#loading").css("display", "block");
            $("#submitEditFileOut").attr("disabled",true);
        });
        $(document).ajaxComplete(function(){
            $("#loading").css("display", "none");
            $("#submitEditFileOut").attr("disabled",false);
        });

        $('#changeStatus').on('show.bs.modal', function(e) {
            $("form[name='change_status'] input[name='translation_id']").val($(e.relatedTarget).attr("data-translation"));
        });

        $("#submitChangeStatus").click(function(e){
            var form_data = new FormData($("form[name='change_status']")[0]);
//            $("form[name='change_status']").submit();
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "/user/orders/translation/change_status",
                data: form_data,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                success: function(data){
                    $('#changeStatus').modal('hide');
                    $('#translation_'+data['translation_id']+' .td_status').html("<div class='status in_process' style='margin: 0 auto;' title='В работе'></div>");
                },
                error: function() {
                    console.log("error");
                }
            });
        });

        $('#changeOrderStatus').on('show.bs.modal', function(e) {
            var status = $(e.relatedTarget).attr("data-newstatus");
            $("form[name='change_order_status']  input[name='order_status']").val(status);

            var notice = "";
            switch(status){
                case "done": notice = "Вы подтверждаете, что хотите закрыть Заказ (статус Выполнено)?"; break;
                case "in_process": notice = "Вы подтверждаете, что хотите взять Заказ в работу?"; break;
            }
            $("form[name='change_order_status']  .notice").text(notice);
        });

        $("#submitOrderChangeStatus").click(function(e){
            var form_data = new FormData($("form[name='change_order_status']")[0]);
//            $("form[name='change_order_status']").submit();
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "/user/orders/translation/change_order_status",
                data: form_data,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                success: function(data){
                    var status = "";
                    var order_id = "";
                    if(data["error"]){
                        $('#changeOrderStatus').modal('hide');
                        $("#order_container").html("<div id='error_block'>" + data["error"] + "</div>");
                    } else {
                        status = $("form[name='change_order_status']  input[name='order_status']").val();
                        order_id = $("form[name='change_order_status']  input[name='order_id']").val();
                        $('#changeOrderStatus').modal('hide');

                        switch(status){
                            case "done": status = "<div class='status order done' style='margin: 0 auto;' title='Выполнен'></div> выполнен"; break;
                            case "in_process":
                                status = "<a href='#' class='orderStatusEditButton' data-toggle='modal' data-target='#changeOrderStatus' type='button' data-newstatus='done' data-translation='"+order_id+"'><div class='status order in_process' style='margin: 0 auto;cursor:pointer;' title='В работе'></div> в работе</a>";
                                break;
                        }
                        $('#order_status').html(status);
                        if(data["manager"]) {
                            $("#manager").html(data["manager"]);
                        }
                    }
                },
                error: function() {
                    console.log("error");
                }
            });
        });

    });

    $(document).on('change', ':file', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });
</script>