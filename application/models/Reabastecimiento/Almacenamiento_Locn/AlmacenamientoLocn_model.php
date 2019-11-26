<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AlmacenamientoLocn_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database();
		
	}

	public function info(){
		$sql = "SELECT 
					A.AISLE, 
					A.LOCN_CLASS, 
					A.PUTWY_TYPE, 
					B.CODE_DESC 
				FROM 
					PMMWMS.RDX_PUTWY_LOCN A,
					SYS_CODE B
				WHERE
					B.REC_TYPE = 'B'
					AND B.CODE_TYPE = '667'
					AND B.CODE_ID = A.PUTWY_TYPE
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
}