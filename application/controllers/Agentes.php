<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


require_once '/home/mindware/resources/vendor/autoload.php';
use Aws\Ses\SesClient;
use Aws\Exception\AwsException;

error_reporting(E_ERROR | E_PARSE);
/**
 *	Controller de Agentes
 *  XALFEIRAN 2016
 */
class Agentes extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Agentes_model');
		$this->load->model('Admin_model');
		$this->load->model('Puestos_model');
		$this->load->model('Jornadas_model');
		$this->load->model('Cando_model');
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
	}
	
	public function index()
	{
		if($this->session->userdata('perfil') == FALSE )
		{
			redirect(base_url().'login');
		}

		$this->LoadAgents();
	}
	
	public function LoadAgents()
	{
		$empresa = $this->session->userdata('idempresa');
		$oficina = $this->session->userdata('idoficina');
		
		// consulta las oficinas de la cuales es admin el usuario actual
		$adminoficina = $this->Admin_model->GetOficinasAdmin();
		
		$tdata['titulo'] = 'Agents List';
		$tdata['oficinas'] = $adminoficina;
	
		$agents = $this->Agentes_model->StationAgents($empresa,$oficina);
		
		$agentlist = array();
		foreach($agents as $agent)
		{
			$fullcando = $this->Cando_model->LoadCandoAgent($empresa,$oficina,$agent['uniqueid']);
			$agentlist[] = array($agent,$fullcando);
		}

		$data['isadmin'] = $this->session->userdata('isadmin');
		$data['idempresa'] = $empresa;
		$data['idoficina'] = $oficina;
		$data['userlist'] =  $agentlist;	
		$data['empresas'] = $this->Agentes_model->GetEmpresas();
		$data['puestos'] = $this->Puestos_model->CompanyPositions($empresa);
		$data['jornadas'] = $this->Jornadas_model->CompanyWorkdays($empresa);
		$data['candos'] = $this->Cando_model->CompanySkills($empresa);
		
		if($data)
		{
			//$data = Array('userlist' => $userlist);
			//print_r($userlist);
			$this->load->view('paginas/header',$tdata);
			$this->load->view('Agentes_view',$data);
			$this->load->view('paginas/footer');
		}
	
	}
	
	
	
	public function LoadAgentId()
	{
		$agenteid = $this->input->post("agenteid");
		$oficina = $this->input->post('idoficina');
		$empresa = $this->input->post('idempresa');
		
		$data = $this->Agentes_model->LoadAgentId($empresa,$oficina,$agenteid);
		header('Access-Control-Allow-Origin: *');
		header('Content-type: application/json; charset=utf-8');
		print_r(json_encode($data));
		//return $data;
		
	}

	public function LoadAgentUniqueId()
	{
		$uniqueid = $this->input->post("uniqueid");
		$oficina = $this->input->post('idoficina');
		$empresa = $this->input->post('idempresa');
		
		$data = $this->Agentes_model->LoadAgentUniqueId($empresa,$oficina,$uniqueid);

		header('Content-type: application/json; charset=utf-8');
		print_r(json_encode($data));
		//return $data;
		
	}

	public function PostAgent()
	{
		header('Access-Control-Allow-Origin: *');
		$uniqueid = $this->input->post('uniqueid');
		$empresa = $this->input->post('idempresa');
		$oficina = $this->input->post('idoficina');
		$agenteid = $this->input->post("agenteid");
		$primernombre = $this->input->post("primernombre");
		$apellidos = $this->input->post("apellidos");
		$shortname = $this->input->post("shortname");
		$ingreso = $this->input->post("ingreso");
		$email = $this->input->post("email");
		$puesto = $this->input->post("position");
		$jornada = $this->input->post("jornada");
		$telefono = $this->input->post("telefono");
		$birthday = $this->input->post("birthday");

		$diasdescanso = $this->input->post("dayoff");
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
		
		$insert = $this->Agentes_model->PostAgent($uniqueid, $empresa, $oficina, $agenteid,$primernombre,$apellidos,$shortname,$ingreso,$email,$telefono,$birthday,$puesto,$jornada,$dia1,$dia2);
		if($insert)
		{
			$this->response($this->json($insert), 200);
		}
		else
		{
			$error = array('status' => "Failed", "msg" => "Error al actualizar el agente");
			$this->response($this->json($error), 400);
		}
	}

	public function UpdateAgentAssignments(){
		$uniqueid = $this->input->post('uniqueid');
		$idempresa = $this->input->post('idempresa');
		$idoficina = $this->input->post('idoficina');
		$agenteid = $this->input->post("agenteid");

		$result = $this->Agentes_model->UpdateAgentAssignments($idempresa, $idoficina, $uniqueid, $agenteid);

		header('Access-Control-Allow-Origin: *');
		if($result)
		{
			$result = array('status' => "OK", "msg" => "Workdays updated");
			$this->response($this->json($result), 200);
		}
		else
		{
			$error = array('status' => "Failed", "msg" => "Error al actualizar el agente");
			$this->response($this->json($error), 400);
		}
	}
	
	public function ReleaseAgent()
	{
		header('Access-Control-Allow-Origin: *');
		$uniqueid = $this->input->post('uniqueid');
		$empresa = $this->input->post('idempresa');
		$oficina = $this->input->post('idoficina');

		$insert = $this->Agentes_model->ReleaseAgent($uniqueid, $empresa, $oficina);
		if($insert)
		{
			$this->response($this->json($insert), 200);
		}
		else
		{
			$error = array('status' => "Failed", "msg" => "Error al actualizar el agente");
			header('Access-Control-Allow-Origin: *');
			$this->response($this->json($error), 400);
		}
	}

	public function GetAccess()
	{
		$uniqueid = $this->input->get('u');
		$encrypt = md5($this->input->get('e'));
	
		$agent = $this->Agentes_model->LoadUniqueId($uniqueid,$encrypt);
	
		if($agent)
		{
			$data['uniqueid'] = $uniqueid;
			$data['idempresa'] = $agent['idempresa'];
			$data['idoficina'] = $agent['idoficina'];
			$data['idagente'] = $agent['idagente'];
			$data['nombre'] = $agent['nombre'];
			$data['apellidos'] = $agent['apellidos'];
			$data['shortname'] = $agent['shortname'];
			$data['email'] = $agent['email'];
			$data['joindate'] = $agent['ingreso'];
			$this->load->view('GetAccessAgentes_view',$data);
			$this->load->view('paginas/footer');
		}	
	}

	public function NotifyGrantAccess()
	{
		$uniqueid = $this->input->post('uniqueid');
		$empresa = $this->input->post('idempresa');
		$oficina = $this->input->post('idoficina');	

		$agents = $this->Agentes_model->LoadAgentUniqueId($empresa,$oficina,$uniqueid);
		$agent = $agents[0];
		//print_r($agent);

		// Create an SesClient. Change the value of the region parameter if you're 
		// using an AWS Region other than EE.UU. Oeste (Oregón). Change the value of the
		// profile parameter if you want to use a profile in your credentials file
		// other than the default.

		$SesClient = new SesClient([
		    'credentials' => array(
		    	'key' => 'AKIAQLB77NPZBNP5OGMR',
				'secret' => 'wqtDadbSjXl2AYjtVVYTe/duKiIO9asnDXfJGOAn'
			),
		    'version' => '2010-12-01',
		    'region'  => 'us-east-1',
		]);

        
        //$uniqueid = $agents['uniqueid'];
        $encrypt = $encrypt = md5($agent['shortname']);

        $sender_email = 'webroster@mindware.com.mx';
        $subject="Web Roster Access";

        $recipient_emails = [$agent['email']];
        //$configuration_set = 'ConfigSet';

        $html_body = 'Hello, ' . $agent['shortname'] . '<br/> <br/>This mail is to inform that you can have access to the WebCUNOP Web software, for checking your CUNOP and daily positions.<br/><br/>Please click the following link to set your access token for Web App<br><br>https://apps.mindware.com.mx/cun/agentes/getaccess?e=' . $encrypt .'&u=' . $uniqueid . 
        		   '<br/><br/><br/>There you will be able to setup a password for your account and then a you will be redirected to the login page.<br/><br/>This mail was sent automatically by mindWARE.com.mx.</body></html>';
       	//$subject = 'Amazon SES test (AWS SDK para PHP)';
       	$char_set = 'UTF-8';

       	try {
		    $result = $SesClient->sendEmail([
		        'Destination' => [
		            'ToAddresses' => $recipient_emails,
		            'BccAddresses' => ['x@mindware.com.mx']
		        ],
		        'ReplyToAddresses' => [$sender_email],
		        'Source' => $sender_email,
		        'Message' => [
		          'Body' => [
		              'Html' => [
		                  'Charset' => $char_set,
		                  'Data' => $html_body,
		              ],
		          ],
		          'Subject' => [
		              'Charset' => $char_set,
		              'Data' => $subject,
		          ],
		        ],
		        // If you aren't using a configuration set, comment or delete the
		        // following line
		        //'ConfigurationSetName' => $configuration_set,
		    ]);
		    $messageId = $result['MessageId'];
		    echo("Email sent! Message ID: $messageId"."\n");
		} catch (AwsException $e) {
		    // output error message if fails
		    echo $e->getMessage();
		    echo("The email was not sent. Error message: ".$e->getAwsErrorMessage()."\n");
		    echo "\n";
		}

		/*
		//Create a new PHPMailer instance
		$mail = new PHPMailer;
		//Tell PHPMailer to use SMTP
		$mail->isSMTP();
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug = 2;
		//Ask for HTML-friendly debug output
		$mail->Debugoutput = function($str, $level){

			$txt = "$level: $str\n";
 			$myfile = file_put_contents('uploads/log_notify.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
		};
		//Set the hostname of the mail server
		$mail->Host = "apps.mindware.com.mx";
		//Set the SMTP port number - likely to be 25, 465 or 587
		$mail->Port = 587;
		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;

		//$mail->SMTPSecure = 'tls';
		//Username to use for SMTP authentication
		$mail->Username = "cunop@apps.mindware.com.mx";
		//Password to use for SMTP authentication
		$mail->Password = "AjBE+I7MMEKX0VateR2iyibN0jBa3UqmD5sdku7tDmm7";

		//Set who the message is to be sent from
		$mail->setFrom('cunop@apps.mindware.com.mx', 'WebCunOP');
		//Set an alternative reply-to address
		//$mail->addReplyTo('x@mindware.com.mx', 'First Last');
		$mail->addAddress($agent['email'], $agent['nombre'] . ' ' . $agent['apellidos']);
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
			$error = array('status' => "Failed", "msg" => "Mailer Error: " . $mail->ErrorInfo);
			$this->response($this->json($error), 400);
		} else {
			//$mail->copyToFolder("Sent"); // Will save into Sent folder
		    $this->response('OK', 200);
		}*/
	}

	public function EnableAccount()
	{
		$idempresa = $this->input->post("idempresa");
		$idoficina = $this->input->post("idoficina");
		$uniqueid = $this->input->post("uniqueid");
		$password = $this->input->post("password");

		$agente = $this->Agentes_model->EnableAccount($idempresa, $idoficina, $uniqueid, $password);
		//print_r($agente[0]);
		$this->Agentes_model->AddOficinaAdmin($idempresa, $idoficina, $agente[0]['idagente']);
		$this->response('OK', 200);
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

