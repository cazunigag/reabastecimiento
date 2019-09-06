<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Estanteria extends CI_Controller{


	public function __construct() {
        parent:: __construct();
        $this->load->helper("url");
        $this->load->model("Estanteria_model");
        $this->load->library("pagination");
    }
	public function index(){
		$this->load->view('CDLayout/centrodistribucion');
	}
	public function loadCDLayout(){
		 $this->load->view('CDSource/CDLayout');
	}
	public function loadPasillosEstanteria($piso){
        $data['clasificaciones'] = $this->Estanteria_model->getClasificaciones();
        $data['info'] = $this->Estanteria_model->getClasificacionPasillo();
		$data['pasillos'] = $this->Estanteria_model->loadPasillosEstanteria($piso);
        $data['resultado'] = ' ';
        switch ($piso) {
            case 4:
                $data['piso']= 'PISO '.$piso.' (COLGADOS)';
                break;
            case 5:
                $data['piso']= 'PISO '.$piso.' (SENSIBLES)';
                break;
            default:
                $data['piso']= 'PISO '.$piso;
                break;
        }
		$this->load->view('CDSource/PasillosEstanteria', $data);
	}
	public function loadPisosEstanteria(){
		 $this->load->view('CDSource/PisosEstanteria');
	}
	public function loadLocacionesEstanteria($pasillo){
		$data['locacionesimpar'] = $this->Estanteria_model->loadLocacionesEstanteriaImpar($pasillo);
		$data['locacionespar'] = $this->Estanteria_model->loadLocacionesEstanteriaPar($pasillo);
		$data['bayheaderpar'] = $this->Estanteria_model->getBayHeaderPar($pasillo);
		$data['bayheaderimpar'] = $this->Estanteria_model->getBayHeaderImPar($pasillo);
		$data['dimensiones'] = $this->Estanteria_model->getDimensiones($pasillo);
		$data['pasillo'] = $pasillo;
		$this->load->view('CDSource/Locaciones', $data);
	}
	public function getDetalleLocn(){
		$idLocn = $this->input->post('idLocn');
        echo $this->Estanteria_model->getDetalleLocn($idLocn);
    }

    public function getHeader(){
    	$idLocn = $this->input->post('idLocn');
        echo $this->Estanteria_model->getHeader($idLocn);
    }

    public function getEmptyLocn(){
    	$pasillo = $this->input->post('pasillo');
    	echo $this->Estanteria_model->getEmptyLocn($pasillo);
    }
    public function getLocnSKU(){
    	$sku = trim($this->input->post('sku'));
    	echo $this->Estanteria_model->getLocnSKU($sku);
    }
    public function getPasilloSKU(){
    	$sku = trim($this->input->post('sku'));
    	echo $this->Estanteria_model->getPasilloSKU($sku);
    }
    public function getAntiguedadSku(){
    	$pasillo = trim($this->input->post('pasillo'));
    	$dias = trim($this->input->post('dias'));
    	echo $this->Estanteria_model->getAntiguedadSku($pasillo, $dias);
    }
    public function getAntiguedadContCiclico(){
        $pasillo = trim($this->input->post('pasillo'));
        $dias = trim($this->input->post('dias'));
        echo $this->Estanteria_model->getAntiguedadContCiclico($pasillo, $dias);
    }
    public function getCartonTypePasillos(){
    	$pasillos = trim($this->input->post('pasillos'));
    	echo $this->Estanteria_model->getCartonTypePasillos($pasillos);
    }
    public function getCartonTypePasillo(){
        $pasillo  = trim($this->input->post('pasillo'));
        echo $this->Estanteria_model->getCartonTypePasillo($pasillo);
    }
    public function getCartonTypePasilloURL($pasillo){
        echo $this->Estanteria_model->getCartonTypePasillo($pasillo);
    }

    public function getUtilizacionPasillo(){
        $pasillo  = trim($this->input->post('pasillo'));
        echo $this->Estanteria_model->getUtilizacionPasillo($pasillo);
    }
    public function getImagenSku(){
        $sku  = trim($this->input->post('sku'));
        echo $this->Estanteria_model->getImagenSku($sku);
    }
    public function actualizarClassTabla(){
        $arrayPasillos  = $this->input->post('pasillos');
        $pasillos = '';
        foreach ($arrayPasillos as $key) {
            if(next($arrayPasillos)!= null){ 
                $pasillos = $pasillos.$key."','";
            }
            else{
                $pasillos = $pasillos.$key;
            }
        }
        $class  = trim($this->input->post('class'));
        echo $this->Estanteria_model->actualizarClassTabla($pasillos, $class);
     }
     public function actualizarCartonType(){
        $pasillo = $this->input->post('pasillo');
        $cartonType = $this->input->post('cartonType');
        echo $this->Estanteria_model->actualizarCartonType($pasillo, $cartonType);
     }
     public function downloadExcelAntiguedadSku($dias, $pasillo){

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'LOCACION');
        $sheet->setCellValue('B1', 'ARTICULO');
        $sheet->setCellValue('C1', 'FECHA MOD');
        $sheet->setCellValue('D1', 'CANT ACTUAL');

        $result = $this->Estanteria_model->downloadExcelAntiguedadSku($pasillo, $dias);
        $count = 2;

        foreach ($result as $key) {
            $sheet->setCellValue('A'.$count, $key->DSP_LOCN);
            $sheet->setCellValue('B'.$count, $key->SKU_ID);
            $sheet->setCellValue('C'.$count, $key->MOD_DATE_TIME);
            $sheet->setCellValue('D'.$count, $key->ACTL_INVN_QTY);
            $count++;
        }

        $sheet->getStyle('A1:D1')->applyFromArray(
              array(
                'borders' => array(
                    'allborders' => array(
                        'style' => 'thin',
                        'color' => array('hex' => '000000')
                    )
                ),
                'alignment' => array(
                    'horizontal' => 'center',
                    'vertical' => 'center'
                ),
                'font' => array(
                    'bold' => true
                ),
                'fill' => array(
                    'type' => 'solid',
                    'color' => array('hex' => 'FFFF00')
                )
            )
          );
        foreach (range('A','D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);  
        }           

        $writer = new Xlsx($spreadsheet);
 
        $filename = 'Reporte_Antiguedad_Sku';
 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output'); // download file 
     }
     public function downloadAntiguedadContCiclico($dias, $pasillo){

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'LOCACION');
        $sheet->setCellValue('B1', 'ULTIMO CONTEO CICLICO');
        $sheet->setCellValue('C1', 'CONTEO PENDIENTE');

        $result = $this->Estanteria_model->downloadAntiguedadContCiclico($pasillo, $dias);
        $count = 2;

        foreach ($result as $key) {
            $sheet->setCellValue('A'.$count, $key->DSP_LOCN);
            $sheet->setCellValue('B'.$count, $key->LAST_CNT_DATE_TIME);
            $sheet->setCellValue('C'.$count, $key->CYCLE_CNT_PENDING);
            $count++;
        }

        $sheet->getStyle('A1:C1')->applyFromArray(
              array(
                'borders' => array(
                    'allborders' => array(
                        'style' => 'thin',
                        'color' => array('hex' => '000000')
                    )
                ),
                'alignment' => array(
                    'horizontal' => 'center',
                    'vertical' => 'center'
                ),
                'font' => array(
                    'bold' => true
                ),
                'fill' => array(
                    'type' => 'solid',
                    'color' => array('hex' => 'FFFF00')
                )
            )
          );
        foreach (range('A','C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);  
        }           

        $writer = new Xlsx($spreadsheet);
 
        $filename = 'Reporte_Antiguedad_Cont_Ciclico';
 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output'); // download file 
     }
}