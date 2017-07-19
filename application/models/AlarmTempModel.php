<?php
class AlarmTempModel extends CI_Model 
{
	public $table	= 'alarm_temp';

	function __construct()
	{
		parent::__construct();
	}
	
    function get_total($node_id)
    {
        $this->db->select('count(id) as total');
        $this->db->where('site_id', $node_id);
        $rs = $this->db->get($this->table);
        if($rs->num_rows() > 0) {
            $row = $rs->row();
            return intval($row->total);
        }
        else return 0;
    }
    
    function get_paged_list($node_id, $limit, $offset, $sort, $order)
    {
        $this->db->where('site_id', $node_id);
        $this->db->order_by($sort, $order);
        return $this->db->get($this->table.'_view', $limit, $offset);
    }
    
    function get_list($node_id)
    {
        if(!empty($node_id)) $this->db->where('site_id', $node_id);
        $this->db->order_by('dtime', 'desc');
        return $this->db->get($this->table.'_view');      
    }
    
    function get_total_model($model, $model_id, $alarm_id, $from, $to)
    {
        $this->db->select('count(id) as total');
        if($model == 'node' && $model_id != '_') $this->db->where_in('site_id', explode('_', $model_id));
        elseif($model == 'site' && $model_id != '_') $this->db->where_in('subnet_id', explode('_', $model_id));
        elseif($model == 'region' && $model_id != '_') $this->db->where_in('region_id', explode('_', $model_id));
        
        if($alarm_id != '' && $alarm_id != '_') $this->db->where_in('alarm_list_id', explode('_', $alarm_id));
        
        $this->db->where('dtime >=', $from);
        $this->db->where('dtime <=', $to);
        
        $rs = $this->db->get($this->table.'_view');
        if($rs->num_rows() > 0) {
            $row = $rs->row();
            return intval($row->total);
        }
        else return 0;
    }
    
    function get_paged_model($model, $model_id, $alarm_id, $from, $to, $limit, $offset, $sort, $order)
    {
        if($model == 'site' && $model_id != '_') $this->db->where_in('site_id', explode('_', $model_id));
        elseif($model == 'area' && $model_id != '_') $this->db->where_in('subnet_id', explode('_', $model_id));
        elseif($model == 'region' && $model_id != '_') $this->db->where_in('region_id', explode('_', $model_id));
        
        if($alarm_id != '' && $alarm_id != '_') $this->db->where_in('alarm_list_id', explode('_', $alarm_id));
        
        $this->db->where('dtime >=', $from.' 00:00:00');
        $this->db->where('dtime <=', $to.' 23:59:59');
        
        $this->db->order_by($sort, $order);
        return $this->db->get($this->table.'_view', $limit, $offset);
    }
    
    function get_severity_statistic()
    {
        $this->db->select('als.name,als.color,count(al.id) as total');
        $this->db->from('severity als ');
        $this->db->join('alarm_temp al', 'als.id=al.severity_id', 'left');
        $this->db->group_by('als.name,als.color');
        return $this->db->get();
    }
    
    function get_alarm_statistic()
    {
        $this->db->select('al.name,count(alt.id) as total');
        $this->db->from('alarm_list al');
        $this->db->join('alarm_temp alt', 'alt.alarm_list_id=al.id', 'left');
        $this->db->group_by('al.name');
        return $this->db->get();
    }
}
?>