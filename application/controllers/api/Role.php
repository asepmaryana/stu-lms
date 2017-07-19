<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Role extends REST_Controller 
{	
	function __construct()
	{
		parent::__construct();
        if($this->session->userdata('uid') == '') $this->response(array('success'=>false, 'msg'=>'LOGIN_REQUIRED'), REST_Controller::HTTP_OK);
        $this->load->model('RoleModel','',TRUE);
	}  
    
    function all_get()
    {
        $this->response(array('success'=>true, 'data'=>$this->RoleModel->get_list()->result()), REST_Controller::HTTP_OK);
    }
}
?>