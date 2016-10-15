<?php
//Контрольная точка для замеров производительности
$this->benchmark->mark('code_start');
//Загрузка хедер-слоя с дополнительными параметрами
$this->load->view('front/header');
//Загрузка основного контента
//tpl - имя шаблона
//content - ассоциативный массив с данными
$this->load->view('front/'.$tpl,$content);
//Загружаем подвал
$this->load->view('front/footer',false);

?>