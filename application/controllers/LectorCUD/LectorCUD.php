<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LectorCUD extends CI_Controller{

	public function __construct() {
        parent:: __construct();
        $this->load->model("LectorCUD/LectorCUD_model");
        $this->load->library('session');
    }
    public function index(){
    	/*$newuser = array(
						"modulo" => 'lector',
					);
		  $this->session->set_userdata($newuser);
      $this->load->view('LectorCUD/LectorCUD');*/
      header('Location: http://localhost/pistolapp');
    }

    public function picking(){
    	/*$newuser = array(
						"modulo" => 'lector',
					);
		  $this->session->set_userdata($newuser);
      $this->load->view('LectorCUD/Picking');*/
      header('Location: http://10.0.149.42/pistolapp');
    }

    public function Testpicking(){ 
      /*$newuser = array(
            "modulo" => 'lector',
          );
      $this->session->set_userdata($newuser);
      $this->load->view('LectorCUD/LectorCUD');*/
      header('Location: http://10.0.149.42/pistolapp');
    }

    public function faltantes(){
      /*$newuser = array(
            "modulo" => 'lector',
          );
      $this->session->set_userdata($newuser);
      $this->load->view('LectorCUD/Faltantes');*/
      header('Location: http://10.0.149.42/pistolapp');
    }

    public function dashboard(){
      /*$newuser = array(
        "modulo" => 'lector',
      );
      $this->session->set_userdata($newuser);
      $this->load->view('LectorCUD/Dashboard_lector');*/
      header('Location: http://10.0.149.42/pistolapp');
    }

    public function devueltos(){
      /*$newuser = array(
        "modulo" => 'lector',
      );
      $this->session->set_userdata($newuser);
      $this->load->view('LectorCUD/Devueltos');*/
      header('Location: http://10.0.149.42/pistolapp');
    }

    public function asignacionManual(){
      /*$newuser = array(
        "modulo" => 'lector',
      );
      $this->session->set_userdata($newuser);
      $this->load->view('LectorCUD/AsignacionManual');*/
      header('Location: http://10.0.149.42/pistolapp');
    }

    public function importarEXCEL(){

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

                     $cud = $worksheet->getCellByColumnAndRow(1,$row)->getValue();
                     $idtransporte = $worksheet->getCellByColumnAndRow(2,$row)->getValue();
                     $courier = $worksheet->getCellByColumnAndRow(3,$row)->getValue();
                     $transportista = $worksheet->getCellByColumnAndRow(4,$row)->getValue();
                     $patente = $worksheet->getCellByColumnAndRow(5,$row)->getValue();
                     $sucursal = $worksheet->getCellByColumnAndRow(6,$row)->getValue();
                     $fecha = $worksheet->getCellByColumnAndRow(7,$row)->getValue();
                     /*$rut2 = $worksheet->getCellByColumnAndRow(7,$row)->getValue();
                     $cliente = $worksheet->getCellByColumnAndRow(8,$row)->getValue();
                     $direccion = $worksheet->getCellByColumnAndRow(9,$row)->getValue();
                     $comuna = $worksheet->getCellByColumnAndRow(10,$row)->getValue();
                     $cud = $worksheet->getCellByColumnAndRow(11,$row)->getValue();
                     $sku = $worksheet->getCellByColumnAndRow(12,$row)->getValue();
                     $ng = $worksheet->getCellByColumnAndRow(13,$row)->getValue();
                     $descsku = $worksheet->getCellByColumnAndRow(14,$row)->getValue();
                     $unidad = $worksheet->getCellByColumnAndRow(15,$row)->getValue();
                     $stock = $worksheet->getCellByColumnAndRow(16,$row)->getValue();
                     $estado = $worksheet->getCellByColumnAndRow(17,$row)->getValue();
                     $booster = $worksheet->getCellByColumnAndRow(18,$row)->getValue();
                     $idbooster = $worksheet->getCellByColumnAndRow(19,$row)->getValue();
                     $patente = $worksheet->getCellByColumnAndRow(20,$row)->getValue();*/

					 $data[] = array(
						 'CUD' =>  isset($cud)?$cud:'NULL',
						 'IDTRANSPORTE' =>  isset($idtransporte)?$idtransporte:'NULL',
						 'COURIER' =>  isset($courier)?$courier:'NULL',
						 'TRANSPORTISTA' =>  isset($transportista)?$transportista:'NULL',
						 'PATENTE' =>  isset($patente)?$patente:'NULL',
						 'SUCURSAL' =>  isset($sucursal)?$sucursal:'NULL',
             'FECHA' =>  isset($fecha)?$fecha:'NULL'
						 /*'RUT2' =>  isset($rut2)?$rut2:'NULL',
						 'CLIENTE' =>  isset($cliente)?$cliente:'NULL',
						 'DIRECCION' =>  isset($direccion)?$direccion:'NULL',
						 'COMUNA' =>  isset($comuna)?$comuna:'NULL',
						 'CUD' =>  isset($cud)?$cud:'NULL',
						 'SKU' =>  isset($sku)?$sku:'NULL',
						 'NG' =>  isset($ng)?$ng:'NULL',
						 'DESCSKU' =>  isset($descsku)?$descsku:'NULL',
						 'UNIDAD' =>  isset($unidad)?$unidad:'NULL',
						 'STOCK' =>  isset($stock)?$stock:'NULL',
						 'ESTADO' =>  isset($estado)?$estado:'NULL',
						 'BOOSTER' =>  isset($booster)?$booster:'NULL',
						 'IDBOOSTER' =>  isset($idbooster)?$idbooster:'NULL',
						 'PATENTE' =>  isset($patente)?$patente:'NULL',*/
					 );
                 }
                echo $this->LectorCUD_model->save($data);
             }
          }else{
            echo 0;
          }
        }
        else{
          echo 1;
        }
  	}

  	public function importarEXCELv2(){

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

                     $cud = $worksheet->getCellByColumnAndRow(1,$row)->getValue();
                     $idtransporte = $worksheet->getCellByColumnAndRow(2,$row)->getValue();
                     $courier = $worksheet->getCellByColumnAndRow(3,$row)->getValue();
                     $transportista = $worksheet->getCellByColumnAndRow(4,$row)->getValue();
                     $patente = $worksheet->getCellByColumnAndRow(5,$row)->getValue();
                     $sucursal = $worksheet->getCellByColumnAndRow(6,$row)->getValue();
                     /*$rut2 = $worksheet->getCellByColumnAndRow(7,$row)->getValue();
                     $cliente = $worksheet->getCellByColumnAndRow(8,$row)->getValue();
                     $direccion = $worksheet->getCellByColumnAndRow(9,$row)->getValue();
                     $comuna = $worksheet->getCellByColumnAndRow(10,$row)->getValue();
                     $cud = $worksheet->getCellByColumnAndRow(11,$row)->getValue();
                     $sku = $worksheet->getCellByColumnAndRow(12,$row)->getValue();
                     $ng = $worksheet->getCellByColumnAndRow(13,$row)->getValue();
                     $descsku = $worksheet->getCellByColumnAndRow(14,$row)->getValue();
                     $unidad = $worksheet->getCellByColumnAndRow(15,$row)->getValue();
                     $stock = $worksheet->getCellByColumnAndRow(16,$row)->getValue();
                     $estado = $worksheet->getCellByColumnAndRow(17,$row)->getValue();
                     $booster = $worksheet->getCellByColumnAndRow(18,$row)->getValue();
                     $idbooster = $worksheet->getCellByColumnAndRow(19,$row)->getValue();
                     $patente = $worksheet->getCellByColumnAndRow(20,$row)->getValue();*/

					 $data[] = array(
						 'CUD' =>  isset($cud)?$cud:'NULL',
						 'IDTRANSPORTE' =>  isset($idtransporte)?$idtransporte:'NULL',
						 'COURIER' =>  isset($courier)?$courier:'NULL',
						 'TRANSPORTISTA' =>  isset($transportista)?$transportista:'NULL',
						 'PATENTE' =>  isset($patente)?$patente:'NULL',
						 'SUCURSAL' =>  isset($sucursal)?$sucursal:'NULL',
						 /*'RUT2' =>  isset($rut2)?$rut2:'NULL',
						 'CLIENTE' =>  isset($cliente)?$cliente:'NULL',
						 'DIRECCION' =>  isset($direccion)?$direccion:'NULL',
						 'COMUNA' =>  isset($comuna)?$comuna:'NULL',
						 'CUD' =>  isset($cud)?$cud:'NULL',
						 'SKU' =>  isset($sku)?$sku:'NULL',
						 'NG' =>  isset($ng)?$ng:'NULL',
						 'DESCSKU' =>  isset($descsku)?$descsku:'NULL',
						 'UNIDAD' =>  isset($unidad)?$unidad:'NULL',
						 'STOCK' =>  isset($stock)?$stock:'NULL',
						 'ESTADO' =>  isset($estado)?$estado:'NULL',
						 'BOOSTER' =>  isset($booster)?$booster:'NULL',
						 'IDBOOSTER' =>  isset($idbooster)?$idbooster:'NULL',
						 'PATENTE' =>  isset($patente)?$patente:'NULL',*/
					 );
                 }
                echo $this->LectorCUD_model->save_v2($data);
             }
          }else{
            echo 0;
          }
        }
        else{
          echo 1;
        }
  	}

  	public function search(){
  		$cud = $this->input->post('barcode');
  		$fecha = $this->input->post('fecha');
  		$tienda = $this->input->post('tienda');
  		echo $this->LectorCUD_model->search($cud, $fecha, $tienda);
  	}

  	public function tiendas(){
  		echo $this->LectorCUD_model->tiendas();
  	}

  	public function cerrarCarga(){
  		$fecha = $this->input->post('fecha');
  		$tienda = $this->input->post('tienda');
  		echo json_encode($this->LectorCUD_model->cerrarCarga($fecha, $tienda));
  	}

  	public function detalleCierreCarga(){
  		$fecha = $this->input->post('fecha');
  		$tienda = $this->input->post('tienda');
  		echo json_encode($this->LectorCUD_model->detalleCierreCarga($fecha, $tienda));
  	}
    public function detalleCierreCarga_V2(){
      $fecha = $this->input->post('fecha');
      $tienda = $this->input->post('tienda');
      echo json_encode($this->LectorCUD_model->detalleCierreCarga_V2($fecha, $tienda));
    }

  	public function PickFaltantes(){
  		$cud = $this->input->post('barcode');
  		$fecha = $this->input->post('fecha');
  		$tienda = $this->input->post('tienda');
  		echo $this->LectorCUD_model->PickFaltantes($cud, $fecha, $tienda);
  	}
    public function Pick(){
      $cud = $this->input->post('barcode');
      $fecha = $this->input->post('fecha');
      $tienda = $this->input->post('tienda');
      echo $this->LectorCUD_model->Pick($cud, $fecha, $tienda);
    }

    public function TestPick(){
      $cud = $this->input->post('barcode');
      $fecha = $this->input->post('fecha');
      $tienda = $this->input->post('tienda');
      echo $this->LectorCUD_model->TestPick($cud, $fecha, $tienda);
    }

  	public function totalPick(){
  		$fecha = $this->input->post('fecha');
  		$tienda = $this->input->post('tienda');
  		echo json_encode($this->LectorCUD_model->totalPick($fecha, $tienda));
  	}

  	public function resumenDespacho($id, $tienda, $fec){

  		$fecha = str_replace('-', '/', $fec);
  		$spreadsheet = $this->LectorCUD_model->resumenDespacho($id, $fecha, $tienda);
  		$file = "Resumen_".$id."_".$fecha."_".$tienda."xlsx";

  		$writer = new Xlsx($spreadsheet);
 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $file .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output'); // download file 
  	}

    public function resumenDespacho_V2($id, $store, $fec, $opl){

      $fecha = str_replace('-', '/', $fec);
      $tienda = $store;
      $spreadsheet = $this->LectorCUD_model->resumenDespacho_V2($id, $fecha, $tienda, $opl);
      $file = "Resumen_".$id."_".$fecha."_".$tienda;

      //$writer = new Mpdf($spreadsheet);

      
      $writer = IOFactory::createWriter($spreadsheet, 'Mpdf');

      //$writer = new \Mpdf\Mpdf($spreadsheet);

      /*$class = \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf::class;
      \PhpOffice\PhpSpreadsheet\IOFactory::registerWriter('Pdf', $class);
      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Pdf');*/

 
      //header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'. $file .'.pdf"'); 
      //header('Cache-Control: max-age=0');
      
      $writer->save('php://output'); // download file 
    }

  	public function getIds(){
  		$fecha = $this->input->post('fecha');
  		$tienda = $this->input->post('tienda');
  		echo $this->LectorCUD_model->getIds($fecha, $tienda);
  	}

    public function getIds_V2(){
      $fecha = $this->input->post('fecha');
      $tienda = $this->input->post('tienda');
      $opl = $this->input->post('opl');
      echo $this->LectorCUD_model->getIds_V2($fecha, $tienda, $opl);
    }

    public function detalleFaltantes(){
      $id = $this->input->post('id');
      $fecha = $this->input->post('fecha');
      $tienda = $this->input->post('tienda');
      $opl = $this->input->post('opl');
      echo json_encode($this->LectorCUD_model->detalleFaltantes($id, $fecha, $tienda, $opl));
    }

    public function totalFaltantes(){
      $id = $this->input->post('id');
      $fecha = $this->input->post('fecha');
      $tienda = $this->input->post('tienda');
      $opl = $this->input->post('opl');
      echo json_encode($this->LectorCUD_model->totalFaltantes($id, $fecha, $tienda, $opl));
    }

    public function dataDashboard(){
      $desde = $this->input->post('desde');
      $hasta = $this->input->post('hasta');
      echo $this->LectorCUD_model->dataDashboard($desde, $hasta);
    }
    public function devolver(){
      $cud = $this->input->post('barcode');
      $motivo = $this->input->post('motivo');
      $tienda = $this->input->post('tienda');
      echo json_encode($this->LectorCUD_model->devolver($cud, $motivo, $tienda));
    }

    public function buscarCud(){
      $cud = $this->input->post('barcode');
      echo json_encode($this->LectorCUD_model->buscarCud($cud));
    }

    public function guardarInfoDespacho(){
      $cud = $this->input->post('barcode');
      $id = $this->input->post('id');
      $chofer = $this->input->post('chofer');
      $empresa = $this->input->post('empresa');
      $patente = $this->input->post('patente');
      $fecha = $this->input->post('fecha');
      $tienda = $this->input->post('tienda');
      echo json_encode($this->LectorCUD_model->guardarInfoDespacho($cud, $id, $chofer, $empresa, $patente, $fecha, $tienda));
    }
    public function detalleDevueltos(){
      $fecha = $this->input->post('fecha');
      $tienda = $this->input->post('tienda');
      echo json_encode($this->LectorCUD_model->detalleDevueltos($fecha, $tienda));
    }

    public function contarDevueltos(){
      $tienda = $this->input->post('tienda');
      echo $this->LectorCUD_model->contarDevueltos($tienda);
    }

    public function datosTransporte(){
      $id = $this->input->post('id');
      $fecha = $this->input->post('fecha');
      $tienda = $this->input->post('tienda');
      echo json_encode($this->LectorCUD_model->datosTransporte($id, $tienda, $fecha));
    }

    public function getOPL(){
      $fecha = $this->input->post('fecha');
      $tienda = $this->input->post('tienda');
      echo $this->LectorCUD_model->getOPL($tienda, $fecha);
    }
}