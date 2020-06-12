<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LectorCUD_model extends CI_Model{

	private $estiloCabecera = array(
		'borders' => array(
			'allborders' => array(
				'borderstyle' => 'thin',
				'color' => array('rgb' => '000000')
			)
		),
		'alignment' => array(
			'horizontal' => 'center',
			'vertical' => 'center',
		),
		'font' => array(
			'bold' => true
		)
	);

	private $estiloCabeceraP = array(
		'borders' => array(
			'allborders' => array(
				'borderstyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				'color' => array('rgb' => '000000')
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
			'color' => array('rgb' => 'FFFF00')
		)
	);
	private $estiloCabeceraR = array(
		'borders' => array(
			'allborders' => array(
				'style' => 'thin',
				'color' => array('rgb' => '000000')
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
			'color' => array('rgb' => 'DDEBF7')
		)
	);
	private $estiloCelda = array(
		'borders' => array(
			'allborders' => array(
				'style' => 'thin',
				'color' => array('rgb' => '000000')
			)
		),
		'alignment' => array(
			'horizontal' => 'center',
			'vertical' => 'center'
		),
	);

	public function __construct(){
		parent::__construct();
		$this->load->database();
		
	}
	public function search($cud, $fecha, $tienda){

		$sql = "SELECT CUD, ID ID_BOOSTER, BOOSTER, PATENTE, ESCANEADO, TO_CHAR(FECHA_DE_CARGA, 'DD/MM/YYYY') FECHA_DE_CARGA, TIENDA FROM SHIP_FROM_STORE WHERE CUD = '$cud'";

		$result = $this->db->query($sql);
		if(sizeof($result->result())>0){

			foreach ($result->result() as $key) {

				if($key->FECHA_DE_CARGA != $fecha){

					return 2;
				}
				if($key->TIENDA != $tienda){

					return 3;
				}else{

					if($key->ESCANEADO == 'F'){

						$sql = "UPDATE SHIP_FROM_STORE SET ESCANEADO = 'T', FECHA_ESCANEO = SYSDATE WHERE CUD = '$cud'";
						$upd = $this->db->query($sql);
					}elseif ($key->ESCANEADO == 'T') {

						return 1;
					}
				}

			}

			$data = json_encode($result->result());
			$this->db->close();
			return $data;
		}
	}

	public function Pick($cud, $fecha, $tienda){

		$db2 = $this->load->database('PMMWMS', TRUE);

		$sql = "SELECT 
					CUD, 
					ARTICULO, 
					CANTIDAD,
					ID,
					PATENTE,
					NOMBRE_TRANSPORTISTA, 
					TO_CHAR(FECHA_DESPACHO, 'DD/MM/YYYY') FECHA_DESPACHO, 
					TIENDA,
					INFORMADO 
				FROM 
					SFS_PICKEODESPACHO 
				WHERE 
					CUD = '$cud'";

		$result = $db2->query($sql);
		if(sizeof($result->result())>0){

			foreach ($result->result() as $key) {

				if($key->FECHA_DESPACHO != $fecha){

					return 2;
				}
				if($key->TIENDA != $tienda){

					return 3;
				}if($key->INFORMADO != 'T'){

					return 5;
				}else{
					$sql = "SELECT SKU_DESC FROM ITEM_MASTER WHERE SKU_BRCD = '$key->ARTICULO'";

					$result = $db2->query($sql);

					foreach ($result->result() as $key2) {
						$descripcion = $key2->SKU_DESC;
					}
				}

				$sql = "UPDATE
							SFS_PICKEODESPACHO
						SET
							PICKEADO = 'T',
							FECHA_PICKEADO = SYSDATE
						WHERE 
							CUD = '$cud'";

				$result = $db2->query($sql);

				$data[] = array(
					"CUD" => $key->CUD,
					"ARTICULO" => $key->ARTICULO,
					"ID" => $key->ID,
					"PATENTE" => $key->PATENTE,
					"NOMBRE_TRANSPORTISTA" => $key->NOMBRE_TRANSPORTISTA,
					"SKU_DESC" => $descripcion,
					"CANTIDAD" => $key->CANTIDAD
				);
			}

			$this->db->close();
			return json_encode($data);
		}
		return 4;
	}

	public function save_v2($data){

		$db2 = $this->load->database('BTPROD', TRUE);

		$db3 = $this->load->database('PMMWMS', TRUE);

		$fecha_despacho = "";
		$articulo = "";
		$cantidad = "";
		$tienda = "";
		$sucursalDespacho = "";

		foreach ($data as $key) {
			
			$sql = "SELECT ESCANEADO FROM SFS_PICKEODESPACHO WHERE CUD = '$key[CUD]'";

			$result = $db3->query($sql);

			if(sizeof($result->result()) > 0){
				foreach ($result->result() as $key2) {
					if($key2->ESCANEADO == "T"){
						break;
					}
					else{

						$sql = "UPDATE 
									SFS_PICKEODESPACHO 
								SET
									INFORMADO = 'T',
									FECHA_CARGA = SYSDATE,
									ID = '$key[IDTRANSPORTE]',
									NOMBRE_TRANSPORTISTA = '$key[COURIER]',
									PATENTE = '$key[PATENTE]',
									TIENDA = '$key[SUCURSAL]'
								WHERE
									CUD = '$key[CUD]'";

						$resp = $db3->query($sql);

						if(!$resp){
							return 1;
						}
					}
				}
			}
			else{
				$sql = "SELECT 
							FECHA_DESP,
							CODIGO_VTA,
							CANTIDAD,
							NRO_LOC_BOD,
							SUCURSAL_DESP
						FROM 
							BIGT_DESPACHOS 
						WHERE 
							CUD = '$key[CUD]'";

				$resp = $db2->query($sql);

				foreach ($resp->result() as $despacho) {
					$fecha_despacho = $despacho->FECHA_DESP;
					$articulo = $despacho->CODIGO_VTA;
					$cantidad = $despacho->CANTIDAD;
					$tienda = $despacho->NRO_LOC_BOD;
					$sucursalDespacho = $despacho->SUCURSAL_DESP;
				}

				$sql = "INSERT INTO SFS_PICKEODESPACHO (CUD, 
														ARTICULO, 
														CANTIDAD,
														FECHA_DESPACHO, 
														TIENDA, 
														SUCURSAL_DESPACHO, 
														PICKEADO,
														FECHA_PICKEADO,
														INFORMADO,
														FECHA_CARGA,
														ESCANEADO,
														FECHA_ESCANEADO,
														PATENTE,
														ID,
														NOMBRE_TRANSPORTISTA) 
														VALUES ('$key[CUD]', 
																'$articulo', 
																'$cantidad', 
																'$fecha_despacho', 
																'$tienda', 
																'$sucursalDespacho', 
																'F', 
																null,
																'T',
																SYSDATE,
																'F',
																null,
																'$key[PATENTE]',
																'$key[IDTRANSPORTE]',
																'$key[TRANSPORTISTA]')";

				$result = $db3->query($sql);

				if(!$resp){
					return 1;
				}
			}
		}
		return 2;
	}

	public function save($data){

		$db2 = $this->load->database('BTPROD', TRUE);

		foreach ($data as $key) {
			
			$sql = "SELECT ESCANEADO FROM SHIP_FROM_STORE WHERE CUD = '$key[CUD]'";

			$result = $this->db->query($sql);

			$sql = "SELECT FECHA_DESP FROM BIGT_DESPACHOS WHERE CUD = '$key[CUD]'";

			$resp = $db2->query($sql);

			foreach ($resp->result() as $despacho) {
				$fecha_despacho = $despacho->FECHA_DESP;
			}

			if(sizeof($result->result()) > 0){
				foreach ($result->result() as $key2) {
					if($key2->ESCANEADO == "T"){
						break;
					}
					else{

						$sql = "UPDATE 
									SHIP_FROM_STORE 
								SET
									ID = '$key[IDTRANSPORTE]',
									BOOSTER = '$key[TRANSPORTISTA]',
									PATENTE = '$key[PATENTE]',
									STOCK = '$key[SUCURSAL]',
									TIENDA = '$key[SUCURSAL]',
									COURIER = '$key[COURIER]',
									FECHA_DE_CARGA = '$fecha_despacho'
								WHERE
									CUD = '$key[CUD]'";

						$resp = $this->db->query($sql);

						if(!$resp){
							return 1;
						}
					}
				}
			}
			else{

				$sql = "INSERT INTO SHIP_FROM_STORE(CUD, ID, BOOSTER, PATENTE, STOCK, TIENDA, COURIER, FECHA_DE_CARGA, ESCANEADO) VALUES ('$key[CUD]', '$key[IDTRANSPORTE]', '$key[TRANSPORTISTA]', '$key[PATENTE]', '$key[SUCURSAL]', '$key[SUCURSAL]', '$key[COURIER]', '$fecha_despacho', 'F')";

				$result = $this->db->query($sql);

				if(!$resp){
					return 1;
				}
			}
		}
		return 2;
	}

	public function tiendas(){

		$db3 = $this->load->database('PMMWMS', TRUE);

		$sql = "SELECT STORE_NBR, STORE_NBR||' - '||NAME AS NAME FROM STORE_MASTER ORDER BY STORE_NBR ASC";

		$result = $db3->query($sql);
		if($result || $result != null){
			$resultado = json_encode($result->result());
			$db3->close();
			return $resultado;
		}
		else{
			return $db3->error();
		}
	}

	public function cerrarCarga($fecha, $tienda){

		$data = array();

		$db2 = $this->load->database('BTPROD', TRUE);

		$sql = "SELECT
				    COURIER, 
				    COUNT(CUD) INFORMADOS,
				    SUM(CASE WHEN ESCANEADO = 'T' THEN 1 ELSE 0 END) ESCANEADOS,
				    (SELECT SUM(CASE WHEN ESCANEADO = 'T' THEN 1 ELSE 0 END) FROM SHIP_FROM_STORE WHERE TRUNC(FECHA_DE_CARGA) = '$fecha' AND TIENDA = '$tienda') TOTAL_ESCANEADOS
				FROM
				    SHIP_FROM_STORE
				WHERE
				    TRUNC(FECHA_DE_CARGA) = '$fecha'
				    AND TIENDA = '$tienda'
				GROUP BY
				    COURIER";

		$result = $this->db->query($sql);

		$sql = "SELECT COUNT(*) TOTAL FROM BIGT_DESPACHOS WHERE TRUNC(FECHA_DESP) = TO_DATE('$fecha', 'DD/MM/YYYY') AND NRO_LOC_BOD = '$tienda'";

		$resp = $db2->query($sql);

		foreach ($resp->result() as $totalbt) {
			$total = $totalbt->TOTAL;
		}

		foreach ($result->result() as $key) {
			
			$data[] = array(
				'COURIER' => $key->COURIER,
				'INFORMADOS' => $key->INFORMADOS,
				'ESCANEADOS' => $key->ESCANEADOS,
				'TOTAL' => $key->TOTAL_ESCANEADOS.'/'.$total
			);
		}

		return $data;

	}

	public function detalleCierreCarga($fecha, $tienda){

		$skudesc = "";

		$db2 = $this->load->database('BTPROD', TRUE);

		$db3 = $this->load->database('PMMWMS', TRUE);

		$sql = "SELECT
				    CUD
				FROM
				    SHIP_FROM_STORE
				WHERE
				    TRUNC(FECHA_DE_CARGA) = '$fecha'
				    AND TIENDA = '$tienda'
				    AND ESCANEADO = 'F'";

		$result = $this->db->query($sql);

		foreach ($result->result() as $key) {

			$sql = "SELECT CODIGO_VTA, FECHA_DESP, NRO_LOC_BOD FROM BIGT_DESPACHOS WHERE CUD = '$key->CUD'";

			$resp = $db2->query($sql);

			foreach ($resp->result() as $key2) {
				$articulo = $key2->CODIGO_VTA;
				$fecha = $key2->FECHA_DESP;
				$tienda = $key2->NRO_LOC_BOD;
			}
			
			$sql = "SELECT SKU_DESC FROM ITEM_MASTER WHERE SKU_BRCD = '$articulo'";

			$result = $this->db->query($sql);

			foreach ($result->result() as $key2) {
				$skudesc = $key2->SKU_DESC;
			}	

			$data[] = array(
				'CUD' => $key->CUD,
				'SKU' => $articulo,
				'SKU_DESC' => $skudesc,
				'FECHA' => $fecha,
				'TIENDA' => $tienda
			);
		}

		return $data;	
	}

	public function detalleCierreCarga_V2($fecha, $tienda){

		$db2 = $this->load->database('BTPROD', TRUE);

		$db3 = $this->load->database('PMMWMS', TRUE);

		$sql = "SELECT
				    CUD,
				    ARTICULO,
				    FECHA_DESPACHO FECHA,
				    TIENDA
				FROM
				    SFS_PICKEODESPACHO
				WHERE
				    TRUNC(FECHA_DESPACHO) = '$fecha'
				    AND TIENDA = '$tienda'
				    AND PICKEADO = 'F'";

		$result = $db3->query($sql);

		foreach ($result->result() as $key) {
			
			$sql = "SELECT SKU_DESC FROM ITEM_MASTER WHERE SKU_BRCD = '$key->ARTICULO'";

			$result = $db3->query($sql);

			foreach ($result->result() as $key2) {
				$skudesc = $key2->SKU_DESC;
			}	

			$data[] = array(
				'CUD' => $key->CUD,
				'SKU' => $key->ARTICULO,
				'SKU_DESC' => $skudesc,
				'FECHA' => $key->FECHA,
				'TIENDA' => $key->TIENDA
			);
		}

		return $data;	
	}

	public function totalPick($fecha, $tienda){

		$db3 = $this->load->database('PMMWMS', TRUE);

		$sql = "SELECT 
					SUM(CASE WHEN PICKEADO = 'T' THEN 1 ELSE 0 END) PICKEADOS,
					COUNT(*) TOTAL
				FROM
					SFS_PICKEODESPACHO
				WHERE
					TRUNC(FECHA_DESPACHO) = '$fecha'
					AND TIENDA = '$tienda'";

		$result = $db3->query($sql);

		if(sizeof($result->result()) > 0){
			foreach ($result->result() as $key) {
				$total = $key->PICKEADOS." de ".$key->TOTAL;
			}
		}else{
			$total = "0 de 0";
		}
		
		return $total;
	}

	public function resumenDespacho($id, $fecha, $tienda){

		$db2 = $this->load->database('default', TRUE);

		$db3 = $this->load->database('BTPROD', TRUE);

		$fechaDespacho = "";
		$articulo = "";
		$cantidad = "";
		$nroboleta = "";

		$sql = "SELECT 
					(SELECT COUNT(*) FROM SHIP_FROM_STORE WHERE ID = '$id' AND ESCANEADO = 'T' AND TRUNC(FECHA_DE_CARGA) = '$fecha' AND TIENDA = '$tienda') TOTAL,
					COURIER
				FROM 
					SHIP_FROM_STORE 
				WHERE 
					ID =  '$id'";

		$resp = $this->db->query($sql);

		foreach ($resp->result() as $key) {
			$totalcuds = $key->TOTAL; 
			$courier = $key->COURIER; 
		}
	    
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

		$sheet->setTitle("Packing List");
		$sheet->SetCellValue("A1", "ID TRANSPORTE: ");
		$sheet->getStyle("A1")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("B1", $id);
		$sheet->getStyle("B1")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("A2", "CUDS:");
		$sheet->getStyle("A2")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("B2", $totalcuds);
		$sheet->getStyle("B2")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("A3", "EMPRESA TRANSPORTE: ");
		$sheet->getStyle("A3")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("B3", $courier);
		$sheet->getStyle("B3")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("A4", "CONDUCTOR: ");
		$sheet->getStyle("A4")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("F2", "FIRMA: ");
		$sheet->getStyle("F2")->applyFromArray($this->estiloCabeceraP);



		$sheet->SetCellValue("A7", "CUD");
		$sheet->getStyle("A7")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("B7", "RUTA");
		$sheet->getStyle("B7")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("C7", "FECHA DESPACHO");
		$sheet->getStyle("C7")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("D7", "NUMERO BOLETA");
		$sheet->getStyle("D7")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("E7", "ARTICULO");
		$sheet->getStyle("E7")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("F7", "CANTIDAD");
		$sheet->getStyle("F7")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("G7", "NRO GUIA");
		$sheet->getStyle("G7")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("H7", "RUT CLIENTE");
		$sheet->getStyle("H7")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("I7", "NOMBRE CLIENTE");
		$sheet->getStyle("I7")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("J7", "DIRECCION CLIENTE");
		$sheet->getStyle("J7")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("K7", "COMUNA");
		$sheet->getStyle("K7")->applyFromArray($this->estiloCabeceraP);

		$sql = "SELECT CUD FROM SHIP_FROM_STORE WHERE ID = '$id' AND ESCANEADO = 'T' AND TRUNC(FECHA_DE_CARGA) = '$fecha' AND TIENDA = '$tienda'";

		$resp = $this->db->query($sql);

		$count = 8;

		foreach ($resp->result() as $key2) {
			
			$sql = "SELECT 
						BD.CUD,
						BD.FECHA_DESP,
						TO_CHAR(BD.CODIGO_VTA) CODIGO_VTA,
						BD.CANTIDAD,
						BD.NRO_LOC_BOD,
						BD.NRO_BOLETA,
						BD.RUT_CLIENTE,
						BD.NOMBRE_DESP,
						BD.DIRECCION_DESP,
						BC.DESCRIPCION_COMUNA,
						BD.NRO_GUIA
					FROM 
						BIGT_DESPACHOS BD,
						BIGT_COMUNA BC
					WHERE 
						BD.CUD = '$key2->CUD'
						AND BD.COD_REGION = BC.COD_REGION
						AND BD.COD_COMUNA = BC.COD_COMUNA ";

			$resp2 = $db3->query($sql);

			foreach ($resp2->result() as $key3) {
				$fechaDespacho = $key3->FECHA_DESP;
				$articulo = $key3->CODIGO_VTA." ";
				$cantidad = $key3->CANTIDAD;
				$nroboleta = $key3->NRO_BOLETA;
				$rutcliente = $key3->RUT_CLIENTE;
				$nombre = $key3->NOMBRE_DESP;
				$direccion = $key3->DIRECCION_DESP;
				$comuna = $key3->DESCRIPCION_COMUNA;
				$nroguia = $key3->NRO_GUIA;
			}

			$sheet->SetCellValue('A'.$count, $key2->CUD);
			$sheet->SetCellValue('B'.$count, $id);
			$sheet->SetCellValue('C'.$count, $fechaDespacho);
			$sheet->SetCellValue('D'.$count, $nroboleta);
			$sheet->SetCellValue('E'.$count, $articulo);
			$sheet->SetCellValue('F'.$count, $cantidad);
			$sheet->SetCellValue('G'.$count, $nroguia);
			$sheet->SetCellValue('H'.$count, $rutcliente);
			$sheet->SetCellValue('I'.$count, $nombre);
			$sheet->SetCellValue('J'.$count, $direccion);
			$sheet->SetCellValue('K'.$count, $comuna);
			$count++; 

		}
		foreach (range('A','J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->getPageSetup()->setHorizontalCentered(true);   

		return $spreadsheet;		
	}

	public function resumenDespacho_V2($id, $fecha, $tienda){

		$db2 = $this->load->database('PMMWMS', TRUE);

		$db3 = $this->load->database('BTPROD', TRUE);

		$fechaDespacho = "";
		$articulo = "";
		$cantidad = "";
		$nroboleta = "";

		$sql = "SELECT 
				    (SELECT COUNT(*) FROM SFS_PICKEODESPACHO WHERE ID = '$id' AND PICKEADO = 'T' AND TRUNC(FECHA_DESPACHO) = '$fecha' AND TIENDA = '$tienda') TOTAL,
				    NOMBRE_TRANSPORTISTA COURIER
				FROM 
				    SFS_PICKEODESPACHO 
				WHERE 
				    ID =  '$id'
				    AND TRUNC(FECHA_DESPACHO) = '$fecha'
				    AND TIENDA = '$tienda'
				GROUP BY
				    NOMBRE_TRANSPORTISTA";

		$resp = $db2->query($sql);

		foreach ($resp->result() as $key) {
			$totalcuds = $key->TOTAL; 
			$courier = $key->COURIER; 
		}
	    
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

		$sheet->setTitle("Packing List");
		$sheet->SetCellValue("A1", "ID TRANSPORTE: ");
		$sheet->getStyle("A1")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("B1", $id);
		$sheet->getStyle("B1")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("A2", "CUDS:");
		$sheet->getStyle("A2")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("B2", $totalcuds);
		$sheet->getStyle("B2")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("A3", "EMPRESA TRANSPORTE: ");
		$sheet->getStyle("A3")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("B3", $courier);
		$sheet->getStyle("B3")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("A4", "CONDUCTOR: ");
		$sheet->getStyle("A4")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("F2", "FIRMA: ");
		$sheet->getStyle("F2")->applyFromArray($this->estiloCabeceraP);



		$sheet->SetCellValue("A7", "CUD");
		$sheet->getStyle("A7")->applyFromArray($this->estiloCabeceraP);
		$sheet->getStyle("A7")->getBorders()
		->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
		$sheet->SetCellValue("B7", "RUTA");
		$sheet->getStyle("B7")->applyFromArray($this->estiloCabeceraP);
		$sheet->getStyle("B7")->getBorders()
		->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
		$sheet->SetCellValue("C7", "FECHA DESPACHO");
		$sheet->getStyle("C7")->applyFromArray($this->estiloCabeceraP);
		$sheet->getStyle("C7")->getBorders()
		->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
		$sheet->SetCellValue("D7", "NUMERO BOLETA");
		$sheet->getStyle("D7")->applyFromArray($this->estiloCabeceraP);
		$sheet->getStyle("D7")->getBorders()
		->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
		$sheet->SetCellValue("E7", "ARTICULO");
		$sheet->getStyle("E7")->applyFromArray($this->estiloCabeceraP);
		$sheet->getStyle("E7")->getBorders()
		->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
		$sheet->SetCellValue("F7", "CANTIDAD");
		$sheet->getStyle("F7")->applyFromArray($this->estiloCabeceraP);
		$sheet->getStyle("F7")->getBorders()
		->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
		$sheet->SetCellValue("G7", "NRO GUIA");
		$sheet->getStyle("G7")->applyFromArray($this->estiloCabeceraP);
		$sheet->getStyle("G7")->getBorders()
		->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
		$sheet->SetCellValue("H7", "RUT CLIENTE");
		$sheet->getStyle("H7")->applyFromArray($this->estiloCabeceraP);
		$sheet->getStyle("H7")->getBorders()
		->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
		$sheet->SetCellValue("I7", "NOMBRE CLIENTE");
		$sheet->getStyle("I7")->applyFromArray($this->estiloCabeceraP);
		$sheet->getStyle("I7")->getBorders()
		->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
		$sheet->SetCellValue("J7", "DIRECCION CLIENTE");
		$sheet->getStyle("J7")->applyFromArray($this->estiloCabeceraP);
		$sheet->getStyle("J7")->getBorders()
		->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
		$sheet->SetCellValue("K7", "COMUNA");
		$sheet->getStyle("K7")->applyFromArray($this->estiloCabeceraP);
		$sheet->getStyle("K7")->getBorders()
		->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

		$sql = "SELECT CUD FROM SFS_PICKEODESPACHO WHERE ID = '$id' AND PICKEADO = 'T' AND TRUNC(FECHA_DESPACHO) = '$fecha' AND TIENDA = '$tienda'";

		$resp = $db2->query($sql);

		$count = 8;

		foreach ($resp->result() as $key2) {
			
			$sql = "SELECT 
						BD.CUD,
						BD.FECHA_DESP,
						TO_CHAR(BD.CODIGO_VTA) CODIGO_VTA,
						BD.CANTIDAD,
						BD.NRO_LOC_BOD,
						BD.NRO_BOLETA,
						BD.RUT_CLIENTE,
						BD.NOMBRE_DESP,
						BD.DIRECCION_DESP,
						BC.DESCRIPCION_COMUNA,
						BD.NRO_GUIA
					FROM 
						BIGT_DESPACHOS BD,
						BIGT_COMUNA BC
					WHERE 
						BD.CUD = '$key2->CUD'
						AND BD.COD_REGION = BC.COD_REGION
						AND BD.COD_COMUNA = BC.COD_COMUNA ";

			$resp2 = $db3->query($sql);

			foreach ($resp2->result() as $key3) {
				$fechaDespacho = $key3->FECHA_DESP;
				$articulo = $key3->CODIGO_VTA." ";
				$cantidad = $key3->CANTIDAD;
				$nroboleta = $key3->NRO_BOLETA;
				$rutcliente = $key3->RUT_CLIENTE;
				$nombre = $key3->NOMBRE_DESP;
				$direccion = $key3->DIRECCION_DESP;
				$comuna = $key3->DESCRIPCION_COMUNA;
				$nroguia = $key3->NRO_GUIA;
			}

			$sheet->SetCellValue('A'.$count, $key2->CUD);
			$sheet->getStyle('A'.$count)->applyFromArray($this->estiloCelda);
			$sheet->getStyle('A'.$count)->getBorders()
			->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
			$sheet->SetCellValue('B'.$count, $id);
			$sheet->getStyle('B'.$count)->applyFromArray($this->estiloCelda);
			$sheet->getStyle('B'.$count)->getBorders()
			->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
			$sheet->SetCellValue('C'.$count, $fechaDespacho);
			$sheet->getStyle('C'.$count)->applyFromArray($this->estiloCelda);
			$sheet->getStyle('C'.$count)->getBorders()
			->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
			$sheet->SetCellValue('D'.$count, $nroboleta);
			$sheet->getStyle('D'.$count)->applyFromArray($this->estiloCelda);
			$sheet->getStyle('D'.$count)->getBorders()
			->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
			$sheet->SetCellValue('E'.$count, $articulo);
			$sheet->getStyle('E'.$count)->applyFromArray($this->estiloCelda);
			$sheet->getStyle('E'.$count)->getBorders()
			->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
			$sheet->SetCellValue('F'.$count, $cantidad);
			$sheet->getStyle('F'.$count)->applyFromArray($this->estiloCelda);
			$sheet->getStyle('F'.$count)->getBorders()
			->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
			$sheet->SetCellValue('G'.$count, $nroguia);
			$sheet->getStyle('G'.$count)->applyFromArray($this->estiloCelda);
			$sheet->getStyle('G'.$count)->getBorders()
			->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
			$sheet->SetCellValue('H'.$count, $rutcliente);
			$sheet->getStyle('H'.$count)->applyFromArray($this->estiloCelda);
			$sheet->getStyle('H'.$count)->getBorders()
			->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
			$sheet->SetCellValue('I'.$count, $nombre);
			$sheet->getStyle('I'.$count)->applyFromArray($this->estiloCelda);
			$sheet->getStyle('I'.$count)->getBorders()
			->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
			$sheet->SetCellValue('J'.$count, $direccion);
			$sheet->getStyle('J'.$count)->applyFromArray($this->estiloCelda);
			$sheet->getStyle('J'.$count)->getBorders()
			->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
			$sheet->SetCellValue('K'.$count, $comuna);
			$sheet->getStyle('K'.$count)->applyFromArray($this->estiloCelda);
			$sheet->getStyle('K'.$count)->getBorders()
			->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
			$count++; 
		}
		foreach (range('A','K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

		return $spreadsheet;		
	}

	public function getIds($fecha, $tienda){
		$db2 = $this->load->database('default', TRUE);

		$sql = "SELECT
				    ID
				FROM 
				    SHIP_FROM_STORE
				WHERE
				    TRUNC(FECHA_DE_CARGA) = '$fecha'
				    AND TIENDA = '$tienda'
				GROUP BY
				    ID";

		$result = $db2->query($sql);
		if($result || $result != null){
			$resultado = json_encode($result->result());
			$db2->close();
			return $resultado;
		}
		else{
			return $db2->error();
		}

	}

	public function getIds_V2($fecha, $tienda){
		$db2 = $this->load->database('PMMWMS', TRUE);

		$sql = "SELECT
				    ID
				FROM 
				    SFS_PICKEODESPACHO
				WHERE
				    TRUNC(FECHA_DESPACHO) = '$fecha'
				    AND TIENDA = '$tienda'
				    AND INFORMADO = 'T'
				GROUP BY
				    ID";

		$result = $db2->query($sql);
		if($result || $result != null){
			$resultado = json_encode($result->result());
			$db2->close();
			return $resultado;
		}
		else{
			return $db2->error();
		}

	}
	public function detalleFaltantes($id ,$fecha, $tienda){

		$db2 = $this->load->database('BTPROD', TRUE);

		$db3 = $this->load->database('PMMWMS', TRUE);

		$sql = "SELECT
				    CUD,
				    ARTICULO,
				    FECHA_DESPACHO FECHA,
				    TIENDA
				FROM
				    SFS_PICKEODESPACHO
				WHERE
				    TRUNC(FECHA_DESPACHO) = '$fecha'
				    AND TIENDA = '$tienda'
				    AND PICKEADO = 'F'
				    AND ID = '$id'";

		$result = $db3->query($sql);

		foreach ($result->result() as $key) {
			
			$sql = "SELECT SKU_DESC FROM ITEM_MASTER WHERE SKU_BRCD = '$key->ARTICULO'";

			$result = $db3->query($sql);

			foreach ($result->result() as $key2) {
				$skudesc = $key2->SKU_DESC;
			}	

			$data[] = array(
				'CUD' => $key->CUD,
				'SKU' => $key->ARTICULO,
				'SKU_DESC' => $skudesc,
				'FECHA' => $key->FECHA,
				'TIENDA' => $key->TIENDA
			);
		}

		return $data;	
	}

	public function totalFaltantes($id, $fecha, $tienda){

		$db3 = $this->load->database('PMMWMS', TRUE);

		$sql = "SELECT 
					SUM(CASE WHEN PICKEADO = 'T' THEN 1 ELSE 0 END) PICKEADOS,
					COUNT(*) TOTAL
				FROM
					SFS_PICKEODESPACHO
				WHERE
					TRUNC(FECHA_DESPACHO) = '$fecha'
					AND TIENDA = '$tienda'
					AND ID = '$id'";

		$result = $db3->query($sql);

		if(sizeof($result->result()) > 0){
			foreach ($result->result() as $key) {
				$total = $key->PICKEADOS." de ".$key->TOTAL;
			}
		}else{
			$total = "0 de 0";
		}
		
		return $total;
	}
}