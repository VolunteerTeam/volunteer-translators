<h1 class="page_title">
    Индексное изображение для раздела
</h1>


<?php

if(isset($images) and $images != false)
{


    echo '
<div class="edit_sections">
  <div class="root_edit">

    </div>
            <ul  class="moders_list">';

    foreach($images as $item)
    {
        $status = ($item->status==1)? '<a href="javascript:void(0)" class="i_published switch_image_status" id="'.$item->id.'" title="Опубликовано">Опубликовано</a>':'<a href="javascript:void(0)" id="'.$item->id.'" class="i_not_published switch_image_status" title="Не опубликовано">Не опубликовано</a>';

        echo '<li>
                    <span>'.$item->title.'</span>
                    <div class="controls">
                        <a class="i_delete" href="/admin/remove_image/'.$item->id.'" title="Удалить">Удалить</a>
                        <a href="/admin/edit_image/'.$item->id.'/edit_catalog_item/'.$item->subject_id.'" class="i_edit" title="Редактировать">Редактировать</a>';

        echo $status;

        echo '        </div>
                </li>';
    }



    echo '</ul>
        </div>
        ';



}
else
{



    ?>
<div class="contact_info">

    <fieldset class="contact_form">

        <dl>
            <dt>Добавить изображение:</dt>
            <dd>
                <input type="file" name="images_files[]" id="files"  class="multi accept-gif|jpeg|png|jpg|JPG"/>&nbsp;
            </dd>
        </dl>


    </fieldset>
</div>



<?php


}

?>
<script type="text/javascript">

    $('.multi').MultiFile({
        accept: 'jpg|png|PNG|JPG|gif|JPEG|GIF',
        max: 1,
        STRING: {
            remove: '<img style="float: left; margin-right: 20px;" src="/images/admin/delete.png" class="i_delete" height="20" width="20" title="Удалить" alt="Удалить">',
            file: ' Описание: <input type="text" placeholder="на русском"  name="itxt[title][]"/> <input type="text" placeholder="на английском"  name="itxt[en_title][]"/>',
            selected: ' Выбраны: $file ',
            denied: 'Неверный тип файла: $ext!',
            duplicate: 'Этот файл уже выбран:\n$file!'
        }
    });
</script>
