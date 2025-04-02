<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 * 
 */
class Config extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('Config_model');
		$this->load->model('Agentes_model');
		$this->load->model('Login_model');
		$this->load->library(array('session'));
		$this->load->helper(array('url'));

		
        //echo 'pre phpmailer<br/>' . __DIR__;
       	require('PHPMailer-master/PHPMailerAutoload.php');
        
	}
	
	public function index()
	{
		if($this->session->userdata('perfil') == FALSE || $this->session->userdata('isadmin') != 1)
		{
			redirect(base_url().'login');
		}
		
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		$agenteslist = $this->Agentes_model->StationAgents($idempresa,$idoficina);

		$data['idempresa'] = $idempresa;
		$data['idoficina'] = $idoficina;
		$data['agentes'] = $agenteslist;

		$data['empresas'] = $this->Config_model->GetEmpresas();
		$tdata['perfil'] = $this->session->userdata('perfil');
		$this->load->view('paginas/header',$data);
		$this->load->view('Config_view',$data);
		$this->load->view('paginas/footer',$data);
		
	}
	
	public function LoadOficinasEmpresa()
	{
		
		$empresa = $this->input->post('empresa');
		
		$data = $this->Config_model->GetOficinasEmpresa($empresa);
		header('Content-type: application/json; charset=utf-8');
		print_r(json_encode($data));

		
	}

	public function SimulateSession(){
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		$idagente = $this->input->post('inputAgent');

		if($this->session->userdata('isadmin')){

			$check_user = $this->Login_model->SimulateSession($idempresa, $idoficina, $idagente);

			if($check_user){

				// save current user data
				$curr_idempresa = $this->session->userdata('idempresa');
				$curr_idoficina = $this->session->userdata('idoficina');
				$curr_idagente = $this->session->userdata('idagente');
				$this->session->set_userdata([
					'curr_idempresa' => $curr_idempresa,
					'curr_idoficina' => $curr_idoficina,
					'curr_idagente' => $curr_idagente
				]);

				// obtiene la version del webapp
				$versionweb = $this->Config_model->GetEmpresas()[0]['versionweb'];

				// registra el acceso
				$user = $this->Login_model->log_signin($check_user->shortname, $versionweb, 'simulate');

				// obtiene la oficina a la que pertenece el usuario
				$oficina = $this->Login_model->getOficinaAdmin($check_user->idempresa, $check_user->idoficina);

				$isadmin = $this->Agentes_model->IsAdmin($check_user->idempresa,$check_user->idoficina,$check_user->uniqueid);
				
				$data = array(
					'is_logued_in' 	=> 	TRUE,
					'idempresa'	=>	$check_user->idempresa,
					'idoficina'	=>	$check_user->idoficina,
					'iatacode'	=>	$oficina->iatacode,
					'idagente' 	=> 	$check_user->idagente,
					'perfil'	=>	$check_user->perfil ?? 'usuario',
					'email' 	=> 	$check_user->email,
					'jornada' 	=> 	$check_user->jornada,
					'puesto'	=>	$check_user->puesto,
					'isadmin'	=>	$isadmin,
					'shortname' => 	$check_user->shortname,
					'fullname' 	=> 	$check_user->nombre . ' ' . $check_user->apellidos,
					'timezone'	=>	$oficina->timezone,
					'termsaccepted' =>	$check_user->termsaccepted,
					'issimulation' => 	TRUE
				);
				
				$this->session->set_userdata($data);
				redirect(base_url().'admin');

			}
		}
	}

	public function EndSimulation() {
		$curr_idempresa = $this->session->userdata('curr_idempresa');
		$curr_idoficina = $this->session->userdata('curr_idoficina');
		$curr_idagente = $this->session->userdata('curr_idagente');

		echo $curr_idempresa . ' ' . $curr_idoficina . ' ' . $curr_idagente;

		$check_user = $this->Login_model->SimulateSession($curr_idempresa, $curr_idoficina, $curr_idagente);

		if($check_user){

			// obtiene la version del webapp
			$versionweb = $this->Config_model->GetEmpresas()[0]['versionweb'];

			// registra el acceso
			$user = $this->Login_model->log_signin($check_user->shortname, $versionweb, 'simulate');

			// obtiene la oficina a la que pertenece el usuario
			$oficina = $this->Login_model->getOficinaAdmin($check_user->idempresa, $check_user->idoficina);

			$isadmin = $this->Agentes_model->IsAdmin($check_user->idempresa,$check_user->idoficina,$check_user->uniqueid);

			$data = array(
				'is_logued_in' 	=> 	TRUE,
				'idempresa'	=>	$check_user->idempresa,
				'idoficina'	=>	$check_user->idoficina,
				'iatacode'	=>	$oficina->iatacode,
				'idagente' 	=> 	$check_user->idagente,
				'perfil'	=>	$check_user->perfil,
				'email' 	=> 	$check_user->email,
				'jornada' 	=> 	$check_user->jornada,
				'puesto'	=>	$check_user->puesto,
				'isadmin'	=>	$isadmin,
				'shortname' => 	$check_user->shortname,
				'fullname' 	=> 	$check_user->nombre . ' ' . $check_user->apellidos,
				'timezone'	=>	$oficina->timezone,
				'termsaccepted' =>	$check_user->termsaccepted,
				'issimulation' => 	FALSE
			);		
			$this->session->set_userdata($data);
			redirect(base_url().'admin');

		}

	}

	/* old simulation 

	public function SimulateSession(){
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		$idagente = $this->input->post('inputAgent');

		if($this->session->userdata('isadmin')){

			$check_user = $this->Login_model->SimulateSession($idempresa, $idoficina, $idagente);
			if($check_user){

				// obtiene la version del webapp
				$versionweb = $this->Config_model->GetEmpresas()[0]['versionweb'];

				// registra el acceso
				$user = $this->login_model->log_signin($check_user->shortname, $versionweb, 'web');

				// obtiene la oficina a la que pertenece el usuario
				$oficina = $this->login_model->getOficinaAdmin($check_user->idempresa, $check_user->idoficina);

				$isadmin = $this->Agentes_model->IsAdmin($check_user->idempresa,$check_user->idoficina,$check_user->uniqueid);

				$data = array(
                	'is_logued_in' 	=> 	TRUE,
					'idempresa'	=>	$check_user->idempresa,
					'idoficina'	=>	$check_user->idoficina,
					'iatacode'	=>	$oficina->iatacode,
					'idagente'	=> 	$check_user->idagente,
					'perfil'	=>	$check_user->perfil,
					'email' 	=> 	$check_user->email,
					'jornada'	=> 	$check_user->jornada,
					'puesto'	=>	$check_user->puesto,
					'isadmin'	=>	$isadmin,
					'shortname' 	=> 	$check_user->shortname,
					'fullname'	=> 	$check_user->nombre . ' ' . $check_user->apellidos,
					'timezone'	=>	$oficina->timezone,
					'termsaccepted' =>	$check_user->termsaccepted
    			);		
				$this->session->set_userdata($data);

				// dump de la sesion
				print_r($this->session->all_userdata());
				die();
				redirect(base_url().'admin');

			}
		}
	}

	*/
	
	public function SetMinimumPos()
	{
		
		$idempresa = $this->input->post('idempresa');
		$idoficina = $this->input->post('idoficina');
		
		$data = $this->Config_model->SetMinimumPos($idempresa,$idoficina);
		//print_r(json_encode($data));
		return $data;	
		
	}

	// manda invitacion a ingresar a todos los agentes sin perfil
	public function SendUserRequests()
	{
		$idempresa = $this->input->post('idempresa');
		$idoficina = $this->input->post('idoficina');
		$all = $this->input->post("all");

		// consulta todos los agentes que no tienen perfil aun
		if($all == 1)
			$agentlist = $this->Config_model->GetAgents($idempresa,$idoficina);
		else
			$agentlist = $this->Config_model->ListaAgentesSinAcceso($idempresa,$idoficina);
		$resultado = array();
		foreach ($agentlist as $agente) {
			$delivery =$this->NotifyGrantAccess($agente['uniqueid'],$idempresa,$idoficina);
			array_push($resultado,$delivery);
		
		}
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($resultado);
	}

	// manda invitacion a ingresar a todos los agentes sin perfil
	public function SendUniqueRequests()
	{
		$idempresa = $this->input->post('idempresa');
		$idoficina = $this->input->post('idoficina');

		$uniqueid = $this->input->post('uniqueid');

		$delivery =$this->NotifyGrantAccess($uniqueid,$idempresa,$idoficina);
			
	}

	public function LastLogins()
	{
		
		$data = $this->Config_model->LastLogins();
		if(!$data)
		{
			//$error = array('status' => "OK", "msg" => "No data available");
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 No data', true, 500);
		}
		header('Content-type: application/json; charset=utf-8');
		print_r(json_encode($data));
	}

	public function totals()
	{
		
		$data = $this->Config_model->Totales();
		if(!$data)
		{
			//$error = array('status' => "OK", "msg" => "No data available");
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 No data', true, 500);
		}
		header('Content-type: html/text; charset=utf-8');
		print_r(json_encode($data));
	}

	public function NotifyGrantAccess($uniqueid,$empresa,$oficina)
	{

		$agents = $this->Agentes_model->LoadAgentUniqueId($empresa,$oficina,$uniqueid);
		$agent = $agents[0];
		//print_r($agent);

        $subject="Web CUNOP Access";

//        mail($to,$subject,$body,$headers);

        //$uniqueid = $agents['uniqueid'];
        $encrypt = $encrypt = md5($agent['shortname']);

        $message = 'Hello, ' . $agent['shortname'] . '<br/> <br/>As requested, this mail is to inform that you can access to the WebCUNOP Web App.<br/><br/>If you already have access to the Web App, please ignore this mail, if not please click the following link <br><br>https://apps.mindware.com.mx/agentes/getaccess?e=' . $encrypt .'&u=' . $uniqueid . 
        		   '<br/><br/><br/>There you will be able to setup a password for your account and then a you will be redirected to the login page.<br/><br/>This mail was sent automatically by mindWARE.com.mx.</body></html>';
        //$message = 'Hi, ' . $agent['nombre'] . '<br/> <br/>Your please click the following link to set tus access token for Web CUNOP<br><br><br/><br/>.';

		//Create a new PHPMailer instance
		$mail = new PHPMailer;
		//Tell PHPMailer to use SMTP
		$mail->isSMTP();
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug = 2;

		$mail->Debugoutput = function($str, $level){

			$txt = "$level: $str\n";
 			$myfile = file_put_contents('uploads/log_notify.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
		};
		//Set the hostname of the mail server
		//Set the hostname of the mail server
		$mail->Host = "email-smtp.us-east-1.amazonaws.com";
		//Set the SMTP port number - likely to be 25, 465 or 587
		$mail->Port = 587;
		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;

		$mail->SMTPSecure = 'tls';
		//Username to use for SMTP authentication
		$mail->Username = "AKIAI6LMOHOXU7T42AHA";
		//Password to use for SMTP authentication
		$mail->Password = "AjBE+I7MMEKX0VateR2iyibN0jBa3UqmD5sdku7tDmm7";

		//Set who the message is to be sent from
		$mail->setFrom('cunop@apps.mindware.com.mx', 'WebCunOP by Mindware');
		//Set an alternative reply-to address
		//$mail->addReplyTo('x@mindware.com.mx', 'First Last');
		//Set who the message is to be sent to
		$mail->addAddress($agent['email'], $agent['nombre'] . ' ' . $agent['apellidos']);
		$mail->addBCC('x@mindware.com.mx','Admin');
		//Set the subject line
		$mail->Subject = $subject;
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$mail->msgHTML($message);
		//Replace the plain text body with one created manually
		//$mail->AltBody = 'This is a plain-text message body';
		//Attach an image file
		//$mail->addAttachment('PHPMailer-master/images/phpmailer_mini.png');

		//send the message, check for errors

		if (!$mail->send()) {
			$error = array('shortname' => $agent['shortname'], 'status' => "Failed", "msg" => "Mailer Error: " . $mail->ErrorInfo);
			return $error;
		} else {
			//$mail->copyToFolder("Sent"); // Will save into Sent folder
		    
		}
		return $agent['shortname'];
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

