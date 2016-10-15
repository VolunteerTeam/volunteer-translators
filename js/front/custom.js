$(function(){

    if ( $('#choosecity').size()>0 ) {
        var modalAllCity = '\
<div class="modal fade" id="allCity" tabindex="-1" role="dialog" aria-labelledby="allCityLabel" aria-hidden="true">\
<div class="modal-dialog modal-lg">\
<div class="modal-content">\
<div class="modal-header">\
<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>\
<h4 class="modal-title" id="allCityLabel">Выберите ваш город:</h4>\
</div>\
<div class="modal-body">\
<div class="city-list-all">\
\
</div>\
<div class="clearfix"></div>\
</div>\
</div>\
</div>\
</div>';

        $('body').append(modalAllCity);

        $('#choosecity').add('.choosecity').on('click',function(){
            $.ajax({
                contentType: "text/html",
                type: "GET",
                dataType: "html",
                url: '/goroda/ajax',
                success: function(data){
                    var $modal = $('#allCity');
                    $modal.find(".modal-body .city-list-all").html(" ").append(data);
                    $modal.modal('show');
                    
                }
            });
        })

        $(".choosecity_save").on('click',function(e){
            var $modal = $('#allCity');
            var val = $("#allCity input[name='city_name']:checked").val();
            if(val){
                $.cookie('userCity',val, { expires: 7 });
                $modal.modal('hide');

                $('#yourcity').html($.cookie('userCity'));
                location.reload();
            }
            $modal.modal('hide');
            $("#allCity .modal-body .city-list-all").html('');
        });

        $('#allCity .closed').on('click',function(){
            $("#allCity .modal-body .city-list-all").html('');
        });
    };

});