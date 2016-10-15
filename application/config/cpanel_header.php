<?php
$config['meta_title'] = array('content'=>'Панель управления');
$config['meta'] = array(
    'keywords'      =>array('content'=>''),
    'description'   =>array('content'=>'')
);
$config['js']['header'] = array(
    array('src'=>'/js/cpanel/modernizr.min.js'),
    array('src'=>'//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js'),
   // array('src'=>'/js/cpanel/jquery.min.js')
);

$config['js']['footer'] = array(
    array('src'=>'/js/cpanel/jquery.cookie.js'),
    array('src'=>'/js/cpanel/jquery-ui.min.js'),
    array('src'=>'/js/cpanel/bootstrap.min.js'),
    array('src'=>'/js/cpanel/ckeditor_full/ckeditor.js'),
    array('src'=>'/js/cpanel/ckfinder/ckfinder.js'),
    array('src'=>'/js/cpanel/plugins.js'),
    array('src'=>'/js/front/vendor/jquery.maskedinput.js'),
    array('src'=>'/js/cpanel/kladr/jquery.kladr.min.js'),
    array('src'=>'/js/cpanel/custom.js'),

);

$config['css']  = array(

    array('src'=>'/css/cpanel/glyphicons.css'),
    array('src'=>'/css/cpanel/bootstrap.css'),
    array('src'=>'/css/cpanel/custom.css'),
    array('src'=>'/js/cpanel/kladr/jquery.kladr.min.css'),
);
$config['inline_js'] = false;
$config['inline_css'] = false;



?>
