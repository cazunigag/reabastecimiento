<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class login extends CI_Controller{

	public function __construct(){

		parent::__construct();
		$this->load->library('session');
		$this->load->model("Login/Login_model");
	}
	public function index()
	{
		$this->session->sess_destroy();
		$this->load->view('login');
	}
	
	public function auth(){
		$user = str_replace("-", "", $this->input->post('user'));
		$pass = hash("sha1", $this->input->post('pass'));

		$data = $this->Login_model->buscarUser($user);

		if(sizeof($data) > 0){
			foreach ($data as $key) {
				if($key->PSWD == $pass){
					$modulos = $data = $this->Login_model->modulos($user);
					$newuser = array(
						"nombre" => $key->USER_NAME,
						"modulos" => $modulos
					);
					$this->session->set_userdata($newuser);
					echo 0;
				}else{
					echo 2;
				}
			}
		}else{
			echo 1;
		}
	}
	public function home(){
		if($this->session->has_userdata('nombre')){
			$this->load->view('home');
        }else{
            redirect('', 'refresh');
        }
	}
	public function logOut(){
		$this->session->sess_destroy();
		redirect('', 'refresh');
	}
}