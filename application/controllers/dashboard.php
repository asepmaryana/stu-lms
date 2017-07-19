<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

 	function __construct()
    {
        parent::__construct();
        session_start();
        //$this->load->model('models_name');
    }


    public function index()
    {
        if($this->session->userdata('uid') == '') redirect('login');
        $data['page'] = 'Dashboard ';
        $this->load->view('dashboard', $data);
    }

}
?>