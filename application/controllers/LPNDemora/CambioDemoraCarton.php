<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class CambioDemoraCarton extends CI_Controller{

	public function __construct(){

		parent::__construct();
		$this->load->model("LPNDemora/CambioDemoraCarton_model");
	}

	public function index(){
		$this->load->view('LPNDemora/CambioDemoraCarton');
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
                     $carton = $worksheet->getCellByColumnAndRow(1,$row)->getValue();
                     $estado = $worksheet->getCellByColumnAndRow(2,$row)->getValue();
                     $fecha = $worksheet->getCellByColumnAndRow(3,$row)->getValue();

                     if($estado < 20){
                        $estado = 20;
                     }
                     if($estado > 21){
                        $estado = 21;
                     }

                     if($carton == null && $estado == null && $fecha == null){

                     }else{
                       $data[] = array(
                        'CARTON' =>  $carton,
                        'ESTADO' =>  $estado,
                        'FECHA' =>  $fecha
                       );
                     } 
                 }
             }
             echo $this->CambioDemoraCarton_model->read($data);
          }else{
            echo 0;
          }
        }
        else{
          echo 1;
        }
  }
  public function save(){
    $result = 0;
    $data = json_decode($this->input->post('data'));
    foreach ($data as $key) {
      $carton = $key->CARTON;
      $estado_inicial = $key->ESTADO_INICIAL;
      $estado_final = $key->ESTADO_FINAL;
      if($key->FECHA != "" && !is_null($key->FECHA)){
        $fecha_liberacion = "'".date("d/m/Y", strtotime($key->FECHA))."'";
      }else
      {
        $fecha_liberacion = "NULL";
      }

      if($estado_inicial == 21 && $estado_final == 20){
        $result += $this->CambioDemoraCarton_model->saveStat21_20($carton, $estado_inicial, $estado_final, $fecha_liberacion);
      }elseif($estado_inicial == 20 && $estado_final == 21){
        $result += $this->CambioDemoraCarton_model->saveStat20_21($carton, $estado_inicial, $estado_final, $fecha_liberacion);
      }else{
        echo 1; 
        break;
      }
      if($result > 0 ){
        break;
      }
    }
    echo $result;
  }
}