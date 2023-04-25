<?php
!defined('BASE_URL') ? exit('direct access denied') : '';
class Base_class{
    public $config;
    public $load;
    public $input;
    
    public function __construct(){
        global $config;
        $this->config = $config;
        $this->load = new Loader();

        #let us autoload the validation library
        $this->input = $this->load->library('Validate');
    }

    public function site_url($path){
        return 'index.php/'.$path;
    }

    public function controller_method($path_info = ''){
        if($path_info === ''){
            #get the path info use the routes to get its corresponding controller method
            $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : null;
        }
        
        #trim the slashes of the uri
        $path_info = trim($path_info, '/');

        if($path_info == null){
            #set to and return the defaul controller and method
            return array(
                'controller' => $this->config['default_controller'],
                'method' => $this->config['default_method']
            );
        }
        elseif(isset($this->config['routes'][$path_info])){
            #the route is set so get the controller and method
            $c_method = explode('/', $this->config['routes'][$path_info]);

            #explode the route pair and return the array
            return array(
                'controller' => $c_method[0],
                'method'=> $c_method[1]
            );
        }
        else{
            #if we have a 404 page set it
            if(isset($this->config['routes']['404_path'])){
               return $this->controller_method('404_path');
            }
        }
    }

    public function run_request($call_arr){
        #instantiate the Controller
        $controller = $this->load->controller($call_arr['controller']);
        #call the controller method in Question
        $controller->{$call_arr['method']}();
    }
}

class Base_controller extends Base_class{
    public function __construct(){
        parent::__construct();
    }
}

class Base_model extends Base_class{
    public function __construct(){
        parent::__construct();
    }
}