<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 *	Controller de Descansos
 *  XALFEIRAN 2016
 *
 *  Borrar agente extra 2017-06-29
 */
class Webcunop extends CI_Controller {
	
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
		if($this->session->userdata('perfil') == FALSE )
		{
			redirect(base_url().'login');
		}

		$fecha = $this->input->get("fecha");
		if($fecha == ''){
			$fecha = $this->input->post("inputFecha");
			if(!isset($fecha) || $fecha=='')	
	            $fecha = date('Y-m-d');
	    }
		$empresa = $this->session->userdata('idempresa');
		$oficina = $this->session->userdata('idoficina');

		$this->LoadCunopFecha($empresa,$oficina,$fecha);
	}

	public function LoadCunopFecha($empresa,$oficina,$qdate)
	{

		// consulta las oficinas de la cuales es admin el usuario actual
		$adminoficina = $this->Admin_model->GetOficinasAdmin();

		$fullagents = $this->LoadAgentsDate($empresa,$oficina,$qdate);
		
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

		$pt4 = $this->Webcunop_model->LoadAgentsScheduleFechaByPos($empresa,$oficina,$qdate,'PT');
		$pt6 = $this->Webcunop_model->LoadAgentsScheduleFechaByPos($empresa,$oficina,$qdate,'PT6');
		$ft8 = $this->Webcunop_model->LoadAgentsScheduleFechaByPos($empresa,$oficina,$qdate,'FT');

		// el footer con datos de gerentes, fechas e indicaciones extras
		$footer = $this->Webcunop_model->LoadFlightDateHeaderFooter($empresa,$oficina,$qdate,'F');
		$data['timezone'] =  $this->session->userdata('timezone');
		$data['idusuario'] = $this->session->userdata('shortname');
		$tdata['titulo'] = 'Web CunOP Off';
		$tdata['oficinas'] = $adminoficina;
		$data['isadmin'] = $this->session->userdata('isadmin');
		$data['fecha'] = $qdate;
		$data['headerlist'] =  $header;
		$data['mainlist'] 	=  $main;
		$data['bmaslist'] =  $bmas;
		$data['leadslist'] =  $leads;
		$data['agentslist'] =  $agents;	
		$data['pt4'] = $pt4;
		$data['pt6'] = $pt6;
		$data['ft8'] = $ft8;
		$data['posiciones'] = $posiciones;
		$data['fullagents'] =  $fullagents;
		$data['footerlist'] =  $footer;	
		$data['idempresa'] = $empresa;
		$data['idoficina'] = $oficina;

		if($data)
		{
			//$data = Array('userlist' => $userlist);
			//print_r($userlist);
			if($this->session->userdata('isadmin')!='1')
				$this->load->view('paginas/header_u',$tdata);
			else
				$this->load->view('paginas/header',$tdata);
			$this->load->view('Webcunop_view',$data);
			$this->load->view('paginas/footer');
		}
	
	}


	public function LoadTest()
	{
		$data = $this->Webcunop_model->LoadFlightsDate(1,1,'2017-05-11');
		header('Content-type: application/json; charset=utf-8');
    	echo json_encode($data);
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

		$infovuelo = $this->Webcunop_model->GetVueloDate($empresa,$oficina,$idvuelo,$fecha);

		$data['posiciones'] = $posiciones;
		$data['flight'] = $flight;
		$data['mensaje'] = $infovuelo->mensaje;
		$data['departure'] = $infovuelo->horasalida;
		
		header('Content-type: application/json; charset=utf-8');
    	echo json_encode($data);
	}

	public function AsyncLoadStationDate()
	{
		$oficina = $this->input->post('idoficina');
		$empresa = $this->input->post('idempresa');
		$fecha 	 = $this->input->post('fecha');

		$main = $this->Webcunop_model->LoadFlightsDate($empresa,$oficina,$fecha);

		header('Content-type: application/json; charset=utf-8');
    	echo json_encode($main);
	}

	public function AsyncLoadStationLeadsDate()
	{
		$oficina = $this->input->post('idoficina');
		$empresa = $this->input->post('idempresa');
		$fecha 	 = $this->input->post('fecha');

		$main = $this->Webcunop_model->LoadLeadsFecha($empresa,$oficina,$fecha);

		header('Content-type: application/json; charset=utf-8');
    	echo json_encode($main);
	}

	public function AppLoadStationDate()
	{
		$oficina = $this->input->post('idoficina');
		$empresa = $this->input->post('idempresa');
		$fecha 	 = $this->input->post('fecha');

		$main = $this->Webcunop_model->LoadFlightsDate($empresa,$oficina,$fecha);

		header('Content-type: application/json; charset=utf-8');
    	echo json_encode($main);
	}

	public function AsyncLoadStationSchedule()
	{
		$oficina = $this->input->post('idoficina');
		$empresa = $this->input->post('idempresa');
		$fecha 	 = $this->input->post('fecha');

		$pt4 = $this->Webcunop_model->LoadAgentsScheduleFechaByPos($empresa,$oficina,$fecha,'PT');
		$pt6 = $this->Webcunop_model->LoadAgentsScheduleFechaByPos($empresa,$oficina,$fecha,'PT6');
		$ft8 = $this->Webcunop_model->LoadAgentsScheduleFechaByPos($empresa,$oficina,$fecha,'FT');
		$leads = $this->Webcunop_model->LoadLeadsFecha($empresa,$oficina,$fecha);
		$bmas = $this->Webcunop_model->LoadBmasFecha($empresa,$oficina,$fecha);
		$main = array($pt4,$pt6,$ft8,$bmas,$leads);

		header('Content-type: application/json; charset=utf-8');
    	echo json_encode($main);
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
		$uniqueid = $this->input->post("uniqueid");
		$agenteid = $this->input->post("agenteid");
		$oficina = $this->input->post('idoficina');
		$empresa = $this->input->post('idempresa');
		$fecha = $this->input->post('fecha');
		$posicion = $this->input->post('posicion');
		
		$data = $this->Webcunop_model->LoadAgentSchedule($uniqueid,$empresa,$oficina,$agenteid,$fecha,$posicion);

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

	public function LoadLeadSchedule()
	{
		$uniqueid = $this->input->post("uniqueid");
		$agenteid = $this->input->post("agenteid");
		$oficina = $this->input->post('idoficina');
		$empresa = $this->input->post('idempresa');
		$fecha = $this->input->post('fecha');
		$posicion = $this->input->post('posicion');
		
		$data = $this->Webcunop_model->LoadLeadSchedule($uniqueid,$empresa,$oficina,$agenteid,$fecha,$posicion);

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

	public function LoadAgentsDate($empresa,$oficina,$fecha)
	{
		
		$data = $this->Webcunop_model->LoadAgentsDate($empresa,$oficina,$fecha);
		return $data;
		
	}


	public function SwitchAgente()
	{
		$idempresa = $this->input->post('idempresa');
		$idoficina = $this->input->post('idoficina');
		$fecha = $this->input->post("fecha");
		$uniqueid = $this->input->post('uniqueid');
		$idagente_nuevo = $this->input->post("agenteid");
		$agente_nuevo = $this->input->post("shortname");
		$posicion = $this->input->post("posicion");
		$usuario = $this->input->post('usuario');

		$agenteActual = $this->Webcunop_model->ConsultarAgenteActual($idempresa,$idoficina,$uniqueid);
		if(count($agenteActual)==0)
		{
			$error = array('status' => "Failed", "msg" => "No agent is assigned to this position");
			$this->response($this->json($error), 400);
		}
		else
		{
			$insert = $this->Webcunop_model->PostCambioSchedule($idempresa, $idoficina, $uniqueid, $fecha, $idagente_nuevo, $agente_nuevo, $posicion, $usuario);

			$shortname_old = $agenteActual[0]['shortname'];
			$this->Webcunop_model->RegistrarBitacora($idempresa,$idoficina,$fecha,'',$shortname_old,$posicion,$agente_nuevo,$posicion,$usuario);
			// si la posicion es de sala, hace switch en el vuelo
			if (strpos($posicion, 'G') !== false) {
				$insert = $this->Webcunop_model->SwitchAgentes($idempresa,$idoficina,$fecha,$shortname_old,$agente_nuevo,$posicion,$usuario);
			}
			
			header('Content-type: application/json; charset=utf-8');
	    	echo json_encode($insert);
		}
	}

	public function DeleteExtraAgent()
	{
		$idempresa = $this->input->post('idempresa');
		$idoficina = $this->input->post('idoficina');
		$uniqueid = $this->input->post('uniqueid');
		$idvuelo = $this->input->post('idvuelo');
		$fecha = $this->input->post('fecha');

		$data = $this->Webcunop_model->DeleteExtraAgent($idempresa,$idoficina,$uniqueid,$idvuelo,$fecha);
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


	public function UpdateFlightMessage()
	{
		$idempresa = $this->input->post('idempresa');
		$idoficina = $this->input->post('idoficina');
		$usuario = $this->input->post('usuario');
		$idvuelo = $this->input->post('idvuelo');
		$fecha = $this->input->post('fecha');
		$mensaje = $this->input->post('mensaje');

		$data = $this->Webcunop_model->UpdateFlightMessage($idempresa,$idoficina,$idvuelo,$fecha,$mensaje,$usuario);
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

	public function UpdateFlightDeparture()
	{
		$idempresa = $this->input->post('idempresa');
		$idoficina = $this->input->post('idoficina');
		$usuario = $this->input->post('usuario');
		$idvuelo = $this->input->post('idvuelo');
		$fecha = $this->input->post('fecha');
		$departure = $this->input->post('departure');

		$data = $this->Webcunop_model->UpdateFlightDeparture($idempresa,$idoficina,$idvuelo,$fecha,$departure,$usuario);
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


	public function SwitchLead()
	{
		$idempresa = $this->input->post('idempresa');
		$idoficina = $this->input->post('idoficina');
		$fecha = $this->input->post("fecha");
		$uniqueid = $this->input->post('uniqueid');
		$idagente_nuevo = $this->input->post("agenteid");
		$agente_nuevo = $this->input->post("shortname");
		$posicion = $this->input->post("posicion");
		$usuario = $this->input->post('usuario');

		$agenteActual = $this->Webcunop_model->ConsultarLeadActual($idempresa,$idoficina,$uniqueid);
		if(count($agenteActual)==0)
		{
			$error = array('status' => "Failed", "msg" => "No lead is assigned to this position");
			$this->response($this->json($error), 400);
		}
		else
		{
			$insert = $this->Webcunop_model->PostCambioScheduleLead($idempresa, $idoficina, $uniqueid, $fecha, $idagente_nuevo, $agente_nuevo, $posicion, $usuario);

			$shortname_old = $agenteActual[0]['shortname'];
			$this->Webcunop_model->RegistrarBitacora($idempresa,$idoficina,$fecha,'',$shortname_old,$posicion,$agente_nuevo,$posicion,$usuario);
			// si la posicion es de sala, hace switch en el vuelo
			if (strpos($posicion, 'G') !== false) {
				$insert = $this->Webcunop_model->SwitchAgentes($idempresa,$idoficina,$fecha,$shortname_old,$agente_nuevo,$posicion,$usuario);
			}
			
			header('Content-type: application/json; charset=utf-8');
	    	echo json_encode($insert);
		}
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

			$currflight = $this->Webcunop_model->LoadAgentPrevious($empresa,$oficina,$fecha, $idvuelo);
			$i=0;
			foreach($currflight as $oldagent)
			{
				if($i==0)
					$this->Webcunop_model->UpdateAgentsVuelosFecha($empresa,$oficina,$fecha,$oldagent['idagente'],$agente1,$posicion1,$usuario);
				else
					$this->Webcunop_model->UpdateAgentsVuelosFecha($empresa,$oficina,$fecha,$oldagent['idagente'],$agente2,$posicion2,$usuario);
				$i++;
			}

			$insert = $this->Webcunop_model->ConsultarNuevosAgentes($empresa,$oficina,$fecha,$agente1);
			
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

	public function AddExtraAgent()
	{
		$idempresa = $this->input->post('idempresa');
		$idoficina = $this->input->post('idoficina');
		$idvuelo = $this->input->post("idvuelo");
		$fecha = $this->input->post("fecha");
		$linea = $this->input->post('linea');
		$idagente = $this->input->post("idagente");
		$posicion = $this->input->post("posicion");
		$usuario = $this->input->post('usuario');
		
		
		$linea = $this->input->post("linea");

		$insert = $this->Webcunop_model->PostExtraAgent($idempresa, $idoficina, $idvuelo, $fecha, $linea, $idagente, $posicion, $usuario);
		
		header('Content-type: application/json; charset=utf-8');
    	echo json_encode($insert);
		
	}

	public function PostCambioSchedule()
	{
		$empresa = $this->input->post('idempresa');
		$oficina = $this->input->post('idoficina');
		$uniqueid = $this->input->post('uniqueid');
		$usuario = $this->input->post('usuario');
		$fecha = $this->input->post("fecha");
		$agenteid = $this->input->post("agenteid");
		$shortname = $this->input->post("shortname");
		$posicion = $this->input->post("posicion");
		
		$insert = $this->Webcunop_model->PostCambioSchedule($empresa, $oficina, $uniqueid, $fecha, $agenteid, $shortname, $posicion, $usuario);
		
		header('Content-type: application/json; charset=utf-8');
    	echo json_encode($insert);
		//$this->response($this->json($insert), 200);
	
	}

	public function PostCambioScheduleLead()
	{
		$empresa = $this->input->post('idempresa');
		$oficina = $this->input->post('idoficina');
		$uniqueid = $this->input->post('uniqueid');
		$usuario = $this->input->post('usuario');
		$fecha = $this->input->post("fecha");
		$agenteid = $this->input->post("agenteid");
		$shortname = $this->input->post("shortname");
		$posicion = $this->input->post("posicion");
		
		$insert = $this->Webcunop_model->PostCambioScheduleLead($empresa, $oficina, $uniqueid, $fecha, $agenteid, $shortname, $posicion, $usuario);
		
		header('Content-type: application/json; charset=utf-8');
    	echo json_encode($insert);
		//$this->response($this->json($insert), 200);
	
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

		$data = $this->Posicionesvuelos_model->PosicionesParaAgenteUnique($idempresa,$idoficina,$idagente);
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

