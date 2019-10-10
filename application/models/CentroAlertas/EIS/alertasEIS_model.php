<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class alertasEIS_model extends CI_Model{

	public function __construct(){
		$this->load->database('EIS');
	}

	public function msgEIS(){
		$sql = "SELECT
					CLQ.ENDPOINT_ID,
					CE.NAME,
					CASE
						WHEN CLQ.STATUS = 2 THEN 'EN COLA'
						WHEN CLQ.STATUS = 5 THEN 'EXITOSO'
						WHEN CLQ.STATUS = 6 THEN 'FALLIDO'
						WHEN CLQ.STATUS = 10 THEN 'EN PROCESO'
					END ESTADO,
					COUNT(*) TOTAL_MSG
				FROM
					CL_ENDPOINT_QUEUE CLQ,
					CL_ENDPOINT CE
				WHERE
					TRUNC(CLQ.WHEN_QUEUED) = TRUNC(SYSDATE)
					AND CLQ.ENDPOINT_ID = CE.ENDPOINT_ID
					AND CLQ.STATUS <> 5
				GROUP BY
					CLQ.ENDPOINT_ID,
					CE.NAME,
					CLQ.STATUS";

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
	public function resumenEIS($endpoint){
		$sql = "SELECT
					CLQ.MSG_ID,
					TO_CHAR(CM.DATA) DATA
				FROM
					CL_ENDPOINT_QUEUE CLQ,
					CL_MESSAGE CM
				WHERE
					TRUNC(CLQ.WHEN_QUEUED) = TRUNC(SYSDATE)
					AND CLQ.ENDPOINT_ID = $endpoint
					AND CLQ.STATUS = 6
					AND CLQ.MSG_ID = CM.MSG_ID";

		
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