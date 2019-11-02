<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//error_reporting(E_ERROR | E_PARSE);

/**
 * 
 */
class Posicionesvuelos extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Admin_model');
		$this->load->model('Vuelos_model');
		$this->load->model('Posicionesvuelos_model');
		$this->load->model('Posiciones_model');
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
	}
	
	public function index()
	{
		if($this->session->userdata('perfil') == FALSE )
		{
			redirect(base_url().'login');
		}
		
		$this->LoadRows();
	}
	
	public function LoadRows()
	{
		$empresa = $this->session->userdata('idempresa');
		$oficina = $this->session->userdata('idoficina');
		$iatacode = $this->session->userdata('iatacode');
		$tdata['perfil'] = $this->session->userdata('perfil');
		
		// consulta las oficinas de la cuales es admin el usuario actual
		$adminoficina = $this->Admin_model->GetOficinasAdmin();
		//$data = Array('userlist' => $userlist);
			//print_r($userlist);

		$rowlist = $this->Posicionesvuelos_model->LoadRows($empresa, $oficina);
		$asignadosvsvuelos = $this->Posicionesvuelos_model->GetVuelosAsignadosVsTotal($empresa,$oficina,$iatacode);

		$sinflights = $this->Posicionesvuelos_model->GetVuelosSinAsignar($empresa,$oficina,$iatacode);
		$flights = $this->Vuelos_model->LoadVuelos($iatacode);
		$posiciones = $this->Posiciones_model->CompanyGatePositions($empresa);
		$eposiciones = $this->Posiciones_model->CompanyExtraGatePositions($empresa);

		$tdata['oficinas'] = $adminoficina;
		$data['iatacode'] = $iatacode;
		$tdata['titulo'] = 'Positions per Flight';
		$data['rowlist'] = $rowlist;
		$data['flights'] = $flights;
		$data['sinflights'] = $sinflights;
		$data['asignados'] = $asignadosvsvuelos['asignados'];
		$data['vuelos'] = $asignadosvsvuelos['vuelos'];
		$data['posiciones'] = $posiciones;
		$data['eposiciones'] = $eposiciones;
		$data['timezone'] = $this->session->userdata('timezone');
		$data['usuario'] = $this->session->userdata('shortname');
		$data['idempresa'] = $empresa;
		$data['idoficina'] = $oficina;

		if($data)
		{
			
			$this->load->view('paginas/header',$tdata);
			$this->load->view('Posicionesvuelos_view',$data);
			$this->load->view('paginas/footer');
		}
	
	}

	public function GetVuelosAsignadosVsTotal()
	{

		$vas = $this->Posicionesvuelos_model->GetVuelosAsignadosVsTotal(1,1,'CUN');
		echo $vas['asignados'] . ':' . $vas['vuelos'];
	}

	public function LoadVueloCode()
	{
		error_reporting(E_ERROR | E_PARSE);
		
		$idempresa = $this->input->post("idempresa");
		$idoficina = $this->input->post("idoficina");
		$idvuelo   = $this->input->post("idvuelo");
		$linea   = $this->input->post("linea");

		$row = $this->Posicionesvuelos_model->LoadRowCode($idempresa,$idoficina,$idvuelo,$linea);
		if($row)
		{
			$this->response($this->json($row), 200);
		}
		else
		{
			$error = array('status' => "Failed", "msg" => "No se encontro el row");
			$this->response($this->json($error), 400);
		}

	}
	
	public function Postrow()
	{

		error_reporting(E_ERROR | E_PARSE);

		$idempresa = $this->input->post("idempresa");
		$idoficina = $this->input->post("idoficina");
		$idvuelo = $this->input->post("idvuelo");
		$linea = $this->input->post("linea");
		$posmon = $this->input->post("posmon");
		$postue = $this->input->post("postue");
		$poswed = $this->input->post("poswed");
		$posthu = $this->input->post("posthu");
		$posfri = $this->input->post("posfri");
		$possat = $this->input->post("possat");
		$possun = $this->input->post("possun");
		$usuario = $this->input->post("usuario");
	
		$insert = $this->Posicionesvuelos_model->PostRow($idempresa, $idoficina, $idvuelo, $linea, $posmon, $postue, $poswed, $posthu, $posfri, $possat, $possun, $usuario);
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



	public function DeletePositionId()
	{

		error_reporting(E_ERROR | E_PARSE);

		$idempresa = $this->input->post("idempresa");
		$idoficina = $this->input->post("idoficina");
		$idvuelo = $this->input->post("idvuelo");
		$posicion = $this->input->post("posicion");
	
		$this->Posicionesvuelos_model->DeletePositionId($idempresa, $idoficina, $idvuelo, $posicion);
		
	}

	public function PosicionesHorario(){

		$idvuelo = $this->input->post("idvuelo");

		$vuelo = $this->Vuelos_model->LoadVueloCode($idvuelo);

		if(sizeof($vuelo))
		{
			$horario = $vuelo[0]['horasalida'];
			
			$rows = $this->Posicionesvuelos_model->PosicionesHorario($horario);

			if($rows)
			{
				$this->response($this->json($rows), 200);
			}
			else
			{
				$error = array('status' => "Failed", "msg" => "No se encontraron horarios");
				$this->response($this->json($error), 400);
			}

		}
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

