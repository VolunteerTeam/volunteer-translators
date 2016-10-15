<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

function setcity() {

    if(!empty($_COOKIE['userCity'])){

        define('USERCITY', $_COOKIE['userCity']);
    } else {
        define('USERCITY', null);
    }
}