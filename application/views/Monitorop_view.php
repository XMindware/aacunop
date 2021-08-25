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
    <link rel="icon" type="image/png" href="<? echo base_url(); ?>assets/images/favicon.png"/>
    <title><? echo $titulo; ?></title>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<? echo base_url(); ?>assets/js/bootstrap.min.js"></script>

    <script src="<? echo base_url(); ?>assets/js/jquery.hotkey.js"></script>

    
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
    <style>

       .row{
            margin-left: 0px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr; /* fraction*/
       }


       .rotate{
            -webkit-transform: rotate(90deg); 
            -moz-transform: rotate(90deg); 
            -ms-transform: rotate(90deg); 
            -o-transform: rotate(90deg);
            transform: rotate(90deg);
            position: absolute;
            height: 100%;
            width: 494px;
            right : 0px;
            bottom : 0px;
        }
        .border-top-radius(@radius) {
          border-top-right-radius: @radius;
           border-top-left-radius: @radius;
        }
        .border-right-radius(@radius) {
          border-bottom-right-radius: @radius;
             border-top-right-radius: @radius;
        }
        .border-bottom-radius(@radius) {
          border-bottom-right-radius: @radius;
           border-bottom-left-radius: @radius;
        }
        .border-left-radius(@radius) {
          border-bottom-left-radius: @radius;
             border-top-left-radius: @radius;
        }

        body {
            padding-top: 0px;
            padding-bottom: 0px;
            font-family: Helvetica;
            font-size: 11px;
        }

        marquee {
            vertical-align: top;
        }

        div.rowlist{
           //width: 860px;
        }

        div.bg-danger
        {
            background-color: #ffb3b3;
        }

        marquee {
            vertical-align: top;
        }

        div.flight {
            border: 1px solid;
            margin: 5px;
            border-radius: 5px;
            min-height: 150px;
            max-height: 210px;
            max-width: 236px;
            /*background: url("<? echo base_url(); ?>assets/images/cities/ord.jpg") no-repeat left center;
            background-repeat: no-repeat;
            background-size: cover;*/
        }

        div.statuscolor {
            position: absolute;
            left:0px;
            width: 100%;
            height: 100%;
            background-color: rgba(200,100,0,.5); // Tint color
        }

        table.flightrow {
            width: 100%;
        }

        td {
            vertical-align: top;
        }

        td.dest {
            text-align: left;
            max-height: 80px !important;
        }

        h3.flightnumber {
            margin-top: 10px !important;
            vertical-align: middle;
            color : #5a92ed;
        }

        .warning {
            background-color: #ffff99;
        }

        .bg-info {
            background-color: #66ccff;
        }

        h4.estimateddep {
            vertical-align: middle;
            margin-right: 20px;
        }

        h2.flightdest {
            vertical-align: middle;
            text-align: left;
        }

        p.smalltit {
            font-size: 13pt;
            margin-bottom: 0px;
        }

        p.lead {
            margin-top:5px;
        }

        .col-md-4 {
            width: 32%;
        }

        div.divfoot {
            margin-top: 20px;
            position: absolute;
            bottom: 0;
        }

        div.tele{
            width: 654px;
        }

        h3.late {
            color :red;
            -webkit-animation-name: blink;
            -webkit-animation-duration: 1s;
            -webkit-animation-timing-function: linear;
            -webkit-animation-iteration-count: infinite;

            -moz-animation-name: blink;
            -moz-animation-duration: 1s;
            -moz-animation-timing-function: linear;
            -moz-animation-iteration-count: infinite;

            animation-name: blink;
            animation-duration: 1s;
            animation-timing-function: linear;
            animation-iteration-count: infinite;
        }

        @-moz-keyframes blink {  
            0% { opacity: 1.0; color: blue; }
            50% { opacity: 0.0; }
            100% { opacity: 1.0; color: red; }
        }

        @-webkit-keyframes blink {  
            0% { opacity: 1.0; color: blue;}
            50% { opacity: 0.0; }
            100% { opacity: 1.0; color: red; }
        }

        @keyframes blink {  
            0% { opacity: 1.0; color: blue; }
            50% { opacity: 0.0; }
            100% { opacity: 1.0; color: red; }
        }
    </style>
  </head>

  <body>
  
    <?
        if(!isset($fecha) || $fecha=='')
            $fecha = date('Y-m-d');
    ?>

    <input type="hidden" id="inputIdEmpresa" value="<? echo $idempresa; ?>" />
    <input type="hidden" id="inputIdOficina" value="<? echo $idoficina; ?>" />
    <input type="hidden" id="inputUsuario" value="<? echo $idusuario; ?>" />
    <div class="rotate">
    <center><h3 id="titfecha"></h3></center>
    <!-- End Horizontal Form -->
    
    <center>
        <div class="row rowlist" id="divflow">


        </div>
    </center>   
    </div>
    
    <script type="text/javascript">

    $(document).ready(function(){
        
        var positions = [<? foreach($posiciones as $position) { echo "'" . $position['code'] . "',"; } ?>];
        var agenteslista = [<? foreach($fullagents as $agent) { echo "{ id : '" . $agent['idagente'] . "', name : '" . $agent['shortname'] . "'},"; } ?>];
        RefreshFlights();
        setInterval(function(){
            RefreshFlights();
        },60000);
        
    });

    function RefreshFlights()
    {

        console.log(moment().format("dddd, MMMM Do YYYY, h:mm a"));
        $("#titfecha").text(moment().format("dddd, MMMM Do YYYY, h:mm a"));
        var lidempresa = $("#inputIdEmpresa").val();
        var lidoficina = $("#inputIdOficina").val();
        var lfecha = moment().format('YYYY-MM-DD');
        var fields = {
            idempresa : lidempresa,
            idoficina : lidoficina,
            fecha : lfecha
        }

        var request = $.ajax({
            url: 'webcunop/asyncloadstationdate',
            type: 'POST',
            data: fields,
            beforeSend:function(){
                //console.log('sending...');
                $('#myPleaseWait').modal('show');
            },
            success:function(result){
                
                //console.log('sent!');
                //console.log(result);
                $('#divflow').empty();

                $.each(result, function(row, flight){

                    
                    var msjclass = (typeof(flight['mensaje'])!='undefined') ? 'warning' : 'bg-info';

                    var rowclass = (moment(flight['salida'],'hh:mm')).diff(moment(),'minutes') < 60 ? 'bg-danger passed' : '';

                    if(moment().diff(moment(flight['salida'],'hh:mm'),'minutes') < 15)
                    {

                        var msjdep = flight['newdeparture'] == 1 ? 'ETD' : 'SKD';
                        var depclass = flight['newdeparture'] != 1 ? '' : 'late';
                        var html = '<div class="col-xs-6 ' + rowclass + ' flight">' + 
                                   '<!--<div class="statuscolor"></div>-->' +
                                   '    <table class="flightrow">' +
                                   '      <tr>' + 
                                   '         <td>' +
                                   '             <h3 class="flightnumber ' + depclass + '" >' + flight['idvuelo'] + '</h3> ' +
                                   '             <div><h2 class="flightdest">' + flight['destino'] + '</h2></div>';
                        html+=     '         </td>' + 
                                   '         <td>' +
                                   '            <h4 class="estimateddep">'+ msjdep + ' ' + moment(flight['salida'],'h:mm').format('HH:mm') + '</h4>' +
                                   '         </td>';
                        html+=     '      </tr>';
                        html+=     '       <tr>' +
                                   '         <td colspan-3>' +
                                   '            <p class="lead smalltit">Agents</p>' +
                                   '         </td>' +
                                   '       </tr>' +
                                   '       <tr>' +
                                   '         <td colspan=3>';
                        html +=    '            <p>';
                            for(var i=1;i<=3;i++)
                            {
                                if(typeof(flight['idagent' + i])!='undefined')
                                {
                                    html += flight['idagent' + i] + ' <b>' + flight['pos' + i] + '</b>'; 
                                }
                                if(typeof(flight['idagent' + (i+1)])!='undefined')
                                    html +=  ' / ';
                            }
                                    html += '  </p>';
                        html +=    '         </td>' +
                                   '       </tr>' +
                                   '       <tr><td colspan=3>';
                                   
                        var msj = (flight['mensaje']!='') ? '' + flight['mensaje'] : '&nbsp;';
                        html +=    '' +
                                   '        <marquee><strong><p class="smalltit">' + msj + '</p></strong></marquee>' +
                                   '' + 
                                   '    </td></tr>' +
                                   '    </table>' ;
                        html +=    '</div>';
                       
                        $("#divflow").append(html);
                    }
                
                });

                $('#myPleaseWait').modal('hide');
            }, 
            error:function(exception){console.log(exception);}
            
        });
       
    }


function loadFlightDetail(idempresa, idoficina, idvuelo, qdate)
{
    
    var infoData = {  idempresa : idempresa,
                      idoficina : idoficina,
                      idvuelo : idvuelo,
                      fecha : qdate
                     };
    $.ajax({
        url: 'webcunop/loadflightdetail',
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

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<? echo base_url(); ?>assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
