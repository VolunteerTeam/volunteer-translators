<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function show_success_message($text)
{
    return '<div class="alert alert-success" role="alert">'.$text.'</div>';

}


function show_error_message($text)
{
        return '<div class="alert alert-danger" role="alert">'.$text.'</div>';
}



function monthes($i,$mode = 'ru')
{

    $m = array(

        'ru'=>array(
            '1'=>'января',
            '2'=>'февраля',
            '3'=>'марта',
            '4'=>'апреля',
            '5'=>'мая',
            '6'=>'июня',
            '7'=>'июля',
            '8'=>'августа',
            '9'=>'сентября',
            '10'=>'октября',
            '11'=>'ноября',
            '12'=>'декабря'
        ),
        'ru2'=>array(
            '1'=>'Январь',
            '2'=>'Февраль',
            '3'=>'Март',
            '4'=>'Апрель',
            '5'=>'Май',
            '6'=>'Июнь',
            '7'=>'Июль',
            '8'=>'Август',
            '9'=>'Сентябрь',
            '10'=>'Октябрь',
            '11'=>'Ноябрь',
            '12'=>'Декабрь'
        ),
        'en'=>array(
            '1'=>'january',
            '2'=>'february',
            '3'=>'march',
            '4'=>'april',
            '5'=>'may',
            '6'=>'june',
            '7'=>'jule',
            '8'=>'august',
            '9'=>'september',
            '10'=>'october',
            '11'=>'november',
            '12'=>'december'
        )


    );

    return $m[$mode][$i];


}


function decl_of_num($number, $titles)
{
    $cases = array (2, 0, 1, 1, 1, 2);
    return $number." ".$titles[ ($number%100 > 4 && $number %100 < 20) ? 2 : $cases[min($number%10, 5)] ];
}






?>