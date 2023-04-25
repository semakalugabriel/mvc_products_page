<?php
/**
 * ABOUT THE APP
 * This is a simple MCV structured app with OOP used
 * This index.php is the entry point, 
 * It loads the config, loader and base Class
 * The loader class will use url parameters to call controller methods
 */

#Get the current URL
#This is only necessary because instructions indication that the must work without configurationr
$current_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

#Split the URL by 'index.php'
$url_parts = explode('index.php', $current_url);

#Take the first part as the base URL
$base_url = $url_parts[0];

#define the app roo / base_url
define('BASE_URL', $base_url);

#load the configuration file
require_once 'app/config.php';

#require the autoloader class to load classes and views
require_once 'app/loader.php';

#require the base class will be extented by other classes
require_once 'app/Base.php';

#exit($config['base_url']);

#instantiate the app
$base = new Base_class();

#call array : this is an array with the controller and method to call
$call_array = $base->controller_method();

/**
 * AT THIS POINT WE HAVE THE CONTROLLER BEING CALLED AND THE METHOD IN QUESTION
 * IN THE FUTURE WE CAN USE THIS POINT TO INTERCEPT METHOD CALLS
*/

#call run the request now 
$base->run_request($call_array);

