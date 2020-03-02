        <div class="card col-md-8 col-11 mx-auto mb-3">
        	<div class="card-header" style="background: none;">
        		<h4>Data Mahasiswa</h4>
        	</div>
        	<div class="card-body justify-content-between px-md-5">
        		<table class="table table-borderless mx-n4">
        			<tr>
        				<td>Nama Mahasiswa</td>
        				<td>:</td>
        				<td><?php echo $nim->nama ?></td>
        			</tr>
        			<tr>
        				<td>NIM</td>
        				<td>:</td>
        				<td><?php echo $nim->nim ?></td>
        			</tr>
        			<tr>
        				<td>Program Studi</td>
        				<td>:</td>
        				<td><?php echo $prodi->nama_prodi ?></td>
        			</tr>
        			<tr>
        				<td>Nama Kelas</td>
        				<td>:</td>
        				<td><?php echo $kelas->nama_kelas ?></td>
        			</tr>
        			<tr>
        				<td>Tahun Akademik</td>
        				<td>:</td>
        				<td><?php echo $tahun->thnAkademik ?></td>
        			</tr>
        		</table>
        	</div>
        </div>
        <div class="container1 col-md-12 col-12">
        	<form method="POST" action="<?php echo base_url() . 'index.php/main/save_hasil'; ?>" id="signup-form" class="signup-form" enctype="multipart/form-data">

        		<?php

				$j = 1;
				$x = 1;




				foreach ($group as $row) {
					$no = 1;
					$nos = 1;
					$i = 1;

					// $this->load->model(array('prodi_model' => 'prodi'));
					// $prodi = $this->prodi->where('prodiID', $row->prodiID)->get_all();
					if ($row->prodiID == 0 || $row->prodiID == $this->session->prodiID) :


				?>
        				<h3>
        					<input type="hidden" name="groupID" value="<?php echo $row->groupID ?>">
        				</h3>
        				<fieldset>


        					<div class="form-row">
        						<div class="mb-2 mt-5">
        							<h5 class="mb-4 pl-0"><?php echo $row->nama ?></h5>
        							<p>Pilihlah salah satu dari penilaian yang tersedia.</p>
        						</div>


        						<table class="table table-borderless d-none d-sm-block">
        							<tr>
        								<td></td>
        								<td></td>
        								<?php

										$jawaban = $row->jawaban;
										$pisah = explode(",", $jawaban);

										foreach ($pisah as $jwb) {

										?>
        									<td class="text-center" width="112px"><?php echo $jwb ?></td>

        								<?php } ?>
        							</tr>
        							<?php
									$this->load->model(array('Pertanyaan_model' => 'pertanyaan'));
									$query = $this->pertanyaan->where('groupID', $row->groupID)->get_all();
									if (!empty($query)) :
										foreach ($query as $row1) {

									?>

        									<tr>
        										<input type="hidden" name="pertanyaanID-<?php echo $j++ ?>" value="<?php echo $row1->pertanyaanID ?>">
        										<td><?php echo $no++ ?></td>
        										<td><?php echo $row1->pertanyaan ?></td>
        										<!-- <label class="error" for="pertanyaanID-<?php echo $row1->pertanyaanID ?>" style="color: red;"></label> -->
        										<?php

												// $nilai = $row->nilai;
												// $pisah1 = explode(",", $nilai);

												foreach ($pisah as $jwb) {

												?>
        											<td class="text-center"><input type="radio" name="pertanyaanID-<?php echo $row1->pertanyaanID ?>" value="<?php echo $jwb ?>" required aria-required="true" class="error" aria-invalid="true"></td>

        										<?php } ?>
        									</tr>
        							<?php
										}

									endif; ?>
        						</table>


        						<div class="card mt-3 py-2 d-block d-sm-none border-0">
        							<?php

									if (!empty($query)) :
										foreach ($query as $row1) {


									?>

        									<div class="justify-content-center">
        										<div class="row">
        											<input type="hidden" name="pertanyaanID-<?php echo $x++ ?>" value="<?php echo $row1->pertanyaanID ?>">
        											<div class="col-md-1 col-2 mr-n2 mr-md-n4"><?php echo $nos++ ?></div>
        											<div class="col-md-11 col-10 ml-n3 ml-md-0"><?php echo $row1->pertanyaan ?></div>
        										</div>
        										<div class="form-group">
        											<div class="ml-4 ml-md-4 mt-3">
        												<?php

														foreach ($pisah as $jwb) {

														?>
        													<div class="form-check">
        														<input class="form-check-input" type="radio" name='pertanyaanID-<?php echo $row1->pertanyaanID ?>' id="exampleRadios1" value='<?php echo $jwb ?>'>
        														<label class="form-check-label" for="exampleRadios1">
        															<?php echo $jwb ?>
        														</label>
        													</div>
        												<?php } ?>

        											</div>
        										</div>
        									</div>
        							<?php
										}
									endif; ?>

        						</div>

        					</div>


        				</fieldset>

        		<?php

					endif;
				}

				?>

        	</form>
        </div>
