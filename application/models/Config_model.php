<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 */
class Config_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	
	public function GetEmpresas(){

		$query = $this->db->get('cunop_empresas');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}
	
	public function GetOficinasEmpresa($empresa){

		$this->db->where('idempresa',$empresa);
		$query = $this->db->get('cunop_oficinas');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function LastLogins()
	{
		$sql = "select * from cunop_bitacoraingresos where updated > date_sub(now(), interval 3 minute)";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}

	public function Totales()
	{
		$sql = "SELECT count(*) as total,'total' as perfil from cunop_agentes union SELECT count(*) as total, perfil as fuera FROM `cunop_agentes` group by perfil";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			$data = $query->result_array();
			$result  = "Agentes totales " . $data[0]['total'];
			$result .= ". Sin acceso " . $data[1]['total'];
			$result .= ". Usuarios admin " . $data[2]['total'];
			$result .= ". Usuarios std " . $data[3]['total'];
			
			return $result;
		}
	}
	
	public function LoadOficinaIdEmpresa($empresa,$oficina){
		$this->db->where('idempresa',$empresa);
		$this->db->where('idoficina',$oficina);

		$query = $this->db->get('cunop_oficinas');
		//print_r($this->db->last_query());
		//$query = $this->db->query('select * from cunop_agentes where idagente='.$agentid);
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	// utilerias para resetear los skills de los agentes

	public function SetMinimumPos($idempresa, $idoficina)
	{

		$allagents = $this->GetAgents($idempresa,$idoficina);

		foreach ($allagents as $agente) {
			echo $agente['idagente'] . " " . $agente['uniqueid'] . "\n";
			$candos = $this->GetAgentCando($idempresa,$idoficina,$agente['uniqueid']);
			//echo "    " . $candos[0]['idcando'] . "\n";

			$foundcando = false;
			
			if(sizeof($candos) > 0)
			{
				foreach ($candos as $eachcando)
				{
					if($eachcando['idcando'] == 'M')
						$foundcando = true;

				}
			}
			if (!$foundcando)
			{
				echo "  agregar M\n";
				$this->SetCandoAgent($idempresa,$idoficina,$agente['uniqueid'],"M","XALFEIRAN");
			}

			$foundcando = false;
			if(sizeof($candos) > 0)
			{
				foreach ($candos as $eachcando)
				{
					if($eachcando['idcando'] == 'G')
						$foundcando = true;

				}
			}
			if (!$foundcando)
			{
				echo "  agregar G\n";
				$this->SetCandoAgent($idempresa,$idoficina,$agente['uniqueid'],"G","XALFEIRAN");
			}
		}
	}


	public function ListaAgentesSinAcceso($idempresa, $idoficina)
	{
		$this->db->where('idempresa',$idempresa);
		$this->db->where('idoficina',$idoficina);
		$this->db->where('perfil','');

		$query = $this->db->get('cunop_agentes');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function GetAgents($idempresa,$idoficina)
	{
		$this->db->where('idempresa',$idempresa);
		$this->db->where('idoficina',$idoficina);

		$query = $this->db->get('cunop_agentes');
		//print_r($this->db->last_query());
		//$query = $this->db->query('select * from cunop_agentes where idagente='.$agentid);
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}
	
	public function GetAgentCando($idempresa,$idoficina,$idagente)
	{
		$this->db->where('idempresa',$idempresa);
		$this->db->where('idoficina',$idoficina);
		$this->db->where('idagente',$idagente);

		$query = $this->db->get('cunop_relcandoagents');
		//print_r($this->db->last_query());
		//$query = $this->db->query('select * from cunop_agentes where idagente='.$agentid);
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function HasCandoAgent($idempresa,$idoficina,$idagente,$cando)
	{
		$this->db->where('idempresa',$idempresa);
		$this->db->where('idoficina',$idoficina);
		$this->db->where('idagente',$idagente);
		$this->db->where('idcando',$cando);

		$query = $this->db->get('cunop_relcandoagents');
		print_r($this->db->last_query());
		//$query = $this->db->query('select * from cunop_agentes where idagente='.$agentid);
		if($query->num_rows() > 0)
		{
			return $query->row();
		}	
	}

	public function SetCandoAgent($idempresa,$idoficina,$idagente,$idcando,$user)
	{
		$data = array(
            'idempresa' 	=> $idempresa,
            'idoficina' 	=> $idoficina,
            'idagente' 		=> $idagente,
			'idcando'		=> $idcando,
			'okusuario'		=> $user,
            'updated' 		=> $date = date('Y-m-d H:i:s'),
			'usuario' 		=> $user
        );
		
		// actualiza la info
        $this->db->replace('cunop_relcandoagents', $data);
	}
}