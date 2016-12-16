<?php
    $user_id = $this->ion_auth->get_user_id();
?>

<div class="container main" role="main">
    <?php
        echo $this->session->flashdata('msg');
    ?>
    <div id="error_block"></div>
    <?php //require_once APPPATH."views/front/users/order_filter.php"; ?>
    <div id="translationsTable"></div>
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
                    <div id="loading"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="submitChangeStatus" class="btn btn-success">Да</button>
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

    $(document).ready(function () {
        var translationsTable = $('#translationsTable');

        translationsTable.jtable({
            title: 'Переводы',
            paging: true, //Enable paging
//            pageSize: 5, //Set page size (default: 10)
            sorting: true, //Enable sorting
            defaultSorting: 'o.created_on DESC', //Set default sorting
            actions: {
                listAction: '/user/translations/list?id=' + <?= $user_id ?>,
//                createAction: '/user/orders/create',
//                updateAction: '/GettingStarted/UpdatePerson',
//                deleteAction: '/GettingStarted/DeletePerson',
            },
            fields: {
                created_on: {
                    title: 'Дата создания',
                    width: '11%',
                    display: function (data) {
                        var localTime = moment.utc(data.record.created_on).toDate();
                        localTime = moment(localTime).format('DD.MM.YYYY HH:mm:ss');
                        return localTime;
                    }
                },
                id: {
                    key: true,
                    list: false
                },
                order_id: {
                    title: 'Заказ',
                    width: '8%',
                    display: function (data) {
                        return "<a href='/user/orders/" + data.record.order_id + "' target='_blank'>" + data.record.order_id + "</a>";
                    }
                },
                name_in: {
                    title: 'Файл оригинала',
                    width: '19%',
                    display: function (data) {
                        return '<a href="/' + data.record.file_in + '" download="' + data.record.name_in + '">' + data.record.name_in + ' <i class="fa fa-download"></i></a>';
                    }
                },
                name_out: {
                    title: 'Файл перевода',
                    width: '19',
                    display: function (data) {
                        if(data.record.file_out) return '<span id="translation_' + data.record.id + '"><a href="/' + data.record.file_out + '" download="' + data.record.name_out + '">' + data.record.name_out + ' <i class="fa fa-download"></i></a></span>';
                        return '<span id="translation_' + data.record.id + '"></span>';
                    }
                },
                edit: {
                    title: '',
                    width: '1%',
                    sorting: false,
                    display: function (data) {
                        if(!data.record.order_date_out){
                            if(!data.record.date_in) return "<div id='translation_edit_" + data.record.id + "'><i class='fa fa-edit non-active' title='Возьмите заказ в работу, чтобы иметь возможность редактировать'></i></div>";
                            return "<a href='#' class='fileEditButton' data-toggle='modal' data-target='#editFileOut' type='button' data-translation='" + data.record.id + "'><i class='fa fa-edit'></i></a>";
                        }
                        return "<i class='fa fa-edit non-active' title='Заказ уже закрыт, поэтому Вы не можете редактировать файл.'></i>";
                    }
                },
                language_in: {
                    title: 'Язык оригинала',
                    width: '16%'
                },
                language_out: {
                    title: 'Язык перевода',
                    width: '15%'
                },
                manager_name: {
                    title: 'Менеджер',
                    width: '10%',
                    display: function (data) {
                        if(data.record.manager_user_id) return "<a href='/user/profile/" + data.record.manager_user_id + "' target='_blank'>" + data.record.last_name + " " + data.record.first_name + "</a>";
                        return "";
                    }
                },
                status: {
                    title: '',
                    width: '1%',
                    sorting: false,
                    display: function (data) {
                        if(!data.record.date_in) return "<div id='translation_status_" + data.record.id + "'><a href='#' class='statusEditButton' data-toggle='modal' data-target='#changeStatus' type='button' data-translation='" + data.record.id + "'><div class='status new' style='margin: 0 auto;cursor:pointer;' title='Новый'></div></a></div>";
                        if(!data.record.date_out) return "<div id='translation_status_" + data.record.id + "'><div class='status in_process' title='В работе'></div></div>";
                        return "<div id='translation_status_" + data.record.id + "'><div class='status done' title='Выполнен'></div></div>";
                    }
                }
            }
        });

        translationsTable.jtable('load');


        $(':file').on('fileselect', function(event, numFiles, label) {

            var input = $(this).parents('.input-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' файла выбрано' : label;

            if( input.length ) {
                input.val(log);
            } else {
//                if( log ) alert(log);
            }

        });

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
                    if(data["error"]) {
                        $("#error_block").html(data["error"]);
                        translationsTable.jtable('deleteRecord', {
                            key: data['translation_id'],
                            clientOnly:true
                        });
                    } else {
                        $('#translation_status_'+data['translation_id']).html("<div class='status in_process' style='margin: 0 auto;' title='В работе'></div>");
                        $('#translation_edit_'+data['translation_id']).html("<a href='#' class='fileEditButton' data-toggle='modal' data-target='#editFileOut' type='button' data-translation='" + data['translation_id'] + "'><i class='fa fa-edit'></i></a>");
                    }
                },
                error: function(e) {
                    console.log(e);
                }
            });
        });

        $('#editFileOut').on('show.bs.modal', function(e) {
            $("form[name='edit_fileout']  input[name='translation_id']").val($(e.relatedTarget).attr("data-translation"));
        });

        $("#submitEditFileOut").click(function(e){
            var form_data = new FormData($("form[name='edit_fileout']")[0]);
            var file_data = $('#uploadFile').prop('files');
            form_data.append('file_out', file_data);
            var translation_id = $("input[name='translation_id']").val();
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
                        $('#translation_'+translation_id).html('<a href="/' + data["file_out"] + '" download="' + data["name_out"] + '">' + data["name_out"] + ' <i class="fa fa-download"></i></a>');
                        $('#translation_status_'+translation_id).html("<div class='status done' title='Выполнен'></div>");
                    },
                    error: function() {
                        console.log("error");
                    }
                });
            }
        });

        $(document).ajaxStart(function(){
            $("#loading").css("display", "block");
            $("#submitChangeStatus").attr("disabled",true);
        });
        $(document).ajaxComplete(function(){
            $("#loading").css("display", "none");
            $("#submitChangeStatus").attr("disabled",false);
        });
    });

    $(document).on('change', ':file', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });
</script>