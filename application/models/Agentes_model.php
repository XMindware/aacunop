<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 */
class Agentes_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	
	public function StationAgents($idempresa,$idoficina){
		$this->db->where('idempresa',$idempresa);
		$this->db->where('idoficina',$idoficina);
		$this->db->where('status="OK"');
		$this->db->order_by('nombre,apellidos','ASC');
		$query = $this->db->get('cunop_agentes');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}
	
	
	public function GetEmpresas(){
		//$this->db->where('empresa',$empresa);
		//$this->db->where('oficina',$oficina);
		$query = $this->db->get('cunop_agentes');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}
	
	public function LoadAgentId($empresa,$oficina,$agentid){
		$this->db->where('idempresa',$empresa);
		$this->db->where('idoficina',$oficina);
		$this->db->where('idagente',$agentid);
		$query = $this->db->get('cunop_agentes');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
		else
		{
			return array();
		}
	}

	// solicitamos record de agentes validos
	public function LoadValidAgentId($empresa,$oficina,$agentid){
		$this->db->where('idempresa',$empresa);
		$this->db->where('idoficina',$oficina);
		$this->db->where('idagente',$agentid);
		$this->db->where('status','OK');
		
		$query = $this->db->get('cunop_agentes');
		//echo($this->db->last_query());
		//$query = $this->db->query('select * from cunop_agentes where idagente='.$agentid);
		if($query->num_rows() > 0)
		{
			return $query->row();
		}	
		else
		{
			return false;
		}
	}



	public function LoadAgentUniqueId($empresa,$oficina,$uniqueid){
		$this->db->where('idempresa',$empresa);
		$this->db->where('idoficina',$oficina);
		$this->db->where('uniqueid',$uniqueid);
		$query = $this->db->get('cunop_agentes');
		//echo($this->db->last_query());
		//$query = $this->db->query('select * from cunop_agentes where idagente='.$agentid);
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
		else
		{
			return array();
		}
	}

	
	public function IsAdmin($idempresa, $idoficina, $uniqueid)
	{

		// buscar el skill M_CUNOP
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('idagente',  $uniqueid);
		$this->db->where('idcando', 'M_CUNOP');
		$query = $this->db->get('cunop_relcandoagents');
		
		return $query->num_rows()>0?1:0;
	}

	public function LoadUniqueId($uniqueid,$encrypt){

		$sql = 'SELECT idempresa, idoficina, idagente, shortname, nombre, apellidos, email, ingreso FROM cunop_agentesactivos WHERE uniqueid=? and MD5(shortname)=?';
		$query = $this->db->query($sql,array($uniqueid,$encrypt));
		
		if($query->num_rows() > 0)
		{
			return $query->row();
		}	
		else
		{
			return false;
		}
	}
	
	public function PostAgent($uniqueid, $empresa, $oficina, $agentid,$firstname,$lastname,$shortname,$ingreso,$email,$telefono,$birthday,$puesto,$jornada,$dayoff1,$dayoff2){
		
		$result = $this->LoadAgentId($empresa, $oficina, $agentid);
		
		if($uniqueid > -1)
		{
			echo 'existe ';
			$data = array(
				'idempresa'		=> $empresa,
				'idoficina'		=> $oficina,
	            'idagente' 		=> $agentid,
	            'nombre' 		=> $firstname,
	            'apellidos' 	=> $lastname,
	            'shortname' 	=> $shortname,
	            'ingreso' 		=> $ingreso,
				'email' 		=> $email,
				'telefono'		=> $telefono,
				'birthday'		=> $birthday,
				'puesto' 		=> $puesto,
				'jornada' 		=> $jornada,
				'dayoff1'		=> $dayoff1,
				'dayoff2'		=> $dayoff2,
				'updated' 		=> $date = date('Y-m-d H:i:s'),
				'usuario' 		=> $this->session->userdata('shortname')
	        );
			
			// actualiza la info
			$this->db->set($data);
			$this->db->where(array('uniqueid'  => $uniqueid));
	        $this->db->update('cunop_agentes', $data);

		}
		else
		{
			echo 'no existe';
			$data = array(
				'idempresa'		=> $empresa,
				'idoficina'		=> $oficina,
	            'idagente' 		=> $agentid,
	            'nombre' 		=> $firstname,
	            'apellidos' 	=> $lastname,
	            'shortname' 	=> $shortname,
	            'ingreso' 		=> $ingreso,
				'email' 		=> $email,
				'telefono'		=> $telefono,
				'birthday'		=> $birthday,
				'puesto' 		=> $puesto,
				'jornada' 		=> $jornada,
				'dayoff1'		=> $dayoff1,
				'dayoff2'		=> $dayoff2,
				'updated' 		=> $date = date('Y-m-d H:i:s'),
				'usuario' 		=> $this->session->userdata('shortname')
	        );
			
			$this->db->replace('cunop_agentes', $data);
			//echo $this->db->last_query();
		}
		//echo $this->db->last_query();
		return $this->LoadAgentId($empresa, $oficina, $agentid);
		
	}

	public function UpdateAgentAssignments($idempresa, $idoficina, $uniqueid, $idagente){
		$agent = $this->LoadAgentId($idempresa,$idoficina,$idagente);

		if($agent){
			$workday = $agent[0]['jornada'];

			$data = array(
				'workday' 	=> $workday,
				'updated' 	=> $date = date('Y-m-d H:i:s')
	        );
			
			// actualiza la info
			$this->db->set($data);
			$this->db->where('idempresa',$idempresa)
					 ->where('idoficina',$idoficina)
					 ->where('idagente',$idagente)
					 ->where('fecha>=', date('Y-m-d'))
					 ->where('workday!=',$workday);
	        $this->db->update('cunop_agentscheduler', $data);

			return true;
		}
		else{
			return false;
		}
	}


	public function UpdateDeviceId($uniqueid, $deviceid)
	{

		$data = array(
			'devicetoken' 	=> $deviceid,
			'updated' 		=> $date = date('Y-m-d H:i:s')
        );
		
		// actualiza la info
		$this->db->set($data);
		$this->db->where(array('uniqueid'  => $uniqueid));
        $this->db->update('cunop_agentes', $data);
	}


	public function ReleaseAgent($uniqueid, $empresa, $oficina)
	{
		$data = array(
			'status' 		=> 'RL',
			'updated' 		=> $date = date('Y-m-d H:i:s'),
			'usuario' 		=> $this->session->userdata('shortname')
        );
		
		// actualiza la info
		$this->db->set($data);
		$this->db->where(array('uniqueid'  => $uniqueid));
        $this->db->update('cunop_agentes', $data);

        
        $this->db->where('uniqueid', $uniqueid);
		$query = $this->db->get('cunop_agentes');

	    return $query;
	}

	// ubica a un agente en base a su shortname
	public function FindAgentByShortname($idempresa, $idoficina, $shortname)
	{
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('shortname', $shortname);
		$this->db->where('status', 'OK');
		
		$query = $this->db->get('cunop_agentes');
		//echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
		else
			return [];
	}

	public function GetBirthdayPeople($idempresa, $idoficina)
	{
		$sql = "SELECT shortname,birthday FROM cunop_agentes WHERE status='OK' and idempresa=? and idoficina=? and concat(year(now()),'-',DATE_FORMAT(birthday,'%m-%d')) between DATE_FORMAT(NOW() + INTERVAL -1 DAY,'%Y-%m-%d') and DATE_FORMAT(NOW() + INTERVAL 30 DAY,'%Y-%m-%d') and birthday<>'0000-00-00' order by EXTRACT(month FROM birthday), EXTRACT(day FROM birthday) asc";
		$query = $this->db->query($sql,array($idempresa,$idoficina));
		//echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
		else
			return false;
	}

	public function EnableAccount($idempresa, $idoficina, $uniqueid, $password)
	{
		$data = array(
			'status' 		=> 'OK',
			'perfil'		=> 'usuario',
			'password'		=> sha1($password),
			'updated' 		=> $date = date('Y-m-d H:i:s')
        );
		
		// actualiza la info
		$this->db->set($data);
		$this->db->where('idempresa',$idempresa);
		$this->db->where('idoficina',$idoficina);
		$this->db->where('uniqueid',$uniqueid);
        $this->db->update('cunop_agentes', $data);

        return $this->LoadAgentUniqueId($idempresa, $idoficina, $uniqueid);
	}

	
	public function AddOficinaAdmin($idempresa, $idoficina, $idagente)
	{
		$data = array(
			'idempresa' 	=> $idempresa,
			'idoficina'		=> $idoficina,
			'idagente'		=> $idagente,
			'usuario'		=> 'self',
			'updated' 		=> $date = date('Y-m-d H:i:s')
        );
		
		// actualiza la info
		$this->db->replace('cunop_reladminoficina',$data);
	}

}