        <div class="card col-md-5 col-11 mx-auto my-md-3 my-5 ">
        	<div class="card-header" style="background: none;">
        		<h4>Kuisoner Penilaian Kampus</h4>
        	</div>
        	<div class="card-body">
        		<form role="form" method="POST" action="<?php echo base_url() . 'index.php/main/cek'; ?>">
        			<div class="form-group">
        				<div id="notifications"><?php echo $this->session->flashdata('msg'); ?></div>
        				<label class="h6">Masukan NIM Mahasiswa</label>
        				<input type="text" name="nim" class="form-control" required="required">
        				<button type="submit" name="submit" id="submit" class="btn btn-primary mt-3">Lanjutkan</button>
        			</div>
        		</form>
        	</div>
        </div>
