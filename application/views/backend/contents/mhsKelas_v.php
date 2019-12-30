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
                            <button id="create" class="btn btn-primary btn-sm" title="Data Create" alt="Data Create" ><i class="glyphicon glyphicon-plus"></i> Tambah Mahasiswa Kelas</button> 
                        </div>  
                    </div>
                </div>  
                <div class="table-responsive" id="table-responsive">
               <table id="table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 100px!important;">Action</th>
                            <th>No</th>                            
                            <th>Mahasiswa Kelas ID</th>
                            <th>Nama Kelas</th>
                            <th>Nama Mahasiswa</th>
                            <th>Tahun Akademik</th>      
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>

                    <tfoot>
                        <tr>
                            <th>Action</th>
                            <th>No</th>                            
                            <th>Mahasiswa Kelas ID</th>
                            <th>Nama Kelas</th>
                            <th>Nama Mahasiswa</th>
                            <th>Tahun Akademik</th>                         
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
                        <label>Mahasiswa Kelas ID</label>
                        <div id="mhsKelasID"></div>
                    </div>
                    <div class="form-group">
                        <label>Nama Kelas</label>
                        <div id="kelasID"></div>
                    </div>
                    <div class="form-group">
                        <label>Nama Mahasiswa</label>
                        <div id="nim"></div>
                    </div>
                    <div class="form-group">
                        <label>Tahun Akademik</label>
                        <div id="thnAkademikID"></div>
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
                        <label>Mahasiswa Kelas ID</label>
                        <p id="mhsKelasID"></p>
                    </div>
                    <div class="form-group">
                        <label>Nama Kelas</label>
                        <p id="kelasID"></p>
                    </div>
                    <div class="form-group">
                        <label>Nama Mahasiswa</label>
                        <p id="nim"></p>
                    </div>
                    <div class="form-group">
                        <label>Tahun Akademik</label>
                        <p id="thnAkademikID"></p>
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
    var site_url = site_url() + 'MhsKelas/';

    var table;
    $(document).ready(function() {

        table_data();

        table = $('#table').DataTable({ 

            "processing": true, 
            "serverSide": true, 
            "order": [], 
            
            "ajax": {
                "url": site_url + 'get_mhskelas',
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
                    $('.box-title').text('Create Mahasiswa Kelas');  

                    //data = JSON.parse(data);
                    $('#hidden').html(data.hidden);
                    $('#js-config').html(data.jsConfig);
                    $('#mhsKelasID').html(data.mhsKelasID);
                    $('#kelasID').html(data.kelasID);
                    $('#nim').html(data.nim);
                    $('#thnAkademikID').html(data.thnAkademikID);

                    $(".chosen-select").chosen();
                }
            });
        });

        $('#submit').click(function() {
            $.ajax({
                url : site_url + 'save_mhskelas/',
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

        $('.box-title').text('Mahasiswa Kelas List'); 
    }

    function form_data()
    {
        $('#hidden').empty();
        $('#mhsKelasID').empty();
        $('#kelasID').empty();
        $('#nim').empty();
        $('#thnAkademikID').empty();

        $('#table-data').hide();
        $('#form-data').show();
        $('#form-view').hide();
    }

    function form_view()
    {
        $('p#hidden').empty();
        $('p#mhsKelasID').empty();
        $('p#kelasID').empty();
        $('p#nim').empty();
        $('p#thnAkademikID').empty();
        
        $('#table-data').hide();
        $('#form-data').hide();
        $('#form-view').show();

        $('.box-title').text('Mahasiswa Kelas Product'); 
    }

    function view_data(id)
    {
         $.ajax({
            url: site_url + 'view/',
            data: {'mhsKelasID': id},
            cache: false,
            type: "POST",
            success: function(data){
                form_view();

                data = JSON.parse(data);
                $('p#hidden').html(data.hidden);
                $('p#mhsKelasID').html(data.mhsKelasID);
                $('p#kelasID').html(data.kelasID);
                $('p#nim').html(data.nim);
                $('p#thnAkademikID').html(data.thnAkademikID);
            }
        }); 
    }

    function update_data(id)
    {
         $.ajax({
            url: site_url + 'form_data/',
            data: {'mhsKelasID': id},
            cache: false,
            type: "POST",
            success: function(data){
                $(".chosen-select").chosen("destroy");
                form_data();
                $('.box-title').text('Update Product'); 

                data = JSON.parse(data);
                $('#hidden').html(data.hidden);
                $('#mhsKelasID').html(data.mhsKelasID);
                $('input[name=mhsKelasID]').prop('readonly',true);
                $('#kelasID').html(data.kelasID);
                $('#nim').html(data.nim);
                $('#thnAkademikID').html(data.thnAkademikID);
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
                data: {'mhsKelasID':id},
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