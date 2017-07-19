<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created 9:03 AM 8/5/2016, Author Haris Hardianto
 */


/**
 * Class GetCtrl 
 * @property Models_name $models_name
 */
class GetCtrl extends CI_Controller {

 	function __construct()
    {
        parent::__construct();
        //$this->load->model('models_name');
       
    }


    public function index()
    {
        $data['page'] = 'GetCtrl ';
        $this->load->view('v_GetCtrl', $data);
    }


    public function setStatus()
    {

    }

}


/* End of file GetCtrl.php */
/* Location: ./application/controllers/GetCtrl.php */