<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//error_reporting(E_ERROR | E_PARSE);

/**
 *   Control de incidencias para agentes
 *		XALFEIRAN Junio 2017
 */
class Extrapositions extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Extrapositions_model');
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
	}
	
	public function index()
	{
		if($this->session->userdata('isadmin') == FALSE)
		{
			redirect(base_url().'admin');
		}
		$this->LoadRows();
	}
	
	public function LoadRows()
	{
		$idempresa = $this->session->userdata('idempresa');

		$tdata['perfil'] = $this->session->userdata('perfil');
		
		$rowlist = $this->Extrapositions_model->LoadRows($idempresa);

		$data['rowlist'] = $rowlist;
		$data['usuario'] = $this->session->userdata('shortname');
		$data['idempresa'] = $idempresa;

		if($data)
		{
			
			$this->load->view('paginas/header',$tdata);
			$this->load->view('Extrapositions_view',$data);
			$this->load->view('paginas/footer');
		}
		else
		{
			redirect(base_url().'admin');
		}
	
	}

	public function PostPosition()
	{
		$idempresa = $this->input->post("idempresa");
		$code = $this->input->post("code");
		$uniqueid = $this->input->post("uniqueid");
		$description = $this->input->post("description");
			
		$insert = $this->Extrapositions_model->PostPosition($uniqueid,$idempresa,$code,$description);
		if($insert)
		{
			header('Content-type: application/json; charset=utf-8');
    		echo json_encode($insert);
		}
		else
		{
			$error = array('status' => "Failed", "msg" => "Error al actualizar la posicion");
			header('Content-type: application/json; charset=utf-8');
    		echo json_encode($error);
		}
	}
	
	
	public function LoadPositionId()
	{
		$idempresa = $this->input->post("idempresa");
		$uniqueid = $this->input->post("uniqueid");
		
		$data = $this->Extrapositions_model->LoadPositionUniqueId($idempresa,$uniqueid);
		header('Content-type: application/json; charset=utf-8');
    	echo json_encode($data);
		
	}



	public function DeletePositionId()
	{
		$idempresa = $this->input->post("idempresa");
		$idoficina = $this->input->post("idoficina");
		$uniqueid = $this->input->post("uniqueid");
		
		$data = $this->Extrapositions_model->DeletePositionId($idempresa,$idoficina,$uniqueid);
		header('Content-type: application/json; charset=utf-8');
    	echo json_encode($data);
		
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
		header('Content-type: application/json; charset=utf-8');
		$this->_code = ($status)?$status:200;
		echo $data;
		exit;
	}
}

