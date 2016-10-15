<?php
$config['meta_title'] = array('content'=>'Волонтёры Переводов — бесплатные переводы в благотворительных целях');
$config['meta'] = array(
    'title'         => array('content'=>'Волонтёры Переводов — бесплатные переводы в благотворительных целях'),
    'keywords'      => array('content'=>'Волонтёры Переводов — бесплатные переводы в благотворительных целях'),
    'description'   => array('content'=>'Волонтёры Переводов — бесплатные переводы в благотворительных целях',
    )
);
$config['js']['header'] = false;

$config['js']['footer'] = array(
    array('src'=>'https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'),
    array('src'=>'/js/front/vendor/jquery.cookie-1.4.1.js'),
    array('src'=>'/js/front/bootstrap.min.js'),
    array('src'=>'/js/front/warehouse.js'),
    array('src'=>'/js/front/sypex-geo.js'),
    array('src'=>'/js/cpanel/kladr/jquery.kladr.min.js'),
    array('src'=>'/js/front/custom.js')
);

$config['css']  = array(
    array('src'=>'/css/front/bootstrap.min.css'),
    array('src'=>'/css/front/bootstrap-theme.css'),
    array('src'=>'/css/front/font-awesome.min.css'),
    array('src'=>'/js/cpanel/kladr/jquery.kladr.min.css'),
    array('src'=>'/css/front/custom.css'),
);
$config['inline_js'] = false;
$config['inline_css'] = false;

?>
