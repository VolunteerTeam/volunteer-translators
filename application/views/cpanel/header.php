<!DOCTYPE html>
<html lang="ru">
<head>
    <title><?php echo $meta_title['content'];?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    //Разбор meta
    if(isset($meta) and is_array($meta))
    {
        foreach($meta as $type=> $content)
        {
            echo '<meta name="'.$type.'" content="'.$content['content'].'" />
            ';
        }
    }
    ?>

    <?php
    //Разбор css
    if(isset($css) and is_array($css))
    {
        foreach($css as $item)
        {
            echo '<link href="'.$item['src'].'" rel="stylesheet" type="text/css" />
            ';
        }
    }
    ?>
    <style>
        <?php
    //Разбор inline_js
        if(isset($inline_css) and is_array($inline_css))
        {
            foreach($inline_css as $item)
            {
                echo $item;
            }
        }

        ?>
    </style>
    <?php
    //Разбор js
    if(isset($js['header']) and is_array($js['header']))
    {
        foreach($js['header'] as $item)
        {
            echo '<script type="text/javascript" src="'.$item['src'].'"></script>
            ';
        }
    }


    ?>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="http://scottcheng.github.io/cropit/scripts/vendor.js"></script>
    <script src="/js/cropit/dist/jquery.cropit.js"></script>

    <style>
      .cropit-image-preview {
        background-color: #f8f8f8;
        background-size: cover;
        border: 1px solid #ccc;
        border-radius: 3px;
        margin-top: 7px;
        width: 120px;
        height: 120px;
        cursor: move;
      }

      .cropit-image-background {
        opacity: .2;
        cursor: auto;
      }

      .image-size-label {
        margin-top: 10px;
      }

      input {
        display: block;
      }

      button[type="submit"] {
        margin-top: 10px;
      }

      #result {
        margin-top: 10px;
        width: 900px;
      }

      #result-data {
        display: block;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        word-wrap: break-word;
      }
    </style>
</head>
<body>