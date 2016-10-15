$(function(){


    /**
     * Обработчик для отслеживания сортировки страниц
     */


    $(".i_published").on('click',function(){

        var $page = $(this);
        var arr = $page.attr('class').split(" ");

        $.ajax({
            url:'/admin/ajax/'+arr[1],
            dataType:'json',
            type:'post',
            data:{
                csrf_vh_name:$.cookie('csrf_vh_cookie'),
                id:$page.attr('id'),
                status:0
            },
            success:function(r)
            {
                if(r.result)
                {
                    $page.removeClass('i_published '+arr[1]).addClass('i_not_published '+arr[1]).html('Не опубликовано');

                }
                else
                {
                    alert(r.error);
                }


            }

        });

    });

    $(".i_not_published").on('click',function(){

        var $page = $(this);
        var arr = $page.attr('class').split(" ");

        $.ajax({
            url:'/admin/ajax/'+arr[1],
            dataType:'json',
            type:'post',
            data:{
                csrf_vh_name:$.cookie('csrf_vh_cookie'),
                id:$page.attr('id'),
                status:1
            },
            success:function(r)
            {
                if(r.result)
                {
                    $page.removeClass('i_not_published '+arr[1]).addClass('i_published '+arr[1]).html('Опубликовано');

                }
                else
                {
                    alert(r.error);
                }


            }

        });

    });

    $("#catalog_type").on('change',function(){

        var $select = $(this);
        var $response_layer = $("#constructed");
        $.ajax({
            url:'/cpanel/catalog/get_catalog_template',
            dataType:'json',
            type:'post',
            data:{
                csrf_vh_name:$.cookie('csrf_vh_cookie'),
                id:$select.val()

            },
            success:function(r)
            {
                if(r.result)
                {
                    $response_layer.html(r.result);
                }
                else
                {
                    //alert(r.error);
                }

            }

        });

    });

    $('.icon-remove').on('click',function(e){
        e.preventDefault();
        $('#catalog-id').val($(this).parent().attr('id'));
        $('#myModal').modal('show')

    });

    $('.delete-catalog').on('click', function () {

        var catalog_id = parseInt($('#catalog-id').val());
        var url = $('#'+catalog_id).attr('href');
        $.post(url ,{},function(data){
            if(data.result)
            {
                $('#catalog'+catalog_id).hide();

                $('#message-place').html(data.message);


            }
            else
            {
                $('#message-place').html(data.message);
            }

        },'json');

        $('#myModal').modal('hide')
    });

    var updateOutput = function(e)
    {
        var list   = e.length ? e : $(e.target);
        if (window.JSON) {
            var sort = window.JSON.stringify(list.nestable('serialize'));

            $.post('/cpanel/catalog/sorting',{struct:sort},function(response){
               // console.log(response);
            },'json');
        } else {
            console.log('JSON browser support required for this demo.');
        }
    };
    $('.dd').nestable({listNodeName:"ul"}).on('change', updateOutput);

    $.fn.bootstrapSwitch.defaults.size = 'mini';
    $.fn.bootstrapSwitch.defaults.onColor = 'success';
    $.fn.bootstrapSwitch.defaults.onText = 'вкл';
    $.fn.bootstrapSwitch.defaults.offText = 'выкл';
    $(".toggle_pin").bootstrapSwitch();
    $('.toggle_pin').on('switchChange.bootstrapSwitch', function(event, state) {

        $.post('/cpanel/catalog/set_default',{id:$(this).data('id'),state:state},function(response){

        },'json');

    });




       $("#constructed").on('change',"#nomenclature_drop",function(){
            var $select =  $( "#nomenclature_drop option:selected" ).text();
           $("#title").val($select);





    });


});
