<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class CambioDemoraLPN extends CI_Controller{

	public function __construct(){

		parent::__construct();
		$this->load->model("LPNDemora/CambioDemoraLPN_model");
	}

	public function index(){
		$this->load->view('LPNDemora/CambioDemoraLPN');
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
                     $lpn = $worksheet->getCellByColumnAndRow(1,$row)->getValue();
                     $tipo = $worksheet->getCellByColumnAndRow(2,$row)->getValue();
                     $fecha = $worksheet->getCellByColumnAndRow(3,$row)->getValue();

                     if($tipo < 1){
                        $tipo = 1;
                     }
                     if($tipo > 2 && $tipo != 3){
                        $tipo = 2;
                     }

                     if($lpn == null && $tipo == null && $fecha == null){

                     }else{
                       $data[] = array(
                        'LPN' =>  $lpn,
                        'TIPO' =>  $tipo,
                        'FECHA' =>  $fecha
                       );
                     } 
                 }
             }
             echo $this->CambioDemoraLPN_model->read($data);
          }else{
            echo 0;
          }
        }
        else{
          echo 1;
        }
  }
  public function save(){
    $data = json_decode($this->input->post('data'));
    echo $this->CambioDemoraLPN_model->save($data);
  }
}