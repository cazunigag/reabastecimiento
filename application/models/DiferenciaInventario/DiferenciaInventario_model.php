<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DiferenciaInventario_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database();
		
	}

	public function read(){
		$fecha = "";
		$sql = "SELECT 
					TO_CHAR(FECHA, 'DD/MM/YYYY') FECHA, 
					MAYOR_PMM, 
					MAYOR_WMS
				FROM PNL_EXISTENCIAS";

		$result = $this->db->query($sql);

		foreach ($result->result() as $key) {
			$fecha = date("Y-m-d",strtotime(str_replace("/", "-", $key->FECHA)));

			$datos[] = array(
				'FECHA' => $fecha, 
				'MAYOR_PMM' => $key->MAYOR_PMM,
				'MAYOR_WMS' => $key->MAYOR_WMS);
		}

		return json_encode($datos);
	}

	public function detalleDiffPMM($fecha){
		$db2 = $this->load->database('PMMQA', TRUE);
		$sql = "SELECT TRUNC(A.PHY_PROC_DATE) FECHA, PRD.PRD_LVL_NUMBER, COUNT(*) MAYOR_PMM 
				  FROM RPYPIHEE A, PHYPISEE PIS, PRDMSTEE PRD
				WHERE TRUNC(A.PHY_PROC_DATE) = TO_DATE('$fecha', 'DD/MM/YYYY') AND
				       A.PHY_CTRL_NUM = PIS.PHY_CTRL_NUM AND
				       PIS.PRD_LVL_CHILD = PRD.PRD_LVL_CHILD AND
				       (ABS(PIS.ON_HAND_QTY) > 0 OR ABS(PIS.PHY_TOT_CNT) > 0) AND
				       (PIS.ON_HAND_QTY > PIS.PHY_TOT_CNT)
				GROUP BY TRUNC(A.PHY_PROC_DATE), PRD.PRD_LVL_NUMBER";

		$result = $db2->query($sql);
		if($result || $result != null){
			$data = json_encode($result->result());
			$db2->close();
			return $data;
		}
		else{
			return $db2->error();
		}
	}

	public function detalleDiffWMS($fecha){
		$db2 = $this->load->database('PMMQA', TRUE);
		$sql = "SELECT TRUNC(A.PHY_PROC_DATE) FECHA, PRD.PRD_LVL_NUMBER, COUNT(*) MAYOR_WMS
				  FROM RPYPIHEE A, PHYPISEE PIS, PRDMSTEE PRD
				WHERE TRUNC(A.PHY_PROC_DATE) = TO_DATE('$fecha', 'DD/MM/YYYY') AND 
				       A.PHY_CTRL_NUM = PIS.PHY_CTRL_NUM AND
				       PIS.PRD_LVL_CHILD = PRD.PRD_LVL_CHILD AND
				       (ABS(PIS.ON_HAND_QTY) > 0 OR ABS(PIS.PHY_TOT_CNT) > 0) AND
				       (PIS.ON_HAND_QTY < PIS.PHY_TOT_CNT)
				GROUP BY TRUNC(A.PHY_PROC_DATE), PRD.PRD_LVL_NUMBER";

		$result = $db2->query($sql);
		if($result || $result != null){
			$data = json_encode($result->result());
			$db2->close();
			return $data;
		}
		else{
			return $db2->error();
		}
	}
}