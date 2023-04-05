    <style>
    	#calendar {
	      max-width: 700px;
	      margin: 40px auto;
	    }
	</style>
    <div class="container">
        <div class="panel-body">
            <div class="panel-heading">
            	<div class="row">
                <h2><span class="label label-primary">Calendar Notes</span></h2>
                <input type="hidden" id="inputIdEmpresa" value="<? echo $idempresa; ?>" />
                <input type="hidden" id="inputIdOficina" value="<? echo $idoficina; ?>" />
                </div>
            </div>  
			<link href='https://unpkg.com/@fullcalendar/core@4.3.1/main.min.css' rel='stylesheet' />
			<link href='https://unpkg.com/@fullcalendar/daygrid@4.3.0/main.min.css' rel='stylesheet' />
			<link href='https://unpkg.com/@fullcalendar/timegrid@4.3.0/main.min.css' rel='stylesheet' />
			<script src='https://unpkg.com/@fullcalendar/core@4.3.1/main.min.js'></script>
			<script src='https://unpkg.com/@fullcalendar/interaction@4.3.0/main.min.js'></script>
			<script src='https://unpkg.com/@fullcalendar/daygrid@4.3.0/main.min.js'></script>
			<script src='https://unpkg.com/@fullcalendar/timegrid@4.3.0/main.min.js'></script>

			<div id="calendar">
			</div>
            <form id="frmNoteData" action="#">
            	
                <fieldset required="required">
                    <legend>Edit Note</legend>
                        <div class='row'>
                            <div class='col-sm-12'>
                                <div class='form-group'>
                                    <label for="inputNoteDate">Note Date</label><br/>
                                    <input id="inputNoteDate"  name="inputNoteDate" required="true" type="date">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class='col-sm-12'>
                                <div class='form-group'>
                                    <label for="inputNoteContent">Content</label>
                                    <textarea id="inputNoteContent"  name="inputNoteContent" rows='7' required="true" class="form-control">
                                    	
                                    </textarea>
                      				  
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-sm-8'>
                                <div class='form-group'>
                                    <button type="button" id="btnSubmitNote" class="btn btn-success">Save</button>
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
  
  	document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
          selectable: true,
          plugins: [ 'interaction', 'dayGrid' ],
          events: [
          <?
          foreach($notes as $note)
          {
          	$texto =substr($note['textonota'],0,10);
          	$texto = str_replace("\n", ", ", $texto);
			$texto = str_replace("\r", "", $texto);
          	?>
          	{
          		title : '<? echo  $texto . '...'; ?>',
          		start : '<? echo $note['fecha']; ?>',
          		url : 'javascript:loadDateNote("<? echo $note['fecha']; ?>");'
          	},
          	<?
          }
        ?>
        ],
        dateClick: function(info) {
	        loadDateNote(info.dateStr);
	    }
        });

        calendar.render();
      });

  	$("#frmNoteData").hide();
	$('#myPleaseWait').modal('hide');
	$(document).ready(function(){

		
      	
		
		$("#btnSubmitNote").click(function(){
			
			var lfecha = $("#inputNoteDate").val();
			var ltextnota = $("#inputNoteContent").val();
			var lidempresa = $("#inputIdEmpresa").val();
			var lidoficina = $("#inputIdOficina").val();
			
			$("#frmAgentData").hide();	
			var agent = {
				 idempresa : lidempresa,
				 idoficina : lidoficina,
				 fecha : lfecha,
				 textonota : ltextnota
				 };
			
			$.each(agent, function(index, value) {
				console.log(value);
			});
			
			var request = $.ajax({
				url: 'notes/postnote',
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
		
		
		$("#btnCancel").click(function(){
			$("#inputNoteContent").val('');
			$("#inputNoteDate").val('');
			$("#frmNoteData").hide();	
			$('html, body').animate({
			 scrollTop: $("#calendar").offset().top
			 }, 400);
		});
			
			
		return false;
	})


	function loadDateNote(fecha)
	{
		$("#inputNoteDate").val(fecha);
		$("#frmNoteData").show();	
		
		var idempresa = $("#inputIdEmpresa").val();
		var idoficina = $("#inputIdOficina").val();
		
		var agentData = { fecha : fecha,
						  idempresa : idempresa,
						  idoficina : idoficina
						 };
		$.ajax({
			url: 'notes/loaddatenote',
			type: 'POST',
			data : agentData,
			beforeSend:function(){
				$('#myPleaseWait').modal('show');
				$(document).scrollTop();
			},
			success:function(data){
				console.log('loading data...'  + data.textonota);
				$("#inputNoteContent").val(data.textonota);
				$('html, body').animate({
				 scrollTop: $("#inputNoteContent").offset().top
				 }, 400);
				$('#myPleaseWait').modal('hide');
			}
		});
	}
</script>