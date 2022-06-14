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
$route['home/production']['get'] = 'pages/home_production';

//login?user_email=ejauregui@martechmedical.com&user_name=Emanuel&user_lastname=Jauregui&user_martech_number=10111&user_active=1&user_is_admin=1&user_level_name=Supervisor&user_level_value=2&from=http://localhost/martech_final_inspection/


//reports
$route['reports/index'] = 'reports/index';
$route['reports/calidad'] = 'reports/reporte_calidad';
$route['reports/produccion'] = 'reports/reporte_produccion';

$route['reports/detail/(:any)'] = 'reports/detail/$1';

//produccion
$route['entries/create'] = 'entries/create';
$route['entries/rework']['get'] = 'entries/rework';
$route['entries/rework']['post'] = 'entries/rework_save';

//calidad
$route['entries/release/(:any)']['get'] = 'entries/release/$1';
$route['entries/release/(:any)']['post'] = 'entries/release_save/$1';


$route['entries/close/(:any)'] = 'entries/close/$1';
$route['entries/assign/(:any)'] = 'entries/assign/$1';

$route['entries/all-opened']['get'] = 'entries/api_entries_opened';
$route['entries/all-closed']['get'] = 'entries/api_entries_closed';
$route['entries/all-rejected']['get'] = 'entries/api_entries_rejected';


//forms
$route['forms/create'] = 'forms/create';

//users
$route['users/register'] = 'users/register';
$route['users/login'] = 'users/login';
$route['users/profile'] = 'users/profile';

//Authentication
$route['login'] = 'login/do_login';
$route['logout'] = 'login/logout';
