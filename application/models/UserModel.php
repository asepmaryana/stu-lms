<?php
class UserModel extends CI_Model 
{
	public $table	= 'users';

	function __construct()
	{
		parent::__construct();
	}
	
	function count_all($notId=0)
	{
	    if(is_array($notId) && count($notId)>0) $this->db->where_not_in('id', $notId);
        elseif($notId != '') $this->db->where('id !=', $notId);
        
		return $this->db->count_all($this->table);
	}
	
	function get_paged_list($notId=0, $limit = 10, $offset = 0)
	{
		$this->db->select("users.*, roles.name as role, customers.name as customer");
		$this->db->join('roles', 'users.roles_id=roles.id', 'left');
        $this->db->join('customers', 'users.customers_id=customers.id', 'left');
        
        if(is_array($notId) && count($notId)>0) $this->db->where_not_in('users.id', $notId);
        elseif($notId != '') $this->db->where('users.id !=', $notId);
        
		$this->db->order_by('users.id','desc');
		return $this->db->get($this->table, $limit, $offset);
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
		$this->db->update($this->table, $data);
	}
	
	function delete($id)
	{
		$this->db->where('id', $id);
		$this->db->delete($this->table);
	}
	
	function authenticate($username, $password)
	{
	    $this->db->where('username', $username);
        $this->db->where('password', $password);
        $query = $this->db->get($this->table);
		if($query->num_rows() > 0) {
			$row 	= $query->row();
			$info 	= array(
                'uid'       => $row->id,
                'username'  => $row->username,
                'roles_id'	=> $row->roles_id,
                'customers_id'	=> $row->customers_id
            );
			$this->session->set_userdata($info);
			return true;
		}
        else return false;
	}
	
	function lists($opid=0)
	{
		if($opid != 1) $this->db->where('roles_id', $opid);
		return $this->db->get($this->table);
	}
}
?>