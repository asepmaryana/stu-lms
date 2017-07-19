<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created 12:19 AM 8/5/2016, Author Haris Hardianto
 */


class Polling_Model extends CI_Model {


    public function savePoll($data)
    {
        $this->db->insert('polling', $data);
    }

    public function getdataPolling()
    {
        $this->db->select('*');
        $this->db->from('site');

        return $this->db->get()->result();
    }


    public function updatedataPoll($imei,$pole, $data)
    {

        $this->db->where('imei', $imei);
        $this->db->where('pole', $pole);
        $this->db->update('site', $data);
    }
    
    public function getsite($imei, $pole)
    {
        $this->db->where('imei', $imei);
        $this->db->where('pole', $pole);
        return $this->db->get('site');
    }
}


/* End of file Polling_Model.php */
/* Location: ./application/models/Polling_Model.php */