<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Node extends REST_Controller 
{	
	function __construct()
	{
		parent::__construct();
		if($this->session->userdata('uid') == '') $this->response(array('success'=>false, 'msg'=>'LOGIN_REQUIRED'), REST_Controller::HTTP_OK);
		$this->load->model('SubnetModel','',TRUE);
        $this->load->model('NodeModel','',TRUE);
        $this->load->model('LampControlModel','',TRUE);
	}
    
    function all_get()
    {
		$this->response(array('success'=>true, 'data'=>$this->NodeModel->get_all()->result()), REST_Controller::HTTP_OK);
    }
    
    function site_get()
    {
        $subnet_id = $this->uri->segment(4);
        if($subnet_id == '' || $subnet_id == '_') $subnet_id = '';
        else $subnet_id = explode('_', $subnet_id);
        $this->response(array('success'=>true, 'data'=>$this->NodeModel->get_by_site($subnet_id)->result()), REST_Controller::HTTP_OK);        
    }
    
    function get_by_region_get()
    {
        $region_id = $this->uri->segment(4);
        if(empty($region_id)) $this->response(array('success'=>false, 'data'=>array()), REST_Controller::HTTP_OK);
        else $this->response(array('success'=>true, 'data'=>$this->SubnetModel->getSiteByRegionId($region_id)->result()), REST_Controller::HTTP_OK);
    }
    
    function child_get()
    {
        $node_id = $this->uri->segment(4);
        if(empty($node_id)) $this->response(array('success'=>false, 'data'=>array()), REST_Controller::HTTP_OK);
        else $this->response(array('success'=>true, 'data'=>$this->NodeModel->get_child($node_id)->result()), REST_Controller::HTTP_OK);
    }
    
    function get_node_get()
    {
        $site_id = $this->uri->segment(4);
        if(empty($site_id)) $this->response(array('success'=>false, 'data'=>array()), REST_Controller::HTTP_OK);
        else $this->response(array('success'=>true, 'rows'=>$this->NodeModel->get_by_subnet_id($site_id)->result()), REST_Controller::HTTP_OK);
    }
    
    function master_get()
    {
		$this->response(array('success'=>true, 'data'=>$this->NodeModel->get_master('')->result()), REST_Controller::HTTP_OK);
    }
    
    function save_postx()
    {
        $this->response(array('success'=>false, 'msg'=>'xxx'), REST_Controller::HTTP_OK);
    }
    
    function save_post()
    {
        $values = json_decode(file_get_contents('php://input'), true);
        if(empty($values['name'])) $this->response(array('success'=>false, 'msg'=>'Site name is required. '.$this->input->post('name')), REST_Controller::HTTP_OK);
        elseif(empty($values['pole'])) $this->response(array('success'=>false, 'msg'=>'Pole number is required.'), REST_Controller::HTTP_OK);
        elseif(empty($values['latitude'])) $this->response(array('success'=>false, 'msg'=>'Latitude is required.'), REST_Controller::HTTP_OK);
        elseif(empty($values['longitude'])) $this->response(array('success'=>false, 'msg'=>'Longitude is required.'), REST_Controller::HTTP_OK);
        elseif(empty($values['subnet_id'])) $this->response(array('success'=>false, 'msg'=>'Network group is required.'), REST_Controller::HTTP_OK);
        elseif($values['master'] == '1' && empty($values['imei'])) $this->response(array('success'=>false, 'msg'=>'IMEI is required.'), REST_Controller::HTTP_OK);
        elseif($values['master'] == '0' && empty($values['consent_id'])) $this->response(array('success'=>false, 'msg'=>'Master references is required.'), REST_Controller::HTTP_OK);
        else {
            unset($values['master']);
            if(!isset($values['id'])) {
                $values['created_at']   = date('Y-m-d H:i:s');
                $this->db->insert('site', $values);
                $id = $this->db->insert_id();
                $this->response(array('success'=>true, 'msg'=>'Create site successfully.', 'id'=>intval($id)), REST_Controller::HTTP_OK);
            }
            else {
                $rs = $this->db->update($values['id'], $values);
                if($rs == FALSE) $this->response(array('success'=>false, 'msg'=>'Update site failed.'));
                else $this->response(array('success'=>true, 'msg'=>'Update site successfully.', 'id'=>intval($values['id'])), REST_Controller::HTTP_OK);
            }
        }
    }
    
    function delete_get()
    {
        $site_id    = $this->uri->segment(4);
        #if(empty($site_id)) $site_id = $this->input->post('site_id');
        if(empty($site_id)) $this->response(array('success'=>false, 'msg'=>'Site is empty.'), REST_Controller::HTTP_OK);
        else {
            $res = $this->NodeModel->delete($site_id);
            if($res == FALSE) $this->response(array('success'=>false, 'msg'=>'Site delete failed.'), REST_Controller::HTTP_OK);
            else $this->response(array('success'=>true, 'msg'=>'Site delete successfully.'), REST_Controller::HTTP_OK);
        }
    }
    
    function cmd_post()
    {
        $site_id    = $this->uri->segment(4);
        $command    = trim($this->uri->segment(5));
        
        if(empty($site_id)) $site_id = $this->input->post('site_id');
        if(empty($command)) $command = trim($this->input->post('command'));
        
        if(empty($site_id)) $this->response(array('success'=>false, 'msg'=>'Site is empty.'), REST_Controller::HTTP_OK);
        elseif(empty($command)) $this->response(array('success'=>false, 'msg'=>'Command is empty.'), REST_Controller::HTTP_OK);
        elseif(!in_array(strtoupper($command), array('ON', 'OFF'))) $this->response(array('success'=>false, 'msg'=>'Command is not valid. You have to set ON or OFF.'), REST_Controller::HTTP_OK);
        else {
            $rs = $this->NodeModel->get_by_id($site_id);
            if($rs->num_rows() > 0) {
                $site = $rs->row();
                $imei = '';
                if(trim($site->imei) != '') $imei = trim($site->imei);
                else {
                    $rss = $this->NodeModel->get_by_id($site->consent_id);
                    if($rss->num_rows() > 0) {
                        $sitem = $rss->row();
                        $imei = trim($sitem->imei);
                    }
                    else $this->response(array('success'=>false, 'msg'=>'Site master is not defined for selected site.'), REST_Controller::HTTP_OK);
                    $rss->free_result();
                }
                
                if($imei == '') $this->response(array('success'=>false, 'msg'=>'IMEI is not defined for selected site.'), REST_Controller::HTTP_OK);
                else {
                    $lamp   = strtoupper($site->name);
                    $cmd    = strtoupper($command);
                    $rsc    = $this->LampControlModel->get_by_imei_lamp($imei, $lamp, $cmd);
                    if($rsc->num_rows()>0) $this->response(array('success'=>false, 'msg'=>'Please wait for previous command.'), REST_Controller::HTTP_OK);
                    else {
                        $values = array();
                        $values['imei']         = $imei;
                        $values['site_id']      = $site->id;
                        $values['set_status']   = $cmd;
                        $result = $this->db->insert('lamp_controll', $values);
                    
                        if($result == FALSE) $this->response(array('success'=>false, 'msg'=>'Set Turn '.$command.' was failed.'), REST_Controller::HTTP_OK);
                        else $this->response(array('success'=>true, 'msg'=>'Set Turn '.$command.' was succeed. Please wait some minutes.'), REST_Controller::HTTP_OK);
                    }
                    $rsc->free_result();
                }
            }
            else $this->response(array('success'=>false, 'msg'=>'Site is not registered.'), REST_Controller::HTTP_OK);
            $rs->free_result();
        }
    }
    
    function turn_get()
    {
        $command    = strtoupper(trim($this->uri->segment(4)));
        if(empty($command)) $this->response(array('success'=>false, 'msg'=>'Command is empty.'), REST_Controller::HTTP_OK);
        elseif(!in_array($command, array('ON','OFF'))) $this->response(array('success'=>false, 'msg'=>'Invalid command. Please use ON or OFF'), REST_Controller::HTTP_OK);
        else {
            
            if($command == 'ON') {
                $rs = $this->NodeModel->get_by_status($subnet_id, '0');
                if($rs->num_rows()>0){
                    $rows  = $rs->result();
                    $lamps = array();
                    $i=0;
                    foreach($rows as $row){
                        $lamps[$i] = $row->id;
                        $i++;
                    }
                    $rss = $this->LampControlModel->get_by_ids_cmd($lamps, $command);
                    if($rss->num_rows() == count($lamps))  $this->response(array('success'=>false, 'msg'=>'All site is waiting to turn ON.'), REST_Controller::HTTP_OK);
                    else {
                        $rows  = $rss->result();                        
                        #remove for already turn ON
                        foreach($rows as $row) {
                            if (($key = array_search($row->id, $lamps)) !== false) {
                                unset($lamps[$key]);
                            }
                        }
                    }
                    $rss->free_result();
                    
                    $rsn    = $this->NodeModel->get_by_ids($lamps)->result();
                    foreach($rsn as $row) {
                        $data   = array();
                        $data['site_id']    = $row->id;
                        $data['imei']       = $row->imei;
                        $data['set_status'] = $command;
                        $this->db->insert('lamp_controll', $data);
                    }
                }
                else $this->response(array('success'=>false, 'msg'=>'All site has been turned ON.'), REST_Controller::HTTP_OK);
                $rs->free_result();
            }
            else {
                $rs = $this->NodeModel->get_by_status($subnet_id, '1');
                if($rs->num_rows()>0){
                    $rows  = $rs->result();
                    $lamps = array();
                    $i=0;
                    foreach($rows as $row){
                        $lamps[$i] = $row->id;
                        $i++;
                    }
                    $rss = $this->LampControlModel->get_by_ids_cmd($lamps, $command);
                    if($rss->num_rows() == count($lamps))  $this->response(array('success'=>false, 'msg'=>'All site is waiting to turn OFF.'), REST_Controller::HTTP_OK);
                    else {
                        $rows  = $rss->result();                    
                        #remove for already turn OFF
                        foreach($rows as $row) {
                            if (($key = array_search($row->id, $lamps)) !== false) {
                                unset($lamps[$key]);
                            }
                        }
                    }
                    $rss->free_result();
                    
                    $rsn    = $this->NodeModel->get_by_ids($lamps)->result();
                    foreach($rsn as $row) {
                        $data   = array();
                        $data['site_id']    = $row->id;
                        $data['imei']       = $row->imei;
                        $data['set_status'] = $command;
                        $this->db->insert('lamp_controll', $data);
                    }
                }
                else $this->response(array('success'=>false, 'msg'=>'All site has been turned OFF.'), REST_Controller::HTTP_OK);
                $rs->free_result();
            }
        }        
    }
    
    function clear_post()
    {
        $this->response(array('success'=>false, 'msg'=>'Disabled function.'), REST_Controller::HTTP_OK);
    }
    
    function info_get()
    {
        $node_id = $this->uri->segment(4);
        if(empty($node_id)) $this->response(array('success'=>false, 'data'=>array()), REST_Controller::HTTP_OK);
        else $this->response(array('success'=>true, 'data'=>$this->NodeModel->get_by_id($node_id)->row()), REST_Controller::HTTP_OK);
    }
    
    function statistic_get()
    {
        $data   = array();
        $data['TOTAL']  = $this->NodeModel->get_status_total();
        $data['ON']     = $this->NodeModel->get_status_statistic(1);
        $data['STANDBY']= $this->NodeModel->get_status_statistic(2);
        $data['OFF']    = $data['TOTAL'] - ($data['ON']+$data['STANDBY']);
        $this->response(array('success'=>true, 'data'=>$data), REST_Controller::HTTP_OK);
    }
}
?>