<!-- Sidebar -->
<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
        <li class="sidebar-brand"><a href="<?php echo base_url('cpanel'); ?>"><?php echo $this->config->item('site_name'); ?></a></li>
        <li class="sidebar-title">Модули сайта</li>
        <li><a href="<?php echo base_url($this->side.'/news'); ?>">Новости</a></li>
        <li><a href="<?php echo base_url($this->side.'/pages'); ?>">Страницы</a></li>
        <li><a href="<?php echo base_url($this->side.'/goroda'); ?>">Города</a></li>
        <li><a href="<?php echo base_url($this->side.'/address'); ?>">Адреса</a></li>
        <li><a href="<?php echo base_url($this->side.'/menu'); ?>">Меню</a></li>
        <li><a href="<?php echo base_url($this->side.'/'); ?>">Общие настройки</a></li>
        <li><a href="<?php echo base_url($this->side.'/users'); ?>">Пользователи</a></li>
        <li><a href="<?php echo base_url($this->side.'/users/groups'); ?>">Группы</a></li>
    </ul>
</div>