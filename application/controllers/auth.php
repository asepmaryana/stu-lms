<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created 9:35 PM 1/8/2016, Author Haris Hardianto
 */

/**
 * Class Auth
 * @property Users_Model $users_model
 */
class Auth extends CI_Controller {

    /*
    |--------------------------------------------------------------------------
    | Validator
    |--------------------------------------------------------------------------
    | Run validator to check the username and password
    | If User has right username and password, User willbe Authenticated
    | and will be redirected to home page
    | If User has no right username and password, User willbe  redirected to
    | Login page with error message.
    */

    /*
     * Change 'home' to your default controller after User has successfully login
     */
    protected $home_page = 'dashboard';


    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('users_model');
    }


    function index()
    {
        $this->login();
    }


    function register()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[5]|max_length[25]');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|max_length[10]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|matches[passconf]');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('register');
        }

        $data = [
            'name'     => set_value('name'),
            'username' => set_value('username'),
            'email'    => set_value('email'),
            'password' => md5(set_value('password')),
        ];

        $this->users_model->register($data);

        $this->login();

    }


    private function login()
    {
        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_validator');

        if ($this->form_validation->run() == false) {

            $this->session->set_flashdata('error', 'Invalid username or password');

            redirect('login');
        }

        redirect($this->home_page, 'refresh');
    }


    /**
     * @param $password
     * @return true
     * @return false
     */
    function validator($password)
    {
        $get_user = $this->users_model->login($this->input->post('username'), $password);

        if ($get_user->num_rows == 0) {

            return false;

        } else {

            foreach ($get_user->result() as $row) {

                $data_session = [
                    'username'   => $row->username,
                    'level'      => $row->level,
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                ];

                $this->session->set_userdata('logged_in', $data_session);
            }

            return true;
        }
    }


    function logout()
    {
        $this->session->unset_userdata('logged_in');
        $this->session->sess_destroy();

        redirect('login', 'refresh');
    }


}

/* End of file Auth.php */
/* Location: ./application/controllers/Auth.php */