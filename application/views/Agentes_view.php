    <div class="container">
        <div class="panel-body">
            <div class="panel-heading">
            	<div class="row">
                <h2><span class="label label-primary">Agents on Current Station</span></h2>
                <button type="button" id="btnNewAgent" class="btn btn-link">New Agent</button>
                </div>
            </div>           
            <form id="frmAgentData">
                <fieldset>
                    <legend>Agent Information</legend>
                        <div class='row'>
                            <div class='col-sm-4'>    
                                <div class='form-group'>
                                    <label for="inputAgentId">Agent ID</label>
                                    <input class="form-control" id="inputAgentId" name="inputAgentId" size="30" type="text" />
                                    <input type="hidden" id="inputIdEmpresa"  name="inputIdEmpresa" value="<? echo $idempresa; ?>" />
                                    <input type="hidden" id="inputIdOficina"  name="inputIdOficina" value="<? echo $idoficina; ?>" />
                                    <input type="hidden" id="inputUniqueId"   name="inputUniqueId" />
                                </div>
                            </div>
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                    <label for="inputFirstName">First name</label>
                                    <input class="form-control" id="inputFirstName" name="inputFirstName" required="true" size="30" type="text" />
                                </div>
                            </div>
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                    <label for="inputLastName">Last name</label>
                                    <input class="form-control" id="inputLastName" name="inputLastName" required="true" size="30" type="text" />
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                    <label for="inputShortName">Short name</label>
                                    <input class="form-control" id="inputShortName" name="inputShortName" required="true" size="30" type="text" />
                                </div>
                            </div>
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                    <label for="inputEmail">Email</label>
                                    <input class="form-control required" id="inputEmail" name="inputEmail" required="true" size="30" type="text" />
                                </div>
                            </div>
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                    <label for="inputJoinDate">Join Date</label>
                                    <input class="form-control required" id="inputJoinDate" name="inputJoinDate" required="true" size="30" type="date" />
                                </div>
                            </div>
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                    <label for="inputJoinDate">Phone Number</label>
                                    <input class="form-control required" id="inputPhone" name="inputPhone" required="true" size="30" type="tel" />
                                </div>
                            </div>
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                    <label for="inputBirthday">Date of birth</label>
                                    <input class="form-control required" id="inputBirthday" name="inputBirthday" required="true" size="30" type="date" />
                                </div>
                            </div>
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                    <label for="inputPosition">Position</label>
                                    <select id="inputPosition" required="true" class="form-control">
                                    <?
                                        foreach($puestos as $puesto)
                                        {
                                    ?>
                                      <option><? echo $puesto['code']; ?></option>
                                      <?
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                    <label for="inputHours">Work Day</label>
                                    <select id="inputHours" required="true" class="form-control">
                                    <?
										foreach($jornadas as $jornada)
										{
									
									?>
                                      <option value="<? echo $jornada['code']; ?>"><? echo $jornada['description']; ?></option>
                                      <?
										}
									?>
                                    </select>
                                    <button type="button" class="btn-link btn" id="btnUpdateAgentAssignments">Update Assignments</button>
                                </div>
                            </div>
                        	<div class='col-sm-4'>
                                <div class='form-group'>
                                    <label for="inputDaysOff">Days Off</label>
                                    <select id="inputDaysOff" id="inputDaysOff" name="inputDaysOff" required="true" class="form-control">
                      				  <option value="0">Monday, Tuesday</option>
                                      <option value="1">Tuesday, Wednesday</option>
                                      <option value="2">Wednesday, Thursday</option>
                                      <option value="3">Thursday, Friday</option>
                                      <option value="4">Friday, Saturday</option>
                                      <option value="5">Saturday, Sunday</option>
                                      <option value="6">Sunday, Monday</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-sm-6'>
                                <div class='form-group'>
                                    <label for="inputShortName">Skills</label>
                                    <select id="inputMySkills" multiple class="form-control">
                                    </select>
                                </div>
                            </div>
                            <div class='col-sm-2'>
                                <div class='form-group'>
                                	<label for="inputSelectSkill">Available</label>
                                	<select id="inputSelectSkill" required="true" class="form-control">
                                    <?
										foreach($candos as $cando)
										{
									?>
                                      <option value="<? echo $cando['code']; ?>"><? echo $cando['description']; ?></option>
                                      <?
										}
									?>
                                    </select>
                                    <button type="button" id="btnAddSkill" class="btn btn-success">Add</button>
                                    <button type="button" id="btnRemoveSkill" class="btn btn-default">Remove</button>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                    <button type="button" id="btnSubmitAgent" class="btn btn-success">Save</button>
                                    <button type="button" id="btnCancel" class="btn btn-default">Cancel</button>
                                </div>
                            </div>
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                    <button type="button" id="btnDeleteAgent" class="btn btn btn-warning">Delete Agent</button>
                                    <button type="button" id="btnNotificarAcceso" class="btn btn btn-info">Notificar Acceso</button>
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
                        <th>Full Name</th>
                        <th>Position</th>
                        <th>Join Date</th>
                        <th>Work day</th>
                        <th>Phone</th>
                        <th>Days Off</th>
                        <th>Skills</th>
                      </tr>
                    </thead>
                    <?
						foreach($userlist as $agentdata)
						{
							$agent = $agentdata[0];
							$candos = $agentdata[1];
							$ingreso = strtotime($agent['ingreso']);
					?>
                    <tbody>
                      <tr>
                        <td>
                        	<?
                        	if($isadmin == '1')
                            {
                                ?>
                                <button type="button" onclick="loadAgent('<? echo $agent['idagente'] . "'," . $agent['idempresa'] . ',' . $agent['idoficina'] . ',' . $agent['uniqueid']; ?>);" class="btn btn-link"><? echo $agent['idagente']; ?></button>
                               	<?
                            }
                            else
                            {
                                echo $agent['idagente'];
                            }?>
                        </td>
                 	
                        <td><? echo $agent['nombre'] . ' ' . $agent['apellidos']; ?></td>
                        <td><? echo $agent['puesto']; ?></td>
                        <td><? echo date('M jS, Y', $ingreso); ?></td>
                        <td><? echo $agent['jornada']; ?></td>
                        <td><? echo $agent['telefono']; ?></td>
                        <td><? 

                        	switch($agent['dayoff1'])
                        	{
                        		case 0:
                        			echo 'MON-TUE'; break;
                        		case 1:
                        			echo 'TUE-WED'; break;
                        		case 2:
                        			echo 'WED-THU'; break;
                        		case 3:
                        			echo 'THU-FRI'; break;
                        		case 4:
                        			echo 'FRI-SAT'; break;
                        		case 5:
                        			echo 'SAT-SUN'; break;
                        		case 6:
                        			echo 'SUN-MON';
                        	}
                        	 ?></td>
                        <td><? 
								$list = '';
								foreach($candos as $cando)
								{
									$list .= $cando['code'] . ' ';
								}
								echo $list;
							?>
                        </td>
                      </tr>
                      <? } ?>
                    </tbody>
                  </table>
          	</div>
      	</div>
      </div>
    </div>
    <!-- End Horizontal Form -->
    <!-- Modal -->
    <div id="modalUpdateAssignments" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            
            <h2><span class="label label-primary" id="pFlightInfo">Update Agent Assignments</span></h2>
          </div>
          <div class="modal-body">
            <center>
                <h5>Would you like to update the current assignments for the current user based on the new workday?
            </center>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" id="btnUpdateAssignments" data-dismiss="modal">Update</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
        </div>

      </div>
    </div>
    <!-- End modal -->
    
    <script type="text/javascript">
 
	$("#frmAgentData").hide();
	$('#myPleaseWait').modal('hide');
	$(document).ready(function(){

		$("#btnUpdateAgentAssignments").click(function(){

			$("#modalUpdateAssignments").modal('show');
		});

		$("#btnUpdateAssignments").click(function(){
			var lidempresa = $("#inputIdEmpresa").val();
			var lidoficina = $("#inputIdOficina").val();
			var luniqueid = $("#inputUniqueId").val();
			var lagentid = $("#inputAgentId").val();
			
			$("#frmAgentData").hide();	
			var agent = {
				 idempresa : lidempresa,
				 idoficina : lidoficina,
				 agenteid : lagentid,
				 uniqueid : luniqueid,
				 
				 };
			/*
			$.each(agent, function(index, value) {
				console.log(value);
			});
			*/
			var request = $.ajax({
				url: 'agentes/updateagentassignments',
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
					//location.reload();
				}, 
				error:function(exception){console.log(exception);}
				
			});
			
		})
		
		$("#btnSubmitAgent").click(function(){
			
			var lidempresa = $("#inputIdEmpresa").val();
			var lidoficina = $("#inputIdOficina").val();
			var luniqueid = $("#inputUniqueId").val();
			var lapellidos = $("#inputLastName").val();
			var lagentid = $("#inputAgentId").val();
			var lapellidos = $("#inputLastName").val();
			var lprimernombre = $("#inputFirstName").val();
			var lshortname = $("#inputShortName").val();
			var lingreso = $("#inputJoinDate").val();
			var lemail = $("#inputEmail").val();
			var ltelefono = $("#inputPhone").val();
            var lbirthday = $("#inputBirthday").val();
			var ldayoff = $("#inputDaysOff").val();
			var lpuesto = $("#inputPosition").val();
			var ljornada = $("#inputHours").val();
			$("#frmAgentData").hide();	
			var agent = {
						 idempresa : lidempresa,
						 idoficina : lidoficina,
						 agenteid : lagentid,
						 uniqueid : luniqueid,
						 apellidos : lapellidos,
						 primernombre : lprimernombre,
						 ingreso : lingreso,
						 shortname : lshortname,
						 email : lemail,
						 telefono : ltelefono,
                         birthday : lbirthday,
						 position : lpuesto,
						 jornada : ljornada,
						 dayoff : ldayoff
						 };
			/*
			$.each(agent, function(index, value) {
				console.log(value);
			});
			*/
			var request = $.ajax({
				url: 'agentes/postagent',
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
			
		});

    	$("#btnDeleteAgent").click(function(){
			
			var lidempresa = $("#inputIdEmpresa").val();
			var lidoficina = $("#inputIdOficina").val();
			var luniqueid = $("#inputUniqueId").val();
			
			$("#frmAgentData").hide();	
			var agent = {
						 idempresa : lidempresa,
						 idoficina : lidoficina,
						 uniqueid : luniqueid,
						};
			var request = $.ajax({
				url: 'agentes/releaseagent',
				type: 'POST',
				data: agent,
				beforeSend:function(){
					console.log('sending...');
					$('#myPleaseWait').modal('show');
				},
				success:function(result){
					location.reload();
					console.log('sent!');
					console.log(result);
					$('#myPleaseWait').modal('hide'); 
					
				}, 
				error:function(exception){console.log(exception);}
				
			});
			
		});
		
		$("#btnNewAgent").click(function(){
			$("#frmAgentData").show();	
			$("html, body").animate({ scrollTop: 0 }, "fast");
			$("#inputUniqueId").val(-1);
			$("#inputAgentId").focus();
		});
		
		$("#btnCancel").click(function(){
			$("#inputAgentId").val('');
			$("#inputLastName").val('');
			$("#inputFirstName").val('');
			$("#inputShortName").val('');
			$("#inputJoinDate").val('');
			$("#inputEmail").val('');
			$("#inputPhone").val('');
			$("#inputPosition").val('');
			$("#inputHours").val('');
			$("#frmAgentData").hide();	
		});
			
			
		return false;
	});
	
	$("#btnAddSkill").click(function(){
		var lskill = $("#inputSelectSkill").val();
		var lidagente = $("#inputUniqueId").val();
		
		var agentData = { idagente : lidagente,
						  idcando : lskill
						 };
		$.ajax({
			url: 'cando/addskillagente',
			type: 'POST',
			data : agentData,
			beforeSend:function(){
				$('#myPleaseWait').modal('show');
				$(document).scrollTop();
			},
			success:function(data){
				console.log('skills added');
				//location.reload();	
				LoadAgentSkills();			
			},
				error:function(exception){console.log(exception);}
				
			});
			
	});
                          

	$("#btnRemoveSkill").click(function(){
		
		var lidempresa = $("#inputIdEmpresa").val();
		var lidoficina = $("#inputIdOficina").val();
		var lskill = $("#inputMySkills option:selected").val();
		var lidagente = $("#inputUniqueId").val();
		console.log(lskill);
		var agentData = { 
						  idempresa : lidempresa,
						  idoficina : lidoficina,
						  idagente : lidagente,
						  idcando : lskill
						 };
		$.ajax({
			url: 'cando/removeskillagente',
			type: 'POST',
			data : agentData,
			beforeSend:function(){
				$('#myPleaseWait').modal('show'); 
				$(document).scrollTop();
			},
			success:function(data){
				console.log('skills removed');
				LoadAgentSkills();				
			},
				error:function(exception){console.log(exception);}
				
			});
	});

	$("#btnNotificarAcceso").click(function(){
		alert('Agent will be emailed to enable access for view daily schedule');
		var lidagente = $("#inputUniqueId").val();
		var lidempresa = $("#inputIdEmpresa").val();
		var lidoficina = $("#inputIdOficina").val();


		var shortname = $("#inputShortName").val();
//		window.open('https://apps.mindware.com.mx/agentes/getaccess?e=' + shortname + '&u=' + lidagente, '_blank');

		var agentData = { 
				uniqueid : lidagente,
				idempresa : lidempresa,
				idoficina : lidoficina};
		$.ajax({
			url: 'agentes/notifygrantaccess',
			type: 'POST',
			data : agentData,
			beforeSend:function(){
				$('#myPleaseWait').modal('show');
				$(document).scrollTop();
			},
			success:function(data){
				
				$('#myPleaseWait').modal('hide'); 
							
			},
				error:function(exception){console.log(exception);}
				
			});
			
	});
	
	function LoadAgentSkills(){
		var lidagente = $("#inputUniqueId").val();
		
		var agentData = { idagente : lidagente};
		$.ajax({
			url: 'cando/loadskillsagente',
			type: 'POST',
			data : agentData,
			beforeSend:function(){
				$('#myPleaseWait').modal('show');
				$(document).scrollTop();
			},
			success:function(data){
				console.log('skills loaded');
				var skills = jQuery.parseJSON(data)
				$("#inputMySkills").empty()
				if(skills!=null)
				for(var i=0;i<skills.length;i++){
					var skill = skills[i];
					$("#inputMySkills").append($("<option value='" + skill.code + "'></option>").html(skill.description));
				}
				$('#myPleaseWait').modal('hide'); 
				//$("#inputMySkills").append($("<option></option>").val(1).html("One"));						
			},
				error:function(exception){console.log(exception);}
				
			});
	};



	function loadAgent(idagente, idempresa, idoficina, uniqueid)
	{

		$("#frmAgentData").show();	
		
		
		var agentData = { agenteid : idagente,
						  idempresa : idempresa,
						  idoficina : idoficina,
						  uniqueid : uniqueid
						 };
						 
		$.ajax({
			url: 'agentes/loadagentuniqueid',
			type: 'POST',
			data : agentData,
			beforeSend:function(){
				$('#myPleaseWait').modal('show'); 
				$(document).scrollTop();
			},
			success:function(data){
				console.log('loading data...');
				var agent = data[0];
				console.log('uniqueid ' + agent.uniqueid);
				$("#inputAgentId").val(idagente);
				$("#inputLastName").val(agent.apellidos);
				$("#inputFirstName").val(agent.nombre);
				$("#inputShortName").val(agent.shortname);
				$("#inputJoinDate").val(agent.ingreso);
				$("#inputEmail").val(agent.email);
				$("#inputPhone").val(agent.telefono);
                $("#inputBirthday").val(agent.birthday);
				$("#inputPosition").val(agent.puesto);
				$("#inputHours").val(agent.jornada);
				$("#inputUniqueId").val(agent.uniqueid);
				
				$("html, body").animate({ scrollTop: 0 }, "fast");

				LoadAgentSkills();
				
				$('#myPleaseWait').modal('hide');
			}
		});
	}

/*
	$(document).bind('keydown', 'Ctrl+n', function(){
		$("html, body").animate({ scrollTop: 0 }, "fast");
		$("#frmAgentData").show();	
		$("#inputAgentId").focus();
	});
*/
	
</script>