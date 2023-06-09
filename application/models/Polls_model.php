<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 		Modelo para el control de las encuestas
 */
class Polls_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	
	public function ConsultarRegistros($idempresa,$idoficina){

		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->order_by('fechalimite', 'DESC');

		$query = $this->db->get('cunop_polls');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function GetCurrentPolls($idempresa,$idoficina,$idagente){

		$sql = 'SELECT p.*,r.idagente FROM `cunop_polls` p left outer join cunop_pollrecords r on p.uniqueid=r.pollid and r.idagente=?  WHERE curdate() between fechainicio and fechalimite and p.idempresa=? and p.idoficina=? and r.idagente is null order by fechalimite desc';

		$query = $this->db->query($sql, array($idagente,$idempresa,$idoficina));
		//echo $this->db->last_query() . PHP_EOL;
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function LoadRow($empresa,$oficina,$uniqueid){

		$this->db->where('idempresa', $empresa);
		$this->db->where('idoficina', $oficina);
		$this->db->where('uniqueid', $uniqueid);

		$query = $this->db->get('cunop_polls');
		if($query->num_rows() > 0)
		{
			return $query->row();
		}	
	}

	public function DeleteRow($empresa,$oficina,$uniqueid){

		$this->db->where('idempresa', $empresa);
		$this->db->where('idoficina', $empresa);
		$this->db->where('uniqueid', $uniqueid);

		$this->db->delete('cunop_polls');
			
	}
	
	public function PostRow($idempresa, $idoficina, $uniqueid, $nombre, $descripcion, $fechainicio, $fechalimite, $opcion1, $opcion2, $opcion3, $opcion4, $opcion5, $opcion6, $opcion7, $opcion8, $opcion9, $opcion10, $opcion11, $opcion12, $opcion13, $opcion14, $opcion15, $opcion16, $opcion17, $opcion18, $opcion19, $opcion20, $usuario){

		if($uniqueid > -1)
		{
			//echo 'existe ';
			$data = array(
				'idempresa'		=> $idempresa,
				'idoficina'		=> $idoficina,
	            'nombre' 		=> $nombre,
	            'descripcion' 	=> $descripcion,
	            'fechainicio' 	=> $fechainicio,
				'fechalimite'	=> $fechalimite,
				'opcion1'		=> $opcion1,
				'opcion2'		=> $opcion2,
				'opcion3'		=> $opcion3,
				'opcion4'		=> $opcion4,
				'opcion5'		=> $opcion5,
				'opcion6'		=> $opcion6,
				'opcion7'		=> $opcion7,
				'opcion8'		=> $opcion8,
				'opcion9'		=> $opcion9,
				'opcion10'		=> $opcion10,
				'opcion11'		=> $opcion11,
				'opcion12'		=> $opcion12,
				'opcion13'		=> $opcion13,
				'opcion14'		=> $opcion14,
				'opcion15'		=> $opcion15,
				'opcion16'		=> $opcion16,
				'opcion17'		=> $opcion17,
				'opcion18'		=> $opcion18,
				'opcion19'		=> $opcion19,
				'opcion20'		=> $opcion20,				
				'updated' 		=> $date = date('Y-m-d H:i:s'),
				'usuario' 		=> $usuario
	        );
			
			// actualiza la info
			$this->db->set($data);
			$this->db->where(array('uniqueid'  => $uniqueid));
	        $this->db->update('cunop_polls', $data);

	        return $this->LoadRow($idempresa, $idoficina, $uniqueid);
		}
		else
		{
			//echo 'no existe';
			$data = array(
				'idempresa'		=> $idempresa,
				'idoficina'		=> $idoficina,
				'nombre' 		=> $nombre,
	            'descripcion' 	=> $descripcion,
	            'fechainicio' 	=> $fechainicio,
				'fechalimite'	=> $fechalimite,
				'opcion1'		=> $opcion1,
				'opcion2'		=> $opcion2,
				'opcion3'		=> $opcion3,
				'opcion4'		=> $opcion4,
				'opcion5'		=> $opcion5,
				'opcion6'		=> $opcion6,
				'opcion7'		=> $opcion7,
				'opcion8'		=> $opcion8,
				'opcion9'		=> $opcion9,
				'opcion10'		=> $opcion10,
				'opcion11'		=> $opcion11,
				'opcion12'		=> $opcion12,
				'opcion13'		=> $opcion13,
				'opcion14'		=> $opcion14,
				'opcion15'		=> $opcion15,
				'opcion16'		=> $opcion16,
				'opcion17'		=> $opcion17,
				'opcion18'		=> $opcion18,
				'opcion19'		=> $opcion19,
				'opcion20'		=> $opcion20,
				'updated' 		=> $date = date('Y-m-d H:i:s'),
				'usuario' 		=> $usuario
	        );

			$this->db->insert('cunop_polls', $data);
			echo $this->db->last_query();
			return $this->LoadRow($idempresa, $idoficina, $this->db->insert_id() );
			//echo $this->db->last_query();
		}
		//echo $this->db->last_query();

		
	}

	public function GetPollResult($pollid)
	{
		$sql = "select " .
		"ifnull((SELECT count(*) as opcion1 FROM `cunop_pollrecords` WHERE pollid=? and opcionelegida=1 group by opcionelegida),0) as opcion1, " . 
		"ifnull((SELECT count(*) as opcion2 FROM `cunop_pollrecords` WHERE pollid=? and opcionelegida=2 group by opcionelegida),0) as opcion2, " .
		"ifnull((SELECT count(*) as opcion3 FROM `cunop_pollrecords` WHERE pollid=? and opcionelegida=3 group by opcionelegida),0) as opcion3, " .
		"ifnull((SELECT count(*) as opcion4 FROM `cunop_pollrecords` WHERE pollid=? and opcionelegida=4 group by opcionelegida),0) as opcion4, " .
		"ifnull((SELECT count(*) as opcion5 FROM `cunop_pollrecords` WHERE pollid=? and opcionelegida=5 group by opcionelegida),0) as opcion5, " .
		"ifnull((SELECT count(*) as opcion6 FROM `cunop_pollrecords` WHERE pollid=? and opcionelegida=6 group by opcionelegida),0) as opcion6, " . 
		"ifnull((SELECT count(*) as opcion7 FROM `cunop_pollrecords` WHERE pollid=? and opcionelegida=7 group by opcionelegida),0) as opcion7, " .
		"ifnull((SELECT count(*) as opcion8 FROM `cunop_pollrecords` WHERE pollid=? and opcionelegida=8 group by opcionelegida),0) as opcion8, " .
		"ifnull((SELECT count(*) as opcion9 FROM `cunop_pollrecords` WHERE pollid=? and opcionelegida=9 group by opcionelegida),0) as opcion9, " .
		"ifnull((SELECT count(*) as opcion10 FROM `cunop_pollrecords` WHERE pollid=? and opcionelegida=10 group by opcionelegida),0) as opcion10, " .
		"ifnull((SELECT count(*) as opcion11 FROM `cunop_pollrecords` WHERE pollid=? and opcionelegida=11 group by opcionelegida),0) as opcion11, " . 
		"ifnull((SELECT count(*) as opcion12 FROM `cunop_pollrecords` WHERE pollid=? and opcionelegida=12 group by opcionelegida),0) as opcion12, " .
		"ifnull((SELECT count(*) as opcion13 FROM `cunop_pollrecords` WHERE pollid=? and opcionelegida=13 group by opcionelegida),0) as opcion13, " .
		"ifnull((SELECT count(*) as opcion14 FROM `cunop_pollrecords` WHERE pollid=? and opcionelegida=14 group by opcionelegida),0) as opcion14, " .
		"ifnull((SELECT count(*) as opcion15 FROM `cunop_pollrecords` WHERE pollid=? and opcionelegida=15 group by opcionelegida),0) as opcion15, " .
		"ifnull((SELECT count(*) as opcion16 FROM `cunop_pollrecords` WHERE pollid=? and opcionelegida=16 group by opcionelegida),0) as opcion16, " . 
		"ifnull((SELECT count(*) as opcion17 FROM `cunop_pollrecords` WHERE pollid=? and opcionelegida=17 group by opcionelegida),0) as opcion17, " .
		"ifnull((SELECT count(*) as opcion18 FROM `cunop_pollrecords` WHERE pollid=? and opcionelegida=18 group by opcionelegida),0) as opcion18, " .
		"ifnull((SELECT count(*) as opcion19 FROM `cunop_pollrecords` WHERE pollid=? and opcionelegida=19 group by opcionelegida),0) as opcion19, " .
		"ifnull((SELECT count(*) as opcion20 FROM `cunop_pollrecords` WHERE pollid=? and opcionelegida=20 group by opcionelegida),0) as opcion20";
		$query = $this->db->query($sql, array($pollid,$pollid,$pollid,$pollid,$pollid,$pollid,$pollid,$pollid,$pollid,$pollid,$pollid,$pollid,$pollid,$pollid,$pollid,$pollid,$pollid,$pollid,$pollid,$pollid));
		//echo $this->db->last_query() . PHP_EOL;
		if($query->num_rows() > 0)
		{
			return $query->row();
		}
	}


	public function PostPollRecord($idempresa,$idoficina,$pollid, $idagente, $opcion, $texto)
	{
		$data = array(
				'pollid' 		=> $pollid,
	            'idagente' 		=> $idagente,
	            'opcionelegida' => $opcion,
				'textoopcion'	=> $texto,
				'fecharegistro' => $date = date('Y-m-d H:i:s')
	        );
			
			$this->db->insert('cunop_pollrecords', $data);
			return $this->LoadRow($idempresa, $idoficina, $this->db->insert_id() );
	}
}