    <div class="container">
        <div class="panel-body">
            <div class="panel-heading">
            	<div class="row">
                <h2><span class="label label-primary">Agent Comments</span></h2>
                </div>
            </div>           
            
            <div class="row">
            	<div class="col-md-12">
                    <table class="table table-condensed">
                    <thead>
                      <tr>
                      	<th>Date</th>
                      	<th>From</th>
                        <th>To</th>
                        <th>Title</th>
                        <th>Comment</th>
                      </tr>
                    </thead>
                    <?
						if(sizeof($rowslist)> 0){
							foreach($rowslist as $row)
							{
		
						?>
						<tbody>
						  <tr>
						  	<td class='text-nowrap'><? echo date('m/d/Y',strtotime($row['updated'])); ?></td>
						  	<td class='text-nowrap'><? echo $row['usuario']; ?></td>
							<td class='text-nowrap'><? echo $row['shortname']; ?></td>
							<td><? echo $row['title']; ?></td>
							<td><? echo $row['comment']; ?></td>
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