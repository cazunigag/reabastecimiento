<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;


class Articulo_locacion extends CI_Controller{

    public function __construct(){

        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
    }
	

	public function index()
	{

		$this->load->model('artlocacion_model');
        if($this->session->has_userdata('nombre')){
            $this->load->view('articulolocacion');
        }else{
            redirect('', 'refresh');
        }
		
	}
	public function insertarArtLocacion(){
        $tempData = $this->input->post('models');
        $json = json_decode($tempData, true);
        $this->load->model('artlocacion_model');
        $resp = $this->artlocacion_model->insertarArtLocacion($json);
        echo json_encode($resp);

    }
    public function readArtLocacion(){
    	$this->load->model('artlocacion_model');
        echo $this->artlocacion_model->readArtLocacion();
    }
    public function filtrarDatos(){
        $fecIni = $this->input->post('fecIni');
        $fecFin = $this->input->post('fecFin');
        $this->load->model('artlocacion_model');
        echo $this->artlocacion_model->filtrarDatos($fecIni, $fecFin);
        

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
                     $locacion = $worksheet->getCellByColumnAndRow(1,$row)->getValue();
                     $articulo = $worksheet->getCellByColumnAndRow(2,$row)->getValue();
                     $minimo = $worksheet->getCellByColumnAndRow(3,$row)->getValue();
                     $maximo = $worksheet->getCellByColumnAndRow(4,$row)->getValue();

                     if($locacion == null && $articulo == null && $minimo == null && $maximo == null){

                     }else{
                       $data[] = array(
                        'DSP_LOCN' =>  $locacion,
                        'SKU_ID' =>  $articulo,
                        'MIN_INVN_QTY' =>  $minimo,
                        'MAX_INVN_QTY' =>  $maximo,
                       );
                     } 
                 }
             }
             echo json_encode($data);
          }else{
            echo 0;
          }
        }
        else{
          echo 1;
        }
    }
}