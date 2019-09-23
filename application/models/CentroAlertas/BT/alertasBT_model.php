<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class alertasBT_model extends CI_Model{

	public function __construct(){
		$this->load->database('BTPROD');
	}

	public function sinProcesarSDI(){
		$sql = "SELECT COUNT(1) AS CANTIDAD FROM LGBTCK_SDI_REC_DBO S WHERE S.BTSRC_FCH_PCS_REG IS NULL";

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