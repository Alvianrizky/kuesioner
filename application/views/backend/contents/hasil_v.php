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
                            
                            <th>No</th>                            
                            <th>Hasil ID</th>
                            <th>Pertanyaan</th>
                            <th>Nama Mahasiswa</th>
                            <th>Jawaban</th>
                            <th>Tahun Akademik</th>      
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>

                    <tfoot>
                        <tr>
                            
                            <th>No</th>                            
                            <th>Hasil ID</th>
                            <th>Pertanyaan</th>
                            <th>Nama Mahasiswa</th>
                            <th>Jawaban</th>
                            <th>Tahun Akademik</th>                         
                        </tr>
                    </tfoot>
                </table>
                </div>           
            </div>
        </div>

        <form role="form" method="POST" action="" id="form-view"> 
            <div class="box-body">
                <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Hasil ID</label>
                        <p id="hasilID"></p>
                    </div>
                    <div class="form-group">
                        <label>Pertanyaan</label>
                        <p id="pertanyaanID"></p>
                    </div>
                    <div class="form-group">
                        <label>Nama Mahasiswa</label>
                        <p id="nim"></p>
                    </div>
                    <div class="form-group">
                        <label>Jawaban</label>
                        <p id="jawaban"></p>
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
    var site_url = site_url() + 'Hasil/';

    var table;
    $(document).ready(function() {

        table_data();

        table = $('#table').DataTable({ 

            "processing": true, 
            "serverSide": true, 
            "order": [], 
            
            "ajax": {
                "url": site_url + 'get_hasil',
                "type": "POST",
                // "dataType":"json",
                "data": function(data) {
                    // $('#pertanyaanID').html(data.pertanyaanID);
                    data.pertanyaanID = $('#pertanyaan').val();
                    // data.jawaban = $('#jawaban').val();
                }
            },
            
            "columnDefs": [
            { 
                "targets": [ 0 ], 
                "orderable": false, 
            },
            ],
        });

        $('#btn-filter').click(function(){ //button filter event click
        table.ajax.reload();  //just reload table
        });
        $('#btn-reset').click(function(){ //button reset event click
            $('#form-filter')[0].reset();
            table.ajax.reload();  //just reload table
        });

    });

    function table_data()
    {    
        $('#table-data').show();
        $('#form-view').hide();

        $('.box-title').text('Hasil List'); 
    }

    function form_filter()
    {
        $('#hidden').empty();
        $('#pertanyaanID').empty();
        // $('#barangID').empty();
        // $('#hargajual').empty();
        // $('#jumlahbeli').empty();

        $('#table-data').show();
        $('#form-data').hide();
        $('#form-view').hide();
    }

    function form_view()
    {
        $('p#hidden').empty();
        $('p#hasilID').empty();
        $('p#pertanyaanID').empty();
        $('p#nim').empty();
        $('p#jawaban').empty();
        $('p#thnAkademikID').empty();
        
        $('#table-data').hide();
        $('#form-view').show();

        $('.box-title').text('Hasil View'); 
    }

    function view_data(id)
    {
         $.ajax({
            url: site_url + 'view/',
            data: {'id': id},
            cache: false,
            type: "POST",
            success: function(data){
                form_view();

                data = JSON.parse(data);
                $('p#hidden').html(data.hidden);
                $('p#hasilID').html(data.hasilID);
                $('p#pertanyaanID').html(data.pertanyaanID);
                $('p#nim').html(data.nim);
                $('p#jawaban').html(data.jawaban);
                $('p#thnAkademikID').html(data.thnAkademikID);
            }
        }); 
    }


</script>