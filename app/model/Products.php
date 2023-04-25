<?php
!defined('BASE_URL') ? exit('direct access denied') : '';
/**
 * This class loads the JSON file on every request and provides the following methods:
 * 
 * 1. Save Product:
 *      - Checks if the product already exists and returns an error if so.
 *      - Generates a unique ID for every new product entered.
 * 
 * 2. Delete Product:
 *      - Uses the provided Product ID to remove the corresponding product from the file.
 * 
 * 3. Update Product:
 *      - Uses the provided Product ID to update the corresponding product with the provided data.
 */

class Products extends Base_model{
    #path to products file
    private $products_file;

    #keep track of total Products
    private $total_products;

    #keep track of last Id in products we'll use auto increment
    private $last_id;

    #this will have the products loaded
    private $products;

    public function __construct(){
        parent::__construct();
        #load path to defined products file
        $this->products_file = $this->config['data_file'];

        #call the load products method
        $this->_load_products();
    }

    #inserts a new product
    public function insert_product($product_data){
        #check if product already exists by name
        foreach ($this->products['products'] as $product) {
            if ($product['product_name'] == $product_data['product_name']) {
                return false;
            }
        }

        #create the new product array
        $new_product = array(
            'id' => ++$this->last_id,
            'product_name' => $product_data['product_name'],
            'quantity' => $product_data['quantity'],
            'price' => $product_data['price'],
            'date_submitted' => time(),
            'total_value' => $product_data['quantity'] * $product_data['price'],
            'last_updated'=> time()
        );

        #add the new product to the products array
        $this->products['products'][$new_product['id']] = $new_product;

        #update the total number of products
        $this->total_products++;

        #update the last id
        $this->products['last_id'] = $this->last_id;

        #update the total number of products
        $this->products['total_products'] = $this->total_products;

        #save the updated products array
        $this->_save_products();

        #return the new product
        return $new_product;
    }

    #updates an existing product
    public function update_product($product_id, $product_data){
        #check if the product exists
        if(isset($this->products['products'][$product_id])){
            #update the product data
            $this->products['products'][$product_id]['product_name'] = $product_data['product_name'];
            $this->products['products'][$product_id]['quantity'] = $product_data['quantity'];
            $this->products['products'][$product_id]['price'] = $product_data['price'];
            $this->products['products'][$product_id]['total_value'] = $product_data['quantity'] * $product_data['price'];

            #save the updated products array
            $this->_save_products();

            #return the updated product
            return $this->products['products'][$product_id];
        }

        #product not found
        return false;
    }

    #deletes an existing product
    public function delete_product($product_id){
        #check if the product exists
        if(isset($this->products['products'][$product_id])){
            #remove the product from the products array
            unset($this->products['products'][$product_id]);

            #update the total number of products
            $this->total_products--;

            #update the total number of products
            $this->products['total_products'] = $this->total_products;

            #save the updated products array
            $this->_save_products();

            #return true to indicate success
            return true;
        }

        #product not found
        return false;
    }

    #gets all existing products
    #default page is page 1 and the products set 100
    public function fetch_products($page = 1, $per_page = null) {
        #set per page to default if not provided in function
        $per_page = $per_page === null ? $this->config['products_per_page'] : $per_page;
        #Calculate the start index and end index of the products to fetch
        $start_index = ($page - 1) * $per_page;
        $end_index = $start_index + $per_page - 1;

        #Validate the start and end indexes
        if ($start_index >= $this->total_products) {
            #Start index is beyond the end of the products array
            return array(); 
        }
        if ($end_index >= $this->total_products) {
            #End index is beyond the end of the products array
            $end_index = $this->total_products - 1; 
        }

        #hold returned products
        $products = array();
        #Extract the products for the requested page
        for ($i = $start_index -1; $i < $end_index; $i++) {
            #we are using array values here so that when an index is delete it doesnt raise an null error
            $products[] = array_values($this->products['products'])[$i+1];
        }

        return $products;
    }

    #saves the products array 
    private function _save_products(){
        file_put_contents($this->products_file, json_encode($this->products));
    }    

    #loads the stored products into products property
    private function _load_products(){
        #create the file if not exits and load
        if(!file_exists($this->products_file)){
            #set the products array
            $this->products = array(
                'last_id' => 0,
                'total_products'=>0,
                'products'=> array()
            );

            #call save which will save an empty array
            $this->_save_products();
        }
        else{
            #set the products to the current products
            $this->products = json_decode(file_get_contents($this->products_file), true);

            #set the last id
            $this->last_id = $this->products['last_id'];

            #set the current number of products
            $this->total_products = $this->products['total_products'];
        }
    }

}