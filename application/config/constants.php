<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') or define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  or define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') or define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   or define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  or define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           or define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     or define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       or define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  or define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   or define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              or define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            or define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       or define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        or define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          or define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         or define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   or define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  or define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') or define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     or define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       or define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      or define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      or define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code



/*
 * CONNFIGURE SERVER INSTALLATION...
*/
defined('SERVER_PATH_URL')      or define('SERVER_PATH_URL', 'http://localhost/');
//defined('SERVER_PATH_URL')      or define('SERVER_PATH_URL', 'http://mxmtsvrandon1/');


defined('LOGIN_URL')      or define('LOGIN_URL',  SERVER_PATH_URL . 'authentication/index.php/login?from=' . SERVER_PATH_URL . 'martech_final_inspection');

defined('IS_LOGGED_IN')      or define('IS_LOGGED_IN', 'final_inspection_logged_in');

defined('PRODUCTION_USER')       or define('PRODUCTION_USER', 1);
defined('QUALITY_USER')       or define('QUALITY_USER', 2);
defined('USER_TYPE')      or define('USER_TYPE', 'final_inspection_user_type'); //PRODUCTION OR QUALITY

defined('NAME')      or define('NAME', 'final_inspection_name');
defined('LASTNAME')      or define('LASTNAME', 'final_inspection_lastname');
defined('EMAIL')      or define('EMAIL', 'final_inspection_email');
defined('MARTECH_NUMBER')      or define('MARTECH_NUMBER', 'final_inspection_martech_number');
defined('LEVEL_NAME')      or define('LEVEL_NAME', 'final_inspection_level_name');
defined('LEVEL_VALUE')      or define('LEVEL_VALUE', 'final_inspection_level_value');
defined('DEPARTMENT_NAME')      or define('DEPARTMENT_NAME', 'final_inspection_department_name');
defined('DEPARTMENT_ID')      or define('DEPARTMENT_ID', 'final_inspection_department_id');



defined('PROGRESS_NOT_ASSIGNED')      or define('PROGRESS_NOT_ASSIGNED', 0);
defined('PROGRESS_ASSIGNED')      or define('PROGRESS_ASSIGNED', 1);
defined('PROGRESS_RELEASED')      or define('PROGRESS_RELEASED', 2);
defined('PROGRESS_CLOSED')      or define('PROGRESS_CLOSED', 3);
