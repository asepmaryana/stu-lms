<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

 	function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('uid')) redirect('../login', 'refresh');
        $this->load->model('SubnetModel','',TRUE);
        $this->load->model('NodeModel','',TRUE);
    }

    public function index()
    {
        $regions   = $this->SubnetModel->get_by_parent_id('')->result();
        $i=0;
        foreach($regions as $r)
        {
            $areas  = $this->SubnetModel->get_by_parent_id($r->id)->result();
            $j=0;
            foreach($areas as $a)
            {
                $sites  = $this->NodeModel->get_by_site($a->id)->result();
                $areas[$j]->children = $sites;
                $j++;
            }
            $regions[$i]->children = $areas;
            $i++;
        }
        $data   = array();
        $data['regions']= $regions;
        $this->load->view('home_view', $data);
        #print '<pre>';
        #print_r($data);
        #print '</pre>';
    }
}
?>