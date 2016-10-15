<legend>Виджеты</legend>

<?php

if(!empty($widgets))
{


    foreach($widgets as $widget)
    {
        echo '<div class="controls controls-row">';

        echo '

            '.form_checkbox($widget['checkbox_element']).' '.$widget['label'].',

       позиция: '.form_input($widget['input_element']);

        echo '</div>';
    }


}


?>