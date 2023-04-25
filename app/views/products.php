<!DOCTYPE html>
<html>
<head>
    <title>SEMAKALU GABRIEL PHP SKILLS TEST</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script>
        site_url = '<?php echo $config['site_url'];?>';
        currency_symbol = '<?php echo $config['currency_symbol']?>';
    </script>
</head>
<body>
    <div class="container">
        <div class="pt-6 pb-6" style ="margin-top:50px;">
            <div class="card">
                <div class="card-header">
                    <h4>PHP SKILLS TEST</h4>
                </div>
                <div class="card-body">
                    <p><b>Author : </b> SEMAKALU GABRIEL <br/>
                    <b>Email : </b> gabrielsemakalu@gmail.com <br/>
                    <b>Contact : </b> +256 761 287 549</p>
                    <p>
                        <b>Project Summary:</b><br/>
                        You can Create, Edit or Delete Products, refresh the table and cannot add duplicate products, has alerts for the save, update and delete and they are saved in a Json file
                        <br>
                        <br>
                        <b>What is Special?</b>
                        <br/>
                        I used Object Oriented Programming (OOP) and created a simple MVC (Model View Controller) framework with a simple validation library (from Scratch) to show my MVC skills, I added basic routing, and with a loader for Views Libraries and Models.
                        This structure makes the maintenance of this project easier and makes the project more scalable.

                        <b>Please Note:</b>
                        I could also implement this in any framework (Laravel, Zend, Codeigniter) but opted to write from scratch as that would demonstrate my understanding
                    </p>
            
                </div>
            </div>
        </div>
        <div class="card shadow-lg pt-6 pb-6" style ="margin-top:50px;">
            <div class = "card-header">
                <h4>Product Form</h4>
            </div>
            <div class="card-body">
                <form id="product-form">
                    <div class="alert d-none" id="alert_error"></div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="product-name">Product Name</label>
                            <input type="text" class="form-control" name="product_name" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="quantity-in-stock">Quantity in Stock</label>
                            <input type="number" class="form-control" name="quantity" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="price-per-item">Price per Item</label>
                            <input type="number" class="form-control" name="price" step = 0.001 required>
                        </div>
                        <div class="form-group col-md-12 mt-4 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card pt-6 pb-6" style ="margin-top:50px;">
            <div class = "card-header">
                <h4>Products Table <a href="#!" class="text-xs" id="refresh_table">Refresh</a></h4>
            </div>
            <div class="card-body">
                <table class="table p-0">
                    <thead>
                        <tr>
                            <th scope="col">Product Name</th>
                            <th scope="col">Quantity in Stock</th>
                            <th scope="col">Price per Item</th>
                            <th scope="col">Datetime Submitted</th>
                            <th scope="col">Total Value</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody id = 'table_body'>
                        <!--templace row code-->
                        <tr class="template_row d-none">
                            <td class="product_name">template</td>
                            <td class="quantity">0</td>
                            <td class="price">0</td>
                            <td class="date_submitted">0</td>
                            <td class="total_value">0</td>
                            <td>
                                <button class="btn btn-primary edit-btn">Edit</button>
                                <button class="btn btn-danger delete-btn">Delete</button>
                            </td>
                        </tr>
                        <tr class="sum_row d-none">
                            <td class="h4">Total</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="total_value h4">0</td>
                            <td></td>
                        </tr>
                        <!--templace-->
                    </tbody>
                </table> 
            </div>
        </div>
    </div>
</body>
<footer>
    <!-- Modal -->
    <div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edit_product_form">
                <div class="modal-body">
                        <div class="">
                            <div class="form-group">
                                <label for="edit-product-name">Product Name</label>
                                <input id ="edit_product_name" type="text" class="form-control" name="product_name" required>
                            </div>
                            <div class="form-group ">
                                <label for="edit-quantity-in-stock">Quantity in Stock</label>
                                <input id = "edit_quantity" type="number" class="form-control" name="quantity" required>
                            </div>
                            <div class="form-group">
                                <label for="edit-price-per-item">Price per Item</label>
                                <input id ="edit_price" type="number" class="form-control" name="price" step="0.001" required>
                            </div>
                            <input type="hidden" id ="edit_id" name="product_id" id="edit-product-id">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src = "<?php echo $config['base_url'];?>assets/index.js"></script>
</footer>
</html>