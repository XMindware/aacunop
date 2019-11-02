<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 *	Controller de Monitor de asignaciones
 *  XALFEIRAN 2016
 */
class Monitorop extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Admin_model');
		$this->load->model('Agentes_model');
		$this->load->model('Posiciones_model');
		$this->load->model('Posicionesvuelos_model');
		$this->load->model('Webcunop_model');
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
	}
	
	public function index()
	{
		/*
		if($this->session->userdata('perfil') == FALSE )
		{
			redirect(base_url().'login');
		}
		*/

		$data = array(
            'is_logued_in' 	=> 		TRUE,
			'idempresa'		=>		1,
			'idoficina'		=>		1,
			'iatacode'		=>		'CUN',
            'idagente' 		=> 		'300101',
            'isadmin'		=>		0
    		);		
			$this->session->set_userdata($data);
		$qpxapikey = "AIzaSyBkcjlT5erMSLF-a7K9neTyqd3RSLxkB-E";

		$fecha = $this->input->post("inputFecha");
		
		if(!isset($fecha) || $fecha=='')	
            $fecha = date('Y-m-d');
		$empresa = $this->session->userdata('idempresa');
		$oficina = $this->session->userdata('idoficina');

		$this->LoadCunopFecha($empresa,$oficina,$fecha);
	}

	public function LoadCunopFecha($empresa,$oficina,$qdate)
	{

		// consulta las oficinas de la cuales es admin el usuario actual
		$adminoficina = $this->Admin_model->GetOficinasAdmin();

		$fullagents = $this->LoadAgents($empresa,$oficina);
		
		// consulta primero el header del cunop
		$header = $this->Webcunop_model->LoadFlightDateHeaderFooter($empresa,$oficina,$qdate,'H');
		// el contenido del cunop vuelos, posiciones, horarios
		$main = $this->Webcunop_model->LoadFlightsDate($empresa,$oficina,$qdate);
		// el bmas con 
		$bmas = $this->Webcunop_model->LoadBmasFecha($empresa,$oficina,$qdate);
		// el listado de leads
		$leads = $this->Webcunop_model->LoadLeadsFecha($empresa,$oficina,$qdate);
		// posiciones disponibles
		$posiciones = $this->Posiciones_model->CompanyPositions($empresa);
		// el listado de agentes
		$agents = $this->Webcunop_model->LoadAgentsScheduleFecha($empresa,$oficina,$qdate);
		// el footer con datos de gerentes, fechas e indicaciones extras
		$footer = $this->Webcunop_model->LoadFlightDateHeaderFooter($empresa,$oficina,$qdate,'F');
		$data['timezone'] = $this->session->userdata('timezone');
		$data['idusuario'] = $this->session->userdata('shortname');
		$tdata['titulo'] = 'Web CunOP Off';
		$tdata['oficinas'] = $adminoficina;
		
		$data['fecha'] = $qdate;
		$data['headerlist'] =  $header;
		$data['mainlist'] 	=  $main;
		$data['bmaslist'] =  $bmas;
		$data['leadslist'] =  $leads;
		$data['agentslist'] =  $agents;	
		$data['posiciones'] = $posiciones;
		$data['fullagents'] =  $fullagents;
		$data['footerlist'] =  $footer;	
		$data['idempresa'] = $empresa;
		$data['idoficina'] = $oficina;

		if($data)
		{
			//$data = Array('userlist' => $userlist);
			//print_r($userlist);
			$this->load->view('Monitorop_view',$data);
		}
	
	}

	public function pdfcunop()
	{
		
		$empresa = 1;
		$oficina = 1;
		$qdate = $this->input->get("f");
		// consulta las oficinas de la cuales es admin el usuario actual

		$fullagents = $this->LoadAgents($empresa,$oficina);
		
		// consulta primero el header del cunop
		$header = $this->Webcunop_model->LoadFlightDateHeaderFooter($empresa,$oficina,$qdate,'H');
		// el contenido del cunop vuelos, posiciones, horarios
		$main = $this->Webcunop_model->LoadFlightsDate($empresa,$oficina,$qdate);
		// el bmas con 
		$bmas = $this->Webcunop_model->LoadBmasFecha($empresa,$oficina,$qdate);
		// el listado de leads
		$leads = $this->Webcunop_model->LoadLeadsFecha($empresa,$oficina,$qdate);
		// posiciones disponibles
		$posiciones = $this->Posiciones_model->CompanyPositions($empresa);
		// el listado de agentes
		$agents = $this->Webcunop_model->LoadAgentsScheduleFecha($empresa,$oficina,$qdate);
		// el footer con datos de gerentes, fechas e indicaciones extras
		$footer = $this->Webcunop_model->LoadFlightDateHeaderFooter($empresa,$oficina,$qdate,'F');
		$data['timezone'] = $this->session->userdata('timezone');
		$data['idusuario'] = $this->session->userdata('shortname');
		$tdata['titulo'] = 'Web CunOP Off';
		
		$data['fecha'] = $qdate;
		$data['headerlist'] =  $header;
		$data['mainlist'] 	=  $main;
		$data['bmaslist'] =  $bmas;
		$data['leadslist'] =  $leads;
		$data['agentslist'] =  $agents;	
		$data['posiciones'] = $posiciones;
		$data['fullagents'] =  $fullagents;
		$data['footerlist'] =  $footer;	
		$data['idempresa'] = $empresa;
		$data['idoficina'] = $oficina;

		if($data)
		{
			//$data = Array('userlist' => $userlist);
			//print_r($userlist);
			$this->load->view('pdfcunop_view',$data);
			$this->load->view('paginas/footer');
		}
	
	}
	
	public function LoadFlightDetail()
	{
		$idvuelo = $this->input->post("idvuelo");
		$oficina = $this->input->post('idoficina');
		$empresa = $this->input->post('idempresa');
		$fecha 	 = $this->input->post('fecha');

		$posiciones = $this->PosicionesHorario($idvuelo);
		
		$flight = $this->Webcunop_model->LoadFlightDateDetail($empresa,$oficina,$fecha,$idvuelo);

		$data['posiciones'] = $posiciones;
		$data['flight'] = $flight;
		
		header('Content-type: application/json; charset=utf-8');
    	echo json_encode($data);
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

	public function LoadAgentSchedule()
	{
		$agenteid = $this->input->post("agenteid");
		$oficina = $this->input->post('idoficina');
		$empresa = $this->input->post('idempresa');
		$fecha = $this->input->post('fecha');
		$posicion = $this->input->post('posicion');
		
		$data = $this->Webcunop_model->LoadAgentSchedule($empresa,$oficina,$agenteid,$fecha,$posicion);

		if($data)
		{
			header('Content-type: application/json; charset=utf-8');
    		echo json_encode($data);
		}
		else
		{
			$error = array('status' => "Failed", "msg" => "Error while obtaining position data");
			$this->response($this->json($error), 400);
		}
	}

	public function LoadAgents($empresa,$oficina)
	{
		
		$data = $this->Agentes_model->StationAgents($empresa,$oficina);
		return $data;
		
	}


	public function PostCambio()
	{
		$empresa = $this->input->post('idempresa');
		$oficina = $this->input->post('idoficina');
		$usuario = $this->input->post('usuario');
		$idvuelo = $this->input->post("idvuelo");
		$fecha = $this->input->post("fecha");
		$linea = $this->input->post("linea");
		$linea = ($linea=='')?0:$linea;
		$agente1 = $this->input->post("agente1");
		$posicion1 = $this->input->post("posicion1");
		$agente2 = $this->input->post("agente2");
		$posicion2 = $this->input->post("posicion2");
		$followup = $this->input->post("followup");

		if($followup == 0)
			$insert = $this->Webcunop_model->PostCambio($empresa, $oficina, $idvuelo, $fecha, $linea, $agente1, $agente2, $posicion1, $posicion2, $usuario);
		else
		{
			// pide actualizar todos los vuelos donde estaba el agente
			$flights = $this->Webcunop_model->LoadFlightsDate($empresa,$oficina,$fecha);
			foreach ($flights as $flight) {
				$curr = $this->Webcunop_model->LoadAgentPrevious($empresa,$oficina,$fecha, $idvuelo, 1);
				if(sizeof($curr) > 0 )
					// si es el mismo agente, no hace nada
					if($curr[0]['idagente'] != $agente1)
					{
						// cambiar agentes
						$oldagent = $curr[0]['idagente'];
						$insert = $this->Webcunop_model->UpdateAgentsVuelosFecha($empresa,$oficina,$fecha,$oldagent,$agente1,$usuario);

						$this->Webcunop_model->RegistrarBitacora($empresa,$oficina,$fecha,'',$oldagent,$curr[0]['posicion'],$agente1,$posicion1,$usuario);
					}
				$curr = $this->Webcunop_model->LoadAgentPrevious($empresa,$oficina,$fecha, $idvuelo, 2);
				if(sizeof($curr) > 0 )
					// si es el mismo agente, no hace nada
					if($curr[0]['idagente'] != $agente2)
					{
						// cambiar agentes
						$oldagent = $curr[0]['idagente'];
						$insert = $this->Webcunop_model->UpdateAgentsVuelosFecha($empresa,$oficina,$fecha,$oldagent,$agente2,$usuario);

						$this->Webcunop_model->RegistrarBitacora($empresa,$oficina,$fecha,'',$oldagent,$curr[0]['posicion'],$agente2,$posicion2,$usuario);
					}
					
			}
		}
		if($insert)
		{
			header('Content-type: application/json; charset=utf-8');
    		echo json_encode($insert);
		}
		else
		{
			$error = array('status' => "Failed", "msg" => "Error while obtaining position data");
			$this->response($this->json($error), 400);
		}
	
	}

	public function PostCambioSchedule()
	{
		$empresa = $this->input->post('idempresa');
		$oficina = $this->input->post('idoficina');
		$usuario = $this->input->post('usuario');
		$fecha = $this->input->post("fecha");
		$agenteid = $this->input->post("agenteid");
		$posicion = $this->input->post("posicion");
		
		$insert = $this->Webcunop_model->PostCambioSchedule($empresa, $oficina, $fecha, $agenteid, $posicion, $usuario);
		
		$this->response($this->json($insert), 200);
	
	}

	public function PosicionesHorario($idvuelo){

		$vuelo = $this->Vuelos_model->LoadVueloCode($idvuelo);

		if(sizeof($vuelo))
		{
			$horario = $vuelo[0]['horasalida'];
			
			$rows = $this->Posicionesvuelos_model->PosicionesHorario($horario);

			if($rows)
			{
				return $rows;
			}
		}
	}

	public function PosicionesParaAgente()
	{
		$idempresa = $this->input->post("idempresa");
		$idoficina = $this->input->post("idoficina");
		$idagente = $this->input->post("idagente");

		$data = $this->Posicionesvuelos_model->PosicionesParaAgente($idempresa,$idoficina,$idagente);
		if($data)
		{
			header('Content-type: application/json; charset=utf-8');
    		echo json_encode($data);
		}
		else
		{
			$error = array('status' => "Failed", "msg" => "Error while obtaining position data");
			$this->response($this->json($error), 400);
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

