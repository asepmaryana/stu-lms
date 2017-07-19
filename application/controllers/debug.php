<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created 5:05 PM 8/4/2016, Author Haris Hardianto
 */


/**
 * Class Debug 
 * @property Models_name $models_name
 */
class Debug extends CI_Controller {

 	function __construct()
    {
        parent::__construct();
        //$this->load->model('models_name');
       
    }


    public function index()
    {
        $data['page'] = 'Debug ';
        $this->load->view('debug/v_Debug', $data);
    }


    public function post()
    {
        $this->load->view('debug/v_Post');
    }
}


/* End of file Debug.php */
/* Location: ./application/controllers/Debug.php */