  
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
                <input type="hidden" id="inputRequestId" value="<? echo $requestid; ?>" />
                </div>
            </div>    
            <div id="divFinalStep" >
            	 <fieldset required="required" style="text-align: center">
                    <legend>Shift Request Received. Please Review and Choose Action.</legend>
               		<div class="row">
	                    <div class='col-sm-6 col-xs-6'>
		                    <div class='form-group'>
		                       <label for="inputFechaIni">Date</label>
		                       <input class="form-control" id="inputFinalDate" name="inputFinalDate" size="30" type="text" value="<? echo $presentation['fecha']; ?>" readonly="true" />
		                    </div>
		                </div>
		                <div class='col-sm-6 col-xs-6'>
			                <div class='form-group'>
			                    <label for="inputFechaIni">Type</label>
			                       <input class="form-control" id="inputFinalTipo" name="inputFinalTipo" size="30" value="<? echo $presentation['tipocambio']; ?>" type="text" readonly="true" />
			                </div>
			            </div> 
			        </div>
			        <div class="row">
			            <div class='col-sm-3 col-xs-3'>
			                <div class='form-group'>
			                    <label for="inputSolicitant">Solicitant</label>
			                       <input class="form-control" id="inputAgent1" name="inputAgent1" size="20" value="<? echo $presentation['agent1']; ?>" type="text" readonly="true" />
			                       <input class="form-control" id="inputIdAgent1" name="inputIdAgent1" value="<? echo $presentation['idagent1']; ?>" size="20" type="hidden" />
			                </div>
			            </div> 
			            <div class='col-sm-3 col-xs-3'>
			                <div class='form-group'>
			                    <label for="inputSolicitant">Middle</label>
			                       <input class="form-control" id="inputAgent2" name="inputAgent2" size="20" value="<? echo $presentation['agent2']; ?>" type="text" readonly="true" />
			                       <input class="form-control" id="inputIdAgent2" name="inputIdAgent2" value="<? echo $presentation['idagent2']; ?>" size="20" type="hidden" />
			                </div>
			            </div> 
			            <div class='col-sm-3 col-xs-3'>
			                <div class='form-group'>
			                    <label for="inputSolicitant">Day Off Agent</label>
			                       <input class="form-control" id="inputIdAgent3" name="inputIdAgent3" size="20" value="<? echo $presentation['agent3']; ?>" type="text" readonly="true" />
			                       <input class="form-control" id="inputIdAgent3" name="inputIdAgent3" value="<? echo $presentation['idagent3']; ?>" size="20" type="hidden" />
			                </div>
			            </div> 
			           
			        </div>
			        <div class="row">
			            <div class='col-sm-3 col-xs-3'>
			                <div class='form-group'>
			                    <label for="inputPosition1">Position</label>
			                       <input class="form-control" id="inputPosition1" name="inputPosition1" size="20" value="<? echo $presentation['position1']; ?>" type="text" readonly="true" />
			                       
			                </div>
			            </div> 
			            <div class='col-sm-3 col-xs-3'>
			                <div class='form-group'>
			                    <label for="inputPosition2">Position</label>
			                       <input class="form-control" id="inputPosition2" name="inputPosition2" size="20" value="<? echo $presentation['position2']; ?>" type="text" readonly="true" />
			                       
			                </div>
			            </div> 
			            <div class='col-sm-3 col-xs-3'>
			                <div class='form-group'>
			                    <label for="inputPosition3">Position</label>
			                       <input class="form-control" id="inputPosition3" name="inputPosition3" size="20" value="<? echo $presentation['position3']; ?>" type="text" readonly="true" />
			                       
			                </div>
			            </div> 
			            
			        </div>
			        <div class="row">
	                    <div class='col-sm-12'>
	                        <div class='form-group'>	
	                            <button type="button" id="btnAuthorizeRequest" class="btn btn-success">Authorize Request</button>
	                            <button type="button" id="btnDeclineRequest" class="btn btn-default">Decline</button>
	                        </div>
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

		
		$("#btnAuthorizeRequest").click(function(){
			if(<?
    			if($presentacion['puesto1'] == 'LEAD')
    				if($coordinador == 'NO')
    					echo 1;
    				else
    					echo 0;
    			else
    				echo 0;
        		?>)
			{
				alert('Only the Station Manager can Authorize this Request');
				return;
			}
			
			var lrequestid = $("#inputRequestId").val();
			
			var agent = {
				 requestid : lrequestid
				 };
	
			
			var request = $.ajax({
				url: '<? echo base_url();?>timeswitch/authorizethreerequest',
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
		
			
		return false;
	})

  
	function executeDecline()
	{
		var lrequestid = $("#inputRequestId").val();
		var lreason    = $("#inputDeclineReason").val();
			
		var info = {
			 requestid : lrequestid,
			 reason : lreason
		};

		
		var request = $.ajax({
			url: '<? echo base_url();?>timeswitch/declinethreerequest',
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