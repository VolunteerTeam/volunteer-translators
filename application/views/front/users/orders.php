<div class="container main" role="main">
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
                <div class="row">
                    <?php echo $this->session->flashdata('verify_msg'); ?>
                </div>
                <div class="row user_form">
                    <?php
                        echo $this->session->flashdata('msg');
                        $attributes = array("name" => "create_order", "enctype" => "multipart/form-data");
                        echo form_open("user/orders/create", $attributes);
                    ?>
                    <div>
                        <label class="control-label" style="font-weight:bold;">Назначение перевода <span class="required">*</span></label>
                        <textarea type="textarea" rows="5" name="purpose" value="<?php echo @$_POST['purpose']; ?>" class="form-control <?php if(!empty(form_error('purpose'))){echo "error";} ?>"></textarea>
                        <span class="text-danger"><?php echo form_error('purpose'); ?></span>
                    </div>
                    <div>
                        <label class="control-label" style="font-weight:bold;">Получатель перевода (организация, контакты) <span class="required">*</span></label>
                        <textarea type="textarea" rows="3" name="receiver" value="<?php echo @$_POST['receiver']; ?>" class="form-control <?php if(!empty(form_error('receiver'))){echo "error";} ?>"></textarea>
                        <span class="text-danger"><?php echo form_error('receiver'); ?></span>
                    </div>
                    <div>
                        <label class="control-label" style="font-weight:bold;">Выберите файлы для перевода <span class="required">*</span></label>
                        <div class="input-group">
                            <label class="input-group-btn">
                                <span class="btn btn-default">
                                    Обзор&hellip; <input type="file" name="files[]" style="display: none;" multiple>
                                </span>
                            </label>
                            <input type="text" class="form-control" readonly>
                        </div>
                        <span class="help-block">
                            Файлы должны быть формата ----- не более 5МБ.
                        </span>
                        <span class="text-danger"><?php echo form_error('files'); ?></span>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: bold; display: inline-block;margin-bottom: 5px;font-size: 15px;" for="index_file">Выберите одно фото для отбражения в списке переводов</label>
                        <div id="image-cropper">
                            <div class="cropit-image-preview"></div>
                            <br>
                            <input type="range" class="cropit-image-zoom-input" />
                            <br>
                            <!-- The actual file input will be hidden -->
                            <div class="input-group">
                                <label class="input-group-btn">
                                <span class="btn btn-default">
                                    Обзор&hellip; <input type="file" name="photo_origin" style="display: none;" class="cropit-image-input"/>
                                </span>
                                </label>
                                <input type="text" class="form-control" readonly>
                            </div>
                            <input type="hidden" name="photo" id="photo">
                        </div>
                        <span class="help-block">
                            Фото должно быть формата .jpg или .png не более 5МБ
                        </span>
                    </div>
                    <?php echo form_close(); ?>
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
    $(document).ready(function () {
        var ordersTable = $('#ordersTable');

        ordersTable.jtable({
            title: 'Заказы',
            paging: true, //Enable paging
//            pageSize: 25, //Set page size (default: 10)
            sorting: true, //Enable sorting
            defaultSorting: 'first_name ASC', //Set default sorting
            actions: {
                listAction: '/user/orders/list',
                createAction: '/user/orders/create',
                updateAction: '/GettingStarted/UpdatePerson',
                deleteAction: '/GettingStarted/DeletePerson'
            },
            fields: {
                id: {
                    key: true,
                    create: false,
                    edit: false,
                    list: false
                },
                first_name: {
                    title: 'Имя',
                    width: '40%'
                },
//                Age: {
//                    title: 'Age',
//                    width: '20%'
//                },
//                RecordDate: {
//                    title: 'Record date',
//                    width: '30%',
//                    type: 'date',
//                    create: false,
//                    edit: false
//                }
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

        $("#submitCreateOrder").click(function(){
            $("form[name='create_order']").submit();
        })
    });

    $(document).on('change', ':file', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });
</script>