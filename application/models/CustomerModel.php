<?php
class CustomerModel extends CI_Model 
{
	public $table	= 'customers';

	function __construct()
	{
		parent::__construct();
	}
	
    function count_all()
	{
		return $this->db->count_all($this->table);
	}
	
	function get_paged_list($limit = 10, $offset = 0)
	{
		$this->db->order_by('name','asc');
		return $this->db->get($this->table, $limit, $offset);
	}
    
    function get_all()
	{
		$this->db->order_by('name', 'asc');
		return $this->db->get($this->table);
	}
    
	function get_by_id($id)
	{
		$this->db->where('id', $id);
		return $this->db->get($this->table);
	}
    
	function save($data)
	{
		$this->db->insert($this->table, $data);
        return $this->db->insert_id();
	}
	
	function update($id, $data)
	{
		$this->db->where('id', $id);
		return $this->db->update($this->table, $data);
	}
	
	function delete($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete($this->table);
	}	
	
	function clear()
	{
		return $this->db->truncate($this->table);
	}
}
?>