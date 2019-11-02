
#!/usr/bin/perl

use JSON qw(from_json);
use LWP::UserAgent;
use Time::localtime;
use Time::gmtime;
use feature qw(say);
use LWP::Simple;

$tm = localtime;
if(($tm->mon)+1<9){
	$mes = '0' . (($tm->mon)+1);
	}
else
	{$mes = (($tm->mon)+1);}
if((($tm->mday)+1)<9)
	{$dia = '0' . (($tm->mday)+1);}
else
	{$dia =(($tm->mday)+1);}

$fecha = $tm->year+1900 . "-" . $mes . "-" . $dia;

say $fecha;
$gmt = gmtime();
my $server_endpoint = 'https://www.mindware.com.mx/apps/vuelos/vuelosfechaestacion?fecha=' . $fecha . '&iatacode=CUN';
say ("The current time is " . $gmt->hour . ':' . $gmt->min . ':' . $gmt->sec); 
 
# set custom HTTP request header fields
my $ua = LWP::UserAgent->new(ssl_opts => { verify_hostname => 0 });
my $req = HTTP::Request->new(GET => $server_endpoint);

$thora = ($gmt->hour * 3600) + ($gmt->min * 60);
if($gmt->hour>9)
	{$hora = $gmt->hour;}
else
	{$hora = "0" . $gmt->hour;}
if($gmt->min>9)
	{$min = $gmt->min;}
else
	{$min = "0" . $gmt->min;}
	
$ltime = $hora . ':' . $min;
$req->header('content-type' => 'application/json');
 
my $resp = $ua->request($req);
if ($resp->is_success) {
	$utc = 10;
    my $message = $resp->decoded_content;
    #print "Received reply: $message\n";
    $json = from_json($message);
    foreach my $item(@$json)
    {
    	$hora = int($item->{horasalida}) + ($utc * 3600);
		$horasalida = int($hora);
		$hora = int($horasalida / 3600) ;
		$minutes = int((($horasalida / 3600) - $hora) * 60);
		$stime = ($hora<=9?('0' . $hora) : $hora) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
		$fhora = $item->{horasalida} + ($utc * 3600);
		say '- ' . $item->{idvuelo} . ' ' . (($thora < $fhora)? 'VIGENTE' : 'OLD') . ' L:' . $ltime . ' H:' . $stime;
			if($thora < $fhora)
			{
				$res = getInfoVuelo($item->{idvuelo},$fecha);
			}
    }
}
else {
    print "HTTP GET error code: ", $resp->code, "\n";
    print "HTTP GET error message: ", $resp->message, "\n";
}


sub getInfoVuelo
{
	$idvuelo = $_[0];
	$fecha = $_[1];
	my $vuelo = substr $idvuelo,2;
	my $dia = substr $fecha,8,2;  
	my $mes = substr $fecha,5,2;
	my $ano = substr $fecha,0,4;

	#$json = json_decode(file_get_contents('assets/testdata.json' ));
	$server_endpoint = 'https://api.flightstats.com/flex/flightstatus/rest/v2/json/flight/status/AA/' . $vuelo . '/dep/' . $ano . '/' . $mes . '/' . $dia . '?appId=d8358f80&appKey=f0437a765f84a757a807d12b23d4c879&utc=false&airport=CUN&codeType=IATA';
 
	# set custom HTTP request header fields
	my $ua = LWP::UserAgent->new(ssl_opts => { verify_hostname => 0 });
	my $req = HTTP::Request->new(GET => $server_endpoint);
	$req->header('content-type' => 'application/json');
	 
	my $resp = $ua->request($req);
	if ($resp->is_success) {
		say 'success';
		my $message = $resp->decoded_content;
		#say $message;

		open(my $fh, '> /home/mindware/www/apps/assets/flightstatus/' . $idvuelo . '.json')
			or die "No pudo abrir el archivo " . '~/www/apps/assets/flightstatus/' . $idvuelo . $!;
		print $fh $message;
		close $fh;
	}
	else {
	    say "HTTP GET error code: " . $resp->code;
	    say "HTTP GET error message: " . $resp->message;
	}

	#say $url;
	return 1;
	#$res = file_put_contents("assets/flightstatus/" . $idvuelo . ".json", file_get_contents($url));
}
