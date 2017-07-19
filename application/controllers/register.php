<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created 10:49 PM 5/9/2016, Author Haris Hardianto
 */

class Register extends CI_Controller {

    function __construct()
    {
        parent::__construct();
    }
	
	public function index()
	{
	    $data['page'] = 'Register';
	    
		$this->load->view('v_Register');
	}
}

/* End of file Register.php */
/* Location: ./application/controllers/Register.php */