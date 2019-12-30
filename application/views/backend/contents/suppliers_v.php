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
                <h3 class="box-title"><?php echo isset($panel_heading) ? $panel_heading : '';?> </h3>
            </div><!-- /.box-header -->

        <div id="table-data">
            <div class="box-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-6">
                            <button id="create" class="btn btn-primary btn-sm" title="Data Create" alt="Data Create" ><i class="glyphicon glyphicon-plus"></i> Add Supplier</button> 
                        </div>  
                    </div>
                </div>  
                <div class="table-responsive" id="table-responsive">
               <table id="table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 100px!important;">Action</th>
                            <th>No</th>                            
                            <th>CompanyName</th>
                            <th>ContactName</th>
                            <th>ContactTitle</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>Region</th>
                            <th>PostalCode</th>
                            <th>Country</th>
                            <th>Phone</th>
                            <th>Fax</th>
                            <th>HomePage</th>        
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>

                    <tfoot>
                        <tr>
                            <th>Action</th>
                            <th>No</th>                            
                            <th>CompanyName</th>
                            <th>ContactName</th>
                            <th>ContactTitle</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>Region</th>
                            <th>PostalCode</th>
                            <th>Country</th>
                            <th>Phone</th>
                            <th>Fax</th> 
                            <th>HomePage</th>                          
                        </tr>
                    </tfoot>
                </table>
                </div>           
            </div>
        </div>
        
        <form role="form" method="POST" action="" id="form-data" enctype="multipart/form-data"> 
            <div class="box-body">
                <div class="row">
                <div class="col-lg-6">
                    <div id="hidden"></div>
                    <div class="form-group">
                        <label>Company Name</label>
                        <div id="CompanyName"></div>
                    </div>
                    <div class="form-group">
                        <label>Contact Name</label>
                        <div id="ContactName"></div>
                    </div>
                    <div class="form-group">
                        <label>Contact Title</label>
                        <div id="ContactTitle"></div>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <div id="Address"></div>
                    </div>
                    <div class="form-group">
                        <label>City</label>
                        <div id="City"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Region</label>
                        <div id="Region"></div>
                    </div>
                    <div class="form-group">
                        <label>PostalCode</label>
                        <div id="PostalCode"></div>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <div id="Phone"></div>                       
                    </div>
                    <div class="form-group">
                        <label>Fax</label>
                        <div id="Fax"></div>
                    </div>
                    <div class="form-group">
                        <label>Country</label>
                        <div id="Country"></div>
                    </div>
                    <div class="form-group">
                        <label>HomePage</label>
                        <div id="HomePage"></div>
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
                        <label>Company Name</label>
                        <p id="CompanyName"></p>
                    </div>
                    <div class="form-group">
                        <label>Contact Name</label>
                        <p id="ContactName"></p>
                    </div>
                    <div class="form-group">
                        <label>Contact Title</label>
                        <p id="ContactTitle"></p>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <p id="Address"></p>
                    </div>
                    <div class="form-group">
                        <label>City</label>
                        <p id="City"></p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Region</label>
                        <p id="Region"></p>
                    </div>
                    <div class="form-group">
                        <label>PostalCode</label>
                        <p id="PostalCode"></p>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <p id="Phone"></p>                       
                    </div>
                    <div class="form-group">
                        <label>Fax</label>
                        <p id="Fax"></p>
                    </div>
                    <div class="form-group">
                        <label>Country</label>
                        <p id="Country"></p>
                    </div>
                    <div class="form-group">
                        <label>HomePage</label>
                        <p id="HomePage"></p>
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
    var site_url = site_url() + 'suppliers/';

    var table;
    $(document).ready(function() {

        table_data();

        table = $('#table').DataTable({ 

            "processing": true, 
            "serverSide": true, 
            "order": [], 
            
            "ajax": {
                "url": site_url + 'get_suppliers',
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
            $.post(site_url + 'form_data/', function(data) {
                form_data();
                $('.box-title').text('Create Supplier');  

                data = JSON.parse(data);
                $('#hidden').html(data.hidden);
                $('#CompanyName').html(data.CompanyName);
                $('#ContactName').html(data.ContactName);
                $('#ContactTitle').html(data.ContactTitle);
                $('#Address').html(data.Address);
                $('#City').html(data.City);
                $('#Region').html(data.Region);
                $('#PostalCode').html(data.PostalCode);
                $('#Phone').html(data.Phone); 
                $('#Fax').html(data.Fax);
                $('#Country').html(data.Country);
                $('#HomePage').html(data.HomePage);   
            });
        });

        $('#submit').click(function() {
            $.post(site_url + 'save_shipper/', $('#form-data').serialize(), function(data) {
                if(data.code == 1){
                    $('#notifications').append(data.message);
                }
                else{
                    $('#notifications').append(data.message);                        
                    table_data();
                    table.draw(false);
                }    
            }, 'json')
            .fail(function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
            });
        });

    });

    function table_data()
    {    
        $('#table-data').show();
        $('#form-data').hide();
        $('#form-view').hide();

        $('.box-title').text('Supplier List'); 
    }

    function form_data()
    {
        $('#hidden').empty();
        $('#CompanyName').empty();
        $('#ContactName').empty();
        $('#ContactTitle').empty();
        $('#Address').empty();
        $('#City').empty();
        $('#Region').empty();
        $('#PostalCode').empty();
        $('#Phone').empty(); 
        $('#Fax').empty();
        $('#Country').empty();
        $('#HomePage').empty(); 

        $('#table-data').hide();
        $('#form-data').show();
        $('#form-view').hide();
    }

    function form_view()
    {
        $('p#hidden').empty();
        $('p#CompanyName').empty();
        $('p#ContactName').empty();
        $('p#ContactTitle').empty();
        $('p#Address').empty();
        $('p#City').empty();
        $('p#Region').empty();
        $('p#PostalCode').empty();
        $('p#Phone').empty(); 
        $('p#Fax').empty();
        $('p#Country').empty();
        $('p#HomePage').empty(); 

        $('#table-data').hide();
        $('#form-data').hide();
        $('#form-view').show();

        $('.box-title').text('View Supplier'); 
    }

    function view_data(id)
    { 
        $.post(site_url + 'view/', {'SupplierID': id}, function(data) {
            form_view();

            data = JSON.parse(data);
            $('p#hidden').html(data.hidden);
            $('p#CompanyName').html(data.CompanyName);
            $('p#ContactName').html(data.ContactName);
            $('p#ContactTitle').html(data.ContactTitle);
            $('p#Address').html(data.Address);
            $('p#City').html(data.City);
            $('p#Region').html(data.Region);
            $('p#PostalCode').html(data.PostalCode);
            $('p#Phone').html(data.Phone); 
            $('p#Fax').html(data.Fax);
            $('p#Country').html(data.Country);
            $('p#HomePage').html(data.HomePage);    
        })
        .fail(function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
        });
    }

    function update_data(id)
    {   
        $.post(site_url + 'form_data/', {'SupplierID': id}, function(data) {
            form_data();
            $('.box-title').text('Update Supplier'); 

            data = JSON.parse(data);
            $('#hidden').html(data.hidden);
            $('#CompanyName').html(data.CompanyName);
            $('#ContactName').html(data.ContactName);
            $('#ContactTitle').html(data.ContactTitle);
            $('#Address').html(data.Address);
            $('#City').html(data.City);
            $('#Region').html(data.Region);
            $('#PostalCode').html(data.PostalCode);
            $('#Phone').html(data.Phone); 
            $('#Fax').html(data.Fax);
            $('#Country').html(data.Country);
            $('#HomePage').html(data.HomePage);   
        })
        .fail(function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
        });
    }

    function delete_data(id)
    {
        var agree = confirm("Are you sure you want to delete this item?");
        if (agree){
            $.post(site_url + 'delete/', {'SupplierID': id}, function(data) {
                $('#notifications').append(data.message);
                if(data.code == 0) table.draw(false);
                table_data();   
            }, 'json')
            .fail(function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
            });
        }            
        else
            return false ;
    }

</script>