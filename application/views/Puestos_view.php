    <div class="container">
        <div class="panel-body">
            <div class="panel-heading">
            	<div class="row">
                <h2><span class="label label-primary">Job Positions on Current Station</span></h2>
                <button type="button" id="btnNewRow" class="btn btn-link">New Job Position</button>
                </div>
            </div>           
            <form id="frmData">
            	<input type="hidden" id="inputIdEmpresa" name="inputIdEmpresa" value="<? echo $idempresa; ?>"/>            	
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
                            <div class='col-sm-8'>
                                <div class='form-group'>
                                    <button type="button" id="btnSubmitData" class="btn btn-success">Save</button>
                                    <button type="button" id="btnCancel" class="btn btn-default">Cancel</button>
                                </div>
                            </div>
                            <div class='col-sm-4'>
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
                      </tr>
                    </thead>
                    <?
						if(sizeof($positionlist)> 0){
							foreach($positionlist as $position)
							{
								
						?>
						<tbody>
						  <tr>
							<td><button type="button" onclick="loadRow(<? echo "'" . $position['idempresa'] . "','" . $position['code'] . "'"; ?>);" class="btn btn-link"><? echo $position['code']; ?></button></td>
							<td><? echo $position['description']; ?></td>
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
		
		$("#btnSubmitData").click(function(){
			var lcode = $("#inputCode").val();
			var ldescription = $("#inputDescription").val();
			var lidempresa = $("#inputIdEmpresa").val();
					
			$("#frmData").hide();	
			var agent = {
						 idempresa : lidempresa,
						 code : lcode,
						 description : ldescription
						 };
			
			var request = $.ajax({
				url: 'puestos/postposition',
				type: 'POST',
				data: agent,
				beforeSend:function(){
					console.log('sending...');
					$('#myPleaseWait').modal('show');
				},
				success:function(result){
					//location.reload();
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
			$("#inputIdEmpresa").val(position.idempresa);
		});
		
		$("#btnCancel").click(function(){
			$("#inputCode").val('');
			$("#frmData").hide();	
		});
		
		$("#btnDeleteRow").click(function(){
			
			var lcode = $("#inputCode").val();
			var lidempresa = $("#inputIdEmpresa").val();
			
			var rowData = { idempresa : lidempresa,
							code : lcode };
			$.ajax({
				url: 'puestos/deletepositionid',
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

	function loadRow(idempresa, code)
	{
		$("#frmData").show();	
		
		
		var rowData = { idempresa : idempresa,
						code : code };
		$.ajax({
			url: 'puestos/loadpositionid',
			type: 'POST',
			data : rowData,
			beforeSend:function(){
				$('#myPleaseWait').modal('show');
				$(document).scrollTop();
			},
			success:function(data){
				console.log('loading data...');
				var position = jQuery.parseJSON(data)[0];
				console.log('position code ' + position.code);
				$("#inputCode").val(position.code);
				$("#inputDescription").val(position.description);
				$("#inputIdEmpresa").val(position.idempresa);
				$('#myPleaseWait').modal('hide');
			}
		});
	}
</script>