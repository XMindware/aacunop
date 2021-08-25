  
	<style>

	  #wrap {
	    width: 1100px;
	    margin: 0 auto;
	    }

	  #external-events {
	    float: left;
	    width: 150px;
	    padding: 0 10px;
	    text-align: left;
	    }

	  #external-events h4 {
	    font-size: 16px;
	    margin-top: 0;
	    padding-top: 1em;
	    }

	  .external-event { /* try to mimick the look of a real event */
	    margin: 10px 0;
	    padding: 2px 4px;
	    background: #3366CC;
	    color: #fff;
	    font-size: .85em;
	    cursor: pointer;
	    }

	  #external-events p {
	    margin: 1.5em 0;
	    font-size: 11px;
	    color: #666;
	    }

	  #external-events p input {
	    margin: 0;
	    vertical-align: middle;
	    }

	  #calendar {
	/*    float: right; */
	        margin: 0 auto;
	    width: 400px;
	    background-color: #FFFFFF;
	      border-radius: 6px;
	        box-shadow: 0 1px 2px #C3C3C3;
	    }

	</style>
	<script src="<? echo base_url(); ?>assets/js/custypeahead.js"></script>
    <div class="container">
        <div class="panel-body">
            <div class="panel-heading">
            	<div class="row">
                <h2><span class="label label-primary">Swap Shift Request Control</span></h2>
                <button type="button" id="btnNewAgent" class="btn btn-link">New Request</button>
                <input type="hidden" id="inputIdEmpresa" value="<? echo $idempresa; ?>" />
                <input type="hidden" id="inputIdOficina" value="<? echo $idoficina; ?>" />
                <input type="hidden" id="inputRequestId" value="<? echo $request->uniqueid; ?>" />
                </div>
            </div>    
            <div id="divFinalStep">
            	 <fieldset required="required" style="text-align: center">
                    <legend>Shift Request Received. Please Review and Choose Action.</legend>
               		
                    <div class='col-sm-3 col-xs-6'>
	                    <div class='form-group'>
	                       <label for="inputFechaIni">Date Selected</label>
	                       <input class="form-control" id="inputFinalDate" name="inputFinalDate" size="30" type="date" value="<? echo $request->fechacambio; ?>" readonly="true" />
	                    </div>
	                </div>
	                <div class='col-sm-3 col-xs-6'>
		                <div class='form-group'>
		                    <label for="inputInitialPosition">Initial Position</label>
		                       <input class="form-control" id="inputInitialPosition" name="inputInitialPosition" size="30" value="<? echo $request->posicioninicial; ?>" type="text" readonly="true" />
		                </div>
		            </div>
	                <div class='col-sm-3 col-xs-6'>
		                <div class='form-group'>
		                    <label for="inputFechaIni">Selected Position</label>
		                       <input class="form-control" id="inputFinalPosition" name="inputFinalPosition" size="30" value="<? echo $request->posicionsolicitada; ?>" type="text" readonly="true" />
		                </div>
		            </div>
		            <div class='col-sm-3 col-xs-6'>
		                <div class='form-group'>
		                    <label for="inputFechaIni">Type of Request</label>
		                       <input class="form-control" id="inputFinalTipo" name="inputFinalTipo" size="30" value="<? echo $request->tipocambio; ?>" type="text" readonly="true" />
		                </div>
		            </div> 
		            <div class='col-sm-3 col-xs-6'>
		                <div class='form-group'>
		                    <label for="inputSolicitant">Agent</label>
		                       <input class="form-control" id="inputSolicitant" name="inputSolicitant" size="30" value="<? echo $request->shortname; ?>" type="text" readonly="true" />
		                       <input class="form-control" id="inputlIdSolicitant" name="inputlIdSolicitant" value="<? echo $request->idagente; ?>" size="30" type="hidden" />
		                </div>
		            </div> 
		            <div class='col-sm-3 col-xs-6'>
		                <div class='form-group'>
		                    <label for="inputSolicitant">In case the trade is going to be declined, please provide a reason</label>
		                       <input class="form-control" id="inputDeclineReason" name="inputDeclineReason" size="30" type="text" />
		                       
		                </div>
		            </div> 
		            <div class='col-sm-12'>
		            	<div class='form-group'>
		            		<label id="lblInfo" style="color:red;"></label>
		            	</div>
		            </div>
                    <div class='col-sm-12'>
                        <div class='form-group'>
                            <button type="button" id="btnAcceptRequest" class="btn btn-success">Accept Request</button>
                            <button type="button" id="btnDeclineRequest" class="btn btn-default">Decline</button>
                        </div>
                    </div>        
                </fieldset>
            </div>
            
            <? 
            if(false){
            	?>
            }
            <div class="row">
            	<div class="col-md-12">
                    <table class="table table-condensed">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Seniority</th>
                        <th>Days Off</th>
                        <th>Last Change</th>
                      </tr>
                    </thead>
                    <?

                    	$dowMap = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');

						foreach($descansos as $row)
						{
							$seniority =  strtotime($row['ingreso']);
							$lastchange = $row['updated'];
							$day1 = $dowMap[intval($row['dia1'])-1];
							$day2 = $dowMap[intval($row['dia2'])-1];
					?>
                    <tbody>
                      <tr>
                        <td><button type="button" onclick="loadAgent(<? echo $row['idagente'] . ',' . $row['idempresa'] . ',' . $row['idoficina']; ?>);" class="btn btn-link"><? echo $row['idagente']; ?></button></td>
                        <td><? echo $row['shortname']; ?></td>
                        <td><? echo $row['puesto']; ?></td>
                        <td><? echo date('M jS, Y', $seniority); ?></td>
                        <td><? echo $day1 . ' ' . $day2; ?></td>
                        <td><? echo $lastchange; ?></td>
                      </tr>
                      <? } ?>
                    </tbody>
                  </table>
          	</div>
      	</div>
      	<?
      	}
      ?>
      </div>
    </div>
    <!-- End Horizontal Form -->
    
    
    <script type="text/javascript">
 
	$('#myPleaseWait').modal('hide');
	$(document).ready(function(){

		var agenteslista = [<? foreach($fullagents as $agent) { echo "{ id : '" . $agent['idagente'] . "', name : '" . $agent['shortname'] . "', status : '" . $agent['razon'] . "'},"; } ?>];

		$("#divSelectedInfo").hide();

		$('#inputSelectAgentCambio.typeahead').typeahead({
            source : agenteslista,
            display : 'name',
            val : 'id'
        });

		var umbral = moment.utc('<? echo $limite; ?>');
        console.log(umbral);
        console.log(moment().format('DD/MM/YYYY HH:mm'));
		console.log(moment().isSameOrBefore(umbral));
		if(moment().isBefore(umbral)){
			$("#lblInfo").text('You have until ' + moment.utc('<? echo $limite; ?>').local().format('DD/MM/YYYY HH:mm') + ' to accept the current request.');
		}
		else
		{
			$("#lblInfo").text('This request is has expired');
			$("#btnAcceptRequest").prop('disabled',true);
		}
        
		
		// inicializar el calendario

		var date = new Date();
	      var d = date.getDate();
	      var m = date.getMonth();
	      var y = date.getFullYear();

	      /*  className colors

	      className: default(transparent), important(red), chill(pink), success(green), info(blue)

	      */


	      /* initialize the external events
	      -----------------------------------------------------------------*/

	      $('#external-events div.external-event').each(function() {

	        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
	        // it doesn't need to have a start or end
	        var eventObject = {
	          title: $.trim($(this).text()) // use the element's text as the event title
	        };

	        // store the Event Object in the DOM element so we can get to it later
	        $(this).data('eventObject', eventObject);

	        // make the event draggable using jQuery UI
	        $(this).draggable({
	          zIndex: 999,
	          revert: true,      // will cause the event to go back to its
	          revertDuration: 0  //  original position after the drag
	        });

	      });


	      /* initialize the calendar
	      -----------------------------------------------------------------*/

	      var calendar =  $('#calendar').fullCalendar({
	        header: {
	          left: 'title',
	          center: false,
	          right: 'prev,next today'
	        },
	        editable: false,
	        height : '300px',
	        firstDay: 1, //  1(Monday) this can be changed to 0(Sunday) for the USA system
	        selectable: false,
	        defaultView: 'month',
	        axisFormat: 'h:mm',
	        columnFormat: {
	                  month: 'ddd',    // Mon
	                  week: 'ddd d', // Mon 7
	                  day: 'dddd M/d',  // Monday 9/7
	                  agendaDay: 'dddd d'
	              },
	              titleFormat: {
	                  month: 'MMMM yyyy', // September 2009
	                  week: "MMMM yyyy", // September 2009
	                  day: 'MMMM yyyy'                  // Tuesday, Sep 8, 2009
	              },
	        allDaySlot: false,
	        weekMode : false,
	        eventConstraint : { endTime : '2019-05-29' },
	        selectHelper: true,
	        select: function(start, end, allDay) {
	          var title = prompt('Event Title:');
	          if (title) {
	            calendar.fullCalendar('renderEvent',
	              {
	                title: title,
	                start: start,
	                end: end,
	                allDay: allDay
	              },
	              true // make the event "stick"
	            );
	          }
	          calendar.fullCalendar('unselect');
	        },
	        droppable: false,
	        navLinks : true,
	        navLinkDayClick : function(date, jsEvent) {
	            console.log('day', date.toISOString());
	            console.log('coords', jsEvent.pageX, jsEvent.pageY);
	        },
	        
	        events: [

	        <?

	          foreach($monthlyschedule as $pos)
	          {

	            $color = 'important';
	            if($pos['posicion'][0] == 'M')
	              $color = 'info';
	            else if($pos['posicion'][0] == 'G')
	              $color='success';
	            else
	              $color='important';
	            ?>

	          {
	            title: '<? echo $pos['posicion']; ?>',
	            start: '<? echo $pos['fecha']; ?>',
	            url : 'javascript:selectFecha("<? echo $pos['fecha']; ?>","<? echo $pos['posicion']; ?>");',
	            className : '<? echo $color; ?>'
	          },
	          <?
	        }
	        ?>
	        ],
	      });

	      // finaliza inicializar calendario

	     // selecciono el tipo de cambio, pasa a seleccionar el agente
	     $("#btnNext2").click(function(){
	     	console.log($("input[id=inputTipoCambio]:checked").val());
	     	switch($("input[id=inputTipoCambio]:checked").val())
	     	{
	     		case 'switch' : 
	     			$("#inputFinalTipo").val('Switch');
	     			break;
	     		case 'dayoff' : 
	     			$("#inputFinalTipo").val('Day Off');
	     			break;
	     		case 'cover' : 
	     			$("#inputFinalTipo").val('Cover');
	     			break;
	     	}

	     	$("#divStep2").hide();
	     	$("#divStep3").show();
	     });

	     // selecciono el agente pasa a la revision
	     $("#btnNext3").click(function(){
	     	$("#inputFinalAgent").val($("#inputSelectAgentCambio").val());
	     	$("#divFinalStep").show();
	     	$("#divStep3").hide();
	     	$("#divFinalStep").show();
	     });

	     // regresa a revisar la fecha
	     $("#btnCancel2").click(function(){
	     	$("#divStep1").show();
	     	$("#divStep2").hide();
	     });

	     // regresa a revisar el tipo
	     $("#btnCancel3").click(function(){
	     	$("#divStep2").show();
	     	$("#divStep3").hide();
	     });
		
		$("#btnAcceptRequest").click(function(){

			
			var umbral = moment.utc('<? echo $limite; ?>');
	        console.log(umbral);
	        console.log(moment().format('DD/MM/YYYY HH:mm'));
			console.log(moment().isSameOrBefore(umbral));

	  		if(!moment().isBefore(umbral))
	  		{
	  			$("#lblInfo").text('This Request has expired');
	  			return;
	  		}
			
			var lrequestid = $("#inputRequestId").val();
			
			var agent = {
				 requestid : lrequestid
				 };
	
			
			var request = $.ajax({
				url: '<? echo base_url();?>timeswitch/acceptrequestagent',
				type: 'POST',
				data: agent,
				beforeSend:function(){
					console.log('sending...');
					$('#myPleaseWait').modal('show');
				},
				success:function(result){
					
					console.log('sent!');
					$('#myPleaseWait').modal('hide');
					document.location = '<? echo base_url();?>admin';
				}, 
				error:function(exception){console.log(exception);}
				
			});
			
			request.fail(function( jqXHR, textStatus ) {
  			console.log( "Request failed: " + textStatus );
			});
			
		});


		$("#btnDeclineRequest").click(function(){

			
			var umbral = moment.utc('<? echo $limite; ?>');
	        console.log(umbral);
	        console.log(moment().format('DD/MM/YYYY HH:mm'));
			console.log(moment().isSameOrBefore(umbral));

	  		if(!moment().isBefore(umbral))
	  		{
	  			$("#lblInfo").text('This Request has expired');
	  			return;
	  		}
			
			var lrequestid = $("#inputRequestId").val();
			var lreason = $("#inputDeclineReason").val();
			
			var agent = {
				 requestid : lrequestid,
				 reason : lreason
				 };
	
			
			var request = $.ajax({
				url: '<? echo base_url();?>timeswitch/declineRequest',
				type: 'POST',
				data: agent,
				beforeSend:function(){
					console.log('sending...');
					$('#myPleaseWait').modal('show');
				},
				success:function(result){
					
					console.log('sent!');
					$('#myPleaseWait').modal('hide');
					document.location = '<? echo base_url();?>admin';
				}, 
				error:function(exception){console.log(exception);}
				
			});
			
			request.fail(function( jqXHR, textStatus ) {
  			console.log( "Request failed: " + textStatus );
			});
			
		});
		
		$("#btnNewAgent").click(function(){
			$("#frmRequestInfo").show();	
			$('#calendar').fullCalendar('render');
		});
		
		$("#btnCancel").click(function(){
			$("#inputAgentId").val('');
			$("#inputDaysOff").val('');
			$("#frmAgentData").hide();	
		});
			
		return false;
	})

  	function selectFecha(fecha, posicion){

  		// evaluamos si aun tiene tiempo para seleccionar ese dia
  		var umbral = moment(fecha).hour(9).minutes(0).add(-1,'days');

  		if(!moment().isBefore(umbral))
  		{
  			$("#lblInfo").text('Ya esta fuera de horario');
  		}
  		else
  		{
	  		//$("#inputSelectedPosition").val(posicion);
	  		//$("#inputSelectedDate").val(fecha);
	  		$("#inputFinalDate").val(fecha);
	  		$("#inputFinalPosition").val(posicion);
	  		//$("#divSelectedInfo").show();
	  		$("#divStep2").show();
		    $("#divStep1").hide();
		}
   	}

	function loadAgent(idagente, idempresa, idoficina)
	{
		$("#frmAgentData").show();	
		
		
		var agentData = { agenteid : idagente,
						  idempresa : idempresa,
						  idoficina : idoficina
						 };
		$.ajax({
			url: 'descansos/loadagentid',
			type: 'POST',
			data : agentData,
			beforeSend:function(){
				$('#myPleaseWait').modal('show');
				$(document).scrollTop();
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