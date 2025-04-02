    <div class="container">
        <div class="panel-body">
            <div class="panel-heading">
            	<div class="row">
                <h2><span class="label label-primary">Daily Positions on Current Station</span></h2>
                <button type="button" id="btnNewRow" class="btn btn-link">New Daily Position</button>
                </div>
            </div>           
            <form id="frmData">
            	<input type="hidden" id="inputIdEmpresa" name="inputIdEmpresa" value="<? echo $idempresa; ?>"/>
            	<input type="hidden" id="inputUniqueId" name="inputUniqueId" value="" />
            	<fieldset>
                    <legend>Position Information</legend>
                        <div class='row'>
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                    <label for="inputCode">Code</label>
                                    <input class="form-control" id="inputCode" name="inputCode" required="true" size="30" type="text" />
                                </div>
                            </div>
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                    <label for="inputDescription">Description</label>
                                    <input class="form-control" id="inputDescription" name="inputDescription" required="true" size="30" type="text" />
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                    <label for="inputStartTime">Valid From</label>
                                    <input class="form-control" id="inputStartDate" name="inputStartDate" required="true" size="30" type="date" />
                                </div>
                            </div>
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                	<label for="inputEndDate">Valid Thru</label>
                                	<input class="form-control" id="inputEndDate" name="inputEndDate" required="true" size="30" type="date" />
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                    <label for="inputStartTime">Start Time</label>
                                    <input class="form-control" id="inputStartTime" name="inputStartTime" required="true" size="30" type="time" />
                                </div>
                            </div>
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                    <label for="inputEndTime">End Time</label>
                                    <input class="form-control" id="inputEndTime" name="inputEndTime" required="true" size="30" type="time" />
                                </div>
                            </div>
                        </div>
                         <div class='row'>
                         	<div class='col-sm-4'>
                                <div class='form-group'>
                                    <label for="inputWorkday">Work day to be asssigned</label>
                                    <select id="inputWorkday" required="true" class="form-control">
                                    <?
										foreach($workdaylist as $workday)
										{
									
									?>
                                      <option value="<? echo $workday['code']; ?>"><? echo $workday['description']; ?></option>
                                      <?
										}
									?>
                                    </select>
                                </div>
                            </div>
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                    <label for="inputCando">Skill Required</label>
                                    <select id="inputCando" required="true" class="form-control">
                                    <?
										foreach($candolist as $cando)
										{
									
									?>
                                      <option value="<? echo $cando['code']; ?>"><? echo $cando['description']; ?></option>
                                      <?
										}
									?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-sm-6'>
                                <div class='form-group'>
                                    <button type="button" id="btnSubmitData" class="btn btn-success">Save</button>
                                    <button type="button" id="btnCancel" class="btn btn-default">Cancel</button>
                                </div>
                            </div>
                            <div class='col-sm-2'>
                                <div class='form-group'>
                                    <button type="button" id="btnDeleteRow" class="btn btn btn-warning">Release</button>
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
                      	<th>Code</th>
         				<th>Description</th>
                        <th>Schedule</th>
                        <th>Work day</th>
                        <th>Skill Req</th>
						<th>Type</th>
                      </tr>
                    </thead>
                    <?
						if(sizeof($positionlist)> 0){
							foreach($positionlist as $position)
							{
								$horainicio = intval($position['starttime']) - ($timezone * 3600);
								$hours = intval($horainicio / 3600) ;
								$minutes = (($horainicio / 3600) - $hours) * 60;
								$stime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
								
								$horafin = intval($position['endtime']) - ($timezone * 3600);
								$hours = intval($horafin / 3600);
								$minutes = (($horafin / 3600) - $hours) * 60;
								$etime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
								//echo (($position['endtime'] / 3600) - $hours)  * 60;
						?>
						<tbody>
						  <tr <?php if($position['type']=='e') { echo 'class="bg-warning"'; } else { echo ''; } ?>>
							<td>							
							<?
                        	if($isadmin == 1)
                            {
                                ?>
                                <button type="button" onclick="loadRow(<? echo "'" . $position['idempresa'] . "','" . $position['uniqueid'] . 
									"'"; ?>);" class="btn btn-link"><? echo $position['code']; ?></button>
                            
                               	<?
                            }
                            else
                            {
                                echo $position['code'];
                            }
                            ?>
							</td>
							<td><? echo $position['description']; ?></td>
    						<td><? echo $stime . ' - ' . $etime; ?></td>
    						<td><? echo $position['workday']; ?></td>
                            <td><? echo $position['cando']; ?></td>
							<td><? echo $position['type'] == 'e' ? 'Special' : 'Work'; ?></td>
						  </tr>
						  <? } ?>
                    </tbody>
                    <? } ?>
                  </table>
          	</div>
      	</div>
      </div>
    </div>
    <!-- End Horizontal Form -->
    
    
    <script type="text/javascript">
	$("#frmData").hide();
	$('#myPleaseWait').modal('hide');
	$(document).ready(function(){

		if(<? echo $isadmin; ?>)
		{
			$("#btnNewRow").show();
		}
		else
		{
			$("#btnNewRow").hide();
		}

		
		$("#btnSubmitData").click(function(){
			var offset = new Date().getTimezoneOffset();

			var luniqueid = $("#inputUniqueId").val();
			var lcode = $("#inputCode").val();
			var ldescription = $("#inputDescription").val();
			var lidempresa = $("#inputIdEmpresa").val();
			var horario = $("#inputStartTime").val();
			var hora 	= parseInt(horario.substr(0,2));
			var minuto 	= parseInt(horario.substr(3,2));
			var lstime	= (minuto * 60) + (hora * 3600) - (offset * 60);
				horario = $("#inputEndTime").val();
				hora 	= parseInt(horario.substr(0,2));
				minuto 	= parseInt(horario.substr(3,2));
			var letime	= (minuto * 60) + (hora * 3600) - (offset * 60);
			var lsdate = $("#inputStartDate").val();
			var ledate = $("#inputEndDate").val();
			var lworkday = $("#inputWorkday").val();
			var lcando = $("#inputCando").val();
					
			$("#frmData").hide();	
			var agent = {
						 idempresa : lidempresa,
						 uniqueid : luniqueid,
						 code : lcode,
						 description : ldescription,
						 stime : lstime,
						 etime : letime,
						 sdate : lsdate,
						 edate : ledate,
						 workday : lworkday,
						 cando : lcando
						 };
			
			var request = $.ajax({
				url: 'posiciones/postposition',
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
			
			request.fail(function( jqXHR, textStatus ) {
  			console.log( "Request failed: " + textStatus );
			});
			
		});
		
		$("#btnNewRow").click(function(){
			$("#frmData").show();	
			$("#inputUniqueId").val('-1');
		});
		
		$("#btnCancel").click(function(){
			$("#inputCode").val('');
			$("#frmData").hide();	
		});
		
		$("#btnDeleteRow").click(function(){
			
			var luniqueid = $("#inputUniqueId").val();
			var lidempresa = $("#inputIdEmpresa").val();
			
			var rowData = { idempresa : lidempresa,
							uniqueid : luniqueid };
			$.ajax({
				url: 'posiciones/deletepositionid',
				type: 'POST',
				data : rowData,
				beforeSend:function(){
					$('#myPleaseWait').modal('show');
					$(document).scrollTop();
				},
				success:function(data){
					$('#myPleaseWait').modal('hide');
					location.reload();
				}
			});
			$("#frmData").hide();	
		});
			
			
		return false;
	});

	function loadRow(idempresa, uniqueid)
	{
		$("#frmData").show();	
		
		var rowData = { idempresa : idempresa,
						uniqueid : uniqueid};
		$.ajax({
			url: 'posiciones/loadpositionid',
			type: 'POST',
			data : rowData,
			beforeSend:function(){
				$('#myPleaseWait').modal('show');
				$(document).scrollTop();
			},
			success:function(data){
				console.log('loading data...');
				var offset = new Date().getTimezoneOffset();
				
				var position = jQuery.parseJSON(data)[0];
				console.log('position uniqueid ' + position.uniqueid);
				$("#inputUniqueId").val(position.uniqueid);
				$("#inputCode").val(position.code);
				$("#inputDescription").val(position.description);
				$("#inputStartDate").val(position.startdate);
				$("#inputEndDate").val(position.enddate);

				position.starttime = parseInt(position.starttime) + parseInt(offset * 60);

				var hours = parseInt(position.starttime / 3600);
				var minut = Math.ceil(((position.starttime / 3600) - hours ) * 60);
				
				var stime = (hours<=9?('0' + hours) : hours) + ':' + (minut<=9?('0' + minut) : minut);
				
				position.endtime = parseInt(position.endtime) + parseInt(offset * 60);
				hours = parseInt(position.endtime / 3600);
				minut = Math.ceil(((position.endtime / 3600) - hours) * 60);
				var etime = (hours<=9?('0' + hours) : hours) + ':' + (minut<=9?('0' + minut) : minut);

				$("#inputStartTime").val(stime);
				
				$("#inputEndTime").val(etime);
				$("#inputIdEmpresa").val(position.idempresa);
				$("#inputCando").val(position.cando);
				$("#inputWorkday").val(position.workday);
				$("html, body").animate({ scrollTop: 0 }, "fast");
				$('#myPleaseWait').modal('hide');
			}
		});
	}
</script>