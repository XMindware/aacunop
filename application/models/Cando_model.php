<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 	XALFEIRAN Marzo 2016
 */
class Cando_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	
	public function CompanySkills($empresa){
		$this->db->where('idempresa',$empresa);
		$this->db->order_by('orden', 'ASC');
		$this->db->order_by('code', 'ASC');
		$query = $this->db->get('cunop_cando');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}
	
	public function PostRow($idempresa,$code,$orden,$description,$usuario){
		
		$data = array(
            'idempresa' 	=> $idempresa,
            'code' 			=> $code,
            'orden'			=> $orden,
            'description' 	=> $description,
            'updated' 		=> $date = date('Y-m-d H:i:s'),
			'usuario' 		=> $usuario
        );
		
		// actualiza la info
        $this->db->replace('cunop_cando', $data);
		
		//echo $this->db->last_query();
		
		return $this->LoadRowId($idempresa, $code);
	}
	
	public function AddSkillAgente($idempresa,$idoficina,$idagente,$idcando){
		
		$data = array(
            'idempresa' 	=> $idempresa,
            'idoficina' 	=> $idoficina,
            'idagente' 		=> $idagente,
			'idcando'		=> $idcando,
			'okusuario'		=> $this->session->userdata('shortname'),
            'updated' 		=> $date = date('Y-m-d H:i:s'),
			'usuario' 		=> $this->session->userdata('idagente')
        );
		
		// actualiza la info
        $this->db->replace('cunop_relcandoagents', $data);
		
		echo $this->db->last_query();
		
		return $this->LoadRelCandoAgentsRowId($idempresa,$idoficina,$idagente);
	}
	
	public function RemoveSkillAgente($idempresa,$idoficina,$idagente,$idcando){
		
		$this->db->where('idempresa',$idempresa);
		$this->db->where('idoficina',$idoficina);
		$this->db->where('idagente',$idagente);
		$this->db->where('idcando',$idcando);
		
		// actualiza la info
        $this->db->delete('cunop_relcandoagents');

        //return $this->LoadRelCandoAgentsRowId($idempresa,$idoficina,$idagente);
		
		echo $this->db->last_query();	
	}
	
	public function LoadRelCandoAgentsRowId($idempresa,$idoficina,$idagente){
		$this->db->where('idempresa',$idempresa);
		$this->db->where('idoficina',$idoficina);
		$this->db->where('idagente',$idagente);
		
		$query = $this->db->get('cunop_relcandoagents');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function LoadRowId($idempresa,$code){
		$this->db->where('idempresa',$idempresa);
		$this->db->where('code',$code);
		
		$query = $this->db->get('cunop_cando');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}
	
	// obtiene todos los skills de un agente
	public function LoadCandoAgent($idempresa,$idoficina,$idagente)
	{
		$query = $this->db->query("select cunop_cando.code,cunop_cando.description from cunop_relcandoagents inner join cunop_cando on " .
				" cunop_relcandoagents.idcando" .
				"=cunop_cando.code where cunop_relcandoagents.idempresa=" . $idempresa . " and cunop_relcandoagents.idoficina=" . $idoficina . " and " .
				"cunop_relcandoagents.idagente=" . $idagente . " order by cunop_cando.code ");			
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	/// como la anterior pero mejorada en el agente
	public function GetAgentSkills($idempresa,$idoficina,$idagente){

		$sql = 'SELECT p.idcando FROM cunop_relcandoagents p inner join cunop_agentes a on a.uniqueid=p.idagente 
where a.idempresa=? and a.idoficina=? and a.idagente=? ';

		$query = $this->db->query($sql,array($idempresa,$idoficina,$idagente));
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}

	}

	public function batchupdateskills()
	{
		$agentes = $this->db->get('cunop_agentes');
		$arragentes = $agentes->result_array();
		foreach ($arragentes as $eachagente) {
			$uniqueid = $eachagente['uniqueid'];
			$idagente = $eachagente['idagente'];

			$data = array(
				'idagente' 	=> $uniqueid
				);
			$this->db->where('idempresa', $eachagente['idempresa']);
			$this->db->where('idempresa', $eachagente['idoficina']);
			$this->db->where('idagente', $idagente);
			$this->db->update('cunop_relcandoagents', $data);
		}
	}
	
	
	public function DeleteRowId($idempresa,$code){
		$this->db->where('idempresa',$idempresa);
		$this->db->where('code',$code);
		
		$query = $this->db->delete('cunop_cando');
		
	}
}