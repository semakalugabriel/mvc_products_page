<?php 
!defined('BASE_URL') ? exit('direct access denied') : '';
/*
    Expected App Input Fields
        product_name; Product name
        quantity; Quantity in Stock
        price; price per item

    Field Validation Rules
        1. numeric
            field value must be numerical in nature
        2. no_html
            removes html tags from the field data
        3. htmlspecialchars
            applies the built in special chars
        4. required
            ensures the field is provided in input

    Class Methods
        1. post_input 
            This method accepts required input fields as an array
            It checks the global post for the required fields
            It validate the fields and returns all or non if error
        
        2. validate_field
            This method accepts the data from the input and matches against the provided rules
            If the field data matches the rules, it returns the data else returns null
*/
class Validate{
    private $error_messages = array(
        'required' => ' is required',
        'numeric'  => ' must me numeric in nature'
    );

    public function validate_input($fields) {
        $post = array();
        $errors = array();
        
        #loop through all fields and run rules
        foreach($fields as $field => $rules) {
            #call the validate field method to check the input
            $result = $this->_validate_field($field, $rules);

            #the resutls status 1,2, or 0
            #in the event that status is 2 we do nothing, field is absent and not required
            $status = $result['status'];

            #check the status and act
            if($status === 1){
                #set the post field data
                $post[$field] = $result['processed'];
            }
            elseif($status === 0){
                #field failed the rules so now we gotta show set a message
                $errors[] = 'Field ('.$field.') '.$this->error_messages[$result['rule']];
            }
        }
        
        #if there are errors
        return array(
            'errors' => $errors,
            'data' => $post
        );
    }

    private function _validate_field($field, $rules){
        #make a rules array
        $rules = explode('|', $rules);

        #this will be the returned array for the fiel
        $return = array(
            'processed' => null,
            'rule'=> null, 
            'status' => 0 
        );

        #check if the field is required and set the data
        if(!isset($_POST[$field])){
            #if the fied is required set the violated rule to required
            if(in_array('required', $rules)){
                $return['rule'] = 'required';
            }

            #otherwise set the status to 2
            #This shows the field is absent and not required
            $return['status'] = 2;
            
            return $return;
        }

        #the data is in the post global
        $data = $_POST[$field];

        #A single field can have multiple rules that must all be passed so we apply the rules via loop
        foreach ($rules as $key => $rule) {

            $out = $this->_apply_rule($rule, $data);
            $return['rule'] = $rule;

            if($out === null){
                $return['status'] = 0;
                break;
            }
            else{
                $return['status'] = 1;
            }

            $return['processed'] = $out;
        }

        #return the whole array
        return $return;
    }

    private function _apply_rule($rule, $data){
        #this method will apply the individual rule and return null if the rule is not passed
        $output = '';
        switch ($rule) {
            case 'no_html':
                $output =  htmlspecialchars(strip_tags($data));
                break;

            case 'numeric':
                $output = is_numeric($data) ? $data : null;
                break;
            default:
                $output = $data;
                break;
        }

        #check that we do not get an empty string 
        if(empty($output)){
            $output = null;
        }

        return $output;
    }
}