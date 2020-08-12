<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WorkArea_model extends CI_Model{

	public function __construct(){
		$this->load->database('PMMWMS');

	}

	public function listWorkArea($workgroup){

		$sql = "SELECT A.WORK_AREA WORK_AREA FROM WORK_AREA_MASTER A WHERE WORK_GRP = '$workgroup'";

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

	public function listWorkGroup(){

		$sql = "SELECT DISTINCT
                            WORK_GRP
                        FROM 
                            WORK_AREA_MASTER";

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

	public function gridWorkArea(){

		$sql = "SELECT DISTINCT
				    AREA||ZONE||AISLE PASILLO,
				    WORK_AREA,
				    WORK_GRP
				FROM
				    LOCN_HDR
				ORDER BY 1";

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

	public function actualizarWorkArea($pasillo, $workArea, $workGroup){
		
		$sql = "UPDATE
					LOCN_HDR
				SET
					WORK_AREA = '$workArea',
					WORK_GRP = '$workGroup'
				WHERE
					AREA||ZONE||AISLE = '$pasillo'";

		$result = $this->db->query($sql);
		if($result || $result != null){
			$this->db->close();
			return 1;
		}
		else{
			return $this->db->error();
		}

	}
}
