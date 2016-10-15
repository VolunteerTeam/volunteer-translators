<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

function setlang() {


    $host = explode('.', $_SERVER['HTTP_HOST']);
    $zone = array_pop($host);

    if ($zone == 'com') {
        define('SITELANG', 'en');
        define('LP', 'en_');
    } else {
        define('SITELANG', 'ru');
        define('LP', '');
    }
}

