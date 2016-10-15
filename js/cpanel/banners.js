$(function(){

    $(".form_filter").on('change',function(){

        $("#do_filter").submit();

    });
    $("#banner_type").on('change',function(){

        var $this = $(this);

        var type = parseInt($this.val(),10);

        if(type==1){ //swf
            $("#static").fadeOut();
            $("#swf").fadeIn();

        }else if(type == 2){ //static

            $("#swf").fadeOut();
            $("#static").fadeIn();
        }
        else{

            $("#swf").fadeOut();
            $("#static").fadeOut();
        }

    });

    $('#datepicker2').datepicker({
        format: 'dd.mm.yyyy'
    });


    var updateOutput = function(e)
    {
        var list   = e.length ? e : $(e.target);
        if (window.JSON) {
            var sort = window.JSON.stringify(list.nestable('serialize'));

            $.post('/cpanel/banners/sorting',{struct:sort},function(response){
                // console.log(response);
            },'json');
        } else {
            console.log('JSON browser support required for this demo.');
        }
    };
    $('.dd').nestable({listNodeName:"ul"}).on('change', updateOutput).nestable('collapseAll');

});
