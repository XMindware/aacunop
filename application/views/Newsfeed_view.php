    <div class="container">
        <div class="panel-body">
            <div class="panel-heading">
            	<div class="row">
                <h2><span class="label label-primary">Newsfeed</span></h2>
                <button type="button" id="btnNewRow" class="btn btn-link">Add News Row</button>
                </div>
            </div>           
            <form id="frmData" method="post">
            	<input type="hidden" id="inputIdEmpresa" name="inputIdEmpresa" value="<? echo $idempresa; ?>"/>
            	<input type="hidden" id="inputIdOficina" name="inputIdOficina" value="<? echo $idoficina; ?>"/>
            	
            	<input type="hidden" id="inputUsuario" value="<? echo $idusuario; ?>" />
            	<input type="hidden" id="inputUniqueid" value="-1" />
                <fieldset>
                    <legend>Edit this news</legend>
                        <div class='row'>
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                    <label for="inputCode">Title</label>
                                    <input class="form-control" id="inputTitle" name="inputTitle" required="true" size="30" type="text" />
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-sm-10'>
                                <div class='form-group'>
                                	
                                    <label for="inputFullNews">Full Info</label>
                                    <textarea class="form-control" rows="4" id="inputFullNews"></textarea>
                                </div>
                            </div>
                        </div>
                       	<div class='row'>
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                    <label for="inputOrden">Author</label>
                                    <input class="form-control" id="inputAuthor" name="inputAuthor" required="true" size="30" type="text" />
                                </div>
                            </div>
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                    <label for="inputValidThru">Valid Thru</label>
                                    <input class="form-control" id="inputValidThru" name="inputValidThru" required="true" size="30" type="date" />
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
                                    <button type="button" id="btnDeleteRow" class="btn btn btn-warning">Delete</button>
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
                      	<th></th>
                        <th>Date</th>
                        <th>Title</th>
                      </tr>
                    </thead>
                    <?
						if(sizeof($rowslist)> 0){
							foreach($rowslist as $row)
							{
		
						?>
						<tbody>
						  <tr>
							<td><button type="button" onclick="loadRow(<? echo "'" . $row['idempresa'] . "','" . $row['idoficina'] . "','" . $row['uniqueid'] . "'"; ?>);" class="btn btn-link">View/Edit</button></td>
							<td><? echo $row['updated']; ?></td>
							<td><? echo $row['title']; ?></td>
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
			var luniqueid = $("#inputUniqueid").val();
			var lidempresa = $("#inputIdEmpresa").val();
			var lidoficina = $("#inputIdOficina").val();
			var ltitle = $("#inputTitle").val();
			var lfullnews = $("#inputFullNews").val();
			var lvalidthru = $("#inputValidThru").val();
			var lauthor = $("#inputAuthor").val();
			var lusuario = $("#inputUsuario").val();
					
			$("#frmData").hide();	
			var agent = {
						 uniqueid : luniqueid,
						 idempresa : lidempresa,
						 idoficina : lidoficina,
						 title : ltitle,
						 fullnews : lfullnews,
						 author : lauthor,
						 validthru : lvalidthru,
						 usuario : lusuario
						 };
			
			var request = $.ajax({
				url: 'newsfeed/postnews',
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
		
		$("#btnNewRow").click(function(){
			$("#inputTitle").val('');
			$("#inputFullNews").val('');
			$("#inputAuthor").val('');

			$("#inputValidThru").val(moment().format('YYYY-MM-DD'));				
			$("#frmData").show();
			$("#inputUniqueid").val(-1);	
		});
		
		$("#btnCancel").click(function(){
			$("#inputUniqueid").val('');
			$("#frmData").hide();	
		});
		
		$("#btnDeleteRow").click(function(){
			
			var luniqueid = $("#inputUniqueid").val();
			var lidempresa = $("#inputIdEmpresa").val();
			var lidoficina = $("#inputIdOficina").val();
			
			var rowData = { idempresa : lidempresa,
							idoficina : lidoficina,
							uniqueid : luniqueid };
			$.ajax({
				url: 'newsfeed/deletenewsrowid',
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

	function loadRow(idempresa, idoficina, uniqueid)
	{
		$("#frmData").show();	
		
		
		var rowData = { 
			idempresa : idempresa,
			idoficina : idoficina,
			uniqueid : uniqueid };
		$.ajax({
			url: 'newsfeed/viewfullnewsid',
			type: 'POST',
			data : rowData,
			beforeSend:function(){
				$('#myPleaseWait').modal('show');
				$(document).scrollTop();
			},
			success:function(data){
				console.log('loading data...');
				var position = data[0];
				$("#inputUniqueid").val(position.uniqueid);
				$("#inputTitle").val(position.title);
				$("#inputFullNews").val(position.fullnews);
				$("#inputAuthor").val(position.author);
				$("#inputValidThru").val(position.validthru);
				$('#myPleaseWait').modal('hide');
			}
		});
	}
</script>