<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Monitoring extends CI_Controller {

 	function __construct()
    {
        parent::__construct();
        session_start();
        //$this->load->model('models_name');
    }

    public function index()
    {
        if($this->session->userdata('uid') == '') redirect('login');
        $data['page'] = 'Monitoring ';
        $this->load->view('monitoring', $data);
    }

}
/* End of file monitoring.php */
/* Location: ./application/controllers/monitoring.php */
?>