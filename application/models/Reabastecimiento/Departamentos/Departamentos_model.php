<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Departamentos_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database("PMMWMS");
		
	}
	public function listDepartamentos(){
		$sql = "SELECT CODE_ID, CODE_DESC FROM SYS_CODE WHERE REC_TYPE = 'B' AND CODE_TYPE = '752' AND CODE_ID NOT IN ('*', '0')";

		$result = $this->db->query($sql);
		if($result || $result != null){
			$data = $result->result();
			$this->db->close();
			return $data;
		}
		else{
			return $this->db->error();
		}
	}
	public function selectDepto($depto){
		//$prod = $this->load->database("prodWMS", TRUE);
		$sql = "SELECT
					IM.SKU_ID,
					IM.MERCH_TYPE,
					IWM.PUTWY_TYPE,
					IM.SPL_INSTR_1 SUBLINEA,
					IM.STD_PACK_QTY MODA,
					NVL(RESERVA.TOTAL_RESERVA, 0) TOT_RESERVA,
					NVL(ACTIVO.TOTAL_ACTIVO, 0) TOT_ACTIVO
				FROM
					ITEM_MASTER IM,
					ITEM_WHSE_MASTER IWM,
					(SELECT
						CD.SKU_ID,
						LH.PULL_ZONE,
						SUM(CD.ACTL_QTY) TOTAL_RESERVA
					 FROM 
					 	CASE_HDR CH,
					 	CASE_DTL CD,
					 	LOCN_HDR LH
					 WHERE 
					 	CH.WHSE = '095'
					 	AND CH.STAT_CODE < 95
					 	AND CH.CASE_NBR = CD.CASE_NBR
					 	AND CH.LOCN_ID = LH.LOCN_ID
					 GROUP BY
					 	CD.SKU_ID,
					 	LH.PULL_ZONE) RESERVA,
					(SELECT
						PLD.SKU_ID,
						SUM(PLD.ACTL_INVN_QTY) TOTAL_ACTIVO
					 FROM
					 	PICK_LOCN_DTL PLD
					 GROUP BY
					 	PLD.SKU_ID) ACTIVO
				WHERE
					IM.MERCH_TYPE = '$depto'
					AND IWM.WHSE = '095'
					AND IM.SKU_ID = IWM.SKU_ID
					AND IM.SKU_ID = RESERVA.SKU_ID(+)
					AND RESERVA.PULL_ZONE = 'PLT'
					AND NVL(RESERVA.TOTAL_RESERVA, 0) > 0
					AND NVL(ACTIVO.TOTAL_ACTIVO, 0) = 0
					AND IM.SKU_ID = ACTIVO.SKU_ID(+)";

		$result = $this->db->query($sql);
		if($result || $result != null){
			$data = json_encode($result->result());
			$this->db->close();
			return $data;
		}
		else{
			return $this->db->error();
		}
	}
	public function pasillosPutwy($data){
		$putwys = "";
		foreach ($data as $key) {
			if(next($data) == false){
				$putwys = $putwys.$key->PUTWY_TYPE;
			}else{
				$putwys = $putwys.$key->PUTWY_TYPE."','";
			}
		}

		$sql = "SELECT 
					A.AISLE, 
					A.LOCN_CLASS, 
					A.PUTWY_TYPE
				FROM 
					RDX_PUTWY_LOCN A
				WHERE
					A.PUTWY_TYPE in ('$putwys')
				ORDER BY
					A.AISLE";

		$result = $this->db->query($sql);
		if($result || $result != null){
			$data = json_encode($result->result());
			$this->db->close();
			return $data;
		}
		else{
			return $this->db->error();
		}
	}
	public function availableLocn($pasillo){
		$sql = "SELECT * FROM(
					SELECT
						A.LOCN_ID,
						SUBSTR(A.REPL_LOCN_BRCD,1,4)||'-'||SUBSTR(A.REPL_LOCN_BRCD,5,2)||'-'||SUBSTR(A.REPL_LOCN_BRCD,7,2) AS REPL_LOCN_BRCD,
						A.MAX_NBR_OF_SKU,
						COUNT(B.SKU_ID) TOT_ACT_SKU
					FROM
						PICK_LOCN_HDR A,
						PICK_LOCN_DTL B
					WHERE
						SUBSTR(A.REPL_LOCN_BRCD,1,4) = '$pasillo'
						AND A.LOCN_ID = B.LOCN_ID(+)
					GROUP BY
						A.LOCN_ID,
						A.REPL_LOCN_BRCD,
						A.MAX_NBR_OF_SKU
					ORDER BY
						2
				) X 
				WHERE
					X.MAX_NBR_OF_SKU > X.TOT_ACT_SKU";

		$result = $this->db->query($sql);
		if($result || $result != null){
			$data = json_encode($result->result());
			$this->db->close();
			return $data;
		}
		else{
			return $this->db->error();
		}
	}
	public function configurar($data){

		$datos = array();
		$putwys = "";
		$count = 1;
		$overall = 0;
		$locn = "";

		foreach ($data as $key) {
			if(next($data) == false){
				$putwys = $putwys.$key->PUTWY_TYPE;
			}else{
				$putwys = $putwys.$key->PUTWY_TYPE."','";
			}

			$sql = "SELECT
					    B.SKU_ID,
					    (B.STD_PACK_QTY*(SELECT MINIMO FROM RDX_SUBLINEA_MAXMIN WHERE SUBLINEA = B.SPL_INSTR_1)) AS MINIMO,
					    (B.STD_PACK_QTY*(SELECT MAXIMO FROM RDX_SUBLINEA_MAXMIN WHERE SUBLINEA = B.SPL_INSTR_1)) AS MAXIMO
					FROM
					    ITEM_MASTER B
					WHERE
					    --ROWNUM = 1
					    B.SKU_ID = '$key->SKU_ID'";
			$result = $this->db->query($sql);

			$sql2 = "SELECT
					    A.LOCN_ID,
					    SUBSTR(A.REPL_LOCN_BRCD,1,4)||'-'||SUBSTR(A.REPL_LOCN_BRCD,5,2)||'-'||SUBSTR(A.REPL_LOCN_BRCD,7,2) AS LOCN,
					    A.MAX_NBR_OF_SKU - COUNT(B.SKU_ID) SKUS_RESTANTES
					FROM
					    PICK_LOCN_HDR A,
					    PICK_LOCN_DTL B
					WHERE
					    SUBSTR(A.REPL_LOCN_BRCD,1,4) IN (SELECT AISLE FROM RDX_PUTWY_LOCN WHERE PUTWY_TYPE = '$key->PUTWY_TYPE')
					    AND A.LOCN_ID = B.LOCN_ID(+)
					GROUP BY
					    A.LOCN_ID,
					    A.REPL_LOCN_BRCD,
					    A.MAX_NBR_OF_SKU
					HAVING
					    (A.MAX_NBR_OF_SKU - COUNT(B.SKU_ID)) > 0
					ORDER BY 
					    2";
			$result2 = $this->db->query($sql2);		
			if($result || $result != null){
				foreach ($result->result() as $key2) {

					foreach ($result2->result() as $key3) {
						if($locn == ""){
							if($count <= $key3->SKUS_RESTANTES){
								$locn = $key3->LOCN;
								$count ++;
								break;
							}
						}else{
							if($locn != $key3->LOCN){
								$count = 1;
								$locn = $key3->LOCN;
								foreach ($datos as $arr) {
									if($key3->LOCN == $arr['DSP_LOCN']){
										$count ++;
									}
								}
								if($count > 1 ){
									if($count <= $key3->SKUS_RESTANTES){
										$count ++;
										break;
									}
								}else{
									$count ++;
									break;
								}
							}else{
								if($count <= $key3->SKUS_RESTANTES){
									$count ++;
									break;
								}
							}
						}				
					}
					$datos[] = array(
                        'DSP_LOCN' =>  $locn,
                        'SKU_ID' =>  $key2->SKU_ID,
                        'MIN_INVN_QTY' =>  $key2->MINIMO,
                        'MAX_INVN_QTY' =>  $key2->MAXIMO,
                    );
				}
			}
			else{
				return $this->db->error();
			}		
		}
		return json_encode($datos);			
	}
}