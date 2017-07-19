<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Auth extends REST_Controller 
{	
	function __construct()
	{
		parent::__construct();
		$this->load->model('UserModel','',TRUE);
	}
    
    function login_post()
    {
        $username = trim($this->input->post('username'));
		$password = md5(trim($this->input->post('password')));
		if($this->UserModel->authenticate($username, $password)) $this->response(array('success'=>true, 'msg'=>'Login success, please wait...'), REST_Controller::HTTP_OK);
		else $this->response(array('success'=>false, 'msg'=>'Incorrect username or password !'), REST_Controller::HTTP_OK);
    }
    
    function logout_get()
    {
        $this->session->sess_destroy();
        print $this->response(array('success'=>true, 'msg'=>'Logout successfully.'), REST_Controller::HTTP_OK);
    }
}
?>