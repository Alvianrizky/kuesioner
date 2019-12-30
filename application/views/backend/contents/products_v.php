<style type="text/css">
    #form-data {
        display: none;
    }
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo isset($page_header) ? $page_header : '';?>
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"> <?php echo isset($breadcrumb) ? $breadcrumb : ''; ?></li>
    </ol>
</section> 


<!-- Main content -->
<section class="content">
<div id="notifications"></div>

<div class="row">
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"> </h3>
            </div><!-- /.box-header -->

        <div id="table-data">
            <div class="box-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-6">
                            <button id="create" class="btn btn-primary btn-sm" title="Data Create" alt="Data Create" ><i class="glyphicon glyphicon-plus"></i> Add Product</button> 
                        </div>  
                    </div>
                </div>  
                <div class="table-responsive" id="table-responsive">
               <table id="table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 100px!important;">Action</th>
                            <th>No</th>                            
                            <th>ProductName</th>
                            <th>SupplierID</th>
                            <th>CategoryID</th>
                            <th>QuantityPerUnit</th>
                            <th>UnitPrice</th>
                            <th>UnitsInStock</th>
                            <th>UnitsOnOrder</th>
                            <th>ReorderLevel</th>
                            <th>Discontinued</th>       
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>

                    <tfoot>
                        <tr>
                            <th>Action</th>
                            <th>No</th>                            
                            <th>ProductName</th>
                            <th>SupplierID</th>
                            <th>CategoryID</th>
                            <th>QuantityPerUnit</th>
                            <th>UnitPrice</th>
                            <th>UnitsInStock</th>
                            <th>UnitsOnOrder</th>
                            <th>ReorderLevel</th>
                            <th>Discontinued</th>                          
                        </tr>
                    </tfoot>
                </table>
                </div>           
            </div>
        </div>
        
        <form role="form" method="POST" action="" id="form-data" enctype="multipart/form-data" > 
            <div class="box-body">
                <div class="row">
                <div class="col-lg-6">
                    <div id="hidden"></div>
                    <div id="js-config"></div>
                    <div class="form-group">
                        <label>Product Name</label>
                        <div id="ProductName"></div>
                    </div>
                    <div class="form-group">
                        <label>Supplier ID</label>
                        <div id="SupplierID"></div>
                    </div>
                    <div class="form-group">
                        <label>Category ID</label>
                        <div id="CategoryID"></div>
                    </div>
                    <div class="form-group">
                        <label>QuantityPerUnit</label>
                        <div id="QuantityPerUnit"></div>
                    </div>
                    <div class="form-group">
                        <label>UnitPrice</label>
                        <div id="UnitPrice"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>UnitsInStock</label>
                        <div id="UnitsInStock"></div>
                    </div>
                    <div class="form-group">
                        <label>UnitsOnOrder</label>
                        <div id="UnitsOnOrder"></div>
                    </div>
                    <div class="form-group">
                        <label>Discontinued</label>
                        <div id="Discontinued"></div>                       
                    </div>
                    <div class="form-group">
                        <label>ReorderLevel</label>
                        <div id="ReorderLevel"></div>
                    </div>
                </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="button" name="submit" id="submit" class="btn btn-primary">Submit Data</button> &nbsp; &nbsp; 
                <button type="reset" name="reset" class="btn btn-default">Reset Data</button>

                <button type="button" name="back" class="btn btn-primary pull-right" onClick="table_data();">Back Button</button>
            </div>
        </form>

        <form role="form" method="POST" action="" id="form-view"> 
            <div class="box-body">
                <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Product Name</label>
                        <p id="ProductName"></p>
                    </div>
                    <div class="form-group">
                        <label>Supplier ID</label>
                        <p id="SupplierID"></p>
                    </div>
                    <div class="form-group">
                        <label>Category ID</label>
                        <p id="CategoryID"></p>
                    </div>
                    <div class="form-group">
                        <label>QuantityPerUnit</label>
                        <p id="QuantityPerUnit"></p>
                    </div>
                    <div class="form-group">
                        <label>UnitPrice</label>
                        <p id="UnitPrice"></p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>UnitsInStock</label>
                        <p id="UnitsInStock"></p>
                    </div>
                    <div class="form-group">
                        <label>UnitsOnOrder</label>
                        <p id="UnitsOnOrder"></p>
                    </div>
                    <div class="form-group">
                        <label>Discontinued</label>
                        <p id="Discontinued"></p>                       
                    </div>
                    <div class="form-group">
                        <label>ReorderLevel</label>
                        <p id="ReorderLevel"></p>
                    </div>
                </div>
                </div>
            </div>
            <div class="box-footer"><button type="button" name="back" class="btn btn-primary pull-right" onClick="table_data();">Back Button</button></div>
        </form>

        </div><!-- /.box -->
    </div><!--/.col (right) -->
</div>   <!-- /.row -->


</section><!-- /.content -->


<script type="text/javascript">
    var site_url = site_url() + 'products/';

    var table;
    $(document).ready(function() {

        table_data();

        table = $('#table').DataTable({ 

            "processing": true, 
            "serverSide": true, 
            "order": [], 
            
            "ajax": {
                "url": site_url + 'get_products',
                "type": "POST"
            },
            
            "columnDefs": [
            { 
                "targets": [ 0 ], 
                "orderable": false, 
            },
            ],
        });

        $('#create').click(function() {
            $.ajax({
                url: site_url + 'form_data/',
                cache: false,
                type: "POST",
                dataType:"json",
                success: function(data){
                    $(".chosen-select").chosen("destroy");
                    form_data();
                    $('.box-title').text('Create Product');  

                    //data = JSON.parse(data);
                    $('#hidden').html(data.hidden);
                    $('#js-config').html(data.jsConfig);
                    $('#ProductName').html(data.ProductName);
                    $('#SupplierID').html(data.SupplierID);
                    $('#CategoryID').html(data.CategoryID);
                    $('#QuantityPerUnit').html(data.QuantityPerUnit);
                    $('#UnitPrice').html(data.UnitPrice);
                    $('#UnitsInStock').html(data.UnitsInStock);
                    $('#UnitsOnOrder').html(data.UnitsOnOrder);
                    $('#Discontinued').html(data.Discontinued); 
                    $('#ReorderLevel').html(data.ReorderLevel); 

                    $(".chosen-select").chosen();
                }
            });
        });

        $('#submit').click(function() {
            $.ajax({
                url : site_url + 'save_product/',
                type: "POST",
                data: new FormData($('#form-data')[0]),
                dataType: "JSON",
                contentType: false, 
                cache: false,             
                processData:false,
                success: function(data)
                {
                    if(data.code == 1){
                        $('#notifications').append(data.message);
                    }
                    else{
                        $('#notifications').append(data.message);                        
                        table_data();
                        table.draw(false);
                    }  
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error adding / update data');
                }
            });
        });

    });

    function table_data()
    {    
        $('#table-data').show();
        $('#form-data').hide();
        $('#form-view').hide();

        $('.box-title').text('Product List'); 
    }

    function form_data()
    {
        $('#hidden').empty();
        $('#ProductName').empty();
        $('#SupplierID').empty();
        $('#CategoryID').empty();
        $('#QuantityPerUnit').empty();
        $('#UnitPrice').empty();
        $('#UnitsInStock').empty();
        $('#UnitsOnOrder').empty();
        $('#Discontinued').empty(); 
        $('#ReorderLevel').empty(); 

        $('#table-data').hide();
        $('#form-data').show();
        $('#form-view').hide();
    }

    function form_view()
    {
        $('p#hidden').empty();
        $('p#ProductName').empty();
        $('p#SupplierID').empty();
        $('p#CategoryID').empty();
        $('p#QuantityPerUnit').empty();
        $('p#UnitPrice').empty();
        $('p#UnitsInStock').empty();
        $('p#UnitsOnOrder').empty();
        $('p#Discontinued').empty(); 
        $('p#ReorderLevel').empty();

        $('#table-data').hide();
        $('#form-data').hide();
        $('#form-view').show();

        $('.box-title').text('View Product'); 
    }

    function view_data(id)
    {
         $.ajax({
            url: site_url + 'view/',
            data: {'ProductID': id},
            cache: false,
            type: "POST",
            success: function(data){
                form_view();

                data = JSON.parse(data);
                $('p#hidden').html(data.hidden);
                $('p#ProductName').html(data.ProductName);
                $('p#SupplierID').html(data.SupplierID);
                $('p#CategoryID').html(data.CategoryID);
                $('p#QuantityPerUnit').html(data.QuantityPerUnit);
                $('p#UnitPrice').html(data.UnitPrice);
                $('p#UnitsInStock').html(data.UnitsInStock);
                $('p#UnitsOnOrder').html(data.UnitsOnOrder);
                $('p#Discontinued').html(data.Discontinued); 
                $('p#ReorderLevel').html(data.ReorderLevel);  
            }
        }); 
    }

    function update_data(id)
    {
         $.ajax({
            url: site_url + 'form_data/',
            data: {'ProductID': id},
            cache: false,
            type: "POST",
            success: function(data){
                $(".chosen-select").chosen("destroy");
                form_data();
                $('.box-title').text('Update Product'); 

                data = JSON.parse(data);
                $('#hidden').html(data.hidden);
                $('#ProductName').html(data.ProductName);
                $('#SupplierID').html(data.SupplierID);
                $('#CategoryID').html(data.CategoryID);
                $('#QuantityPerUnit').html(data.QuantityPerUnit);
                $('#UnitPrice').html(data.UnitPrice);
                $('#UnitsInStock').html(data.UnitsInStock);
                $('#UnitsOnOrder').html(data.UnitsOnOrder);
                $('#Discontinued').html(data.Discontinued); 
                $('#ReorderLevel').html(data.ReorderLevel); 
                $(".chosen-select").chosen();                  
            }
        });
    }

    function delete_data(id)
    {
        var agree = confirm("Are you sure you want to delete this item?");
        if (agree){
            $.ajax({
                url: site_url + 'delete/',
                data: {'ProductID':id},
                cache: false,
                type: "POST",
                dataType: "JSON", //Tidak Usah Memakai JSON.parse(data);
                success: function(data){
                    $('#notifications').append(data.message);
                    if(data.code == 0) table.draw(false);
                    table_data();
                }   
            });
        }            
        else
            return false ;
    }

</script>