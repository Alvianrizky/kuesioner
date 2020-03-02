<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Main extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();


		$this->load->model(array('Group_model' => 'group', 'Pertanyaan_model' => 'pertanyaan', 'Main_model' => 'main', 'Mahasiswa_model' => 'mahasiswa', 'prodi_model' => 'prodi', 'MhsKelas_model' => 'mhskelas', 'kelas_model' => 'kelas', 'group_dosen' => 'groupdosen', 'DsnKelas_model' => 'dsnkelas', 'Dosen_model' => 'dosen', 'thnAkademik_model' => 'akademik', 'PertanyaanDosen_model' => 'per', 'Hasil_model' => 'hasil'));
		$this->load->library(array('ion_auth', 'form_validation', 'template'));
		$this->load->helper('bootstrap_helper', 'download');
	}

	public function index()
	{
		$this->load->view('main/header');
		$this->load->view('main/masuk');
		$this->load->view('main/footer');
	}

	public function cek()
	{
		$this->form_validation->set_rules('nim', 'Nim Aktif Mahasiswa', 'trim|required|is_unique[mahasiswa.nim]|max_length[12]');

		if ($this->form_validation->run() == TRUE) {
			$this->session->set_flashdata(
				'msg',
				'<div class="alert alert-danger alert-dismissible pb-n2">
                <a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <p>NIM yang anda masukan salah.</p>
                </div>'
			);
			redirect('main', 'refresh');
		} else {
			$query = $this->input->post('nim');
			$nim1 = $this->mahasiswa->where('nim', $query)->get();
			$mhskelas = $this->mhskelas->where('nim', $query)->get();
			// $thnAkademik1 = $mhskelas->thnAkademikID;
			if(!empty($mhskelas->thnAkademikID))
			{
				$thnAkademik = $this->akademik->where('thnAkademikID', $mhskelas->thnAkademikID)->get();
			}
			
			$hasil = $this->hasil->where('nim', $query)->get();

			if (empty($mhskelas->kelasID)) {
				$this->session->set_flashdata(
					'msg',
					'<div class="alert alert-danger alert-dismissible pb-n2">
                <a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <p>Anda belum mendapatkan kelas di semester ini.</p>
                </div>'
				);
				redirect('main', 'refresh');
			} else {
				if ($thnAkademik->status == 'Tidak Aktif') {
					$this->session->set_flashdata(
						'msg',
						'<div class="alert alert-danger alert-dismissible pb-n2">
					<a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						<p>Anda tidak aktif di semester ini.</p>
					</div>'
					);
					redirect('main', 'refresh');
				} else {
					if (!empty($hasil->nim) && $thnAkademik->status == 'Tidak Aktif') {
						$this->session->set_flashdata(
							'msg',
							'<div class="alert alert-danger alert-dismissible pb-n2">
						<a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							<p>Anda sudah mengisi kuesioner ini.</p>
						</div>'
						);
						redirect('main', 'refresh');
					} elseif (!empty($hasil->nim) && $thnAkademik->status == 'Aktif') {
						$this->session->set_flashdata(
							'msg',
							'<div class="alert alert-danger alert-dismissible pb-n2">
						<a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							<p>Anda sudah mengisi kuesioner ini.</p>
						</div>'
						);
						redirect('main', 'refresh');
					} else {
						$data1 = array(
							'nim' => $nim1->nim,
							'prodiID' => $nim1->prodiID,
							'kelasID' => $mhskelas->kelasID,
							'tahun' => $mhskelas->thnAkademikID
						);

						$this->session->set_userdata($data1);
						redirect('main/mainpage', 'refresh');
					}
				}
			}
		}
	}

	public function mainpage()
	{

		//$nim = $this->session->nim;
		$nim = $this->mahasiswa->where('nim', $this->session->nim)->get();
		$prodi = $this->prodi->where('prodiID', $this->session->prodiID)->get();
		//$mhskelas = $this->mhskelas->where('nim', $this->session->nim)->get();
		$thnAkademik = $this->akademik->where('thnAkademikID', $this->session->tahun)->get();
		$kelas = $this->kelas->where('kelasID', $this->session->kelasID)->get();

		$group1 = $this->group->get_all();
		// $prodi = $this->prodi->where('prodiID', $this->session->prodiID)->get();

		$group = $this->group->as_object()->get_all();
		$pertanyaan = $this->pertanyaan->as_object()->get_all();

		$data = array(
			'group' => $group1,
			'pertanyaan' => $pertanyaan,
			'nim' => $nim,
			'prodi' => $prodi,
			'kelas' => $kelas,
			'tahun' => $thnAkademik
		);

		$this->load->view('main/header');
		$this->load->view('main/isi');
		$this->load->view('main/content', $data);
		$this->load->view('main/footer');
	}

	public function dosen()
	{
		//$query = $this->input->post('nim');
		$query = $this->session->nim;
		$nim = $this->mahasiswa->where('nim', $query)->get();
		$prodi = $this->prodi->where('prodiID', $this->session->prodiID)->get();
		$kelas = $this->kelas->where('kelasID', $this->session->kelasID)->get();

		$where = array(
			'kelasID' => $this->session->kelasID,
			'thnAkademikID' => $this->session->tahun
		);

		$dsnkls = $this->dsnkelas->where($where)->get_all();
		$thnAkademik = $this->akademik->where('thnAkademikID', $this->session->tahun)->get();
		$group = $this->groupdosen->as_object()->get_all();
		//$pertanyaan = $this->pertanyaan->as_object()->get_all();

		$data = array(
			'group' => $group,
			'nim' => $nim,
			'prodi' => $prodi,
			'kelas' => $kelas,
			'dsnkls' => $dsnkls,
			'tahun' => $thnAkademik
		);

		$this->load->view('main/header');
		//$this->load->view('main/isi', $data);
		$this->load->view('main/dosen', $data);
		$this->load->view('main/footer');
	}

	public function save_hasil()
	{
		$row = array();
		$pertanyaan = $this->pertanyaan->as_object()->get_all();
		//$query = $this->input->post('nim_mhs');

		$no = 1;
		$xh = 1;

		for ($i = 0; $i < count((array) $pertanyaan); $i++) {

			$char = date('his');
			$newID = $char . sprintf("%04s", $xh++);
			$id = $this->input->post('pertanyaanID-' . $no++ . '');
			$per = $this->input->post('pertanyaanID-' . $id . '');
			// $nim = $this->input->post('nim');

			$row[$i]['hasilID'] = $newID;
			$row[$i]['pertanyaanID'] = $id;
			$row[$i]['nim'] = $this->session->nim;
			$row[$i]['jawaban'] = $per;
			$row[$i]['thnAkademikID'] = $this->session->tahun;
			$row[$i]['created_at'] = date('Y-m-d H:i:s');
		}

		// if (!empty($row)) {
		// 	echo "<pre>";
		// 	print_r($row);
		// 	exit();

		// 	redirect('main/mainpage', 'refresh');
		// } elseif (empty($row)) {
		// 	redirect('main/mainpage', 'refresh');
		// }



		if(!empty($row)){
		    $this->main->setBatchImport($row);
		    $this->main->insertData('hasil');
		}

		redirect('main/dosen', 'refresh');

	}

	public function save_dosen()
	{

		$pertanyaan = $this->per->as_object()->get_all();

		$where = array(
			'kelasID' => $this->session->kelasID,
			'thnAkademikID' => $this->session->tahun
		);

		$dsnkls = $this->dsnkelas->where($where)->get_all();

		//$query = $this->input->post('nim_mhs');


		$xh = 1;
		$xr = 1;
		$xp = 1;
		$xq = 1;
		$xy = 1;

		$nos = 1;


		// $tot = count((array)$dsnkls) * count((array)$pertanyaan);

		// $kadal = array();
		for ($j = 0; $j < count((array) $dsnkls); $j++) {
			$npp = $this->input->post('npp-' . $xr++ . '');
			// $npp1 = $this->input->post('npp-'.$xp++.'');
			// $npp2 = $this->input->post('npp-'.$xq++.'');
			// $npp3 = $this->input->post('npp-'.$xy++.'');
			$no = 1;
			$array = [];

			for ($i = 0; $i < count((array) $pertanyaan); $i++) {

				$char = date('his');
				$newID = $char . sprintf("%04s", $xh++);
				$id = $this->input->post('pertanyaanID-' . $npp . '-' . $no++ . '');
				// $id1 = $this->input->post('pertanyaanID-'.$npp2.'-'.$nos++.'');
				$per = $this->input->post('pertanyaanID-' . $npp . '-' . $id . '');

				$array['npp'] = $npp;
				$array['hasilID'] = $newID;
				$array['pertanyaanID'] = $id;
				$array['nim'] = $this->session->nim;
				$array['jawaban'] = $per;
				$array['thnAkademikID'] = $this->session->tahun;
				$array['created_at'] = date('Y-m-d H:i:s');

				$row[] = $array;
			}
		}

		// echo "<pre>";
		// print_r($row);
		// exit();

		if (!empty($row)) {
			$this->main->setBatchImport($row);
			$this->main->insertData('hasil_dosen');
		}

		$this->session->sess_destroy();

		redirect('main/keluar', 'refresh');
	}

	public function keluar()
	{

		$this->load->view('main/header');
		//$this->load->view('main/isi', $data);
		$this->load->view('main/keluar');
		$this->load->view('main/footer');
	}

	public function main()
	{
		redirect('main', 'refresh');
	}
}
