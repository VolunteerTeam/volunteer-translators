<div class="profile_info" style="padding-top:10px;">
    <span style="font-weight:bold;font-size:18px;"><a href="/user/orders/<?= $order->id ?>">Заказ №<?= $order->id ?></a></span><br/>
    от
    <script type="text/javascript">
        getLocalDateTime("<?= $order->created_on ?>");
    </script><br/>
    заказчик: <a href="/user/profile/<?= $order->client_user_id ?>"><?= $this->users_model->getUserName($order->client_user_id) ?></a><br/>
    менеджер: <div style="display:inline;" id="manager"><?php if($order->manager_user_id) echo '<a href="/user/profile/'.$order->manager_user_id.'">'.$this->users_model->getUserName($order->manager_user_id).'</a>'; ?></div><br/>
    статус: <div id="order_status" style="display:inline-block;"><?php
        if($order->date_out) {
            echo "<div class='status order done' style='margin: 0 auto;' title='Выполнен'></div> выполнен";
        } else if($order->date_in) {
            echo "<a href='#' class='orderStatusEditButton' data-toggle='modal' data-target='#changeOrderStatus' type='button' data-newstatus='done' data-translation='".$order->id."'><div class='status order in_process' style='margin: 0 auto;cursor:pointer;' title='В работе'></div> в работе</a>";
        } else {
            echo "<a href='#' class='orderStatusEditButton' data-toggle='modal' data-target='#changeOrderStatus' type='button' data-newstatus='in_process' data-translation='".$order->id."'><div class='status order new' style='margin: 0 auto;cursor:pointer;' title='Новый'></div> новый</a> <span style='font-style:italic;font-weight:normal;font-size:12px;color:#ccc;'>(нажмите, чтобы изменить)</span>";
        }
        ?></div>
</div>
<div id="error_block"></div>
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
        </thead>
        <tbody>
        <?php
        $files = $this->orders_model->getFiles($order->id);
        for($i = 0; $i < count($files); $i++){
            ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><a href="/<?= $files[$i]["file_in"] ?>"><?= $files[$i]["name_in"] ?> <i class="fa fa-download"></i></a></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <br/>
</div>