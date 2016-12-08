<script type="text/javascript">
    function getLocalDateTime(datetime){
        var localTime = moment.utc(datetime).toDate();
        localTime = moment(localTime).format('DD.MM.YYYY HH:mm:ss');
        document.write(localTime);
    }
</script>

<div class="container main" role="main">
    <? if(isset($order)) {?>
        <?php if($order->photo_thumb){
            ?>
            <div style="float:left;padding-top:10px;margin-right:10px;">
                <img alt="" src="<?= $order->photo_thumb ?>">
            </div>
            <?php
        } ?>
        <div class="profile_info" style="padding-top:10px;float:left;">
            <span style="font-weight:bold;font-size:18px;">Заказ №<?= $order->id ?></span><br/>
            от
            <script type="text/javascript">
                getLocalDateTime("<?= $order->created_on ?>");
            </script>
            <div style="margin-top:10px;">
                <span>Статус заказа:</span>
                <?php if($order->date_out) {
                    echo "выполнен";
                } else if($order->date_in) { ?>
                    в работе с
                    <script type="text/javascript">
                        <?php if($order->date_in) echo "getLocalDateTime(".$order->date_in.");"; ?>
                    </script>
                    <?php
                } else {
                    echo "<div class='status order new' title='Новый'></div>новый";
                }
                ?><br/>
                <span>Заказчик:</span> <a href="/user/profile/<?= $order->client_user_id ?>" target="_blank"><?= $this->users_model->getUserName($order->client_user_id) ?></a><br/>
                <span>Менеджер:</span> <a href="/user/profile/<?= $order->manager_user_id ?>" target="_blank"><?php if($order->manager_user_id) echo $this->users_model->getUserName($order->manager_user_id); ?></a><br/>
            </div>
        </div>
        <div class="profile_info" style="clear:both;padding-top:10px;">
            <span>Язык оригинала: </span><?= $this->orders_model->getLanguageName($order->language_in) ?> <br/>
            <span>Язык перевода: </span><?= $this->orders_model->getLanguageName($order->language_out) ?>
            <br/>
        </div>
        <div class="profile_info" style="padding-top:10px;">
            <span>Получатель перевода: </span><?= $order->receiver ?> <br/>
            <span>Назначение перевода: </span><?= $order->purpose ?>
            <br/>
        </div>
        <div class="profile_info" style="padding-top:10px;">
            <span>Файлы: </span><br/>
            <table class="table">
                <thead class="thead-inverse">
                    <th>№п/п</th>
                    <th>Файл оригинала</th>
                    <th>Файл перевода</th>
                    <th>Переводчик</th>
                    <th>Статус</th>
                </thead>
                <tbody>
                    <?php
                        $files = $this->orders_model->getFiles($order->id);
                        for($i = 0; $i < count($files); $i++){
                            ?>
                            <tr>
                                <td><?= $i+1 ?></td>
                                <td><a href="/<?= $files[$i]["file_in"] ?>"><?= $files[$i]["name_in"] ?> <i class="fa fa-download"></i></a></td>
                                <td><?php if($files[$i]["file_out"]) echo "<a href='/".$files[$i]["file_out"]."'>".$files[$i]['name_out']." <i class='fa fa-download'></i></a>" ?></td>
                                <td><?php if($files[$i]["translator_user_id"]) echo $this->users_model->getUserName($files[$i]["translator_user_id"]); ?></td>
                                <td><?php
                                    if($files[$i]["date_out"]) {
                                        echo "выполнен";
                                    } else if($files[$i]["date_in"]) {
                                        echo "в работе";
                                    }
                                    ?></td>
                            </tr>
                            <?php
                        }
                    ?>
                </tbody>
            </table>
            <br/>
        </div>

    <? } ?>
</div>