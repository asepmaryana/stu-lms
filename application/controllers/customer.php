<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Customer extends CI_Controller {

 	function __construct()
    {
        parent::__construct();
        session_start();
    }

    function index()
    {
        if($this->session->userdata('uid') == '') redirect('login');
        $data['page'] = 'Customer ';
        $this->load->view('customer', $data);
    }

}
?>