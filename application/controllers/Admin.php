<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 *  XALFEIRAN 2016
 */
class Admin extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Admin_model');
		$this->load->model('Agentes_model');
		$this->load->model('Newsfeed_model');
		$this->load->model('Webcunop_model');		
		$this->load->model('Timeswitch_model');	
		$this->load->model('Polls_model');
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
		

		// consulta las oficinas de la cuales es admin el usuario actual
		$adminoficina = $this->Admin_model->GetOficinasAdmin();
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		$idagente = $this->session->userdata('idagente');
		$fullagentes = $this->LoadAgents($idempresa,$idoficina);
		
		$data['titulo'] = 'WebCUNOP';
		$data['timezone'] = $this->session->userdata('timezone');
		$data['idusuario'] = $this->session->userdata('shortname');
		$data['idempresa'] = $this->session->userdata('idempresa');
		$data['idoficina'] = $this->session->userdata('idoficina');
		$data['termsaccepted'] = $this->session->userdata('termsaccepted');

		// traer el quick roster
		$fecha = date('Y-m-d');
		$data['quickscheduler1'] = $this->Webcunop_model->ConsultarQuickSchedule($idempresa,$idoficina,$idagente,$fecha);
		$data['quickgates1'] = $this->Webcunop_model->ConsultarQuickGates($idempresa,$idoficina,$this->session->userdata('shortname'),$fecha);
		$data['monthlyschedule'] = $this->Webcunop_model->ConsultarMonthlySchedule($idempresa,$idoficina,$idagente,$fecha);
		$fecha = date('Y-m-d',strtotime('tomorrow'));
		
		$data['quickscheduler2'] = $this->Webcunop_model->ConsultarQuickSchedule($idempresa,$idoficina,$idagente,$fecha);

		
		$data['requestpending'] = $this->Timeswitch_model->ConsultarSolicitudesCambioAgente($idempresa,$idoficina,$idagente);
		$data['requestaccepted'] = $this->Timeswitch_model->ConsultarSolicitudesAceptadasAgente($idempresa,$idoficina,$idagente);

		$data['quickgates2'] = $this->Webcunop_model->ConsultarQuickGates($idempresa,$idoficina,$this->session->userdata('shortname'),$fecha);
		
		$data['birthday'] = $this->Agentes_model->GetBirthdayPeople($idempresa,$idoficina);
		$data['currentpolls'] = $this->Polls_model->GetCurrentPolls($idempresa,$idoficina,$idagente);
		$data['oficinas'] = $adminoficina;
		$data['fullagentes'] = $fullagentes;
		
		$data['isadmin'] = $this->session->userdata('isadmin');
		if($this->session->userdata('isadmin'))
			if($this->session->userdata('puesto') == 'MANAGER')
				$data['requesttoauthorize'] = $this->Timeswitch_model->ConsultarSolicitudesLeadsPorAutorizar($idempresa,$idoficina);
			else
				$data['requesttoauthorize'] = $this->Timeswitch_model->ConsultarSolicitudesPorAutorizar($idempresa,$idoficina);

		$data['perfil'] = $this->session->userdata('perfil');
		if($this->session->userdata('isadmin')!='')
				$this->load->view('paginas/header',$data);
			else
				$this->load->view('paginas/header_u',$data);
		$data['newslist'] = $this->Newsfeed_model->LoadValidNews($idempresa,$idoficina);

		$this->load->view('Admin_view',$data);
		$this->load->view('paginas/footer');
			
	}

	public function LoadAgents($empresa,$oficina)
	{
		
		$data = $this->Agentes_model->StationAgents($empresa,$oficina);
		return $data;
		
	}

	public function PlaceComment()
	{
		$idempresa = $this->input->post('idempresa'); 
		$idoficina = $this->input->post('idoficina');
		$idagente = $this->input->post('idagente');
		$shortname = $this->input->post('shortname');
		$title = $this->input->post('title');
		$comment = $this->input->post('comment');
		$usuario = $this->input->post('usuario');

		$agentmail = '';
		//echo 'idagente ' . $idagente . PHP_EOL;
		if($idagente!='')
		{

			$rowagent = $this->Agentes_model->LoadAgentId($idempresa,$idoficina,$idagente);	
			$agentmail = $rowagent[0]['email'];
			//echo 'agentemail ' . $agentmail . PHP_EOL;
		}
		

		$this->Admin_model->PlaceComment($idempresa,$idoficina,$idagente,$shortname,$title,$comment,$usuario);

		$info = $this->NotifyNewComment($shortname,$agentmail,$title,$comment,$usuario);

		header('Content-type: application/json; charset=utf-8');
		print_r(json_encode($info));
	}

	public function NotifyNewComment($shortname,$agentmail,$title,$comment,$usuario)
	{
		
        $info = '';
		$SesClient = new SesClient($this->config->item('aws_ses_keys'));

		$sender_email = 'webroster@mindware.com.mx';
		$subject="Message from {$usuario} by WebRoster.";

		$recipient_emails = [$agentmail];
		//$configuration_set = 'ConfigSet';

		$html_body = 'Hello, ' . $shortname . '<br/> <br/>You have a comment from ' . $usuario . '.<br/><br/><strong>' . $title . '</strong><br/>' . $comment . '<br/><br/><br/>.<br/><br/>This mail was sent automatically by mindWARE.com.mx.</body></html>';
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
		    $info =  "Email sent! Message ID: $messageId";
		} catch (AwsException $e) {
		    // output error message if fails
		    $info = $e->getMessage();
		    $info .= "The email was not sent. Error message: ".$e->getAwsErrorMessage()."\n";
		}
		return array('info' => $info);
	}

	public function Acceptterms()
	{
		$idempresa = $this->input->post('idempresa'); 
		$idoficina = $this->input->post('idoficina');
		$idagente = $this->session->userdata('idagente');

		$this->Admin_model->AcceptTerms($idempresa,$idoficina,$idagente);

		$error = array('status' => "OK", "msg" => "Terms Accepted");
		$this->response($this->json($error), 400);
	}

	public function TestComment()
	{
		
		$shortname = 'Xavier Alfeiran';
		$agentmail = 'xalfeiran@sipse.com.mx';
		$title='Excelente trabajo compañero!';
		$comment='Gracias por el esfuerzo en la resolucion de la tarea asignada';
		$usuario = 'sysadmin';

        $subject="New Comment for agents " . $agentmail;

//        mail($to,$subject,$body,$headers);

        //echo 'pre phpmailer<br/>' . __DIR__;
       	require('PHPMailer-master/PHPMailerAutoload.php');
        
        $message = '<html><body><b>Hello ' . $shortname . '</b><br/><br/>You have a comment from ' . $usuario . ' <br/><br/>' . $title . '<br/>' . $comment . '<br/><br/><br/>This mail was sent automatically by mindWARE.com.mx.</body></html>';
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
		//Ask for HTML-friendly debug output
		$mail->Debugoutput = function($str, $level){
			echo "$level: " . $str . PHP_EOL;  
			$txt = "$level: $str\n";
 			$myfile = file_put_contents('uploads/log_comments.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
			};
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
		$mail->setFrom('cunop@apps.mindware.com.mx', 'WebCunOP');
		//Set an alternative reply-to address
		//$mail->addReplyTo('x@mindware.com.mx', 'First Last');
		//Set who the message is to be sent to
		//if($agentmail!='')
			//$mail->addAddress($agentmail,$shortname);
		$mail->addAddress('web-pt4l4@mail-tester.com','Comments');
		//$mail->addAddress('xmindware@gmail.com','Comments X Admin');
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
		} else {
			//$mail->copyToFolder("Sent"); // Will save into Sent folder
		    $error = array('status' => "OK", "msg" => "Mail sent");
		}
		return $error;
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



