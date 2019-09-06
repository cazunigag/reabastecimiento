<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Artlocacion_controller extends CI_Controller{

    

    public function index(){
        $this->load->model('artlocacion_model');
    }


    public function insertarArtLocacion(){
        $tempData = $this->input->post();
        $json = json_decode($tempData, true);

        
        $resp = $this->artlocacion_model->insertarArtLocacion($json);
        echo $resp;

    }

    public function readArtLocacion(){
        echo $this->artlocacion_model->readArtLocacion();
    }
}



 
 