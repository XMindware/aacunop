    <div class="container">
        <div class="panel-body">
            <div class="panel-heading">
            	<div class="row">
                <h2><span class="label label-primary">Work days on Current Station</span></h2>
                <button type="button" id="btnNewRow" class="btn btn-link">New Work day</button>
                </div>
            </div>           
            <form id="frmData" >
            	<input type="hidden" id="inputIdEmpresa" name="inputIdEmpresa" value="<? echo $idempresa; ?>"/>
            	
                <fieldset>
                    <legend>Work day Information</legend>
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
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                    <label for="inputHours">Hours</label>
                                    <input class="form-control" id="inputHours" name="inputHours" required="true" size="30" type="number" />
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
                    <table id="tblInfo" class="table table-condensed">
                    
                  	</table>
                </div>
          	</div>
      	</div>
      </div>
    </div>
    <!-- End Horizontal Form -->
    
    
    <script type="text/javascript">
	$("#frmData").hide();
	$('#myPleaseWait').modal('hide');
	$(document).ready(function(){
		
		RefreshList();

		$("#btnSubmitData").click(function(){
			var lcode = $("#inputCode").val();
			var ldescription = $("#inputDescription").val();
			var lidempresa = $("#inputIdEmpresa").val();
			var lhours = $("#inputHours").val();
					
			$("#frmData").hide();	
			var agent = {
						 idempresa : lidempresa,
						 code : lcode,
						 description : ldescription,
						 hours : lhours
						 };
			
			var request = $.ajax({
				url: 'jornadas/postworkday',
				type: 'POST',
				data: agent,
				beforeSend:function(){
					console.log('sending...');
					$('#myPleaseWait').modal('show');
				},
				success:function(result){
					//location.reload();
					console.log('sent!');
					RefreshList();
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
				url: 'jornadas/deleteworkdayid',
				type: 'POST',
				data : rowData,
				beforeSend:function(){
					$('#myPleaseWait').modal('show');
					$(document).scrollTop();
				},
				success:function(data){
					RefreshList();
					$('#myPleaseWait').modal('hide');
				}
			});
			$("#frmData").hide();	
		});
			
			
		return false;
	});

	function RefreshList()
    {
    	var lidempresa = $("#inputIdEmpresa").val();

	    var fields = {
	        idempresa : lidempresa
	    }
	    var request = $.ajax({
	        url: 'jornadas/asyncloadinfo',
	        type: 'POST',
	        data: fields,
	        beforeSend:function(){
	            console.log('sending...');
	            $('#myPleaseWait').modal('show');
	        },
	        success:function(result){
	            
	            console.log('sent!');
	            //console.log(result);
	            $('#tblInfo tr').remove();
	           var html = '<thead>' + 
	                     '<tr>' +
	                      	'<th>Code</th>' + 
	         				'<th>Description</th>' +
	                        '<th>Hours</th>' +
	                      '</tr>' +
	                    '</thead>' +
	                    '<tbody>';
	            $.each(result,function(thisrow,row){

	                html += '<tr>' +
                           '<td><button type="button" onclick="loadRow(' + row['idempresa'] + ',\'' + row['code']  + '\');" class="btn btn-link">' + row['code'] + '</button></td>' + 
							'<td>' + row['description']  + '</td>' + 
							'<td>' + row['hours'] + '</td>' +
							'</tr>' ;
	            });

	            html +='</tbody>';
	           
	            $("#tblInfo").append(html);
	            $('#myPleaseWait').modal('hide');
	        }, 
	        error:function(exception){console.log(exception);}
	        
	    });
    }

	function loadRow(idempresa, code)
	{
		$("#frmData").show();	
		
		
		var rowData = { idempresa : idempresa,
						code : code };
		$.ajax({
			url: 'jornadas/loadworkdayid',
			type: 'POST',
			data : rowData,
			beforeSend:function(){
				$('#myPleaseWait').modal('show');
				$(document).scrollTop();
			},
			success:function(data){
				console.log('loading data...');
				var position = jQuery.parseJSON(data)[0];
				//console.log('position code ' + position.code);
				$("#inputCode").val(position.code);
				$("#inputDescription").val(position.description);
				$("#inputHours").val(position.hours);
				$('#myPleaseWait').modal('hide');
			}
		});
	}
</script>