<?php
class NodeModel extends CI_Model 
{

	public $table	= 'site';

	function __construct()
	{
		parent::__construct();
	}
	
    function get_by_site($sites)
	{
	    $this->db->select('id,name,imei,consent_id');
	    if(is_array($sites) && count($sites) > 0) $this->db->where_in('subnet_id', $sites);
        elseif(!is_array($sites) && !empty($sites)) $this->db->where('subnet_id', $sites);
        $this->db->order_by('name','asc');
        return $this->db->get($this->table);
	}
    
    function get_all()
	{
	    $this->db->select('s.*, sub.name as subnet');
        $this->db->join('subnet sub', 's.subnet_id=sub.id');
		$this->db->order_by('s.name', 'asc');
		return $this->db->get($this->table.' s');
	}
    
	function get_by_id($id)
	{
	    $this->db->select('s.*, sub.name as subnet');
        $this->db->join('subnet sub', 's.subnet_id=sub.id');
		$this->db->where('s.id', $id);
		return $this->db->get($this->table.' s');
	}
	
    function get_by_ids($ids)
	{
	    $this->db->select('id,name,imei');
		$this->db->where_in('id', $ids);
		return $this->db->get($this->table);
	}
    
    function get_master($subnet_id)
	{
	    $this->db->select('id,name,imei');
		if(!empty($subnet_id)) $this->db->where('subnet_id', $subnet_id);
        $this->db->where('consent_id is null');
		return $this->db->get($this->table);
	}
    
    function get_childs($node_id)
    {
        $this->db->select('id');
        $this->db->where('consent_id', $node_id);
        $this->db->order_by('id', 'asc');
        $rs  = $this->db->get($this->table);
        $rows= $rs->result();
        
        $ids = array();
        $i   = 0;
        foreach($rows as $row)
        {
            $ids[$i] = $row->id;
            $i++;
        }
        $rs->free_result();
        return $ids;
    }
    
    function get_child($node_id)
	{
	    $this->db->select('id,name,pvoltage,vbatt,ibatt,iload,temperature_ctrl,temperature_batt,status,updated_at');
        $this->db->where_in('id', array_merge(array($node_id), $this->get_childs($node_id)));
		return $this->db->get($this->table);
	}
    
    function get_by_status($subnet_id, $status)
	{
	    $this->db->select('id,name,imei');
		if(!empty($subnet_id)) $this->db->where('subnet_id', $subnet_id);
        $this->db->where('status', $status);
		return $this->db->get($this->table);
	}
    
	function save($data)
	{
		return $this->db->insert($this->table, $data);		
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
    
    function get_status_statistic($status=1)
    {
        $this->db->select('count(id) as total');
        $this->db->from($this->table);
        if($status == 1) {
            $this->db->where('status', 1);
            $this->db->where('iload > 1');
        }
        elseif($status == 2) {
            $this->db->where('status', 1);
            $this->db->where('iload < 1');
        }
        else $this->db->where('iload', 0);
        $rs =  $this->db->get();
        $row= $rs->row();
        return intval($row->total);
    }
    
    function get_status_total()
    {
        $this->db->select('count(id) as total');
        $this->db->from($this->table);
        $rs =  $this->db->get();
        $row= $rs->row();
        return intval($row->total);
    }
}
?>