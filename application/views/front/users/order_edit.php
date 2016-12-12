<script type="text/javascript">
    function getLocalDateTime(datetime){
        var localTime = moment.utc(datetime).toDate();
        localTime = moment(localTime).format('DD.MM.YYYY HH:mm:ss');
        document.write(localTime);
    }
</script>

<div class="container main" id="order_container" role="main">
    <?php
        echo $this->session->flashdata('msg');
    ?>
    <?php if(isset($order)) {
        $user_id = $this->ion_auth->get_user_id();
        $user_groups = $this->users_model->getUserGroupsId($user_id);
        // перебираем группы пользователя от самой главной, и инклюдим соответствующий шаблон
        if(in_array("1", $user_groups)) {
            if($order->date_in) {
                require_once APPPATH."views/front/users/order_edit_admin.php";
            } else {
                require_once APPPATH."views/front/users/order_edit_admin_new.php";
            }
        } else if (in_array("3", $user_groups)) {
            if($order->date_in) {
                require_once APPPATH."views/front/users/order_edit_manager.php";
            } else {
                require_once APPPATH."views/front/users/order_edit_manager_new.php";
            }
        } else if ($user_id == $order->client_user_id) {
            require_once APPPATH."views/front/users/order_edit_client.php";
        }

    } ?>
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

<!-- Modal -->
<div class="modal fade" id="removeFileIn" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Удалить Файл</h4>
            </div>
            <div class="modal-body">
                <div class="row user_form">
                    <?php
                    $attributes = array("name" => "delete_translation");
                    echo form_open("user/translation/delete", $attributes);
                    ?>
                    <input type="number" name="translation_id" class="form-control" style="display:none;">
                    <p class="notice">Вы подтверждаете, что хотите удалить этот файл для перевода?</p>
                    <?php echo form_close(); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="submitRemoveFileIn" class="btn btn-success">Удалить</button>
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

        $('#removeFileIn').on('show.bs.modal', function(e) {
            $("form[name='delete_translation']  input[name='translation_id']").val($(e.relatedTarget).attr("data-translation"));
        });

        $("#submitRemoveFileIn").click(function(e){
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "/user/translation/delete/"+$("form[name='delete_translation']  input[name='translation_id']").val(),
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                success: function(data){
                    $('#removeFileIn').modal('hide');
                    $('#translation_'+data['translation_id']).remove();
                },
                error: function() {
                    console.log("error");
                }
            });
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
            $("form[name='change_order_status']").submit();

//            e.preventDefault();
//            var form_data = new FormData($("form[name='change_order_status']")[0]);
//            $.ajax({
//                type: "POST",
//                url: "/user/orders/translation/change_order_status",
//                data: form_data,
//                dataType: "json",
//                cache: false,
//                contentType: false,
//                processData: false,
//                success: function(data){
//                    var status = "";
//                    var order_id = "";
//                    if(data["error"]){
//                        $('#changeOrderStatus').modal('hide');
//                        $("#order_container").html("<div id='error_block'>" + data["error"] + "</div>");
//                    } else {
//                        status = $("form[name='change_order_status']  input[name='order_status']").val();
//                        order_id = $("form[name='change_order_status']  input[name='order_id']").val();
//                        $('#changeOrderStatus').modal('hide');
//
//                        switch(status){
//                            case "done": status = "<div class='status order done' style='margin: 0 auto;' title='Выполнен'></div> выполнен"; break;
//                            case "in_process":
//                                status = "<a href='#' class='orderStatusEditButton' data-toggle='modal' data-target='#changeOrderStatus' type='button' data-newstatus='done' data-translation='"+order_id+"'><div class='status order in_process' style='margin: 0 auto;cursor:pointer;' title='В работе'></div> в работе</a>";
//                                break;
//                        }
//                        $('#order_status').html(status);
//                        if(data["manager"]) {
//                            $("#manager").html(data["manager"]);
//                        }
//                    }
//                },
//                error: function() {
//                    console.log("error");
//                }
//            });
        });

    });

    $(document).on('change', ':file', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });
</script>