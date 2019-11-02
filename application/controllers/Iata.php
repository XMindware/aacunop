<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 * 
 */
class Iata extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Iata_model');
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
	}
	
	public function LoadIataCodes()
	{
		//$startdate = $this->input->post("sdate");
		
		$data = $this->Iata_model->LoadPositionId($idempresa,$code,$startdate);
		print_r(json_encode($data));
		return $data;
		
	}
	
	public function DeletePositionId()
	{
		$idempresa = $this->input->post("idempresa");
		$code = $this->input->post("code");
		$startdate = $this->input->post("sdate");
		
		$data = $this->Posiciones_model->DeletePositionId($idempresa,$code,$startdate);
		print_r(json_encode($data));
		return $data;
		
	}
	
	/*
		 *	Encode array into JSON
		*/
		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
	
	
	public function response($data,$status){
		$this->_code = ($status)?$status:200;
		echo $data;
		exit;
	}
}

