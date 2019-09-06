<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Valida_login extends CI_Controller{

	public function index(){
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required',
                array('required' => 'You must provide a %s.'));

		if($this->form_validation->run() == FALSE){
			$this->load->view('login');
		}else
		{
			$this->load->model('login_model');
			$resp = $this->login_model->login();
			if($resp == 1)
			{
				$this->load->view('home');
			}

			
		}
	}
	public function home(){
		$this->load->view('home');
	}
}