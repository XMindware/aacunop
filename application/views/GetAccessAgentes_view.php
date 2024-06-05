<?
	error_reporting(E_ERROR | E_PARSE);
?>
<?php header('Access-Control-Allow-Origin: *'); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   
    <meta name="description" content="AA Agents Control">
    <meta name="author" content="Xavier Alfeiran mindware.com.mx">
    <link rel="icon" type="image/png" href="<? echo base_url(); ?>assets/images/favicon.png"/>
    <title><? echo $titulo; ?></title>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<? echo base_url(); ?>assets/js/bootstrap.min.js"></script>

    <script src="<? echo base_url(); ?>assets/js/jquery.hotkey.js"></script>

    <!-- Modal dialogs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/js/bootstrap-dialog.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/css/bootstrap-dialog.min.css" rel="stylesheet">
    
    <!-- Bootstrap core CSS -->
    <link href="<? echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<? echo base_url(); ?>assets/css/sticky-footer-navbar.css" rel="stylesheet">
	<link href="<? echo base_url(); ?>assets/css/loading.css" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link href="<? echo base_url(); ?>assets/css/navbar-fixed-top.css" rel="stylesheet">
	
  <script src="<? echo base_url(); ?>assets/js/moment.js"></script>
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-VPJJCHEV8F"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-VPJJCHEV8F');
    </script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
      .navbar-brand>img {
         max-height: 100%;
         height: 100%;
         width: auto;
         margin: 0 auto;


         /* probably not needed anymore, but doesn't hurt */
         -o-object-fit: contain;
         object-fit: contain; 

      }
  </style>
  </head>

  <body>
    <script src="https://cdn.jsdelivr.net/webshim/1.12.4/extras/modernizr-custom.js"></script>
        <!-- polyfiller file to detect and load polyfills -->
        <script src="https://cdn.jsdelivr.net/webshim/1.12.4/polyfiller.js"></script>
        <script>
          webshims.setOptions('waitReady', false);
          webshims.setOptions('forms-ext', {types: 'date'});
          webshims.polyfill('forms forms-ext');
    </script>  
  <div class="container">
    <div class="container">
        <div class="panel-body">
            <div class="panel-heading">
            	<div class="row">
                <h2><span class="label label-primary">Setup Access for Agent</span></h2>
               
                </div>
            </div>           
            <form id="frmAgentData">
                <fieldset>
                    <legend>Agent Information</legend>
                    <div class='row'>
                        <div class='col-sm-4'>    
                            <div class='form-group'>
                                <label for="inputAgentId">Agent ID</label>
                                <input class="form-control" id="inputAgentId" name="inputAgentId" size="30" type="text" value="<? echo $idagente; ?>" readonly/>
                                <input type="hidden" id="inputIdEmpresa"  name="inputIdEmpresa" value="<? echo $idempresa; ?>" />
                                <input type="hidden" id="inputIdOficina"  name="inputIdOficina" value="<? echo $idoficina; ?>" />
                                <input type="hidden" id="inputUniqueId"   name="inputUniqueId" value="<? echo $uniqueid; ?>"/>
                            </div>
                        </div>
                        <div class='col-sm-4'>
                            <div class='form-group'>
                                <label for="inputFirstName">First name</label>
                                <input class="form-control" id="inputFirstName" name="inputFirstName" required="true" size="30" type="text" value="<? echo $nombre; ?>" readonly/>
                            </div>
                        </div>
                        <div class='col-sm-4'>
                            <div class='form-group'>
                                <label for="inputLastName">Last name</label>
                                <input class="form-control" id="inputLastName" name="inputLastName" required="true" size="30" type="text" value="<? echo $apellidos; ?>" readonly/>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-sm-4'>
                            <div class='form-group'>
                                <label for="inputShortName">Short name</label>
                                <input class="form-control" id="inputShortName" name="inputShortName" required="true" size="30" type="text" value="<? echo $shortname; ?>" readonly/>
                            </div>
                        </div>
                        <div class='col-sm-4'>
                            <div class='form-group'>
                                <label for="inputEmail">Email</label>
                                <input class="form-control required" id="inputEmail" name="inputEmail" required="true" size="30" type="text" value="<? echo $email; ?>" readonly/>
                            </div>
                        </div>
                        <div class='col-sm-4'>
                            <div class='form-group'>
                                <label for="inputJoinDate">Join Date</label>
                                <input class="form-control required" id="inputJoinDate" name="inputJoinDate" required="true" size="30" type="date" value="<? echo $joindate; ?>" readonly/>
                            </div>
                        </div>
                        <div class='col-sm-4'>
                            <div class='form-group'>
                                <label for="inputPassword">Password</label>
                                <input class="form-control required" id="inputPassword" name="inputPassword" required="true" size="30" type="password" />
                            </div>
                        </div>
                        <div class='col-sm-4'>
                        	<div class='form-group'>
                                <label for="inputPassword2">Repeat Password</label>
                                <input class="form-control required" id="inputPassword2" name="inputPassword2" required="true" size="30" type="password" />
                            </div>
                        </div>
                        
                    </div>
                   	<div class='row'>
                        <div class='col-sm-8'>
                        	<label id="lblMensaje" style="color:red"></label>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-sm-4'>
                            <div class='form-group'>
                                <button type="button" id="btnSubmitAgent" class="btn btn-success">Save</button>
                                <button type="button" id="btnCancel" class="btn btn-default">Cancel</button>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>
      	</div>
      </div>
    </div>
    <!-- End Horizontal Form -->
    
    
    <script type="text/javascript">

	$('#myPleaseWait').modal('hide');
	$(document).ready(function(){
		
		$("#btnSubmitAgent").click(function(){
			
			// validar el password
			var pass1 = $("#inputPassword").val();
			var pass2 = $("#inputPassword2").val();

			if(pass1 != pass2)
			{
				$("#lblMensaje").text('Passwords do not match');
			}
			else if(pass1.length == 0 || pass2.length == 0)
			{
				$("#lblMensaje").text('Please fill the password fields');
			}
			else if(pass1.length < 5 || pass2.length < 5)
			{
				$("#lblMensaje").text('Password must be at least 6 characters long');
			}
			else
			{
				var lidempresa = $("#inputIdEmpresa").val();
				var lidoficina = $("#inputIdOficina").val();
				var luniqueid = $("#inputUniqueId").val();
				var lpassword = $("#inputPassword").val();
	
				var agent = {
							 idempresa : lidempresa,
							 idoficina : lidoficina,
							 uniqueid : luniqueid,
							 password : lpassword,
							 };
				
				var request = $.ajax({
					url: '<? echo base_url();?>agentes/enableaccount',
					type: 'POST',
					data: agent,
					beforeSend:function(){
						console.log('sending...');
						$('#myPleaseWait').modal('show');
					},
					success:function(result){
						console.log('sent!');
						$('#myPleaseWait').modal('hide'); 
						window.location.href="https://apps.mindware.com.mx/"
						
					}, 
					error:function(exception){
						console.log(exception);
					}
					
				});
			}
			
		});
			
		//return false;
	});
	
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
				//var agent = jQuery.parseJSON(data);
				var agent = data[0];
				console.log('uniqueid ' + agent.uniqueid);
				$("#inputAgentId").val(idagente);
				$("#inputLastName").val(agent.apellidos);
				$("#inputFirstName").val(agent.nombre);
				$("#inputShortName").val(agent.shortname);
				$("#inputJoinDate").val(agent.ingreso);
				$("#inputEmail").val(agent.email);
				$("#inputPosition").val(agent.puesto);
				$("#inputHours").val(agent.jornada);
				$("#inputUniqueId").val(agent.uniqueid);
				
				$("html, body").animate({ scrollTop: 0 }, "fast");

				LoadAgentSkills();
				
				$('#myPleaseWait').modal('hide');
			}
		});
	}
	
</script>