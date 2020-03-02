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
        	<form method="POST" action="<?php echo base_url() . 'index.php/main/save_dosen'; ?>" id="signup-form" class="signup-form" enctype="multipart/form-data">
        		<input type="hidden" name="nim" value="<?php echo $nim->prodiID ?>">
        		<?php

				//$j = 1;

				$x = 1;
				foreach ($dsnkls as $row) {

					$this->load->model(array('Dosen_model' => 'dosen', 'PertanyaanDosen_model' => 'per'));
					$query = $this->dosen->where('npp', $row->npp)->get();

				?>
        			<h3>
        				<input type="hidden" name="groupID" value="<?php echo $row->kelasID ?>">
        			</h3>
        			<fieldset>


        				<div class="form-row">

        					<div class="card col-12 border-0 mt-5">
        						<div class="mb-3 mt-3 border-bottom">
        							<p class="h6">Nama Dosen</p>
        							<input type="hidden" name="npp-<?php echo $x++ ?>" value="<?php echo $row->npp ?>">
        							<p class="h4-md h5 pl-0"><?php echo $query->nama; ?> S.Kep., Ns., M.Kep.</p>

        						</div>
        					</div>

        					<?php

							$j = 1;
							foreach ($group as $row1) {
								$no = 1;



							?>

        						<div class="card rounded-lg mt-4 px-3 d-none d-sm-block">
        							<div class="mb-n3 mt-3">
        								<h6 class="pl-0"><?php echo $row1->nama ?></h6>
        								<p>Pilihlah salah satu dari penilaian yang tersedia.</p>
        							</div>

        							<table class="table table-borderless d-none d-sm-block">
        								<tr>
        									<td></td>
        									<td></td>
        									<?php

											$jawaban = $row1->jawaban;
											$pisah = explode(",", $jawaban);

											foreach ($pisah as $jwb) {

											?>
        										<td class="text-center" width="80px"><?php echo $jwb ?></td>
        									<?php } ?>
        								</tr>
        								<?php

										$CI = &get_instance();

										$test = $CI->per->where('groupID', $row1->groupID)->get_all();
										if (!empty($test)) :
											foreach ($test as $row2) {

										?>
        										<tr>
        											<input type="hidden" name="pertanyaanID-<?php echo $row->npp ?>-<?php echo $j++ ?>" value="<?php echo $row2->pertanyaanID ?>">
        											<td><?php echo $no++ ?></td>
        											<td><?php echo $row2->pertanyaan ?></td>
        											<?php

													$nilai = $row1->nilai;
													$pisah = explode(",", $nilai);

													foreach ($pisah as $nil) {

													?>
        												<td class="text-center"><input type="radio" name="pertanyaanID-<?php echo $row->npp ?>-<?php echo $row2->pertanyaanID ?>" value="<?php echo $nil ?>" required></td>
        											<?php } ?>
        										</tr>
        								<?php
											}
										endif;
										?>
        							</table>
        						</div>

        					<?php } ?>



        					<div class="card mt-3 py-2 d-block d-sm-none border-0">

        						<?php

								$k = 1;
								foreach ($group as $row1) {

									$nos = 1;


								?>

        							<div class="mb-2 mt-3">
        								<h6 class="pl-0"><?php echo $row1->nama ?></h6>
        								<p>Pilihlah salah satu dari penilaian yang tersedia.</p>
        							</div>

        							<?php
									$CI = &get_instance();

									$test1 = $CI->per->where('groupID', $row1->groupID)->get_all();
									if (!empty($test1)) :
										foreach ($test1 as $row3) {

									?>

        									<div class="justify-content-center">
        										<div class="row">
        											<input type="hidden" name="pertanyaanID-<?php echo $row->npp ?>-<?php echo $k++ ?>" value="<?php echo $row3->pertanyaanID ?>">
        											<div class="col-md-1 col-2 mr-n2 mr-md-n4"><?php echo $nos++ ?></div>
        											<div class="col-md-11 col-10 ml-n3 ml-md-0"><?php echo $row3->pertanyaan ?></div>
        										</div>
        										<div class="form-group">
        											<div class="ml-4 ml-md-4 mt-3">
        												<?php

														$jawaban = $row1->jawaban;
														$nilai1 = $row1->nilai;
														$nilai2 = explode(",", $nilai1);
														$pisah = explode(",", $jawaban);

														$i = 0;
														foreach ($pisah as $jwb) {

														?>
        													<div class="form-check">
        														<input class="form-check-input" type="radio" name='pertanyaanID-<?php echo $row->npp ?>-<?php echo $row3->pertanyaanID ?>' id="exampleRadios1" value='<?php echo $nilai2[$i++] ?>'>
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
									endif;
								}
								?>

        					</div>

        				</div>


        			</fieldset>

        		<?php

				}

				?>

        	</form>
        </div>
