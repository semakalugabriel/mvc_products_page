<?php 
!defined('BASE_URL') ? exit('direct access denied') : '';
#set config as a global variable
global $config;
#products per page
$config['products_per_page'] = 100;
$config['currency_symbol'] = '$';

#set the base url of the project
$config['base_url'] = BASE_URL;
$config['site_url'] = BASE_URL.'index.php/';

#set some config parameters
$config['data_file'] = 'data/products.json';
$config['default_controller'] = 'App';
$config['default_method'] = 'index';

#application roues
$config['routes']['save'] = 'app/save_product';
$config['routes']['update'] = 'app/update_product';
$config['routes']['products'] = 'app/get_products';
$config['routes']['delete'] = 'app/delete_product';

#the 404 path
$config['routes']['404_path'] = 'app/error_404';