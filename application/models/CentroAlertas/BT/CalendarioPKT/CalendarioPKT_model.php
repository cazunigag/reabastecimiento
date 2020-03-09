<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CalendarioPKT_model extends CI_Model{

	public function __construct(){
		$this->load->database();
	}

	public function calendar(){
		$sql = "SELECT 
				    TO_CHAR(C.ADVT_DATE, 'YYYY/MM/DD HH24:MI:SS') ADVT_DATE, 
				    COUNT(*) TOTAL
				FROM
				    (SELECT 
				        A.ADVT_DATE,
				        B.STAT_CODE
				    FROM 
				        PKT_HDR A,
				        PKT_HDR_INTRNL B
				    WHERE 
				        A.ADVT_DATE IS NOT NULL 
				        AND SUBSTR(A.PKT_CTRL_NBR,1,3) = 'BTC'
				        AND A.PKT_CTRL_NBR = B.PKT_CTRL_NBR
				        AND B.STAT_CODE < 90)C
				GROUP BY C.ADVT_DATE
				HAVING COUNT(*) > 0
				ORDER BY C.ADVT_DATE";

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

	public function EstadosPKT($fecha){
		$sql = "SELECT 
					PHI.STAT_CODE, 
					SC.CODE_DESC AS DESC_ESTADO, 
					COUNT(*) AS TOTAL 
				FROM 
					PKT_HDR_INTRNL PHI, 
					SYS_CODE SC,
					PKT_HDR PH
				WHERE 
					SUBSTR(PHI.PKT_CTRL_NBR,1,3) = 'BTC' 
					AND SC.REC_TYPE = 'S'
					AND SC.CODE_TYPE = '501' 
					AND TO_CHAR(PHI.STAT_CODE) = SC.CODE_ID
					AND PHI.PKT_CTRL_NBR = PH.PKT_CTRL_NBR
					AND TRUNC(PH.ADVT_DATE) = TRUNC(TO_DATE('$fecha', 'YYYY/MM/DD'))
					AND PHI.STAT_CODE < 90
				GROUP BY PHI.STAT_CODE, SC.CODE_DESC 
				ORDER BY PHI.STAT_CODE";

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

	public function DetallePKT($fecha, $codigo){
		$sql = "SELECT 
				    A.PKT_CTRL_NBR,
				    A.RTE_ID
				FROM 
				    PKT_HDR A,
				    PKT_HDR_INTRNL B
				WHERE 
				    SUBSTR(A.PKT_CTRL_NBR,1,3) = 'BTC'
				    AND A.PKT_CTRL_NBR = B.PKT_CTRL_NBR
				    AND B.STAT_CODE = $codigo
				    AND TRUNC(A.ADVT_DATE) = TRUNC(TO_DATE('$fecha', 'YYYY/MM/DD'))";

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
}