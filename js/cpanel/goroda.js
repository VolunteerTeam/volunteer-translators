$(function(){


    $(".form_filter").on('change',function(){

        $("#do_filter").submit();

    });

    $(".chosen").chosen();

/*    $("#title").kladr({
        token:'535e517cfca9165929fbad88',
        type: $.kladr.type.city,
        select: function(o){
            console.log(o);
            $("#kladr_city_id").val(o.id);
        }
    });*/

});