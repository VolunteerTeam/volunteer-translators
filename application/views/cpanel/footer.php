<?php
//Разбор js
if(isset($js['footer']) and is_array($js['footer']))
{
    foreach($js['footer'] as $item)
    {
        echo '<script type="text/javascript" src="'.$item['src'].'"></script>
            ';
    }
}


?>


<script type="text/javascript">
    $(function() {
        <?php
    //Разбор inline_js
        if(isset($inline_js) and is_array($inline_js))
        {
            foreach($inline_js as $item)
            {
                echo $item;
            }
        }

        ?>
    });
</script>

</body>
</html>