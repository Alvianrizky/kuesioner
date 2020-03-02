<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Hasil_model extends MY_Model
{
	protected $column_order = array(null, 'hasilID', 'pertanyaanID', 'nim', 'jawaban', 'thnAkademikID');
	protected $column_search = array('hasilID', 'pertanyaan.pertanyaan', 'mahasiswa.nama', 'jawaban', 'tahunakademik.thnAkademik');
	protected $order = array('hasilID' => 'asc');

	public function __construct()
	{
		$this->table       = 'hasil';
		$this->primary_key = 'hasilID';
		$this->fillable    = $this->column_order;
		$this->timestamps  = TRUE;

		$this->has_one['tahunakademik'] = array('ThnAkademik_model', 'thnAkademikID', 'thnAkademikID');
		$this->has_one['pertanyaan'] = array('Pertanyaan_model', 'pertanyaanID', 'pertanyaanID');
		$this->has_one['mahasiswa'] = array('Mahasiswa_model', 'nim', 'nim');

		parent::__construct();
	}

	private function _get_datatables_query()
	{

		$this->db->select($this->column_search);

		if ($this->input->post('pertanyaan')) {
			$this->db->where('pertanyaanID', $this->input->post('pertanyaanID'));
		}
		if ($this->input->post('jawaban')) {
			$this->db->like('jawaban', $this->input->post('jawaban'));
		}
		$this->db->from($this->table);
		$this->db->join('pertanyaan', 'pertanyaan.pertanyaanID=hasil.pertanyaanID');
		$this->db->join('mahasiswa', 'mahasiswa.nim=hasil.nim');
		$this->db->join('tahunakademik', 'tahunakademik.thnAkademikID=hasil.thnAkademikID');



		$i = 0;

		// if(!empty($_POST['search']['value']))
		// {
		foreach ($this->column_search as $item) {
			if ($_POST['search']['value']) {

				if ($i === 0) {
					$this->db->group_start();
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($this->column_search) - 1 == $i)
					$this->db->group_end();
			}
			$i++;
			// }
		}

		if (isset($_POST['order'])) {
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order)) {
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();

		$length = isset($_POST['length']) ? $_POST['length'] : 0;
		if ($length != -1) {
			$start  = isset($_POST['start']) ? $_POST['start'] : 0;
			$this->db->limit($length, $start);
		}

		$query = $this->db->get();
		return $query->result();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}
}
/* End of file '/Products_model.php' */
/* Location: ./application/models/Products_model.php */
