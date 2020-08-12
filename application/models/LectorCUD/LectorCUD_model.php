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
	private $estiloCabeceraI = array(
		'borders' => array(
			'allborders' => array(
				'style' => 'thin',
				'color' => array('rgb' => '000000')
			)
		),
		'alignment' => array(
			'horizontal' => 'left',
			'vertical' => 'left'
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
	

	public function TestPick($cud, $fecha, $tienda){

		$db2 = $this->load->database('PMMWMS', TRUE);

		$db3 = $this->load->database('BTPROD', TRUE);

		if($tienda == 10034 || $tienda == 10018 || $tienda == 10057 || $tienda == 10045 || $tienda == 10012){
			$sql = "SELECT 
						CUD, 
						ARTICULO, 
						CANTIDAD,
						ID,
						PATENTE,
						NOMBRE_TRANSPORTISTA, 
						TO_CHAR(FECHA_PLANIFICACION, 'DD/MM/YYYY') FECHA_PLANIFICACION, 
						TIENDA,
						INFORMADO 
					FROM 
						SFS_PICKEODESPACHO 
					WHERE 
						CUD = '$cud'";

			$result = $db2->query($sql);
			if(sizeof($result->result())>0){

				foreach ($result->result() as $key) {

					if($key->FECHA_PLANIFICACION != $fecha){

						return 2;
					}
					if($key->TIENDA != $tienda){

						return 3;
					}if($key->INFORMADO != 'T'){

						return 5;
					}else{

						$sql = "UPDATE
									SFS_PICKEODESPACHO
								SET
									PICKEADO = 'T',
									FECHA_PICKEADO = SYSDATE
								WHERE 
									CUD = '$cud'";

						$result = $db2->query($sql);

						$sql = "SELECT SKU_DESC FROM ITEM_MASTER WHERE SKU_BRCD = '$key->ARTICULO'";

						$result = $db2->query($sql);

						foreach ($result->result() as $key2) {
							$descripcion = $key2->SKU_DESC;
						}

						$sql = "SELECT 
								    CUD
								FROM 
								    BIGT_DESPACHOS
								WHERE
								    FECHA_DESP = (SELECT FECHA_DESP FROM BIGT_DESPACHOS WHERE CUD = '$cud')
								    AND RUT_DESP = (SELECT RUT_DESP FROM BIGT_DESPACHOS WHERE CUD = '$cud')";

						$result = $db3->query($sql);

						$info = $result->result();

						$cuds = "";
						
						foreach ($info as $key2) {
							if(next($info) == false){
								$cuds = $cuds.$key2->CUD;
							}else{
								$cuds = $cuds.$key2->CUD."','";
							}
						}	

						$sql = "SELECT SUM(CASE WHEN PICKEADO = 'T' THEN 1 ELSE 0 END) PICKEADOS FROM SFS_PICKEODESPACHO WHERE CUD IN ('$cuds')";

						$result = $db2->query($sql);

						foreach ($result->result() as $key2) {
							$pickeados = $key2->PICKEADOS;
						}

						$sql = "SELECT 
								    NOMBRE_DESP, 
								    COUNT(CUD) CUDS
								FROM 
								    BIGT_DESPACHOS
								WHERE
								    FECHA_DESP = (SELECT FECHA_DESP FROM BIGT_DESPACHOS WHERE CUD = '$cud')
								    AND RUT_DESP = (SELECT RUT_DESP FROM BIGT_DESPACHOS WHERE CUD = '$cud')
								GROUP BY
								    NOMBRE_DESP";

						$result = $db3->query($sql);

						foreach ($result->result() as $key2) {
							$cliente = $key2->NOMBRE_DESP;
							$cuds = $key2->CUDS;
						}	    
					}

					$data[] = array(
						"CUD" => $key->CUD,
						"ARTICULO" => $key->ARTICULO,
						"ID" => $key->ID,
						"PATENTE" => $key->PATENTE,
						"NOMBRE_TRANSPORTISTA" => $key->NOMBRE_TRANSPORTISTA,
						"SKU_DESC" => $descripcion,
						"CANTIDAD" => $key->CANTIDAD,
						"CLIENTE" => $cliente,
						"BULTOS" => $pickeados.'/'.$cuds
					);
				}

				$this->db->close();
				return json_encode($data);
			}
			return 4;
		}else{
			$sql = "SELECT 
						CUD, 
						ARTICULO, 
						CANTIDAD,
						ID,
						PATENTE,
						NOMBRE_TRANSPORTISTA, 
						TO_CHAR(FECHA_DESPACHO, 'DD/MM/YYYY') FECHA_DESPACHO, 
						TIENDA,
						INFORMADO,
						PICKEADO,
						ESCANEADO 
					FROM 
						SFS_PICKEODESPACHO 
					WHERE 
						CUD = '$cud'";

			$result = $db2->query($sql);
			if(sizeof($result->result())>0){

				foreach ($result->result() as $key) {
					if($key->PICKEADO == "T"){

						return 1;
					}
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
	}

	public function Pick($cud, $fecha, $tienda){

		$db2 = $this->load->database('PMMWMS', TRUE);

		$db3 = $this->load->database('BTPROD', TRUE);


		if($tienda == 10034 || $tienda == 10018 || $tienda == 10057 || $tienda == 10045 || $tienda == 10012){
			$sql = "SELECT 
						CUD, 
						ARTICULO, 
						CANTIDAD,
						ID,
						PATENTE,
						NOMBRE_TRANSPORTISTA, 
						TO_CHAR(FECHA_PLANIFICACION, 'DD/MM/YYYY') FECHA_PLANIFICACION, 
						TIENDA,
						INFORMADO 
					FROM 
						SFS_PICKEODESPACHO 
					WHERE 
						CUD = '$cud'";

			$result = $db2->query($sql);
			if(sizeof($result->result())>0){

				foreach ($result->result() as $key) {

					if($key->FECHA_PLANIFICACION != $fecha){

						return 2;
					}
					if($key->TIENDA != $tienda){

						return 3;
					}if($key->INFORMADO != 'T'){

						return 5;
					}else{

						$sql = "UPDATE
									SFS_PICKEODESPACHO
								SET
									PICKEADO = 'T',
									FECHA_PICKEADO = SYSDATE
								WHERE 
									CUD = '$cud'";

						$result = $db2->query($sql);

						$sql = "SELECT SKU_DESC FROM ITEM_MASTER WHERE SKU_BRCD = '$key->ARTICULO'";

						$result = $db2->query($sql);

						foreach ($result->result() as $key2) {
							$descripcion = $key2->SKU_DESC;
						}

						$sql = "SELECT 
								    CUD
								FROM 
								    BIGT_DESPACHOS
								WHERE
								    FECHA_DESP = (SELECT FECHA_DESP FROM BIGT_DESPACHOS WHERE CUD = '$cud')
								    AND RUT_DESP = (SELECT RUT_DESP FROM BIGT_DESPACHOS WHERE CUD = '$cud')";

						$result = $db3->query($sql);

						$info = $result->result();

						$cuds = "";
						
						foreach ($info as $key2) {
							if(next($info) == false){
								$cuds = $cuds.$key2->CUD;
							}else{
								$cuds = $cuds.$key2->CUD."','";
							}
						}	

						$sql = "SELECT SUM(CASE WHEN PICKEADO = 'T' THEN 1 ELSE 0 END) PICKEADOS FROM SFS_PICKEODESPACHO WHERE CUD IN ('$cuds')";

						$result = $db2->query($sql);

						foreach ($result->result() as $key2) {
							$pickeados = $key2->PICKEADOS;
						}

						$sql = "SELECT 
								    NOMBRE_DESP, 
								    COUNT(CUD) CUDS
								FROM 
								    BIGT_DESPACHOS
								WHERE
								    FECHA_DESP = (SELECT FECHA_DESP FROM BIGT_DESPACHOS WHERE CUD = '$cud')
								    AND RUT_DESP = (SELECT RUT_DESP FROM BIGT_DESPACHOS WHERE CUD = '$cud')
								GROUP BY
								    NOMBRE_DESP";

						$result = $db3->query($sql);

						foreach ($result->result() as $key2) {
							$cliente = $key2->NOMBRE_DESP;
							$cuds = $key2->CUDS;
						}	    
					}

					$data[] = array(
						"CUD" => $key->CUD,
						"ARTICULO" => $key->ARTICULO,
						"ID" => $key->ID,
						"PATENTE" => $key->PATENTE,
						"NOMBRE_TRANSPORTISTA" => $key->NOMBRE_TRANSPORTISTA,
						"SKU_DESC" => $descripcion,
						"CANTIDAD" => $key->CANTIDAD,
						"CLIENTE" => $cliente,
						"BULTOS" => $pickeados.'/'.$cuds
					);
				}

				$this->db->close();
				return json_encode($data);
			}
			return 4;
		}else{
			$sql = "SELECT 
						CUD, 
						ARTICULO, 
						CANTIDAD,
						ID,
						PATENTE,
						NOMBRE_TRANSPORTISTA, 
						TO_CHAR(FECHA_DESPACHO, 'DD/MM/YYYY') FECHA_PLANIFICACION, 
						TIENDA,
						INFORMADO 
					FROM 
						SFS_PICKEODESPACHO 
					WHERE 
						CUD = '$cud'";

			$result = $db2->query($sql);
			if(sizeof($result->result())>0){

				foreach ($result->result() as $key) {

					if($key->FECHA_PLANIFICACION != $fecha){

						return 2;
					}
					if($key->TIENDA != $tienda){

						return 3;
					}if($key->INFORMADO != 'T'){

						return 5;
					}else{

						$sql = "UPDATE
									SFS_PICKEODESPACHO
								SET
									PICKEADO = 'T',
									FECHA_PICKEADO = SYSDATE
								WHERE 
									CUD = '$cud'";

						$result = $db2->query($sql);

						$sql = "SELECT SKU_DESC FROM ITEM_MASTER WHERE SKU_BRCD = '$key->ARTICULO'";

						$result = $db2->query($sql);

						foreach ($result->result() as $key2) {
							$descripcion = $key2->SKU_DESC;
						}

						$sql = "SELECT 
								    CUD
								FROM 
								    BIGT_DESPACHOS
								WHERE
								    FECHA_DESP = (SELECT FECHA_DESP FROM BIGT_DESPACHOS WHERE CUD = '$cud')
								    AND RUT_DESP = (SELECT RUT_DESP FROM BIGT_DESPACHOS WHERE CUD = '$cud')";

						$result = $db3->query($sql);

						$info = $result->result();

						$cuds = "";
						
						foreach ($info as $key2) {
							if(next($info) == false){
								$cuds = $cuds.$key2->CUD;
							}else{
								$cuds = $cuds.$key2->CUD."','";
							}
						}	

						$sql = "SELECT SUM(CASE WHEN PICKEADO = 'T' THEN 1 ELSE 0 END) PICKEADOS FROM SFS_PICKEODESPACHO WHERE CUD IN ('$cuds')";

						$result = $db2->query($sql);

						foreach ($result->result() as $key2) {
							$pickeados = $key2->PICKEADOS;
						}

						$sql = "SELECT 
								    NOMBRE_DESP, 
								    COUNT(CUD) CUDS
								FROM 
								    BIGT_DESPACHOS
								WHERE
								    FECHA_DESP = (SELECT FECHA_DESP FROM BIGT_DESPACHOS WHERE CUD = '$cud')
								    AND RUT_DESP = (SELECT RUT_DESP FROM BIGT_DESPACHOS WHERE CUD = '$cud')
								GROUP BY
								    NOMBRE_DESP";

						$result = $db3->query($sql);

						foreach ($result->result() as $key2) {
							$cliente = $key2->NOMBRE_DESP;
							$cuds = $key2->CUDS;
						}	    
					}

					$data[] = array(
						"CUD" => $key->CUD,
						"ARTICULO" => $key->ARTICULO,
						"ID" => $key->ID,
						"PATENTE" => $key->PATENTE,
						"NOMBRE_TRANSPORTISTA" => $key->NOMBRE_TRANSPORTISTA,
						"SKU_DESC" => $descripcion,
						"CANTIDAD" => $key->CANTIDAD,
						"CLIENTE" => $cliente,
						"BULTOS" => $pickeados.'/'.$cuds
					);
				}

				$this->db->close();
				return json_encode($data);
			}
			return 4;
		}	
	}

	public function PickFaltantes($cud, $fecha, $tienda){

		$db2 = $this->load->database('PMMWMS', TRUE);

		if($tienda == 10034 || $tienda == 10018 || $tienda == 10057 || $tienda == 10045 || $tienda == 10012){
			$sql = "SELECT 
						CUD, 
						ARTICULO, 
						CANTIDAD,
						ID,
						PATENTE,
						NOMBRE_TRANSPORTISTA, 
						TO_CHAR(FECHA_PLANIFICACION, 'DD/MM/YYYY') FECHA_PLANIFICACION, 
						TIENDA,
						INFORMADO,
						PICKEADO,
						ESCANEADO
					FROM 
						SFS_PICKEODESPACHO 
					WHERE 
						CUD = '$cud'";

			$result = $db2->query($sql);
			if(sizeof($result->result())>0){

				foreach ($result->result() as $key) {
					if($key->ESCANEADO == 'T'){

						return 1;
					}
					if($key->FECHA_PLANIFICACION != $fecha){

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
								ESCANEADO = 'T',
								FECHA_ESCANEADO = SYSDATE
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
		}else{
			$sql = "SELECT 
						CUD, 
						ARTICULO, 
						CANTIDAD,
						ID,
						PATENTE,
						NOMBRE_TRANSPORTISTA, 
						TO_CHAR(FECHA_DESPACHO, 'DD/MM/YYYY') FECHA_DESPACHO, 
						TIENDA,
						INFORMADO,
						PICKEADO,
						ESCANEADO
					FROM 
						SFS_PICKEODESPACHO 
					WHERE 
						CUD = '$cud'";

			$result = $db2->query($sql);
			if(sizeof($result->result())>0){

				foreach ($result->result() as $key) {
					if($key->ESCANEADO == 'T'){

						return 1;
					}
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
								ESCANEADO = 'T',
								FECHA_ESCANEADO = SYSDATE
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
			
			$sql = "SELECT PICKEADO, DEVUELTO, TRUNC(FECHA_CARGA)  FECHA_CARGA, TRUNC(SYSDATE) FECHA_ACTUAL FROM SFS_PICKEODESPACHO WHERE CUD = TRIM('$key[CUD]')";

			$result = $db3->query($sql);

			if(sizeof($result->result()) > 0){
				foreach ($result->result() as $key2) {
					if($key2->PICKEADO == "T"){
						if($key2->FECHA_CARGA == $key2->FECHA_ACTUAL){
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
										CHOFER = '$key[TRANSPORTISTA]',
										FECHA_DESPACHO = SYSDATE+1,
										PICKEADO = 'F',
										FECHA_PICKEADO = NULL,
										ESCANEADO = 'F',
										FECHA_ESCANEADO = NULL,
										DEVUELTO = 'F',
										FECHA_DEVUELTO = NULL,
										CAMPO1 = NULL,
										CAMPO2 = NULL
									WHERE
										CUD = TRIM('$key[CUD]')";

							$resp = $db3->query($sql);

							if(!$resp){
								return 1;
							}
						}
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
									CHOFER = '$key[TRANSPORTISTA]'
								WHERE
									CUD = TRIM('$key[CUD]')";

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
							CUD = TRIM('$key[CUD]')";

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
														NOMBRE_TRANSPORTISTA,
														CHOFER,
														DEVUELTO) 
														VALUES (TRIM('$key[CUD]'), 
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
																'$key[COURIER]',
																'$key[TRANSPORTISTA]',
																'F')";

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

		if($tienda == 10034 || $tienda == 10018 || $tienda == 10057 || $tienda == 10045 || $tienda == 10012){
			$sql = "SELECT
					    CUD,
					    ARTICULO,
					    FECHA_DESPACHO FECHA,
					    TIENDA
					FROM
					    SFS_PICKEODESPACHO
					WHERE
					    TRUNC(FECHA_PLANIFICACION) = '$fecha'
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
		}else{
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
	}

	public function totalPick($fecha, $tienda){

		$db3 = $this->load->database('PMMWMS', TRUE);

		if($tienda == 10034 || $tienda == 10018 || $tienda == 10057 || $tienda == 10045 || $tienda == 10012){
			$sql = "SELECT 
						SUM(CASE WHEN PICKEADO = 'T' THEN 1 ELSE 0 END) PICKEADOS,
						SUM(CASE WHEN INFORMADO = 'T' THEN 1 ELSE 0 END) TOTAL
					FROM
						SFS_PICKEODESPACHO
					WHERE
						TRUNC(FECHA_PLANIFICACION) = '$fecha'
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
		}else{
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
	}

	public function resumenDespacho($id, $fecha, $tienda){

		$db2 = $this->load->database('default', TRUE);

		$db3 = $this->load->database('BTPROD', TRUE);

		$cuds = "";

		$fechaDespacho = "";
		$articulo = "";
		$cantidad = "";
		$nroboleta = "";

		$sql = "SELECT 
					(SELECT COUNT(*) FROM SHIP_FROM_STORE WHERE ID = '$id' AND ESCANEADO = 'T' AND TRUNC(FECHA_DE_CARGA) = '$fecha' AND TIENDA = '$tienda') TOTAL,
					(SELECT COUNT(DISTINCT NRO_GUIA) FROM SHIP_FROM_STORE WHERE ID = '$id' AND ESCANEADO = 'T' AND TRUNC(FECHA_DE_CARGA) = '$fecha' AND TIENDA = '$tienda') TOTAL_GUIAS,
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
		$sheet->SetCellValue("A3", "CUDS:");
		$sheet->getStyle("A3")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("B3", $totalcuds);
		$sheet->getStyle("B3")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("A4", "EMPRESA TRANSPORTE: ");
		$sheet->getStyle("A4")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("B4", $courier);
		$sheet->getStyle("B4")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("A4", "CONDUCTOR: ");
		$sheet->getStyle("A4")->applyFromArray($this->estiloCabeceraP);
		$sheet->SetCellValue("J2", "FIRMA: ");
		$sheet->getStyle("J2")->applyFromArray($this->estiloCabeceraP);



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
		$reg = 1;

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
			$sheet->SetCellValue('B'.$count, $key2->CUD);
			$sheet->SetCellValue('C'.$count, $id);
			$sheet->SetCellValue('D'.$count, $fechaDespacho);
			$sheet->SetCellValue('E'.$count, $nroboleta);
			$sheet->SetCellValue('F'.$count, $articulo);
			$sheet->SetCellValue('G'.$count, $cantidad);
			$sheet->SetCellValue('H'.$count, $nroguia);
			$sheet->SetCellValue('I'.$count, $rutcliente);
			$sheet->SetCellValue('J'.$count, $nombre);
			$sheet->SetCellValue('K'.$count, $direccion);
			$sheet->SetCellValue('L'.$count, $comuna);
			$count++;
			$reg++; 

		}
		foreach (range('A','J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->getPageSetup()->setHorizontalCentered(true);   

		return $spreadsheet;		
	}

	public function resumenDespacho_V2($id, $fecha, $tienda, $opl){

		$db2 = $this->load->database('PMMWMS', TRUE);

		$db3 = $this->load->database('BTPROD', TRUE);

		$cuds = "";

		$fechaDespacho = "";
		$articulo = "";
		$cantidad = "";
		$nroboleta = "";
		$descripcion = "";

		if($tienda == 10034 || $tienda == 10018 || $tienda == 10057 || $tienda == 10045 || $tienda == 10012){
			$sql = "SELECT 
					    (SELECT COUNT(*) FROM SFS_PICKEODESPACHO WHERE ID = '$id' AND PICKEADO = 'T' AND TRUNC(FECHA_PLANIFICACION) = '$fecha' AND TIENDA = '$tienda') TOTAL,
					    (SELECT COUNT(DISTINCT NRO_GUIA) FROM SFS_PICKEODESPACHO WHERE ID = '$id' AND PICKEADO = 'T' AND TRUNC(FECHA_PLANIFICACION) = '$fecha' AND TIENDA = '$tienda') TOTAL_GUIAS,
					    NOMBRE_TRANSPORTISTA COURIER
					FROM 
					    SFS_PICKEODESPACHO 
					WHERE 
					    ID =  '$id'
					    AND TRUNC(FECHA_PLANIFICACION) = '$fecha'
					    AND TIENDA = '$tienda'
						AND NOMBRE_TRANSPORTISTA = '$opl'
					GROUP BY
					    NOMBRE_TRANSPORTISTA";

			$resp = $db2->query($sql);

			foreach ($resp->result() as $key) {
				$totalcuds = $key->TOTAL; 
				$courier = $key->COURIER; 
				$totalguias = $key->TOTAL_GUIAS; 
			}
		    
	        $spreadsheet = new Spreadsheet();
	        $sheet = $spreadsheet->getActiveSheet();

			$sheet->setTitle("Packing List");
			$sheet->SetCellValue("B1", "ID TRANSPORTE: ");
			$sheet->getStyle("B1")->applyFromArray($this->estiloCabeceraI);
			$sheet->SetCellValue("C1", $id);
			$sheet->getStyle("C1")->applyFromArray($this->estiloCabeceraP);
			$sheet->SetCellValue("B2", "CUDS:");
			$sheet->getStyle("B2")->applyFromArray($this->estiloCabeceraI);
			$sheet->SetCellValue("C2", $totalcuds);
			$sheet->getStyle("C2")->applyFromArray($this->estiloCabeceraP);
			$sheet->SetCellValue("B3", "GUIAS: ");
			$sheet->getStyle("B3")->applyFromArray($this->estiloCabeceraI);
			$sheet->SetCellValue("C3", $totalguias);
			$sheet->getStyle("C3")->applyFromArray($this->estiloCabeceraP);
			$sheet->SetCellValue("B4", "EMPRESA TRANSPORTE: ");
			$sheet->getStyle("B4")->applyFromArray($this->estiloCabeceraI);
			$sheet->SetCellValue("C4", $courier);
			$sheet->getStyle("C4")->applyFromArray($this->estiloCabeceraP);
			$sheet->SetCellValue("B5", "CONDUCTOR: ");
			$sheet->getStyle("B5")->applyFromArray($this->estiloCabeceraI);
			$sheet->SetCellValue("K2", "FIRMA: ");
			$sheet->getStyle("K2")->applyFromArray($this->estiloCabeceraI);



			$sheet->SetCellValue("B7", "CUD");
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
			$sheet->SetCellValue("F7", "DESCRIPCION");
			$sheet->getStyle("F7")->applyFromArray($this->estiloCabeceraP);
			$sheet->getStyle("F7")->getBorders()
			->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
			$sheet->SetCellValue("G7", "CANTIDAD");
			$sheet->getStyle("G7")->applyFromArray($this->estiloCabeceraP);
			$sheet->getStyle("G7")->getBorders()
			->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
			$sheet->SetCellValue("H7", "NRO GUIA");
			$sheet->getStyle("H7")->applyFromArray($this->estiloCabeceraP);
			$sheet->getStyle("H7")->getBorders()
			->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
			$sheet->SetCellValue("I7", "RUT CLIENTE");
			$sheet->getStyle("I7")->applyFromArray($this->estiloCabeceraP);
			$sheet->getStyle("I7")->getBorders()
			->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
			$sheet->SetCellValue("J7", "NOMBRE CLIENTE");
			$sheet->getStyle("J7")->applyFromArray($this->estiloCabeceraP);
			$sheet->getStyle("J7")->getBorders()
			->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
			$sheet->SetCellValue("K7", "DIRECCION CLIENTE");
			$sheet->getStyle("K7")->applyFromArray($this->estiloCabeceraP);
			$sheet->getStyle("K7")->getBorders()
			->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
			$sheet->SetCellValue("L7", "COMUNA");
			$sheet->getStyle("L7")->applyFromArray($this->estiloCabeceraP);
			$sheet->getStyle("L7")->getBorders()
			->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

			$sql = "SELECT CUD FROM SFS_PICKEODESPACHO WHERE ID = '$id' AND PICKEADO = 'T' AND TRUNC(FECHA_PLANIFICACION) = '$fecha' AND TIENDA = '$tienda' AND NOMBRE_TRANSPORTISTA = '$opl'";

			$resp = $db2->query($sql);
			$info = $resp->result();

			$count = 8;
			$reg = 1;

			foreach ($info as $key2) {
				if(next($info) == false){
					$cuds = $cuds.$key2->CUD;
				}else{
					$cuds = $cuds.$key2->CUD."','";
				}
				
			}

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
						BD.CUD in ('$cuds')
						AND BD.COD_REGION = BC.COD_REGION
						AND BD.COD_COMUNA = BC.COD_COMUNA
					ORDER BY
						BD.NOMBRE_DESP ASC";

			$resp2 = $db3->query($sql);

			foreach ($resp2->result() as $key3) {
				$cud = $key3->CUD;
				$fechaDespacho = $key3->FECHA_DESP;
				$articulo = $key3->CODIGO_VTA." ";
				$cantidad = $key3->CANTIDAD;
				$nroboleta = $key3->NRO_BOLETA;
				$rutcliente = $key3->RUT_CLIENTE;
				$nombre = $key3->NOMBRE_DESP;
				$direccion = $key3->DIRECCION_DESP;
				$comuna = $key3->DESCRIPCION_COMUNA;
				$nroguia = $key3->NRO_GUIA;

				$sql = "SELECT SKU_DESC FROM ITEM_MASTER WHERE SKU_BRCD = '$key3->CODIGO_VTA'";
			

				$resp3 = $db2->query($sql);

				foreach ($resp3->result() as $desc) {
					$descripcion = $desc->SKU_DESC;
				}

				$sheet->SetCellValue('A'.$count, $reg);
				$sheet->getStyle('A'.$count)->applyFromArray($this->estiloCelda);
				$sheet->getStyle('A'.$count)->getBorders()
				->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->SetCellValue('B'.$count, $cud);
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
				$sheet->SetCellValue('F'.$count, $descripcion);
				$sheet->getStyle('F'.$count)->applyFromArray($this->estiloCelda);
				$sheet->getStyle('F'.$count)->getBorders()
				->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->SetCellValue('G'.$count, $cantidad);
				$sheet->getStyle('G'.$count)->applyFromArray($this->estiloCelda);
				$sheet->getStyle('G'.$count)->getBorders()
				->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->SetCellValue('H'.$count, $nroguia);
				$sheet->getStyle('H'.$count)->applyFromArray($this->estiloCelda);
				$sheet->getStyle('H'.$count)->getBorders()
				->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->SetCellValue('I'.$count, $rutcliente);
				$sheet->getStyle('I'.$count)->applyFromArray($this->estiloCelda);
				$sheet->getStyle('I'.$count)->getBorders()
				->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->SetCellValue('J'.$count, $nombre);
				$sheet->getStyle('J'.$count)->applyFromArray($this->estiloCelda);
				$sheet->getStyle('J'.$count)->getBorders()
				->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->SetCellValue('K'.$count, $direccion);
				$sheet->getStyle('K'.$count)->applyFromArray($this->estiloCelda);
				$sheet->getStyle('K'.$count)->getBorders()
				->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->SetCellValue('L'.$count, $comuna);
				$sheet->getStyle('L'.$count)->applyFromArray($this->estiloCelda);
				$sheet->getStyle('L'.$count)->getBorders()
				->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$count++;
				$reg++; 
			}

			foreach (range('B','L') as $col) {
	            $sheet->getColumnDimension($col)->setAutoSize(true);
	        }
	        $sheet->getPageSetup()->setOrientation(PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
	        $sheet->getPageSetup()->setOrientation(PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
	        

			return $spreadsheet;
		}
		else{
			$sql = "SELECT 
					    (SELECT COUNT(*) FROM SFS_PICKEODESPACHO WHERE ID = '$id' AND PICKEADO = 'T' AND TRUNC(FECHA_DESPACHO) = '$fecha' AND TIENDA = '$tienda') TOTAL,
					    (SELECT COUNT(DISTINCT NRO_GUIA) FROM SFS_PICKEODESPACHO WHERE ID = '$id' AND PICKEADO = 'T' AND TRUNC(FECHA_DESPACHO) = '$fecha' AND TIENDA = '$tienda') TOTAL_GUIAS,
					    NOMBRE_TRANSPORTISTA COURIER
					FROM 
					    SFS_PICKEODESPACHO 
					WHERE 
					    ID =  '$id'
					    AND TRUNC(FECHA_DESPACHO) = '$fecha'
					    AND TIENDA = '$tienda'
						AND NOMBRE_TRANSPORTISTA = '$opl'
					GROUP BY
					    NOMBRE_TRANSPORTISTA";

			$resp = $db2->query($sql);

			foreach ($resp->result() as $key) {
				$totalcuds = $key->TOTAL; 
				$courier = $key->COURIER; 
				$totalguias = $key->TOTAL_GUIAS; 
			}
		    
	        $spreadsheet = new Spreadsheet();
	        $sheet = $spreadsheet->getActiveSheet();

			$sheet->setTitle("Packing List");
			$sheet->SetCellValue("B1", "ID TRANSPORTE: ");
			$sheet->getStyle("B1")->applyFromArray($this->estiloCabeceraI);
			$sheet->SetCellValue("C1", $id);
			$sheet->getStyle("C1")->applyFromArray($this->estiloCabeceraP);
			$sheet->SetCellValue("B2", "CUDS:");
			$sheet->getStyle("B2")->applyFromArray($this->estiloCabeceraI);
			$sheet->SetCellValue("C2", $totalcuds);
			$sheet->getStyle("C2")->applyFromArray($this->estiloCabeceraP);
			$sheet->SetCellValue("B3", "GUIAS: ");
			$sheet->getStyle("B3")->applyFromArray($this->estiloCabeceraI);
			$sheet->SetCellValue("C3", $totalguias);
			$sheet->getStyle("C3")->applyFromArray($this->estiloCabeceraP);
			$sheet->SetCellValue("B4", "EMPRESA TRANSPORTE: ");
			$sheet->getStyle("B4")->applyFromArray($this->estiloCabeceraI);
			$sheet->SetCellValue("C4", $courier);
			$sheet->getStyle("C4")->applyFromArray($this->estiloCabeceraP);
			$sheet->SetCellValue("B5", "CONDUCTOR: ");
			$sheet->getStyle("B5")->applyFromArray($this->estiloCabeceraI);
			$sheet->SetCellValue("K2", "FIRMA: ");
			$sheet->getStyle("K2")->applyFromArray($this->estiloCabeceraI);



			$sheet->SetCellValue("B7", "CUD");
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
			$sheet->SetCellValue("F7", "DESCRIPCION");
			$sheet->getStyle("F7")->applyFromArray($this->estiloCabeceraP);
			$sheet->getStyle("F7")->getBorders()
			->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
			$sheet->SetCellValue("G7", "CANTIDAD");
			$sheet->getStyle("G7")->applyFromArray($this->estiloCabeceraP);
			$sheet->getStyle("G7")->getBorders()
			->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
			$sheet->SetCellValue("H7", "NRO GUIA");
			$sheet->getStyle("H7")->applyFromArray($this->estiloCabeceraP);
			$sheet->getStyle("H7")->getBorders()
			->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
			$sheet->SetCellValue("I7", "RUT CLIENTE");
			$sheet->getStyle("I7")->applyFromArray($this->estiloCabeceraP);
			$sheet->getStyle("I7")->getBorders()
			->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
			$sheet->SetCellValue("J7", "NOMBRE CLIENTE");
			$sheet->getStyle("J7")->applyFromArray($this->estiloCabeceraP);
			$sheet->getStyle("J7")->getBorders()
			->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
			$sheet->SetCellValue("K7", "DIRECCION CLIENTE");
			$sheet->getStyle("K7")->applyFromArray($this->estiloCabeceraP);
			$sheet->getStyle("K7")->getBorders()
			->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
			$sheet->SetCellValue("L7", "COMUNA");
			$sheet->getStyle("L7")->applyFromArray($this->estiloCabeceraP);
			$sheet->getStyle("L7")->getBorders()
			->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

			$sql = "SELECT CUD FROM SFS_PICKEODESPACHO WHERE ID = '$id' AND PICKEADO = 'T' AND TRUNC(FECHA_DESPACHO) = '$fecha' AND TIENDA = '$tienda' AND NOMBRE_TRANSPORTISTA = '$opl'";

			$resp = $db2->query($sql);
			$info = $resp->result();

			$count = 8;
			$reg = 1;

			foreach ($info as $key2) {
				if(next($info) == false){
					$cuds = $cuds.$key2->CUD;
				}else{
					$cuds = $cuds.$key2->CUD."','";
				}
				
			}

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
						BD.CUD in ('$cuds')
						AND BD.COD_REGION = BC.COD_REGION
						AND BD.COD_COMUNA = BC.COD_COMUNA
					ORDER BY
						BD.NOMBRE_DESP ASC";

			$resp2 = $db3->query($sql);

			foreach ($resp2->result() as $key3) {
				$cud = $key3->CUD;
				$fechaDespacho = $key3->FECHA_DESP;
				$articulo = $key3->CODIGO_VTA." ";
				$cantidad = $key3->CANTIDAD;
				$nroboleta = $key3->NRO_BOLETA;
				$rutcliente = $key3->RUT_CLIENTE;
				$nombre = $key3->NOMBRE_DESP;
				$direccion = $key3->DIRECCION_DESP;
				$comuna = $key3->DESCRIPCION_COMUNA;
				$nroguia = $key3->NRO_GUIA;

				$sql = "SELECT SKU_DESC FROM ITEM_MASTER WHERE SKU_BRCD = '$key3->CODIGO_VTA'";
			

				$resp3 = $db2->query($sql);

				foreach ($resp3->result() as $desc) {
					$descripcion = $desc->SKU_DESC;
				}

				$sheet->SetCellValue('A'.$count, $reg);
				$sheet->getStyle('A'.$count)->applyFromArray($this->estiloCelda);
				$sheet->getStyle('A'.$count)->getBorders()
				->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->SetCellValue('B'.$count, $cud);
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
				$sheet->SetCellValue('F'.$count, $descripcion);
				$sheet->getStyle('F'.$count)->applyFromArray($this->estiloCelda);
				$sheet->getStyle('F'.$count)->getBorders()
				->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->SetCellValue('G'.$count, $cantidad);
				$sheet->getStyle('G'.$count)->applyFromArray($this->estiloCelda);
				$sheet->getStyle('G'.$count)->getBorders()
				->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->SetCellValue('H'.$count, $nroguia);
				$sheet->getStyle('H'.$count)->applyFromArray($this->estiloCelda);
				$sheet->getStyle('H'.$count)->getBorders()
				->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->SetCellValue('I'.$count, $rutcliente);
				$sheet->getStyle('I'.$count)->applyFromArray($this->estiloCelda);
				$sheet->getStyle('I'.$count)->getBorders()
				->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->SetCellValue('J'.$count, $nombre);
				$sheet->getStyle('J'.$count)->applyFromArray($this->estiloCelda);
				$sheet->getStyle('J'.$count)->getBorders()
				->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->SetCellValue('K'.$count, $direccion);
				$sheet->getStyle('K'.$count)->applyFromArray($this->estiloCelda);
				$sheet->getStyle('K'.$count)->getBorders()
				->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$sheet->SetCellValue('L'.$count, $comuna);
				$sheet->getStyle('L'.$count)->applyFromArray($this->estiloCelda);
				$sheet->getStyle('L'.$count)->getBorders()
				->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
				$count++;
				$reg++; 
			}

			foreach (range('B','L') as $col) {
	            $sheet->getColumnDimension($col)->setAutoSize(true);
	        }
	        $sheet->getPageSetup()->setOrientation(PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
	        $sheet->getPageSetup()->setOrientation(PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
	        

			return $spreadsheet;
		}
				
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

	public function getIds_V2($fecha, $tienda, $opl){
		$db2 = $this->load->database('PMMWMS', TRUE);

		if($tienda == 10034 || $tienda == 10018 || $tienda == 10057 || $tienda == 10045 || $tienda == 10012){
			$sql = "SELECT
					    ID
					FROM 
					    SFS_PICKEODESPACHO
					WHERE
					    TRUNC(FECHA_PLANIFICACION) = '$fecha'
					    AND TIENDA = '$tienda'
					    AND INFORMADO = 'T'
						AND NOMBRE_TRANSPORTISTA = '$opl'
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
		else{
			$sql = "SELECT
					    ID
					FROM 
					    SFS_PICKEODESPACHO
					WHERE
					    TRUNC(FECHA_DESPACHO) = '$fecha'
					    AND TIENDA = '$tienda'
					    AND INFORMADO = 'T'
						AND NOMBRE_TRANSPORTISTA = '$opl'
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
	}

	public function detalleFaltantes($id ,$fecha, $tienda, $opl){

		$db2 = $this->load->database('BTPROD', TRUE);

		$db3 = $this->load->database('PMMWMS', TRUE);

		if($tienda == 10034 || $tienda == 10018 || $tienda == 10057 || $tienda == 10045 || $tienda == 10012){
			$sql = "SELECT
					    CUD,
					    ARTICULO,
					    FECHA_DESPACHO FECHA,
					    TIENDA
					FROM
					    SFS_PICKEODESPACHO
					WHERE
					    TRUNC(FECHA_PLANIFICACION) = '$fecha'
					    AND TIENDA = '$tienda'
					    AND PICKEADO = 'T'
					    AND ESCANEADO = 'F'
						AND ID = '$id'
						AND NOMBRE_TRANSPORTISTA = '$opl'";

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
		else{
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
					    AND PICKEADO = 'T'
					    AND ESCANEADO = 'F'
						AND ID = '$id'
						AND NOMBRE_TRANSPORTISTA = '$opl'";

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
			
	}

	public function totalFaltantes($id, $fecha, $tienda, $opl){

		$db3 = $this->load->database('PMMWMS', TRUE);

		if($tienda == 10034 || $tienda == 10018 || $tienda == 10057 || $tienda == 10045 || $tienda == 10012){
			$sql = "SELECT 
						SUM(CASE WHEN ESCANEADO = 'T' THEN 1 ELSE 0 END) PICKEADOS,
						SUM(CASE WHEN PICKEADO = 'T' THEN 1 ELSE 0 END) TOTAL
					FROM
						SFS_PICKEODESPACHO
					WHERE
						TRUNC(FECHA_PLANIFICACION) = '$fecha'
						AND TIENDA = '$tienda'
						AND ID = '$id'
						AND NOMBRE_TRANSPORTISTA = '$opl'";

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
		else{
			$sql = "SELECT 
						SUM(CASE WHEN ESCANEADO = 'T' THEN 1 ELSE 0 END) PICKEADOS,
						SUM(CASE WHEN PICKEADO = 'T' THEN 1 ELSE 0 END) TOTAL
					FROM
						SFS_PICKEODESPACHO
					WHERE
						TRUNC(FECHA_DESPACHO) = '$fecha'
						AND TIENDA = '$tienda'
						AND ID = '$id'
						AND NOMBRE_TRANSPORTISTA = '$opl'";

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

	public function dataDashboard($desde, $hasta){

		$sumasignado = 0;
		$sumdiferencia = 0;
		$sumdevuelto = 0;
		$sumrechazo = 0;
		$sumcomprometido = 0;
		$suminformado = 0;
		$totporInformado = 0;
		$totporDevuelto = 0;
		$totporPickeado = 0;
		$totpordiffIP = 0;
		$totpordiffPI = 0;

		$data = array();

		$db3 = $this->load->database('PMMWMS', TRUE);

		$sql = "SELECT 
				    SFS.TIENDA||' - '|| SM.NAME TIENDA,
				    COUNT(SFS.CUD) TOTAL,
				    SUM(CASE WHEN SFS.PICKEADO = 'T' THEN 1 ELSE 0 END) TOTAL_PICKEADO,
				    SUM(CASE WHEN SFS.INFORMADO = 'T' THEN 1 ELSE 0 END) TOTAL_INFORMADO,
				    SUM(CASE WHEN SFS.ESCANEADO = 'T' THEN 1 ELSE 0 END) TOTAL_ESCANEADO,
				    SUM(CASE WHEN SFS.DEVUELTO = 'T' THEN 1 ELSE 0 END) TOTAL_DEVUELTO
				FROM 
				    SFS_PICKEODESPACHO SFS,
				    STORE_MASTER SM
				WHERE
				    SFS.FECHA_DESPACHO BETWEEN TO_DATE('$desde') AND TO_DATE('$hasta')
				    AND SFS.TIENDA = SM.STORE_NBR
				    AND SFS.TIENDA <> '10034'
				GROUP BY
				    SFS.TIENDA||' - '|| SM.NAME";

		$result = $db3->query($sql);

		foreach ($result->result() as $key) {
			$resultados[] = array(
				"TIENDA" => $key->TIENDA,
				"TOTAL" => $key->TOTAL,
				"TOTAL_PICKEADO" => $key->TOTAL_PICKEADO,
				"TOTAL_INFORMADO" => $key->TOTAL_INFORMADO,
				"TOTAL_ESCANEADO" => $key->TOTAL_ESCANEADO,
				"TOTAL_DEVUELTO" => $key->TOTAL_DEVUELTO,
			);
		}

		$sql = "SELECT 
				    SFS.TIENDA||' - '|| SM.NAME TIENDA,
				    COUNT(SFS.CUD) TOTAL,
				    SUM(CASE WHEN SFS.PICKEADO = 'T' THEN 1 ELSE 0 END) TOTAL_PICKEADO,
				    SUM(CASE WHEN SFS.INFORMADO = 'T' THEN 1 ELSE 0 END) TOTAL_INFORMADO,
				    SUM(CASE WHEN SFS.ESCANEADO = 'T' THEN 1 ELSE 0 END) TOTAL_ESCANEADO,
				    SUM(CASE WHEN SFS.DEVUELTO = 'T' THEN 1 ELSE 0 END) TOTAL_DEVUELTO
				FROM 
				    SFS_PICKEODESPACHO SFS,
				    STORE_MASTER SM
				WHERE
				    SFS.FECHA_PLANIFICACION BETWEEN TO_DATE('$desde') AND TO_DATE('$hasta')
				    AND SFS.TIENDA = SM.STORE_NBR
				    AND SFS.TIENDA = '10034'
				GROUP BY
				    SFS.TIENDA||' - '|| SM.NAME";

		$result2 = $db3->query($sql);

		foreach ($result2->result() as $key) {
			$resultados[] = array(
				"TIENDA" => $key->TIENDA,
				"TOTAL" => $key->TOTAL,
				"TOTAL_PICKEADO" => $key->TOTAL_PICKEADO,
				"TOTAL_INFORMADO" => $key->TOTAL_INFORMADO,
				"TOTAL_ESCANEADO" => $key->TOTAL_ESCANEADO,
				"TOTAL_DEVUELTO" => $key->TOTAL_DEVUELTO,
			);
		}

		foreach ($resultados as $key) {
			$tienda = $key['TIENDA'];
			$total = $key['TOTAL'];
			$totalPickeado = $key['TOTAL_PICKEADO'];
			$totalInformado = $key['TOTAL_INFORMADO'];
			$totalEscaneado = $key['TOTAL_ESCANEADO'];
			$totalDevuelto = $key['TOTAL_DEVUELTO'];



			$diferenciaPI = $total - $totalInformado;


			if($totalInformado == 0){
				$pordiffPI = 100;
			}else{
				$pordiffPI = round(($diferenciaPI/$total)*100,1).'%';
			}

			$diferenciaIP = $totalInformado - $totalPickeado;

			

			if($diferenciaIP == 0){
				$pordiffIP = 0;
			}else{
				$pordiffIP = round(($diferenciaIP/$totalInformado)*100,1).'%';
			}

			if($totalInformado == 0){
				$porcentajePickeado = 0;
			}else{
				$porcentajePickeado = round(($totalPickeado * 100)/$totalInformado,1);
			}
			
			$porcentajeInformado = round(($totalInformado * 100)/$total,1);

			if($totalPickeado == 0){
				$porcentajeEscaneado = 0;
			}else{
				$porcentajeEscaneado = round(($totalEscaneado * 100)/$totalPickeado,1);
			}

			if($totalPickeado == 0){
				$porcentajeDevuelto = 0;
			}else{
				$porcentajeDevuelto = round(($totalDevuelto * 100)/$totalPickeado,1);
			}

			if($totalPickeado > 0){

				$sumasignado = $sumasignado + $totalPickeado;

				$sumdiferencia = $sumdiferencia + $diferenciaPI;

				$sumdevuelto = $sumdevuelto + $totalDevuelto;

				$sumrechazo = $sumrechazo + $diferenciaIP;

				$sumcomprometido = $sumcomprometido + $total;

				$suminformado = $suminformado + $totalInformado;

				$totporInformado = round(($suminformado * 100)/$sumcomprometido,1);

				$totporDevuelto = round(($sumdevuelto * 100)/$sumasignado,1);

				$totporPickeado = round(($sumasignado * 100)/$suminformado,1);

				$totpordiffIP = round(($sumrechazo/$suminformado)*100,1).'%';

				$totpordiffPI = round(($sumdiferencia/$sumcomprometido)*100,1).'%';

				$data[] = array(
					"TIENDA" => $tienda,
					"INFORMADO" => $totalInformado,
					"PLANIFICADO" => $total,
					"DIFERENCIA_PLAN_INFO" => $diferenciaPI,
					"POR_DIFERENCIA_PLAN_INFO" => $pordiffPI,
					"POR_INFORMADO" => $porcentajeInformado,
					"ASIGNADO" => $totalPickeado,
					"DIFERENCIA_PLAN_ASIGN" => $diferenciaIP,
					"POR_PLAN_ASIGN" => $pordiffIP,
					"PORC_ASIGNADO" => $porcentajePickeado,
					"DEVUELTO" => $totalDevuelto,
					"PORC_DEVUELTO" => $porcentajeDevuelto
				);
			}

		}

		$data[] = array(
			"TIENDA" => "TOTAL",
			"INFORMADO" => $suminformado,
			"PLANIFICADO" => $sumcomprometido,
			"DIFERENCIA_PLAN_INFO" => $sumdiferencia,
			"POR_DIFERENCIA_PLAN_INFO" => $totpordiffPI,
			"POR_INFORMADO" => $totporInformado,
			"ASIGNADO" => $sumasignado,
			"DIFERENCIA_PLAN_ASIGN" => $sumrechazo,
			"POR_PLAN_ASIGN" => $totpordiffIP,
			"PORC_ASIGNADO" => $totporPickeado,
			"DEVUELTO" => $sumdevuelto,
			"PORC_DEVUELTO" => $totporDevuelto
		);

		return json_encode($data);
	}

	public function devolver($cud, $motivo, $tienda){

		$db2 = $this->load->database('PMMWMS', TRUE);

		$sql = "SELECT
					ARTICULO,
					CANTIDAD,
					ESCANEADO,
					PICKEADO,
					DEVUELTO
				FROM 
					SFS_PICKEODESPACHO
				WHERE
					CUD = '$cud'"; 

		$result = $db2->query($sql);

		if(sizeof($result->result()) > 0){
			foreach ($result->result() as $key) {
				$sku = $key->ARTICULO;
				$cantidad = $key->CANTIDAD;
				$escaneado = $key->ESCANEADO;
				$pickeado = $key->PICKEADO;
				$devuelto = $key->DEVUELTO;
			}

			$sql = "SELECT SKU_DESC FROM ITEM_MASTER WHERE SKU_BRCD = '$sku'";

			$result2 = $db2->query($sql);

			foreach ($result2->result() as $key) {
				$descripcion = $key->SKU_DESC;
			}

			if($pickeado == 'F'){

				return 2;
			}elseif ($devuelto == 'T') {
				return 3;
			}else{
				$sql = "UPDATE 
							SFS_PICKEODESPACHO
						SET
							DEVUELTO = 'T',
							FECHA_DEVUELTO = SYSDATE,
							CAMPO1 = '$motivo',
							CAMPO2 = '$tienda'
						WHERE
							CUD = '$cud'";

				$result3 = $db2->query($sql);

				$data[] = array(
					"ARTICULO" => $sku,
					"DESCRIPCION" => $descripcion,
					"CANTIDAD" => $cantidad,
				);

				return $data;
			}
		}
		else{
			return 1;
		}	
	}

	public function buscarCud($cud){

		$db2 = $this->load->database('BTPROD', TRUE);

		$db3 = $this->load->database('PMMWMS', TRUE);

		$sql = "SELECT 
					BC.DESCRIPCION_COMUNA
				FROM 
					BIGT_DESPACHOS BD,
					BIGT_COMUNA BC
				WHERE 
					BD.CUD = '$cud'
					AND BD.COD_REGION = BC.COD_REGION
					AND BD.COD_COMUNA = BC.COD_COMUNA";

		$result = $db2->query($sql);
		foreach ($result->result() as $key) {
			$comuna = $key->DESCRIPCION_COMUNA;
		}

		$sql = "SELECT INFORMADO FROM SFS_PICKEODESPACHO WHERE CUD = '$cud'";

		$result = $db3->query($sql);
		foreach ($result->result() as $key) {
			$informado = $key->INFORMADO;
		}

		$data[] = array(
			"DESCRIPCION_COMUNA" => $comuna,
			"INFORMADO" => $informado,
		);

		return $data;
	}

	public function guardarInfoDespacho($cud, $id, $chofer, $empresa, $patente, $fecha, $tienda){

		$db2 = $this->load->database('PMMWMS', TRUE);

		$sql = "UPDATE
					SFS_PICKEODESPACHO
				SET
					ID = '$id',
					CHOFER = '$chofer',
					NOMBRE_TRANSPORTISTA = '$empresa',
					PATENTE = '$patente',
					INFORMADO = 'T',
					FECHA_CARGA = SYSDATE,
					PICKEADO = 'T',
					FECHA_PICKEADO = SYSDATE,
					FECHA_DESPACHO = TO_DATE('$fecha', 'DD/MM/YYYY'),
					TIENDA = '$tienda'
				WHERE
					CUD = '$cud'";

		$result = $db2->query($sql);

		if($result){
			return 1;
		}
		else{
			return 2;
		}
	}

	public function detalleDevueltos($fecha, $tienda){

		$data = array();

		$db2 = $this->load->database('BTPROD', TRUE);

		$db3 = $this->load->database('PMMWMS', TRUE);

		
		$sql = "SELECT
				    CUD,
				    ARTICULO,
				    FECHA_DESPACHO,
				    TIENDA,
				   	FECHA_DEVUELTO,
				   	CAMPO2 TIENDA_DEVUELTO,
				   	CAMPO1 MOTIVO
				FROM
				    SFS_PICKEODESPACHO
				WHERE
				    TRUNC(FECHA_DEVUELTO) = '$fecha'
				    AND TIENDA = '$tienda'
				    AND DEVUELTO = 'T'";

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
				'FECHA_DESPACHO' => $key->FECHA_DESPACHO,
				'TIENDA_ORIGEN' => $key->TIENDA,
				'FECHA_DEVUELTO' => $key->FECHA_DEVUELTO,
				'TIENDA_DEVUELTO' => $key->TIENDA_DEVUELTO,
				'MOTIVO' => $key->MOTIVO
			);
		}

		return $data;
		
	}
	public function contarDevueltos($tienda){

		$db3 = $this->load->database('PMMWMS', TRUE);

		$sql = "SELECT COUNT(*) TOTAL FROM SFS_PICKEODESPACHO WHERE TIENDA = '$tienda' AND FECHA_DEVUELTO = SYSDATE";

		$result = $db3->query($sql);

		foreach ($result->result() as $key) {
			$total = $key->TOTAL;
		}

		return $total;
	}

	public function datosTransporte($id, $tienda, $fecha){

		$db3 = $this->load->database('PMMWMS', TRUE);

		$sql = "SELECT
				    CHOFER,
				    NOMBRE_TRANSPORTISTA,
				    PATENTE
				FROM 
				    SFS_PICKEODESPACHO
				WHERE
				    ID = '$id'
				    AND TRUNC(FECHA_DESPACHO) = '$fecha'
				    AND TIENDA = '$tienda'
				GROUP BY
				    CHOFER,
				    NOMBRE_TRANSPORTISTA,
				    PATENTE";

		$result = $db3->query($sql);
		if(sizeof($result->result())){
			return $result->result();
		}
	}

	public function getOPL($tienda, $fecha){

		$db3 = $this->load->database('PMMWMS', TRUE);

		if($tienda == 10034 || $tienda == 10018 || $tienda == 10057 || $tienda == 10045 || $tienda == 10012){
			$sql = "SELECT
						NOMBRE_TRANSPORTISTA
					FROM 
						SFS_PICKEODESPACHO
					WHERE
						TRUNC(FECHA_PLANIFICACION) = '$fecha'
						AND TIENDA = '$tienda'
						AND NOMBRE_TRANSPORTISTA IS NOT NULL
					GROUP BY
						NOMBRE_TRANSPORTISTA";

			$result = $db3->query($sql);
			if($result || $result != null){
				$resultado = json_encode($result->result());
				$db3->close();
				return $resultado;
			}
			else{
				return $db3->error();
			}
		}else{
			$sql = "SELECT
						NOMBRE_TRANSPORTISTA
					FROM 
						SFS_PICKEODESPACHO
					WHERE
						TRUNC(FECHA_DESPACHO) = '$fecha'
						AND TIENDA = '$tienda'
						AND NOMBRE_TRANSPORTISTA IS NOT NULL
					GROUP BY
						NOMBRE_TRANSPORTISTA";

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
	}
}