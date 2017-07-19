<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Subnet extends REST_Controller 
{	
	function __construct()
	{
		parent::__construct();
		if($this->session->userdata('uid') == '') $this->response(array('success'=>false, 'msg'=>'LOGIN_REQUIRED'), REST_Controller::HTTP_OK);
		$this->load->model('SubnetModel','',TRUE);
        $this->load->model('NodeModel','',TRUE);
	}
    
    function all_get()
    {
		$data = $this->SubnetModel->get_sites()->result();
		$this->response(array('success'=>true, 'data'=>$data), REST_Controller::HTTP_OK);
    }
    
    function get_by_region_get()
    {
        $region_id = $this->uri->segment(4);
        if(empty($region_id)) $this->response(array('success'=>true, 'data'=>array()), REST_Controller::HTTP_OK);
        else $this->response(array('success'=>true, 'data'=>$this->SubnetModel->getSiteByRegionId($region_id)->result()), REST_Controller::HTTP_OK);
    }
    
    function get_node_get()
    {
        $site_id = $this->uri->segment(4);
        if(empty($site_id)) $this->response(array('success'=>true, 'data'=>array()), REST_Controller::HTTP_OK);
        else $this->response(array('success'=>true, 'data'=>$this->NodeModel->get_by_subnet_id($site_id)->result()), REST_Controller::HTTP_OK);
    }
    
    function region_get()
    {
        $this->response(array('success'=>true, 'data'=>$this->SubnetModel->get_regions()->result()), REST_Controller::HTTP_OK);
    }
    
    function area_get()
    {
        $regions_id = explode('_', $this->uri->segment(4));
        $this->response(array('success'=>true, 'data'=>$this->SubnetModel->get_areas($regions_id)->result()), REST_Controller::HTTP_OK);
    }
    
}
?>