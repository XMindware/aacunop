
    <div class="container">
        <div class="panel-body">
            <div class="panel-heading">
            	<div class="row">
                <h2><span class="label label-primary">Load Monthly Leads Assignments</span></h2>
                
                </div>
            </div>          
            <?
              if(!isset($agentesok))
              { ?> 
            <?php echo form_open_multipart('Loadleadsmensual/loadcsv');?>
            	<input type="hidden" id="inputIdEmpresa" name="inputIdEmpresa" value="<? echo $idempresa; ?>" />
                <input type="hidden" id="inputIdOficina" name="inputIdOficina" value="<? echo $idoficina; ?>" />
            	<center>
                    <div id="divloading" style="position:fixed; z-index:10; background-color:#FFF">
                        <img src="<? echo base_url(); ?>assets/images/loading.gif" />
                    </div>
                    <fieldset>
	                    <legend>Fill info</legend>
	                    <div class='row'>
	                        <div class='col-sm-4'>
	                            <div class='form-group'>
	                                <label for="inputFechaIni">Initial Date</label>
	                                <input class="form-control" id="inputFechaIni" name="inputFechaIni" required="true" size="30" type="date" />
	                            </div>
	                        </div>
	                        <div class='col-sm-4'>
	                            <div class='form-group'>
	                                <label for="inputFechaFin">Final Date</label>
	                                <input class="form-control" id="inputFechaFin" name="inputFechaFin" required="true" size="30" type="date" />
	                            </div>
	                        </div>
	                    </div>
	                    <div class='row'>
	                        <div class='col-sm-12'>
	                            <div class='form-group'>
	                            	<p class="text-left">
		                                <label for="userfile">CSV Impressions File</label>
	    								<input type="file" id="userfile" name="userfile" accept=".csv">
	    							</p>
	                            </div>
	                        </div>
	                    </div>
	                    <div class='row'>
	                        <div class='col-sm-8'>
	                            <div class='form-group'>
	                                <button type="submit" id="btnSubmitData" class="btn btn-success">Load</button>
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
                </center>
            </form>
            <?
              }
            ?>
            <?
              if(isset($agentesok))
              {

            ?>
            <div class="row" id="divResults">
              <div class="col-md-12">
                <fieldset>
                    <legend>Import Results</legend>
                    <div class='row'>
                        <div class='col-sm-6'>
                            <div class='form-group'>
                                <label for="inputAgentsok">Agents imported OK</label>
                                <input class="form-control" id="inputAgentsok" name="inputAgentsok" value="<? echo $agentesok; ?>" required="true" size="30" type="text" />
                            </div>
                        </div>
                        <div class='col-sm-6'>
                            <div class='form-group'>
                                <label for="inputAgentsok">Agents missing in Catalog</label>
                                <textarea class="form-control" id="inputMissing" rows="5"><? 
                                  foreach($notfound as $not)
                                  {
                                    print_r($not[0]);
                                  }
                                  ?>
                                </textarea>
                            </div>
                        </div>
                    </div>
                </fieldset>
              </div>
            </div>
            <?
              }
            ?>
      	</div>
    </div>
    <!-- End Horizontal Form -->
    
    
    <script type="text/javascript">
	//$("#frmAgentData").hide();
	$("#divloading").hide();
	$(document).ready(function(){
		
		
		$("#btnNewAgent").click(function(){
			$("#frmAgentData").show();	
		});
		
		$("#btnCancel").click(function(){
			$("#inputAgentId").val('');
			$("#inputDaysOff").val('');
			$("#frmAgentData").hide();	
		});
			
			
		return false;
	})


	function loadFlightDetail(idempresa, idoficina, idvuelo, qdate)
	{
		
		var infoData = { idempresa : idempresa,
						  idoficina : idoficina,
						  idvuelo : idvuelo,
						  fecha : qdate
						 };
		$.ajax({
			url: 'webcunop/loadflightdetail',
			type: 'POST',
			data : infoData,
			beforeSend:function(){
				$("#divloading").show();
			},
			success:function(data){
				console.log('loading data...');
				var agent = jQuery.parseJSON(data)[0];
				console.log('shortname ' + agent.dia1);
				$("#inputAgentId").val(idagente);
				$("#inputDaysOff").val(agent.dia1);
				
				$("#divloading").fadeOut("slow");
			}
		});
	}
</script>