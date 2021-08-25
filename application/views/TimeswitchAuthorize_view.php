  
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
            <div id="divFinalStep" >
            	 <fieldset required="required" style="text-align: center">
                    <legend>Shift Request Received. Please Review and Choose Action.</legend>
               		<div class="row">
	                    <div class='col-sm-3 col-xs-6'>
		                    <div class='form-group'>
		                       <label for="inputFechaIni">Date</label>
		                       <input class="form-control" id="inputFinalDate" name="inputFinalDate" size="30" type="text" value="<? echo $request->fechacambio; ?>" readonly="true" />
		                    </div>
		                </div>
		                <div class='col-sm-3 col-xs-6'>
			                <div class='form-group'>
			                    <label for="inputFechaIni">Type</label>
			                       <input class="form-control" id="inputFinalTipo" name="inputFinalTipo" size="30" value="<? echo $request->tipocambio; ?>" type="text" readonly="true" />
			                </div>
			            </div> 
			        </div>
			        <div class="row">
			            <div class='col-sm-2 col-xs-2'>
			                <div class='form-group'>
			                    <label for="inputSolicitant">Solicitant</label>
			                       <input class="form-control" id="inputSolicitant" name="inputSolicitant" size="20" value="<? echo $request->shortname; ?>" type="text" readonly="true" />
			                       <input class="form-control" id="inputlIdSolicitant" name="inputlIdSolicitant" value="<? echo $request->idagente; ?>" size="20" type="hidden" />
			                </div>
			            </div> 
			            <div class='col-sm-2 col-xs-2'>
			                <div class='form-group'>
			                    <label for="inputInitialPosition">Position</label>
			                       <input class="form-control" id="inputInitialPosition" name="inputInitialPosition" size="20" value="<? echo $request->posicioninicial; ?>" type="text" readonly="true" />
			                </div>
			            </div>
			            <div class='col-sm-2 col-xs-2'>
			                <div class='form-group'>
			                    <label for="inputAgentAccepted">Recipient</label>
			                       <input class="form-control" id="inputAgentAccepted" name="inputAgentAccepted" size="20" value="<? echo $request->agentecambio; ?>" type="text" readonly="true" />
			                       <input class="form-control" id="inputlIdSolicitant" name="inputlIdSolicitant" value="<? echo $request->idagente; ?>" size="30" type="hidden" />
			                </div>
			            </div> 
			            <div class='col-sm-2 col-xs-2'>
			                <div class='form-group'>
			                    <label for="inputFechaIni">Position</label>
			                       <input class="form-control" id="inputFinalPosition" name="inputFinalPosition" size="20" value="<? echo $request->posicionsolicitada; ?>" type="text" readonly="true" />
			                </div>
			            </div>
			        </div>
			        <div class="row">
			        	<div class='col-sm-4'>
			        		<label for="inputFechaRequested">Date Requested</label>
			                       <input class="form-control" id="inputFechaRequested" name="inputFechaRequested" size="20" value="<? echo $request->fechasolicitud; ?>" type="text" readonly />
			        	</div>
			        	<div class='col-sm-4'>
			        		<label for="inputFechaAccept">Date Accepted</label>
			                       <input class="form-control" id="inputFechaAccept" name="inputFechaAccept" size="20" value="<?
			                       	$IST = new DateTime($request->fechaacepta, new DateTimeZone('UTC'));

								    // change the timezone of the object without changing it's time
								    $IST->setTimezone(new DateTimeZone('America/Cancun'));

								    // format the datetime
								   	echo $IST->format('Y-m-d H:i:s T');
			                        ?>" type="text" readonly />
			        	</div>
			        </div>
			        <div class="row" style="margin-top: 20px;">
	                    <div class='col-sm-8'>
                            <button type="button" id="btnAuthorizeRequest" class="btn btn-success">Authorize Request</button>
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

    <div class="modal fade" id="modDeclineRequest" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Decline Request</h5>
            <input type="hidden" id="hdnIdRequest" />
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
          	<span>If you want to decline this request please provide a reason.</span>
          	<input type="text" id="inputDeclineReason" name="inputDeclineReason"/>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" id="btnExecuteDecline" href="javascript:executeDecline();">Decline</a>
          </div>
        </div>
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
		
		$("#btnAuthorizeRequest").click(function(){
			if(<?
        		if($request->tipocambio == 'Cover' )
        			if($solicitante[0]['puesto'] == 'LEAD')
        				if($coordinador == 'NO')
        					echo 1;
        				else
        					echo 0;
        			else
        				echo 0;
        		else
        			echo 0;?>)
			{
				alert('Only the Station Manager can Authorize this Request');
				return;
			}
			
			var lrequestid = $("#inputRequestId").val();
			
			var agent = {
				 requestid : lrequestid
				 };
	
			
			var request = $.ajax({
				url: '<? echo base_url();?>timeswitch/authorizerequest',
				type: 'POST',
				data: agent,
				beforeSend:function(){
					console.log('sending...');
					$('#myPleaseWait').modal('show');
				},
				success:function(result){
					result = jQuery.parseJSON(result);
					alert(result.msg);
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
			$("#modDeclineRequest").modal('show');
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

	function executeDecline()
	{
		var lrequestid = $("#inputRequestId").val();
		var lreason    = $("#inputDeclineReason").val();
			
		var info = {
			 requestid : lrequestid,
			 reason : lreason
		};

		
		var request = $.ajax({
			url: '<? echo base_url();?>timeswitch/declinerequest',
			type: 'POST',
			data: info,
			beforeSend:function(){
				console.log('sending...');
				$('#myPleaseWait').modal('show');
			},
			success:function(result){
				result = jQuery.parseJSON(result);
				alert(result.msg);
				console.log('sent!');
				
				$('#myPleaseWait').modal('hide');
				document.location = '<? echo base_url();?>admin';
			}, 
			error:function(exception){console.log(exception);}
			
		});
		
		request.fail(function( jqXHR, textStatus ) {
			console.log( "Request failed: " + textStatus );
		});
	}
</script>