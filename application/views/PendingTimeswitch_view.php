  
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

	    .greenc {
            color : #ABEBC6;
            font-size: 12px;
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
                <input type="hidden" id="inputIdEmpresa" value="<? echo $idempresa; ?>" />
                <input type="hidden" id="inputIdOficina" value="<? echo $idoficina; ?>" />
                <input type="hidden" id="inputShortname" value="<? echo $shortname; ?>" />
                <input type="hidden" id="inputRequestToRemove" />
                <input type="hidden" id="inputIdAgenteToRemove" />
                <button type="button" id="btnNewRequest" class="btn btn-link">Place New Request</button>
                <button type="button" id="btnNewThreeRequest" class="btn btn-link">Place New Triangular Request </button>
                </div>
            </div>  

            <form id="frmRequestInfo" action="#" autocomplete="off">   
            	<div id="divStep1">               
	                <fieldset required="required" style="text-align: center">
	                    <legend>1. Type the agent requesting </legend>
	                    <div class='row'>
	                        <div class='col-sm-8'>
	                            <div class='form-group'>
	                            <label for="inputSelectSolicitant">Enter the agent name</label>
	                               <input class="form-control typeahead" id="inputSelectSolicitant" name="inputSelectSolicitant" required="true" placeholder="Search Agents" size="30" type="text" />
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
	                    <legend>2. Select the type of Request</legend>
	                    
	                    <div class='row'>
	                        <div class='col-sm-8'>
	                            <div class='form-group' style="text-align: left">
	                            	<fieldset id="inputTipoCambio">
									    <div class="radio">
										  <label><input type="radio" name="inputTipoCambio" value="switch" checked>Shift Change</label>
										</div>
										<div class="radio">
										  <label><input type="radio" name="inputTipoCambio" value="dayoff" >Day Off Switch</label>
										</div>
										<div class="radio">
										  <label><input type="radio" name="inputTipoCambio" value="cover">Cover</label>
										</div>
								  </fieldset>
	                               
	                            </div>
	                        </div>
	                    </div>
	                    <div class='col-sm-4'>
	                        <div class='form-group'>
	                            <button type="button" id="btnNext2" class="btn btn-success">Next</button>
	                            <button type="button" id="btnCancel2" class="btn btn-default">Back</button>
	                        </div>
	                    </div>        
	                </fieldset>
	            </div>
	            <div id="divStep3">
	            	<fieldset required="required" style="text-align: center">
	                    <legend>3. Please select the date requested</legend>
	                    <label id="lblInfo" style="color: red"></label>
	                    <div id="calendar"></div>
	                    <br/>
	                    <br/>
	                </fieldset>
	            </div>
	            <div id="divStep4">
	            	 <fieldset required="required" style="text-align: center">
	                    <legend>4. Who is covering?</legend>
	                   
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
	                            <button type="button" id="btnNext4" class="btn btn-success">Next</button>
	                            <button type="button" id="btnCancel4" class="btn btn-default">Back</button>
	                        </div>
	                    </div>        
	                </fieldset>
	            </div>
	            <div id="divStep5">
	            	 <fieldset required="required" style="text-align: center">
	            	 	<input type="hidden" id="inputGiveDate" />
	                    <legend>5. Select the date to give</legend>
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
		                        	<label for="inputFechaIni">Place Request pre-accepted</label>
		                        	<input type="checkbox" class="custom-control-input" id="inputAccepted" name="inputAccepted">
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
            <!-- Termina form de cambios normales -->

            <!-- Inicia form de cambios triangulares -->

            <form id="frmThreeRequestInfo" autocomplete="off" action="#" style="display:none">   
            	<div id="divThreeStep1">               
	                <fieldset required="required" style="text-align: center">
	                    <legend>1. Type the agent initiating the three way request </legend>
	                    <div class='row'>
	                        <div class='col-sm-8'>
	                            <div class='form-group'>
	                            <label for="inputThreeSolicitant">Enter the agent name</label>
	                               <input class="form-control typeahead" id="inputThreeSolicitant" name="inputThreeSolicitant" required="true" placeholder="Search Agents" size="30" type="text" />
	                            </div>
	                        </div>
	                    </div>
	                    <div class='col-sm-4'>
	                        <div class='form-group'>
	                            <button type="button" id="btnThreeNext1" class="btn btn-success">Next</button>
	                            <button type="button" id="btnThreeCancel1" class="btn btn-default">Back</button>
	                        </div>
	                    </div>        
	                </fieldset>
	            </div>       	
		
	            <div id="divThreeStep2" style="display:none">
	            	<fieldset required="required" style="text-align: center">
	                    <legend>2. Please select the date requested</legend>
	                    <label id="lblThreeInfo" style="color: red"></label>
	                    <div id="threecalendar"></div>
	                    <br/>
	                    <br/>
	                </fieldset>
	            </div>
	            <div id="divThreeStep3" style="display:none">
	            	 <fieldset required="required" style="text-align: center">
	                    <legend>3. Who is the middle cover?</legend>
	                   
	                    <div class='row'>
	                        <div class='col-sm-8'>
	                            <div class='form-group'>
	                            <label for="inputThreeSelectAgentCambio">Enter the agent name</label>
	                               <input class="form-control typeahead" id="inputThreeSelectAgentCambio" name="inputThreeSelectAgentCambio" required="true" placeholder="Search Agents" size="30" type="text" />
	                            </div>
	                        </div>
	                    </div>
	                    <div class='col-sm-4'>
	                        <div class='form-group'>
	                            <button type="button" id="btnThreeNext3" class="btn btn-success">Next</button>
	                            <button type="button" id="btnThreeCancel3" class="btn btn-default">Back</button>
	                        </div>
	                    </div>        
	                </fieldset>
	            </div>
	            <div id="divThreeStep4" style="display:none">
	            	 <fieldset required="required" style="text-align: center">
	                    <legend>4. Who is the agent with day off?</legend>
	                   
	                    <div class='row'>
	                        <div class='col-sm-8'>
	                            <div class='form-group'>
	                            <label for="inputThreeSelectFinalAgent">Enter the agent name</label>
	                               <input class="form-control typeahead" id="inputThreeSelectFinalAgent" name="inputThreeSelectFinalAgent" required="true" placeholder="Search Agents" size="30" type="text" />
	                            </div>
	                        </div>
	                    </div>
	                    <div class='col-sm-4'>
	                        <div class='form-group'>
	                            <button type="button" id="btnThreeNext4" class="btn btn-success">Next</button>
	                            <button type="button" id="btnThreeCancel4" class="btn btn-default">Back</button>
	                        </div>
	                    </div>        
	                </fieldset>
	            </div>
	            
	            <div id="divThreeFinalStep" style="display:none">
	            	 <fieldset required="required" style="text-align: center">
	                    <legend>Confirm Triangular Request Info</legend>
	               		<div class="row">
	               			<input type="hidden" id="inputThreeIdAgent1"><input type="hidden" id="inputThreeAgentPos1">
	               			<input type="hidden" id="inputThreeIdAgent2"><input type="hidden" id="inputThreeAgentPos2">
	               			<input type="hidden" id="inputThreeIdAgent3"><input type="hidden" id="inputThreeAgentPos3">
		                    <div class='col-sm-3 col-xs-6'>
			                    <div class='form-group'>
			                       <label for="inputThreeFechaCover">Date Selected</label>
			                       <input class="form-control" id="inputThreeFechaCover" name="inputThreeFechaCover" size="30" type="date" readonly="true" />
			                    </div>
			                </div>
			                <div class='col-sm-3 col-xs-6'>
				                <div class='form-group'>
				                    <label for="inputThreeAgent1">Requesting Agent</label>
				                       <input class="form-control" id="inputThreeAgent1" name="inputThreeAgent1" size="30" type="text" readonly="true" />
				                </div>
				            </div>
				            <div class='col-sm-3 col-xs-6'>
				                <div class='form-group'>
				                    <label for="inputThreeAgent2">Middle Agent</label>
				                       <input class="form-control" id="inputThreeAgent2" name="inputThreeAgent2" size="30" type="text" readonly="true" />
				                </div>
				            </div>
				            <div class='col-sm-3 col-xs-6'>
				                <div class='form-group'>
				                    <label for="inputThreeAgent3">Day Off Agent</label>
				                       <input class="form-control" id="inputThreeAgent3" name="inputThreeAgent3" size="30" type="text" readonly="true" />
				                </div>
				            </div>
				             
				        </div>
				        <div class="row">
		                    <div class='col-sm-4'>
		                        <div class='form-group'>
		                        	<label for="inputFechaIni">Place Request pre-accepted</label>
		                        	<input type="checkbox" class="custom-control-input" id="inputThreeAccepted" name="inputAccepted">
		                        </div>
		                    </div>
		                </div>
				        <div class="row">
		                    <div class='col-sm-4'>
		                        <div class='form-group'>
		                            <button type="button" id="btnThreeSubmitRequest" class="btn btn-success">Send Request</button>
		                            <button type="button" id="btnThreeBackFinal" class="btn btn-default">Back</button>
		                        </div>
		                    </div>
		                </div>        
	                </fieldset>
	            </div>
            </form> 

            <!-- Termina form de cambios Triangulares -->

        
          	<div class="row">
            	<div class="col-md-12">
            		<legend>Current Requests </legend>
                    <table class="table table-condensed">
                    <thead>
                      <tr>
                        <th>Action</th>
                        <th>Type of Request</th>
                        <th>Recipient Agent</th>
                        <th>Date</th>
                        <th>Position</th>
                        <th>Requesting Agent</th>
                        <th>Date Given</th>
                        <th>Accepted</th>
                        <th>Authorized</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?
                    	if($registros)
						foreach($registros as $row)
						{
							$status = 'OK';
							// son solicitudes actuales
							if(strtotime($row['fechacambio']) >= time())
							{
					?>
                    
                      <tr>
                      	<td>
                      		<?
                      			if($row['status'] != 'AUT')
                      			{
                      				if($row['tipocambio'] == 'Triangle')
                      				{
	                      				?>
	                      				<span class="glyphicon glyphicon-ok-sign greenc" style="cursor:pointer" onclick="javascript:goAuthorizeTriangle(<? echo $row['uniqueid'];?>);"></span>  <span class="glyphicon glyphicon-remove-sign redc" style="cursor:pointer" onclick="javascript:goRemove('<? echo $row['uniqueid'];?>','<? echo $row['idagente'];?>');"></span>	
	                      				<?
	                      			}
	                      			else
	                      			{
	                      				?>
	                      				<span class="glyphicon glyphicon-ok-sign greenc" style="cursor:pointer" onclick="javascript:goAuthorize(<? echo $row['uniqueid'];?>);"></span>  <span class="glyphicon glyphicon-remove-sign redc" style="cursor:pointer" onclick="javascript:goRemove('<? echo $row['uniqueid'];?>','<? echo $row['idagente'];?>');"></span>	
	                      				<?
	                      			}
                      			}
                      		?>
                      		</td>
                        <td><? echo $row['tipocambio']; ?></button></td>
                        <td><? echo $row['agentecambio']; ?></td>
                        <td><? echo date('d/m/Y',strtotime($row['fechacambio'])); ?></td>
                        <td><? echo $row['posicionsolicitada'] . ' &rarr; ' . $row['posicioninicial']; ?></td>
                        <td><? echo $row['shortname']; ?></td>
                        <td><? echo $row['fechatarget'] == '0000-00-00' ? '' : date('d/m/Y',strtotime($row['fechatarget'])) ; ?></td>
                        <td><? echo $row['fechaacepta'] == '0000-00-00 00:00:00' ? 'NO' : 'YES'; ?></td>
                        <td><? echo $row['fechaautoriza'] == '0000-00-00 00:00:00' ? 'NO' : 'YES ' . $row['leadautoriza']; ?></td>
                      </tr>
                      <?
                      		} 
                  		} ?>
                    </tbody>
                  </table>
              </div>
          	</div>

          	<div class="row">
            	<div class="col-md-12">
            		<legend>Historic Requests </legend>
                    <table class="table table-condensed">
                    <thead>
                      <tr>
                        <th>Action</th>
                        <th>Type of Request</th>
                        <th>Recipient Agent</th>
                        <th>Date</th>
                        <th>Position</th>
                        <th>Requesting Agent</th>
                        <th>Date Given</th>
                        <th>Accepted</th>
                        <th>Authorized</th>
                      </tr>
                    </thead>
                    <?
                    	if($registros)
						foreach($registros as $row)
						{
							// son solicitudes actuales
							if(strtotime($row['fechacambio']) < time())
							{
					?>
                    
                      <tr>
                      	<td>&nbsp;&nbsp;&nbsp;</td>
                      	<td><? echo $row['tipocambio']; ?></td>
                        <td><? echo $row['agentecambio']; ?></td>
                        <td><? echo date('d/m/Y',strtotime($row['fechacambio'])); ?></td>
                        <td><? echo $row['posicionsolicitada'] . ' &rarr; ' . $row['posicioninicial']; ?></td>
                        <td><? echo $row['shortname']; ?></td>
                        <td><? echo $row['fechatarget'] == '0000-00-00' ? '' : date('d/m/Y',strtotime($row['fechatarget'])) ; ?></td>
                        <td><? echo $row['fechaacepta'] == '0000-00-00 00:00:00' ? 'NO' : 'YES'; ?></td>
                        <td><? echo $row['fechaautoriza'] == '0000-00-00 00:00:00' ? 'NO' : 'YES ' . $row['leadautoriza']; ?></td>
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
    </div>
    <!-- End Horizontal Form -->
    <!-- Modal -->
	<div id="dlgRemoveRequest" class="modal fade" role="dialog">
	  <div class="modal-dialog">

	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        
	        <h2><span class="label label-primary" id="pFlightInfo">Plase Confirm</span></h2>
	      </div>
	      <div class="modal-body">
	        <center>
	            <p>Confirm that the selected request will be deleted</p>
	        </center>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-primary" id="btnExecuteRemoveRequest" data-dismiss="modal">Delete Request</button>
	        <button type="button" class="btn btn-dark" id="btnCancelRemove" data-dismiss="modal">Cancel</button>
	      </div>
	    </div>
	  </div>
	</div>
    
    <script type="text/javascript">
 
	$('#myPleaseWait').modal('hide');
	$(document).ready(function(){

		var agenteslista = [<? foreach($fullagents as $agent) { echo "{ id : '" . $agent['idagente'] . "', name : '" . $agent['shortname'] . "', status : '" . $agent['razon'] . "'},"; } ?>];

		$("#divSelectedInfo").hide();

		// carga la lista de agentes al input de solicitante inicial
		$('#inputSelectSolicitant.typeahead').typeahead({
            source : agenteslista,
            display : 'name',
            val : 'id',
            onSelect: function(item) {
            	console.log(item);
	        	$("#inputSelectSolicitant").val(item.text);
	    	}
        });

        $('#inputThreeSolicitant.typeahead').typeahead({
            source : agenteslista,
            display : 'name',
            val : 'id',
            onSelect: function(item) {
            	console.log(item);
	        	$("#inputThreeSolicitant").val(item.text);
	        	$("#inputThreeAgent1").val(item.text);
	        	$("#inputThreeIdAgent1").val(item.value);
			    $("#inputThreeAgentPos1").val(item.comment);
	    	}
        });

		/*
		$('#inputSelectAgentCambio.typeahead').typeahead({
            source : agenteslista,
            display : 'name',
            val : 'id'
        });*/
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


	      // finaliza inicializar calendario

	    $("#btnNewRequest").click(function(){
			$("#frmRequestInfo").show();	
			$('#calendar').fullCalendar('render');
		});

		// iniciar el cambio triangular
		$("#btnNewThreeRequest").click(function(){
			$("#frmThreeRequestInfo").show();	
			$('#calendar').fullCalendar('render');
		});

	    // selecciono el tipo de cambio, pasa a seleccionar el agente
	    $("#btnNext1").click(function(){
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

	     	$("#divStep1").hide();
	     	$("#divStep2").show();
	     });

	     // selecciono el agente pasa a la revision
	     $("#btnNext2").click(function(){
	     	$("#divStep2").hide();
	     	$("#divStep3").show();
	     	CargarAgenteMes();
	     });

	     $("#btnNext3").click(function(){
	     		
	     	$("#inputFinalAgent").val($("#inputSelectAgentCambio").val());
	     	$("#divFinalStep").show();
	     	$("#divStep3").hide();
	     	$("#divFinalStep").show();
	     
	     });

	     $("#btnNext4").click(function(){
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
	     			CargarInfoDayOffs();
	     			$("#infTargetDate").show();
	     			$("#divStep5").show();
	     			$("#divStep4").hide();
	     		}
	     		else if($("#inputFinalTipo").val()=='Cover')
	     		{
	     			$("#inputFinalAgent").val($("#inputSelectAgentCambio").val());
					CargarPosicionSolicitada();
			     	$("#divFinalStep").show();
	     			$("#divStep4").hide();
	     		}
	     		else
	     		{
	     			$("#inputFinalAgent").val($("#inputSelectAgentCambio").val());
					$("#divFinalStep").show();
	     			$("#divStep4").hide();
	     		}
		     }
	     })

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

	     // regresa a revisar el tipo
	     $("#btnCancel4").click(function(){
	     	$("#divStep3").show();
	     	$("#divStep4").hide();
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
			var lsolicitant = $("#inputSelectSolicitant").val();
			var laccepted = $("#inputAccepted").is(":checked")?1:0;
			
			var agent = {
				 fechacambiar : lfechacambiar,
				 shortname : lsolicitant,
				 posicioninicial : lposicioninicial,
				 posicionsolicitada : lposicionsolicitada,
				 tipocambio : ltipocambio,
				 agentecambio : lagentecambio,
				 fechatarget : ltargetdate,
				 accepted : laccepted
				 };

			
			var request = $.ajax({
				url: '<? echo base_url(); ?>timeswitch/expostswitchrequest',
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

		$("#btnExecuteRemoveRequest").click(function(){
			var lrequestid = $("#inputRequestToRemove").val();
			var lidagente = $("#inputIdAgenteToRemove").val();
			var agent = {
				idagente : lidagente,
				requestid : lrequestid
				};

			var request = $.ajax({
				url: '<? echo base_url(); ?>timeswitch/adminremoverequest',
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
					$('#dlgRemoveRequest').modal('hide');
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

		// eventos del proceso de request triangulado

		// oculta paso 1 y muestra el paso 2 (calendario)
		$("#btnThreeNext1").click(function(){
			$("#divThreeStep1").hide();
			$("#divThreeStep2").show();
			CargarThreeAgenteMes();
		});

		// oculta paso 2 y regresa a paso 1
		$("#btnCancel2").click(function(){
			$("#divThreeStep2").hide();
			$("#divThreeStep1").show();
		});

		// next paso 3 a 4
		$("#btnThreeNext3").click(function(){

			cargarThreeAgentesLastCoverFecha();

			$("#divThreeStep3").hide();
			$("#divThreeStep4").show();
		});

		// cancel de 3 a 2
		$("#btnThreeCancel3").click(function(){
			$("#divThreeStep3").hide();
			$("#divThreeStep2").show();
		});

		// next de 4 a final
		$("#btnThreeNext4").click(function(){

			// muestra la informacion

			$("#divThreeStep4").hide();
			$("#divThreeFinalStep").show();
		});

		// cancel de 4 a 3
		$("#btnThreeCancel4").click(function(){
			$("#divThreeStep4").hide();
			$("#divThreeStep3").show();
		});

		$("#btnThreeBackFinal").click(function(){
			$("#divThreeFinalStep").hide();
			$("#divThreeStep4").show();
		});

		$("#btnThreeSubmitRequest").click(function(){
			
			var lfechacambiar = $("#inputFinalDate").val();
			var lagent1 = $("#inputThreeAgent1").val();
			var lidagent1 = $("#inputThreeIdAgent1").val();
			var lpos1 = $("#inputThreeAgentPos1").val();
			var lagent2 = $("#inputThreeAgent2").val();
			var lidagent2 = $("#inputThreeIdAgent2").val();
			var lpos2 = $("#inputThreeAgentPos2").val();
			var lagent3 = $("#inputThreeAgent3").val();
			var lidagent3 = $("#inputThreeIdAgent3").val();
			var lpos3 = $("#inputThreeAgentPos3").val();
			var laccepted = $("#inputThreeAccepted").is(":checked")?1:0;
			
			var agent = {
				 fechacambiar : lfechacambiar,
				 agent1 : lagent1,
				 idagent1 : lidagent1,
				 posicion1 : lpos1,
				 agent2 : lagent2,
				 idagent2 : lidagent2,
				 posicion2 : lpos2,
				 agent3 : lagent3,
				 idagent3 : lidagent3,
				 posicion3 : lpos3,
				 accepted : laccepted
				 };

			
			var request = $.ajax({
				url: '<? echo base_url(); ?>timeswitch/expostthreerequest',
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



			
		$("#frmRequestInfo").hide();
		$("#divFinalStep").hide();
		$("#divStep2").hide();
		$("#divStep3").hide();
		$("#divStep4").hide();
		$("#divStep5").hide();

		return false;
	})

  	function goAuthorize(uniqueid)
  	{
  		console.log('Open authorize form');
  		window.location = '<? echo base_url(); ?>timeswitch/reviewRequestLead?uid=' + uniqueid;
  	}

  	function goAuthorizeTriangle(uniqueid)
  	{
  		console.log('Open Triangle authorize form');
  		window.location = '<? echo base_url(); ?>timeswitch/reviewTriangleRequestLead?uid=' + uniqueid;
  	}

  	function goRemove(requestid,idagente)
  	{
  		console.log('remove');
  		$("#inputRequestToRemove").val(requestid);
  		$("#inputIdAgenteToRemove").val(idagente);
  		$("#dlgRemoveRequest").modal('show');
  	}
  	/*
  	function selectFecha(fecha, posicion){

  		// evaluamos si aun tiene tiempo para seleccionar ese dia
  		var umbral = moment(fecha).hour(9).minutes(0).add(-1,'days');
  		/*
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
		//}
   	}*/

   	// carga las asignaciones del mes del agente solicitante
   	function CargarAgenteMes()
  	{

  		var lagentecambio = $("#inputSelectSolicitant").val();

  		var agent = {
			 shortname : lagentecambio
			 };
		console.log(agent);
		
		var request = $.ajax({
			url: '<? echo base_url(); ?>timeswitch/AsyncMonthlySchedule',
			type: 'POST',
			data: agent,
			beforeSend:function(){
				console.log('sending...');
				$('#myPleaseWait').modal('show');
			},
			success:function(result){
				//console.log(result);
				var listado = [];
				$.each(result,function(value,row){
					listado.push({
						title : row.posicion,
						start : row.fecha,
						url : 'javascript:selectFecha("' + row.fecha + '","' + row.posicion + '","' + row.workday + '");',
						className : 'info'
					});
				});

		  		/* initialize the calendar
			      -----------------------------------------------------------------*/
			      
			    var calendar2 =  $('#calendar').fullCalendar({
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
		    }
			
		});

  	}

    function CargarThreeAgenteMes()
  	{

  		var lagentecambio = $("#inputThreeSolicitant").val();

  		var agent = {
			 shortname : lagentecambio
			 };
		console.log(agent);
		
		var request = $.ajax({
			url: '<? echo base_url(); ?>timeswitch/AsyncMonthlySchedule',
			type: 'POST',
			data: agent,
			beforeSend:function(){
				console.log('sending...');
				$('#myPleaseWait').modal('show');
			},
			success:function(result){
				//console.log(result);
				var listado = [];
				$.each(result,function(value,row){
					listado.push({
						title : row.posicion,
						start : row.fecha,
						url : 'javascript:selectThreeFecha("' + row.fecha + '","' + row.posicion + '","' + row.workday + '");',
						className : 'info'
					});
				});

		  		/* initialize the calendar
			      -----------------------------------------------------------------*/
			      
			    var calendar2 =  $('#threecalendar').fullCalendar({
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
		    }
			
		});

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

	// el usuario selecciona una fecha y con base al tipo de cambio se carga la siguiente informacion
	function selectFecha(fecha, posicion, jornada){

  		// evaluamos si aun tiene tiempo para seleccionar ese dia
  		var umbral = moment(fecha).hour(9).minutes(0).add(-1,'days');

  		/*if(!moment().isBefore(umbral))
  		{
  			$("#lblInfo").text('Ya esta fuera de horario');
  		}
  		else
  		{*/
	  		//$("#inputSelectedPosition").val(posicion);
	  		//$("#inputSelectedDate").val(fecha);
	  		$("#inputFinalDate").val(fecha);
	  		$("#inputUserPosicion").val(posicion);
	  		$("#inputUserJornada").val(jornada);
	  		
	  		$("#inputInitialPosition").val(posicion);

	  		console.log($("#inputTipoCambio input:radio:checked").val());
	     	switch($("#inputTipoCambio input:radio:checked").val())
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
	  		$("#divStep4").show();
		    $("#divStep3").hide();
		//}
   	}

   	// el usuario del cambio de triangulacion selecciona la fecha de cambio
   	function selectThreeFecha(fecha, posicion, jornada){

  		// evaluamos si aun tiene tiempo para seleccionar ese dia
  		var umbral = moment(fecha).hour(9).minutes(0).add(-1,'days');

  		/*if(!moment().isBefore(umbral))
  		{
  			$("#lblInfo").text('Ya esta fuera de horario');
  		}
  		else
  		{*/
  			$("#inputThreeFechaCover").val(fecha);
	  		$("#inputFinalDate").val(fecha);
	  		$("#inputFinalPosition").val(posicion);
	  		$("#inputUserJornada").val(jornada);
	  		$("#inputThreeAgentPos1").val(posicion);

   			cargarThreeAgentesCoverFecha(fecha,posicion,jornada);
   			
   			
	  		//$("#divSelectedInfo").show();
	  		$("#divThreeStep3").show();
		    $("#divThreeStep2").hide();
		//}
   	}

   	function selectDayOffFecha(fecha, posicion)
   	{
   		// evaluamos si aun tiene tiempo para seleccionar ese dia
  		var umbral = moment(fecha).hour(9).minutes(0).add(-1,'days');
  		/*
  		if(false || !moment().isBefore(umbral))
  		{
  			alert('This date cannot be used.');
  		}
  		else
  		{*/
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
			    $("#divStep5").hide();
			}
		//}
   	}

   	function CargarPosicionSolicitada()
   	{
   		var lfechacambiar = $("#inputFinalDate").val();
  		var lagentecambio = $("#inputFinalAgent").val();

  		var agent = {
			 fechacambiar : lfechacambiar,
			 agente : lagentecambio
			 };
		var request = $.ajax({
			url: '<? echo base_url(); ?>timeswitch/consultarQuickSchedule',
			type: 'POST',
			data: agent,
			beforeSend:function(){
				console.log('sending...');
				$('#myPleaseWait').modal('show');
			},
			success:function(result){
				console.log(result);
				$("#inputFinalPosition").val(result.posicion);
				$('#myPleaseWait').modal('hide');
			}
		});
   	}

   	function CargarInfoDayOffs()
  	{

  		var lfechacambiar = $("#inputFinalDate").val();
  		var lagente = $("#inputSelectSolicitant").val();

  		var agent = {
			 fechacambiar : lfechacambiar,
			 agente : lagente
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

   	function cargarAgentesSwitchFecha()
  	{
  		var lfechacambiar = $("#inputFinalDate").val();
  		var lagentecambio = $("#inputSelectSolicitant").val();

  		var agent = {
			 fechacambiar : lfechacambiar,
			 agente : lagentecambio
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
		            val : 'idagente',
		            onSelect: function(item) {
		            	console.log(item);
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

   	function cargarAgentesCoverFecha()
  	{
  		var lfechacambiar = $("#inputFinalDate").val();
  		var luserposicion = $("#inputUserPosicion").val();
  		var luserjornada = $("#inputUserJornada").val();
  		var lagentecambio = $("#inputSelectSolicitant").val();

  		var agent = {
  			 agente : lagentecambio,
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

  	// carga las opciones para cubrir una triangulacion
  	function cargarThreeAgentesCoverFecha(fecha,posicion,jornada)
  	{

  		var lagentecambio = $("#inputThreeSolicitant").val();

  		var agent = {
  			 agente : lagentecambio,
			 fechacambiar : fecha,
			 userposicion : posicion,
			 userjornada : jornada
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

				$('#inputThreeSelectAgentCambio.typeahead').typeahead({
		            source : agenteslista,
		            display : 'name',
		            val : 'id',
		            onSelect: function(item) {
			        	$("#inputThreeFinalPosition").val(item.comment);
			        	$("#inputThreeAgent2").val(item.text);
			        	$("#inputThreeIdAgent2").val(item.value);
			        	$("#inputThreeAgentPos2").val(item.comment);
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

  	// carga la lista de los que pueden ser last agents en la triangulacion
  	function cargarThreeAgentesLastCoverFecha()
  	{
  		var lfechacambiar = $("#inputFinalDate").val();
  		var luserposicion = $("#inputUserPosicion").val();
  		var luserjornada = $("#inputUserJornada").val();

  		var lagentecambio = $("#inputThreeSolicitant").val();

  		var agent = {
  			 agente : lagentecambio,
			 fechacambiar : lfechacambiar,
			 userposicion : luserposicion,
			 userjornada : luserjornada
			 };
		console.log(agent);
		
		var request = $.ajax({
			url: '<? echo base_url(); ?>timeswitch/getagentslastcoverdate',
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

				$('#inputThreeSelectFinalAgent.typeahead').typeahead({
		            source : agenteslista,
		            display : 'name',
		            val : 'id',
		            onSelect: function(item) {
			        	$("#inputThreeSelectFinalAgent").val(item.name);
			        	$("#inputThreeAgent3").val(item.text);
			        	$("#inputThreeIdAgent3").val(item.value);
			        	$("#inputThreeAgentPos3").val(item.comment);
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
  		var lagentecambio = $("#inputIdAgenteCambio").val();

  		var agent = {
  			 agente : lagentecambio,
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
</script>