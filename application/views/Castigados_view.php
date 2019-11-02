    <script src="<? echo base_url(); ?>assets/js/typeahead.min.js"></script>
    <div class="container">
        <div class="panel-body">
            <div class="panel-heading">
            	<div class="row">
                <h2><span class="label label-primary">Non Compliant Agents</span></h2>
                <button type="button" id="btnNewAgent" class="btn btn-link">New Agent</button>
                <input type="hidden" id="inputIdEmpresa" value="<? echo $idempresa; ?>" />
                <input type="hidden" id="inputIdOficina" value="<? echo $idoficina; ?>" />
                <input type="hidden" id="inputUsuario" value="<? echo $usuario; ?>" />
                </div>
            </div>           
            <form id="frmAgentData" method="post">
            	
                <fieldset required="required">
                        <div class='row'>
                            <div class='col-sm-6'>
                                <div class='form-group'>
                                    <label for="inputAgent">Select an Agent</label>
                                    <input type="hidden" id="inputUniqueId" name="inputUniqueId" />
                                    <input type="hidden" id="inputAgenteId" name="inputAgenteId" />
                                    
                                	<input class="form-control typeahead" id="inputAgent" name="inputAgent" required="true" placeholder="Search Agents" size="30" type="text" />
                                                                    
                                </div>
                            </div>
                        </div>
                       	<legend>Record Information</legend>
                       	<div class='row'>
                            <div class='col-sm-6'>
                                <div class='form-group'>
                                    <label for="inputFechaIni">From</label>
                                    <input class="form-control required" id="inputFechaIni" name="inputFechaIni" required="true" size="30" type="date" />
                                </div>
                            </div>
                            <div class='col-sm-6'>
                                <div class='form-group'>
                                    <label for="inputFechaFin">To</label>
                                    <input class="form-control required" id="inputFechaFin" name="inputFechaFin" required="true" size="30" type="date" />
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-sm-6'>
                                <div class='form-group'>
                                    <label for="inputRazon">Reason</label>
                                    <input class="form-control required" id="inputRazon" name="inputRazon" required="true" size="30" type="text" />
                                </div>
                            </div>
                            <div class='col-sm-6'>
                                <div class='form-group'>
                                    <input class="form-control required" id="inputStatus" name="inputStatus" required="true" size="30" type="hidden" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-sm-8'>
                                <div class='form-group'>
                                    <button type="button" id="btnSubmit" class="btn btn-success">Save</button>
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
                        <th>#</th>
                        <th>Name</th>
                        <th>Dates</th>
                        <th>Reason</th>
                        <th>By</th>
                      </tr>
                    </thead>
                    <?
						foreach($castigados as $row)
						{
					?>
                    <tbody id="tblData">
                      <tr>
                        <td><button type="button" onclick="loadAgent(<? echo $row['uniqueid'] . ',' . $row['idempresa'] . ',' . $row['idoficina']; ?>);" class="btn btn-link"><? echo $row['idagente']; ?></button></td>
                        <td><? echo $row['shortname']; ?></td>
                        <td><? echo date('d/M/Y', strtotime($row['fechacastigo'])) . ' to ' . date('d/M/Y', strtotime($row['fechafin'])); ?></td>
                        <td><? echo $row['razon']; ?></td>
                        <td><? echo $row['aplicocastigo']; ?></td>
                      </tr>
                      <? } ?>
                    </tbody>
                  </table>
          	</div>
      	</div>
      </div>
    </div>
    <!-- End Horizontal Form -->
    
    
    <script type="text/javascript">
  
	$("#frmAgentData").hide();
	$('#myPleaseWait').modal('hide');
	$(document).ready(function(){

		var agenteslista = [<? foreach($fullagents as $agent) { echo "{ id : '" . $agent['idagente'] . "', name : '" . $agent['shortname'] . "'},"; } ?>];

        $('#inputAgent.typeahead').typeahead({
            source : agenteslista,
            display : 'name',
            val : 'id',
            onSelect : function(item)
            {
                console.log(item.value);
                $("#inputAgenteId").val(item.value);
            }
        });

		
		$("#btnSubmit").click(function(){
			
			var lagent = $("#inputAgent").val();
            var lidagent = $("#inputAgenteId").val();
			var lfechaini = $("#inputFechaIni").val();
            var lfechafin = $("#inputFechaFin").val();
            var lrazon = $("#inputRazon").val();
			var lidempresa = $("#inputIdEmpresa").val();
			var lidoficina = $("#inputIdOficina").val();
            var luniqueid = $("#inputUniqueId").val();
            var lusuario = $("#inputUsuario").val();
			
			$("#frmAgentData").hide();	
			var agent = {
						 idempresa : lidempresa,
						 idoficina : lidoficina,
						 agente : lagent,
                         agenteid : lidagent,
                         fechaini : lfechaini,
                         fechafin : lfechafin,
                         razon : lrazon,
                         usuario : lusuario,
                         uniqueid : luniqueid
						 };
			
			$.each(agent, function(index, value) {
				console.log(value);
			});
			
			var request = $.ajax({
				url: 'castigados/postrow',
				type: 'POST',
				data: agent,
				beforeSend:function(){
					console.log('sending...');
					$('#myPleaseWait').modal('show');
				},
				success:function(result){
					
					console.log('sent!');
                    RefreshList();
					$('#myPleaseWait').modal('hide');
				}, 
				error:function(exception){console.log(exception);}
				
			});
			
		});
		
		$("#btnNewAgent").click(function(){
            $("#inputUniqueId").val(-1);
            $("#inputAgent").val('');
            $("#inputAgenteId").val('');
            $("#inputFechaIni").val('');
            $("#inputFechaFin").val('');
            $("#inputRazon").val('');
            $("#inputIdEmpresa").val();
            $("#inputIdOficina").val();
			$("#frmAgentData").show();	
		});

        $("#btnDeleteRow").click(function(){

            var lidempresa = $("#inputIdEmpresa").val();
            var lidoficina = $("#inputIdOficina").val();
            var luniqueid = $("#inputUniqueId").val();
            
            var agentData = {
                idempresa : lidempresa,
                idoficina : lidoficina,
                uniqueid : luniqueid
            };

            $.ajax({
                url: 'castigados/deleterow',
                type: 'POST',
                data : agentData,
                beforeSend:function(){
                    $('#myPleaseWait').modal('show');
                    $(document).scrollTop();
                },
                success:function(data){
                    console.log('loading data...');

                    RefreshList();
                    
                    $('#myPleaseWait').modal('hide');
                }
            });
        })
		
		$("#btnCancel").click(function(){
			$("#inputAgentId").val('');
			$("#inputDaysOff").val('');
			$("#frmAgentData").hide();	
		});
			
			
		return false;
	})

    function RefreshList()
    {
        var lidempresa = $("#inputIdEmpresa").val();
        var lidoficina = $("#inputIdOficina").val();

        var fields = {
            idempresa : lidempresa,
            idoficina : lidoficina
        }

        var request = $.ajax({
            url: 'castigados/asynclist',
            type: 'POST',
            data: fields,
            beforeSend:function(){
                console.log('sending...');
                $('#myPleaseWait').modal('show');
            },
            success:function(result){
                
                console.log('sent!');
                //console.log(result);
                $('#tblData tr').remove();

                var html = '';
                $.each(result, function(row, line){

                    html += "<tr>" +
                        "<td><button type='button' onclick=\"loadAgent('" + line['uniqueid'] + "','" + line['idempresa'] + "','" + line['idoficina'] + "');\" class='btn btn-link'>" + 
                        line['idagente'] + "</button></td>";
                    html += "<td>" + line['shortname'] + "</td>";
                    html += "<td>" + line['fechacastigo'] + ' to ' + line['fechafin'] + "</td>";
                    html += "<td>" + line['razon'] + "</td>";
                    html += "<td>" + line['aplicocastigo'] + "</td>";
                    html += "</tr>";
                
                });    
                  
                $("#tblData").append(html);
                

                $('#myPleaseWait').modal('hide');
            }, 
            error:function(exception){console.log(exception);}
            
        });
    }


	function loadAgent(uniqueid, idempresa, idoficina)
	{
		$("#frmAgentData").show();	
		
		
		var agentData = { uniqueid : uniqueid,
						  idempresa : idempresa,
						  idoficina : idoficina
						 };
		$.ajax({
			url: 'castigados/loadrow',
			type: 'POST',
			data : agentData,
			beforeSend:function(){
				$('#myPleaseWait').modal('show');
				$(document).scrollTop();
			},
			success:function(data){
				console.log('loading data...');

                $("#inputAgent").val(data.shortname);
				$("#inputUniqueId").val(data.uniqueid);
				$("#inputFechaIni").val(data.fechacastigo);
                $("#inputFechaFin").val(data.fechafin);
                $("#inputRazon").val(data.razon);
                $("#inputAplicoCastigo").val(data.aplicocastigo);
                $("#inputStatus").val(data.status);
				
				$('#myPleaseWait').modal('hide');
			}
		});
	}
</script>