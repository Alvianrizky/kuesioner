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
                
                
                <form action="" id="form-data" enctype="multipart/form-data" role="form" method="POST" >
                
                    <div class="col-lg-6 order-lg-1">
                    <input type="file" class="custom-file-input" id="validatedCustomFile" name="fileURL">
                    
                    </div>
                    <div class="col-lg-6 order-lg-2">
                    <button type="button" name="import" id="submit" class="float-right btn btn-primary">Import</button>
                    </div>
                
                </form>
                       
            </div>
        </div>

        </div><!-- /.box -->
    </div><!--/.col (right) -->
</div>   <!-- /.row -->


</section><!-- /.content -->


<script type="text/javascript">
    var site_url = site_url() + 'Import/';
    $(document).ready(function() {

        $('#submit').click(function() {
            $.ajax({
                url : site_url + 'upload/',
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

    

</script>