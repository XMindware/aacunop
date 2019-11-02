<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 	XALFEIRAN Marzo 2016
 */
class Vuelos_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	
	public function LoadVuelos($estacion, $fecha = null){

		if(!is_null($fecha))
		{
			$this->db->where('begindate<=',$fecha);
			$this->db->where('enddate>=',$fecha);
		}	
		$this->db->where('origen',$estacion);
		$this->db->order_by('horasalida','ASC');
		$query = $this->db->get('cunop_vuelos');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}
	
	public function PostVuelo($code,$origen,$horasalida,$destino,$duracionvuelo,$lun,$mar,$mie,$jue,$vie,$sab,$dom,$begindate,$enddate){
		
		$data = array(
            'idvuelo' 		=> $code,
            'origen' 		=> $origen,
            'horasalida' 	=> $horasalida,
            'destino' 		=> $destino,
            'duracionvuelo' => $duracionvuelo,
            'lun' 			=> $lun,
            'mar' 			=> $mar,
            'mie' 			=> $mie,
			'jue'			=> $jue,
			'vie'			=> $vie,
			'sab'			=> $sab,
			'dom'			=> $dom,
			'begindate'		=> $begindate,
			'enddate'		=> $enddate,
			'altausuario'	=> $this->session->userdata('shortname'),
			'altadate'		=> $date = date('Y-m-d H:i:s'),
            'updated' 		=> $date = date('Y-m-d H:i:s'),
			'usuario' 		=> $this->session->userdata('shortname')
        );
		
		// actualiza la info
        $this->db->replace('cunop_vuelos', $data);
		
		//echo $this->db->last_query();
		
		return $this->LoadVueloCode($code);
	}
	
	public function LoadVueloCode($idvuelo){
		
		$this->db->where('idvuelo',$idvuelo);
		
		$query = $this->db->get('cunop_vuelos');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function LoadVuelosFechaEstacion($origen, $qdate)
	{
		$sql = 'SELECT v . * FROM cunop_vuelos v INNER JOIN cunop_distribucionvuelos d ON v.idvuelo = d.idvuelo WHERE d.fecha = ? and origen = ? ORDER BY v.horasalida';
		$query = $this->db->query($sql,Array($qdate,$origen));
		//echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}
	
	
	public function DeleteRowId($idvuelo,$origen){
		$this->db->where('idvuelo',$idvuelo);
		$this->db->where('origen',$origen);
		
		$query = $this->db->delete('cunop_vuelos');
		
	}
}