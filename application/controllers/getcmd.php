<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created 9:29 AM 8/8/2016, Author Haris Hardianto
 */


/**
 * Class GetCmd 
 * @property LampControl_Model $lampcontrol_model
 */
class GetCmd extends CI_Controller {

 	function __construct()
    {
        parent::__construct();
        $this->load->model('lampcontrol_model');
       
    }


    public function index($imei = '')
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $data['msg'] = $method;

        $cmd = $this->lampcontrol_model->getLampCtrl($imei);

        if ($this->lampcontrol_model->getLampCtrl($imei)->num_rows == 0) {
            echo '';
        } else {
            //format message controll L1ON L1OFF
            //Show command
            $cmd =$this->lampcontrol_model->getLampCtrl($imei)->row();
            echo 'L'.$cmd->site_id.$cmd->set_status;

            //Delete Requested controll
            $this->lampcontrol_model->deleteRequestedCtrl($imei,$cmd->site_id);

        }
    }

}


/* End of file GetCmd.php */
/* Location: ./application/controllers/GetCmd.php */