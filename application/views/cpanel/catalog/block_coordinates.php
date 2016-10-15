

<?php

if(isset($coords) and $coords != false)
{


    echo '
    <h1 class="page_title">
    Координаты
</h1>

<div class="edit_sections">
  <div class="root_edit">
         <a href="/admin/add_coords/edit_catalog_item/'.$id.'" class="i_add">Добавить</a>
    </div>
            <ul  class="moders_list">';

    foreach($coords as $item)
    {
        $status = ($item->status==1)? '<a href="javascript:void(0)" class="i_published switch_coords_status" id="'.$item->id.'" title="Опубликовано">Опубликовано</a>':'<a href="javascript:void(0)" id="'.$item->id.'" class="i_not_published switch_coords_status" title="Не опубликовано">Не опубликовано</a>';

        echo '<li>
                    <span>'.$item->title.'</span>
                    <div class="controls">
                        <a class="i_delete" href="/admin/remove_coords/'.$item->id.'/edit_catalog_item/'.$item->subject_id.'" title="Удалить">Удалить</a>
                        <a href="/admin/edit_coords/'.$item->id.'/edit_catalog_item/'.$item->subject_id.'" class="i_edit" title="Редактировать">Редактировать</a>';

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
<h1 class="page_title">
    Добавить координату
</h1>


<div class="contact_info" style="margin-bottom: 50px;">
    <div class="warning"></div>

    <fieldset class="contact_form" >
        <dl>
            <dt>Координаты:</dt>
            <dd>
                <?php echo form_input($coordinates); ?>
            </dd>
        </dl>
        <dl>
            <dt>Заголовок [RU]:</dt>
            <dd>
                <?php echo form_input($title); ?>
            </dd>
        </dl>

        <dl>
            <dt>Заголовок [EN]:</dt>
            <dd>
                <?php echo form_input($en_title); ?>
            </dd>
        </dl>



        <dl>
            <dt>Тип маркера:</dt>
            <dd>
                <?php  echo form_dropdown('marker_type', $marker_type['options'], $marker_type['default'], $marker_type['additional']); ?>
            </dd>
        </dl>


        <dl>
            <dt>Содержимое балуна  [RU]:</dt>
            <dd>
                <?php echo form_textarea($content); ?>

            </dd>
        </dl>

        <dl>
            <dt>Содержимое  балуна[EN] :</dt>
            <dd>
                <?php echo form_textarea($en_content); ?>

            </dd>
        </dl>


    </fieldset>

</div>



    <?php




}

?>
