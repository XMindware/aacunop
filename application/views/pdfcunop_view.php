<?
    error_reporting(E_ERROR | E_PARSE);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   
    <meta name="description" content="AA Agents Control">
    <meta name="author" content="Xavier Alfeiran mindware.com.mx">

    <title><? echo $titulo; ?></title>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<? echo base_url(); ?>assets/js/bootstrap.min.js"></script>

    <script src="<? echo base_url(); ?>assets/js/jquery.hotkey.js"></script>
    <!-- Bootstrap core CSS -->
    <link href="<? echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<? echo base_url(); ?>assets/css/sticky-footer-navbar.css" rel="stylesheet">
    <link href="<? echo base_url(); ?>assets/css/loading.css" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link href="<? echo base_url(); ?>assets/css/navbar-fixed-top.css" rel="stylesheet">
    
  <script src="<? echo base_url(); ?>assets/js/moment.js"></script>
  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-80040509-1', 'auto');
    ga('send', 'pageview');

  </script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
      .navbar-brand>img {
         max-height: 100%;
         height: 100%;
         width: auto;
         margin: 0 auto;


         /* probably not needed anymore, but doesn't hurt */
         -o-object-fit: contain;
         object-fit: contain; 

      }
</style>
  </head>

  <body>


    <?
        if(!isset($fecha) || $fecha=='')
            $fecha = date('Y-m-d');
    ?>
    <div class="container">
        <div class="panel-body">
            <div class="panel-heading">
            	<div class="row">
                <h2><span class="label label-primary">CUNOP</span></h2>
                <input type="hidden" id="inputIdEmpresa" value="<? echo $idempresa; ?>" />
                <input type="hidden" id="inputIdOficina" value="<? echo $idoficina; ?>" />
                <input type="hidden" id="inputUsuario" value="<? echo $idusuario; ?>" />
                </div>
            </div>           

            <? 
              if($fecha!='')
            { ?>
            <div class="row">
            	<div class="col-md-12">
                    <table class="table table-condensed">
                    <!-- HEADER -->
                    <thead>
                      <tr>
                        <th colspan=5><center>Date</center></th>
                      </tr>
                    </thead>
                  
                    <tbody>
                      <tr>
                        <td colspan='5'><center><? echo date('l jS \of F Y', strtotime($fecha)) ?></center></td>
                        <input type="hidden" id="inputFlightDate" name="inputFlightDate" value="<? echo $fecha; ?>" />
                      </tr>
 
                    </tbody>
                </table>
                <!-- DETAIL -->
                <table class="table table-condensed">
                    <thead>
                      <tr>
                        <th>Flight</th>
                        <th>Agents</th>
                        <th>Departure</th>
                        <th>Positions</th>
                      </tr>
                    </thead>
                    <?

						foreach($mainlist as $header)
						{
					?>
                    <tbody>
                      <tr <? if(isset($header['mensaje'])) echo 'class="warning"'; ?>>
                        <td><button type="button" onclick="loadRowVuelo('<? echo $header['idvuelo']; ?>','<? echo $header['linea']; ?>','<? echo $header['fecha']; ?>');" class="btn btn-link"><? echo $header['idvuelo']; ?></button></td>
                        <?
                            if(isset($header['mensaje']))
                            {
                                ?>
                                    <td colspan=3><strong><? echo $header['mensaje']; ?></strong></td>
                                <?
                            }
                            else
                            {
                                ?>
                                    <td><? echo $header['idagent1'] . ' / ' . $header['idagent2'] . ' / ' . $header['idagent3']; ?></td>
                                    <td><? echo $header['salida']; ?></td>
                                    <td><? echo $header['pos1'] . ' / ' . $header['pos2']; ?></td>
                                <?
                            }
                            ?>
                      </tr>
                      <? } ?>
                    </tbody>
                </table>
                <!-- AGENTS -->
                <table class="table table-condensed">
                    <thead>
                      <tr>
                        <th colspan='9'><center>AGENTS / POSITIONS</center></th>
                      </tr>
                    </thead>
                    <?
                    	$counter = 0;
                    	do
                    	{

                    		if(isset($agentslist[$counter])) $header = $agentslist[$counter++]; else unset($header);
                    		if(isset($agentslist[$counter])) $header2 = $agentslist[$counter++]; else unset($header2);
                    		if(isset($agentslist[$counter])) $header3 = $agentslist[$counter++]; else unset($header3);
					?>
                    <tbody>
                      <tr>
                        <td><? if(isset($header))  echo $header['shortname'] .  '</td><td>' . $header['asignacion'] .  "</td><td style='font-weight:bold'>" .  $header['posicion']; ?></td>
                        <td><? if(isset($header2)) echo $header2['shortname'] . '</td><td>' . $header2['asignacion'] . "</td><td style='font-weight:bold'>"  . $header2['posicion']; ?></td>          
                        <td><? if(isset($header3)) echo $header3['shortname'] . '</td><td>' . $header3['asignacion'] . "</td><td style='font-weight:bold'>"  . $header3['posicion']; ?></td>
                      </tr>
                      <? }while($counter<sizeof($agentslist)) ?>
                    </tbody>
                </table>
                <!-- BMAS -->
                <table class="table table-condensed">
                    <thead>
                      <tr>
                        <th colspan='6'><center>BMAS / EQUIPAJES</center></th>
                      </tr>
                    </thead>
                    <?
                    	
                    	$counter = 0;
                    	do
                    	{

                    		if(isset($bmaslist[$counter])) $header = $bmaslist[$counter++]; else unset($header);
                    		if(isset($bmaslist[$counter])) $header2 = $bmaslist[$counter++]; else unset($header2);
                    		if(isset($bmaslist[$counter])) $header3 = $bmaslist[$counter++]; else unset($header3);
					?>
                    <tbody>
                      <tr>
                        <td><? echo $header['shortname']; ?>
                        <td><span style='font-weight:bold'><? echo $header['position']; ?></span></td>
                        <td><? echo $header2['shortname']; ?>
                        <td><span style='font-weight:bold'><? echo $header2['position']; ?></span></td>
                        <td><? echo $header3['shortname']; ?>
                        <td><span style='font-weight:bold'><? echo $header3['position']; ?></span></td>
                      </tr>
                      <? }while($counter<sizeof($bmaslist)) ?>
                    </tbody>
                 </table>
                    <!-- LEADS -->
                 <table class="table table-condensed">
                    <thead>
                      <tr>
                        <th colspan='5'><center>LEADS</center></th>
                      </tr>
                    </thead>
                    <?
                    	$counter = 0;
                    	do
                    	{

                    		if(isset($leadslist[$counter])) $header = $leadslist[$counter++]; else unset($header);
                    		if(isset($leadslist[$counter])) $header2 = $leadslist[$counter++]; else unset($header2);
                    		if(isset($leadslist[$counter])) $header3 = $leadslist[$counter++]; else unset($header3);

					?>
                    <tbody>
                      <tr>
                        <td><? echo $header['idagente']; ?></td>
                        <td><? echo $header['shortname']; ?></td>
                        <td><? echo $header2['idagente']; ?></td>
                        <td colspan=2><? echo $header2['shortname']; ?></td>
                      </tr>
                      <? }while($counter<sizeof($leadslist)) ?>
                    </tbody>
                </table>
                    <!-- FOOTER -->
                <table class="table table-condensed">
                    <?

						$counter = 0;
                    	do
                    	{

                    		if(isset($footerlist[$counter])) $header = $footerlist[$counter++]; else unset($header);
                    		if(isset($footerlist[$counter])) $header2 = $footerlist[$counter++]; else unset($header2);
                    		if(isset($footerlist[$counter])) $header3 = $footerlist[$counter++]; else unset($header3);
                    
					?>
                    <tbody>
                      <tr>
                        <td colspan='2'><center><? echo $header['comentario']; ?></center></td>
                        <td colspan='3'><center><? echo $header2['comentario']; ?></center></td>
                      </tr>
                      <? }while($counter<sizeof($footerlist)) ?>
                    </tbody>
                  </table>
          	</div>
            <?
              }
            ?>
      	</div>
      </div>
    </div>
    <!-- End Horizontal Form -->
    
    
    <script type="text/javascript">
	$("#frmAgentData").show();
    $("#frmVueloData").hide();
	$('#myPleaseWait').modal('hide');
	$(document).ready(function(){
		
		$("#btnSubmitFlightData").click(function(){
			
			var lidempresa = $("#inputIdEmpresa").val();
			var lidoficina = $("#inputIdOficina").val();
			var lidvuelo = $("#inputFlight").val();
			var lfecha = $("#inputFlightDate").val();
            var llinea = $("#inputLinea").val();
            var lagente1 = $("#inputAgent1").val();
            var lposicion1 = $("#inputPosicion1").val();
            var lagente2 = $("#inputAgent2").val();
            var lposicion2 = $("#inputPosicion2").val();
            var lusuario = $("#inputUsuario").val();
			
			$("#frmAgentData").hide();	
			var agent = {
                idempresa : lidempresa,
                idoficina : lidoficina,
                idvuelo : lidvuelo,
                fecha : lfecha,
                linea : llinea,
                agente1 : lagente1,
                posicion1 : lposicion1,
                agente2 : lagente2,
                posicion2 : lposicion2,
                usuario : lusuario
			};
			
			$.each(agent, function(index, value) {
				console.log(value);
			});
			
			var request = $.ajax({
				url: 'http://www.mindware.com.mx/apps/webcunop/postcambio',
				type: 'POST',
				data: agent,
				beforeSend:function(){
					console.log('sending...');
					$('#myPleaseWait').modal('show');
				},
				success:function(result){
					
					console.log('sent!');
					console.log(result);
					$('#myPleaseWait').modal('hide');
					location.reload();
				}, 
				error:function(exception){console.log(exception);}
				
			});
			
			request.fail(function( jqXHR, textStatus ) {
  			console.log( "Request failed: " + textStatus );
			});
			
		});

        $("#btnGetPDF").click(function(){
            var fecha = $("#inputFecha").val();
            location.href = '<? echo base_url(); ?>1pdf.php?f=' + fecha;
        });
		
		$("#btnNewAgent").click(function(){
			$("#frmAgentData").show();	
		});
		
		$("#btnCancel").click(function(){
			$("#inputAgentId").val('');
			$("#inputDaysOff").val('');
			$("#frmAgentData").hide();	
		});
			
			
		return false;
	})

function loadRowVuelo(idvuelo,linea,fecha)
  {
    $("#frmVueloData").show();
    $("html, body").animate({ scrollTop: 0 }, "fast");

    idempresa = $("#inputIdEmpresa").val();
    idoficina = $("#inputIdEmpresa").val();

    $("inputFlight").val(idvuelo);
    var infoData = { 
              idempresa : idempresa,
              idoficina : idoficina,
              idvuelo : idvuelo,
              fecha : fecha
             };
    $.ajax({
      url: 'http://www.mindware.com.mx/apps/webcunop/loadflightdetail',
      type: 'POST',
      data : infoData,
      beforeSend:function(){
        $('#myPleaseWait').modal('show');
      },
      success:function(data){
        console.log('loading data...'); 
        
        var agents = jQuery.parseJSON(data);

        if(agents.length > 1)
        {
          //LoadAgentsAssigned(agents);
          console.log(agents[0].idagente);
          $("#inputFlight").val(agents[0].idvuelo);
          $("#inputLinea").val(agents[0].linea);
          $("#inputAgent1").val(agents[0].idagente);
          $("#inputPosicion1").val(agents[0].posicion);
          $("#inputAgent2").val(agents[1].idagente);
          $("#inputPosicion2").val(agents[1].posicion);
        }
      
        $('#myPleaseWait').modal('hide');
      }
    });
  }

function loadFlightDetail(idempresa, idoficina, idvuelo, qdate)
{
	
	var infoData = { idempresa : idempresa,
					  idoficina : idoficina,
					  idvuelo : idvuelo,
					  fecha : qdate
					 };
	$.ajax({
		url: 'http://www.mindware.com.mx/apps/webcunop/loadflightdetail',
		type: 'POST',
		data : infoData,
		beforeSend:function(){
			$('#myPleaseWait').modal('show');
		},
		success:function(data){
			console.log('loading data...');
			var agent = jQuery.parseJSON(data)[0];
			console.log('shortname ' + agent.dia1);
			$("#inputAgentId").val(idagente);
			$("#inputDaysOff").val(agent.dia1);
			
			$('#myPleaseWait').modal('hide');
		}
	});
}
</script>