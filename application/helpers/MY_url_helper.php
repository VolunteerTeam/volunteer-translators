<?php

/**
 * хелпер модифицирован для возврата baseurl + /lang/
 */


if (!function_exists('base_url')) {

    function base_url($uri = '') {
        $CI = & get_instance();


        if($CI->config->item('db_router')=='true')
        {

            $uri_array = explode('/',trim($uri,'/'));

            $class = array_shift($uri_array);
            $params = implode("/",$uri_array);




            if(isset($CI->matrix_route[$class][$params]))
            {

                $uri =  trim($CI->matrix_route[$class][$params],'/');

                return $CI->config->base_url($uri);
            }


        }
        return $CI->config->base_url($uri);


    }

}



/**
 * хелпер модифицирован для возврата URL без index.php и со значением языка в первом сегменте uri
 */
if (!function_exists('site_url')) {

    function site_url($uri = '') {
        $CI = & get_instance();
        return base_url() . $uri;
    }

}


function get_switcher()
{
    $CI = & get_instance();
    $allowed_hosts = $CI->config->item('allowed_hosts');

     if(SITELANG=='ru')
     {
         return '<li><a href="http://'.$allowed_hosts['en'].'" class="en" title="English">English</a></li>';

     }
    else
    {
        return '<li><a href="http://'.$allowed_hosts['ru'].'" class="ru" title="Русский">Русский</a></li>';
    }


}
?>