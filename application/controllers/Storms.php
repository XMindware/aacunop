<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

error_reporting(E_ERROR | E_PARSE);

/**
 * 
 */
class Storms extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Storms_model');
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
	}
	
	public function UpdateUser()
	{

		$userid = $this->input->post("userid");
		$email = $this->input->post("email");
		$deviceid = $this->input->post("deviceid");
		$platform = $this->input->post("platform");
		$nombre = $this->input->post("nombre");
		$push = $this->input->post("push");

		//echo $userid;
		//return $nombre;
		
		$insert = $this->Storms_model->UpdateUser($userid,$email, $deviceid, $platform, $nombre, $push);
		
		$this->response($this->json($insert), 200);
		
	}

	public function DeleteRowId()
	{
		$idvuelo = $this->input->post("idvuelo");
		$origen = $this->input->post("origen");
		
		$data = $this->Vuelos_model->DeleteRowId($idvuelo,$origen);
		print_r(json_encode($data));
		return $data;
	}
	
	
	public function LoadVueloCode()
	{
		$idvuelo = $this->input->post("idvuelo");
		
		$data = $this->Vuelos_model->LoadVueloCode($idvuelo);
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

