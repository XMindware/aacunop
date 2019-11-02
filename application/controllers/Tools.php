<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 *	Controller de Descansos
 *  XALFEIRAN 2016
 */
class Tools extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Webcunop_model');
		$this->load->model('Vuelos_model');
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
	}

	public function test()
	{
		echo 'hola ' . date('Y-m-d h:i e').PHP_EOL;
	}
	
	public function index()
	{
		$estacion = 'CUN';
		$qdate = date('Y-m-d');
		echo date_default_timezone_get() . PHP_EOL;
		echo date('Y-m-d h:i e').PHP_EOL;
		$ltime = date('G:i');

		$utc = 10;
		$main = $this->Vuelos_model->LoadVuelosFechaEstacion($estacion, $qdate);

		foreach ($main as $flight) {
			//print_r($flight);
			
			$hora = intval($flight['horasalida']) + ($utc * 3600);
			$horasalida = intval($hora);
			$hora = intval($horasalida / 3600) ;
			$minutes = (($horasalida / 3600) - $hora) * 60;
			$stime = ($hora<=9?('0' . $hora) : $hora) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
			

			//$stime = gmdate('G:i', strtotime($flight['salida']));
			echo '- ' . $flight['idvuelo'] . ' ' . (($ltime < $stime)? 'VIGENTE' : 'OLD') . ' L:' . $ltime . ' H:' . $stime . PHP_EOL;
			if($ltime < $stime)
			{
				$res = $this->getFlightStatus($flight['idvuelo'],$qdate);
				if($res>0)
					echo 'FlighStatus saved' . PHP_EOL;
				else
					echo '--- ERROR FLGSTATUS' . PHP_EOL;
			}

		}
	}

	public function GetFlightStatus($idvuelo,$fecha)
	{
		$vuelo = substr($idvuelo,2);
		$dia = substr($fecha,8,2);   // 2017-05-05
		$mes = substr($fecha,5,2);
		$ano = substr($fecha,0,4);

		//$json = json_decode(file_get_contents('assets/testdata.json' ));
		$url = 'https://api.flightstats.com/flex/flightstatus/rest/v2/json/flight/status/AA/' . $vuelo . '/dep/' . $ano . '/' . $mes . '/' . $dia . '?appId=d8358f80&appKey=f0437a765f84a757a807d12b23d4c879&utc=false&airport=CUN&codeType=IATA';
		//echo $url;
		$res = file_put_contents("assets/flightstatus/" . $idvuelo . ".json", file_get_contents($url));
		return $res;
		//$flightstatus = $json->flightStatuses[0];
	}
}


?>