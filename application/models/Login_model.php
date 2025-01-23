<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  Modelo para acceso a datos de acceso
 */
class Login_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function login_user($email,$password)
	{

		$sql = 'SELECT * FROM cunop_agentesactivos WHERE (email = ? OR idagente = ? ) AND password = ?';
		$query = $this->db->query($sql, array($email,$email,$password));
		if($query->num_rows() > 0)
		{
			return $query->row();
		}else{
			$this->session->set_flashdata('usuario_incorrecto','Los datos introducidos son incorrectos');
			redirect(base_url().'login','refresh');
		}
	}

	public function getOficinaAdmin($idempresa, $idoficina)
	{
		$this->db->where('idempresa',$idempresa);
		$this->db->where('idoficina', $idoficina);
		
		$query = $this->db->get('cunop_oficinas');
		
		if($query->num_rows() > 0)
		{
			return $query->row();
		}else{
			$this->session->set_flashdata('usuario_incorrecto','Los datos introducidos son incorrectos');
			redirect(base_url().'login','refresh');
		}
	}

	public function SimulateSession($idempresa, $idoficina, $idagente){

		$this->db->where('idempresa',$idempresa);
		$this->db->where('idoficina',$idoficina);
		$this->db->where('idagente',$idagente);

		$query = $this->db->get('cunop_agentesactivos');
		
		if($query->num_rows() > 0)
		{
			return $query->row();
		}
	}

	public function TestUpdate($os)
	{
		$query=$this->db->get('cunop_empresas');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			if($os=='iphone')
				return $row->updateios;
			else
				return $row->updateandroid;
		}
		else
			return false;
	}

	public function log_signin($shortname, $version, $os )
	{
		$data = array(
            'shortname' 	=> $shortname,
            'version' 		=> $version,
            'os' 			=> $os	
        );
		
		// actualiza la info
        $this->db->replace('cunop_bitacoraingresos', $data);
	}
}