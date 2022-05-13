<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
*/

//pages
$route['(:any)'] = 'pages/view/$1';

//reports
$route['reports/index'] = 'reports/index';

//produccion
$route['entries/create'] = 'entries/create';

//calidad
$route['entries/release/(:any)'] = 'entries/release/$1';
$route['entries/close/(:any)'] = 'entries/close/$1';
$route['entries/asign/(:any)'] = 'entries/asign/$1';

//forms
$route['forms/create'] = 'forms/create';


//users
$route['users/register'] = 'users/register';
$route['users/login'] = 'users/login';
$route['users/profile'] = 'users/profile';


$route['default_controller'] = 'pages/view';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
