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

        var dob = '<?php if(isset($user) && isset($user['dob'])){echo date('d.m.Y', strtotime($user["dob"]));} else {echo @$_POST['dob'];} ?>';
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

        // Проверка пользовательского соглашения
        $("input[name='agreement']").change(function(){
            if(!$("input[name='agreement']:checked").length){
                $("input[type='submit']").attr("disabled","disabled");
            } else {
                $("input[type='submit']").removeAttr("disabled");
            }
        });
        $("input[name='group']").change(function(){
            $("#agreement_link").attr("href",$(this).attr("data-href"));
        });

        // Подключение геокодера к поиску по городам
        var city_field = $("input[name='city']");
        var place_id = '<?php if(isset($user) && isset($user['city']['place_id'])){echo $user['city']['place_id'];} else {echo @$_POST['place_id'];} ?>';
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