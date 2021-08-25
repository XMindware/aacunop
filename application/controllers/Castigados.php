<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 *	Controller de Lista de castigados
 *  XALFEIRAN 2017
 */
class Castigados extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Admin_model');
		$this->load->model('Agentes_model');
		$this->load->model('Castigados_model');
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
	}
	
	public function index()
	{
		if($this->session->userdata('perfil') == FALSE || $this->session->userdata('isadmin')!='1')
		{
			redirect(base_url().'login');
		}

		$this->LoadCastigados();
	}
	
	public function LoadCastigados()
	{
		$empresa = $this->session->userdata('idempresa');
		$oficina = $this->session->userdata('idoficina');
		
		// consulta las oficinas de la cuales es admin el usuario actual
		$adminoficina = $this->Admin_model->GetOficinasAdmin();
		
		$tdata['titulo'] = 'List of Agents penalties';
		$tdata['oficinas'] = $adminoficina;
		$tdata['perfil'] = $this->session->userdata('perfil');
		$data['usuario'] = $this->session->userdata('shortname');

		$castigados = $this->Castigados_model->ConsultarCastigadosEstacion($empresa,$oficina);
		$agentes = $this->Agentes_model->StationAgents($empresa,$oficina);
		
		$data['fullagents'] =  $agentes;	
		$data['castigados'] = $castigados;
		$data['idempresa'] = $empresa;
		$data['idoficina'] = $oficina;

		if($data)
		{
			$this->load->view('paginas/header',$tdata);
			$this->load->view('Castigados_view',$data);
			$this->load->view('paginas/footer');
		}
	
	}

	public function DeleteRow()
	{
		$uniqueid = $this->input->post("uniqueid");
		$oficina = $this->input->post('idoficina');
		$empresa = $this->input->post('idempresa');
		
		$this->Castigados_model->DeleteRow($empresa,$oficina,$uniqueid);

		$data = array( 'status' => 'OK' );
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($data);
	}
	

	public function LoadRow()
	{
		$uniqueid = $this->input->post("uniqueid");
		$oficina = $this->input->post('idoficina');
		$empresa = $this->input->post('idempresa');
		
		$data = $this->Castigados_model->LoadRow($empresa,$oficina,$uniqueid);
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($data);
		
	}

	public function PostRow()
	{
		$empresa = $this->input->post('idempresa');
		$oficina = $this->input->post('idoficina');
		$agenteid = $this->input->post("agenteid");
		$shortname = $this->input->post("agente");
		$fechaini = $this->input->post("fechaini");
		$fechafin = $this->input->post("fechafin");
		$razon = $this->input->post("razon");
		$usuario = $this->input->post("usuario");
		$uniqueid = $this->input->post("uniqueid");

		$insert = $this->Castigados_model->PostRow($empresa, $oficina, $agenteid, $shortname, $fechaini, $fechafin, $razon, $usuario, $uniqueid);
		
		$data = isset($insert->status)?'OK':'ERROR';

		header('Content-type: application/json; charset=utf-8');
		echo json_encode($data);
	}

	public function Asynclist()
	{
		$empresa = $this->input->post('idempresa');
		$oficina = $this->input->post('idoficina');
		$data = $this->Castigados_model->ConsultarCastigadosEstacion($empresa,$oficina);
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
		$this->_code = ($status)?$status:200;
		echo $data;
		exit;
	}
}

