<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 * 
 */
class Puestos extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Admin_model');
		$this->load->model('Puestos_model');
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
	}
	
	public function index()
	{
		if($this->session->userdata('perfil') == FALSE || $this->session->userdata('isadmin')!='1')
		{
			redirect(base_url().'login');
		}
		
		$this->LoadPositions();
	}
	
	public function LoadPositions()
	{
		$empresa = $this->session->userdata('idempresa');
		
		// consulta las oficinas de la cuales es admin el usuario actual
		$adminoficina = $this->Admin_model->GetOficinasAdmin();
		
		$tdata['titulo'] = 'Agent List';
		$tdata['oficinas'] = $adminoficina;
		
		$tdata['perfil'] = $this->session->userdata('perfil');
		$data['positionlist'] = $this->Puestos_model->CompanyPositions($empresa);
		$data['idempresa'] = $empresa;
	
		if($data)
		{
			//$data = Array('userlist' => $userlist);
			//print_r($userlist);
			$this->load->view('paginas/header',$tdata);
			$this->load->view('Puestos_view',$data);
			$this->load->view('paginas/footer');
		}
	
	}
	
	public function PostPosition()
	{
		$idempresa = $this->input->post("idempresa");
		$code = $this->input->post("code");
		$description = $this->input->post("description");
			
		$insert = $this->Puestos_model->PostPosition($idempresa,$code,$description);
		if($insert)
		{
			$this->response($this->json($insert), 200);
		}
		else
		{
			$error = array('status' => "Failed", "msg" => "Error al actualizar la posicion");
			$this->response($this->json($error), 400);
		}
	}
	
	
	public function LoadPositionId()
	{
		$idempresa = $this->input->post("idempresa");
		$code = $this->input->post("code");
		
		$data = $this->Puestos_model->LoadPositionId($idempresa,$code);
		print_r(json_encode($data));
		return $data;
		
	}
	
	public function DeletePositionId()
	{
		$idempresa = $this->input->post("idempresa");
		$code = $this->input->post("code");
		
		$data = $this->Puestos_model->DeletePositionId($idempresa,$code);
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

