<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

class Import_model extends CI_Model
{
    private $_batchImport;
    private $_batchUpdate;

    public function setBatchImport($batchImport)
    {
        $this->_batchImport = $batchImport;
    }

    public function setBatchUpdate($batchUpdate)
    {
        $this->_batchUpdate = $batchUpdate;
    }

    public function importData($mytable)
    {
        $data = $this->_batchImport;
        $this->db->insert_batch($mytable, $data);
    }

    public function updateData($mytable ,$id)
    {
        //$where = $this->db->where('npp',$id);
        $data = $this->_batchUpdate;
        $this->db->update_batch($mytable, $data, $id);
    }

    public function dosenList()
    {
        $this->db->select(array('e.npp','e.nama','e.created_at'));
        $this->db->from('dosen as e');
        $query = $this->db->get();
        return $query->result_array();
    }
}