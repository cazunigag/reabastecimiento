<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database();
		
	}

	public function buscarUser($user){
		$sql = "SELECT * FROM RDX_USER_MASTER WHERE USER_ID = '$user'";

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

	public function modulos($user){
		$sql = "SELECT
					B.MENU_ID, 
					B.MENU_NAME
		 		FROM 
		 			RDX_USER_MENU A, 
		 			RDX_MENU_CATALOG B
		 		WHERE 
		 			A.USER_ID = '$user'
		 			AND B.MENU_ID = A.MENU_ID";

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
}