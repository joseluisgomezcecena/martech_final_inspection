<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
*/

$route['default_controller'] = 'pages/default';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


$route['home'] = 'pages/home'; //this will server for quality and for production...
$route['home/production'] = 'pages/home_production';

//login?user_email=ejauregui@martechmedical.com&user_name=Emanuel&user_lastname=Jauregui&user_martech_number=10111&user_active=1&user_is_admin=1&user_level_name=Supervisor&user_level_value=2&from=http://localhost/martech_final_inspection/


//reports
$route['reports/index'] = 'reports/index';
$route['reports/calidad'] = 'reports/reporte_calidad';
$route['reports/detail/(:any)'] = 'reports/detail/$1';

//produccion
$route['entries/create'] = 'entries/create';

//calidad
$route['entries/release/(:any)'] = 'entries/release/$1';
$route['entries/close/(:any)'] = 'entries/close/$1';
$route['entries/assign/(:any)'] = 'entries/assign/$1';

$route['entries/all-not-closed']['post'] = 'entries/api_entries_not_closed';
$route['entries/all-closed']['post'] = 'entries/api_entries_closed';




//forms
$route['forms/create'] = 'forms/create';

//users
$route['users/register'] = 'users/register';
$route['users/login'] = 'users/login';
$route['users/profile'] = 'users/profile';

//Authentication
$route['login'] = 'login/login';
$route['logout'] = 'login/logout';
