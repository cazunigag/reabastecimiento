<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estanteria_model extends CI_Model{

	public function __construct() 
     {
        $this->load->database('prodWMS');
        $DB2 = $this->load->database('PMMWMS', TRUE);
        $DB3 = $this->load->database('PMMPRODCONT', TRUE);
     }

	public function loadPasillosEstanteria($piso){
		$sql = "SELECT AREA||ZONE||AISLE AS PASILLOS, COLOUR, CLASSIFICATION FROM PNL_AISLE_CLASSIFICATION WHERE FLOOR = '$piso' 
				ORDER BY SEQUENCE_AISLE";
		$DB2 = $this->load->database('PMMWMS', TRUE);
		$result = $DB2->query($sql);
		if($result || $result != null){
			$pasillos = $result->result();
			$this->db->close();
			return $pasillos;
		}
		else{
			return $this->db->error();
		}

	}
	public function getClasificacionPasillo(){
		$sql = "SELECT TRIM(AREA||ZONE||AISLE) AS PASILLOS, AISLE , SUM(CASE WHEN PICK_DETRM_ZONE <> '0' THEN 1 ELSE 0 END) AS TOTAL FROM LOCN_HDR 
				WHERE AREA = 'K' AND ZONE = 'D'  
				AND AISLE <= '48'
				GROUP BY AREA||ZONE||AISLE, AISLE
				UNION ALL
				SELECT TRIM(AREA||ZONE||AISLE) AS PASILLOS , AISLE , SUM(CASE WHEN PICK_DETRM_ZONE <> '0' THEN 1 ELSE 0 END)  AS TOTAL FROM LOCN_HDR
				WHERE AREA = 'K' AND ZONE = 'A'
				AND AISLE <= '48'
				GROUP BY AREA||ZONE||AISLE, AISLE
				ORDER BY 2";
		$result = $this->db->query($sql);
		$DB2 = $this->load->database('PMMWMS', TRUE);
		$pass = '';
		$r = $result->result();
		foreach ($result->result() as $key) {
			if(next($r)!= null){ 
				$pass = $pass.$key->PASILLOS."','";
			}
			else{
				$pass = $pass.$key->PASILLOS;
			}
		}
		$sqlINF="SELECT TRIM(AREA||ZONE||AISLE) AS PASILLO , CLASSIFICATION, USER_ID FROM PNL_AISLE_CLASSIFICATION WHERE AREA||ZONE||AISLE IN ('$pass') ORDER BY 1";
		$result2 = $DB2->query($sqlINF);
		if($result || $result != null){
			foreach ($result2->result() as $key) {
				$info[$key->PASILLO] = array("CLASSIFICATION" => $key->CLASSIFICATION,
											 "COLOR" => $key->USER_ID);
			}
			$this->db->close();
			return $info;
		}
		else{
			return $this->db->error();
		}
	}
	public function loadLocacionesEstanteriaImpar($pasillo){
		$area = substr($pasillo, 0, 1);
		$zone = substr($pasillo, 1, 1);
		$aisle = substr($pasillo, 2, 2);
		$fecha =date("d/m/y", strtotime('-6 month'));
				//OBTIENE LA INFORMACION DE LAS LOCACIONES POR PASILLO
		$sql = "SELECT DSP_LOCN, LOCN_ID, LVL, POSN, BAY FROM LOCN_HDR
    			WHERE AREA = '$area' AND ZONE = '$zone' AND AISLE = '$aisle' AND MOD(TO_NUMBER(BAY),2) = 1
    			AND PICK_DETRM_ZONE <> '0'
    			--AND MOD_DATE_TIME >= TO_DATE('$fecha', 'DD/MM/YY')
    			GROUP BY DSP_LOCN, LOCN_ID, LVL, POSN, BAY ORDER BY 1";
    			//OBTIENE LA DIMENSION DEL ESTANTE
    	$sqlMAX = "SELECT MAX(LVL) AS MAX,  MAX(POSN) AS LARGO FROM LOCN_HDR
				   WHERE AREA = '$area' AND ZONE = '$zone' AND AISLE = '$aisle' AND MOD(TO_NUMBER(BAY),2) = 1
				   --AND MOD_DATE_TIME >= TO_DATE('$fecha', 'DD/MM/YY')
				   AND PICK_DETRM_ZONE <> '0' ORDER BY 1";
		$sqlTOTMAT = "SELECT  COUNT(DISTINCT(BAY)) AS TOTMATRICES  FROM LOCN_HDR
    			WHERE AREA||ZONE||AISLE = '$pasillo' AND MOD(TO_NUMBER(BAY),2) = 1
    			--AND MOD_DATE_TIME >= TO_DATE('$fecha', 'DD/MM/YY')
    			AND PICK_DETRM_ZONE <> '0'";
		$sqlMAt = "SELECT  COUNT(BAY) AS DIMENSION, BAY FROM LOCN_HDR
				   WHERE AREA = '$area' AND ZONE = '$zone' AND AISLE = '$aisle' AND MOD(TO_NUMBER(BAY),2) = 1
				   --AND MOD_DATE_TIME >= TO_DATE('$fecha', 'DD/MM/YY')
				   AND PICK_DETRM_ZONE <> '0'
		           GROUP BY BAY ORDER BY BAY";		   	
		$result = $this->db->query($sql);
		$resultado = $result->result();
		$maxalt = 0;
		$maxalrg = 0;
		$matAct= 0;
		$matrices = 0;
		$matsize = $this->db->query($sqlMAt)->result();
		$this->db->close();
		foreach ($this->db->query($sqlTOTMAT)->result() as $key) {
			$matrices = $key->TOTMATRICES;
		}
		foreach ($this->db->query($sqlMAX)->result() as $key) {
			$maxalt = $key->MAX;
			$maxalrg = $key->LARGO;
		}
		$datos =  array();	
		$count = 0;
		
		$countot = 1;
		$saldo = $result->num_rows();
		if($saldo > 0){
			foreach ($result->result() as $key) {
				
				foreach ($matsize as $key2) {
					if($key2->BAY == $key->BAY){
						if($key2->DIMENSION != $matAct){
							$matAct = $key2->DIMENSION;
							break;
						}
					}
				}
				$saldo--;
				if($pasillo == 'KC01'){
					${"locaciones". $key->BAY}[$maxalt - $key->LVL][$key->POSN] = array(
								"BRCD" => $key->DSP_LOCN,
								"LOCNID" => $key->LOCN_ID
					);
				}else{
					${"locaciones". $key->BAY}[$maxalt - $key->LVL][$key->POSN-1] = array(
								"BRCD" => $key->DSP_LOCN,
								"LOCNID" => $key->LOCN_ID
					);
				}
			  $count ++;
			  if($count != 0){
			  	if($countot <= $matrices){
				  if($count ==  $matAct){	
				 	array_push($datos, ${"locaciones". $key->BAY});
				 	$count = 0;
				 	$countot++;
				 	
				  }
				}else
				{
					if($saldo == 0){
						array_push($datos, ${"locaciones". $key->BAY});
					}
				}
			  }
			}
		}
		else{
			return null;
		}
		if($datos || $datos != null){
			$this->db->close();
			return $datos;
		}
		else{
			return $this->db->error();
		} 
	}

	public function loadLocacionesEstanteriaPar($pasillo){
		$area = substr($pasillo, 0, 1);
		$zone = substr($pasillo, 1, 1);
		$aisle = substr($pasillo, 2, 2);
		$fecha =date("d/m/y", strtotime('-6 month'));
				//OBTIENE LA INFORMACION DE LAS LOCACIONES POR PASILLO
		$sql = "SELECT DSP_LOCN, LOCN_ID, LVL, POSN, BAY FROM LOCN_HDR
				WHERE AREA = '$area' AND ZONE = '$zone' AND AISLE = '$aisle' AND MOD(TO_NUMBER(BAY),2) = 0
				AND PICK_DETRM_ZONE <> '0'
				--AND MOD_DATE_TIME >= TO_DATE('$fecha', 'DD/MM/YY')
				GROUP BY DSP_LOCN, LOCN_ID, LVL, POSN, BAY ORDER BY 1";
    			//OBTIENE LA DIMENSION DEL ESTANTE
    	$sqlMAX = "SELECT MAX(LVL) AS MAX,  MAX(POSN) AS LARGO FROM LOCN_HDR
				   WHERE AREA = '$area' AND ZONE = '$zone' AND AISLE = '$aisle' AND MOD(TO_NUMBER(BAY),2) = 0
				   --AND MOD_DATE_TIME >= TO_DATE('$fecha', 'DD/MM/YY')
				   AND PICK_DETRM_ZONE <> '0' ORDER BY 1";
		$sqlTOTMAT = "SELECT  COUNT(DISTINCT(BAY)) AS TOTMATRICES  FROM LOCN_HDR
    			WHERE AREA = '$area' AND ZONE = '$zone' AND AISLE = '$aisle' AND MOD(TO_NUMBER(BAY),2) = 0
    			--AND MOD_DATE_TIME >= TO_DATE('$fecha', 'DD/MM/YY')
    			AND PICK_DETRM_ZONE <> '0'";
		$sqlMAt = "SELECT  COUNT(BAY) AS DIMENSION, BAY FROM LOCN_HDR
				   WHERE AREA = '$area' AND ZONE = '$zone' AND AISLE = '$aisle' AND MOD(TO_NUMBER(BAY),2) = 0
				   --AND MOD_DATE_TIME >= TO_DATE('$fecha', 'DD/MM/YY')
				   AND PICK_DETRM_ZONE <> '0'
		           GROUP BY BAY ORDER BY BAY";		   	
		$result = $this->db->query($sql);
		$resultado = $result->result();
		$maxalt = 0;
		$maxalrg = 0;
		$matAct= 0;
		$matrices = 0;
		$matsize = $this->db->query($sqlMAt)->result();
		$this->db->close();
		foreach ($this->db->query($sqlTOTMAT)->result() as $key) {
			$matrices = $key->TOTMATRICES;
		}
		foreach ($this->db->query($sqlMAX)->result() as $key) {
			$maxalt = $key->MAX;
			$maxalrg = $key->LARGO;
		}

		$datos =  array();	
		$count = 0;
		
		$countot = 1;
		$saldo = $result->num_rows();
		if($saldo > 0){
			foreach ($result->result() as $key) {
				
				foreach ($matsize as $key2) {
					if($key2->BAY == $key->BAY){
						if($key2->DIMENSION != $matAct){
							$matAct = $key2->DIMENSION;
							break;
						}
					}
				}
				$saldo--;
				if($pasillo == 'KC01'){
					${"locaciones". $key->BAY}[$maxalt - $key->LVL][$key->POSN] = array(
								"BRCD" => $key->DSP_LOCN,
								"LOCNID" => $key->LOCN_ID
					);
				}else{
					${"locaciones". $key->BAY}[$maxalt - $key->LVL][$key->POSN-1] = array(
								"BRCD" => $key->DSP_LOCN,
								"LOCNID" => $key->LOCN_ID
					);
				}
			  $count ++;
			  if($count != 0){
			  	if($countot <= $matrices){
				  if($count ==  $matAct){	
				 	array_push($datos, ${"locaciones". $key->BAY});
				 	$count = 0;
				 	$countot++;
				 	
				  }
				}else
				{
					if($saldo == 0){
						array_push($datos, ${"locaciones". $key->BAY});
					}
				}
			  }
			}
		}
		else{
			return null;
		}
		
		if($datos || $datos != null){
			$this->db->close();
			return $datos;
		}
		else{
			return $this->db->error();
		} 
	}

	public function getDimensiones($pasillo){
		$area = substr($pasillo, 0, 1);
		$zone = substr($pasillo, 1, 1);
		$aisle = substr($pasillo, 2, 2);
		$sql = "SELECT COUNT(DISTINCT LVL) AS ALTO, COUNT(DISTINCT POSN) AS LARGO FROM LOCN_HDR
				WHERE AREA = '$area' AND ZONE = '$zone' AND AISLE = '$aisle' AND PICK_DETRM_ZONE <> '0' ORDER BY 1";	
		$result = $this->db->query($sql);
		if($result || $result != null){
			$dimensiones = $result->result();
			$this->db->close();
			return $dimensiones;
		}
		else{
			return $this->db->error();
		}		   
	}

	public function totalLocacionesPasillo($pasillo){
		$area = substr($pasillo, 0, 1);
		$zone = substr($pasillo, 1, 1);
		$aisle = substr($pasillo, 2, 2);
		$sql = "SELECT  * FROM LOCN_HDR
				WHERE AREA = '$area' AND ZONE = '$zone' AND AISLE = '$aisle' AND PICK_DETRM_ZONE <> '0' ORDER BY 1";	
		return $this->db->query($sql)->num_rows();	
	}

	public function getDetalleLocn($idLocn){ 
		$sql = "SELECT PLD.SKU_ID, IM.SKU_DESC, IM.MERCH_TYPE, IWM.PUTWY_TYPE, IM.CARTON_TYPE, PLD.ACTL_INVN_QTY, 
				PLD.TO_BE_PIKD_QTY, PLD.TO_BE_FILLD_QTY, PLD.MOD_DATE_TIME
				FROM PICK_LOCN_DTL PLD, ITEM_MASTER IM, ITEM_WHSE_MASTER IWM
				WHERE PLD.LOCN_ID = '$idLocn' AND PLD.SKU_ID = IM.SKU_ID AND IM.SKU_ID = IWM.SKU_ID";
		$result = $this->db->query($sql);
		if($result || $result != null){
			$json = json_encode($result->result());
			$this->db->close();
			return $json;
		}
		else{
			return $this->db->error();
		}		
	}

	public function getHeader($idLocn){
		$sql = "SELECT LH.LOCN_ID||' '||LH.DSP_LOCN||' '||LH.LOCN_CLASS||' '||LH.PICK_DETRM_ZONE||' '||LH.WORK_GRP||' '||
				LH.WORK_AREA||' '||LH.PUTWY_ZONE||' '||LH.LAST_CNT_DATE_TIME AS HEADER
			    FROM LOCN_HDR LH
			    WHERE LH.LOCN_ID='$idLocn'";
		$result = $this->db->query($sql);
		if($result || $result != null){
			$json = json_encode($result->result());
			$this->db->close();
			return $json;
		}
		else{
			return $this->db->error();
		}	
	}

	public function getBayHeaderPar($pasillo){
		$area = substr($pasillo, 0, 1);
		$zone = substr($pasillo, 1, 1);
		$aisle = substr($pasillo, 2, 2);
		$sql = "SELECT DISTINCT AREA||ZONE||AISLE||'-'||BAY AS BAYHEADER FROM LOCN_HDR 
		WHERE AREA = '$area' AND ZONE = '$zone' AND AISLE = '$aisle' AND MOD(TO_NUMBER(BAY),2) = 0 ORDER BY 1";
		$result = $this->db->query($sql);
		if($result || $result != null){
			$header = $result->result();
			$this->db->close();
			return $header;
		}
		else{
			return $this->db->error();
		}
	}
	public function getBayHeaderImPar($pasillo){
		$area = substr($pasillo, 0, 1);
		$zone = substr($pasillo, 1, 1);
		$aisle = substr($pasillo, 2, 2);
		$sql = "SELECT DISTINCT AREA||ZONE||AISLE||'-'||BAY AS BAYHEADER FROM LOCN_HDR 
		WHERE AREA = '$area' AND ZONE = '$zone' AND AISLE = '$aisle' AND MOD(TO_NUMBER(BAY),2) = 1 ORDER BY 1";
		$result = $this->db->query($sql);
		if($result || $result != null){
			$header = $result->result();
			$this->db->close();
			return $header;
		}
		else{
			return $this->db->error();
		}
	}
	public function getEmptyLocn($pasillo){
		$area = substr($pasillo, 0, 1);
		$zone = substr($pasillo, 1, 1);
		$aisle = substr($pasillo, 2, 2);
		$sql = "SELECT LH.LOCN_ID, CASE WHEN SUM(PLD.ACTL_INVN_QTY) >0 THEN 'Verde' WHEN SUM(ACTL_INVN_QTY) IS NULL THEN 'Rojo'
				ELSE 'Naranjo' END AS COLOR FROM LOCN_HDR LH, PICK_LOCN_DTL PLD WHERE LH.AREA='$area' AND LH.ZONE= '$zone' 
				AND LH.AISLE = '$aisle' AND LH.PICK_DETRM_ZONE <> '0' AND LH.LOCN_ID = PLD.LOCN_ID(+) GROUP BY LH.LOCN_ID, LH.DSP_LOCN";
		$result = $this->db->query($sql);
		if($result || $result != null){
			$vacias = json_encode($result->result());
			$this->db->close();
			return $vacias;
		}
		else{
			return $this->db->error();
		}
	}
	public function getLocnSKU($sku){
		$sql = "SELECT LOCN_ID, '$sku' AS SKU_ID FROM PICK_LOCN_DTL WHERE SKU_ID = '$sku'";
		$result = $this->db->query($sql);
		if($result || $result != null){
			$resultado = json_encode($result->result());
			$this->db->close();
			return $resultado;
		}
		else{
			return $this->db->error();
		}
	}
	public function getPasilloSKU($sku){
		$sql = "SELECT DISTINCT A.AREA||A.ZONE||A.AISLE AS PASILLOS, A.LOCN_ID, '$sku' AS SKU_ID FROM LOCN_HDR A, PICK_LOCN_DTL B WHERE
				A.LOCN_ID IN (SELECT LOCN_ID FROM PICK_LOCN_DTL WHERE SKU_ID = '$sku')AND A.PICK_DETRM_ZONE <> '0' ORDER BY 1";
		$result = $this->db->query($sql);
		if($result || $result != null){
			$resultado = json_encode($result->result());
			$this->db->close();
			return $resultado;
		}
		else{
			return $this->db->error();
		}
	}

	public function getAntiguedadSku($pasillo, $dias){
		$area = substr($pasillo, 0, 1);
		$zone = substr($pasillo, 1, 1);
		$aisle = substr($pasillo, 2, 2);
		$fecha =date("d/m/y", strtotime('-'.$dias.' day'));
		$sql = "SELECT DISTINCT B.LOCN_ID, SUM(B.ACTL_INVN_QTY) FROM PICK_LOCN_DTL B, LOCN_HDR A
				WHERE B.MOD_DATE_TIME <= TO_DATE('$fecha', 'DD/MM/YY') 
				AND A.AREA = '$area' AND A.ZONE = '$zone' AND A.AISLE = '$aisle'
				AND PICK_DETRM_ZONE <> '0'
				AND A.LOCN_ID = B.LOCN_ID
                GROUP BY B.LOCN_ID
                HAVING SUM(B.ACTL_INVN_QTY)>0";
		$result = $this->db->query($sql);
		if($result || $result != null){
			$resultado = json_encode($result->result());
			$this->db->close();
			return $resultado;
		}
		else{
			return $this->db->error();
		}		
	}
	public function getAntiguedadContCiclico($pasillo, $dias){
		$area = substr($pasillo, 0, 1);
		$zone = substr($pasillo, 1, 1);
		$aisle = substr($pasillo, 2, 2);
		$fecha =date("d/m/y", strtotime('-'.$dias.' day'));
		$sql = "SELECT LOCN_ID FROM LOCN_HDR WHERE LAST_CNT_DATE_TIME <= TO_DATE('$fecha', 'DD/MM/YY') 
				AND AREA = '$area' AND ZONE = '$zone' AND AISLE = '$aisle' AND PICK_DETRM_ZONE <> '0'";
		$result = $this->db->query($sql);
		if($result || $result != null){
			$resultado = json_encode($result->result());
			$this->db->close();
			return $resultado;
		}
		else{
			return $this->db->error();
		}
	}
	public function getCartonTypePasillos($pasillos){
		
		$sql = "SELECT PISO_PASILLO.PASILLO, COUNT(*) CANTIDAD FROM(SELECT /*+ INDEX(IM PK_ITEM_MASTER) INDEX(LH LOCN_HDR_IND_1) */
				SUBSTR(LH.LOCN_BRCD, 1, 4) AS PASILLO, IM.CARTON_TYPE TIPO_CARTON FROM LOCN_HDR LH, PICK_LOCN_DTL PLD, ITEM_MASTER IM
				WHERE SUBSTR(LH.LOCN_BRCD, 1, 4) IN ('$pasillos') AND LH.WHSE = '095' AND LH.LOCN_ID = PLD.LOCN_ID AND PLD.SKU_ID = IM.SKU_ID
				GROUP BY IM.CARTON_TYPE, SUBSTR(LH.LOCN_BRCD, 1, 4))PISO_PASILLO GROUP BY PISO_PASILLO.PASILLO ORDER BY PASILLO";
		$result = $this->db->query($sql);
		if($result || $result != null){
			$resultado = json_encode($result->result());
			$this->db->close();
			return $resultado;
		}
		else{
			return $this->db->error();
		}		
	}
	public function getCartonTypes(){
		$DB3 = $this->load->database('PMMPRODCONT', TRUE);
		$sql ="SELECT 
					TRIM(C.ATR_CODE) AS CARTON_TYPE,
					C.ATR_CODE_DESC
				FROM
					BASACDEE C,
					BASAHREE D,
					BASATYEE E
				WHERE
					D.ATR_TYP_TECH_KEY = E.ATR_TYP_TECH_KEY
					AND C.ATR_HDR_TECH_KEY = D.ATR_HDR_TECH_KEY
					AND E.ATR_TYP_TECH_KEY = 10
					AND C.ATR_HDR_TECH_KEY = 232
				ORDER BY
					C.ATR_CODE";
		
		$result = $DB3->query($sql);
		if($result || $result != null){
			$resultado = json_encode($result->result());
			$this->db->close();
			return $resultado;
		}
		else{
			return $this->db->error();
		}
	}
	public function getCartonTypePasillo($pasillo){

		$area = substr($pasillo, 0, 1);
		$zone = substr($pasillo, 1, 1);
		$aisle = substr($pasillo, 2, 2);
		$sql="SELECT IM.CARTON_TYPE, COUNT(*) AS TOTAL FROM LOCN_HDR LH, PICK_LOCN_DTL PLD, ITEM_MASTER IM WHERE LH.AREA = '$area'
			  AND LH.ZONE = '$zone' AND LH.AISLE = '$aisle' AND LH.LOCN_ID = PLD.LOCN_ID AND PLD.SKU_ID = IM.SKU_ID
			  GROUP BY IM.CARTON_TYPE ORDER BY COUNT(*) DESC";
		$result = $this->db->query($sql);
		if($result || $result != null){
			$resultado = json_encode($result->result());
			$this->db->close();
			return $resultado;
		}
		else{
			return $this->db->error();
		}			  	

	}
	public function getUtilizacionPasillo($pasillo){

		$area = substr($pasillo, 0, 1);
		$zone = substr($pasillo, 1, 1);
		$aisle = substr($pasillo, 2, 2);
		$sql="SELECT SUM(CASE WHEN LOCACION.LOCN_ID_PLD IS NULL OR LOCACION.SUMA = 0 OR LOCACION.SUMA > 0 THEN 1 ELSE 0 END) TOTAL_LOC,
			  SUM(CASE WHEN LOCACION.LOCN_ID_PLD IS NULL THEN 1 ELSE 0 END) ROJO, SUM(CASE WHEN LOCACION.SUMA = 0 THEN 1 ELSE 0 END) NARANJO,
			  SUM(CASE WHEN LOCACION.SUMA > 0 THEN 1 ELSE 0 END) VERDE FROM (SELECT LH.DSP_LOCN LOC, LH.LOCN_ID ID, PLD.LOCN_ID LOCN_ID_PLD,
			  SUM(PLD.ACTL_INVN_QTY) SUMA FROM LOCN_HDR LH, PICK_LOCN_DTL PLD WHERE LH.AREA = '$area' AND LH.ZONE = '$zone'
			  AND LH.AISLE = '$aisle' AND PICK_DETRM_ZONE <> '0' AND LH.LOCN_ID = PLD.LOCN_ID(+) GROUP BY LH.DSP_LOCN, LH.LOCN_ID, PLD.LOCN_ID)
			  LOCACION";
	
		$result = $this->db->query($sql);
		if($result || $result != null){
			$resultado = json_encode($result->result());
			$this->db->close();
			return $resultado;
		}
		else{
			return $this->db->error();
		}		
	}
	public function getImagenSku($sku){
		$sql = "SELECT SKU_BRCD, MERCH_TYPE FROM ITEM_MASTER WHERE SKU_ID = '$sku'";
		$result = $this->db->query($sql);
		if($result || $result != null){
			$resultado = json_encode($result->result());
			$this->db->close();
			return $resultado;
		}
		else{
			return $this->db->error();
		}
	}
	public function getClasificaciones(){
		$sql="SELECT DISTINCT CLASSIFICATION FROM PNL_AISLE_CLASSIFICATION";
		$DB2 = $this->load->database('PMMWMS', TRUE);
		$result = $DB2->query($sql);
		if($result || $result != null){
			$resultado = $result->result();
			$this->db->close();
			return $resultado;
		}
		else{
			return $this->db->error();
		}
	}
	public function actualizarClassTabla($pasillos, $class){
		$sql="UPDATE PMMWMS.PNL_AISLE_CLASSIFICATION B SET B.CLASSIFICATION = '$class', B.COLOUR = (SELECT DISTINCT COLOUR FROM PNL_AISLE_CLASSIFICATION WHERE CLASSIFICATION = '$class') WHERE B.AREA||B.ZONE||B.AISLE IN ('$pasillos')";
		$DB2 = $this->load->database('PMMWMS', TRUE);
		$result = $DB2->query($sql);
		if($result || $result != null){
			$this->db->close();
			return $result;
		}
		else{
			return $this->db->error();
		}
	}
	public function actualizarCartonType($pasillo, $cartonType){
		$area = substr($pasillo, 0, 1);
		$zone = substr($pasillo, 1, 1);
		$aisle = substr($pasillo, 2, 2);
		$out = "";
		$DB2 = $this->load->database('LECLWMPROD', TRUE);
		$DB3 = $this->load->database('PMMWMS', TRUE);
		$sql = "UPDATE PNL_AISLE_CLASSIFICATION SET CARTON_TYPE = '$cartonType' WHERE AREA = '$area' AND ZONE = '$zone' AND AISLE = '$aisle'";
		$params = array(array('name' => ":p_Area", 'value' => $area, 'type' => SQLT_CHR, 'length' => 99 ),
						array('name' => ":p_Zone", 'value' => $zone, 'type' => SQLT_CHR, 'length' => 99 ),
						array('name' => ":p_Aisle", 'value' => $aisle, 'type' => SQLT_CHR, 'length' => 99 ),
						array('name' => ":p_Carton_Type", 'value' => $cartonType, 'type' => SQLT_CHR, 'length' => 99 ),
						array('name' => ":p_Status", 'value' => $out, 'type' => SQLT_CHR, 'length' => 99 ));

		$result = $DB2->stored_procedure("PMMWMS", "Rdx_Actualiza_Carton_Type", $params);
		$result2 = $DB3->query($sql);
		if($result || $result != null || $result2 || $result2 != null){
			$this->db->close();
			return $result;
		}
		else{
			$this->db->close();
			return $this->db->error();
		}
	}
	public function actualizarCartonTypeArticulo($sku, $cartonType){
		
		$DB2 = $this->load->database('LECLWMPROD', TRUE);
		$DB3 = $this->load->database('PMMWMS', TRUE);
		$sql = "UPDATE ITEM_MASTER SET CARTON_TYPE = '$cartonType' WHERE SKU_ID = '$sku'";
		$result2 = $DB2->query($sql);
		if($result || $result != null || $result2 || $result2 != null){
			$this->db->close();
			return $result;
		}
		else{
			$this->db->close();
			return $this->db->error();
		}
	}
	public function actualizarCartonTypeEstilo($estilo, $cartonType){
		
		$DB2 = $this->load->database('LECLWMPROD', TRUE);
		$DB3 = $this->load->database('PMMWMS', TRUE);
		$sql = "UPDATE ITEM_MASTER SET CARTON_TYPE = '$cartonType' WHERE EXP_LICN_SYMBOL = '$estilo'";
		$result2 = $DB2->query($sql);
		if($result || $result != null || $result2 || $result2 != null){
			$this->db->close();
			return $result;
		}
		else{
			$this->db->close();
			return $this->db->error();
		}
	}
	public function downloadExcelAntiguedadSku($pasillo, $dias){
		$area = substr($pasillo, 0, 1);
		$zone = substr($pasillo, 1, 1);
		$aisle = substr($pasillo, 2, 2);
		$fecha =date("d/m/y", strtotime('-'.$dias.' day'));
		$sql = "SELECT DISTINCT A.DSP_LOCN, B.SKU_ID, B.MOD_DATE_TIME, B.ACTL_INVN_QTY FROM PICK_LOCN_DTL B, LOCN_HDR A
				WHERE B.MOD_DATE_TIME <= TO_DATE('$fecha', 'DD/MM/YY') 
				AND A.AREA = '$area' AND A.ZONE = '$zone' AND A.AISLE = '$aisle'
				AND A.LOCN_ID = B.LOCN_ID AND PICK_DETRM_ZONE <> '0'
                ORDER BY 1";
        $result = $this->db->query($sql);        
        if($result || $result != null){
        	$response = $result->result();
			$this->db->close();
			return $response;
		}
		else{
			return $this->db->error();
		}                
	}
	public function downloadAntiguedadContCiclico($pasillo, $dias){
		$area = substr($pasillo, 0, 1);
		$zone = substr($pasillo, 1, 1);
		$aisle = substr($pasillo, 2, 2);
		$fecha =date("d/m/y", strtotime('-'.$dias.' day'));
		$sql = "SELECT DSP_LOCN, LAST_CNT_DATE_TIME, CYCLE_CNT_PENDING FROM LOCN_HDR
				WHERE MOD_DATE_TIME <= TO_DATE('$fecha', 'DD/MM/YY') 
				AND AREA = '$area' AND ZONE = '$zone' AND AISLE = '$aisle'
				AND PICK_DETRM_ZONE <> '0'
				ORDER BY 1";
        $result = $this->db->query($sql);        
        if($result || $result != null){
        	$response = $result->result();
			$this->db->close();
			return $response;
		}
		else{
			return $this->db->error();
		}        
	}
}

	