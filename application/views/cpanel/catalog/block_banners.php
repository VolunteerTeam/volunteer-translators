<h1 class="page_title">
    Банеры
</h1>


    <?php

    if(isset($banners) and $banners != false)
    {


        echo '
<div class="edit_sections">
  <div class="root_edit">

    </div>
            <ul  class="moders_list">';

        foreach($banners as $item)
        {
            $status = ($item->status==1)? '<a href="javascript:void(0)" class="i_published switch_banner_status" id="'.$item->id.'" title="Опубликовано">Опубликовано</a>':'<a href="javascript:void(0)" id="'.$item->id.'" class="i_not_published switch_banner_status" title="Не опубликовано">Не опубликовано</a>';

            echo '<li>
                    <span>'.$item->title.'</span>
                    <div class="controls">
                        <a class="i_delete" href="/admin/remove_banner/'.$item->id.'" title="Удалить">Удалить</a>
                        <a href="/admin/edit_banner/'.$item->id.'/edit_catalog_item/'.$item->subject_id.'" class="i_edit" title="Редактировать">Редактировать</a>';

            echo $status;

            echo '        </div>
                </li>';
        }



        echo '</ul>
        </div>

         <div class="contact_info">

            <fieldset class="contact_form">

                <dl>
                    <dt>Добавить изображения:</dt>
                    <dd>
                        <input type="file" name="files[]" id="files"  class="multi accept-gif|jpeg|png|jpg|JPG max-5"/>&nbsp;
                    </dd>
                </dl>


            </fieldset>
          </div>
        ';



    }
    else
    {



        ?>
          <div class="contact_info">

            <fieldset class="contact_form">

                <dl>
                    <dt>изображения:</dt>
                    <dd>
                        <input type="file" name="files[]" id="files"  class="multi accept-gif|jpeg|png|jpg|JPG max-5"/>&nbsp;
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
        max: 150,
        STRING: {
            remove: '<img style="float: left; margin-right: 20px;" src="/images/admin/delete.png" class="i_delete" height="20" width="20" title="Удалить" alt="Удалить">',
            file: ' Название: <input type="text" placeholder="обязательное поле"  name="ftxt[title][]"/>  ссылка: <input type="text" placeholder="http://"  name="ftxt[url][]"/>',
            selected: ' Выбраны: $file ',
            denied: 'Неверный тип файла: $ext!',
            duplicate: 'Этот файл уже выбран:\n$file!'
        }
    });
</script>
