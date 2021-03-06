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


$route['home']['get'] = 'pages/home'; //this will server for quality and for production...
$route['pages/default']['post'] = 'pages/default_save'; //this will server for quality and for production...


$route['home/production']['get'] = 'pages/home_production';

//login?user_email=ejauregui@martechmedical.com&user_name=Emanuel&user_lastname=Jauregui&user_martech_number=10111&user_active=1&user_is_admin=1&user_level_name=Supervisor&user_level_value=2&from=http://localhost/martech_final_inspection/


//reports
$route['reports/index'] = 'reports/index';
$route['reports/calidad'] = 'reports/reporte_calidad';
$route['reports/produccion'] = 'reports/reporte_produccion';


$route['production/rejected_by_product'] = 'reports/rejected_by_product';
$route['production/rejected_by_document'] = 'reports/rejected_by_document';
$route['production/all_entries'] = 'reports/all_entries';
$route['production/all_rejected'] = 'reports/all_rejected';


$route['quality/rejected_by_document'] = 'reports/rejected_by_document';


$route['reports/detail/(:any)'] = 'reports/detail/$1';
$route['reports/detail_accepted/(:any)'] = 'reports/detail_accepted/$1';
$route['reports/delete_entry']['post'] = 'reports/delete_entry';

//produccion
$route['entries/create'] = 'entries/create';
$route['entries/rework']['get'] = 'entries/rework';
$route['entries/rework']['post'] = 'entries/rework_save';

$route['entries/solved']['get'] = 'entries/solved';
$route['entries/solved']['post'] = 'entries/solved_save';

//calidad
$route['entries/releavisu/(:any)']['get'] = 'entries/release/$1';
$route['entries/release/(:any)']['post'] = 'entries/release_save/$1';


$route['entries/close/(:any)'] = 'entries/close/$1';
$route['entries/assign/(:any)'] = 'entries/assign/$1';
$route['entries/reassign/(:any)'] = 'entries/reassign/$1';

$route['entries/all-opened']['get'] = 'entries/api_entries_opened';
$route['entries/all-closed']['get'] = 'entries/api_entries_closed';
$route['entries/all-rejected']['get'] = 'entries/api_entries_rejected';

$route['entries/rejected-by-product']['get'] = 'entries/api_entries_rejected_by_product';
$route['entries/rejected-by-document']['get'] = 'entries/api_entries_rejected_by_document';
$route['entries/quality-all']['get'] = 'entries/api_entries_quality_all';
$route['entries/quality-rejected']['get'] = 'entries/api_entries_quality_rejected';

//forms
$route['forms/create'] = 'forms/create';

//users
$route['users/register'] = 'users/register';
$route['users/login'] = 'users/login';
$route['users/profile'] = 'users/profile';

//Authentication
$route['login'] = 'login/do_login';
$route['logout'] = 'login/logout';
