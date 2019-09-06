<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asignacionpedido_model extends CI_Model{

	public function __construct(){
		$this->load->database();


	}

	public function getAsignaciones(){
		$sql = "SELECT * FROM PMMWMS.RDX_ASIGNACIONPEDIDO";
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

	public function getSku(){
		$sql = "SELECT ARTICULO FROM PMMWMS.RDX_ASIGNACIONPEDIDO";
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

	public function actualizarSKUS($json){
		$sql = "CALL PMMWMS.rdx_asignacionpedido_pr('$json')";
		$result = $this->db->query($sql);
		if($result || $result != null){
			$this->db->close();
			return 0;
		}
		else{
			$this->db->close();
			return $this->db->error();
		}
	}
}