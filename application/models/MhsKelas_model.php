<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class MhsKelas_model extends MY_Model
{
	protected $column_order = array(null, 'mhsKelasID','kelasID','nim','thnAkademikID'); 
    protected $column_search = array('mhsKelasID','kelas.nama_kelas','mahasiswa.nama','tahunakademik.thnAkademik');
    protected $order = array('mhsKelasID' => 'asc');

	public function __construct()
	{
        $this->table = 'mahasiswa_kelas';
        $this->primary_key = 'mhsKelasID';
        $this->fillable = $this->column_order;
        $this->timestamps = TRUE;

        $this->has_one['tahunakademik'] = array('ThnAkademik_model', 'thnAkademikID', 'thnAkademikID');
        $this->has_one['kelas'] = array('Kelas_model','kelasID','KelasID');
        $this->has_one['mahasiswa'] = array('Mahasiswa_model','nim','nim');

		parent::__construct();
	
	}

	private function _get_datatables_query()
    {
        
        $this->db->select($this->column_search);

        $this->db->from($this->table);
        $this->db->join('kelas', 'kelas.kelasID=mahasiswa_kelas.kelasID');
        $this->db->join('mahasiswa', 'mahasiswa.nim=mahasiswa_kelas.nim');
        $this->db->join('tahunakademik', 'tahunakademik.thnAkademikID=mahasiswa_kelas.thnAkademikID');

        $i = 0;
        
        if(!empty($_POST['search']['value']))
        {
            foreach ($this->column_search as $item)
            {
                if($_POST['search']['value'])
                {
                    
                    if($i===0)
                    {
                        $this->db->group_start(); 
                        $this->db->like($item, $_POST['search']['value']);
                    }
                    else
                    {
                        $this->db->or_like($item, $_POST['search']['value']);
                    }

                    if(count($this->column_search) - 1 == $i)
                        $this->db->group_end();
                }
                $i++;
            }
        }
        
        if(isset($_POST['order']))
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();

        $length = isset($_POST['length']) ? $_POST['length'] : 0;
        if($length != -1){
        	$start  = isset($_POST['start']) ? $_POST['start'] : 0;        	
        	$this->db->limit($length, $start);
        }

        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }


}
/* End of file '/Dosen_model.php' */
/* Location: ./application/models/Dosen_model.php */