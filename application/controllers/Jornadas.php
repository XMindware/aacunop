<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 * 
 */
class Jornadas extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Admin_model');
		$this->load->model('Jornadas_model');
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
	}
	
	public function index()
	{
		if($this->session->userdata('perfil') == FALSE || $this->session->userdata('isadmin')!='1')
		{
			redirect(base_url().'login');
		}
		
		$this->LoadWorkdays();
	}
	
	public function LoadWorkdays()
	{
		$empresa = $this->session->userdata('idempresa');
		
		// consulta las oficinas de la cuales es admin el usuario actual
		$adminoficina = $this->Admin_model->GetOficinasAdmin();
		
		$tdata['titulo'] = 'Work days lenght';
		$tdata['oficinas'] = $adminoficina;
		$tdata['perfil'] = $this->session->userdata('perfil');
		
		$data['rowlist'] = $this->Jornadas_model->CompanyWorkdays($empresa);
		$data['idempresa'] = $empresa;
	
		if($data)
		{
			//$data = Array('userlist' => $userlist);
			//print_r($userlist);
			$this->load->view('paginas/header',$tdata);
			$this->load->view('Jornadas_view',$data);
			$this->load->view('paginas/footer');
		}
	
	}
	
	public function PostWorkday()
	{
		$idempresa = $this->input->post("idempresa");
		$code = $this->input->post("code");
		$description = $this->input->post("description");
		$hours = $this->input->post("hours");
			
		$insert = $this->Jornadas_model->PostWorkday($idempresa,$code,$description,$hours);
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
	
	public function Asyncloadinfo()
	{
		$idempresa = $this->input->post("idempresa");
		$rowlist = $this->Jornadas_model->CompanyWorkdays($idempresa);
		if($rowlist)
		{
			header('Content-type: application/json; charset=utf-8');
			print_r(json_encode($rowlist));
		}
		else
		{
			$error = array('status' => "Failed", "msg" => "Error al obtener informacion");
			$this->response($this->json($error), 400);
		}
	}
	
	public function LoadWorkdayId()
	{
		$idempresa = $this->input->post("idempresa");
		$code = $this->input->post("code");
		
		$data = $this->Jornadas_model->LoadWorkdayId($idempresa,$code);
		print_r(json_encode($data));
		return $data;
		
	}
	
	public function DeleteWorkdayId()
	{
		$idempresa = $this->input->post("idempresa");
		$code = $this->input->post("code");
		
		$data = $this->Jornadas_model->DeleteWorkdayId($idempresa,$code);
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

