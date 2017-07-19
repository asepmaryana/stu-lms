<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Site extends REST_Controller 
{	
	function __construct()
	{
		parent::__construct();
		if($this->session->userdata('uid') == '') $this->response(array('success'=>false, 'msg'=>'LOGIN_REQUIRED'), REST_Controller::HTTP_OK);
		$this->load->model('SubnetModel','',TRUE);
        $this->load->model('NodeModel','',TRUE);
        $this->load->model('LampControlModel','',TRUE);
        $this->load->model('SiteModel','',TRUE);
	}
    
    function fetch_get()
    {
        $page    = $this->uri->segment(4);
        $size    = $this->uri->segment(5);
        if(empty($page) || $page == '0') $page = 1;
        if(empty($size) || $size == '0') $size = 10;
        $offset  = ($page-1)*$size;
        
        $rows   = $this->SiteModel->get_paged_list($size, $offset)->result();
        $total  = $this->SiteModel->count_all();
        $totalPage  = ceil($total/$size);
        $firstPage  = ($page == 0 || $page == 1) ? true : false;
        $lastPage   = ($page == $totalPage) ? true : false;
        $msg        = array('content'=>$rows, 'totalPage'=>$totalPage, 'firstPage'=>$firstPage, 'lastPage'=>$lastPage, 'page'=>intval($page), 'total'=>$total);
        $this->response($msg, REST_Controller::HTTP_OK);
    }
    
    function get_get()
    {
        $imei   = trim($this->uri->segment(4));
        if(empty($imei)) $imei = trim($this->input->post('imei'));
        if(!empty($imei)) {
            $rs = $this->LampControlModel->get_by_imei($imei);
            if($rs->num_rows()>0) {
                $row = $rs->row();
                print $row->name.':'.$row->set_status;
            }
            $rs->free_result();
        }
        #print 'site get ok';
    }
    
    function set_post()
    {
        $imei   = $this->uri->segment(4);
        $msg    = $this->uri->segment(5);
        if(empty($imei)) $imei = trim($this->input->post('imei'));
        if(empty($msg))  $msg  = trim($this->input->post('msg'));
        if(!empty($imei) && !empty($msg)) {
            if(count(explode(':', $msg)) == 3) {
                list($lamp, $cmd, $status) = explode(':', $msg);
                #print 'lamp='.$lamp.', cmd='.$cmd.', status='.$status;                
                #print 'msg valid.';
                if(strtoupper($status) == 'OK') {
                    $rs = $this->LampControlModel->get_by_imei_lamp($imei, $lamp, $cmd);
                    if($rs->num_rows()>0) {
                        $row = $rs->row();
                        $this->LampControlModel->delete($row->id);
                        $cmd = ($cmd == 'ON') ? '1' : '0';
                        $this->NodeModel->update($row->site_id, array('status'=>$cmd));
                        #print 'lamp control '.$row->id.' deleted successfully.';
                    }
                    #else print 'lamp control is empty.';
                    $rs->free_result();
                }
                #else print 'command is still failed.';                
            }
            #else print 'invalid msg format.';
        }
        #else print 'imei or msg is empty.';
    }
    
    function latlng_post()
    {
        $id     = $this->uri->segment(4);
        $lat    = $this->uri->segment(5);
        $lng    = $this->uri->segment(6);
        if(empty($id)) $id   = trim($this->input->post('id'));
        if(empty($lat)) $lat = trim($this->input->post('lat'));
        if(empty($lng)) $lng = trim($this->input->post('lng'));
        
        $rs = $this->NodeModel->get_by_id($id);
        if($rs->num_rows() > 0) {
            $values = array();
            $values['latitude'] = $lat;
            $values['longitude']= $lng;
            $res = $this->NodeModel->update($id, $values);
            if($res == FALSE) $this->response(array('success'=>false, 'msg'=>'Update position failed.'), REST_Controller::HTTP_OK);
            else $this->response(array('success'=>true, 'msg'=>'Update position succeed.'), REST_Controller::HTTP_OK);
        }
        else $this->response(array('success'=>false, 'msg'=>'Site is not registered.'), REST_Controller::HTTP_OK);
        $rs->free_result();
    }
    
    function all_get()
    {
        $this->response(array('success'=>true, 'rows'=>$this->SubnetModel->get_sites()->result()), REST_Controller::HTTP_OK);
    }
    
    function get_by_region()
    {
        $region_id = $this->uri->segment(4);
        if(empty($region_id)) print json_encode(array('success'=>false, 'rows'=>array()));
        else print json_encode(array('success'=>true, 'rows'=>$this->SubnetModel->getSiteByRegionId($region_id)->result()));
    }
    
    function get_node()
    {
        $site_id = $this->uri->segment(4);
        if(empty($site_id)) print json_encode(array('success'=>false, 'rows'=>array()));
        else print json_encode(array('success'=>true, 'rows'=>$this->NodeModel->get_by_subnet_id($site_id)->result()));
    }
    
    function cmd_post()
    {
        $site_id    = $this->uri->segment(4);
        $command    = trim($this->uri->segment(5));
        
        if(empty($site_id)) $site_id = $this->input->post('site_id');
        if(empty($command)) $command = trim($this->input->post('command'));
        
        if(empty($site_id)) print json_encode(array('success'=>false, 'status'=>200, 'msg'=>'Site is empty.'));
        elseif(empty($command)) print json_encode(array('success'=>false, 'status'=>200, 'msg'=>'Command is empty.'));
        elseif(!in_array(strtoupper($command), array('ON', 'OFF'))) print json_encode(array('success'=>false, 'status'=>200, 'msg'=>'Command is not valid. You have to set ON or OFF.'));
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
                    else print json_encode(array('success'=>false, 'status'=>200, 'msg'=>'Site master is not defined for selected site.'));
                    $rss->free_result();
                }
                
                if($imei == '') print json_encode(array('success'=>false, 'status'=>200, 'msg'=>'IMEI is not defined for selected site.'));
                else {
                    $values = array();
                    $values['imei']         = $imei;
                    $values['site_id']      = $site->id;
                    $values['set_status']   = strtoupper($command);
                    $result = $this->db->insert('lamp_controll', $values);
                    
                    if($result == FALSE) print json_encode(array('success'=>false, 'status'=>200, 'msg'=>'Set Turn '.$command.' was failed.'));
                    else print json_encode(array('success'=>true, 'status'=>200, 'msg'=>'Set Turn '.$command.' was succeed. Please wait some minutes.'));
                }
            }
            else print json_encode(array('success'=>false, 'status'=>200, 'msg'=>'Site is not registered.'));
            $rs->free_result();
        }
    }
    
    function savex_post()
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
                $values['updated_at']   = date('Y-m-d H:i:s');
                $this->db->where('id', $values['id']);
                $rs = $this->db->update($this->table, $values);
                if($rs == FALSE) $this->response(array('success'=>false, 'msg'=>'Update site failed.'));
                else $this->response(array('success'=>true, 'msg'=>'Update site successfully.', 'id'=>intval($values['id'])), REST_Controller::HTTP_OK);
            }
        }
    }
    
    function save_post()
    {
        $values = json_decode(file_get_contents('php://input'), true);
        if(empty($values['name'])) $this->response(array('success'=>false, 'msg'=>'Site name is required. '.$this->input->post('name')), REST_Controller::HTTP_OK);
        elseif(empty($values['pole'])) $this->response(array('success'=>false, 'msg'=>'Pole number is required.'), REST_Controller::HTTP_OK);
        elseif(empty($values['latitude'])) $this->response(array('success'=>false, 'msg'=>'Latitude is required.'), REST_Controller::HTTP_OK);
        elseif(empty($values['longitude'])) $this->response(array('success'=>false, 'msg'=>'Longitude is required.'), REST_Controller::HTTP_OK);
        elseif(empty($values['subnet_id'])) $this->response(array('success'=>false, 'msg'=>'Network group is required.'), REST_Controller::HTTP_OK);
        elseif($values['pos'] == 'master' && empty($values['imei'])) $this->response(array('success'=>false, 'msg'=>'IMEI is required.'), REST_Controller::HTTP_OK);
        elseif($values['pos'] == 'slave' && empty($values['consent_id'])) $this->response(array('success'=>false, 'msg'=>'Master references is required.'), REST_Controller::HTTP_OK);
        else
        {
            if($values['pos'] == 'master') $values['consent_id'] = null;
            unset($values['area']);
            unset($values['pos']);
            $id     = $this->SiteModel->save($values);
            $values['id']   = $id;
            $this->response(array('success'=>true, 'msg'=>'Site added.'), REST_Controller::HTTP_CREATED);
        }
    }
    
    function update_post()
    {
        $id    = $this->uri->segment(4);
        $values = json_decode(file_get_contents('php://input'), true);
        if(empty($values['name'])) $this->response(array('success'=>false, 'msg'=>'Site name is required. '.$this->input->post('name')), REST_Controller::HTTP_OK);
        elseif(empty($values['pole'])) $this->response(array('success'=>false, 'msg'=>'Pole number is required.'), REST_Controller::HTTP_OK);
        elseif(empty($values['latitude'])) $this->response(array('success'=>false, 'msg'=>'Latitude is required.'), REST_Controller::HTTP_OK);
        elseif(empty($values['longitude'])) $this->response(array('success'=>false, 'msg'=>'Longitude is required.'), REST_Controller::HTTP_OK);
        elseif(empty($values['subnet_id'])) $this->response(array('success'=>false, 'msg'=>'Network group is required.'), REST_Controller::HTTP_OK);
        elseif($values['pos'] == 'master' && empty($values['imei'])) $this->response(array('success'=>false, 'msg'=>'IMEI is required.'), REST_Controller::HTTP_OK);
        elseif($values['pos'] == 'slave' && empty($values['consent_id'])) $this->response(array('success'=>false, 'msg'=>'Master references is required.'), REST_Controller::HTTP_OK);
        else
        {
            if($values['pos'] == 'master') $values['consent_id'] = null;
            unset($values['area']);
            unset($values['pos']);
            $this->SiteModel->update($id, $values);
            $this->response(array('success'=>true, 'msg'=>'Site updated.'), REST_Controller::HTTP_OK);
        }
    }
}
?>