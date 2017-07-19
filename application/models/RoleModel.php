<?php
class RoleModel extends CI_Model 
{
	public $table	= 'roles';

	function __construct()
	{
		parent::__construct();
	}
	
    function get_list()
    {
        $this->db->select('id,name');
        $this->db->order_by('name', 'asc');
        return $this->db->get($this->table);
    }
    
    function count_all()
	{
		return $this->db->count_all($this->table);
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
}
?>