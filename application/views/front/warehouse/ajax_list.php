<table border="0" cellpadding="5" class="table" cellspacing="5" >
    <tbody>
    <tr>
        <td >
            <p>Укажите ваш город:</p>

            <p><input id="searchCity" type="text" style="width:80%"></p>

            <p><?php
                if(!empty($goroda)){
                    foreach($goroda as $item){
                        $lfp[] = "<a onclick=\"selectCity('".$item->title."')\" href=\"javascript:void(0)\" class=\"gorod_status_".$item->city_status."\">".$item->title."</a>";
                    }
                    echo implode(", ",$lfp);
                }

                ?></p>
        </td>
    </tr>
    </tbody>
</table>

<script>
    $(function(){

        $("#searchCity").kladr({
            token:'535e517cfca9165929fbad88',
            type: $.kladr.type.city,
            select: function(o){
                console.log(o);
                selectCity(o.name);

            }
        });
    });

    function selectCity(val){
        var $modal = $('#allCity');
        if(val){
            $.cookie('userCity',val, { expires: 7 });
            $modal.modal('hide');

            $('#yourcity').html($.cookie('userCity'));
            location.reload();
        }
        $modal.modal('hide');
        $("#allCity .modal-body .city-list-all").html('');
    }
</script>