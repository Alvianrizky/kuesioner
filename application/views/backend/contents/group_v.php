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
                            <button id="create" class="btn btn-primary btn-sm" title="Data Create" alt="Data Create" ><i class="glyphicon glyphicon-plus"></i> Tambah Group</button>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal"><i class="glyphicon glyphicon-import"></i>Import Group</button>
                        </div>  
                    </div>
                </div>  
                <div class="table-responsive" id="table-responsive">
               <table id="table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 100px!important;">Action</th>
                            <th>No</th>                            
                            <th>Group ID</th>
                            <th>Program Studi</th>
                            <th>Nama</th>
                            <th>Jawaban</th>
                            <th>Nilai</th>     
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>

                    <tfoot>
                        <tr>
                            <th>Action</th>
                            <th>No</th>                            
                            <th>Group ID</th>
                            <th>Program Studi</th>
                            <th>Nama</th>
                            <th>Jawaban</th>
                            <th>Nilai</th>                          
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
                        <label>Group ID</label>
                        <div id="groupID"></div>
                    </div>
                    <div class="form-group">
                        <label>Program Studi</label>
                        <div id="prodiID"></div>
                    </div>
                    <div class="form-group">
                        <label>Nama</label>
                        <div id="nama"></div>
                    </div>
                    <div class="form-group">
                        <label>Jawaban</label>
                        <div id="jawaban"></div>
                    </div>
                    <div class="form-group">
                        <label>Nilai</label>
                        <div id="nilai"></div>
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
                        <label>Group ID</label>
                        <p id="groupID"></p>
                    </div>
                    <div class="form-group">
                        <label>Program Studi</label>
                        <p id="prodiID"></p>
                    </div>
                    <div class="form-group">
                        <label>Nama</label>
                        <p id="nama"></p>
                    </div>
                    <div class="form-group">
                        <label>Jawaban</label>
                        <p id="jawaban"></p>
                    </div>
                    <div class="form-group">
                        <label>Nilai</label>
                        <p id="nilai"></p>
                    </div>
                </div>
                
                </div>
            </div>
            <div class="box-footer"><button type="button" name="back" class="btn btn-primary pull-right" onClick="table_data();">Back Button</button></div>
        </form>

        </div><!-- /.box -->
    </div><!--/.col (right) -->
</div>   <!-- /.row -->



<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Group</h4>
      </div>
      <div class="modal-body">
        <form role="form" method="POST" action="" id="import-data" enctype="multipart/form-data">
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="validatedCustomFile" name="fileURL">
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <a href="<?=base_url()?>assets/download/group.xlsx" class="btn btn-success">Example Data Import Group</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" name="import" id="import" class="btn btn-primary" data-dismiss="modal">Import</button>
      </div>
    </div>

  </div>
</div>


</section><!-- /.content -->


<script type="text/javascript">
    var site_url = site_url() + 'group/';

    var table;
    $(document).ready(function() {

        table_data();

        table = $('#table').DataTable({ 

            "processing": true, 
            "serverSide": true, 
            "order": [], 
            
            "ajax": {
                "url": site_url + 'get_group',
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
                    $('.box-title').text('Create Group');  

                    //data = JSON.parse(data);
                    $('#hidden').html(data.hidden);
                    $('#js-config').html(data.jsConfig);
                    $('#groupID').html(data.groupID);
                    $('#prodiID').html(data.prodiID);
                    $('#nama').html(data.nama);
                    $('#jawaban').html(data.jawaban);
                    $('#nilai').html(data.nilai);
                    
                    $(".chosen-select").chosen();
                }
            });
        });

        $('#submit').click(function() {
            $.ajax({
                url : site_url + 'save_group/',
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

        $('#import').click(function() {
            $.ajax({
                url : site_url + 'upload/',
                type: "POST",
                data: new FormData($('#import-data')[0]),
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
                    alert('Error adding / import data');
                }
            });
        });

    });

    function table_data()
    {    
        $('#table-data').show();
        $('#form-data').hide();
        $('#form-view').hide();

        $('.box-title').text('Group List'); 
    }

    function form_data()
    {
        $('#hidden').empty();
        $('#groupID').empty();
        $('#prodiID').empty();
        $('#nama').empty();
        $('#jawaban').empty();
        $('#nilai').empty();

        $('#table-data').hide();
        $('#form-data').show();
        $('#form-view').hide();
    }

    function form_view()
    {
        $('p#hidden').empty();
        $('p#groupID').empty();
        $('p#prodiID').empty();
        $('p#nama').empty();
        $('p#jawaban').empty();
        $('p#nilai').empty();

        $('#table-data').hide();
        $('#form-data').hide();
        $('#form-view').show();

        $('.box-title').text('View Group'); 
    }

    function view_data(id)
    {
         $.ajax({
            url: site_url + 'view/',
            data: {'groupID': id},
            cache: false,
            type: "POST",
            success: function(data){
                form_view();

                data = JSON.parse(data);
                $('p#hidden').html(data.hidden);
                $('p#groupID').html(data.groupID);
                $('p#prodiID').html(data.prodiID);
                $('p#nama').html(data.nama);
                $('p#jawaban').html(data.jawaban);
                $('p#nilai').html(data.nilai);
                 
            }
        }); 
    }

    function update_data(id)
    {
         $.ajax({
            url: site_url + 'form_data/',
            data: {'groupID': id},
            cache: false,
            type: "POST",
            success: function(data){
                $(".chosen-select").chosen("destroy");
                form_data();
                $('.box-title').text('Update Mahasiswa'); 
                
                data = JSON.parse(data);
                $('#hidden').html(data.hidden);
                $('#groupID').html(data.groupID);
                $('input[name=groupID]').prop('readonly',true);
                $('#prodiID').html(data.prodiID);
                $('#nama').html(data.nama);
                $('#jawaban').html(data.jawaban);
                $('#nilai').html(data.nilai);
                
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
                data: {'groupID':id},
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