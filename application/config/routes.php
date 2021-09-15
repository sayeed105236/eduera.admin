<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
 */
$route['default_controller'] = 'home';
$route['courses'] = 'home/courses';
// $route['users'] = 'home/users';
$route['categories'] = 'home/categories';
$route['instructors'] = 'home/instructors';
$route['users/(:any)'] = 'users/index/$1';
$route['home/createexcel'] = 'home/createexcel';
$route['users/(:num)/info'] = 'users/info/$1';
$route['users/(:num)/user_role'] = 'users/user_role/$1';
$route['users/(:num)/user_enrollment'] = 'users/user_enrollment/$1';
$route['users/(:num)/user_certificate'] = 'users/user_certificate/$1';

$route['users/(:num)/user_enroll_make_payment'] = 'users/user_enroll_make_payment/$1';
$route['users/(:num)/instructor_enrollment'] = 'users/instructor_enrollment/$1';
$route['users/(:num)/instructor_payment'] = 'users/instructor_payment/$1';
$route['users/(:num)/status_monitoring'] = 'users/status_monitoring/$1';

$route['404_override'] = 'home/page_not_found';
$route['translate_uri_dashes'] = FALSE;
