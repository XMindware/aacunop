    <script src="<? echo base_url(); ?>assets/js/typeahead.min.js"></script>
    <div class="container">
        <div class="panel-body">
            <div class="panel-heading">
                <div class="row">
                <h2><span class="label label-primary">Vacations & Leaves</span></h2>
                <button type="button" id="btnNewRow" class="btn btn-link">Add Record</button>
                </div>
            </div>           
            <form id="frmData" method="post">
                <input type="hidden" id="inputIdEmpresa" name="inputIdEmpresa" value="<? echo $idempresa; ?>"/>
                <input type="hidden" id="inputIdOficina" name="inputIdOficina" value="<? echo $idoficina; ?>"/>
                <input type="hidden" id="inputUsuario" name="inputUsuario" value="<? echo $usuario; ?>"/>
               
                <fieldset>
                    <legend>Leave Of Abscent Detail</legend>
                        <div class='row'>
                            <div class='col-sm-6'>
                                <div class='form-group'>
                                <label for="inputAgent">Agent</label>
                                <input type="hidden" id="inputIdAgent" />
                                <input type="hidden" id="inputUniqueId" />
                                <input class="form-control typeahead" id="inputAgent" name="inputAgent" required="true" placeholder="Search Agents" size="30" type="text" />
                                
                                </div>
                            </div>
                            <div class='col-sm-3'>
                                <div class='form-group'>
                                    <label for="inputIncidencia">Type of Abscent</label>
                                    <select id="inputIncidencia" name="inputIncidencia" required="true" class="form-control">
                                      <option value="MED">Medical</option>
                                      <option value="PAR">Parent Leave</option>
                                      <option value="PER">Personal Leave</option>
                                      <option value="TRA">Training</option>
                                      <option value="VAC">Vacations</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class='row'>
                             <div class='col-sm-6'>
                                <div class='form-group'>
                                    <label for="inputFechaIni">From</label>
                                    <input class="form-control" name="inputFechaIni" id="inputFechaIni" required="true" size="30" type="date">
                                </div>
                            </div>
                            <div class='col-sm-6'>
                                <div class='form-group'>
                                    <label for="inputFechaFin">To</label>
                                    <input class="form-control" name="inputFechaFin" id="inputFechaFin" required="true" size="30" type="date">
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
                        <th>Agent ID</th>
                        <th>Shortname</th>
                        <th>Type</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Responsible</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?
                    if(sizeof($rowlist)> 0){
                        foreach($rowlist as $row)
                            {
                            ?>
                              <tr>
                                <td><button type="button" onclick="loadRow('<? echo $row['uniqueid']; ?>');" class="btn btn-link">
                                    <? echo $row['idagente']; ?></button>
                                </td>
                                <td><? echo $row['shortname']; ?></td>
                                <td><? echo $row['incidencia']; ?></td>
                                <td><? echo $row['fechaini']; ?></td>
                                <td><? echo $row['fechafin']; ?></td>
                                <td><? echo $row['responsable']; ?></td>
                              </tr>
                            <?
                              
                           } ?>
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
    // ocultar los elementos de editar vuelo
    
    $(document).ready(function(){
      
        var agenteslista = [<? foreach($fullagents as $agent) { echo "{ id : '" . $agent['idagente'] . "', name : '" . $agent['shortname'] . "'},"; } ?>];

        $('#inputAgent.typeahead').typeahead({
            source : agenteslista,
            display : 'name',
            val : 'id',
            onSelect : function(item)
            {
                console.log(item.value);
                $("#inputIdAgent").val(item.value);
            }
        });
        
        // grabar cambios
        $("#btnSubmitData").click(function(){
        
            var luniqueid = $("#inputUniqueId").val();
            var lincidencia = $("#inputIncidencia").val();
            var lshortname = $("#inputAgent").val();
            var lidagent = $("#inputIdAgent").val();
            var lfechaini = $("#inputFechaIni").val();
            var lfechafin = $("#inputFechaFin").val();
            var lidempresa = $("#inputIdEmpresa").val();
            var lidoficina = $("#inputIdOficina").val();
            var lusuario = $("#inputUsuario").val();
        
            //console.log('lposicion ' + lposition);
            $("#frmData").hide();   
            var data = {
                 uniqueid : luniqueid,
                 idempresa : lidempresa,
                 idoficina : lidoficina,
                 idagent : lidagent,
                 shortname : lshortname,
                 incidencia : lincidencia,
                 fechaini : lfechaini,
                 fechafin : lfechafin,
                 usuario : lusuario
                 };
            var request = $.ajax({
                url: 'incidencias/postrow',
                type: 'POST',
                data: data,
                beforeSend:function(){
                    console.log('sending...');
                    $('#myPleaseWait').modal('show');
                },
                success:function(result){
                    console.log('sent!');
                    console.log(result);
                    location.reload();
                    $('#myPleaseWait').modal('hide');
                }, 
                error:function(exception){console.log(exception);}
                
            });
        });

        
        $("#btnNewRow").click(function(){
            $("#frmData").show();
            $("#inputUniqueId").val('-1');
            $("#inputAgent").val('');
            $("#inputIdAgent").val('');
        });
        
        $("#btnCancel").click(function(){
            $("#inputCode").val('');
            $("#frmData").hide();   
        });
        
        $("#btnDeleteRow").click(function(){
            
            var luniqueid = $("#inputUniqueId").val();
            var lidempresa = $("#inputIdEmpresa").val();
            var lidoficina = $("#inputIdOficina").val();
            
            var rowData = { idempresa : lidempresa,
                            idoficina : lidoficina,
                            uniqueid : luniqueid };
            $.ajax({
                url: 'incidencias/deleterowid',
                type: 'POST',
                data : rowData,
                beforeSend:function(){
                    $('#myPleaseWait').modal('show');
                    $(document).scrollTop();
                },
                success:function(data){
                    console.log('deleted row')
                    $('#myPleaseWait').modal('hide');
                    location.reload();
                }
            });
            $("#frmData").hide();   
        });
            
            
        return false;
    });

    function loadRow(uniqueid)
    {
        $("#frmData").show();   
        
        var lidempresa = $("#inputIdEmpresa").val();
        var lidoficina = $("#inputIdOficina").val();

        var rowData = { 
            idempresa : lidempresa,
            idoficina : lidoficina,
            uniqueid : uniqueid
        };

        $.ajax({
            url: 'incidencias/loadrowid',
            type: 'POST',
            data : rowData,
            beforeSend:function(){
                $('#myPleaseWait').modal('show');
                $(document).scrollTop();
            },
            success:function(data){
                console.log('loading data...');
                var row = data;

                
                $("#inputUniqueId").val(row.uniqueid);
                $("#inputIncidencia").val(row.incidencia);
                $("#inputAgent").val(row.shortname);
                $("#inputIdAgent").val(row.idagente);
                $("#inputFechaIni").val(row.fechaini);
                $("#inputFechaFin").val(row.fechafin);

                $("html, body").animate({ scrollTop: 0 }, "fast");
  
                $('#myPleaseWait').modal('hide');
            }
        });
    }
</script>