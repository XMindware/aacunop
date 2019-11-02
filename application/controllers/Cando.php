<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 * 	Controlador de Habilidades que pueden ser asignados a un usuarios
 *   XALFEIRAN Marzo 2016
 */
class Cando extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Admin_model');
		$this->load->model('Cando_model');
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
	}
	
	public function index()
	{
		if($this->session->userdata('perfil') == FALSE || $this->session->userdata('isadmin')!='1')
		{
			redirect(base_url().'login');
		}
		
		$this->LoadRows();
	}
	
	public function LoadRows()
	{
		$empresa = $this->session->userdata('idempresa');
		
		// consulta las oficinas de la cuales es admin el usuario actual
		$adminoficina = $this->Admin_model->GetOficinasAdmin();
		$tdata['perfil'] = $this->session->userdata('perfil');
		$tdata['titulo'] = 'Skills Catalog';
		$tdata['oficinas'] = $adminoficina;
		
		$data['rowslist'] = $this->Cando_model->CompanySkills($empresa);
		$data['idempresa'] = $empresa;
		$data['idusuario'] = $this->session->userdata('shortname');
	
		if($data)
		{
			//$data = Array('userlist' => $userlist);
			//print_r($userlist);
			$this->load->view('paginas/header',$tdata);
			$this->load->view('Cando_view',$data);
			$this->load->view('paginas/footer');
		}
	
	}
	
	public function PostRow()
	{
		$idempresa = $this->input->post("idempresa");
		$code = $this->input->post("code");
		$orden = $this->input->post("orden");
		$description = $this->input->post("description");
		$usuario = $this->input->post("usuario");
			
		$insert = $this->Cando_model->PostRow($idempresa,$code,$orden,$description,$usuario);
		if($insert)
		{
			header('Content-type: application/json; charset=utf-8');
    		echo json_encode($insert);
		}
		else
		{
			$error = array('status' => "Failed", "msg" => "Error al actualizar la posicion");
			$this->response($this->json($error), 400);
		}
	}
	
	public function AddSkillAgente()
	{
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		$idagente = $this->input->post("idagente");
		$idcando = $this->input->post("idcando");
			
		$insert = $this->Cando_model->AddSkillAgente($idempresa,$idoficina,$idagente,$idcando);
		if($insert)
		{
			$this->response($this->json($insert), 200);
		}
		else
		{
			$error = array('status' => "Failed", "msg" => "Error al agregar Skill");
			$this->response($this->json($error), 400);
		}
	}
	
	public function RemoveSkillAgente()
	{
		$idempresa = $this->input->post("idempresa");
		$idoficina = $this->input->post("idoficina");
		$uniqueid = $this->input->post("idagente");
		$idcando = $this->input->post("idcando");
			
		$insert = $this->Cando_model->RemoveSkillAgente($idempresa,$idoficina,$uniqueid,$idcando);
		
		$this->response($this->json($insert), 200);
	}
	
	public function LoadSkillsAgente()
	{
		$idagente = $this->input->post("idagente");
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		
		$data = $this->Cando_model->LoadCandoAgent($idempresa,$idoficina,$idagente);
		print_r(json_encode($data));
		return $data;
		
	}
	
	
	public function LoadRowId()
	{
		$idempresa = $this->input->post("idempresa");
		$code = $this->input->post("code");
		
		$data = $this->Cando_model->LoadRowId($idempresa,$code);
		print_r(json_encode($data));
		return $data;
		
	}
	
	public function DeleteRowId()
	{
		$idempresa = $this->input->post("idempresa");
		$code = $this->input->post("code");
		
		$data = $this->Cando_model->DeleteRowId($idempresa,$code);
		print_r(json_encode($data));
		return $data;
		
	}
	
	public function batchupdateskills()
	{
		$this->Cando_model->batchupdateskills();
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

