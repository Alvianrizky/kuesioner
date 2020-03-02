<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Main_model extends CI_Model
{
	private $_batchImport;

	public function setBatchImport($batchImport)
	{
		$this->_batchImport = $batchImport;
	}

	public function insertData($mytable)
	{
		$data = $this->_batchImport;
		$this->db->insert_batch($mytable, $data);
	}

	public function tampilgroup()
	{
		return $this->db->get('group_pertanyaan');
	}

	public function tampil($groupID)
	{
		//$query = $this->db->query("SELECT * FROM pertanyaan where groupID='.$groupID.'");
		$this->db->select('*');
		$this->db->from('pertanyaan');
		$this->db->where('groupID', $groupID);
		$query = $this->db->get();

		return $query;
	}
}
