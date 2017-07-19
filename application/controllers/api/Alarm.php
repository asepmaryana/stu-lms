<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Alarm extends REST_Controller 
{	
	function __construct()
	{
		parent::__construct();
		#$this->load->model('SubnetModel','',TRUE);
        #$this->load->model('NodeModel','',TRUE);
        $this->load->model('AlarmTempModel','',TRUE);
	}
    
    function index_get()
    {
        //print 'alarm ok';
        $node_id = $this->uri->segment(4);
        $page    = $this->uri->segment(5);
        $size    = $this->uri->segment(6);
        $rows    = $this->AlarmTempModel->get_list($node_id)->result();
        $this->response(array('data'=>$rows, 'total'=>count($rows)), REST_Controller::HTTP_OK);
    }
    
    function index_post()
    {
        print 'alarm ok';
    }
    
    function index_put()
    {
        print 'alarm ok';
    }
    
    function index_delete()
    {
        print 'alarm ok';
    }
    
    function node_get()
    {
        #$node_id = $this->uri->segment(4);
        #$this->response($this->AlarmTempModel->get_list($node_id)->result(), REST_Controller::HTTP_OK);
        $node_id = $this->uri->segment(4);
        $page    = $this->uri->segment(5);
        $size    = $this->uri->segment(6);
        if(empty($page) || $page == '0') $page = 1;
        if(empty($size) || $size == '0') $size = 10;
        $offset  = ($page-1)*$size;
                
        $rows   = $this->AlarmTempModel->get_paged_list($node_id, $size, $offset, 'dtime', 'desc')->result();
        $total  = $this->AlarmTempModel->get_total($node_id);
        $totalPage  = ceil($total/$size);
        $firstPage  = ($page == 0 || $page == 1) ? true : false;
        $lastPage   = ($page == $totalPage) ? true : false;
        $msg        = array('content'=>$rows, 'totalPage'=>$totalPage, 'firstPage'=>$firstPage, 'lastPage'=>$lastPage, 'page'=>intval($page), 'total'=>$total);
        $this->response($msg, REST_Controller::HTTP_OK);
    }
    
    function fetch_get()
    {
        $model      = $this->uri->segment(4);
        $model_id   = $this->uri->segment(5);
        $alarm_id   = $this->uri->segment(6);
        $from       = $this->uri->segment(7);
        $to         = $this->uri->segment(8);
        $page       = $this->uri->segment(9);
        $size       = $this->uri->segment(10);
        $doc        = $this->uri->segment(11);
        
        if(empty($page) || $page == '0') $page = 1;
        if(empty($size) || $size == '0') $size = 10;
        $offset  = ($page-1)*$size;
        
        $rows   = $this->AlarmTempModel->get_paged_model($model, $model_id, $alarm_id, $from, $to, $size, $offset, 'dtime', 'desc')->result();
        $total  = $this->AlarmTempModel->get_total_model($model, $model_id, $alarm_id, $from, $to);
        $totalPage  = ceil($total/$size);
        $firstPage  = ($page == 0 || $page == 1) ? true : false;
        $lastPage   = ($page == $totalPage) ? true : false;
        $msg        = array('content'=>$rows, 'totalPage'=>$totalPage, 'firstPage'=>$firstPage, 'lastPage'=>$lastPage, 'page'=>intval($page), 'total'=>$total);
        
        if($doc == 'xls')
        {
            require_once APPPATH . 'third_party/phpexcel/PHPExcel.php';
            
            $objPHPExcel    = new PHPExcel();
            $r = 1;
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$r, 'Regional');
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$r, 'Area');
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$r, 'Site');
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$r, 'Date Time');
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$r, 'Severity');
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$r, 'Alarm Name');
            
            foreach($rows as $row)
            {
                $r++;
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$r, $row->region);
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$r, $row->area);
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$r, $row->site);
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$r, $row->ddtime);
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$r, $row->severity);
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$r, $row->alarm_label);
            }
            
            $this->load->helper('excel');
            download_excel($objPHPExcel, 'Alarm Active_'.$from.'-'.$to);
        }
        else $this->response($msg, REST_Controller::HTTP_OK);
    }
    
    function severity_get()
    {
        $this->response(array('success'=>true, 'data'=>$this->AlarmTempModel->get_severity_statistic()->result()), REST_Controller::HTTP_OK);
    }
    
    function statistic_get()
    {
        $this->response(array('success'=>true, 'data'=>$this->AlarmTempModel->get_alarm_statistic()->result()), REST_Controller::HTTP_OK);
    }
}
?>