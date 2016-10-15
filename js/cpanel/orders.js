$(function(){

    $(".form_filter").on('change',function(){

        $("#do_filter").submit();

    });

    $('#myModal').on('hide.bs.modal', function (e) {
        // do something...
        $(this).removeData('bs.modal');
        $(this).find('.modal-body').html('Подождите, состав заказа сейчас подгрузится...');
        $(this).find('.modal-title').html('Загрузка...');
        //console.log($(this));
    });

    $("#order_discount").on('keyup',function(){
       var $this = $(this);
        $("#total_price").val( parseInt($("#order_price").val(),10)-$this.val());

    });


    $("#order_warehouses").on('change',function(){
        var $this = $(this);
        if(parseInt($this.val())==0)
        {
            $("#warehouse_description").fadeIn();

            $("#order_warehouse").val('');

            $("#total_price").val( parseInt($("#total_price").val(),10)+parseInt($("#ship_cost").val()));

        }
        else
        {
            $("#warehouse_description").fadeOut();
            $("#total_price").val( parseInt($("#total_price").val(),10)-parseInt($("#ship_cost").val()));
        }

    });

    $("#order_payment_method").on('change',function(){
        var $this = $(this);

        if(parseInt($this.val())==2){

            $("#fileupload_layer").fadeIn();

        }else{
            $("#fileupload_layer").fadeOut();
        }


    });

})