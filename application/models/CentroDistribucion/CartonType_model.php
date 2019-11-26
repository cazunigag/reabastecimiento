<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CartonType_model extends CI_Model{

	public function __construct(){
		$this->load->database('PMMWMS');
		$db2 = $this->load->database('LECLWMPROD');

	}

	public function dataGrid1(){
		$sql = "SELECT 
					AREA||ZONE||AISLE AS PASILLO,
					CARTON_TYPE
				FROM
					PNL_AISLE_CLASSIFICATION";

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