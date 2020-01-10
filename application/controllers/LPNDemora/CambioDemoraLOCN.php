<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class CambioDemoraLOCN extends CI_Controller{

	public function __construct(){

		parent::__construct();
		$this->load->model("LPNDemora/CambioDemoraLOCN_model");
	}

	public function index(){
		$this->load->view('LPNDemora/CambioDemoraLOCN');
	}
	public function importarEXCEL(){
        $data = array();
        if (isset($_FILES['files']['name'])) {
          $file_name = $_FILES['files']['name'];
          $tmp = explode('.', $file_name);
          $extension = end($tmp);
          if($extension == 'xlsx' || $extension == 'xls'){
             $path = $_FILES['files']['tmp_name'];
             $object = IOFactory::load($path);
             foreach ($object->getWorksheetIterator() as $worksheet) {
                 $lastRow = $worksheet->getHighestRow();
                 for ($row=2; $row <= $lastRow; $row++) { 
                     $locn = $worksheet->getCellByColumnAndRow(1,$row)->getValue();
                     $fecha = $worksheet->getCellByColumnAndRow(2,$row)->getValue();

                     if($locn == null && $fecha == null){

                     }else{
                       $data[] = array(
                        'LOCN' =>  $locn,
                        'FECHA' =>  $fecha
                       );
                     } 
                 }
             }
             echo $this->CambioDemoraLOCN_model->read($data);
          }else{
            echo 1000;
          }
        }
        else{
          echo 1;
        }
  }
  public function save(){
    $data = json_decode($this->input->post('data'));
    echo $this->CambioDemoraLOCN_model ->save($data);
  }
  public function detalleLocns(){
    $data = json_decode($this->input->post('data'));
    echo $this->CambioDemoraLOCN_model ->detalleLocns($data);
  }
}