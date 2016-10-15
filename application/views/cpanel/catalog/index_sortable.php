<h1 class="page_title">
    Каталог  / Изменить порядок
</h1>

<div class="edit_order_info">
    Для изменения порядка разделов просто перетащите их на нужное место <span class="orange">(Drag & Drop)</span>
</div>
<?php echo $message; ?>

<input type="hidden" id="order_mode" name="order_mode" value="update_catalog_order"/>
<?php


echo '
            <ul id="edit_order">';


foreach($menu_items as $item)
{
    echo '<li id="'.$item->id.'">'.$item->title.'</li>';
}



echo '</ul>';



?>

