$(function(){

    var $modal =$('#sypex-model');
    var city = $.cookie('userCity');

    if(!city){
        $.ajax({
            dataType: "jsonp",
            url: 'https://api.sypexgeo.net/sWCTr/jsonp?callback=check',
            success: function(data){

                $modal.find(".modal-body").html('<p>Ваш город '+data.city.name_ru+'?</p>');
                $modal.find("#city_name").val(data.city.name_ru);
                $modal.modal('show');
            }
        });
    }

    $(".savecity").on('click',function(e){
        var val = $("#city_name").val();
        if(val){
            $.cookie('userCity',val, { expires: 7 });
            $modal.modal('hide');
            $('#yourcity').html($.cookie('userCity'));
            location.reload();
        }
    });

    if ( $.cookie('userCity') != null ) {
        $('#yourcity').html($.cookie('userCity'));
    };



});
