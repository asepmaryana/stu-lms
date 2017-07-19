<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created 5:08 PM 8/5/2016, Author Haris Hardianto
 */


class Debug_Model extends CI_Model {

 	public function getdata_TableRow()
    {
        $this->db->select('*');
        $this->db->from('debug');

        return $this->db->get()->row();
    }


    public function getdata_TableArray()
    {
        $this->db->select('*');
        $this->db->from('debug');

        return $this->db->get()->result();
    }

    public function saveRawData($data)
    {
        $this->db->insert('debug', $data);
    }
}


/* End of file Debug_Model.php */
/* Location: ./application/models/Debug_Model.php */