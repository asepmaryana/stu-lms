<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Polling extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Polling_Model');
        $this->load->model('Debug_Model');
        $this->load->model('Datalog_Model');
    }


    public function indexx()
    {
        $data = $this->input->get('message');

        $updated_at = substr($data, 214, 4) . '-' . substr($data, 218, 2) . '-' . substr($data, 220, 2) . ' ' . substr($data, 222, 2) . ':' . substr($data, 224, 2). ':' . substr($data, 226, 2);
        $imei = substr($data, 0, 15);
        $chanel = substr($data, 16, 2);
        $group = substr($data, 19, 2);
        $pole = $site_id = ($group * 8 - 7);

        $row['dtime'] = date('Y-m-d h:i:s');
        $row['msg'] = $data;

        //insert data to  debug table
        $this->Debug_Model->saveRawData($row);

        $dt= $lg = substr($data, 22, strlen($data) - 34);

        //update data on  site table
        for ($x = 0; $x <= 7; $x++) {
            $rs = substr($dt, ($x * 24), 24);

            $col['updated_at'] = $updated_at;
            $col['chanel'] = $chanel;
            $col['vbatt'] = substr($rs, 0, 2) . '.' . substr($rs, 2, 2);
            $col['pvoltage'] = substr($rs, 4, 2) . '.' . substr($rs, 6, 2);
            $col['ibatt'] = ((substr($rs, 8, 1) == 'N' | substr($rs, 8, 1) == 'n') ? '-' : '') . substr($rs, 9, 2) . '.' . substr($rs, 11, 2);
            $col['iload'] = substr($rs, 13, 2) . '.' . substr($rs, 15, 2);
            $col['temperature_ctrl'] = substr($rs, 17, 2) . '.' . substr($rs, 19, 1);
            $col['temperature_batt'] = substr($rs, 20, 2) . '.' . substr($rs, 22, 1);
            $col['status'] = substr($rs, 23, 1);

            $this->Polling_Model->updatedataPoll($imei, $pole++, $col);

        }

        //insert data from message to  datalog table
        for ($y = 0; $y <= 7; $y++) {
            $ls = substr($lg, ($y * 24), 24);
            $log['site_id'] = $site_id++;
            $log['dtime'] = $updated_at;
            $log['vbatt'] = substr($ls, 0, 2) . '.' . substr($ls, 2, 2);
            $log['pvoltage'] = substr($ls, 4, 2) . '.' . substr($ls, 6, 2);
            $log['ibatt'] = ((substr($rs, 8, 1) == 'N' | substr($rs, 8, 1) == 'n') ? '-' : '') . substr($ls, 9, 2) . '.' . substr($ls, 11, 2);
            $log['iload'] = substr($ls, 13, 2) . '.' . substr($ls, 15, 2);
            $log['temperature_ctrl'] = substr($ls, 17, 2) . '.' . substr($ls, 19, 1);
            $log['temperature_batt'] = substr($ls, 20, 2) . '.' . substr($ls, 22, 1);
            $log['status'] = substr($ls, 23, 1);

            $this->Datalog_Model->saveDataLog($log);
        }

    }
    
    public function index()
    {
        $msg    = $this->input->get('message');        
        #$msg    = '865067021303667*07*01*00032437P00000000311321100011226P00220029320317100000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000020160818191712';
        
        $row['dtime']   = date('Y-m-d h:i:s');
        $row['msg']     = $msg;        
        $this->Debug_Model->saveRawData($row);
        
        #print $msg.'<br/>';
        if(count(explode('*', $msg)) < 4) print 'NOK';
        elseif(count(explode('*', $msg)) == 4)
        {
            $dtime      = substr($msg, strlen($msg)-14, 14);
            $updated_at = substr($dtime, 0, 4).'-'.substr($dtime, 4, 2).'-'.substr($dtime, 6, 2).' '.substr($dtime, 8, 2).':'.substr($dtime, 10, 2).':'.substr($dtime, 12, 2);
            
            list($imei, $chanel, $group, $data) = explode('*', $msg);
            
            #print 'valid msg.<br/>';
            #print 'length: '.strlen($msg).'<br/>';
            #print 'imei: '.$imei.'<br/>';
            #print 'channel: '.$chanel.'<br/>';
            #print 'group: '.$group.'<br/>';
            #print 'data: '.$data.'<br/><br/>';
            
            $g=intval($group);
            $i=0;
            while($i<8)
            {
                $pole=$i+1+($g*8-8);
                $ls   = substr($data, $i*24, 24);
                #print 'lamp '.$pole.': '.$ls.'<br/>';
                
                $res = $this->Polling_Model->getsite($imei, $pole);
                if($res->num_rows()>0) {
                    $row    = $res->row();
                    $site_id= $row->id;
                    
                    $pvoltage = substr($ls, 0, 2) . '.' . substr($ls, 2, 2);
                    $vbatt = substr($ls, 4, 2) . '.' . substr($ls, 6, 2);
                    $ibatt = ((substr($ls, 8, 1) == 'N' | substr($ls, 8, 1) == 'n') ? '-' : '') . substr($ls, 9, 2) . '.' . substr($ls, 11, 2);
                    $iload = substr($ls, 13, 2) . '.' . substr($ls, 15, 2);
                    $temperature_ctrl = substr($ls, 17, 2) . '.' . substr($ls, 19, 1);
                    $temperature_batt = substr($ls, 20, 2) . '.' . substr($ls, 22, 1);
                    $status = substr($ls, 23, 1);
                    
                    $site   = array();
                    $site['vbatt'] = $vbatt;
                    $site['pvoltage'] = $pvoltage;
                    $site['ibatt'] = $ibatt;
                    $site['iload'] = $iload;
                    $site['temperature_ctrl'] = $temperature_ctrl;
                    $site['temperature_batt'] = $temperature_batt;
                    $site['status'] = $status;
                    $site['updated_at'] = $updated_at;
                    $site['chanel'] = $chanel;
                    # update table site
                    $this->db->where('id', $site_id);
                    $this->db->update('site', $site);
                    
                    $log = array();
                    $log['site_id'] = $site_id;
                    $log['dtime'] = $updated_at;
                    $log['vbatt'] = $vbatt;
                    $log['pvoltage'] = $pvoltage;
                    $log['ibatt'] = $ibatt;
                    $log['iload'] = $iload;
                    $log['temperature_ctrl'] = $temperature_ctrl;
                    $log['temperature_batt'] = $temperature_batt;
                    $log['status'] = $status;
                    #insert datalog
                    $this->db->insert('datalog', $log);
                }
                $res->free_result();
                $i++;
            }
            #print 'updated_at : '.$updated_at.'<br/>';
            print 'OK';
        }
        elseif(count(explode('*', $msg)) == 24)
        {
            list($protocol, $imei, $pole, $rfid, $pack_volt, $cell_1_volt, $cell_2_volt, $cell_3_volt, $cell_4_volt, $cell_5_volt, $cell_6_volt, $cell_7_volt, $cell_8_volt, $bms_curr, $soc, $bms_status, $vbatt, $ibatt, $pvoltage, $iload, $status, $temperature_ctrl, $temperature_batt, $ddtime) = explode('*', $msg);
            $res = $this->Polling_Model->getsite($imei, $pole);
            if($res->num_rows()>0)
            {
                $row    = $res->row();
                $site_id= $row->id;
                
                $pack_volt  = floatval($pack_volt)/100;
                $cell_1_volt= floatval($cell_1_volt)/100;
                $cell_2_volt= floatval($cell_2_volt)/100;
                $cell_3_volt= floatval($cell_3_volt)/100;
                $cell_4_volt= floatval($cell_4_volt)/100;
                $cell_5_volt= floatval($cell_5_volt)/100;
                $cell_6_volt= floatval($cell_6_volt)/100;
                $cell_7_volt= floatval($cell_7_volt)/100;
                $cell_8_volt= floatval($cell_8_volt)/100;
                $bms_curr   = (substr($bms_curr, 0, 1) == 'P') ? floatval(substr($bms_curr, 1, strlen($bms_curr)))/100 : floatval(substr($bms_curr, 1, strlen($bms_curr)))/100 * -1;
                $soc        = intval($soc);
                $bms_status = intval($bms_status);
                $vbatt      = floatval($vbatt)/100;
                $ibatt      = (substr($ibatt, 0, 1) == 'P') ? floatval(substr($ibatt, 1, strlen($ibatt)))/100 : floatval(substr($ibatt, 1, strlen($ibatt)))/100 * -1;
                $pvoltage   = floatval($pvoltage)/100;
                $iload      = floatval($iload)/100;
                $status     = intval($status);
                $temperature_ctrl = floatval($temperature_ctrl)/10;
                $temperature_batt = floatval($temperature_batt)/10;
                
                $updated_at = date('Y-m-d H:i:s');
                if(strlen($ddtime) == 14)
                    $updated_at = substr($ddtime, 0, 4).'-'.substr($ddtime, 4, 2).'-'.substr($ddtime, 6, 2).' '.substr($ddtime, 8, 2).':'.substr($ddtime, 10, 2).':'.substr($ddtime, 12, 2);
                
                $site   = array();
                $site['vbatt'] = $vbatt;
                $site['pvoltage'] = $pvoltage;
                $site['ibatt'] = $ibatt;
                $site['iload'] = $iload;
                $site['temperature_ctrl'] = $temperature_ctrl;
                $site['temperature_batt'] = $temperature_batt;
                $site['status']      = $status;
                $site['protocol']    = $protocol;
                $site['rfid_mstr']   = $rfid;
                $site['pack_volt']   = $pack_volt;
                $site['cell_1_volt'] = $cell_1_volt;
                $site['cell_2_volt'] = $cell_2_volt;
                $site['cell_3_volt'] = $cell_3_volt;
                $site['cell_4_volt'] = $cell_4_volt;
                $site['cell_5_volt'] = $cell_5_volt;
                $site['cell_6_volt'] = $cell_6_volt;
                $site['cell_7_volt'] = $cell_7_volt;
                $site['cell_8_volt'] = $cell_8_volt;
                $site['bms_curr']    = $bms_curr;
                $site['soc']         = $soc;
                $site['bms_status']  = $bms_status;
                $site['updated_at'] = $updated_at;
                
                # update table site
                $this->db->where('id', $site_id);
                $this->db->update('site', $site);
                    
                $log = array();
                $log['site_id'] = $site_id;
                $log['dtime'] = $updated_at;
                $log['vbatt'] = $vbatt;
                $log['pvoltage'] = $pvoltage;
                $log['ibatt'] = $ibatt;
                $log['iload'] = $iload;
                $log['temperature_ctrl'] = $temperature_ctrl;
                $log['temperature_batt'] = $temperature_batt;
                $log['status']      = $status;
                $log['pack_volt']   = $pack_volt;
                $log['cell_1_volt'] = $cell_1_volt;
                $log['cell_2_volt'] = $cell_2_volt;
                $log['cell_3_volt'] = $cell_3_volt;
                $log['cell_4_volt'] = $cell_4_volt;
                $log['cell_5_volt'] = $cell_5_volt;
                $log['cell_6_volt'] = $cell_6_volt;
                $log['cell_7_volt'] = $cell_7_volt;
                $log['cell_8_volt'] = $cell_8_volt;
                $log['bms_curr']    = $bms_curr;
                $log['soc']         = $soc;
                $log['bms_status']  = $bms_status;
                
                #insert datalog
                $this->db->insert('datalog', $log);
            }
            $res->free_result();
            print 'OK';
        }
    }
}
/* End of file Polling.php */
/* Location: ./application/controllers/Polling.php */
?>