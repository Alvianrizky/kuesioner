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
<div id="notifications-success"></div>

 <div class="row">
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo isset($panel_heading) ? $panel_heading : '';?> </h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-6">
                            <button id="create" class="btn btn-primary btn-sm" title="Data Create" alt="Data Create" ><i class="glyphicon glyphicon-plus"></i> Create Categories</button> 
                        </div>  
                    </div>
                </div>  
                <div class="table-responsive" id="table-responsive">
                <table id="table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 100px!important;">Action</th>
                            <th>No</th>
                            <th>Category Name</th>
                            <th>Description</th>      
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>

                    <tfoot>
                        <tr>
                            <th>Action</th>
                            <th>No</th>
                            <th>Category Name</th>
                            <th>Description</th>                         
                        </tr>
                    </tfoot>
                </table>
                </div>           
            </div>

        </div><!-- /.box -->
    </div><!--/.col (right) -->
</div>   <!-- /.row -->

</section><!-- /.content -->

<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Modal Judul</h4>
            </div>         
            
            <form role="form" method="POST" action="" id="form-data" enctype="multipart/form-data"> 
                <div class="modal-body">
                    <div id="notifications-error"></div>
                    <div class="row">
                    <div class="col-lg-12">
                        <div id="hidden"></div>
                        <div class="form-group">
                            <label>Category Name</label>
                            <div id="CategoryName"></div>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <div id="Description"></div>
                        </div>
                        <div class="form-group">
                            <label>Picture</label>
                            <div id="Picture"></div>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" name="submit" id="submit" class="btn btn-primary">Submit Data</button> &nbsp; &nbsp; 
                    <button type="reset" name="reset" class="btn btn-default">Reset Data</button>
                </div>
            </form>

            <form role="form" method="POST" action="" id="form-view"> 
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="hidden"></div>
                            <div class="form-group">
                                <label>Category Name</label>
                                <p id="CategoryName"></p>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <p id="Description"></p>
                            </div>  
                            <div class="form-group">
                                <label>Picture</label>
                                <p id="Picture"></p>
                            </div>                          
                        </div>
                    </div>
                </div>
                <div class="modal-footer"></div>
            </form>

        </div>      
    </div>
</div>


<script type="text/javascript">
    var site_url = site_url() + 'categories/';

    var table;
    $(document).ready(function() {

        table = $('#table').DataTable({ 

            "processing": true, 
            "serverSide": true, 
            "order": [], 
            
            "ajax": {
                "url": site_url + 'get_categories',
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
                data = JSON.parse(data);

                $('#hidden').html(data.hidden);
                $('#CategoryName').html(data.CategoryName);
                $('#Description').html(data.Description);
                $('#Picture').html(data.Picture);  
            });

            $('#form-view').hide();
            $('#form-data').show();
            //$('input[name="CategoryID"]').remove();
            $('#modal-form').modal('show');
            $('.modal-title').text('Create Categories');
        });
        

        $('#submit').click(function() {
            $.ajax({
                url : site_url + 'save_category/',
                type: "POST",
                data: new FormData($('#form-data')[0]),
                dataType: "JSON",
                contentType: false, 
                cache: false,             
                processData:false,
                success: function(data)
                {
                    if(data.code == 1){
                        $('#notifications-error').append(data.message);
                    }
                    else{
                        $('#notifications-success').append(data.message);
                        table.draw(false);
                        $('#modal-form').modal('hide');
                    }  
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error adding / update data');
                }
            });
        });

    });

    function view_data(id)
    {
        $.post(site_url + 'view/', {'CategoryID': id}, function(data) {
            data = JSON.parse(data);

            $('p#CategoryID').html(data.CategoryID);
            $('p#CategoryName').html(data.CategoryName);
            $('p#Description').html(data.Description);  
            $('p#Picture').html(data.Picture);    
        });

        $('#form-view').show();
        $('#form-data').hide();

        $('#modal-form').modal('show');
        $('.modal-title').text('View Category'); 
    }

    function update_data(id)
    {
        $.post(site_url + 'form_data/', {'CategoryID': id}, function(data) {
            data = JSON.parse(data);
            $('#hidden').html(data.hidden);
            $('#CategoryID').html(data.CategoryID);
            $('#CategoryName').html(data.CategoryName);
            $('#Description').html(data.Description);
            $('#Picture').html(data.Picture);     
        });

        $('#form-view').hide();
        $('#form-data').show();

        $('#modal-form').modal('show');
        $('.modal-title').text('Update Category'); 
    }

    function delete_data(id)
    {
        var agree = confirm("Are you sure you want to delete this item?");
        if (agree){
            $.post(site_url + 'delete/', {'CategoryID': id}, function(data) {
                $('#notifications-success').append(data.message);
                if(data.code == 0) table.draw(false);    
            }, 'json');
        }            
        else
            return false ;
    }

</script>