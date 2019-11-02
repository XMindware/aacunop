<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 * 
 */
class Posiciones extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Admin_model');
		$this->load->model('Cando_model');
		$this->load->model('Jornadas_model');
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
		
		$this->LoadPositions();
	}
	
	public function LoadPositions()
	{
		$empresa = $this->session->userdata('idempresa');
		
		// consulta las oficinas de la cuales es admin el usuario actual
		$adminoficina = $this->Admin_model->GetOficinasAdmin();
		
		$tdata['titulo'] = 'Agent List';
		$tdata['oficinas'] = $adminoficina;
		$data['workdaylist'] = $this->Jornadas_model->CompanyWorkdays($empresa);
		$data['positionlist'] = $this->Posiciones_model->CompanyPositions($empresa);
		$data['timezone'] = $this->session->userdata('timezone');
		$data['isadmin'] = $this->session->userdata('isadmin');
		$data['candolist'] = $this->Cando_model->CompanySkills($empresa);
		$data['idempresa'] = $empresa;
		$data['isadmin'] = $this->session->userdata('isadmin');
		
		if($data)
		{
			//$data = Array('userlist' => $userlist);
			//print_r($userlist);
			$this->load->view('paginas/header',$tdata);
			$this->load->view('Posiciones_view',$data);
			$this->load->view('paginas/footer');
		}
	
	}
	
	public function PostPosition()
	{
		$idempresa = $this->input->post("idempresa");
		$code = $this->input->post("code");
		$uniqueid = $this->input->post("uniqueid");
		$description = $this->input->post("description");
		$stime = $this->input->post("stime");
		$etime = $this->input->post("etime");
		$sdate = $this->input->post("sdate");
		$edate = $this->input->post("edate");
		$workday = $this->input->post("workday");
		$cando = $this->input->post("cando");
			
		$insert = $this->Posiciones_model->PostPosition($uniqueid,$idempresa,$code,$description,$stime,$etime,$sdate,$edate,$workday,$cando);
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
	
	
	public function LoadPositionId()
	{
		$idempresa = $this->input->post("idempresa");
		$uniqueid = $this->input->post("uniqueid");
		
		$data = $this->Posiciones_model->LoadPositionUniqueId($idempresa,$uniqueid);
		print_r(json_encode($data));
		return $data;
		
	}
	
	public function DeletePositionId()
	{
		$idempresa = $this->input->post("idempresa");
		$uniqueid = $this->input->post("uniqueid");
		
		$data = $this->Posiciones_model->DeletePositionId($idempresa,$uniqueid);
		print_r(json_encode($data));
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

