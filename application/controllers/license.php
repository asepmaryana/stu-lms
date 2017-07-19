<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created 4:15 PM 1/28/2016, Author Haris Hardianto
 */


/**
 * Class License
 * @property License_Model $license_model
 */
class License extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('license_model');
    }


    public function index()
    {
        $this->load->view('lic/v_License');
    }


    public function validate()
    {
        $license = $this->input->post('license');

        if(strlen($license) != '143'){
            $this->session->set_flashdata('error','Enter Your Valid License Key');
            redirect('license');
        };

        $this->license_model->save_license($license);

        redirect('login');
    }
}

/* End of file License.php */
/* Location: ./application/controllers/License.php */