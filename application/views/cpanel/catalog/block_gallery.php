
<div class="bestmedia-h20"></div>
<div class="row bestmedia-img-gallery">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Фотогалерея</div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="#">Выберите изображения для загрузки</label>
                    <input type="file" name="files_gallery[]" id="files_gallery"  class="multi_gallery"/>&nbsp;

                </div>

                <div class="bestmedia-callout bestmedia-callout-info">
                    Выберите файлы .JPG или .PNG не более 5 мегабайт каждый<br>
                    Вы можете добавить до 20 фотографий за раз. Порядок изменяется при помощи функции Drag&Drop
                </div>
                <div class="bestmedia-h10"></div>


                    <?php

                    if(isset($covers) and $covers !=false)
                    {

                        echo '<div class="img-sorting">';


                        foreach($covers as $image)
                        {


                            echo '
                            <div id="'.$image->id.'" class="col-xs-5 col-sm-4 col-md-3 col-lg-2">
                                <div href="#" class="thumbnail">
                                    <div class="dropup">
                                        <i class="status glyphicons '.($image->status ==1?'':'eye_close').' pull-right"></i>
                                        <a href="#" data-toggle="dropdown" class="glyphicons edit pull-right"></a>
                                        <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu1">
                                            <li><a
                                                    href="javascript:void(0)"
                                                    data-show="Показывать"
                                                    data-hide="Скрыть"
                                                    data-module="gallery"
                                                    data-id="'.$image->id.'"
                                                    class="toggle_status"
                                                    role="menuitem" tabindex="-1" href="#">'.($image->status ==1?'Скрыть':'Показывать').'</a></li>
                                            <li class="divider"></li>
                                            <li><a  class="i_delete" role="menuitem" tabindex="-1" href="'.base_url('cpanel/gallery/remove_gallery_item/'.$image->id.'/catalog/'.$image->subject_id).'">
                                            <i class="pull-left glyphicons remove_2"></i>Удалить</a></li>
                                        </ul>
                                    </div>
                                    <img style="width:223px; height: 140px;"  src="/content/'.$image->filename.'" alt="">
                                </div>
                            </div>

                            ';


                        }



                        echo '</div>';

                    }
                    else
                    {
                        //echo '<span class="empty_album">Альбом пуст</span>';
                    }


                    ?>


                </div>
        </div>
    </div>
</div>


<script type="text/javascript">

    $('.multi_gallery').MultiFile({
        accept: 'jpg|png|PNG|JPG|gif|JPEG|GIF',
        max: 150,
        STRING: {
            remove: '<i class=" glyphicons remove_2 pull-left"></i>',
            file: ' <div class=".col-md-offset-1 pull-left"> Описание: <input type="text" placeholder="Описание"  name="ftxt[description][ru][]"/></div>',
            selected: ' Выбраны: $file ',
            denied: 'Неверный тип файла: $ext!',
            duplicate: 'Этот файл уже выбран:\n$file!'
        }
    });
</script>