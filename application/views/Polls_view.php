    <script src="<? echo base_url(); ?>assets/js/typeahead.min.js"></script>
    <div class="container">
        <div class="panel-body">
            <div class="panel-heading">
                <div class="row">
                <h2><span class="label label-primary">Polls</span></h2>
                <button type="button" id="btnNewAgent" class="btn btn-link">New Poll</button>
                <input type="hidden" id="inputIdEmpresa" value="<? echo $idempresa; ?>" />
                <input type="hidden" id="inputIdOficina" value="<? echo $idoficina; ?>" />
                <input type="hidden" id="inputUsuario" value="<? echo $usuario; ?>" />
                <input type="hidden" id="inputUniqueId"  />
                </div>
            </div>           
            <form id="frmAgentData" method="post">
                
                <fieldset required="required">
                        <legend>Record Information</legend>
                        <div class='row'>
                            <div class='col-sm-3'>
                                <div class='form-group'>
                                    <label for="inputNombre">Poll Short name</label>
                                    <input class="form-control required" id="inputNombre" name="inputNombre" required="true" size="30" type="text" />
                                </div>
                            </div>
                            <div class='col-sm-9'>
                                <div class='form-group'>
                                    <label for="inputDescripcion">Poll Description</label>
                                    <input class="form-control required" id="inputDescripcion" name="inputDescripcion" required="true" size="30" type="text" />
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                             <div class='col-sm-6'>
                                <div class='form-group'>
                                    <label for="inputFechaInicio">Begining</label>
                                    <input class="form-control required" id="inputFechaInicio" name="inputFechaInicio" size="30" type="date"/>
                                </div>
                            </div>
                            <div class='col-sm-6'>
                                <div class='form-group'>
                                    <label for="inputFechalimite">Deadline</label>
                                    <input class="form-control required" id="inputFechalimite" name="inputFechalimite" size="30" type="date"/>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-sm-12'>
                                <legend>Please type the options available for the poll, only the options with data will be available to be choosen.</legend>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-sm-6'>
                                <div class='form-group'>
                                    <label for="inputOpcion1">Option 1</label>
                                    <input class="form-control required" id="inputOpcion1" name="inputOpcion1" required="true" size="30" type="text" />
                                </div>
                            </div>
                             <div class='col-sm-6'>
                                <div class='form-group'>
                                    <label for="inputOpcion2">Option 2</label>
                                    <input class="form-control" id="inputOpcion2" name="inputOpcion2" required="true" size="30" type="text" />
                                </div>
                            </div>
                            <div class='col-sm-6'>
                                <div class='form-group'>
                                    <label for="inputOpcion3">Option 3</label>
                                    <input class="form-control" id="inputOpcion3" name="inputOpcion3" required="true" size="30" type="text" />
                                </div>
                            </div>
                             <div class='col-sm-6'>
                                <div class='form-group'>
                                    <label for="inputOpcion4">Option 4</label>
                                    <input class="form-control" id="inputOpcion4" name="inputOpcion4" required="true" size="30" type="text" />
                                </div>
                            </div>
                            <div class='col-sm-6'>
                                <div class='form-group'>
                                    <label for="inputOpcion5">Option 5</label>
                                    <input class="form-control" id="inputOpcion5" name="inputOpcion5" required="true" size="30" type="text" />
                                </div>
                            </div>
                             <div class='col-sm-6'>
                                <div class='form-group'>
                                    <label for="inputOpcion6">Option 6</label>
                                    <input class="form-control" id="inputOpcion6" name="inputOpcion6" required="true" size="30" type="text" />
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
                        <th>Poll</th>
                        <th>Dates range</th>
                        <th colspan=4>Options available</th>
                      </tr>
                    </thead>
                    <tbody id="tblData">
                    <?
                        if($registros)
                        foreach($registros as $row)
                        {
                    ?>
                    
                      <tr>
                        <td><button type="button" onclick="loadRow(<? echo $row['uniqueid'] . ',' . $row['idempresa'] . ',' . $row['idoficina']; ?>);" class="btn btn-link"><? echo $row['nombre']; ?></button></td>
                        <td><? echo date('d/M/Y', strtotime($row['fechainicio'])) . ' to ' . date('d/M/Y', strtotime($row['fechalimite'])); ?></td>
                        <td><? echo $row['opcion1'] . '(' . $row['result']->opcion1 . ')'; ?></td>
                        <td><? if($row['opcion2'] !='') echo $row['opcion2'] . '(' . $row['result']->opcion2 . ')'; ?></td>
                        <td><? if($row['opcion3'] !='') echo $row['opcion3'] . '(' . $row['result']->opcion3 . ')'; ?></td>
                        <td><? if($row['opcion4'] !='') echo $row['opcion4'] . '(' . $row['result']->opcion4 . ')'; ?></td>
                        <td><? if($row['opcion5'] !='') echo $row['opcion5'] . '(' . $row['result']->opcion5 . ')'; ?></td>
                        <td><? if($row['opcion6'] !='') echo $row['opcion6'] . '(' . $row['result']->opcion6 . ')'; ?></td>
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
            var lidempresa = $("#inputIdEmpresa").val();
            var lidoficina = $("#inputIdOficina").val();
            
            var luniqueid = $("#inputUniqueId").val();
            var lnombre = $("#inputNombre").val();
            var ldescripcion = $("#inputDescripcion").val();
            var lfechaini = $("#inputFechaInicio").val();
            var lfechafin = $("#inputFechalimite").val();
            var lopcion1 = $("#inputOpcion1").val();
            var lopcion2 = $("#inputOpcion2").val();
            var lopcion3 = $("#inputOpcion3").val();
            var lopcion4 = $("#inputOpcion4").val();
            var lopcion5 = $("#inputOpcion5").val();
            var lopcion6 = $("#inputOpcion6").val();
            
            $("#frmAgentData").hide();  
            var data = {
                         idempresa : lidempresa,
                         idoficina : lidoficina,
                         uniqueid : luniqueid,
                         nombre : lnombre,
                         descripcion : ldescripcion,
                         fechaini : lfechaini,
                         fechafin : lfechafin,
                         opcion1 : lopcion1,
                         opcion2 : lopcion2,
                         opcion3 : lopcion3,
                         opcion4 : lopcion4,
                         opcion5 : lopcion5,
                         opcion6 : lopcion6
                         };
            
            $.each(data, function(index, value) {
                console.log(value);
            });
            
            var request = $.ajax({
                url: 'polls/postrow',
                type: 'POST',
                data: data,
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
            url: 'polls/asynclist',
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
                        "<td><button type='button' onclick=\"loadRow('" + line['uniqueid'] + "','" + line['idempresa'] + "','" + line['idoficina'] + "');\" class='btn btn-link'>" + 
                        line['nombre'] + "</button></td>";
                    html += "<td>" + line['nombre'] + "</td>";
                    html += "<td>" + line['fechainicio'] + ' to ' + line['fechalimite'] + "</td>";
                    html += "<td>" + line['opcion1'] + "</td>";
                    html += "<td>" + line['opcion2'] + "</td>";
                    html += "<td>" + line['opcion3'] + "</td>";
                    html += "<td>" + line['opcion4'] + "</td>";
                    html += "<td>" + line['opcion5'] + "</td>";
                    html += "<td>" + line['opcion6'] + "</td>";
                    html += "</tr>";
                
                });    
                  
                $("#tblData").append(html);
                

                $('#myPleaseWait').modal('hide');
            }, 
            error:function(exception){console.log(exception);}
            
        });
    }


    function loadRow(uniqueid, idempresa, idoficina)
    {
        $("#frmAgentData").show();  
        
        
        var agentData = { uniqueid : uniqueid,
                          idempresa : idempresa,
                          idoficina : idoficina
                         };
        $.ajax({
            url: 'polls/loadrow',
            type: 'POST',
            data : agentData,
            beforeSend:function(){
                $('#myPleaseWait').modal('show');
                $(document).scrollTop();
            },
            success:function(data){
                console.log('loading data...');

                $("#inputNombre").val(data.nombre);
                $("#inputUniqueId").val(data.uniqueid);
                $("#inputFechaInicio").val(data.fechainicio);
                $("#inputFechalimite").val(data.fechalimite);
                $("#inputDescripcion").val(data.descripcion);
                $("#inputOpcion1").val(data.opcion1);
                $("#inputOpcion2").val(data.opcion2);
                $("#inputOpcion3").val(data.opcion3);
                $("#inputOpcion4").val(data.opcion4);
                
                $('#myPleaseWait').modal('hide');
            }
        });
    }
</script>