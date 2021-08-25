    <div class="container">
        <div class="panel-body">
            <div class="panel-heading">
            	<div class="row">
                <h2><span class="label label-primary">Process agents schedule</span></h2>
                <input type="hidden" id="inputIdEmpresa" value="<? echo $idempresa; ?>" />
                <input type="hidden" id="inputIdOficina" value="<? echo $idoficina; ?>" />
                <input type="hidden" id="inputTimezone" name="inputTimezone" value="<? echo $timezone; ?>"/>
                <input type="hidden" id="inputUsuario" name="inputUsuario" value="<? echo $usuario; ?>"/>
                <input type="hidden" id="inputIATA" name="inputIATA" value="<? echo $iatacode; ?>"/>
                <input type="hidden" id="inputProgress" name="inputProgress" value='0'/>
                </div>
            </div>           
             <form id="frmData" method="post">
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
	                        <div class='col-sm-8'>
	                            <div class='form-group'>
	                                <button type="button" id="btnSubmitData" class="btn btn-success">Load</button>
	                                <button type="button" id="btnCancel" class="btn btn-default">Cancel</button>
	                            </div>
	                        </div>
	                    </div>
                      <div class='row'>
                        <div class="progress">
                          <div class="progress-bar" id="progressbar" role="progressbar" 
                          aria-valuemin="0" aria-valuemax="10">
                          </div>
                        </div>
                      </div>
                    </fieldset>
                </center>
            </form>
          
    <script type="text/javascript">

    var totaldias = 10;
    var diasok = 0;

	$("#divloading").hide();
	$(document).ready(function(){
		  $("#progressbar").hide();
      //$("#progressbar").css('width', 0+'%').attr('aria-valuenow', 30);
		  $("#btnSubmitData").click(function(){

        var fechaini = $("#inputFechaIni").val();
        var fechafin = $("#inputFechaFin").val();
        var lidempresa = $("#inputIdEmpresa").val();
        var lidoficina = $("#inputIdOficina").val();
        var estacion = $("#inputIATA").val();
        var usuario = $("#inputUsuario").val();
        var timezone = new Date().getTimezoneOffset();
        $("#progressbar").show();
          
        var mfechaini = moment(fechaini);
        var mfechafin = moment(fechafin);

        var dias = moment(fechafin).diff(moment(fechaini),'days') + 1;

        totaldias = dias;
        diasok = 0;

        $("#progressbar").css('width', 0+'%').attr('aria-valuemax', dias);
        $("#progressbar").css('width', 0+'%').attr('aria-valuenow', 0);
        $("#inputProgress").val(0);

        for(var i=0; i<dias;i++)
        {

          var thisfecha = moment(fechaini).add(i,'days').format("YYYY-MM-DD");
          var data = {
               idempresa : lidempresa,
               idoficina : lidoficina,
               fecha : thisfecha,
               estacion : estacion,
               usuario : usuario,
               timezone : timezone
               };

          $.each(data, function(index, value) {
            console.log(value);
          });
        
          var request = $.ajax({
            url: 'fillcunopdate/processdates',
            type: 'POST',
            data: data,
            beforeSend:function(){
              //console.log('sending...');
              //diasok++;
              //updateProgress((diasok/totaldias)*100);
              $("#divloading").show();
            },
            success:function(result){
              //location.reload();
              console.log('sent!');
              console.log(result);

              diasok++;
              updateProgress((diasok/totaldias)*100);

              //$("#inputProgress").val(parseInt($("#inputProgress").val()+1));
              //alert($("#inputProgress").val());
              //var lday = parseInt($("#inputProgress").val());
              console.log('progress ' + diasok);
              //$("#progressbar").css('width', 100+'%').attr('aria-valuenow', lday);
            }, 
            error:function(exception){
              console.log(exception);}
            
          });

          /*
          request.fail(function( jqXHR, textStatus, errorThrown ) {
             if ( console && console.log ) {
                 console.log( "La solicitud a fallado: " +  textStatus);
             }
          });*/
        }
        $("#divloading").hide();

      });



  			
  		return false;
	})


  function updateProgress(percentage){
    if(percentage > 100) percentage = 100;
    $('#progressbar').css('width', parseInt(percentage)+'%');
    $('#progressbar').html(parseInt(percentage)+'%');
}

</script>