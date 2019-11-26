<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Departamentos_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database();
		
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
					IM.STD_CASE_QTY MODA,
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
		$sql = "SELECT
					A.LOCN_ID,
					A.REPL_LOCN_BRCD,
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
					2";

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

		foreach ($data as $key) {
			
			$sql = "SELECT * FROM   
					    (SELECT
					        SUBSTR(A.REPL_LOCN_BRCD,1,4)||'-'||SUBSTR(A.REPL_LOCN_BRCD,5,2)||'-'||SUBSTR(A.REPL_LOCN_BRCD,7,2) AS LOCN,
					        B.SKU_ID,
					        ($key->MODA*(SELECT MINIMO FROM RDX_SUBLINEA_MAXMIN WHERE SUBLINEA = B.SPL_INSTR_1)) AS MINIMO,
					        ($key->MODA*(SELECT MAXIMO FROM RDX_SUBLINEA_MAXMIN WHERE SUBLINEA = B.SPL_INSTR_1)) AS MAXIMO
					    FROM
					        PICK_LOCN_HDR A,
					        ITEM_MASTER B
					    WHERE
					        --ROWNUM = 1
					         B.SKU_ID = '$key->SKU_ID'
					        AND SUBSTR(A.REPL_LOCN_BRCD,1,4) = (SELECT AISLE FROM RDX_PUTWY_LOCN WHERE ROWNUM = 1 AND PUTWY_TYPE = '$key->PUTWY_TYPE')
					        AND A.MAX_NBR_OF_SKU > (SELECT COUNT(*) FROM PICK_LOCN_DTL WHERE LOCN_ID = A.LOCN_ID)
					    ORDER BY
					        A.REPL_LOCN_BRCD)
					WHERE ROWNUM = 1";
			$result = $this->db->query($sql);
			if($result || $result != null){
				foreach ($result->result() as $key2) {
					$datos[] = array(
                        'DSP_LOCN' =>  $key2->LOCN,
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