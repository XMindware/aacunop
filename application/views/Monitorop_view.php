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
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-VPJJCHEV8F"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-VPJJCHEV8F');
    </script>
    <style>
        /* Reset and base styles */
        * {
            box-sizing: border-box;
        }

        body {
            padding: 0;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            overflow: hidden;
        }

        /* Main container - no rotation */
        .rotate {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            box-sizing: border-box;
        }

        /* Header section */
        .header-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 20px;
        }

        .header-buttons {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        /* Header styling */
        #titfecha {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            border-radius: 25px;
            font-size: 18px;
            font-weight: 600;
            text-align: center;
            margin: 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
            flex: 1;
        }

        /* Refresh button */
        .refresh-button {
            background: linear-gradient(135deg, #00b894 0%, #00a085 100%);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .refresh-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        /* Toggle date button */
        .toggle-date-button {
            background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .toggle-date-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .toggle-icon {
            font-size: 16px;
        }

        .refresh-icon {
            font-size: 16px;
            animation: spin 2s linear infinite;
        }

        /* Tomorrow warning styling */
        .tomorrow-warning {
            background: linear-gradient(135deg, #ff7675 0%, #e17055 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 15px;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-block;
            margin-bottom: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            animation: pulse-warning 2s ease-in-out infinite;
        }

        @keyframes pulse-warning {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        /* Main content container */
        .rowlist {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            width: 100%;
            padding: 20px;
            max-width: none;
        }

        /* Flight card styling */
        .flight {
            background: white;
            border: 2px solid #e1e8ed;
            border-radius: 12px;
            padding: 20px;
            min-height: 180px;
            max-height: 220px;
            position: relative;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .flight:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border-color: #667eea;
        }

        /* Status colors */
        .bg-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%) !important;
            color: white !important;
            border-color: #ff4757 !important;
        }

        .bg-danger .flightnumber,
        .bg-danger .flightdest,
        .bg-danger .estimateddep,
        .bg-danger .smalltit,
        .bg-danger .lead {
            color: white !important;
        }

        .bg-danger marquee {
            background: rgba(255,255,255,0.2) !important;
            border-color: rgba(255,255,255,0.3) !important;
        }

        .bg-danger marquee strong {
            color: white !important;
        }

        .warning {
            background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%);
            border-color: #fdcb6e;
        }

        .bg-info {
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
            color: white;
            border-color: #0984e3;
        }

        .bg-info .flightnumber,
        .bg-info .flightdest {
            color: white !important;
        }

        /* Flight row table */
        .flightrow {
            width: 100%;
            border-collapse: collapse;
        }

        .flightrow td {
            vertical-align: top;
            padding: 5px;
        }

        /* Flight number */
        .flightnumber {
            margin: 0 0 10px 0;
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        /* Destination */
        .flightdest {
            margin: 0 0 15px 0;
            font-size: 20px;
            font-weight: 600;
            color: #2d3436;
        }

        /* Estimated departure */
        .estimateddep {
            margin: 0;
            font-size: 16px;
            font-weight: 500;
            color: #636e72;
            text-align: right;
        }

        /* Agents section */
        .smalltit {
            font-size: 14px;
            font-weight: 600;
            margin: 0 0 8px 0;
            color: #2d3436;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .lead {
            margin: 8px 0;
            font-size: 13px;
            line-height: 1.4;
            color: #636e72;
        }

        /* Marquee styling */
        marquee {
            background: rgba(255,255,255,0.9);
            padding: 8px 12px;
            border-radius: 6px;
            margin-top: 10px;
            border: 1px solid #e1e8ed;
        }

        marquee strong {
            color: #e17055;
            font-weight: 600;
        }

        /* Late flight animation */
        .late {
            color: #e74c3c !important;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }

        /* Responsive adjustments */
        @media (max-width: 1200px) {
            .rowlist {
                grid-template-columns: repeat(3, 1fr);
                gap: 12px;
            }
        }
        
        @media (max-width: 900px) {
            .rowlist {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }
        }
        
        @media (max-width: 768px) {
            .rotate {
                padding: 15px;
            }
            
            .header-section {
                flex-direction: column;
                gap: 15px;
            }
            
            .header-buttons {
                flex-direction: column;
                width: 100%;
            }
            
            .rowlist {
                grid-template-columns: 1fr;
                padding: 15px;
                gap: 10px;
            }
            
            .flight {
                min-height: 160px;
                padding: 15px;
            }
            
            #titfecha {
                font-size: 16px;
                padding: 12px 25px;
            }
            
            .refresh-button,
            .toggle-date-button {
                padding: 10px 16px;
                font-size: 13px;
                width: 100%;
                justify-content: center;
            }
        }

        /* Landscape orientation adjustments */
        @media (orientation: landscape) and (max-height: 600px) {
            .rotate {
                padding: 10px;
            }
            
            .header-section {
                margin-bottom: 15px;
            }
            
            .rowlist {
                gap: 10px;
            }
        }

        /* Loading state */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        /* Smooth transitions */
        .flight, .flight * {
            transition: all 0.3s ease;
        }

        /* Agent styling */
        .agents-container {
            margin: 10px 0;
        }

        .agent-item {
            display: inline-block;
            background: rgba(102, 126, 234, 0.1);
            padding: 4px 8px;
            border-radius: 4px;
            margin: 2px;
            border: 1px solid rgba(102, 126, 234, 0.3);
        }

        .agent-separator {
            margin: 0 5px;
            color: #b2bec3;
            font-weight: 300;
        }

        /* Flight info layout */
        .flight-info {
            width: 70%;
        }

        .departure-info {
            width: 30%;
            text-align: right;
        }

        /* Enhanced status indicators */
        .status-indicator {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #00b894;
        }

        .bg-danger .status-indicator {
            background: #e74c3c;
        }

        .warning .status-indicator {
            background: #fdcb6e;
        }

        /* Loading indicator */
        .loading-indicator {
            text-align: center;
            padding: 40px;
            color: #636e72;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #636e72;
        }

        .empty-state h4 {
            margin-bottom: 15px;
            color: #2d3436;
        }

        /* Summary display */
        .summary-display {
            background: linear-gradient(135deg, #00b894 0%, #00a085 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .summary-display p {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
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
        <div class="header-section">
            <h3 id="titfecha"></h3>
            <div class="header-buttons">
                <button id="toggle-date-btn" class="toggle-date-button" onclick="ToggleDateView()">
                    <span class="toggle-icon">📅</span> Toggle Date
                </button>
                <button id="refresh-btn" class="refresh-button" onclick="RefreshFlights()">
                    <span class="refresh-icon">↻</span> Refresh
                </button>
            </div>
        </div>
        
        <div class="rowlist" id="divflow">
            <!-- Flight cards will be dynamically inserted here -->
        </div>
        
        <!-- Loading indicator -->
        <div id="loading-indicator" class="loading-indicator" style="display: none;">
            <div class="spinner"></div>
            <p>Loading flight data...</p>
        </div>
    </div>
    
    <script type="text/javascript">

    // Global variables for date management
    var currentViewDate = moment();
    var isShowingTomorrow = false;

    $(document).ready(function(){
        
        var positions = [<? foreach($posiciones as $position) { echo "'" . $position['code'] . "',"; } ?>];
        var agenteslista = [<? foreach($fullagents as $agent) { echo "{ id : '" . $agent['idagente'] . "', name : '" . $agent['shortname'] . "'},"; } ?>];
        
        // Initialize with today's date, but check if we should show tomorrow
        var currentTime = moment();
        if (currentTime.hour() >= 23) {
            currentViewDate = currentTime.add(1, 'day');
            isShowingTomorrow = true;
        }
        
        RefreshFlights();
        setInterval(function(){
            RefreshFlights();
        },60000);
        
    });

    function RefreshFlights()
    {
        var currentTime = moment();
        var lidempresa = $("#inputIdEmpresa").val();
        var lidoficina = $("#inputIdOficina").val();
        
        // Use the current view date (either today or tomorrow)
        var lfecha = currentViewDate.format('YYYY-MM-DD');
        var displayDate = currentViewDate;
        
        // Update the title with appropriate warning
        if (isShowingTomorrow) {
            $("#titfecha").html('<span class="tomorrow-warning">⚠️ TOMORROW\'S SCHEDULE</span><br>' + 
                               displayDate.format("dddd, MMMM Do YYYY, h:mm a"));
        } else {
            $("#titfecha").text(displayDate.format("dddd, MMMM Do YYYY, h:mm a"));
        }
        
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
                $('#loading-indicator').show();
                $('#divflow').addClass('loading');
            },
            success:function(result){
                $('#divflow').empty();
                $('#loading-indicator').hide();
                $('#divflow').removeClass('loading');
                
                // Debug: Log the result
                console.log('Flight data received:', result);
                console.log('Number of flights:', result ? result.length : 0);
                
                // Check if we have results
                if (!result || result.length === 0) {
                    var emptyMessage = isShowingTomorrow ? 
                        '<div class="empty-state"><h4>No flights scheduled for tomorrow</h4><p>No flights found in the system for tomorrow.</p></div>' :
                        '<div class="empty-state"><h4>No flights scheduled for today</h4><p>No flights found in the system for today.</p></div>';
                    $('#divflow').html(emptyMessage);
                    return;
                }

                var flightCount = 0;
                $.each(result, function(row, flight){
                    // Debug: Log each flight
                    console.log('Processing flight:', flight);
                    
                    var msjclass = (typeof(flight['mensaje'])!='undefined' && flight['mensaje'] !== '') ? 'warning' : 'bg-info';

                    // Calculate minutes until departure (negative if already departed)
                    var minutesUntilDeparture = moment(flight['salida'],'hh:mm').diff(moment(),'minutes');
                    
                    // Set row class based on departure time
                    var rowclass = '';
                    if (minutesUntilDeparture < 0) {
                        // Flight has already departed
                        rowclass = 'bg-danger passed';
                    } else if (minutesUntilDeparture <= 45) {
                        // Flight is within 45 minutes of departure - make it red
                        rowclass = 'bg-danger';
                    }

                    // Filter out past flights - only show flights that haven't departed yet or departed within the last 15 minutes
                    if(moment().diff(moment(flight['salida'],'hh:mm'),'minutes') < 15)
                    {

                        var msjdep = flight['newdeparture'] == 1 ? 'ETD' : 'SKD';
                        var depclass = flight['newdeparture'] != 1 ? '' : 'late';
                        var html = '<div class="flight ' + rowclass + ' ' + msjclass + '">' +
                                   '    <div class="status-indicator"></div>' +
                                   '    <table class="flightrow">' +
                                   '      <tr>' + 
                                   '         <td class="flight-info">' +
                                   '             <h3 class="flightnumber ' + depclass + '">' + flight['idvuelo'] + '</h3>' +
                                   '             <h2 class="flightdest">' + flight['destino'] + '</h2>';
                        html+=     '         </td>' + 
                                   '         <td class="departure-info">' +
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
                        flightCount++;
                    }
                
                });
                
                // Debug: Log final count
                console.log('Total flights displayed:', flightCount);
                
                // Add summary display
                if (flightCount > 0) {
                    var summaryHtml = '<div class="summary-display">' +
                                     '<p><strong>Total Flights: ' + flightCount + '</strong></p>' +
                                     '</div>';
                    $("#divflow").prepend(summaryHtml);
                }
            }, 
            error:function(exception){
                console.log(exception);
                $('#loading-indicator').hide();
                $('#divflow').removeClass('loading');
                var errorMessage = isTomorrow ? 
                    '<div class="empty-state"><h4>Error loading tomorrow\'s data</h4><p>Unable to load flight information for tomorrow. Please try again.</p></div>' :
                    '<div class="empty-state"><h4>Error loading data</h4><p>Unable to load flight information. Please try again.</p></div>';
                $('#divflow').html(errorMessage);
            }
            
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

    function ToggleDateView() {
        if (isShowingTomorrow) {
            // Switch to today
            currentViewDate = moment();
            isShowingTomorrow = false;
        } else {
            // Switch to tomorrow
            currentViewDate = moment().add(1, 'day');
            isShowingTomorrow = true;
        }
        
        // Update button text
        var buttonText = isShowingTomorrow ? '📅 Show Today' : '📅 Show Tomorrow';
        $('#toggle-date-btn').html('<span class="toggle-icon">📅</span> ' + buttonText);
        
        // Refresh the flights display
        RefreshFlights();
    }
</script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<? echo base_url(); ?>assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
