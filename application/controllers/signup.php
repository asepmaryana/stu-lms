<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created 11:31 PM 1/8/2016, Author Haris Hardianto
 */

/**
 * Class SignUp
 */
class SignUp extends CI_Controller {

    function __construct()
    {
        parent::__construct();
    }


    public function index()
    {
        if ($this->session->userdata('logged_in')) {

            redirect('home', 'refresh');
        }

        $this->load->view('v_SignUp');
    }
}

/* End of file SignUp.php */
/* Location: ./application/controllers/SignUp.php */