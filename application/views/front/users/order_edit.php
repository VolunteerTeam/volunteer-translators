<script type="text/javascript">
    function getLocalDateTime(datetime){
        var localTime = moment.utc(datetime).toDate();
        localTime = moment(localTime).format('DD.MM.YYYY HH:mm:ss');
        document.write(localTime);
    }
</script>

<div class="container main" role="main">
    <? if(isset($order)) {?>
        <div class="profile_info" style="padding-top:10px;padding-left:10px;">
            <span style="font-weight:bold;font-size:18px;">Заказ №<?= $order->id ?></span><br/>
            от
            <script type="text/javascript">
                getLocalDateTime("<?= $order->created_on ?>");
            </script>
        </div>
        <div class="row user_form order_edit">
            <?php
            $attributes = array("name" => "edit_order", "enctype" => "multipart/form-data");
            echo form_open("user/orders/edit", $attributes);
            ?>
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
                <label class="control-label">Менеджер</label>
                <select name="manager_user_id" class="form-control">
                    <option value="">Выберите менеджера...</option>
                    <?php if(isset($managers) && !empty($managers)){
                        foreach($managers as $value){
                            echo "<option value='".$value->id."' ".($value->id == $order->manager_user_id ? "selected" : "").">".$value->last_name." ".$value->first_name." ".$value->patro_name." (".$value->email.")"."</option>";
                        }
                    }?>
                </select>
            </div>
            <hr>
            <div class="form-item">
                <label class="control-label">Назначение перевода <span class="required">*</span></label>
                <span class="text-danger" data-error="purpose"></span>
                <textarea type="textarea" rows="5" name="purpose" class="form-control"><?= $order->purpose ?></textarea>
            </div>
            <div class="form-item">
                <label class="control-label" style="font-weight:bold;">Получатель перевода (организация, контакты) <span class="required">*</span></label>
                <span class="text-danger" data-error="receiver"></span>
                <textarea type="textarea" rows="3" name="receiver" class="form-control"><?= $order->receiver ?></textarea>
            </div>
            <hr>
            <div class="form-item">
                <span>Файлы: </span><br/>
                <table class="table">
                    <thead class="thead-inverse">
                    <th>№п/п</th>
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
                        <tr>
                            <td><?= $i+1 ?></td>
                            <td><a href="/<?= $files[$i]["file_in"] ?>"><?= $files[$i]["name_in"] ?> <i class="fa fa-download"></i></a></td>
                            <td><input type="number" value="<?= $files[$i]["volume_in"] ?>" style="width:80px;"></td>
                            <td><?php if($files[$i]["file_out"]) echo "<a href='/".$files[$i]["file_out"]."'>".$files[$i]['name_out']." <i class='fa fa-download'></i></a>" ?></td>
                            <td><a href='#'><i class='fa fa-edit'></i></a></td>
                            <td><input type="number" value="<?= $files[$i]["volume_out"] ?>" style="width:80px;"></td>
                            <td>
                                <select name="translator_user_id" class="form-control" style="width:210px;">
                                    <option value="">Выберите переводчика...</option>
                                    <?php if(isset($translators) && !empty($translators)){
                                        foreach($translators as $value){
                                            echo "<option value='".$value->id."' ".($value->id == $files[$i]["translator_user_id"] ? "selected" : "").">".$value->last_name." ".$value->first_name." ".$value->patro_name." (".$value->email.")"."</option>";
                                        }
                                    }?>
                                </select>
                            </td>
                            <td style="vertical-align: middle;"><?php
                                if($files[$i]["date_out"]) {
                                    echo "<div class='status done' style='margin: 0 auto;' title='Выполнен'></div>";
                                } else if($files[$i]["date_in"]) {
                                    echo "<div class='status in_process' style='margin: 0 auto;' title='В работе'></div>";
                                } else {
                                    echo "<div class='status new' style='margin: 0 auto;' title='Новый'></div>";
                                }
                                ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
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


<!--            <div class="form-item">-->
<!--                <label class="control-label">Добавить файлы для перевода</label>-->
<!--                <span class="text-danger" data-error="files[]"></span>-->
<!--                <div class="input-group">-->
<!--                    <label class="input-group-btn">-->
<!--                                    <span class="btn btn-default">-->
<!--                                        Обзор&hellip; <input type="file" name="files[]" id="uploadFiles" style="display: none;" multiple>-->
<!--                                    </span>-->
<!--                    </label>-->
<!--                    <input type="text" class="form-control" readonly>-->
<!--                </div>-->
<!--                            <span class="help-block">-->
<!--                                Файлы должны быть формата .doc, .docx, .xls, .xlsx, .pdf, .rtf, .jpg, .jpeg, .png не более 5МБ.-->
<!--                            </span>-->
<!--            </div>-->

            <hr>
            <div>
                <span class="text-danger" data-error="total"></span>
            </div>
            <div class="col-sm-12 center-block">
                <button type="submit" id="submitEditOrder" class="btn btn-success center-block">Сохранить</button>
            </div>
            <?php echo form_close(); ?>

        </div>
    </div>
    <? } ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var h = $('#image-cropper').cropit();
        $('.select-image-btn').click(function() {
            $('.cropit-image-input').click();
        });
    })
</script>