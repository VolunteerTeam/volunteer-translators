<style type="text/css">
    .form-control:focus {
        border-color: #F70707;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(247, 7, 7, 1);
    }
    h1 {
        text-align: center;
    }
    .required {
        color: red;
    }
    .form-control {
        width: 100%;
    }
    .error {
        border: 1px solid red;
    }
    .text-danger {
        margin: 0;
        font-size: 12px;
        font-weight: bold;
    }
    #map_container {
        padding: 15px;
        display: none;
    }
    #map_canvas {
        width: 100%;
        height: 600px;
    }
    .ui-autocomplete {
        background-color: white;
        border: 1px solid #cfcfcf;
        list-style-type: none;
        padding-left: 5px;
    }
    .ui-autocomplete li {
        list-style-type: none;
    }
    .ui-menu-item a {
        color: #6f6f6f;
    }
    .ui-menu-item a:hover {
        cursor: pointer;
        text-decoration: none;
        color: #3C2A94;
    }
    .bestmedia-input {
        margin-bottom: 10px;
    }
</style>

<div class="container main" role="main">
    <div class="row">
        <?php echo $this->session->flashdata('verify_msg'); ?>
    </div>
    <div class="row">
        <h1>Регистрация</h1>
        <?php
            $attributes = array("name" => "reg_form");
            echo form_open("user/register", $attributes);
        ?>
        <div class="row bestmedia-input">
            <div class="col-md-4">
                <label class="control-label">Фамилия <span class="required">*</span></label>
                <input type="text" name="last_name" value="<?php echo @$_POST['last_name']; ?>" class="form-control <?php if(!empty(form_error('last_name'))){echo "error";} ?>">
                <span class="text-danger"><?php echo form_error('last_name'); ?></span>
            </div>
            <div class="col-md-4">
                <label class="control-label">Имя <span class="required">*</span></label>
                <input type="text" name="first_name" value="<?php echo @$_POST['first_name']; ?>" class="form-control <?php if(!empty(form_error('first_name'))){echo "error";} ?>">
                <span class="text-danger"><?php echo form_error('first_name'); ?></span>
            </div>
            <div class="col-md-4">
                <label class="control-label">Отчество</label>
                <input type="text" name="patro_name" value="<?php echo @$_POST['patro_name']; ?>" class="form-control">
            </div>
        </div>
        <div class="row bestmedia-input">
            <div class="col-md-4">
                <label class="control-label">Электронная почта <span class="required">*</span></label>
                <input type="text" name="email" value="<?php echo @$_POST['email']; ?>" class="form-control <?php if(!empty(form_error('email'))){echo "error";} ?>">
                <span class="text-danger"><?php echo form_error('email'); ?></span>
            </div>
            <div class="col-md-4">
                <label class="control-label">Пароль <span class="required">*</span></label>
                <div id="mypass-wrap" class="control-group">
                    <input type="password" name="password" value="" placeholder="пароль" class="form-control <?php if(!empty(form_error('password'))){echo "error";} ?>">
                </div>
                <span class="text-danger"><?php echo form_error('password'); ?></span>
            </div>
            <div class="col-md-4">
                <label class="control-label">Повторите пароль <span class="required">*</span></label>
                <div id="mypass-wrap" class="control-group">
                    <input type="password" name="cpassword" value="" placeholder="пароль" class="form-control <?php if(!empty(form_error('cpassword'))){echo "error";} ?>">
                </div>
                <span class="text-danger"><?php echo form_error('cpassword'); ?></span>
            </div>
        </div>
        <div class="row bestmedia-input">
            <div class="col-md-6">
                <label class="control-label">Представление (вкратце о себе) <span class="required">*</span></label>
                <input type="text" name="about" value="<?php echo @$_POST['about']; ?>" class="form-control <?php if(!empty(form_error('about'))){echo "error";} ?>">
                <span class="text-danger"><?php echo form_error('about'); ?></span>
            </div>
            <div class="col-md-6">
                <label class="control-label">Координаты </label>
                <input type="text" name="coordinates" value="<?php echo @$_POST['coordinates']; ?>" class="form-control">
            </div>
        </div>
        <div class="row bestmedia-input">
            <div class="col-md-12">
                <label class="control-label">Должность (кем работаете) <span class="required">*</span></label>
                <input type="text" name="job_post" value="<?php echo @$_POST['job_post']; ?>" class="form-control <?php if(!empty(form_error('job_post'))){echo "error";} ?>">
                <span class="text-danger"><?php echo form_error('job_post'); ?></span>
            </div>
        </div>
        <div class="row bestmedia-input">
            <div class="col-md-4">
                <label class="control-label">Дата рождения <span class="required">*</span></label>
                <div class='input-group date' id='datepicker1'>
                    <input type='text' class="form-control <?php if(!empty(form_error('dob'))){echo "error";} ?>" name="dob" value="<?php echo @$_POST['dob']; ?>"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
                <span class="text-danger"><?php echo form_error('dob'); ?></span>
            </div>
            <div class="col-md-4">
                <label class="control-label">Пол <span class="required">*</span></label>
                <select name="sex" class="form-control <?php if(!empty(form_error('sex'))){echo "error";} ?>" size="1">
                    <option value="">------------</option>
                    <option value="1" <?php if(@$_POST['sex'] == '1') { echo "selected"; } ?> >Мужской</option>
                    <option value="2" <?php if(@$_POST['sex'] == '2') {echo "selected"; } ?> >Женский</option>
                </select>
                <span class="text-danger"><?php echo form_error('sex'); ?></span>
            </div>
            <div class="col-md-4">
                <label class="control-label">Город <span class="required">*</span></label>
                <input type="text" name="city" value="<?php echo @$_POST['city']; ?>" class="form-control <?php if(!empty(form_error('city'))){echo "error";} ?>">
                <input id="country" name="country" type="text" value="<?php echo @$_POST['country']; ?>" hidden/>
                <input id="country_short" name="country_short" type="text" value="<?php echo @$_POST['country_short']; ?>" hidden/>
                <input id="latlng" name="latlng" type="text" value="<?php echo @$_POST['latlng']; ?>" hidden/>
                <input id="place_id" name="place_id" type="text" value="<?php echo @$_POST['place_id']; ?>" hidden/>
                <span class="text-danger" id="city-error"><?php echo form_error('city'); ?></span>
            </div>
        </div>
        <div class="row bestmedia-input" id="map_container">
            <div id="map_canvas"></div><br/>
        </div>
        <div class="row bestmedia-input">
            <div class="col-md-6">
                <label class="control-label">Сотовый телефон <span class="required">*</span></label>
                <input type="text" name="phone" value="<?php echo @$_POST['phone']; ?>" placeholder="+7-ххх-ххх-хх-хх" class="form-control <?php if(!empty(form_error('phone'))){echo "error";} ?>" max="16">
                <span class="text-danger"><?php echo form_error('phone'); ?></span>
            </div>
            <div class="col-md-6">
                <label class="control-label">Скайп</label>
                <input type="text" name="skype" value="<?php echo @$_POST['skype']; ?>" class="form-control" >
            </div>
        </div>
        <div style="height: 50px;"></div>
        <div class="row bestmedia-input">
            <div class="col-md-6">
                <label class="control-label">Facebook</label>
                <input type="text" name="fb_profile" value="<?php echo @$_POST['fb_profile']; ?>" class="form-control" ></div>
            <div class="col-md-6">
                <label class="control-label">Вконтакте</label>
                <input type="text" name="vk_profile" value="<?php echo @$_POST['vk_profile']; ?>" class="form-control" ></div>
        </div>
        <div class="row bestmedia-input">
            <div class="col-md-6">
                <label class="control-label">Одноклассники</label>
                <input type="text" name="od_profile" value="<?php echo @$_POST['od_profile']; ?>" class="form-control" ></div>
            <div class="col-md-6">
                <label class="control-label">Google+</label>
                <input type="text" name="gp_profile" value="<?php echo @$_POST['gp_profile']; ?>" class="form-control" ></div>
        </div>
        <div class="row bestmedia-input">
            <div class="col-md-6">
                <label class="control-label">Twitter</label>
                <input type="text" name="tw_profile" value="<?php echo @$_POST['tw_profile']; ?>" class="form-control" ></div>
            <div class="col-md-6">
                <label class="control-label">Instagram</label>
                <input type="text" name="in_profile" value="<?php echo @$_POST['in_profile']; ?>" class="form-control" ></div>
        </div>
        <div class="row bestmedia-input">
            <div class="col-md-6">
                <label class="control-label">Livejournal</label>
                <input type="text" name="lj_profile" value="<?php echo @$_POST['lj_profile']; ?>" class="form-control" ></div>
            <div class="col-md-6">
                <label class="control-label">LinkenId</label>
                <input type="text" name="li_profile" value="<?php echo @$_POST['li_profile']; ?>" class="form-control" ></div>
        </div>
        <div class="row bestmedia-input">
            <div class="col-md-12">
                <p><b>Ваша роль <span class="required">*</span>:</b></p>
                <div class="btn-group <?php if(!empty(form_error('group'))){echo "error";} ?>" data-toggle="buttons">
                    <label class="btn btn-default <?php if(@$_POST['group'] == '7') { echo "active"; } ?>">
                        <input type="radio" name="group" value="7"  <?php if(@$_POST['group'] == '7') { echo "checked"; } ?>/> Заказчик
                    </label>
                    <label class="btn btn-default <?php if(@$_POST['group'] == '4') { echo "active"; } ?>">
                        <input type="radio" name="group" value="4"  <?php if(@$_POST['group'] == '4') { echo "checked"; } ?>/> Волонтёр
                    </label>
                </div>
                <span class="text-danger"><?php echo form_error('group'); ?></span>
                <?php
                /*<select class="form-control" name="group" size="1">
                    <option value="7" <?php //if(@$_POST['group'] == 'Заказчик') { echo "selected"; } ?>>Заказчик</option>
                    <option value="4" <?php //if(@$_POST['group'] == 'Волонтёр') { echo "selected"; } ?>>Волонтёр</option>
                </select>*/
                ?>

            </div>
        </div>
        <div style="height: 30px;"></div>
        <div>
            <div class="text-center">
                <input type="submit" name="submit" value="Зарегистрироваться" class="btn btn-success">
            </div>
        </div>
        <?php echo form_close(); ?>
        <?php echo $this->session->flashdata('msg'); ?>
    </div>
</div>

<script>
    var geocoder;
    var map;
    var marker;

    function initMap(){
        //Определение карты
        var latlng = "";
        var myLatLng = "";
        var coordinates = $("#latlng").val();
        if(coordinates){
            coordinates = coordinates.split(",");
            myLatLng = {lat: parseFloat(coordinates[0]), lng: parseFloat(coordinates[1])};
            latlng = new google.maps.LatLng(myLatLng['lat'],myLatLng['lng']);
        } else {
            latlng = new google.maps.LatLng(55.7494733,37.3523255); // Москва
        }
        var options = {
            zoom: 10,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById("map_canvas"), options);

        //Определение геокодера
        geocoder = new google.maps.Geocoder();

        marker = new google.maps.Marker({
            map: map,
            draggable: true
        });
        if(myLatLng) marker.setPosition(myLatLng);
    }

    $(document).ready(function(){
        // Подключение Datepicker для выбора даты рождения
        var datepicker1 = $("#datepicker1");
        datepicker1.datetimepicker({
            format: 'DD.MM.YYYY',
            minDate: moment().add(-100, 'years'),
            maxDate: moment().add(-10, 'years'),
            viewMode: 'years',
            locale: 'ru'
        });
        datepicker1.on('dp.hide',function(){
            setTimeout(function(){
                $('#datepicker1').data('DateTimePicker').viewMode('years');
            },1);
        });

        var dob = '<?php echo @$_POST['dob']; ?>';
        if(!dob){
            datepicker1.data('DateTimePicker').date(null);
        } else {
            datepicker1.data('DateTimePicker').date(dob);
        }

        // Создание маски ввода номера телефона
        var phone_field = $("input[name='phone']");
        phone_field.on('focus',function(){
            if(!$(this).val()){
                $(this).val("+7-")
            }
        });
        phone_field.keypress(function() {
            if (this.value.length >= 16) return false;
            var hyphens = [6,10,13]; // длина ввода, после которой нужно вставить дефис
            var value = $(this).val(); // текущее значение поля Телефон
            if($.inArray(value.length, hyphens) != -1) {
                $(this).val(value+"-");
            }
        });

        // Подключение геокодера к поиску по городам
        var city_field = $("input[name='city']");
        var place_id = '<?php echo @$_POST['place_id']; ?>';
        var latlng_field = $("#latlng");
        var place_id_field = $("#place_id");
        var country_field = $("#country");
        var country_short_field = $("#country_short");
        if(!place_id && navigator.geolocation){
            navigator.geolocation.getCurrentPosition(function(position) {
                geocoder = new google.maps.Geocoder();
                geocoder.geocode( {'location': {lat: position.coords.latitude, lng: position.coords.longitude}}, function(results, status){
                    if (status === 'OK'){
                        var num = 0;
                        $.each(results,function(i,result){
                            if(result.address_components[0].types[0] == 'locality'){
                                num = i;
                                $.each(result.address_components, function(i,component){
                                    if(component.types[0] == 'country'){
                                        country_field.val(component.long_name);
                                        country_short_field.val(component.short_name);
                                    }
                                });
                                return false;
                            }
                        });
                        var address_length = results[num].address_components.length;
                        city_field.val(results[num].formatted_address);
                        latlng_field.val(position.coords.latitude + "," + position.coords.longitude);
                        place_id_field.val(results[num].place_id);
                    }
                })
            })
        }
        city_field.focusin(function(){
            $('#map_container').show();
            initMap();
        });
        city_field.focusout(function(){
            $('#map_container').hide();
        });
        city_field.keypress(function(){
            latlng_field.val("");
        });

        city_field.autocomplete({
            //Определяем значение для адреса при геокодировании
            source: function(request, response) {
                geocoder.geocode( {'address': request.term}, function(results, status) {
                    response($.map(results, function(item) {
                        return {
                            label:  item.formatted_address,
                            value: item.formatted_address,
                            latitude: item.geometry.location.lat(),
                            longitude: item.geometry.location.lng(),
                            address_components: item.address_components,
                            place_id: item.place_id
                        }
                    }));
                })
            },
            //Выполняется при выборе конкретного адреса
            select: function(event, ui) {
                if(ui.item.address_components[0]['types'][0] != 'locality'){
                    $("#city-error").text("Нужно выбрать только населённый пункт (город, пгт и т.п.)").show();
                } else {
                    var address_length = ui.item.address_components.length;
                    latlng_field.val(ui.item.latitude + "," + ui.item.longitude);
                    place_id_field.val(ui.item.place_id);
                    $.each(ui.item.address_components, function(i,component){
                        if(component.types[0] == 'country'){
                            country_field.val(component.long_name);
                            country_short_field.val(component.short_name);
                        }
                    });
                    $("#city-error").hide();
                    var location = new google.maps.LatLng(ui.item.latitude, ui.item.longitude);
                    marker.setPosition(location);
                    map.setCenter(location);
                }
            },
            messages: {
                noResults: '',
                    results: function() {}
            },
            open: function(){
                $('.ui-autocomplete').css('width', city_field.outerWidth()+"px");
            }
        });
    })

</script>
