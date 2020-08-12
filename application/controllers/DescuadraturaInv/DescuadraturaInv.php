<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DescuadraturaInv extends CI_Controller{

    public function __construct() {
        parent:: __construct();

        $this->load->model("DescuadraturaInv/DescuadraturaInv_model");
        $this->load->library('session');
    }
	public function index()
	{
		$this->load->view('DescuadraturaInv/DescuadraturaInv');
    }
    
    public function update(){

        if (isset($_FILES['files']['name'])) {
            $file_name = $_FILES['files']['name'];
            $tmp = explode('.', $file_name);
            $extension = end($tmp);

            if($extension == 'xlsx' || $extension == 'xls'){
               $path = $_FILES['files']['tmp_name'];
               $object = IOFactory::load($path);
               foreach ($object->getWorksheetIterator() as $worksheet) {
                   $lastRow = $worksheet->getHighestRow();
                   for ($row=1; $row <= $lastRow; $row++) { 
  
                       $cud = $worksheet->getCellByColumnAndRow(1,$row)->getValue();
  
                       $data[] = array(
                           'CUD' =>  isset($cud)?$cud:'NULL'
                       );
                   }
                  echo $this->DescuadraturaInv_model->update($data);
               }
            }else{
              echo 2;
            }
        }
        else{
        echo 1;
        }
    }

    public function mainGrid(){
        echo $this->DescuadraturaInv_model->mainGrid();
    }

    public function dataGrafico(){
        echo $this->DescuadraturaInv_model->dataGrafico();
    }
}