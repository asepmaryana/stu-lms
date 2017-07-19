<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class User extends REST_Controller 
{	
	function __construct()
	{
		parent::__construct();
		if($this->session->userdata('uid') == '') $this->response(array('success'=>false, 'msg'=>'LOGIN_REQUIRED'), REST_Controller::HTTP_OK);
		$this->load->model('UserModel','',TRUE);
	}
    
    function info_get()
    {
        $uid = $this->session->userdata('uid');
        $user= $this->UserModel->get_by_id($uid)->row();
        $this->response(array('success'=>true, 'data'=>$user), REST_Controller::HTTP_OK);
    }
    
    function fetch_get()
    {
        $page    = $this->uri->segment(4);
        $size    = $this->uri->segment(5);
        if(empty($page) || $page == '0') $page = 1;
        if(empty($size) || $size == '0') $size = 10;
        $offset  = ($page-1)*$size;
        
        $notId  = $this->session->userdata('uid');
        $rows   = $this->UserModel->get_paged_list($notId, $size, $offset)->result();
        $total  = $this->UserModel->count_all($notId);
        $totalPage  = ceil($total/$size);
        $firstPage  = ($page == 0 || $page == 1) ? true : false;
        $lastPage   = ($page == $totalPage) ? true : false;
        $msg        = array('success'=>true, 'content'=>$rows, 'totalPage'=>$totalPage, 'firstPage'=>$firstPage, 'lastPage'=>$lastPage, 'page'=>intval($page), 'total'=>$total);
        $this->response($msg, REST_Controller::HTTP_OK);
    }
    
    function save_post()
    {
        $values = json_decode(file_get_contents('php://input'), true);
        if(empty($values['username'])) $this->response(array('success'=>false, 'msg'=>'Username is required.'), REST_Controller::HTTP_OK);
        elseif(empty($values['password'])) $this->response(array('success'=>false, 'msg'=>'Password is required.'), REST_Controller::HTTP_OK);
        elseif(empty($values['name'])) $this->response(array('success'=>false, 'msg'=>'Fullname is required.'), REST_Controller::HTTP_OK);
        elseif(empty($values['roles_id'])) $this->response(array('success'=>false, 'msg'=>'Role is required.'), REST_Controller::HTTP_OK);
        else
        {
            unset($values['role']);
            unset($values['customer']);
            $values['password']     = md5($values['password']);
            $values['created_at']   = date('Y-m-d H:i:s');
            $id     = $this->UserModel->save($values);
            $values['id']   = $id;
            $this->response(array('success'=>true, 'msg'=>'User created.'), REST_Controller::HTTP_CREATED);
        }
    }
    
    function update_post()
    {
        $id    = $this->uri->segment(4);
        $values = json_decode(file_get_contents('php://input'), true);
        if(empty($values['username'])) $this->response(array('success'=>false, 'msg'=>'Username is required.'), REST_Controller::HTTP_OK);
        elseif(empty($values['password'])) $this->response(array('success'=>false, 'msg'=>'Password is required.'), REST_Controller::HTTP_OK);
        elseif(empty($values['name'])) $this->response(array('success'=>false, 'msg'=>'Fullname is required.'), REST_Controller::HTTP_OK);
        elseif(empty($values['roles_id'])) $this->response(array('success'=>false, 'msg'=>'Role is required.'), REST_Controller::HTTP_OK);
        else
        {
            unset($values['role']);
            unset($values['customer']);
            unset($values['password']);
            unset($values['created_at']);
            $values['updated_at'] = date('Y-m-d H:i:s');
            $this->UserModel->update($id, $values);
            $this->response(array('success'=>true, 'msg'=>'User updated.'), REST_Controller::HTTP_OK);
        }
    }
}
?>