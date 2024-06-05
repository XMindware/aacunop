<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Utilities
{
    const TIMEZONE = -5;
    const POSICIONES_DESCANSO = ['XX', 'VAC', 'FLO', 'HDY', 'INC', 'V', 'V-', 'TRA', 'PASS'];

    const POSICIONES_VACACION = ['VAC', 'V', 'V-'];

    const POSICIONES_CON_COMIDA = ['FT', '7H'];

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
        
        // Load models and helper
        $this->CI->load->model(['Agentes_model','Timeswitch_model','Posiciones_model']);
        $this->CI->load->helper(['url']);
    }

    public function convertIntDateToString(string $date, int $time, int $tz = self::TIMEZONE): string
    {

        $fullhora = $time + ($tz * 3600);
        $hora = intval($fullhora / 3600);
        $minutes = ceil((($fullhora / 3600) - $hora) * 60);

        // if hora is greater than 24, then we have to add a day
        if ($hora >= 24) {
            $hora = $hora - 24;
            $date = date('Y-m-d', strtotime($date . ' +1 day'));
        }
        
        $hora = str_pad($hora, 2, "0", STR_PAD_LEFT);
        $minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);
        $fullhora = $hora . ":" . $minutes;

        return $date . ' ' . $fullhora;
    }

    public function convertIntTimeToString(int $time, int $tz = self::TIMEZONE): string
    {

        $fullhora = $time - ($tz * 3600);
        $hora = intval($fullhora / 3600);
        $minutes = ceil((($fullhora / 3600) - $hora) * 60);

        $hora = str_pad($hora, 2, "0", STR_PAD_LEFT);
        $minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);
        $fullhora = $hora . ":" . $minutes;

        return $fullhora;
       
    }

    public function convertStringDateToInt(string $date): int
    {
        $hora = date('H:i', strtotime($date));
        $hora = explode(':', $hora);
        $hora = (intval($hora[0]) * 3600 - self::TIMEZONE * 3600) + intval($hora[1]) * 60;
        return $hora;
    }
    
    public function GetFullDatetime(string $fechaHora, int $time): string
    {
        $tz = getenv('TIMEZONE');
        if ($tz == false) {
            $tz = self::TIMEZONE;
        }

        $fullhora = $time + ($tz * 3600);
        $hora = intval($fullhora / 3600);
        $minutes = ceil((($fullhora / 3600) - $hora) * 60);;
        // if hora is greater than 24, then we have to add a day
        if ($hora >= 24) {
            $hora = $hora - 24;
            $date = date('Y-m-d', strtotime($fechaHora . ' +1 day'));
        }
        $hora = str_pad($hora, 2, "0", STR_PAD_LEFT);
        $minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);
        $fullhora = $hora . ":" . $minutes;
        $date = date('Y-m-d', strtotime($fechaHora));
        return $date . ' ' . $fullhora;
    }

    public function convertStringTimeToInt($time): int {

        $tz = getenv('TIMEZONE');
        if ($tz == false) {
            $tz = self::TIMEZONE;
        }
        // Split the time into hours and minutes
        list($hours, $minutes) = explode(':', $time);
    
        // Convert hours and minutes back to seconds
        $totalSeconds = (int)$hours * 3600 + (int)$minutes * 60;

        // Add the timezone offset
        $totalSeconds = $totalSeconds + ($tz * 3600);
    
        return $totalSeconds;
    }

    public function getTimesFromPosicion(string $codigo) {
        $posicion = $codigo;

        $tz = getenv('TIMEZONE');
        if ($tz == false) {
            $tz = self::TIMEZONE;
        }
        
        $horaInicial = (int)substr($codigo, 0, 2);
        if((int)$horaInicial >= 24){
            $horaInicial = (int)substr($codigo, 0, 1);
        }
        $codigo = substr($codigo, 2);
        $tipo = substr($codigo, -1);
        
        switch ($tipo) {  // 2126H
            case 'P':
                $horas = '4';
                $codigo = substr($codigo, 0, -1);
                break;
            case 'F':
                $horas = '8';
                $codigo = substr($codigo, 0, -1);
                break;
            case '5':
                $horas = '5';
                $codigo = substr($codigo, 0, -1);
                break;
            case '6':
                $horas = '6';
                $codigo = substr($codigo, 0, -1);
                break;
            default:
                $horas = substr($codigo, -2, -1);
                $codigo = substr($codigo, 0, -2);
                break;
        }       

        $minutes = substr($codigo, -1);
        
        switch ($minutes) {
            case '1':
                $minutes = 15;
                $codigo = substr($codigo, 0, -1);
                break;
            case '2':
                $minutes = 30;
                $codigo = substr($codigo, 0, -1);
                break;
            case '3':
                $minutes = 45;
                $codigo = substr($codigo, 0, -1);
                break;
            default:
                $minutes = 00;                
                break;
        }
        

        $starttime = intval($horaInicial * 3600) + intval($minutes * 60) - ($tz * 3600);
        // if horas is not numeric
        if (!is_numeric($horas)) {
            $horas = 0;
        }
        $endtime = $starttime + ($horas * 3600);
        $horasdiurnasnocturnas = $this->horasDiurnasNocturnas($starttime, $endtime);

        $result = new StdClass();
        $result->starttime = $starttime;
        $result->endtime = $endtime;
        $result->diurnas = $horasdiurnasnocturnas->diurnas;
        $result->nocturnas = $horasdiurnasnocturnas->nocturnas;
        return $result;
    }

    public function horasDiurnasNocturnas(int $starttime, int $endtime): object
    {
        $horasdiurnas = 0;
        $horasnocturnas = 0;
        $horafin = $endtime;
        while($starttime < $horafin)
        {   
            if($starttime >= 21600 && $starttime <= 72000)
                $horasdiurnas+=0.5;
            else
                $horasnocturnas+=0.5;               
            $starttime = $starttime + 1800;                  
        }

        $result = new StdClass();
        $result->diurnas = $horasdiurnas;
        $result->nocturnas = $horasnocturnas;
        
        return $result;
    }

    public function isNoWorkPosition(string $posicion): bool {
        return $posicion == 'XX' || in_array($posicion, self::POSICIONES_DESCANSO);
    }

    public function IsVacationPosition(string $posicion): bool {
        return in_array($posicion, self::POSICIONES_VACACION);
    }

    public function canHaveMealTime(string $posicion): bool {
        return in_array($posicion, self::POSICIONES_CON_COMIDA);
    }
    public function isEmptyDatetime(string $datetime): bool {
        return $datetime == '0000-00-00 00:00:00';
    }

    public function filterUnnecessaryPositions(array $monthly): array
    {
        $result = [];
        $lastFecha = null;
        foreach ($monthly as $day) {
            if ($lastFecha == $day['fecha']) {                
                continue;
            }
            $dayArray = array_filter($monthly, function ($item) use ($day) {
                return $item['fecha'] == $day['fecha'];
            });
            
            $workingPositionFound = false;
    
            if(count($dayArray) == 1){
                $result[] = $day;
                continue;
            }

            // if there is a descanso (XX) position, remove it                   
            foreach ($dayArray as $item) {
                if (!in_array($item['posicion'], self::POSICIONES_DESCANSO)) {
                    $result[] = $item;
                }
            }
            $lastFecha = $day['fecha'];
        }
    
        return $result;
    }


	public function calcularCuotaPermiso(object $posicionInicial, object $posicionSolicitada, object $trade, int $idempresa, int $idoficina): object
	{
		$horas = new stdClass();
        $result = new stdClass();
        $result->cuota = new stdClass();
        $result->permiso = new stdClass();

        /** 
         * Posicion inicial cuota
         */
        if(isset($posicionInicial->diurna)){
            $result->permiso->d = $posicionInicial->diurna;
            $result->permiso->n = $posicionInicial->nocturna;
        }
        else{
            $starttime = $this->GetFullDatetime($trade->fechacambio, $posicionInicial->starttime);
            $endtime = $this->GetFullDatetime($trade->fechacambio, $posicionInicial->endtime);
            $horasdiurnas = 0;
            $horasnocturnas = 0;
            $minutos = date('i', strtotime($starttime));            
            if($minutos > 0 && $minutos < 30)
                $starttime = date('Y-m-d H:i:s', strtotime($starttime . ' + ' . (integer)(30 - $minutos) . ' minutes'));
            else if($minutos > 30)
                $starttime = date('Y-m-d H:00:00', strtotime($starttime . ' + 1 hour'));

            // if endttime is less than starttime, add 24 hours to endtime
            if($endtime < $starttime)
                $endtime = date('Y-m-d H:i:s', strtotime($endtime . ' + 24 hours'));
            
            $horafin = $endtime;
            while($starttime < $horafin)
            {
                $hora = date('H:i', strtotime($starttime));
                
                if($hora >= '06:00' && $hora <= '20:00')
                    $horasdiurnas+=0.5;
                else
                    $horasnocturnas+=0.5;               
                $starttime = date('Y-m-d H:i:s', strtotime($starttime . ' + 30 minutes'));                    
            }

            if(isset($posicionInicial->workday)){
                switch($posicionInicial->workday){
                    case 'FT' : $horasjornada = 8; break;
                    case 'PT' : $horasjornada = 4; break;
                    case '6H' : $horasjornada = 6; break;
                    case '5H' : $horasjornada = 5; break;
                }
            }
            else{
                $agente = $this->CI->Agentes_model->LoadValidAgentId($idempresa, $idoficina, $trade->idagente);
                
                switch($agente->jornada){
                    case 'FT' : $horasjornada = 8; break;
                    case 'PT' : $horasjornada = 4; break;
                    case '6H' : $horasjornada = 6; break;
                    case '5H' : $horasjornada = 5; break;
                }
            }

            //echo 'horasjornada: ' . $horasjornada . ' horasdiurnas: ' . $horasdiurnas . ' horasnocturnas: ' . $horasnocturnas . PHP_EOL;
            $result->permiso->d = $horasdiurnas;
            $result->permiso->n = $horasnocturnas;
        }
        /**
         * Posicion solicitada cuota         
         */
        if(isset($posicionSolicitada->diurna)){
            $result->cuota->d = $posicionSolicitada->diurna;
            $result->cuota->n = $posicionSolicitada->nocturna;
        }
        else{
            $starttime = $this->GetFullDatetime($trade->fechacambio, $posicionSolicitada->starttime);
            $endtime = $this->GetFullDatetime($trade->fechacambio, $posicionSolicitada->endtime);
            $horasdiurnas = 0;
            $horasnocturnas = 0;

            $minutos = date('i', strtotime($starttime));            
            if($minutos > 0 && $minutos < 30)
                $starttime = date('Y-m-d H:i:s', strtotime($starttime . ' + ' . (integer)(30 - $minutos) . ' minutes'));
            else if($minutos > 30)
                $starttime = date('Y-m-d H:00:00', strtotime($starttime . ' + 1 hour'));

            // if endttime is less than starttime, add 24 hours to endtime
            if($endtime < $starttime)
                $endtime = date('Y-m-d H:i:s', strtotime($endtime . ' + 24 hours'));
            
            $horafin = $endtime;
            while($starttime < $horafin)
            {
                $hora = date('H:i', strtotime($starttime));
                
                if($hora >= '06:00' && $hora <= '20:00')
                    $horasdiurnas+=0.5;
                else
                    $horasnocturnas+=0.5;               
                $starttime = date('Y-m-d H:i:s', strtotime($starttime . ' + 30 minutes'));                    
            }

            if(isset($posicionSolicitada->workday)){
                switch($posicionSolicitada->workday){
                    case 'FT' : $horasjornada = 8; break;
                    case 'PT' : $horasjornada = 4; break;
                    case '6H' : $horasjornada = 6; break;
                    case '5H' : $horasjornada = 5; break;
                }
            }
            else{
                $agente = $this->CI->Agentes_model->LoadValidAgentId($idempresa, $idoficina, $trade->idagente);
                
                switch($agente->jornada){
                    case 'FT' : $horasjornada = 8; break;
                    case 'PT' : $horasjornada = 4; break;
                    case '6H' : $horasjornada = 6; break;
                    case '5H' : $horasjornada = 5; break;
                }
            }
            //echo 'horasjornada: ' . $horasjornada . ' horasdiurnas: ' . $horasdiurnas . ' horasnocturnas: ' . $horasnocturnas . PHP_EOL;
            $result->cuota->d = $horasdiurnas;
            $result->cuota->n = $horasnocturnas;
        }

        return $result;
	}

    public function parsePosition($position) {

        // Extract the time and duration components from the position string
        // This regex will match both formats: "142H" and "1422H"
        preg_match('/^(\d{2})(\d{0,1})(\d+)H$/', $position, $matches);
    
        if (count($matches) !== 4) {
            return [];
        }
    
        // Extract hours, optional minutes and duration
        $hour = (int)$matches[1];
        $minuteSegment = $matches[2];
        $minuteOffset = strlen($minuteSegment) === 1 ? (int)$minuteSegment * 15 : 0; // Convert 1,2,3,4 to 0, 15, 30, 45 minutes
        $duration = (int)$matches[3];
    
        // Create DateTime object for start time
        $startTime = new DateTime();
        $startTime->setTime($hour, $minuteOffset);
    
        // Clone the start time to calculate end time
        $endTime = clone $startTime;
        $endTime->modify("+{$duration} hour");
    
        return [
            'starttime' => $this->convertStringTimeToInt($startTime->format('H:i')),
            'endtime' => $this->convertStringTimeToInt($endTime->format('H:i'))
        ];
    }

    public function emailTemplate($content): string 
    {
        $html = <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    margin: 0;
                    padding: 20px;
                    background-color: #f4f4f4;
                }                
                .email-container {
                    max-width: 600px;
                    margin: 0 auto;
                    background-color: #fff;
                    padding: 20px;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                }
                .email-header {
                    font-size: 20px;
                    font-weight: bold;
                    margin-bottom: 20px;
                }
                .email-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                .email-table th, .email-table td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }
                .email-table th {
                    background-color: #f2f2f2;
                }
            </style>
        </head>
        <body>
            
            <div class="email-container">
                <div class="email-header">Webroster United MEX Notification</div>
                $content
            </div>
            <p style="text-align: center; margin-top: 20px;">This email was sent automatically by Webroster.</p>
        </body>
        </html>
        HTML;

        return $html;
    }

    public function getCDMXTime(string $format): string {
        $date = new DateTime('now', new DateTimeZone('America/Mexico_City'));
        // substract 1 hour to get the correct time
        $date->modify('-1 hour');
        return $date->format($format);
    }
}