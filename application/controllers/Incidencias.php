<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//error_reporting(E_ERROR | E_PARSE);

/**
 *   Control de incidencias para agentes
 *		XALFEIRAN Junio 2017
 */
class Incidencias extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Admin_model');
		$this->load->model('Vuelos_model');
		$this->load->model('Jornadas_model');
		$this->load->model('Incidencias_model');
		$this->load->model('Posiciones_model');
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
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		if($this->input->post('fechaini'))
		{
			$fechaini = $this->input->post('fechaini');
			$fechafin = $this->input->post('fechafin');
		}
		else
		{
			$fecha = date('Y-m-d');
			$fechaini = date('Y-m-d',strtotime($fecha . ' - 1 months'));
			$fechafin = date('Y-m-d',strtotime($fecha . ' + 1 months'));
		}

		$tdata['perfil'] = $this->session->userdata('perfil');
		
		// consulta las oficinas de la cuales es admin el usuario actual
		$adminoficina = $this->Admin_model->GetOficinasAdmin();
		//$data = Array('userlist' => $userlist);
			//print_r($userlist);

		$rowlist = $this->Incidencias_model->LoadIncidenciasRequired($idempresa, $idoficina, $fechaini, $fechafin);

		$tdata['oficinas'] = $adminoficina;
		$data['fullagents'] = $this->LoadAgents($idempresa,$idoficina);
		$tdata['titulo'] = 'Vacations & leaves';
		$data['rowlist'] = $rowlist;
		$data['timezone'] = $this->session->userdata('timezone');
		$data['usuario'] = $this->session->userdata('shortname');
		$data['idempresa'] = $idempresa;
		$data['idoficina'] = $idoficina;

		if($data)
		{
			
			$this->load->view('paginas/header',$tdata);
			$this->load->view('Incidencias_view',$data);
			$this->load->view('paginas/footer');
		}
	
	}

	public function GetVuelosAsignadosVsTotal()
	{

		$vas = $this->Posicionesvuelos_model->GetVuelosAsignadosVsTotal(1,1,'CUN');
		echo $vas['asignados'] . ':' . $vas['vuelos'];
	}

	public function LoadRowId()
	{	
		$idempresa = $this->input->post("idempresa");
		$idoficina = $this->input->post("idoficina");
		$uniqueid   = $this->input->post("uniqueid");

		$row = $this->Incidencias_model->LoadRowCode($idempresa,$idoficina,$uniqueid);
		if($row)
		{
			header('Content-type: application/json; charset=utf-8');
    		echo json_encode($row);
		}
		else
		{
			$error = array('status' => "Failed", "msg" => "No se encontro el row");
			$this->response($this->json($error), 400);
		}

	}
	
	public function Postrow()
	{

		$idempresa = $this->input->post("idempresa");
		$idoficina = $this->input->post("idoficina");
		$uniqueid = $this->input->post("uniqueid");
		$idagente = $this->input->post("idagent");
		$shortname = $this->input->post("shortname");
		$incidencia = $this->input->post("incidencia");
		$fechaini = $this->input->post("fechaini");
		$fechafin = $this->input->post("fechafin");
		$vigente = $this->input->post("vigente");
		$usuario = $this->input->post("usuario");

		$insert = $this->Incidencias_model->PostRow($uniqueid, $idempresa, $idoficina, $idagente, $shortname, $incidencia, $fechaini, $fechafin, $vigente, $usuario);
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



	public function DeleteRowId()
	{

		error_reporting(E_ERROR | E_PARSE);

		$idempresa = $this->input->post("idempresa");
		$idoficina = $this->input->post("idoficina");
		$uniqueid = $this->input->post("uniqueid");
	
		$this->Incidencias_model->DeleteRowId($idempresa, $idoficina, $uniqueid);
		
	}

	public function LoadRequiredPosId(){

		$idempresa = $this->input->post("idempresa");
		$idoficina = $this->input->post("idoficina");
		$position = $this->input->post("position");
		$workday = $this->input->post("workday");

		$rows = $this->Posicionesrequeridas_model->LoadRowCode($idempresa, $idoficina, $position, $workday);
		
		if($rows)
		{
			header('Content-type: application/json; charset=utf-8');
    		echo json_encode($rows);
		}
		else
		{
			$error = array('status' => "Failed", "msg" => "No se encontraron registros");
			$this->response($this->json($error), 400);
		}

	}

	public function LoadAgents($empresa,$oficina)
	{
		
		$data = $this->Agentes_model->StationAgents($empresa,$oficina);
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

