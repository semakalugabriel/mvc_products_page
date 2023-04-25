<?php
/**
 * THE METHOD OF THIS CLASS
 * 1. get_controller_method
 *      This Method uses the PATH_INFO to get the uri
 *      It matches the URI to the defined routes in the app
 *      The route contains the controller and method to be called by the function
 */
!defined('BASE_URL') ? exit('direct access denied') : '';
class Loader{
    public function controller($class, $parameters = ''){
        $path = 'app/controller/'.$class.'.php';
        
        if(file_exists($path)){
            #include the view file as was called
            require_once $path;

            #return the instance of the class
            return new $class($parameters);
        }
        else{
            exit('Failed to Load Class ::'. $class);
        }
    }

    public function model($class, $parameters = ''){
        $path = 'app/model/'.$class.'.php';
        
        if(file_exists($path)){
            #include the view file as was called
            require_once $path;

            #return the instance of the class
            return new $class($parameters);
        }
        else{
            exit('Failed to Load Class ::'. $class);
        }
    }

    public function library($class, $parameters = ''){
        $path = 'app/library/'.$class.'.php';
        
        if(file_exists($path)){
            #include the view file as was called
            require_once $path;

            #return the instance of the class
            return new $class($parameters);
        }
        else{
            exit('Failed to Load Class ::'. $class);
        }
    }

    public function view($view, $data = array()){
        #file path for view
        $path = 'app/views/'.$view.'.php';

        #check if the view exists
        if(file_exists($path)){
            #extract data array into variables
            extract($data);

            #include the view file as was called
            include $path;
        }
        else{
            exit('Failed to Load View ::'. $view);
        }
    }
}