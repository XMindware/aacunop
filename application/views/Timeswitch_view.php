  
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
	    width: 400px;
	    background-color: #FFFFFF;
	      border-radius: 6px;
	        box-shadow: 0 1px 2px #C3C3C3;
	    }

	   .redc {
            color : #F1948A;
            font-size: 12px;
        }
	</style>
	<script src="<? echo base_url(); ?>assets/js/custypeahead.js"></script>
    <div class="container">
        <div class="panel-body">
            <div class="panel-heading">
            	<div class="row">
                <h2><span class="label label-primary">Shift Change Request Control</span></h2>
                <?
                	if(!$requestshoy)
                	{
                ?>
                <button type="button" id="btnNewAgent" class="btn btn-link">New Request</button>
                <?
                	}
                ?>
                <input type="hidden" id="inputIdEmpresa" value="<? echo $idempresa; ?>" />
                <input type="hidden" id="inputIdOficina" value="<? echo $idoficina; ?>" />
                <input type="hidden" id="inputShortname" value="<? echo $shortname; ?>" />
                <input type="hidden" id="inputIdAgenteCambio" />
                </div>
            </div>    
             
            <form id="frmRequestInfo" action="#">          	
				<div id="divStep1">               
	                <fieldset required="required" style="text-align: center">
	                    <legend>1. Select the type of Request</legend>
	                    
	                    <div class='row'>
	                        <div class='col-sm-8'>
	                            <div class='form-group' style="text-align: left">
	                               <div class="radio">
									  <label><input type="radio" id="inputTipoCambio" name="inputTipoCambio" value="switch" checked>Shift Change</label>
									</div>
									<div class="radio">
									  <label><input type="radio" id="inputTipoCambio" name="inputTipoCambio" value="dayoff" >Day Off Switch</label>
									</div>
									<div class="radio">
									  <label><input type="radio" id="inputTipoCambio" name="inputTipoCambio" value="cover">Cover</label>
									</div>
	                            </div>
	                        </div>
	                    </div>
	                    <div class='col-sm-4'>
	                        <div class='form-group'>
	                            <button type="button" id="btnNext1" class="btn btn-success">Next</button>
	                            <button type="button" id="btnCancel1" class="btn btn-default">Back</button>
	                        </div>
	                    </div>        
	                </fieldset>
	            </div>
	            <div id="divStep2">
	            	<fieldset required="required" style="text-align: center">
	                    <legend>2. Please select the date requested</legend>
	                    <label id="lblInfo" style="color: red"></label>
	                    <div id="calendar"></div>
	                    <br/>
	                    <br/>
	                </fieldset>
	            </div>
	            <div id="divStep3">
	            	 <fieldset required="required" style="text-align: center">
	                    <legend>2. Who is covering you?</legend>
	                    <div id="calendar"></div>
	                    <div class='row'>
	                        <div class='col-sm-8'>
	                            <div class='form-group'>
	                            <label for="inputSelectAgentCambio">Enter the agent name</label>
	                               <input class="form-control typeahead" id="inputSelectAgentCambio" name="inputSelectAgentCambio" required="true" placeholder="Search Agents" size="30" type="text" />
	                            </div>
	                        </div>
	                    </div>
	                    <div class='col-sm-4'>
	                        <div class='form-group'>
	                            <button type="button" id="btnNext3" class="btn btn-success">Next</button>
	                            <button type="button" id="btnCancel3" class="btn btn-default">Back</button>
	                        </div>
	                    </div>        
	                </fieldset>
	            </div>
	            <div id="divStep4">
	            	 <fieldset required="required" style="text-align: center">
	            	 	<input type="hidden" id="inputGiveDate" />
	                    <legend>2. Select the date to give</legend>
	                    <label id="lblInfo" style="color: red"></label>
	                    <div id="calendar2"></div>
	                    <br/>
	                    <br/>       
	                </fieldset>
	            </div>
	            <div id="divFinalStep">
	            	 <fieldset required="required" style="text-align: center">
	                    <legend>Confirm Request Info</legend>
	               		<div class="row">
		                    <div class='col-sm-3 col-xs-6'>
			                    <div class='form-group'>
			                       <label for="inputFechaIni">Date Selected</label>
			                       <input class="form-control" id="inputFinalDate" name="inputFinalDate" size="30" type="date" readonly="true" />
			                    </div>
			                </div>
			                <div class='col-sm-3 col-xs-6'>
				                <div class='form-group'>
				                    <label for="inputInitialPosition">Selected Position</label>
				                       <input class="form-control" id="inputInitialPosition" name="inputInitialPosition" size="30" type="text" readonly="true" />
				                </div>
				            </div>
				            <div class='col-sm-3 col-xs-6'>
				                <div class='form-group'>
				                    <label for="inputFechaIni">Type of Request</label>
				                       <input class="form-control" id="inputFinalTipo" name="inputFinalTipo" size="30" type="text" readonly="true" />
				                </div>
				            </div> 
				        </div>
				        <div class="row">
				            <div class='col-sm-3 col-xs-6'>
				                <div class='form-group'>
				                    <label for="inputFechaIni">Selected Agent</label>
				                       <input class="form-control" id="inputFinalAgent" name="inputFinalAgent" size="30" type="text" readonly="true" />
				                       <input class="form-control" id="inputFinalIdAgent" name="inputFinalIdAgent"  type="hidden" />
				                       <input class="form-control" id="inputUserPosicion" name="inputUserPosicion"  type="hidden" />
				                       <input class="form-control" id="inputUserJornada" name="inputUserJornada"    type="hidden" />
				                </div>
				            </div> 
				            <div class='col-sm-3 col-xs-6'>
				                <div class='form-group'>
				                    <label for="inputFinalPosition">Position Requested</label>
				                       <input class="form-control" id="inputFinalPosition" name="inputFinalPosition" size="30" type="text" readonly="true" />
				        
				                </div>
				            </div> 
				            <div class='col-sm-3 col-xs-6' id="infTargetDate">
			                    <div class='form-group'>
			                       <label for="inputFechaIni">Date Given</label>
			                       <input class="form-control" id="inputTargetDate" name="inputTargetDate" size="30" type="date" readonly="true" />
			                    </div>
			                </div>
				        </div>
				        <div class="row">
		                    <div class='col-sm-4'>
		                        <div class='form-group'>
		                            <button type="button" id="btnSubmitRequest" class="btn btn-success">Send Request</button>
		                            <button type="button" id="btnBackFinal" class="btn btn-default">Back</button>
		                        </div>
		                    </div>
		                </div>        
	                </fieldset>
	            </div>
            </form>
            <? 
            if($registros){
            	?>
            <div class="row">
            	<div class="col-md-12">
            		<legend>Current Requests </legend>
                    <table class="table table-condensed">
                    <thead>
                      <tr>
                        <th>Type of Request</th>
                        <th>Date</th>
                        <th>Solicitant</th>
                        <th>Position</th>
                        <th>Date Given</th>
                        <th>Recipient</th>
                        <th>Accepted</th>
                        <th>Authorized</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <?
						foreach($registros as $row)
						{
							if(strtotime($row['fechacambio']) >= time())
							{
								$tipocambio = $row['tipocambio'];
								if($row['tipocambio'] == 'Day Off')
									$tipocambio = "Day Off Switch";
					?>
                    <tbody>
                      <tr>
                        <td><? echo $tipocambio; ?></button></td>
                        <td><? echo date('d/m/Y',strtotime($row['fechacambio'])); ?></td>
                        <td><? echo $row['shortname']; ?></td>
                        <td><? echo $row['posicioninicial'] . '->' . $row['posicionsolicitada']; ?></td>
                        <td><? echo $row['fechatarget'] == '0000-00-00' ? '' : date('d/m/Y',strtotime($row['fechatarget'])) ; ?></td>
                        <td><? echo $row['agentecambio']; ?></td>
                        <td><? echo $row['fechaacepta'] == '0000-00-00 00:00:00' ? 'NO' : 'YES'; ?></td>
                        <td><? echo $row['fechaautoriza'] == '0000-00-00 00:00:00' ? 'NO' : 'YES'; ?></td>
                        <td>
                        	<?
                        	if($row['status']=='REQ' || $row['status']=='ACC')
                        	{
                        		?>
                        		<span class="glyphicon glyphicon-remove-sign redc" onclick="javascript:doDeleteRequest('<? echo $row['uniqueid']; ?>');"></span>
                        		<?
                        	}
                        	?>
                        	</td>
                      </tr>
                      <?
                      		} 
                  		} ?>
                    </tbody>
                  	</table>
          		</div>
      		</div>
	      	<?
	      	}
	      ?>
	      	<div class="row">
        		<div class="col-md-12">
	        		<legend>Historic Requests </legend>
	                <table class="table table-condensed">
	                <thead>
	                  <tr>
	                    <th>Type of Request</th>
	                    <th>Date</th>
	                    <th>Solicitant</th>
	                    <th>Position</th>
	                    <th>Date Given</th>
	                    <th>Recipient</th>
	                    <th>Accepted</th>
	                    <th>Authorized</th>
	                    <th>Status</th>
	                  </tr>
	                </thead>
	                <?
	                	if($registros)
						foreach($registros as $row)
						{
							$status = 'OK';
							// son solicitudes actuales
							if(strtotime($row['fechacambio']) < time())
							{
								$tipocambio = $row['tipocambio'];
								if($row['tipocambio'] == 'Day Off')
									$tipocambio = "Day Off Switch";
					?>
	                
	                  <tr>
	                    <td><? echo $tipocambio; ?></button></td>
	                    <td><? echo date('d/m/Y',strtotime($row['fechacambio'])); ?></td>
	                    <td><? echo $row['shortname']; ?></td>
	                    <td><? echo $row['posicioninicial'] . '->' . $row['posicionsolicitada']; ?></td>
	                    <td><? echo $row['fechatarget'] == '0000-00-00' ? '' : date('d/m/Y',strtotime($row['fechatarget'])) ; ?></td>
	                    <td><? echo $row['agentecambio']; ?></td>
	                    <td><? echo $row['fechaacepta'] == '0000-00-00 00:00:00' ? 'NO' : 'YES'; ?></td>
	                    <td><? echo $row['fechaautoriza'] == '0000-00-00 00:00:00' ? 'NO' : 'YES ' . $row['leadautoriza']; ?></td>
	                    <td><? echo $status; ?></td>
	                  </tr>
	                  <?
	                  		} 
	              		} ?>
	                </tbody>
	              </table>
          		</div>
      		</div>
      	</div>
    </div>
    <!-- End Horizontal Form -->
     <!-- delete request Modal-->
    <div class="modal fade" id="modDeleteRequest" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Delete Request</h5>
            <input type="hidden" id="hdnIdRequest" />
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Please confirm if you want to delete selected request, this cannot be undone.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" id="btnExecuteDelete" href="javascript:executeDeleteRequest();">Delete Request</a>
          </div>
        </div>
      </div>
    </div>
    
    
    <script type="text/javascript">
 
	$('#myPleaseWait').modal('hide');
	$(document).ready(function(){

		var agenteslista;
		$("#divSelectedInfo").hide();
		$("#infTargetDate").hide();

		
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
	            $posicion = $pos['posicion'];
	            switch ($pos['tipocambio']) {
	              case 'Cover':
	                $tipocambio = 'Cover';
	                break;
	              case 'Switch':
	                $tipocambio = 'SW HR';
	                break;
	              case 'Day Off':
	                $tipocambio = 'DAY OFF';
	                break;
	              default:
	                
	                break;
	            }

	            if($pos['idagentec'] != '')
	            {
	              if($pos['idagente'] != $pos['idagentec'] )
	                $agentecambio = $pos['shortname'];
	              else
	                $agentecambio = $pos['agentecambio'];
	            }
	            if($pos['status'] == 'AUT'){
	              $color = 'success';
	              $posicion = $pos['posicion'] . ' ' . $tipocambio. ' ' . $agentecambio;
	            }
	            else if($pos['status'] == 'ACC' ){
	              $color='chill';
	              $posicion = $pos['posicion'] . ' ' . $tipocambio . ' ' . $agentecambio;
	            }
	            else if($pos['status'] == 'REQ'){
	              $color='warning';
	              $posicion = $pos['posicion'] . ' ' . $tipocambio . ' ' . $agentecambio;
	            }
	            else
	              $color='info';
	            ?>

	          {
	            title: '<? echo $posicion; ?>',
            	start: '<? echo $pos['fecha']; ?>',
	            url : 'javascript:selectFecha("<? echo $pos['fecha']; ?>","<? echo $pos['posicion']; ?>","<? echo $pos['workday']; ?>");',
	            className : '<? echo $color; ?>'
	          },
	          <?
	        }
	        ?>
	        ],
	      });

	      var calendar2 =  $('#calendar2').fullCalendar({
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

	          foreach($monthlydayoffschedule as $pos)
	          {

	            $color = 'important';
	            $posicion = $pos['posicion'];
	            switch ($pos['tipocambio']) {
	              case 'Cover':
	                $tipocambio = 'Cover';
	                break;
	              case 'Switch':
	                $tipocambio = 'SW HR';
	                break;
	              case 'Day Off':
	                $tipocambio = 'DAY OFF';
	                break;
	              default:
	                
	                break;
	            }

	          
	              $color='info';
	            ?>

	          {
	            title: '<? echo $pos['posicion']; ?>',
            	start: '<? echo $pos['fecha']; ?>',
	            url : 'javascript:selectDayOffFecha("<? echo $pos['fecha']; ?>","<? echo $pos['posicion']; ?>");',
	            className : '<? echo $color; ?>'
	          },
	          <?
	        }
	        ?>
	        ],
		});

	      // finaliza inicializar calendario
	    

	     // selecciono el tipo de cambio, pasa a seleccionar el agente
	     $("#btnNext1").click(function(){
	     	

	     	$("#divStep1").hide();
	     	$("#divStep2").show();
	     });

	     // selecciono el agente pasa a la revision
	     $("#btnNext3").click(function(){
	     	//validar que no se elija a si mismo
	     	if($("#inputSelectAgentCambio").val() == $("#inputShortname").val())
	     	{
	     		alert('You cannot choose yourself to switch position.')
	     	}
	     	else
	     	{
	     		if($("#inputFinalTipo").val()=='Day Off')
	     		{
	     			$("#inputFinalAgent").val($("#inputSelectAgentCambio").val());
	     			//CargarInfoDayOffs();
	     			$("#infTargetDate").show();
	     			$("#divStep3").hide();
			     	$("#divStep4").show();
	     		}
	     		else
	     		{
	     			$("#inputFinalAgent").val($("#inputSelectAgentCambio").val());
			     	$("#divFinalStep").show();
			     	$("#divStep3").hide();
			     	$("#divFinalStep").show();
	     		}
		     }
	     });

	     // selecciono el agente pasa a la revision
	     $("#btnNext4").click(function(){
	     	//validar que no se elija a si mismo
 
 			$("#inputFinalAgent").val($("#inputSelectAgentCambio").val());
	     	$("#divFinalStep").show();
	     	$("#divStep4").hide();
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

	     $("#btnBackFinal").click(function(){
	     	$("#divStep3").show();
	     	$("#divFinalStep").hide();
	     })
		
		$("#btnSubmitRequest").click(function(){
			
			var lfechacambiar = $("#inputFinalDate").val();
			var lposicioninicial = $("#inputInitialPosition").val();
			var lposicionsolicitada = $("#inputFinalPosition").val();
			var ltipocambio = $("#inputFinalTipo").val();
			var lagentecambio = $("#inputFinalAgent").val();
			var ltargetdate = $("#inputTargetDate").val();
			
			$("#frmAgentData").hide();	
			var agent = {
				 fechacambiar : lfechacambiar,
				 posicioninicial : lposicioninicial,
				 posicionsolicitada : lposicionsolicitada,
				 tipocambio : ltipocambio,
				 agentecambio : lagentecambio,
				 fechatarget : ltargetdate
				 };
	
			
			var request = $.ajax({
				url: '<? echo base_url(); ?>timeswitch/postswitchrequest',
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
		
		$("#btnNewAgent").click(function(){
			$("#frmRequestInfo").show();	
			$('#calendar').fullCalendar('render');
		});
		
		$("#btnCancel").click(function(){
			$("#inputAgentId").val('');
			$("#inputDaysOff").val('');
			$("#frmAgentData").hide();	
		});
			
		$("#frmRequestInfo").hide();
		$("#divFinalStep").hide();
		$("#divStep2").hide();
		$("#divStep3").hide();
		$("#divStep4").hide();
		return false;
	})

  	function doDeleteRequest(idrequest)
  	{
  		console.log(idrequest);
  		$("#hdnIdRequest").val(idrequest);
  		$('#modDeleteRequest').modal('show');
  	}

  	function executeDeleteRequest()
  	{
  		var lidrequest = $("#hdnIdRequest").val();

  		var agent = {
			 idrequest : lidrequest,
			 };
		
		var request = $.ajax({
			url: '<? echo base_url(); ?>timeswitch/deleterequest',
			type: 'POST',
			data: agent,
			beforeSend:function(){
				console.log('sending...');
				$('#myPleaseWait').modal('show');
			},
			success:function(result){
				console.log(result);

				$('#myPleaseWait').modal('hide');
				location.reload();
			}, 
			error:function(exception){console.log(exception);}
			
		});
  	}

  	function cargarAgentesSwitchFecha()
  	{
  		var lfechacambiar = $("#inputFinalDate").val();

  		var agent = {
			 fechacambiar : lfechacambiar,
			 };
		console.log(agent);
		
		var request = $.ajax({
			url: '<? echo base_url(); ?>timeswitch/getagentsswitchdate',
			type: 'POST',
			data: agent,
			beforeSend:function(){
				console.log('sending...');
				$('#myPleaseWait').modal('show');
			},
			success:function(result){
				//console.log(result);
				// arma la lista de agentes para seleccionar
				agenteslista = result;

				$('#inputSelectAgentCambio.typeahead').typeahead({
		            source : agenteslista,
		            display : 'name',
		            val : 'id',
		            onSelect: function(item) {

			        	$("#inputFinalPosition").val(item.comment);
			        	
			    	}
		        });
				console.log('sent!');
				//console.log(result);
				$('#myPleaseWait').modal('hide');
				//location.reload();
			}, 
			error:function(exception){console.log(exception);}
			
		});
  	}

  	function CargarInfoDayOffs()
  	{

  		var lfechacambiar = $("#inputFinalDate").val();
  		var lagentecambio = $("#inputIdAgenteCambio").val();

  		var agent = {
			 fechacambiar : lfechacambiar
			 };
		console.log(agent);
		
		var request = $.ajax({
			url: '<? echo base_url(); ?>timeswitch/getAgentDaysOffDate',
			type: 'POST',
			data: agent,
			beforeSend:function(){
				console.log('sending...');
				$('#myPleaseWait').modal('show');
			},
			success:function(result){
				var listado = [];
				$.each(result,function(value,row){
					listado.push({
						title : row.posicion,
						start : row.fecha,
						url : 'javascript:selectDayOffFecha("' + row.fecha + '","' + row.posicion + '");',
						className : 'info'
					});
				});

		  		/* initialize the calendar
			      -----------------------------------------------------------------*/
			      
			      var calendar2 =  $('#calendar2').fullCalendar({
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
			       
			        events: listado
			      });

			      $('#myPleaseWait').modal('hide');
			    }, 
			error:function(exception){console.log(exception);}
			
		});

  	}

  	function cargarAgentesCoverFecha()
  	{
  		var lfechacambiar = $("#inputFinalDate").val();
  		var luserposicion = $("#inputUserPosicion").val();
  		var luserjornada = $("#inputUserJornada").val();

  		var agent = {
			 fechacambiar : lfechacambiar,
			 userposicion : luserposicion,
			 userjornada : luserjornada
			 };
		console.log(agent);
		
		var request = $.ajax({
			url: '<? echo base_url(); ?>timeswitch/getagentscoverdate',
			type: 'POST',
			data: agent,
			beforeSend:function(){
				console.log('sending...');
				$('#myPleaseWait').modal('show');
			},
			success:function(result){
				console.log(result);
				// arma la lista de agentes para seleccionar
				agenteslista = result;

				$('#inputSelectAgentCambio.typeahead').typeahead({
		            source : agenteslista,
		            display : 'name',
		            val : 'id',
		            onSelect: function(item) {
			        	$("#inputFinalPosition").val(item.comment);
			    	}
		        });
				console.log('sent!');
				//console.log(result);
				$('#myPleaseWait').modal('hide');
				//location.reload();
			}, 
			error:function(exception){console.log(exception);}
			
		});
  	}


  	function cargarAgentesDayOffFecha()
  	{
  		var lfechacambiar = $("#inputFinalDate").val();
  		var luserposicion = $("#inputUserPosicion").val();
  		var luserjornada = $("#inputUserJornada").val();

  		var agent = {
			 fechacambiar : lfechacambiar,
			 userposicion : luserposicion,
			 userjornada : luserjornada
			 };
		console.log(agent);
		
		var request = $.ajax({
			url: '<? echo base_url(); ?>timeswitch/getagentsdayoffdate',
			type: 'POST',
			data: agent,
			beforeSend:function(){
				console.log('sending...');
				$('#myPleaseWait').modal('show');
			},
			success:function(result){
				console.log(result);
				// arma la lista de agentes para seleccionar
				agenteslista = result;

				$('#inputSelectAgentCambio.typeahead').typeahead({
		            source : agenteslista,
		            display : 'name',
		            val : 'id',
		            onSelect: function(item) {
			        	$("#inputFinalPosition").val(item.comment);		        	
			        	$("#inputIdAgenteCambio").val(item.value);
			    	}
		        });
				console.log('sent!');
				//console.log(result);
				$('#myPleaseWait').modal('hide');
				//location.reload();
			}, 
			error:function(exception){console.log(exception);}
			
		});
  	}



  	function selectFecha(fecha, posicion, jornada){

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
	  		$("#inputUserPosicion").val(posicion);
	  		$("#inputUserJornada").val(jornada);
	  		
	  		$("#inputInitialPosition").val(posicion);

	  		console.log($("input[id=inputTipoCambio]:checked").val());
	     	switch($("input[id=inputTipoCambio]:checked").val())
	     	{
	     		case 'switch' : 
	     		{
	     			// pidio un switch, necesitamos cargas los agentes que tienen ese dia
	     			cargarAgentesSwitchFecha();
	     			$("#inputFinalTipo").val('Switch');
	     			break;
	     		}
	     		case 'dayoff' : 
	     			cargarAgentesDayOffFecha();
	     			$("#inputFinalTipo").val('Day Off');
	     			break;
	     		case 'cover' : 
	     			cargarAgentesCoverFecha();
	     			$("#inputFinalTipo").val('Cover');
	     			break;
	     	}
	  		//$("#divSelectedInfo").show();
	  		$("#divStep3").show();
		    $("#divStep2").hide();
		}
   	}

   	function selectDayOffFecha(fecha, posicion)
   	{
   		// evaluamos si aun tiene tiempo para seleccionar ese dia
  		var umbral = moment(fecha).hour(9).minutes(0).add(-1,'days');

  		if(!moment().isBefore(umbral))
  		{
  			alert('This date cannot be used.');
  		}
  		else
  		{
  			var final = moment($("#inputFinalDate").val());
  			var target = moment(fecha);
  			if(moment.duration(target.diff(final)).asDays() >=8)
  			{
  				alert('Difference between dates cannot be more than 8 days.');
  			}
  			else
  			{
		  		//$("#inputSelectedPosition").val(posicion);
		  		//$("#inputSelectedDate").val(fecha);
		  		$("#inputTargetDate").val(fecha);
		  		
		  		//$("#divSelectedInfo").show();
		  		$("#divFinalStep").show();
			    $("#divStep4").hide();
			}
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
			url: '<? echo base_url(); ?>descansos/loadagentid',
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