<?php
$user_data = $this->ion_auth->user()->row();
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
                <li><a href="#">Переводы<span class="glyphicon glyphicon-stats pull-right"></span></a></li>
                <li><a href="/user/logout">Выход<span class="glyphicon glyphicon-log-out pull-right"></span></a></li>
            </ul>
        </li>
    </ul>
</div>