<?
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Login extends CI_Controller
{
	const TITULO = 'WebRoster AA CUN';

    public function __construct()
    {
        parent::__construct();
		$this->load->helper(array('url','form'));
		$this->load->library(array('form_validation'));
		
		$this->load->model('login_model');
		$this->load->model('Config_model');
		$this->load->model('Agentes_model');

		$this->load->database('default');
		
		// sirve para oculta errores o mensajes de warning
		//error_reporting(0);
    }
	
	public function index()
	{	

		$empresa = $this->Config_model->GetEmpresas();

		$data['versionweb'] = $empresa[0]['versionweb'];

		switch ($this->session->userdata('perfil')) {
			case '':
				$data['token'] = $this->token();
				$data['titulo'] = self::TITULO;
				$this->load->view('Login_view',$data);
				break;
			case 'admin':	
				redirect(base_url() . 'admin');
				break;
			case 'usuario':	
				redirect(base_url() . 'admin');
				break;
			default :	
				$data['titulo'] = self::TITULO;
				$this->load->view('Login_view',$data);
				break;		
		}
	}
 
public function new_user()
	{

		if($this->input->post('token') && $this->input->post('token') == $this->session->userdata('token'))
		{

            //$this->form_validation->set_rules('email', 'Email Address', 'required|trim|min_length[2]|max_length[150]|xss_clean');
            $this->form_validation->set_rules('password', 'password', 'required|trim|min_length[2]|max_length[150]|xss_clean');

            //lanzamos mensajes de error si es que los hay
			if($this->form_validation->run() == FALSE)
			{
				$this->index();
			}else{
			
				$email = $this->input->post('email');
				$password = sha1($this->input->post('password'));
	
				
	
				//echo $email;
				//echo $password;
				$check_user = $this->login_model->login_user($email,$password);


				if($check_user == TRUE)
				{

					// obtiene la version del webapp
					$versionweb = $this->Config_model->GetEmpresas()[0]['versionweb'];

					// registra el acceso
					$user = $this->login_model->log_signin($check_user->shortname, $versionweb, 'web');

					// obtiene la oficina a la que pertenece el usuario
					$oficina = $this->login_model->getOficinaAdmin($check_user->idempresa, $check_user->idoficina);

					$isadmin = $this->Agentes_model->IsAdmin($check_user->idempresa,$check_user->idoficina,$check_user->uniqueid);

					$data = array(
	                'is_logued_in' 	=> 		TRUE,
					'idempresa'		=>		$check_user->idempresa,
					'idoficina'		=>		$check_user->idoficina,
					'iatacode'		=>		$oficina->iatacode,
	                'idagente' 		=> 		$check_user->idagente,
	                'perfil'		=>		$check_user->perfil,
	                'email' 		=> 		$check_user->email,
	                'jornada' 		=> 		$check_user->jornada,
	                'puesto'		=>		$check_user->puesto,
	                'isadmin'		=>		$isadmin,
					'shortname' 	=> 		$check_user->shortname,
					'fullname' 		=> 		$check_user->nombre . ' ' . $check_user->apellidos,
					'timezone'		=>		$oficina->timezone,
					'termsaccepted' =>		$check_user->termsaccepted
            		);		
					$this->session->set_userdata($data);
					$this->index();
				}
			}
		}else{
			redirect(base_url().'login');
		}
	}

	public function ValidateUser()
	{
	
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$version = $this->input->post('version');
		$os = $this->input->post('os');
		$deviceid = $this->input->post('deviceid');	

		$check_user = $this->login_model->login_user($email,$password);

		$pleaseupdate = $this->login_model->testUpdate($os);

		$oficina = $this->login_model->getOficinaAdmin($check_user->idempresa, $check_user->idoficina);
		//echo $check_user->perfil;
		//return;
		if($check_user == TRUE)
		{

			$this->Agentes_model->UpdateDeviceId($check_user->uniqueid, $deviceid);

			$user = $this->login_model->log_signin($check_user->shortname, $version, $os);
			
			$data = array(
            'is_logued_in' 	=> 		TRUE,
			'idempresa'		=>		$check_user->idempresa,
			'idoficina'		=>		$check_user->idoficina,
			'iatacode'		=>		$oficina->iatacode,
            'idagente' 		=> 		$check_user->idagente,
            'perfil'		=>		$check_user->perfil,
            'email' 		=> 		$check_user->email,
            'puesto'		=>		$check_user->puesto,
            'pleaseupdate'	=>		$pleaseupdate,
			'shortname' 	=> 		$check_user->shortname,
			'fullname' 		=> 		$check_user->nombre . ' ' . $check_user->apellidos,
			'timezone'		=>		$oficina->timezone
    		);		
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($data);
		}
		else
		{
			$error = array('status' => "Failed", "msg" => "Error while obtaining user data");
			//$this->response($this->json($error), 400);
			echo json_encode($error);
		}
	
	}

/*
	public function AppUserLoggedIn()
	{
		$uniqueid = $this->input->post('uniqueid');
		$shortname = $this->input->post('shortname');
		$version = $this->input->post('version');
		$os = $this->input->post('os');
		$deviceid = $this->input->post('deviceid');	

		$this->Agentes_model->UpdateDeviceId($uniqueid, $deviceid);

		$user = $this->login_model->log_signin($shortname, $version, $os);
	}*/
	
	public function token()
	{
		$token = md5(uniqid(rand(),true));
		$this->session->set_userdata('token',$token);
		$_SESSION['token'] = $token;
		return $token;
	}
	
	public function logout_ci()
	{
		$this->session->sess_destroy();
		$this->index();
	}
}


?>