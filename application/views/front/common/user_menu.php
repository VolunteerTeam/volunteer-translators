<?php
    $user_data = $this->ion_auth->user()->row();
    $user_groups_db = $this->ion_auth->get_users_groups()->result_array();
    $user_groups = array();
    foreach($user_groups_db as $key => $value){
        array_push($user_groups, $value["id"]);
    }
?>

<div id="user-topmenu">
    <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="glyphicon glyphicon-user"></span>
                <span><?php echo $user_data->first_name ." ".$user_data->last_name; ?></span>
                <span class="glyphicon glyphicon-chevron-down"></span>
            </a>
            <ul class="dropdown-menu">
                <li><a href="/user/profile">Профиль<span class="glyphicon glyphicon-cog pull-right"></span></a></li>
                <li><a href="/user/orders">Заказы<span class="glyphicon glyphicon-shopping-cart pull-right"></span></a></li>
                <?php if(in_array("4", $user_groups) || in_array("1", $user_groups)) echo '<li><a href="/user/translations">Переводы<span class="glyphicon glyphicon-list pull-right"></span></a></li>'; ?>
                <li><a href="/user/logout">Выход<span class="glyphicon glyphicon-log-out pull-right"></span></a></li>
            </ul>
        </li>
    </ul>
</div>