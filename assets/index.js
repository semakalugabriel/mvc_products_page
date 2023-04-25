$(document).ready(function(){
    function handle_response(resp){
        //expects json response from API calls 
        //response has a standard format with status and message
        if(resp.status === 1){
            //show success alert
            $('#alert_error').text(resp.message).addClass('alert-success').removeClass('d-none');
        }
        else{
            //show error message
            $('#alert_error').text(resp.message).addClass('alert-danger').removeClass('d-none');
        }

        console.log('Calling Populate');
        //repopulate the table
        populateTable();

        setTimeout(function() {
            $('#alert_error').addClass('d-none');
        }, 5000);  
    }

    function alert_error(error){
        //log the error
        console.log("Error: " + error.statusText);
    }
    
    $('#product-form').submit(function(e){
        //prevent event default
        e.preventDefault();

        //get the form data
        var form_data = $(this).serialize();

        //now send the form data to php for processing
        $.ajax({
            url: site_url + 'save',
            method: 'POST',
            data: form_data,
            success: function(resp){
                //handle response
                handle_response(resp);
            },
            error:function(error){
                //call the general error function
                alert_error(error);
            }
        });
    });

    $('#edit_product_form').submit(function(e){
        //prevent event default
        e.preventDefault();

        //get the form data
        var form_data = $(this).serialize();

        //now send the form data to php for processing
        $.ajax({
            url: site_url + 'update',
            method: 'POST',
            data: form_data,
            success: function(resp){
                //handle response
                handle_response(resp);
                //show the modal
                $('#edit_modal').modal('hide');
            },
            error:function(error){
                //call the general error function
                alert_error(error);
            }
        });
    });

    // Get the table body element
    const $tableBody = $('#table_body');

    //get the template row
    const $template_row = $('.template_row').removeClass('template_row d-none');
    const $sum_row = $('.sum_row').removeClass('d-none sum_row');
    
    // Function to populate table rows with product data
    function populateTable() {
        // Get products data from API
        $.ajax({
        url: site_url + 'products',
        type: 'GET',
        success: function(data) {
            //inititate the total Value
            let total_value = 0;

            // Clear current table rows exept template row
            $tableBody.find('tr').remove();

            // Loop through products and add rows to table
            $.each(data, function(key, product) {
                //increment the total value
                total_value += product.total_value;

                // Clone template_row and remove 'template_row' class
                const $row = $template_row.clone();

                // Populate row cells with product data
                $row.find('.product_name').text(product.product_name);
                $row.find('.quantity').text(product.quantity);
                $row.find('.price').text(currency_symbol + product.price);
                $row.find('.date_submitted').text(new Date(product.date_submitted * 1000).toLocaleString());
                $row.find('.total_value').text(currency_symbol + product.total_value);

                // Add row to table body
                $tableBody.append($row);
                
                // Add event listener for delete button
                $row.find('.delete-btn').on('click', function() {
                    //call delete ajax and remove the row
                    $.ajax({
                        url: site_url + 'delete',
                        method: 'POST',
                        data:{
                            product_id:product.id
                        },
                        success:function(resp){
                            //handle response
                            handle_response(resp);
                        },
                        error:function(){
                            //call the general error function
                            alert_error(error);
                        }
                    });
                });
                
                // Add event listener for edit button
                $row.find('.edit-btn').on('click', function() {
                    // Get input values from product row
                    const productName = product.product_name;
                    const quantity = product.quantity;
                    const price = product.price;
                    const product_id = product.id

                    // Set input values in edit form
                    $('#edit_product_name').val(productName);
                    $('#edit_quantity').val(quantity);
                    $('#edit_price').val(price);
                    $('#edit_id').val(product_id);

                    // Show edit modal
                    $('#edit_modal').modal('show');

                    //user can now submit edit form for a row
                });
            });

            //let us now populate the total value into the table
            $sum_row.find('.total_value').text(currency_symbol + total_value);
            $tableBody.append($sum_row);
        }
        });
    }

    // Populate table with initial data
    populateTable();
    
    // Add event listener for refresh button
    $('#refresh_table').on('click', function() {
        populateTable();
    });
});