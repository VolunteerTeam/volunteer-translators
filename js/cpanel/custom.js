//  Sidebar Menu Toggle
$('#menu-toggle').click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("active");
});

//  Sorting
$('.bestmedia-table tbody').sortable().sortable('disable');
$('.sorting').bind({
    mousedown:function(){
        $('.bestmedia-table tbody').sortable('enable');
    },
    mouseup:function() {
        $('.bestmedia-table tbody').sortable('disable');
    }
});



$(function() {

    $.ajaxSetup({
        data:{
            csrf_vh_name:$.cookie('csrf_vh_cookie')
        }
    });

    $( ".img-sorting" ).sortable({
        update: function(event, ui) {
            var list = $(this).sortable('toArray');
            $.ajax({
                url:'/cpanel/gallery/update_list',
                type: 'POST',
                data:{
                    new_data:list

                },
                dataType:'json'
            });
        }
    }).disableSelection();



    $('.i_delete').click(function(){
        if(confirm("Удалить?")){
            return true;
        }
        else{
            return false;
        }

    });

    $(".toggle_status").on('click',function(){
        var $this = $(this);
        var b = $this.data('show');
        var a = $this.data('hide');
        var m = $this.data('module');

        ($this.text()==a) ? $this.text(b) :  $this.text(a);
        $this.parent().parent().parent().find('i.status').toggleClass("eye_close");

        $.post('/cpanel/'+m+'/toggle_status',{
            id:$this.data('id')
        })

    });
});


function ckInit(domId,url,options){
    var o = options || [];

    var editor = CKEDITOR.replace(domId,o);
    CKFinder.setupCKEditor( editor, '/js/cpanel/ckfinder/' );

}
// Add components to server, on-off switch
$('.bestmedia-table-components .btn-success').on('click', function(){
    $(this).parent().parent().removeClass('inactive');
    $(this).parent().next().attr('disabled', false);
});
$('.bestmedia-table-components .btn-danger').on('click', function(){
    $(this).parent().parent().addClass('inactive');
    $(this).parent().next().attr('disabled', true);
});

// Catalog Chevron
$('.cat').click(function(){
    if ($(this).hasClass('collapsed')) {
        $(this).children('i').removeClass('chevron-right').addClass('chevron-down');
    }
    else {
        $(this).children('i').removeClass('chevron-down').addClass('chevron-right');
    }
});

// Datepicker
$('#datepicker').datepicker({
    format: 'dd.mm.yyyy'
});