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
                 
                <div class="table-responsive" id="table-responsive">
               <table id="table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            
                            <th>Rangking</th>                            
                            <th>Nama</th>
                            <th>Tahun Akademik</th>
                            <th>Jawaban</th>      
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>

                    <tfoot>
                        <tr>
                            
                            <th>Rangking</th>                            
                            <th>Nama</th>
                            <th>Tahun Akademik</th>
                            <th>Jawaban</th>                         
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
                        <label>Nama Kelas</label>
                        <input type="hidden" id="dsnKelasID">
                        <div id="kelasID"></div>
                    </div>
                    <div class="form-group">
                        <label>Nama Dosen</label>
                        <div id="npp"></div>
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
                        <label>Dosen Kelas ID</label>
                        <p id="dsnKelasID"></p>
                    </div>
                    <div class="form-group">
                        <label>Nama Kelas</label>
                        <p id="kelasID"></p>
                    </div>
                    <div class="form-group">
                        <label>Nama Dosen</label>
                        <p id="npp"></p>
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
    var site_url = site_url() + 'Hasildosen/';

    var table;
    $(document).ready(function() {

        table_data();

        table = $('#table').DataTable({ 

            "processing": true, 
            "serverSide": true, 
            "order": [], 
            
            "ajax": {
                "url": site_url + 'get_hasildosen',
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
                    $('#dsnKelasID').html(data.dsnKelasID);
                    $('#kelasID').html(data.kelasID);
                    $('#npp').html(data.npp);
                    $('#thnAkademikID').html(data.thnAkademikID);

                    $(".chosen-select").chosen();
                }
            });
        });

        $('#submit').click(function() {
            $.ajax({
                url : site_url + 'save_dsnkelas/',
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

        $('.box-title').text('Dosen Kelas List'); 
    }

    function form_data()
    {
        $('#hidden').empty();
        $('#dsnKelasID').empty();
        $('#kelasID').empty();
        $('#npp').empty();
        $('#thnAkademikID').empty();

        $('#table-data').hide();
        $('#form-data').show();
        $('#form-view').hide();
    }

    function form_view()
    {
        $('p#hidden').empty();
        $('p#dsnKelasID').empty();
        $('p#kelasID').empty();
        $('p#npp').empty();
        $('p#thnAkademikID').empty();
        
        $('#table-data').hide();
        $('#form-data').hide();
        $('#form-view').show();

        $('.box-title').text('Dosen Kelas View'); 
    }

    function view_data(id)
    {
         $.ajax({
            url: site_url + 'view/',
            data: {'dsnKelasID': id},
            cache: false,
            type: "POST",
            success: function(data){
                form_view();

                data = JSON.parse(data);
                $('p#hidden').html(data.hidden);
                $('p#dsnKelasID').html(data.dsnKelasID);
                $('p#kelasID').html(data.kelasID);
                $('p#npp').html(data.npp);
                $('p#thnAkademikID').html(data.thnAkademikID);
            }
        }); 
    }

    function update_data(id)
    {
         $.ajax({
            url: site_url + 'form_data/',
            data: {'dsnKelasID': id},
            cache: false,
            type: "POST",
            success: function(data){
                $(".chosen-select").chosen("destroy");
                form_data();
                $('.box-title').text('Update Product'); 

                data = JSON.parse(data);
                $('#hidden').html(data.hidden);
                $('#dsnKelasID').html(data.dsnKelasID);
                $('input[name=dsnKelasID]').prop('readonly',true);
                $('#kelasID').html(data.kelasID);
                $('#npp').html(data.npp);
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
                data: {'dsnKelasID':id},
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