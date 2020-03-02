<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Hasil extends CI_Controller
{

	protected $page_header = 'Hasil Management';

	public function __construct()
	{
		parent::__construct();


		$this->load->model(array('Hasil_model' => 'hasil', 'ThnAkademik_model' => 'akademik', 'Mahasiswa_model' => 'mahasiswa', 'Pertanyaan_model' => 'pertanyaan'));
		$this->load->library(array('ion_auth', 'form_validation', 'template'));
		$this->load->helper('bootstrap_helper');
	}

	public function index()
	{

		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		}

		$opt_pertanyaan     = $this->pertanyaan->as_dropdown('pertanyaan')->get_all();

		$data['page_header']   = $this->page_header;
		$data['panel_heading'] = 'Hasil List';
		$data['page']         = '';
		// $data['pertanyaan'] = form_dropdown('pertanyaanID', $opt_pertanyaan, '', 'id="pertanyaan" class="form-control select2"');
		// $data['jawaban'] = form_input(array('name'=>'hasilID', 'id'=>'jawaban', 'class'=>'form-control', 'value'=>''));

		$this->template->backend('hasil_v', $data);
	}

	public function get_hasil()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		}

		$list = $this->hasil->get_datatables();
		$data = array();
		$no = isset($_POST['start']) ? $_POST['start'] : 0;
		foreach ($list as $field) {
			$id = $field->hasilID;

			$url_view   = 'view_data(' . $id . ');';
			$url_update = 'update_data(' . $id . ');';
			$url_delete = 'delete_data(' . $id . ');';

			$no++;
			$row = array();
			// $row[] = '<button id="view_data" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-eye-open"></i></button>';
			$row[] = $no;
			$row[] = $field->hasilID;
			$row[] = $field->pertanyaan;
			$row[] = $field->nama;
			$row[] = $field->jawaban;
			$row[] = $field->thnAkademik;
			$data[] = $row;
		}

		$draw = isset($_POST['draw']) ? $_POST['draw'] : null;

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $this->hasil->count_all(),
			"recordsFiltered" => $this->hasil->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}


	public function view()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		}

		$id = $this->input->post('hasilID');

		$query = $this->hasil
			->with_pertanyaan('fields:pertanyaan')
			->with_mahasiswa('fields:nama')
			->with_tahunakademik('fields:thnAkademik')
			->where('hasilID', $id)
			->get();

		$data = array();
		if ($query) {
			$data = array(
				'hasilID' => $query->hasilID,
				'pertanyaanID' => $query->pertanyaan->pertanyaan,
				'nim' => $query->mahasiswa->nama,
				'jawaban' => $query->jawaban,
				'thnAkademikID' => $query->tahunakademik->thnAkademik
			);
		}

		echo json_encode($data);
	}

	public function form_filter()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		}

		$opt_pertanyaan     = $this->pertanyaan->as_dropdown('pertanyaan')->get_all();
		$opt_mahasiswa     = $this->mahasiswa->as_dropdown('nama')->get_all();
		$opt_akademik     = $this->akademik->as_dropdown('thnAkademik')->get_all();

		$row = array();
		if ($this->input->post('hasilID')) {
			$id      = $this->input->post('hasilID');
			$query   = $this->hasil->where('hasilID', $id)->get();
			if ($query) {
				$row = array(
					'hasilID'       => $query->hasilID,
					'pertanyaanID'     => $query->pertanyaanID
					// 'nim'      => $query->nim,
					// 'thnAkademikID'      => $query->thnAkademikID
				);
			}
			$row = (object) $row;
		}

		$uni = "" . date('ymdhis') . "";

		$data = array(
			'hidden' => form_hidden('aksi', !empty($row->hasilID) ? 'update' : 'create'),
			'hasilID' => form_input(array('name' => 'hasilID', 'id' => 'datepicker', 'class' => 'form-control', 'value' => !empty($row->hasilID) ? $row->hasilID : $uni)),
			'pertanyaanID' => form_dropdown('pertanyaanID', $opt_pertanyaan, !empty($row->pertanyaanID) ? $row->pertanyaanID : '', 'class="chosen-select"')
			//  'nim' => form_dropdown('nim', $opt_mahasiswa, !empty($row->nim) ? $row->nim : '', 'class="chosen-select"'),
			//  'jawaban' => form_input(array('name'=>'jawaban', 'id'=>'jawaban', 'class'=>'form-control', 'value'=>!empty($row->jawaban) ? $row->jawaban : '')),
			//  'thnAkademikID' => form_dropdown('thnAkademikID', $opt_akademik, !empty($row->thnAkademikID) ? $row->thnAkademikID : '', 'class="chosen-select"')
		);

		echo json_encode($data);
	}


	public function save_hasil()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		}

		$rules = array(
			'insert' => array(
				array('field' => 'hasilID', 'label' => 'Hasil ID', 'rules' => 'trim|required|is_unique[hasil.hasilID]|max_length[12]'),
				array('field' => 'pertanyaanID', 'label' => 'Pertanyaan', 'rules' => 'max_length[11]'),
				array('field' => 'jawaban', 'label' => 'Jawaban', 'rules' => 'trim|required|max_length[150]'),
				array('field' => 'nim', 'label' => 'Nama Mahasiswa', 'rules' => 'max_length[11]'),
				array('field' => 'thnAkademikID', 'label' => 'Tahun Akademik', 'rules' => 'max_length[20]')
			),
			'update' => array(
				array('field' => 'hasilID', 'label' => 'Hasil ID', 'rules' => 'trim|required|max_length[12]'),
				array('field' => 'pertanyaanID', 'label' => 'Pertanyaan', 'rules' => 'max_length[11]'),
				array('field' => 'jawaban', 'label' => 'Jawaban', 'rules' => 'trim|required|max_length[150]'),
				array('field' => 'nim', 'label' => 'Nama Mahasiswa', 'rules' => 'max_length[11]'),
				array('field' => 'thnAkademikID', 'label' => 'Tahun Akademik', 'rules' => 'max_length[20]')
			),
		);

		$row = array(
			'hasilID' => $this->input->post('hasilID'),
			'pertanyaanID' => $this->input->post('pertanyaanID'),
			'nim' => $this->input->post('nim'),
			'jawaban' => $this->input->post('jawaban'),
			'thnAkademikID'      => $this->input->post('thnAkademikID')
		);

		$code = 0;

		if ($this->input->post('aksi') == 'create') {

			$this->form_validation->set_rules($rules['insert']);

			if ($this->form_validation->run() == true) {

				$this->hasil->insert($row);

				$error =  $this->db->error();
				if ($error['code'] <> 0) {
					$code = 1;
					$notifications = $error['code'] . ' : ' . $error['message'];
				} else {
					$notifications = 'Success Insert Data';
				}
			} else {
				$code = 1;
				$notifications = validation_errors('<p>', '</p>');
			}
		} else {

			$this->form_validation->set_rules($rules['update']);

			if ($this->form_validation->run() == true) {

				$id = $this->input->post('hasilID');

				$this->hasil->where('hasilID', $id)->update($row);

				$error =  $this->db->error();
				if ($error['code'] <> 0) {
					$code = 1;
					$notifications = $error['code'] . ' : ' . $error['message'];
				} else {
					$notifications = 'Success Update Data';
				}
			} else {
				$code = 1;
				$notifications = validation_errors('<p>', '</p>');
			}
		}

		$notifications = ($code == 0) ? notifications('success', $notifications) : notifications('error', $notifications);

		echo json_encode(array('message' => $notifications, 'code' => $code));
	}

	public function delete()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		}

		$code = 0;

		$id = $this->input->post('hasilID');

		$this->hasil->where('hasilID', $id)->delete();

		$error =  $this->db->error();
		if ($error['code'] <> 0) {
			$code = 1;
			$notifications = $error['code'] . ' : ' . $error['message'];
		} else {
			$notifications = 'Success Delete Data';
		}

		$notifications = ($code == 0) ? notifications('success', $notifications) : notifications('error', $notifications);

		echo json_encode(array('message' => $notifications, 'code' => $code));
	}
}
