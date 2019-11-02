<!-- Horizontal Form -->
     <div class="form-group">
        <div class="panel-heading">
            	<div class="row">
                <h2><span class="label label-primary">Current Setup</span></h2>
                </div>
            </div>   
        <!-- Enviar mails de acceso a todos -->
         <div class="panel-body">
            <form id="frmSendAccessRequest">
            	<input type="hidden" id="inputIdEmpresa"  name="inputIdEmpresa" value="<? echo $idempresa; ?>" />
                <input type="hidden" id="inputIdOficina"  name="inputIdOficina" value="<? echo $idoficina; ?>" />
                </center>
                <fieldset>
                    <legend>Send Access Mail</legend>              
                        <div class='row'>
                            <div class='col-sm-12'>
                                <div class='form-group'>
                                	<label for="btnLaunchMails">Send notification mail to all Station agents</label>
                                    <button type="button" id="btnLaunchMails" class="btn btn-success">with no access to Web CUNOP</button>
                                	<button type="button" id="btnLaunchMailsToAll" class="btn btn-success">All Agents</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        	 <div class='col-sm-6'>
                                <div class='form-group'>
                                	<ul id="liResult">
                                	</ul>
                                </div>
                            </div>
                          </div>
                    </fieldset>
            </form>
           
        </div>
        <!-- Configurar la cuenta -->
        <div class="panel-body">
            <form id="frmAgentData">
                </center>
                <fieldset>
                    <legend>Setup</legend>              
                        <div class='row'>
                            <div class='col-sm-6'>
                                <div class='form-group'>
                                    <label for="inputCompany">Company</label>
                                    <select id="inputCompany" required="true" class="form-control">
                                    <?
										foreach($empresas as $empresa)
										{
									
									?>
                                      <option value="<? echo $empresa['idempresa']; ?>"><? echo $empresa['nombre']; ?></option>
                                      <?
										}
									?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        	 <div class='col-sm-6'>
                                <div class='form-group'>
                                    <label for="inputStation">Station</label>
                                    <select id="inputStation" name="inputStation" required="true" class="form-control">
                                   
                                    </select>
                                </div>
                            </div>
                          </div>
                        <div class='row'>
                            <div class='col-sm-12'>
                                <div class='form-group'>
                                    <button type="submit" id="btnSubmitAgent" class="btn btn-success">Save</button>
                                    <button type="button" id="btnCancel" class="btn btn-default">Cancel</button>
                                </div>
                            </div>
                            
                        </div>
                    </fieldset>
            </form>
           
        </div>
    </div>
    
    
     <script type="text/javascript">

	$(document).ready(function(){
		
		LoadConfigData();
		
		$('#inputCompany').on('change', function() {
		  alert( this.value ); // or $(this).val()
		});
		
		$("#btnNewAgent").click(function(){
			$("#frmAgentData").show();	
		});
		
		$("#btnCancel").click(function(){
			$("#inputAgentId").val('');
			$("#inputLastName").val('');
			$("#inputFirstName").val('');
			$("#inputShortName").val('');
			$("#inputJoinDate").val('');
			$("#inputEmail").val('');
			$("#inputPosition").val('');
			$("#inputHours").val('');
			$("#frmAgentData").hide();	
		});

		$("#btnLaunchMails").click(function(){
			var lidempresa = $("#inputIdEmpresa").val();
			var lidoficina = $("#inputIdOficina").val();
			console.log('loading data ' + lidempresa);
			var data = { 
				idempresa : lidempresa,
				idoficina : lidoficina,
				all : 0
			};
			
			var request = $.ajax({
				url: 'config/senduserrequests',
				type: 'POST',
				data: data,
				beforeSend:function(){
					console.log('sending...');
					//$("#divloading").show();
				},
				success:function(result){				
					console.log('sent!');
					
					$.each(result,function(index, value) 
					{
						console.log(value);
						if(value)
						{
							$('#liResult').append( '<li>' + value + ' Mail sent successfully </li> ');	
						}
					});
				}, 
				error:function(exception){console.log(exception);}
				
			});	
		});

		$("#btnLaunchMailsToAll").click(function(){
			var lidempresa = $("#inputIdEmpresa").val();
			var lidoficina = $("#inputIdOficina").val();
			console.log('loading data ' + lidempresa);
			var data = { 
				idempresa : lidempresa,
				idoficina : lidoficina,
				all : 1
			};
			
			var request = $.ajax({
				url: 'config/senduserrequests',
				type: 'POST',
				data: data,
				beforeSend:function(){
					console.log('sending...');
					//$("#divloading").show();
				},
				success:function(result){				
					console.log('sent!');
					
					$.each(result,function(index, value) 
					{
						console.log(value);
						if(value.status)
						{
							$('#liResult').append( '<li>' + value.shortname + ' ' + value.msg + '</li>' );	
						}
						else
						{
							$('#liResult').append( '<li>' + value.shortname + ' Mail sent successfully </li>' );	
						}
					});
				}, 
				error:function(exception){console.log(exception);}
				
			});	
		});
			
		function LoadConfigData(){
			
			var lidempresa = $("#inputIdEmpresa").val();
			console.log('loading data ' + lidempresa);
			var data = { empresa : lidempresa };
			
			var request = $.ajax({
				url: 'config/loadoficinasempresa',
				type: 'POST',
				data: data,
				beforeSend:function(){
					console.log('sending...');
					//$("#divloading").show();
				},
				success:function(result){				
					console.log('sent!');
					
					//var data = jQuery.parseJSON(result);
					console.log(result);
					
					$.each(result,function(index, value) 
					{
						console.log(value.idoficina);
						$("#inputStation").append('<option value=' + value.idoficina + '>' + value.sede + '</option>');
					});
				}, 
				error:function(exception){console.log(exception);}
				
			});	
		}
			
		return false;
	});
	
	</script>
    <!-- End Horizontal Form -->