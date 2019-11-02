<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 *	Controller que administra la solicitud de cambios
 *  XALFEIRAN 2019
 */
class Timeswitch extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model(array('Admin_model','Loadleadsmensual_model','Loadimpresionmensual_model'));
		$this->load->model('Agentes_model');
		$this->load->model('Timeswitch_model');
		$this->load->model('Webcunop_model');
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
	}
	
	public function index()
	{
		if($this->session->userdata('perfil') == FALSE )
		{
			//echo 'no paso';
			redirect(base_url().'login');
		}
		
			$this->LoadAgentRequests();
	}

	public function Pending()
	{
		if($this->session->userdata('perfil') == FALSE )
		{
			redirect(base_url().'login');
		}

		$fecha = date('Y-m-d');
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		
		// consulta las oficinas de la cuales es admin el usuario actual
		$adminoficina = $this->Admin_model->GetOficinasAdmin();
		
		$tdata['titulo'] = 'Time Shift Request form';
		$tdata['oficinas'] = $adminoficina;
		$tdata['perfil'] = $this->session->userdata('perfil');
		$requests = $this->Timeswitch_model->ConsultarAgentRequests($idempresa,$idoficina,$this->session->userdata('idagente'));

		// registro de transcciones pasadas
		$registros = $this->Timeswitch_model->ConsultarAgentHistoricalRequests($idempresa,$idoficina);
		$agentes = $this->Agentes_model->StationAgents($idempresa,$idoficina);
		
		$data['monthlyschedule'] = $this->Webcunop_model->ConsultarMonthlySchedule($idempresa,$idoficina,$this->session->userdata('idagente'),$fecha);
		$data['fullagents'] =  $agentes;	
		$data['requests'] = $requests;
		$data['registros'] = $registros;
		$data['idempresa'] = $idempresa;
		$data['idoficina'] = $idoficina;
		$data['shortname'] = $this->session->userdata('shortname');

		if($data)
		{
			//$data = Array('userlist' => $userlist);
			//print_r($userlist);
			if($this->session->userdata('isadmin')!='1')
				$this->load->view('paginas/header_u',$tdata);
			else
				$this->load->view('paginas/header',$tdata);
			$this->load->view('PendingTimeswitch_view',$data);
			$this->load->view('paginas/footer');
		}
	}

	public function LoadAgentRequests(){

		if($this->session->userdata('perfil') == FALSE )
		{
			redirect(base_url().'login');
		}

		$fecha = date('Y-m-d');
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		
		// consulta las oficinas de la cuales es admin el usuario actual
		$adminoficina = $this->Admin_model->GetOficinasAdmin();
		
		$tdata['titulo'] = 'Time Shift Request form';
		$tdata['oficinas'] = $adminoficina;
		$tdata['perfil'] = $this->session->userdata('perfil');

		$hayrequesthoy = false; //$this->Timeswitch_model->HayRequestsHoy($idempresa,$idoficina,$this->session->userdata('idagente'));
		$registros = $this->Timeswitch_model->ConsultarAgentRequests($idempresa,$idoficina,$this->session->userdata('idagente'));
		$agentes = $this->Agentes_model->StationAgents($idempresa,$idoficina);
		
		$data['monthlyschedule'] = $this->Webcunop_model->ConsultarMonthlySchedule($idempresa,$idoficina,$this->session->userdata('idagente'),$fecha);
		$data['monthlydayoffschedule'] = $this->Webcunop_model->ConsultarDayOffSchedule($idempresa,$idoficina,$this->session->userdata('idagente'),$fecha);
		
		$data['fullagents'] =  $agentes;	
		$data['registros'] = $registros;
		$data['idempresa'] = $idempresa;
		$data['idoficina'] = $idoficina;
		$data['requestshoy'] = $hayrequesthoy;
		$data['shortname'] = $this->session->userdata('shortname');

		if($data)
		{
			//$data = Array('userlist' => $userlist);
			//print_r($userlist);
			if($this->session->userdata('isadmin')!='1')
				$this->load->view('paginas/header_u',$tdata);
			else
				$this->load->view('paginas/header',$tdata);
			$this->load->view('Timeswitch_view',$data);
			$this->load->view('paginas/footer');
		}
	
	}
	
	public function PostSwitchRequest()
	{

		if($this->session->userdata('perfil') == FALSE )
		{
			$error = array('status' => "Failed", "msg" => "La sesion ha expirado. Intente nuevamente.");
			$this->response($this->json($error), 429);
			return;
		}

		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		$idagente = $this->session->userdata('idagente');
		$shortname = $this->session->userdata('shortname');
		$fechacambio = $this->input->post('fechacambiar');
		$fechatarget = $this->input->post('fechatarget');
		$posicioninicial = $this->input->post('posicioninicial');
		$tipocambio = $this->input->post('tipocambio');
		$posicionsolicitada = $this->input->post('posicionsolicitada');
		$agentecambio = $this->input->post('agentecambio');

		$rowagente = $this->Agentes_model->FindAgentByShortname($idempresa, $idoficina, $agentecambio)[0];

		if(!$rowagente)
		{
			$error = array('status' => "Failed", "msg" => "Agent selected for switch is not registered.");
			$this->response($this->json($error), 429);
			return;
		}

		$idagentecambio = $rowagente['idagente'];

		// evaluamos si el agente idcambio no ha sido solicitado por alguien mas ese dia
		$buscar = $this->Timeswitch_model->BuscarAgenteSwitch($idempresa,$idoficina,$idagentecambio,$fechacambio);
		if($buscar)
		{
			$error = array('status' => "Failed", "msg" => "The agent selected for switch has already a request on this day.");
			$this->response($this->json($error), 429);
			return;
		}


		$inserted = $this->Timeswitch_model->PostSwitchRequest($idempresa,$idoficina,$idagente,$shortname,$fechacambio,$fechatarget,$posicioninicial,$tipocambio,$posicionsolicitada,$idagentecambio,$agentecambio);

		if($inserted)
		{
			$error = array('status' => "OK", "msg" => "Switch request has been placed succesfully.");
			$this->response($this->json($error), 400);
			return;
			//$this->Agentes_model->RegistrarNotificacion();
		}


	}

	public function ExPostSwitchRequest()
	{

		if($this->session->userdata('perfil') == FALSE )
		{
			$error = array('status' => "Failed", "msg" => "La sesion ha expirado. Intente nuevamente.");
			$this->response($this->json($error), 429);
			return;
		}

		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		
		$shortname = $this->input->post('shortname');
		$fechacambio = $this->input->post('fechacambiar');
		$fechatarget = $this->input->post('fechatarget');
		$posicioninicial = $this->input->post('posicioninicial');
		$tipocambio = $this->input->post('tipocambio');
		$accepted = $this->input->post('accepted');
		$posicionsolicitada = $this->input->post('posicionsolicitada');
		$agentecambio = $this->input->post('agentecambio');

		$rowsolicitant = $this->Agentes_model->FindAgentByShortname($idempresa, $idoficina, $shortname)[0];
		if(!$rowsolicitant)
		{
			$error = array('status' => "Failed", "msg" => "Agent solicitant for switch is not registered.");
			$this->response($this->json($error), 429);
			return;
		}
		$idagente = $rowsolicitant['idagente'];

		$rowagente = $this->Agentes_model->FindAgentByShortname($idempresa, $idoficina, $agentecambio)[0];

		if(!$rowagente)
		{
			$error = array('status' => "Failed", "msg" => "Agent selected for switch is not registered.");
			$this->response($this->json($error), 429);
			return;
		}

		$idagentecambio = $rowagente['idagente'];

		// evaluamos si el agente idcambio no ha sido solicitado por alguien mas ese dia
		$buscar = $this->Timeswitch_model->BuscarAgenteSwitch($idempresa,$idoficina,$idagentecambio,$fechacambio);
		if($buscar)
		{
			$error = array('status' => "Failed", "msg" => "The agent selected for switch has already a request on this day.");
			$this->response($this->json($error), 429);
			return;
		}


		$inserted = $this->Timeswitch_model->PostSwitchRequest($idempresa,$idoficina,$idagente,$shortname,$fechacambio,$fechatarget,$posicioninicial,$tipocambio,$posicionsolicitada,$idagentecambio,$agentecambio);

		if($inserted && !$accepted)
		{
			$error = array('status' => "OK", "msg" => "Switch request has been placed succesfully.");
			$this->response($this->json($error), 400);
			return;
			//$this->Agentes_model->RegistrarNotificacion();
		}

		if($inserted && $accepted)
		{
			$requestid = $inserted->uniqueid;
			$inserted = $this->Timeswitch_model->AcceptSwitchRequest($idempresa,$idoficina,$requestid);
	
		}
		if($inserted)
		{
			$error = array('status' => "OK", "msg" => "Switch request has been placed succesfully.");
			$this->response($this->json($error), 400);
			return;
			//$this->Agentes_model->RegistrarNotificacion();
		}


	}

	// el agente que acepta el cambio debe revisar el request y aceptarlo
	public function ReviewRequestAgent()
	{
		
		if($this->session->userdata('perfil') == FALSE )
		{
			redirect(base_url().'login');
		}

		$fecha = date('Y-m-d');
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		$requestid = $this->input->get('uid');
		
		// consulta las oficinas de la cuales es admin el usuario actual
		$adminoficina = $this->Admin_model->GetOficinasAdmin();
		
		$tdata['titulo'] = 'Time Shift Accept Request';
		$tdata['oficinas'] = $adminoficina;
		$tdata['perfil'] = $this->session->userdata('perfil');
		$registros = $this->Timeswitch_model->ConsultarAgentRequests($idempresa,$idoficina,$this->session->userdata('idagente'));
		$agentes = $this->Agentes_model->StationAgents($idempresa,$idoficina);
		
		$data['monthlyschedule'] = $this->Webcunop_model->ConsultarMonthlySchedule($idempresa,$idoficina,$this->session->userdata('idagente'),$fecha);
		$data['request'] = $this->Timeswitch_model->ConsultarAgentCambioRequestById($idempresa,$idoficina,$this->session->userdata('idagente'),$requestid);
		$data['fullagents'] =  $agentes;	
		$data['registros'] = $registros;
		$data['idempresa'] = $idempresa;
		$data['idoficina'] = $idoficina;

		if($data)
		{
			//$data = Array('userlist' => $userlist);
			//print_r($userlist);
			if($this->session->userdata('isadmin')!='1')
				$this->load->view('paginas/header_u',$tdata);
			else
				$this->load->view('paginas/header',$tdata);
			$this->load->view('TimeswitchAccept_view',$data);
			$this->load->view('paginas/footer');
		}

	}

	public function AcceptRequestAgent()
	{

		if($this->session->userdata('perfil') == FALSE )
		{
			$error = array('status' => "Failed", "msg" => "La sesion ha expirado. Intente nuevamente.");
			$this->response($this->json($error), 429);
			return;
		}

		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		$idagente = $this->session->userdata('idagente');
		$shortname = $this->session->userdata('shortname');
		$requestid = $this->input->post('requestid');
		

		$requestinfo = $this->Timeswitch_model->LoadRow($idempresa, $idoficina, $requestid);

		if(!$requestinfo)
		{
			$error = array('status' => "Failed", "msg" => "The request provided is not valid");
			$this->response($this->json($error), 429);
			return;
		}

		print_r($requestinfo);
		// validar que este request no haya sido aceptado previamente
		if($requestinfo->fechaacepta != '0000-00-00 00:00:00')
		{
			$error = array('status' => "Failed", "msg" => "The Request was previously accepted.");
			$this->response($this->json($error), 429);
			return;
		}			

		// validar que el agente cambio es que esta aceptando
		if($requestinfo->idagentecambio != $idagente)
		{
			$error = array('status' => "Failed", "msg" => "Current user is not the recipient of the request");
			$this->response($this->json($error), 429);
			return;
		}

		$inserted = $this->Timeswitch_model->AcceptSwitchRequest($idempresa,$idoficina,$requestid);

		if($inserted)
		{
			$error = array('status' => "OK", "msg" => "Request provided was succesfully accepted");
			$this->response($this->json($error), 400);
			return;
			//$this->Agentes_model->RegistrarNotificacion();
		}


	}

	// un supervisor con permisos debe revisar el request previamente aceptado por los dos agentes y autorizarlo
	public function ReviewRequestLead()
	{
		
		if($this->session->userdata('perfil') == FALSE )
		{
			redirect(base_url().'login');
		}

		$fecha = date('Y-m-d');
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		$requestid = $this->input->get('uid');
		
		// consulta las oficinas de la cuales es admin el usuario actual
		$adminoficina = $this->Admin_model->GetOficinasAdmin();
		
		$tdata['titulo'] = 'Time Shift Authorize Request';
		$tdata['oficinas'] = $adminoficina;
		$tdata['perfil'] = $this->session->userdata('perfil');
		
		$data['coordinador'] = ($this->session->userdata('idagente') == '471974' || $this->session->userdata('idagente') == '555092' ) ? 'SI':'NO';
		$request = $this->Timeswitch_model->ConsultarLeadCambioRequestById($idempresa,$idoficina,$requestid);	
		if($request)
		{
			$data['solicitante'] = $this->Agentes_model->LoadAgentId($idempresa,$idoficina,$request->idagente);
		}
		$data['idempresa'] = $idempresa;
		$data['idoficina'] = $idoficina;
		$data['request'] = $request;

		if($data)
		{
			//$data = Array('userlist' => $userlist);
			//print_r($userlist);
			if($this->session->userdata('isadmin')!='1')
				$this->load->view('paginas/header_u',$tdata);
			else
				$this->load->view('paginas/header',$tdata);
			$this->load->view('TimeswitchAuthorize_view',$data);
			$this->load->view('paginas/footer');
		}

	}

	// llamado por ajax para autorizar un request, debe ser un usuario admin
	public function AuthorizeRequest()
	{

		$debug = false;

		if($this->session->userdata('perfil') == FALSE )
		{
			$error = array('status' => "Failed", "msg" => "La sesion ha expirado. Intente nuevamente.");
			$this->response($this->json($error), 429);
			return;
		}

		if($this->session->userdata('perfil') != 'admin' )
		{
			$error = array('status' => "Failed", "msg" => "No tiene permisos para hacer esta autorizacion.");
			$this->response($this->json($error), 429);
			return;
		}

		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		$idagente = $this->session->userdata('idagente');
		$shortname = $this->session->userdata('shortname');
		$requestid = $this->input->post('requestid');
		

		$requestinfo = $this->Timeswitch_model->LoadRow($idempresa, $idoficina, $requestid);

		if(!$requestinfo)
		{
			$error = array('status' => "Failed", "msg" => "The request provided is not valid");
			$this->response($this->json($error), 429);
			return;
		}

		if($debug)
			print_r($requestinfo);
		// validar que este request no haya sido aceptado previamente
		if($requestinfo->fechaacepta != '0000-00-00 00:00:00' && $requestinfo->status != 'ACC')
		{
			$error = array('status' => "Failed", "msg" => "The Request was not previously accepted by the receiving agent.");
			$this->response($this->json($error), 429);
			return;
		}			

		// validar que el agente cambio es que esta aceptando
		if($requestinfo->idleadautoriza != '')
		{
			$error = array('status' => "Failed", "msg" => "Current Request was previously authorized by " . $requestinfo->leadautoriza);
			$this->response($this->json($error), 429);
			return;
		}

		// cargamos el perfil de los agentes
		$perfilsolicita = $this->Agentes_model->LoadAgentId($idempresa,$idoficina,$requestinfo->idagente);

		$perfildestino = $this->Agentes_model->LoadAgentId($idempresa,$idoficina,$requestinfo->idagentecambio);

		if($debug)
		{
			echo 'perfil solicita ' . PHP_EOL;
			print_r($perfilsolicita);

			echo 'perfil destino ' . PHP_EOL;
			print_r($perfildestino);
		}

		// se autoriza el cambio
		$inserted = $this->Timeswitch_model->AuthorizeSwitchRequest($idempresa,$idoficina,$requestid,$idagente,$shortname);

		if($inserted)
		{

			// consultamos la asignacion actual del solicitante para esa fecha (puede tener mas de una asignacion)
			$agentesolicita = $this->Webcunop_model->ConsultarRegistroAgenteFechaPos($idempresa,$idoficina,$requestinfo->idagente,$requestinfo->fechacambio,$requestinfo->posicioninicial);

			if($debug)
			{
				echo 'Agente Solicita ' .PHP_EOL;
				print_r($agentesolicita);
			}

			$possolicitada = $requestinfo->posicionsolicitada == '' ? 'XX' : $requestinfo->posicionsolicitada;

			// consultamos la asignacion actual del destinatario para esa fecha (puede tener mas de una asignacion)
			$agentedestino = $this->Webcunop_model->ConsultarRegistroAgenteFechaPos($idempresa,$idoficina,$requestinfo->idagentecambio,$requestinfo->fechacambio,$possolicitada);



			if($debug)
			{
				echo 'Agente Destino ' .PHP_EOL;
				print_r($agentedestino);
			}

			// Dependiendo del tipo de cambio se ejecuta 
			if($requestinfo->tipocambio == 'Switch')
			{
				/* 
				** cambiar agente solicitante
				*/

				// El switch no permite escribir directamente al scheduler la posicion nueva del solicitante, por duplicidad de campos, debemos
				//   triangular

				$asignacion = '';
				
				// eliminamos el schedule inicial (para evitar error de duplicidad de registros en index idempresa-idoficina-idagente-fecha-posicion)
				if($perfilsolicita[0]['puesto']=='LEAD')
				{
					$this->Webcunop_model->DeleteLeadSchedulerUniqueid($idempresa,$idoficina,$agentesolicita->uniqueid);
				}
				else
				{
					$this->Webcunop_model->DeleteAgenteSchedulerUniqueid($idempresa,$idoficina,$agentesolicita->uniqueid);
				}

				//  2. inserta nueva asignacion al lead / agente
				if($perfilsolicita[0]['puesto']=='LEAD')
				{
					$this->Loadleadsmensual_model->ActualizarScheduleLead($idempresa, $idoficina, $agentesolicita->idagente, $requestinfo->fechacambio, $agentedestino->workday, $requestinfo->shortname, $asignacion, $requestinfo->posicionsolicitada);
				}
				else
				{
					$this->Loadimpresionmensual_model->ActualizarScheduleAgente($idempresa, $idoficina, $agentesolicita->idagente, $requestinfo->fechacambio, $agentedestino->workday, $requestinfo->shortname, $asignacion, $requestinfo->posicionsolicitada);
				}

				$this->Webcunop_model->RegistrarBitacora($idempresa,$idoficina,$requestinfo->fechacambio,'',$requestinfo->shortname,$requestinfo->posicioninicial,$requestinfo->agentecambio,$requestinfo->posicionsolicitada,$shortname);
				

				// 2. hace switch destino -> solicita 
				if($perfilsolicita[0]['puesto']=='LEAD')
				{
					// se cambia el schedule del usuario que solicita
					$insert = $this->Webcunop_model->PostCambioScheduleLead($idempresa, $idoficina, $agentedestino->uniqueid, $requestinfo->fechacambio, $requestinfo->idagentecambio, $requestinfo->agentecambio, $requestinfo->posicioninicial, $shortname);
				}
				else
				{
					// se cambia el schedule del usuario que solicita
					$insert = $this->Webcunop_model->PostCambioSchedule($idempresa, $idoficina, $agentedestino->uniqueid, $requestinfo->fechacambio, $requestinfo->idagentecambio, $requestinfo->agentecambio, $requestinfo->posicioninicial, $shortname);	
				}
				
				$this->Webcunop_model->RegistrarBitacora($idempresa,$idoficina,$requestinfo->fechacambio,'',$requestinfo->agentecambio,$requestinfo->posicionsolicitada,$requestinfo->shortname,$requestinfo->posicioninicial,$shortname);
	
				// llamamos el proceso de process dates
				$this->Processdates($idempresa,$idoficina,$requestinfo->fechacambio,'CUN',$this->session->userdata('shortname'));

				$error = array('status' => "OK", "msg" => "Request provided was succesfully authorized.");
				$this->response($this->json($error), 400);
				return;
			}

			if($requestinfo->tipocambio == 'Cover')
			{

				/*
				**   En el Cubre, el agente destino no pierde su horario, se agrega a su turno y el solicitante queda libre de esa asignacion
				*/


				// El switch no permite escribir directamente al scheduler la posicion nueva del solicitante, por duplicidad de campos, debemos
				//   triangular

				$asignacion = '';

				// eliminamos el schedule inicial del agente que solicita
				if($perfilsolicita[0]['puesto']=='LEAD')
				{
					$this->Webcunop_model->DeleteLeadSchedulerUniqueid($idempresa,$idoficina,$agentesolicita->uniqueid);
				}
				else
				{
					$this->Webcunop_model->DeleteAgenteSchedulerUniqueid($idempresa,$idoficina,$agentesolicita->uniqueid);
				}
				$this->Webcunop_model->RegistrarBitacora($idempresa,$idoficina,$requestinfo->fechacambio,'',$requestinfo->shortname,$requestinfo->posicioninicial,$requestinfo->agentecambio,$requestinfo->posicionsolicitada,$shortname);

				//  2. inserta nueva asignacion al lead / agente destino
				if($perfilsolicita[0]['puesto']=='LEAD')
				{
						// ActualizarScheduleLead($idempresa, $idoficina, $idagente, $fecha, $workday, $shortname, $asignacion, $posicion)
					$this->Loadleadsmensual_model->ActualizarScheduleLead($idempresa, $idoficina, $requestinfo->idagentecambio, $requestinfo->fechacambio, $agentedestino->workday, $requestinfo->agentecambio, $asignacion, $requestinfo->posicioninicial);
				}
				else
				{

					$this->Loadimpresionmensual_model->ActualizarScheduleAgente($idempresa, $idoficina, $requestinfo->idagentecambio, $requestinfo->fechacambio, $agentedestino->workday, $requestinfo->agentecambio, $asignacion, $requestinfo->posicioninicial);
				}

				// se le asigna un descanso
				$possolicitada = $requestinfo->posicionsolicitada == '' ? 'XX' : 'XX';
				//  3. inserta una asignacio de descanso
				if($perfilsolicita[0]['puesto']=='LEAD')
				{
					// ActualizarScheduleLead($idempresa, $idoficina, $idagente, $fecha, $workday, $shortname, $asignacion, $posicion)
					
					$this->Loadleadsmensual_model->ActualizarScheduleLead($idempresa, $idoficina, $requestinfo->idagente, $requestinfo->fechacambio, $agentedestino->workday, $requestinfo->shortname, $asignacion, $possolicitada);
				}
				else
				{

					$this->Loadimpresionmensual_model->ActualizarScheduleAgente($idempresa, $idoficina, $requestinfo->idagente, $requestinfo->fechacambio, $agentedestino->workday, $requestinfo->shortname, $asignacion, $possolicitada);
				}

				$this->Webcunop_model->RegistrarBitacora($idempresa,$idoficina,$requestinfo->fechacambio,'',$requestinfo->shortname,$requestinfo->posicioninicial,$requestinfo->agentecambio,$possolicitada,$shortname);


				// llamamos el proceso de process dates
				$this->Processdates($idempresa,$idoficina,$requestinfo->fechacambio,'CUN',$this->session->userdata('shortname'));

				

				$error = array('status' => "OK", "msg" => "Request provided was succesfully authorized.");
				$this->response($this->json($error), 400);
				return;
			}

			if($requestinfo->tipocambio == 'Day Off')
			{

				if($debug){
					echo 'requestinfo' . PHP_EOL;
					print_r($requestinfo);
				}
				// consultamos la asignacion actual del solicitante para esa fecha (puede tener mas de una asignacion)
				$agentesolicita1 = $this->Webcunop_model->ConsultarRegistroAgenteFecha($idempresa,$idoficina,$requestinfo->idagente,$requestinfo->fechacambio);

				// consultamos la asignacion actual del destinatario para esa fecha (puede tener mas de una asignacion)
				$agentedestino1 = $this->Webcunop_model->ConsultarRegistroAgenteFecha($idempresa,$idoficina,$requestinfo->idagentecambio,$requestinfo->fechacambio);

				$agentesolicita2 = $this->Webcunop_model->ConsultarRegistroAgenteFecha($idempresa,$idoficina,$requestinfo->idagente,$requestinfo->fechatarget);

				// consultamos la asignacion actual del destinatario para esa fecha (puede tener mas de una asignacion)
				$agentedestino2 = $this->Webcunop_model->ConsultarRegistroAgenteFecha($idempresa,$idoficina,$requestinfo->idagentecambio,$requestinfo->fechatarget);

				if($debug)
				{
						echo 'agentesolicita1' . PHP_EOL;
						print_r($agentesolicita1);

						echo 'agentedestino1' . PHP_EOL;
						print_r($agentedestino1);

						echo 'agentesolicita2' . PHP_EOL;
						print_r($agentesolicita2);

						echo 'agentedestino2' . PHP_EOL;
						print_r($agentedestino2);
				}

				$asignacion = '';


				// paso 1, asignar descanso al agente solicitante

				if($perfilsolicita[0]['puesto']=='LEAD')
				{

					if(!is_null($agentesolicita1))
					{
						if($debug)
						echo '1/ LEAD Post cambio XX ' . $requestinfo->idagente  . ' ' . $requestinfo->fechacambio . PHP_EOL;
						$this->Webcunop_model->PostCambioScheduleLead($idempresa, $idoficina, $agentesolicita1->uniqueid, $requestinfo->fechacambio, $requestinfo->idagente, $requestinfo->shortname, 'XX', $shortname);
					}
					else
					{
						if($debug)
						echo '1. LEAD actualizar cambio XX ' . $requestinfo->idagente  . ' ' . $requestinfo->fechacambio . PHP_EOL;
						$this->Loadleadsmensual_model->ActualizarScheduleLead($idempresa, $idoficina, $agentesolicita1->idagente, $requestinfo->fechacambio, $perfilsolicita->jornada, $requestinfo->shortname, $asignacion, 'XX');
					}
				}
				else
				{
					// se cambia el schedule del usuario que solicita
					if(!is_null($agentesolicita1))
					{
						if($debug)
						echo '1. Post cambio XX ' . $requestinfo->idagente  . ' ' . $requestinfo->fechacambio . PHP_EOL;
						$this->Webcunop_model->PostCambioSchedule($idempresa, $idoficina, $agentesolicita1->uniqueid, $requestinfo->fechacambio, $requestinfo->idagente, $requestinfo->shortname, 'XX', $shortname);	
					}
					else
					{
						if($debug)
						echo '1. Actualizar cambio XX ' . $requestinfo->idagente  . ' ' . $requestinfo->fechacambio . PHP_EOL;
						$this->Loadimpresionmensual_model->ActualizarScheduleAgente($idempresa, $idoficina, $agentesolicita1->idagente, $requestinfo->fechacambio, $perfilsolicita->jornada, $requestinfo->shortname, $asignacion, 'XX');
					}

				}
				
				//$this->Webcunop_model->RegistrarBitacora($idempresa,$idoficina,$requestinfo->fechacambio,'',$requestinfo->agentecambio,$requestinfo->posicionsolicitada,$requestinfo->shortname,$requestinfo->posicioninicial,$shortname);


				// paso 2, asignar la posicion inicial al agente destino
				if($perfildestino[0]['puesto']=='LEAD')
				{

					if(!is_null($agentedestino1))
					{
						if($debug)
						echo '2. LEAD Post cambio ' . $requestinfo->posicioninicial . ' ' . $requestinfo->idagentecambio  . ' ' . $requestinfo->fechacambio . PHP_EOL;
						$this->Webcunop_model->PostCambioScheduleLead($idempresa, $idoficina, $agentedestino1->uniqueid, $requestinfo->fechacambio, $requestinfo->idagentecambio, $requestinfo->agentecambio, $requestinfo->posicioninicial, $shortname);
					}
					else
					{
						if($debug)
						echo '2. LEAD actualizar cambio ' . $requestinfo->posicioninicial . ' ' . $requestinfo->idagentecambio  . ' ' . $requestinfo->fechacambio . PHP_EOL;
						$this->Loadleadsmensual_model->ActualizarScheduleLead($idempresa, $idoficina, $agentedestino1->idagente, $requestinfo->fechacambio, $perfildestino->jornada, $requestinfo->agentecambio, $asignacion, $requestinfo->posicioninicial);
					}
				}
				else
				{
					// se cambia el schedule del usuario que solicita
					if(!is_null($agentedestino1))
					{
						if($debug)
						echo '2. Post cambio ' . $requestinfo->posicioninicial . ' ' . $requestinfo->idagentecambio  . ' ' . $requestinfo->fechacambio . PHP_EOL;
						$this->Webcunop_model->PostCambioSchedule($idempresa, $idoficina, $agentedestino1->uniqueid, $requestinfo->fechacambio, $requestinfo->idagentecambio, $requestinfo->agentecambio, $requestinfo->posicioninicial, $shortname);	
					}
					else
					{
						if($debug)
						echo '2. Actualizar cambio ' . $requestinfo->posicioninicial . ' ' . $requestinfo->idagentecambio  . ' ' . $requestinfo->fechacambio . PHP_EOL;
						$this->Loadimpresionmensual_model->ActualizarScheduleAgente($idempresa, $idoficina, $perfildestino[0]['idagente'], $requestinfo->fechacambio, $perfildestino[0]['jornada'], $requestinfo->agentecambio, $asignacion, $requestinfo->posicioninicial);
					}

				}

				// paso 3, asignar la posicion 2 al agente solicitante en la fecha target
				if($perfilsolicita[0]['puesto']=='LEAD')
				{

					if(!is_null($agentesolicita2))
					{
						if($debug)
						echo '3. LEAD Post cambio ' . $agentedestino2->posicion . ' ' . $requestinfo->idagente  . ' ' . $requestinfo->fechatarget . PHP_EOL;
						$this->Webcunop_model->PostCambioScheduleLead($idempresa, $idoficina, $agentesolicita2->uniqueid, $requestinfo->fechatarget, $requestinfo->idagente, $requestinfo->shortname, $agentedestino2->posicion, $shortname);
					}
					else
					{
						if($debug)
						echo '3. LEAD actualizar cambio ' . $agentedestino2->posicion . ' ' . $requestinfo->idagente  . ' ' . $requestinfo->fechatarget . PHP_EOL;
						$this->Loadleadsmensual_model->ActualizarScheduleLead($idempresa, $idoficina, $agentesolicita2->idagente, $requestinfo->fechatarget, $perfilsolicita->jornada, $requestinfo->shortname, $asignacion, $agentedestino2->posicion);
					}
				}
				else
				{
					// se cambia el schedule del usuario que solicita
					if(!is_null($agentesolicita2))
					{
						if($debug)
						echo '3. Post cambio ' . $agentedestino2->posicion . ' ' . $requestinfo->idagente  . ' ' . $requestinfo->fechatarget . PHP_EOL;
						$this->Webcunop_model->PostCambioSchedule($idempresa, $idoficina, $agentesolicita2->uniqueid, $requestinfo->fechatarget, $requestinfo->idagente, $requestinfo->shortname, $agentedestino2->posicion, $shortname);	
					}
					else
					{
						if($debug)
						echo '3. Actualizar cambio ' . $requestinfo->posicionsolicitada . ' ' . $requestinfo->idagente  . ' ' . $requestinfo->fechatarget . PHP_EOL;
						$this->Loadimpresionmensual_model->ActualizarScheduleAgente($idempresa, $idoficina, $agentesolicita2->idagente, $requestinfo->fechatarget, $perfilsolicita[0]['jornada'], $requestinfo->shortname, $asignacion, $agentedestino2->posicion);
					}

				}

				// paso 4, asignar descanso al agente solicitante en la fecha target
				if($perfildestino[0]['puesto']=='LEAD')
				{

					if(!is_null($agentedestino2))
					{
						if($debug)
						echo '4. LEAD Post cambio ' . $agentesolicita2->posicion . ' ' . $requestinfo->idagentecambio  . ' ' . $requestinfo->fechatarget . PHP_EOL;
						$this->Webcunop_model->PostCambioScheduleLead($idempresa, $idoficina, $agentedestino2->uniqueid, $requestinfo->fechatarget, $requestinfo->idagentecambio, $requestinfo->agentecambio, $agentesolicita2->posicion, $shortname);
					}
					else
					{
						if($debug)
						echo '4. LEAD actualizar cambio ' . $agentesolicita2->posicion . ' ' . $requestinfo->idagentecambio  . ' ' . $requestinfo->fechatarget . PHP_EOL;
						$this->Loadleadsmensual_model->ActualizarScheduleLead($idempresa, $idoficina, $agentedestino2->idagente, $requestinfo->fechatarget, $perfildestino[0]['jornada'], $requestinfo->shortname, $asignacion, $agentesolicita2->posicion);
					}
				}
				else
				{
					// se cambia el schedule del usuario que solicita
					if(!is_null($agentedestino2))
					{
						if($debug)
						echo '4. Post cambio ' . $agentesolicita2->posicion . ' ' . $requestinfo->idagentecambio  . ' ' . $requestinfo->fechatarget . PHP_EOL;
						$this->Webcunop_model->PostCambioSchedule($idempresa, $idoficina, $agentedestino2->uniqueid, $requestinfo->fechatarget, $requestinfo->idagentecambio, $requestinfo->agentecambio, $agentesolicita2->posicion, $shortname);	
					}
					else
					{
						if($debug)
						echo '4. Actualizar cambio ' . $agentesolicita2->posicion . ' ' . $requestinfo->idagentecambio  . ' ' . $requestinfo->fechatarget . PHP_EOL;
						$this->Loadimpresionmensual_model->ActualizarScheduleAgente($idempresa, $idoficina, $agentedestino2->idagente, $requestinfo->fechatarget, $perfildestino[0]['jornada'], $requestinfo->agentecambio, $asignacion, $agentesolicita2->posicion);
					}

				}



				/* 
				** cambiar agente solicitante
				*/

				// El switch no permite escribir directamente al scheduler la posicion nueva del solicitante, por duplicidad de campos, debemos
				//   triangular

				

				/*
				// eliminamos el schedule inicial (para evitar error de duplicidad de registros en index idempresa-idoficina-idagente-fecha-posicion)
				if($perfilsolicita[0]['puesto']=='LEAD')
				{
					$this->Webcunop_model->DeleteLeadSchedulerUniqueid($idempresa,$idoficina,$agentesolicita->uniqueid);
				}
				else
				{
					$this->Webcunop_model->DeleteAgenteSchedulerUniqueid($idempresa,$idoficina,$agentesolicita->uniqueid);
				}

				//  2. inserta nueva asignacion al lead / agente
				if($perfilsolicita[0]['puesto']=='LEAD')
				{
					$this->Loadleadsmensual_model->ActualizarScheduleLead($idempresa, $idoficina, $agentesolicita->idagente, $requestinfo->fechacambio, $agentedestino->workday, $requestinfo->shortname, $asignacion, $requestinfo->posicionsolicitada);
				}
				else
				{
					$this->Loadimpresionmensual_model->ActualizarScheduleAgente($idempresa, $idoficina, $agentesolicita->idagente, $requestinfo->fechacambio, $agentedestino->workday, $requestinfo->shortname, $asignacion, $requestinfo->posicionsolicitada);
				}

				$this->Webcunop_model->RegistrarBitacora($idempresa,$idoficina,$requestinfo->fechacambio,'',$requestinfo->shortname,$requestinfo->posicioninicial,$requestinfo->agentecambio,$requestinfo->posicionsolicitada,$shortname);
				

				// 2. hace switch destino -> solicita 
				if($perfildestino[0]['puesto']=='LEAD')
				{
					// se cambia el schedule del usuario que solicita
					$insert = $this->Webcunop_model->PostCambioScheduleLead($idempresa, $idoficina, $agentedestino->uniqueid, $requestinfo->fechacambio, $requestinfo->idagentecambio, $requestinfo->agentecambio, $requestinfo->posicioninicial, $shortname);
				}
				else
				{
					// se cambia el schedule del usuario que solicita
					$insert = $this->Webcunop_model->PostCambioSchedule($idempresa, $idoficina, $agentedestino->uniqueid, $requestinfo->fechacambio, $requestinfo->idagentecambio, $requestinfo->agentecambio, $requestinfo->posicioninicial, $shortname);	
				}
				
				$this->Webcunop_model->RegistrarBitacora($idempresa,$idoficina,$requestinfo->fechacambio,'',$requestinfo->agentecambio,$requestinfo->posicionsolicitada,$requestinfo->shortname,$requestinfo->posicioninicial,$shortname);

				$error = array('status' => "OK", "msg" => "Request provided was succesfully authorized.");
				$this->response($this->json($error), 400);
				return;

				/* 
				** cambiar agente solicitante
				*/

				/*
				// se cambia el schedule del usuario que solicita
				$cambio = $this->Webcunop_model->PostCambioSchedule($idempresa, $idoficina, $agentesolicita->uniqueid, $requestinfo->fechacambio, $requestinfo->idagentecambio, $requestinfo->agentecambio, $requestinfo->posicioninicial, $shortname);

				//$shortname_old = $agenteActual[0]['shortname'];
				$this->Webcunop_model->RegistrarBitacora($idempresa,$idoficina,$requestinfo->fechacambio,'',$requestinfo->shortname,$requestinfo->posicioninicial,$requestinfo->agentecambio,$requestinfo->posicionsolicitada,$shortname);
				*/
				// llamamos el proceso de process dates
				$this->Processdates($idempresa,$idoficina,$requestinfo->fechacambio,'CUN',$this->session->userdata('shortname'));
				
				$error = array('status' => "OK", "msg" => "Request provided was succesfully authorized.");
				$this->response($this->json($error), 400);
				return;
				
			}



			//$this->Agentes_model->RegistrarNotificacion();
		}


	}

	public function deleteRequest()
	{
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		$idagente  = $this->session->userdata('idagente');
		$idrequest = $this->input->post('idrequest');

		$data = $this->Timeswitch_model->executeDeleteRequest($idempresa,$idoficina,$idagente,$idrequest);
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($data);

	}

	public function declineRequest()
	{
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		$idagente  = $this->session->userdata('idagente');
		$shortname = $this->session->userdata('shortname');

		$idrequest = $this->input->post('requestid');
		$reason = $this->input->post('reason');

		$data = $this->Timeswitch_model->executeDeclineRequest($idempresa,$idoficina,$idagente,$shortname,$idrequest, $reason);
		$error = array('status' => "OK", "msg" => "Request provided was succesfully marked as declined.");
		$this->response($this->json($error), 400);

	}

	public function adminRemoveRequest()
	{
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		$idagente  = $this->input->post('idagente');
		$requestid = $this->input->post('requestid');

		$data = $this->Timeswitch_model->executeDeleteRequest($idempresa,$idoficina,$idagente,$requestid);
		$error = array('status' => "OK", "msg" => "Request provided was succesfully deleted.");
		$this->response($this->json($error), 400);

	}

	public function getAgentsSwitchDate()
	{
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		if($this->input->post('agente')){
			$agente = $this->input->post('agente');
			$rowagent = $this->Agentes_model->FindAgentByShortname($idempresa, $idoficina, $agente)[0];
			$idagentebase = $rowagent['idagente'];
		}
		else
			$idagentebase = $this->session->userdata('idagente');
		$fecha = $this->input->post('fechacambiar');

		$data = $this->Timeswitch_model->getAgentsSwitchDate($idempresa,$idoficina,$fecha,$idagentebase);
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($data);
	}

	public function getAgentsCoverDate()
	{
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		if($this->input->post('agente')){
			$agente = $this->input->post('agente');
			$rowagent = $this->Agentes_model->FindAgentByShortname($idempresa, $idoficina, $agente)[0];
			$idagentebase = $rowagent['idagente'];
			$puesto = $rowagent['puesto'];
			$jornada = $rowagent['jornada'];

		}
		else
		{
			$idagentebase = $this->session->userdata('idagente');
			$puesto = $this->session->userdata('puesto');
			$jornada = $this->session->userdata('jornada');

		}
		$fecha = $this->input->post('fechacambiar');
		/*
		$userpos = $this->input->post('userposicion');
		$userjornada = $this->input->post('userjornada');

		switch($jornada)
		{
			case 'PT' :
				$posiciones = "workday in ('PT6','PT4') ";
				break;
			case 'PT6' :
				$posiciones = "workday in ('PT4')";
			case 'FT' :
				$posiciones = "true";
				break;
		}*/
		

		//echo 'puesto ' . $puesto;
		//$data = $this->Timeswitch_model->getAgentsCoverDate($idempresa,$idoficina,$fecha,$posiciones,$userpos,$userjornada);
		$data = $this->Timeswitch_model->getAgentsCoverDate($idempresa,$idoficina,$fecha,$idagentebase,$puesto);
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($data);
	}

	public function getAgentsDayOffDate()
	{
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		$jornada = $this->session->userdata('jornada');
		$fecha = $this->input->post('fechacambiar');

		$data = $this->Timeswitch_model->getAgentsDayOffDate($idempresa,$idoficina,$fecha);
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($data);
	}

	public function getAgentDaysOffDate()
	{
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		if($this->input->post('agente')){
			$agente = $this->input->post('agente');
			$rowagent = $this->Agentes_model->FindAgentByShortname($idempresa, $idoficina, $agente)[0];
			$idagente = $rowagent['idagente'];
		}
		else
			$idagente = $this->session->userdata('idagente');
		$fecha = $this->input->post('fechacambiar');
		$data = $this->Webcunop_model->ConsultarDayOffSchedule($idempresa,$idoficina,$idagente,$fecha);
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($data);
	}

	public function AsyncMonthlySchedule()
	{
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		$shortname = $this->input->post('shortname');

		$rowagent = $this->Agentes_model->FindAgentByShortname($idempresa, $idoficina, $shortname);

		$data = $this->Webcunop_model->ConsultarMonthlySchedule($idempresa,$idoficina,$rowagent[0]['idagente'],date('Y-m-d'));
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($data);
	}

	public function Asynclist()
	{
		$empresa = $this->input->post('idempresa');
		$oficina = $this->input->post('idoficina');
		$data = $this->Castigados_model->ConsultarCastigadosEstacion($empresa,$oficina);
		
		$this->response($this->json($data), 400);

	}

	public function consultarQuickSchedule()
	{
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		$fecha = $this->input->post('fechacambiar');
		$agente = $this->input->post('agente');

		$rowagente = $this->Agentes_model->FindAgentByShortname($idempresa, $idoficina, $agente);

		if($rowagente)
		{
			$idagente = $rowagente[0]['idagente'];
			$data = $this->Webcunop_model->ConsultarQuickSchedule($idempresa,$idoficina,$idagente,$fecha);
			if($data)
			{
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($data[0]);	
			}
			else
			{
				header('Content-type: application/json; charset=utf-8');
				echo json_encode(Array('posicion' => 'XX'));
			}
		}

		
	}


	// process date para los cambios autorizados
	private function Processdates($idempresa,$idoficina,$fecha,$estacion,$usuario)
	{

		$timezone = -5;
		$debug=false;

		if($debug)
			echo 'incoming ' . $idempresa . ', ' . $idoficina . ', ' . $fecha . ', ' . $estacion . ', ' . $usuario . ', ' . $timezone . PHP_EOL;
		

		//  limpiamos el distribvuelos y distribvuelosagentes para fecha
		$this->Fillcunopdate_model->cleanDate($idempresa,$idoficina,$fecha);

		// cargamos los vuelos existentes
		$vueloslist = $this->Vuelos_model->LoadVuelos($estacion, $fecha);
		$linea = 0;

		// creamos un pool de agentes
		$agentpool = array();
		$maxagentspergate = 2;
		$extraagentspergate = 2;
		
		foreach ($vueloslist as $vuelo) {
			
			// vamos a evaluar si el vuelo existe ese dia de la semana
			$weekday = date('w',strtotime($fecha));
			$weekdayisok  = false;
			$posday = '';
			
			switch ($weekday) {
				case 0:
					if($vuelo['dom'] == 1) $weekdayisok = true;
					$posday = 'possun';
					break;
				case 1:
					if($vuelo['lun'] == 1) $weekdayisok = true;
					$posday = 'posmon';
					break;
				case 2:
					if($vuelo['mar'] == 1) $weekdayisok = true;
					$posday = 'postue';
					break;
				case 3:
					if($vuelo['mie'] == 1) $weekdayisok = true;
					$posday = 'poswed';
					break;
				case 4:
					if($vuelo['jue'] == 1) $weekdayisok = true;
					$posday = 'posthu';
					break;
				case 5:
					if($vuelo['vie'] == 1) $weekdayisok = true;
					$posday = 'posfri';
					break;
				case 6:
					if($vuelo['sab'] == 1) $weekdayisok = true;
					$posday = 'possat';
					break;
			}
			
			if($weekdayisok == true)
			{
				// obtenemos la posicion que le corresponde al vuelo
				$posiciones = $this->Posicionesvuelos_model->LoadPosicionesVuelo($idempresa, $idoficina, $vuelo['idvuelo']);
				if($debug){
					echo '==>vuelo ' . $vuelo['idvuelo'] . '=' . sizeof($posiciones) . PHP_EOL;
				}

				$cagents = 1;
				
				foreach($posiciones as $thispos)
				{
					$posicion = $thispos[$posday];

					if($debug){
						echo '-->' . $posicion . PHP_EOL;
					}
					
					// traemos los agentes para ese vuelo
					$agentesdisp = $this->Fillcunopdate_model->LoadAgentesScheduleDatePosicion($idempresa, $idoficina, $fecha, $posicion);

					$lead= 'RH';
					$hora = intval($vuelo['horasalida']);
					$horasalida = intval($hora) + (intval($timezone) * 60);
					$hora = intval($horasalida / 3600) ;
					$minutes = (($horasalida / 3600) - $hora) * 60;
					$stime = ($hora<=9?('0' . $hora) : $hora) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
					//echo '-- vuelo ' . $vuelo['idvuelo'] . ' fecha ' . $fecha . ' salida ' . $hora . ':' . $minutes . '<br>';
					$this->Fillcunopdate_model->SetFlightHeader($idempresa,$idoficina,$vuelo['idvuelo'],$fecha,$stime,$lead,$usuario);

					$asignadosvuelo = array();

					if($agentesdisp)
					foreach ($agentesdisp as $agente) {
						if($debug)
						{
							echo 'vuelo ' . $vuelo['idvuelo'] . ' posicion ' . $posicion . ' agente ' . $agente['shortname'] . PHP_EOL;
							echo 'cagents ' . $cagents . PHP_EOL;
							echo 'agentpool ' . sizeof($agentpool) . PHP_EOL;
						}
					

						if($cagents<=$maxagentspergate )
						{
							// cada sala permite x agentes 
							$this->Fillcunopdate_model->SetAgentScheduleDay($idempresa, $idoficina, $fecha, $vuelo['idvuelo'],$linea,$agente['shortname'],$posicion,$usuario);
							// agregamos el agente al vuelo
							array_push($asignadosvuelo,$agente['shortname']);	
						}
						else
							{
								if($debug){
									echo '-- asignando extras --' . PHP_EOL;
								}
								// ya asigno los agentes principales, ahora asigna los extras
								if($cagents <= $maxagentspergate + $extraagentspergate)
								{
									// si el agente actual esta en el pool, entonces es de los que sobran, y se asigna
									$tmp = 0;
									$found = false;
									if($debug){
										print_r($agentpool);
									}
									foreach($agentpool as $tmpagent)
									{
										if($debug){
											echo '   evaluando el agente del pool ' . $tmpagent['shortname'] . ' vs ' . $agente['shortname'] . ' ' . $agente['posicion'] . PHP_EOL;
										}
										
										// revisando cada agente del pool
										if($tmpagent['shortname'] != $agente['shortname'] && $tmpagent['posicion'] == $agente['posicion'] && !in_array($tmpagent['shortname'],$asignadosvuelo))
										{
											if($debug){
												echo '  <-- agente del pool ingresado ' . $tmpagent['shortname'] . PHP_EOL;
											}
											// es el mismo agente, entonces lo agrega
											$this->Fillcunopdate_model->SetAgentScheduleDay($idempresa, $idoficina, $fecha, $vuelo['idvuelo'],$linea,$tmpagent['shortname'],$posicion,$usuario);

											// elimina el agente del pool
											unset($agentpool[$tmp]);
											$found = true;
											break 2;
										}
										$tmp++;
									}	
									if(!$found)
									{
										if($debug){
											echo '  <-- agente disponible ' . $agente['shortname'] . PHP_EOL;
										}
										// es el mismo agente, entonces lo agrega
										$this->Fillcunopdate_model->SetAgentScheduleDay($idempresa, $idoficina, $fecha, $vuelo['idvuelo'],$linea,$agente['shortname'],$posicion,$usuario);
										array_push($asignadosvuelo,$agente['shortname']);
									}
								}
								else
								{
									
									// los agentes sobrantes se agregan al pool
									array_push($agentpool,$agente);	

									if($debug){
										echo 'add to pool ' . sizeof($agentpool) . ' ' . $agente['shortname'] . PHP_EOL;
									}
								}
								
							}
						$cagents ++;
					}
				}
				$linea++;
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

