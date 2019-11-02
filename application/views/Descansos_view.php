    <div class="container">
        <div class="panel-body">
            <div class="panel-heading">
            	<div class="row">
                <h2><span class="label label-primary">Days Off on Current Station</span></h2>
                <button type="button" id="btnNewAgent" class="btn btn-link">New Agent</button>
                <input type="hidden" id="inputIdEmpresa" value="<? echo $idempresa; ?>" />
                <input type="hidden" id="inputIdOficina" value="<? echo $idoficina; ?>" />
                </div>
            </div>           
            <form id="frmAgentData" action="#">
            	
                <fieldset required="required">
                    <legend>Information</legend>
                        <div class='row'>
                            <div class='col-sm-6'>
                                <div class='form-group'>
                                    <label for="inputAgentId">Agents listed</label>
                                    <select id="inputAgentId"  name="inputAgentId" required="true" class="form-control">
                                    <?
										foreach($agentlist as $agent)
										{
									?>
                                      <option value="<? echo $agent['idagente']; ?>"><? echo $agent['shortname']; ?></option>
                                      <?
										}
									?>
                                    </select>
                                </div>
                            </div>
                            <div class='col-sm-6'>
                                <div class='form-group'>
                                    <label for="inputDaysOff">Days Off</label>
                                    <select id="inputDaysOff"  name="inputDaysOff" required="true" class="form-control">
                      				  <option value="1">Monday, Tuesday</option>
                                      <option value="2">Tuesday, Wednesday</option>
                                      <option value="3">Wednesday, Thursday</option>
                                      <option value="4">Thursday, Friday</option>
                                      <option value="5">Friday, Saturday</option>
                                      <option value="6">Saturday, Sunday</option>
                                      <option value="7">Sunday, Monday</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-sm-8'>
                                <div class='form-group'>
                                    <button type="button" id="btnSubmitAgent" class="btn btn-success">Save</button>
                                    <button type="button" id="btnCancel" class="btn btn-default">Cancel</button>
                                </div>
                            </div>
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                    <button type="button" id="btnDeleteAgent" class="btn btn btn-warning">Delete</button>
                                </div>
                            </div>
                        </div>
                    </fieldset>
            </form>
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
      </div>
    </div>
    <!-- End Horizontal Form -->
    
    
    <script type="text/javascript">
  
	$("#frmAgentData").hide();
	$('#myPleaseWait').modal('hide');
	$(document).ready(function(){
		
		$("#btnSubmitAgent").click(function(){
			
			var lagentid = $("#inputAgentId").val();
			var ldaysoff = $("#inputDaysOff").val();
			var lidempresa = $("#inputIdEmpresa").val();
			var lidoficina = $("#inputIdOficina").val();
			
			$("#frmAgentData").hide();	
			var agent = {
						 idempresa : lidempresa,
						 idoficina : lidoficina,
						 agenteid : lagentid,
						 diasdescanso : ldaysoff
						 };
			
			$.each(agent, function(index, value) {
				console.log(value);
			});
			
			var request = $.ajax({
				url: 'descansos/postagent',
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