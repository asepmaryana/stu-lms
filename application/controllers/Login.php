<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct()
    {
        parent::__construct();    
        $this->load->helper(array('form'));
    }


    public function index()
    {
        if ($this->session->userdata('uid')) redirect('../home', 'refresh');
        $this->load->view('login_view');
    }
}
?>