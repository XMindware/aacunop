<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 *	Controller de Descansos
 *  XALFEIRAN 2016
 */
class Descansos extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Admin_model');
		$this->load->model('Agentes_model');
		$this->load->model('Descansos_model');
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
	}
	
	public function index()
	{
		if($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != 'admin')
		{
			redirect(base_url().'login');
		}

		$this->LoadDescansos();
	}
	
	public function LoadDescansos()
	{
		$empresa = $this->session->userdata('idempresa');
		$oficina = $this->session->userdata('idoficina');
		
		// consulta las oficinas de la cuales es admin el usuario actual
		$adminoficina = $this->Admin_model->GetOficinasAdmin();
		
		$tdata['titulo'] = 'Agents Days Off';
		$tdata['oficinas'] = $adminoficina;
		$tdata['perfil'] = $this->session->userdata('perfil');
		$descansos = $this->Descansos_model->StationDaysOff($empresa,$oficina);
		$agentes = $this->Agentes_model->StationAgents($empresa,$oficina);
		
		$data['agentlist'] =  $agentes;	
		$data['descansos'] = $descansos;
		$data['idempresa'] = $empresa;
		$data['idoficina'] = $oficina;

		if($data)
		{
			//$data = Array('userlist' => $userlist);
			//print_r($userlist);
			$this->load->view('paginas/header',$tdata);
			$this->load->view('Descansos_view',$data);
			$this->load->view('paginas/footer');
		}
	
	}
	
	
	
	public function LoadAgentId()
	{
		$agenteid = $this->input->post("agenteid");
		$oficina = $this->input->post('idoficina');
		$empresa = $this->input->post('idempresa');
		
		$data = $this->Descansos_model->LoadAgentId($empresa,$oficina,$agenteid);
		print_r(json_encode($data));
		return $data;
		
	}

	public function PostAgent()
	{
		$empresa = $this->input->post('idempresa');
		$oficina = $this->input->post('idoficina');
		$agenteid = $this->input->post("agenteid");
		$diasdescanso = $this->input->post("diasdescanso");
		$dia1 = 0;
		$dia2 = 0;

		switch($diasdescanso){
			case 1:   // lunes martes
				$dia1 = 1;   $dia2 = 2;
				break;
			case 2:   // martes, miercoles
				$dia1 = 2;   $dia2 = 3;
				break;
			case 3:   // miercole, jueves
				$dia1 = 3;   $dia2 = 4;
				break;
			case 4:   //  jueves, viernes
				$dia1 = 4;   $dia2 = 5;
				break;
			case 5:   // viernes, sabado
				$dia1 = 5;   $dia2 = 6;
				break;
			case 6:   // sabado, domingo
				$dia1 = 6;   $dia2 = 7;
				break;
			case 7:   // domingo, lunes
				$dia1 = 7;   $dia2 = 1;
				break;
		}
		
		$insert = $this->Descansos_model->PostAgent($empresa, $oficina, $agenteid,$dia1, $dia2);
		//if($insert)
		//{
			$this->response($this->json($insert), 200);
		//}
		//else
		//{
		//	$error = array('status' => "Failed", "msg" => "Error al actualizar el agente");
		//	$this->response($this->json($error), 400);
		//}
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

