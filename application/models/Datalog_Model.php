<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created 11:35 AM 8/6/2016, Author Haris Hardianto
 */


class DataLog_Model extends CI_Model {


    public function saveDataLog($data)
    {
        $this->db->insert('datalog', $data);
    }


}


/* End of file DataLog_Model.php */
/* Location: ./application/models/DataLog_Model.php */