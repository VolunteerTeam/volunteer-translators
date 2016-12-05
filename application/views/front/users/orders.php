<div class="container main" role="main">
    <?php //require_once APPPATH."views/front/users/order_filter.php"; ?>
    <div id="ordersTable"></div>
</div>

<a href="#" id="createOrderButton" data-toggle="modal" data-target="#createOrderDialog" type="button" style="display:none;"></a>
<!-- Modal -->
<div class="modal fade" id="createOrderDialog" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Создать заказ</h4>
            </div>
            <div class="modal-body">
                <div class="row user_form">
                    <?php
                        $attributes = array("name" => "create_order", "enctype" => "multipart/form-data");
                        echo form_open("user/orders/create", $attributes);
                    ?>
                    <div class="form-item">
                        <label class="control-label">Назначение перевода <span class="required">*</span></label>
                        <span class="text-danger" data-error="purpose"></span>
                        <textarea type="textarea" rows="5" name="purpose" value="" class="form-control"></textarea>
                    </div>
                    <div class="form-item">
                        <label class="control-label" style="font-weight:bold;">Получатель перевода (организация, контакты) <span class="required">*</span></label>
                        <span class="text-danger" data-error="receiver"></span>
                        <textarea type="textarea" rows="3" name="receiver" value="" class="form-control"></textarea>
                    </div>
                    <div class="form-item">
                        <label class="control-label">Перевести <span class="required">*</span></label>
                        <span class="text-danger" data-error="languages"></span>
                        <div class="form-inline">
                            <div class="input-group">
                                <span class="input-group-addon">с</span>
                                <select name="language_in" class="form-control" style="width:246px;">
                                    <option value="">Выберите язык...</option>
                                    <?php if(isset($languages) && !empty($languages)){
                                        foreach($languages as $value){
                                            echo "<option value='".$value->code."'>".$value->name_ru."</option>";
                                        }
                                    }?>
                                </select>
                                <span class="input-group-addon">на</span>
                                <select name="language_out" class="form-control" style="width:247px;">
                                    <option value="">Выберите язык...</option>
                                    <?php if(isset($languages) && !empty($languages)){
                                        foreach($languages as $value){
                                            echo "<option value='".$value->code."'>".$value->name_ru."</option>";
                                        }
                                    }?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-item">
                        <label class="control-label">Выберите файлы для перевода <span class="required">*</span></label>
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
                    <div class="form-group form-item">
                        <label style="display: inline-block;margin-bottom: 5px;font-size: 15px;" for="index_file">Выберите одно фото для отбражения в списке переводов</label>
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
                    <div>
                        <span class="text-danger" data-error="total"></span>
                    </div>
                    <?php echo form_close(); ?>
                    <div id="loading"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="submitCreateOrder" class="btn btn-success">Создать</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    var accepted_format = {
        "files[]": ["doc", "docx", "xls", "xlsx", "pdf", "rtf", "jpg", "jpeg", "png"],
        "photo_origin": ["png", "jpg", "jpeg"]
    };

    $(document).ready(function () {
        var ordersTable = $('#ordersTable');

        ordersTable.jtable({
            title: 'Заказы',
            paging: true, //Enable paging
//            pageSize: 5, //Set page size (default: 10)
            sorting: true, //Enable sorting
            defaultSorting: 'o.created_on DESC', //Set default sorting
            actions: {
                listAction: '/user/orders/list',
                createAction: '/user/orders/create',
                updateAction: '/GettingStarted/UpdatePerson',
                deleteAction: '/GettingStarted/DeletePerson'
            },
            fields: {
                created_on: {
                    title: 'Дата создания',
                    width: '18%',
                    display: function (data) {
                        var localTime = moment.utc(data.record.created_on).toDate();
                        localTime = moment(localTime).format('DD.MM.YYYY HH:mm:ss');
                        return localTime;
                    }
                },
                order_id: {
                    key: true,
                    title: 'Номер',
                    width: '8%',
                    display: function (data) {
                        return "<a href='/orders?id=" + data.record.order_id + "' target='_blank'>" + data.record.order_id + "</a>";
                    }
                },
                user_name: {
                    title: 'Заказчик',
                    width: '20%',
                    display: function (data) {
                        return "<a href='/user/profile/" + data.record.client_user_id + "' target='_blank'>" + data.record.last_name + " " + data.record.first_name + "</a>";
                    }
                },
                city: {
                    title: 'Город',
                    width: '20%'
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
                    width: '20%',
                    display: function (data) {
                        if(data.record.manager_last_name) return "<a href='/users/profile?id=" + data.record.client_user_id + "' target='_blank'>" + data.record.manager_last_name + " " + data.record.manager_first_name + "</a>";
                        return "";
                    }
                },
                status: {
                    title: '',
                    width: '1%',
                    sorting: false,
                    display: function (data) {
                        if(!data.record.date_in) return "<div class='status new' title='Новый'></div>";
                        if(!data.record.date_out) return "<div class='status in_process' title='В работе'></div>";
                        return "<div class='status done' title='Выполнен'></div>";
                    }
                },
            }
        });

        ordersTable.jtable('load');


        $(':file').on('fileselect', function(event, numFiles, label) {

            var input = $(this).parents('.input-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' файла выбрано' : label;

            if( input.length ) {
                input.val(log);
            } else {
//                if( log ) alert(log);
            }

        });

        var h = $('#image-cropper').cropit();
        $('.select-image-btn').click(function() {
            $('.cropit-image-input').click();
        });

        function based64() {
            var imageData = h.cropit('export');
            $('#photo').val(imageData);
        }

        function file_extension(filename){
            var ext = (/[.]/.exec(filename)) ? /[^.]+$/.exec(filename) : null;
            if(ext) return ext[0];
            return ext;
        }

        function checked_files(field){
            var files = $("input[name='" + field + "']").get(0).files;
            if(field == "files[]" && files.length <= 0) {
                $("span[data-error='" + field + "']").html('<p>Нужно добавить хотя бы один файл для перевода.</p>');
                return false;
            }
            for (i = 0; i < files.length; i++) {
                if (files[i].size > 5*1024*1024) { // 5МБ
                    $("span[data-error='" + field + "']").html('<p>Размер файла "' + files[i].name + '" превышает размер 5МБ.</p>');
                    return false;
                }
                if($.inArray(file_extension(files[i].name), accepted_format[field]) == -1) {
                    $("span[data-error='" + field + "']").html('<p>Неверный формат файла "' + files[i].name + '".</p>');
                    return false;
                }
            }
            $("span[data-error='" + field + "']").html('');
            return true;
        }

        function textarea_empty(field) {
            if(!$("textarea[name='" + field + "']").val()) {
                $("span[data-error='" + field + "']").html('<p>Поле обязательно для заполнения</p>');
                return true;
            }
            $("span[data-error='" + field + "']").html('');
            return false;
        }

        function check_languages(){
            if(!$("select[name='language_in']").val() || !$("select[name='language_out']").val()) {
                $("span[data-error='languages']").html('<p>Выберите языки для перевода.</p>');
                return false;
            }
            $("span[data-error='languages']").html('');
            return true;
        }

        function validate_form(){
            var checked_files1 = checked_files('files[]');
            var checked_files2 = checked_files('photo_origin');
            var textarea_empty1 = textarea_empty("purpose");
            var textarea_empty2 = textarea_empty("receiver");
            var check_languages1 = check_languages();
            return checked_files1 && checked_files2 && !textarea_empty1 && !textarea_empty2 && check_languages1;
        }

        $(document).ajaxStart(function(){
            $("#loading").css("display", "block");
            $("#submitCreateOrder").attr("disabled",true);
        });
        $(document).ajaxComplete(function(){
            $("#loading").css("display", "none");
            $("#submitCreateOrder").attr("disabled",false);
        });

        $("#submitCreateOrder").click(function(e){
            based64();

            var form_data = new FormData($("form[name='create_order']")[0]);
            var file_data = $('input[name="photo_origin"]').prop('files');
            form_data.append('file', file_data);
            file_data = $('input[name="files"]').prop('files');
            form_data.append('file', file_data);
//            console.log(form_data);
//            form.submit();
            e.preventDefault();
            if(validate_form()){
                $.ajax({
                    type: "POST",
                    url: "/user/orders/create",
                    data: form_data,
                    dataType: "json",
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data){
                        if(data["errors"] && data["errors"].length > 0){
                            if(data["errors"]["create"]){
                                $("span[data-error='total']").html('<p>'+ data["errors"]["create"] +'</p>');
                            } else {
                                $.each(data["errors"], function(key, value) {
                                    $("span[data-error='"+key+"']").html(value);
                                });
                            }
                        } else {
                            $('#createOrderDialog').modal('hide');
                            ordersTable.jtable('addRecord', {
                                record: data["order"],
                                clientOnly: true,
                                animationsEnabled: true
                            });
                        }
                    },
                    error: function() {
                        console.log("error");
                    }
                });
            }
        });

    });

    $(document).on('change', ':file', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });
</script>