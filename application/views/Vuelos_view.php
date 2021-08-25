	    <script src="<? echo base_url(); ?>assets/js/typeahead.min.js"></script>
    <div class="container">
        <div class="panel-body">
            <div class="panel-heading">
            	<div class="row">
                <h2><span class="label label-primary"><? echo $flightcount; ?> Flights departing for current Station</span></h2>
                <button type="button" id="btnNewRow" class="btn btn-link">New Flight</button>
                <button type="button" id="btnRefresh" class="btn btn-link">Refresh</button>
                </div>
            </div>           
            <form id="frmData" method="post">
            	<input type="hidden" id="inputIdEmpresa" name="inputIdEmpresa" value="<? echo $idempresa; ?>"/>
            	<input type="hidden" id="inputTimezone" name="inputTimezone" value="<? echo $timezone; ?>"/>
            	<input type="hidden" id="inputEstacion" name="inputEstacion" value="<? echo $estacion; ?>"/>
            	<input type="hidden" id="inputUniqueid" name="inputUniqueid" value=""/>
            	<fieldset>
                    <legend>Flight Information</legend>
                        <div class='row'>
                            <div class='col-sm-8'>
                                <div class='form-group'>
                                    <label for="inputCode">Flight Code</label>
                                    <input class="form-control" id="inputCode" name="inputCode" required="true" size="30" type="text" />
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                          	<div class='col-sm-4'>
                                <div class='form-group'>
                                    <label for="inputFrom">From</label>
                                    <input class="form-control" id="inputFrom" name="inputFrom" required="true" size="30" type="text" />
                                </div>
                            </div>
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                    <label for="inputTo">To</label>
                                    <input class="form-control" id="inputTo" name="inputTo" required="true" size="30" type="text" />
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                        	<div class='col-sm-4'>
                                <div class='form-group'>
                                	<label for="inputDeparture">Departure (Local Time)</label>
                                	<input class="form-control" id="inputDeparture" name="inputDeparture" required="true" size="30" type="time" />
                                </div>
                            </div>
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                    <label for="inputDuracion">Fligth Duration</label>
                                    <input class="form-control" type="text" id="inputDuracion" name="inputDuracion" required="true" pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]" value="00:00"/>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-sm-1'>
                                <div class='form-group'>
                                    <label for="inputMonday">
                                    <input class="form-control" id="inputMonday" name="inputMonday" required="true" size="10" type="checkbox" />
                                    Mon</label>
                                </div>
                            </div>
                            <div class='col-sm-1'>
                                <div class='form-group'>
                                    <label for="inputTuesday">
                                    <input class="form-control" id="inputTuesday" name="inputTuesday" required="true" size="10" type="checkbox" />
                                    Tue</label>
                                </div>
                            </div>
                            <div class='col-sm-1'>
                                <div class='form-group'>
                                    <label for="inputWednesday">
                                    <input class="form-control" id="inputWednesday" name="inputWednesday" required="true" size="10" type="checkbox" />
                                    Wed</label>
                                </div>
                            </div>
                            <div class='col-sm-1'>
                                <div class='form-group'>
                                    <label for="inputThursday">
                                    <input class="form-control" id="inputThursday" name="inputThursday" required="true" size="10" type="checkbox" />
                                    Thu</label>
                                </div>
                            </div>
                            <div class='col-sm-1'>
                                <div class='form-group'>
                                    <label for="inputFriday">
                                    <input class="form-control" id="inputFriday" name="inputFriday" required="true" size="10" type="checkbox" />
                                    Fri</label>
                                </div>
                            </div>
                            <div class='col-sm-1'>
                                <div class='form-group'>
                                    <label for="inputSaturday">
                                    <input class="form-control" id="inputSaturday" name="inputSaturday" required="true" size="10" type="checkbox" />
                                    Sat</label>
                                </div>
                            </div>
                            <div class='col-sm-1'>
                                <div class='form-group'>
                                    <label for="inputSunday">
                                    <input class="form-control" id="inputSunday" name="inputSunday" required="true" size="10" type="checkbox" />
                                    Sun</label>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                        	<div class='col-sm-4'>
                                <div class='form-group'>
                                	<label for="inputBeginDate">Initial Date</label>
                                	<input class="form-control" id="inputBeginDate" name="inputBeginDate" required="true" size="30" type="date" />
                                </div>
                            </div>
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                    <label for="inputFinalDate">Final Date</label>
                                    <input class="form-control" id="inputFinalDate" name="inputFinalDate" required="true" size="30" type="date" />
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
                                    <button type="button" id="btnDeleteRow" class="btn btn btn-warning">Delete Flight</button>
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

		$("#btnRefresh").click(function(){
			RefreshList();
		});
		
		$("#btnSubmitData").click(function(){

			var offset = new Date().getTimezoneOffset();
		
			var lcode = $("#inputCode").val();
			var lfrom = $("#inputFrom").val();
			var lto = $("#inputTo").val();
			var horario = $("#inputDeparture").val();
			var hora 	= parseInt(horario.substr(0,2));
			var minuto 	= parseInt(horario.substr(3,2));
			var ldeparture	= (minuto * 60) + (hora * 3600) - (offset * 60);
			//alert(ldeparture + ' ' + offset);
				horario = $("#inputDuracion").val();
			
				hora 	= parseInt(horario.substr(0,2));
				minuto 	= parseInt(horario.substr(3,2));
			
			var lduracion	= (minuto * 60) + (hora * 3600);
			//alert(lduracion);
			var lmon = $("#inputMonday").prop('checked')?1:0;
			var ltue = $("#inputTuesday").prop('checked')?1:0;
			var lwed = $("#inputWednesday").prop('checked')?1:0;
			var lthu = $("#inputThursday").prop('checked')?1:0;
			var lfri = $("#inputFriday").prop('checked')?1:0;
			var lsat = $("#inputSaturday").prop('checked')?1:0;
			var lsun = $("#inputSunday").prop('checked')?1:0;
			var ledate = $("#inputEndDate").val();
			var lbegindate = $("#inputBeginDate").val();
			var lfinaldate = $("#inputFinalDate").val();
			var luniqueid = $("#inputUniqueid").val();
		
			$("#frmData").hide();	
			var data = {
				 uniqueid : luniqueid,
				 code : lcode,
				 origen : lfrom,
				 destino : lto,
				 horasalida : ldeparture,
				 duracionvuelo : lduracion,
				 lun : lmon,
				 mar : ltue,
				 mie : lwed,
				 jue : lthu,
				 vie : lfri,
				 sab : lsat,
				 dom : lsun,
				 begindate : lbegindate,
				 enddate : lfinaldate
				 };
			var request = $.ajax({
				url: 'vuelos/postvuelo',
				type: 'POST',
				data: data,
				beforeSend:function(){
					console.log('sending...');
					$('#myPleaseWait').modal('show');
				},
				success:function(result){
					console.log('sent!');
					//$('#myPleaseWait').modal('hide');
					//console.log(result);
					RefreshList();
				}
			});
			
		});
		
		$("#btnNewRow").click(function(){
			$("#inputUniqueid").val('');
			$("#inputCode").val('');
			$("#inputFrom").val('');
			$("#inputTo").val('');
			$("#inputDeparture").val('');
			$("#inputDuracion").val('');
			$("#inputMonday").prop('checked', false);
			$("#inputTuesday").prop('checked', false);
			$("#inputWednesday").prop('checked', false);
			$("#inputThursday").prop('checked', false);
			$("#inputFriday").prop('checked', false);
			$("#inputSaturday").prop('checked', false);
			$("#inputSunday").prop('checked', false);
			$("#inputBeginDate").val('');
			$("#inputFinalDate").val('');

			$("#frmData	").show();	
		});
		
		$("#btnCancel").click(function(){
			$("#inputCode").val('');
			$("#frmData").hide();	
		});
		
		$("#btnDeleteRow").click(function(){
			
			var lcode = $("#inputCode").val();
			var lidempresa = $("#inputIdEmpresa").val();
			var lorigen = $("#inputFrom").val();
			var luniqueid = $("#inputUniqueid").val();
			if(luniqueid == '')
				return;
			
			var rowData = { idempresa : lidempresa,
							idvuelo : lcode,
							uniqueid : luniqueid,
							origen : lorigen };
			$.ajax({
				url: 'vuelos/deleterowid',
				type: 'POST',
				data : rowData,
				beforeSend:function(){
					$('#myPleaseWait').modal('show');
					$(document).scrollTop();
				},
				success:function(data){
					
					console.log('row deleted');
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
    	var estacion = $("#inputEstacion").val();

	    var fields = {
	        idempresa : lidempresa,
	        iatacode : estacion
	    }
	    var request = $.ajax({
	        url: 'vuelos/asyncloadvuelos',
	        type: 'POST',
	        data: fields,
	        beforeSend:function(){
	            console.log('sending...');
	            $('#myPleaseWait').modal('show');
	        },
	        success:function(result){
	            
	            console.log('sent!');
	            var offset = new Date().getTimezoneOffset();

	            //console.log(result);
	            $('#tblInfo tr').remove();
	            var html = '<thead>' + 
                     '<tr>' +
                      	'<th>Code</th>' + 
         				'<th>Flight Detail</th>' +
                        '<th>Schedule</th>' +
                        '<th>Dates</th>' +
                      '</tr>' +
                    '</thead>' +
                    '<tbody>';
	            $.each(result,function(thisrow,row){

	                html += '<tr>' +
                           '<td><button type="button" onclick="loadRow(\'' + row['uniqueid'] + '\');" class="btn btn-link">' + row['idvuelo'] + '</button>' +
						   row['alert'] + '</td>' + 
							'<td>' + row['departure'] + ' ' + row['origen'] + ' - ' + row['destino'] + ' Duration ' + row['arrival'] + '</td>' + 
							'<td>' + row['msj'] + '</td>' +
							'<td>' + row['begindate'] + ' to ' + row['enddate'] + '</td>' +
							
							'</tr>' ;
	            });

	            html +='</tbody>';
	            $("#tblInfo").append(html);
	            $('#myPleaseWait').modal('hide');
	        }, 
	        error:function(exception){console.log(exception);}
	        
	    });
    }

	function loadRow(uniqueid)
	{
		$("#frmData").show();	
		
		var rowData = { 
						uniqueid : uniqueid };
		$.ajax({
			url: 'vuelos/loadvuelocode',
			type: 'POST',
			data : rowData,
			beforeSend:function(){
				$('#myPleaseWait').modal('show');
				$(document).scrollTop();
			},
			success:function(data){
				console.log('loading data...');
				var row = jQuery.parseJSON(data)[0];
				console.log('flight code ' + row.lun);

				var offset = new Date().getTimezoneOffset();

				$("#inputUniqueid").val(row.uniqueid);
				$("#inputCode").val(row.idvuelo);
				$("#inputFrom").val(row.origen);
				$("#inputTo").val(row.destino);
				$("#inputEndDate").val(row.enddate);

				$("html, body").animate({ scrollTop: 0 }, "fast");

				var horasalida = parseInt(row.horasalida) + (offset * 60);
				var hours = parseInt(horasalida / 3600);
				var minutes = (horasalida - (hours * 3600)) / 60;
				//alert(hours + ':' + minutes);

				$("#inputDeparture").val((hours<=9?('0'+hours):hours) + ':' + (minutes<=9?('0'+minutes):minutes) + ':00');
				hours = parseInt(row.duracionvuelo / 3600);
				minutes = parseInt(((row.duracionvuelo / 3600) - hours) * 60);
				console.log(hours);
				console.log(minutes);
				$("#inputDuracion").val((hours<=9?('0'+hours):hours) + ':' + (minutes<=9?('0'+minutes):minutes) + ':00');
				$("#inputMonday").prop('checked', row.lun==1);
				$("#inputTuesday").prop('checked', row.mar==1);
				$("#inputWednesday").prop('checked', row.mie==1);
				$("#inputThursday").prop('checked', row.jue==1);
				$("#inputFriday").prop('checked', row.vie==1);
				$("#inputSaturday").prop('checked', row.sab==1);
				$("#inputSunday").prop('checked', row.dom==1);
				$("#inputBeginDate").val(row.begindate);
				$("#inputFinalDate").val(row.enddate);
				
				$('#myPleaseWait').modal('hide');
			}
		});
	}
</script>