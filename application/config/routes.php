<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['404_override']          = 'front/errors/error_404';
$route['default_controller']    = "front/index/index";

$route['login/(.*)']            = 'login/$1';
$route['login']                 = 'login/index';
$route['cpanel/(.*)']           = 'cpanel/$1';
$route['cpanel']                = 'cpanel/main';

$route['user/reg']                = 'user/register';
$route['user/register']                = 'user/register';
$route['user/forgot']                = 'user/forgot';
$route['user/forgot/send']		= 'user/forgot/send';
$route['user/forgot/restore/(.*)/(.*)']	= 'user/forgot/restore/$1/$2';

$route['user/reg/active/(.*)']           = 'user/reg/active/$1';
$route['user/reg/regmail']                = 'user/reg/regmail';
$route['user/activate']                = 'user/activate';
$route['user/login']                = 'user/login';
$route['user/auth']                = 'user/auth';
$route['user/logout']                = 'user/logout';
$route['user/auth/ajax']                = 'user/auth/ajax';
$route['user/auth/log']                = 'user/auth/log';
$route['user/profile']                = 'user/profile';
$route['user/profile/save']                = 'user/profile/save';

$route['images/front']                = '/';





$route['volonter/rating']       = 'front/volonter/rating';
$route['volonter']              = 'front/volonter/index';
$route['manager/rating']       = 'front/manager/rating';
$route['manager']              = 'front/manager/index';

$route['goroda/ajax']           = 'front/warehouse/ajax_list';
$route['goroda/json']           = 'front/warehouse/ajax_list_json';
$route['goroda/(.*)']           = 'front/warehouse/detail/$1';
$route['goroda']                = 'front/warehouse/index';



$route['news/(.*)']             = 'front/news/detail/$1';
$route['news']                  = 'front/news/index';

$route['users/(.*)']            = 'front/users/detail/$1';
$route['sovet']                 = 'front/sovet/index';

$route['(.*)']                  = 'front/pages/$1';

/* End of file routes.php */
/* Location: ./application/config/routes.php */