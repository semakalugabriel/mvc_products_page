<?php
!defined('BASE_URL') ? exit('direct access denied') : '';
class App extends Base_controller{
    public function __construct(){
        parent::__construct();

        #use loader to load the products model into this class
        $this->products_model = $this->load->model('Products');
    }
    
    public function index(){
        $data = array(
            'title' => 'Products Page',
            'products' => array(), #change this to actual products
            'config' => $this->config
        );
        $this->load->view('products', $data);
    }

    public function get_products(){
        #get products from the products model
        $this->json_output(
            #fetch the products
            $this->products_model->fetch_products()
        );
    }    

    public function save_product(){
        #set the fields and validation rules
        $fields = array(
            'product_name' => 'required|no_html',
            'quantity'=> 'required|numeric',
            'price' => 'required|numeric'
        );

        #get the data from the validation
        $data = $this->input->validate_input($fields);
        
        #return error if any or save product data
        if(count($data['errors']) > 0){
            return $this->json_output(array(
                'status' => 0,
                'message' => implode(',', $data['errors'])
            ));
        }
        else{
            #there is no error so save the product
            $result = $this->products_model->insert_product($data['data']);

            #if result is not false return
            if($result == false){
                $this->json_output(array(
                    'status'=> 0,
                    'message' => 'Product Already exists'
                ));
            }  
            else{
                $this->json_output(array(
                    'status'=> 1,
                    'message' => 'Product Added Successfully',
                    'product' => $result
                ));
            }
        }
    }

    public function update_product(){
        #set the fields and validation rules
        $fields = array(
            'product_name' => 'required|no_html',
            'quantity'=> 'required|numeric',
            'price' => 'required|numeric',
            'product_id' => 'required|numeric'
        );

        #get the data from the validation
        $data = $this->input->validate_input($fields);
        
        #return error if any or save product data
        if(count($data['errors']) > 0){
            $this->json_output(array(
                'status' => 0,
                'message' => implode(',', $data['errors'])
            ));
        }
        else{
            #there is no error so save the product
            $result = $this->products_model->update_product($data['data']['product_id'], $data['data']);

            #if result is not false return
            if($result === false){
                $this->json_output(array(
                    'status'=> 0,
                    'message' => 'Product Does Not exist'
                ));
            }  
            else{
                $this->json_output(array(
                    'status'=> 1,
                    'message' => 'Product Updated Successfully',
                ));
            }
        }
    }

    public function delete_product(){
        #set the fields and validation rules
        $fields = array(
            'product_id' => 'required|numeric'
        );

        #get the data from the validation
        $data = $this->input->validate_input($fields);
        
        #return error if any or save product data
        if(count($data['errors']) > 0){
            $this->json_output(array(
                'status' => 0,
                'message' => implode(',', $data['errors'])
            ));
        }
        else{
            #there is no error so save the product
            $result = $this->products_model->delete_product($data['data']['product_id']);

            #if result is not false return
            if($result === false){
                $this->json_output(array(
                    'status'=> 0,
                    'message' => 'Product Does Not exist'
                ));
            }  
            else{
                $this->json_output(array(
                    'status'=> 1,
                    'message' => 'Product Deleted Successfully',
                ));
            }
        }
    }

    public function error_404(){
        echo '<h1>Opps, Page not Found</h1>';
    }

    private function json_output($json){
        #let the output have json headers
        header('Content-Type: application/json');
        echo json_encode($json);
    }
}