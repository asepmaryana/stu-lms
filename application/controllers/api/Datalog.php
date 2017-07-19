<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Datalog extends REST_Controller 
{	
	function __construct()
	{
		parent::__construct();
		if($this->session->userdata('uid') == '') $this->response(array('success'=>false, 'msg'=>'LOGIN_REQUIRED'), REST_Controller::HTTP_OK);
		$this->load->model('DatalogModel','',TRUE);
	}
    
    function index()
    {
        print 'datalog';
    }
    
    function fetch_get()
    {
        $mode       = $this->uri->segment(4);
        $site_id    = $this->uri->segment(5);
        $from       = $this->uri->segment(6);
        $to         = $this->uri->segment(7);
        $doc        = $this->uri->segment(8);
        
        $site_id    = ($site_id == '_') ? array() : explode('_', $site_id);
        
        $rows       = $this->DatalogModel->get_site_and_date($site_id, $from, $to)->result();
        
        if($doc == 'xls')
        {
            require_once APPPATH . 'third_party/phpexcel/PHPExcel.php';
            
            $objPHPExcel    = new PHPExcel();
            $r = 1;
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$r, 'Date Time');
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$r, 'PV');
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$r, 'Batt');
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$r, 'I Load');
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$r, 'Temp');
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$r, 'Status');
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$r, 'Volt');
            $objPHPExcel->getActiveSheet()->setCellValue('R'.$r, 'BMS');
            $objPHPExcel->getActiveSheet()->setCellValue('T'.$r, 'SoC');
            
            $r++;
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$r, 'Volt');
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$r, 'Curr');
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$r, 'Ctrl');
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$r, 'Batt');
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$r, 'Pack');
            $objPHPExcel->getActiveSheet()->setCellValue('J'.$r, 'Cell 1');
            $objPHPExcel->getActiveSheet()->setCellValue('K'.$r, 'Cell 2');
            $objPHPExcel->getActiveSheet()->setCellValue('L'.$r, 'Cell 3');
            $objPHPExcel->getActiveSheet()->setCellValue('M'.$r, 'Cell 4');
            $objPHPExcel->getActiveSheet()->setCellValue('N'.$r, 'Cell 5');
            $objPHPExcel->getActiveSheet()->setCellValue('O'.$r, 'Cell 6');
            $objPHPExcel->getActiveSheet()->setCellValue('P'.$r, 'Cell 7');
            $objPHPExcel->getActiveSheet()->setCellValue('Q'.$r, 'Cell 8');
            $objPHPExcel->getActiveSheet()->setCellValue('R'.$r, 'Curr');
            $objPHPExcel->getActiveSheet()->setCellValue('S'.$r, 'Status');
            
            foreach($rows as $row)
            {
                $r++;
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$r, date('n/j/Y H:i:s', strtotime($row->dtime)));
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$r, $row->pvoltage);
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$r, $row->vbatt);
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$r, $row->ibatt);
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$r, $row->iload);
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$r, $row->temperature_ctrl);
                $objPHPExcel->getActiveSheet()->setCellValue('G'.$r, $row->temperature_batt);                
                $objPHPExcel->getActiveSheet()->setCellValue('H'.$r, $row->status);
                $objPHPExcel->getActiveSheet()->setCellValue('I'.$r, $row->pack_volt);
                $objPHPExcel->getActiveSheet()->setCellValue('J'.$r, $row->cell_1_volt);
                $objPHPExcel->getActiveSheet()->setCellValue('K'.$r, $row->cell_2_volt);
                $objPHPExcel->getActiveSheet()->setCellValue('L'.$r, $row->cell_3_volt);
                $objPHPExcel->getActiveSheet()->setCellValue('M'.$r, $row->cell_4_volt);
                $objPHPExcel->getActiveSheet()->setCellValue('N'.$r, $row->cell_5_volt);
                $objPHPExcel->getActiveSheet()->setCellValue('O'.$r, $row->cell_6_volt);
                $objPHPExcel->getActiveSheet()->setCellValue('P'.$r, $row->cell_7_volt);
                $objPHPExcel->getActiveSheet()->setCellValue('Q'.$r, $row->cell_8_volt);
                $objPHPExcel->getActiveSheet()->setCellValue('R'.$r, $row->bms_curr);
                $objPHPExcel->getActiveSheet()->setCellValue('S'.$r, $row->bms_status);
                $objPHPExcel->getActiveSheet()->setCellValue('T'.$r, $row->soc);
                
            }
            
            $this->load->helper('excel');
            download_excel($objPHPExcel, 'Datalog_'.$from.'-'.$to);
        }
        else $this->response(array('success'=>true, 'data'=>$rows), REST_Controller::HTTP_OK);
    }
}
?>