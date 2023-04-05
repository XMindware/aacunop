
    <style>
        th,td.vuelos{
            border-right: 2px solid #ddd;
        }

        .poscell {
            padding: 6px 6px !important;
        }

        .celllink {
            font-size: 12px;
            padding: 6px 6px !important;
        }

        table {
          text-align: center;
        }

        .tablecontainer {
          width: 900px;
          height: 600px;
          overflow: auto;
        }

        thead th {
            top : 0;
            position: sticky;
            z-index: 20;
            min-height: 40px;
            height: 40px;
            text-align: left;
        }

        table th,
        table td {
          white-space: nowrap;
          padding: 10px 20px;
          font-family: Arial;
        }

        table tr th:first-child,
        table tr th:nth-child(2),
        table td:nth-child(2),
        table td:first-child {
          position: sticky;
          width: 200px;
          left: 0;
          z-index: 11;
          background: #fff;
        }

        table tr th:first-child,
        table tr th:nth-child(2)  {
          z-index: 11;
        }

        table tr th {
          position: sticky;
          top: 0;
          z-index: 9;
          background: #fff;
        }

        .agenteinicial { 
            background-color: #ABEBC6 !important; 
        }

        .agenterecibe {
            background-color: #E74C3C !important;
        }

        .agenterecibe button {
            color : #fff;
        }

        .superscript{
            position: absolute;
            top : 2px;
            right: 8px;
            font-size: 10px;
            color : rgba(0, 0, 0, 0.7);
        }

        .text-decoration-underline {
            text-decoration: underline;
        }

    </style>
    <script src="<? echo base_url(); ?>assets/js/custypeahead.js"></script>
    <script src="<? echo base_url(); ?>assets/js/typeahead.min.js"></script>
    <div class="container">
        <div class="panel-body">
            <div class="panel-heading">
                <div class="row">
                <h2><span class="label label-primary">Monthly Agents Scheduler</span></h2>
                <button type="button" id="btnNewRow" class="btn btn-link">New Assignment</button>
                </div>
            </div>           
            
            <!-- LOAD AGENT POSITIONS -->
            <form id="frmAgentPositionData" style="display: none;" autocomplete="off">
                <fieldset>
                    <legend>Agent position assignment</legend>
                    <div class='row'>
                        <div class='col-sm-3'>
                            <div class='form-group'>
                                <input type="hidden" id="inputIdEmpresa" value="<? echo $idempresa; ?>" />
                                <input type="hidden" id="inputIdOficina" value="<? echo $idoficina; ?>" />
                                <input type="hidden" id="inputUniqueId"  />
                                <input type="hidden" id="inputAgenteIdPos" />
                                <input type="hidden" id="typeaheadflag" value=""  />
                                <input type="hidden" id="insertFlag"  />
                                <input type="hidden" id="inputUsuario" value="<? echo $idusuario; ?>" />
                                <label for="inputAgentShortname">Agent</label>
                                <input class="form-control typeahead" id="inputAgentShortname" name="inputAgentShortname" required="true" placeholder="Search Agents" size="30" type="text" />
                            </div>
                        </div>
                        <div class='col-sm-3'>
                            <div class='form-group'>
                                <label for="inputFechaPosicion">Date</label>
                                <input type="date" class="form-control" name="inputDatePosition" id="inputDatePosition" placeholder="Date"  >
                            </div>
                        </div>
                        <div class='col-sm-3'>
                            <div class='form-group'>
                                <label for="inputPositionAgent">Position Assigned</label>
                                <input type="text" class="form-control typeahead" name="inputPositionAgent" id="inputPositionAgent" placeholder="Search Positions">
                            </div>
                        </div>
                        <div class='col-sm-3'>
                            <div class='form-group align-items-end'>
                                <label for="inputAsLead">Acts as Lead</label><br/>
                                <input type="checkbox" name="inputAsLead" id="inputAsLead" placeholder="Lead">
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-sm-5'>
                            <div class='form-group'>
                                <button type="button" id="btnSubmitAgentPostionData" class="btn btn-success">Save</button>
                                <button type="button" id="btnCancelPosition" class="btn btn-default">Cancel</button>
                                <button type="button" id="btnDeleteAgentPositionData" class="btn btn-warning">Delete Asignment</button>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>

            <form>
                <fieldset>
                    <div class="row">
                        <div class="col-xs-2">
                            <label for="inputSelectedMonth">Selected Month</label>
                        </div>
                        <div class="col-xs-3">
                            <select id="inputSelectedMonth" name="inputSelectedMonth" required="true" class="form-control">
                               <?  
                                    $begin = date('Y-m-01', strtotime('-8 month'));
                                    $end = date('Y-m-t', strtotime('+4 month'));
                                    while (strtotime($begin) <= strtotime($end)) {
                                        
                                        $selected = "";
                                        if (isset($_GET['inputSelectedMonth']) ) {
                                            if ($_GET['inputSelectedMonth'] == $begin) {
                                                $selected = "selected";
                                            }
                                        } else {
                                            if (date('Y-m-01') == $begin) {
                                                $selected = "selected";
                                            }
                                        }
                                        
                                        echo "<option " . $selected . " value='".$begin."'>".date('F Y',strtotime($begin))."</option>";
                                        $begin = date ("Y-m-d", strtotime("+1 month", strtotime($begin)));
                                    }
                                    ?>
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <button type="submit" id="btnSubmitMonth" class="btn btn-success">Load</button>
                        </div>
                    
                    
                    </div>
                </fieldset>
            </form>
            <div class="row mt-2">
                <div class="col-md-12 tablecontainer">
                    <table id="tbFlights" class="table table-striped table-bordered">
                        <?
                        if($rows){
                            $estafecha = $fechaini;
                            ?>
                            <thead>
                                <tr><th class="vuelos headcol" colspan=2><? echo date('M',$estafecha); ?></th><?
                            while($estafecha <= $fechafin){
                                ?>
                                <th style="word-wrap: normal;"><? echo date('D d',$estafecha); ?></th>
                                <?
                                 $estafecha += (24*3600);
                            }
                            ?>
                                </tr>
                            </thead>
                            <tbody><?
                        
                        foreach($rows as $row)
                        {
                            ?>
                            <tr>
                                <th scope="row" class="headcol"><? echo $row['idagente']; ?></th>
                                <th scope="row" class="headcol" style="white-space: nowrap;"><? echo $row['shortname']; ?></th>
                            <?
                            if($row['resultado'])
                            foreach($row['resultado'] as $thisdate){
                                    // thisdate
                                    $fecha = $thisdate['fecha'];


                                    $uniqueid = $thisdate['asignaciones']['agent_data'][0]['uniqueid'];
                                    $posicion = '';
                                    $asignacion = '';
                                    $horas = 0;
                                    
                                    $updatedlast = [];

                                    for($i = 0; $i < sizeof($thisdate['asignaciones']['agent_data']); $i++){
                                        $cur_pos = $thisdate['asignaciones']['agent_data'][$i];          
                                        if(substr($posicion,0,2) == 'XX' || substr($posicion,0,2) == 'V-' )
                                            $posicion = $cur_pos['posicion'] . ( ( $i + 1 == sizeof($thisdate['asignaciones']['agent_data'] ) )? '' : ',' );
                                        elseif($posicion == '' && $cur_pos['posicion'] != 'XX')                              
                                            $posicion .= $cur_pos['posicion'] . ( ( $i + 1 == sizeof($thisdate['asignaciones']['agent_data'] ) )? '' : ',' );
                                        else
                                            $posicion .= $cur_pos['posicion'] . ( ( $i + 1 == sizeof($thisdate['asignaciones']['agent_data'] ) )? '' : ',' );

                                        if($cur_pos['workday'] == 'FT' && $cur_pos['regular'] == 1)
                                            $horas += 8;
                                        if($cur_pos['workday'] == 'PT' && $cur_pos['regular'] == 1)
                                            $horas += 4;

                                        if(!in_array($cur_pos['usuario'], $updatedlast)){
                                            $updatedlast[] = $cur_pos['usuario'];
                                        }

                                        $asignacion = $asignacion == '' ? $cur_pos['asignacion'] : $asignacion;
                                    }
                                    
                                    $horas = $horas==0 ? '' : $horas;
                                    
                                    $idagente = $row['idagente'];

                                    // informacion de los cambios del agente en ese dia para mostrar en el tooltip
                                    $cambioinfo = ' Trades : ' . PHP_EOL;
                                    // info de trades
                                    $class='';
                                    if($thisdate['asignaciones']['trade1']){
                                        foreach($thisdate['asignaciones']['trade1'] as $cur_trade ){
                                            if($cur_trade['idagente'] == $idagente){

                                                $idagentecambio = $cur_trade['idagentecambio'];
                                                $agenteinicial = $cur_trade['shortname'];
                                                $posicionsolicitada = $cur_trade['posicionsolicitada'];
                                                $agentecambio = $cur_trade['agentecambio'];
                                                $posicioninicial = $cur_trade['posicioninicial'];
                                                $status = $cur_trade['status'];

                                                $otheragent = '';
                                                $otherpos = '';
                                                if($status){
                                                    if( $idagentecambio == $idagente){
                                                        $otheragent = $agenteinicial;
                                                        $otherpos = $posicionsolicitada;
                                                    }
                                                    else{
                                                        $otheragent = $agentecambio;
                                                        $otherpos = $posicioninicial;
                                                    }
                                                }

                                                $cambioinfo .= ($status == '') ? '' : $cur_trade['tipocambio'] . ' with ' . $otheragent . ' for ' . $otherpos . PHP_EOL ;
                                                 if( $cur_trade['usuarioflagged'] != '')
                                                    $cambioinfo .= 'SPV flagged ' . $cur_trade['usuarioflagged'] . PHP_EOL;
                                                if( sizeof($updatedlast) > 0 )
                                                    $cambioinfo .= 'SPV updated ' . implode(', ', $updatedlast) . PHP_EOL;
                                                $class = "agenteinicial";
                                            }
                                        }
                                    }
                                    if($thisdate['asignaciones']['trade2']){
                                        foreach($thisdate['asignaciones']['trade2'] as $cur_trade ){
                                            if($cur_trade['idagentecambio'] == $idagente){

                                                $idagentecambio = $cur_trade['idagentecambio'];
                                                $agenteinicial = $cur_trade['shortname'];
                                                $posicionsolicitada = $cur_trade['posicionsolicitada'];
                                                $agentecambio = $cur_trade['agentecambio'];
                                                $posicioninicial = $cur_trade['posicioninicial'];
                                                $status = $cur_trade['status'];

                                                $otheragent = '';
                                                $otherpos = '';
                                                if($status){
                                                    if( $idagentecambio == $idagente){
                                                        $otheragent = $agenteinicial;
                                                        $otherpos = $posicionsolicitada;
                                                    }
                                                    else{
                                                        $otheragent = $agentecambio;
                                                        $otherpos = $posicioninicial;
                                                    }
                                                }

                                                $cambioinfo .= ($status == '') ? '' : $cur_trade['tipocambio'] . ' with ' . $otheragent . ' for ' . $otherpos . PHP_EOL ;
                                                if( $cur_trade['usuarioflagged'] != '')
                                                    $cambioinfo .= 'SPV flagged ' . $cur_trade['usuarioflagged'] . PHP_EOL;
                                                if( sizeof($updatedlast) > 0 )
                                                    $cambioinfo .= 'SPV updated ' . implode(', ', $updatedlast) . PHP_EOL;
                                                $class = "agenterecibe";
                                            }
                                        }

                                    }

                                    $status = sizeof($thisdate['asignaciones']['trade1']) > 0 ? $thisdate['asignaciones']['trade1'][0]['status'] : '';
                                    

                                    $color = $posicion == 'XX' || $posicion == '' ? '' : '#eeeeee';
                                    $weight = 'normal';
                                    if($status && $tipocambio == 'Cover'){
                                        $color = '#ABEBC6';
                                        $weight = 'bold';
                                    }
                                    elseif($status && $tipocambio == 'Switch'){
                                        $color = '#FADBD8';
                                        $weight = 'bold';
                                    }
                                    
                                    
                                    if($this->session->userdata('isadmin')){
                                    ?>
                                    <td class="poscell <? echo $class; ?>" align="center" style="position: relative;background-color: <?=$color; ?>"><?
                                        for($i = 0; $i < sizeof($thisdate['asignaciones']['agent_data']); $i++){
                                            $cur_pos = $thisdate['asignaciones']['agent_data'][$i];   
                                        ?>
                                        <button class="btn btn-link celllink <? echo ($asignacion == 'LEAD' ? 'text-decoration-underline' : ''); ?>" style="font-weight: <?=$weight;?>;" onclick="loadRowAgentPosition('<? echo $cur_pos['uniqueid'] . "','" . $cur_pos['posicion'] . "','" . $idagente . "','" . $fecha; ?>');" data-toggle="tooltip" data-placement="top" title="<?=$cambioinfo; ?>"><? echo $cur_pos['posicion']; ?></button><?
                                        }
                                        ?>
                                    <span class="superscript"><? echo $horas; ?></span></td>
                                    <?
                                    }
                                    else{

                                    ?>
                                    <td class="poscell" align="center" style="background-color: <?=$color; ?>"><span class="celllink" style="font-weight: <?=$weight;?>;" data-toggle="tooltip" data-placement="top" title="<?=$cambioinfo; ?>"><? echo $posicion; ?></span></td>
                                    <?
                                    }
                                    
                            }
                            
                            ?></tr><?
                        }
                        ?>
                        </tbody>
                        <?
                    }


                    ?>

                    </table>
                    <table id="tblInfo" class="table table-condensed">
                    
                    </table>
                </div>
            </div>
        </div>
      </div>
    </div>
    <!-- End Horizontal Form -->
    
    
    <script type="text/javascript">

    var positions = [{ id: 'XX', name: 'XX', status :'' },<? foreach($posiciones as $position) { echo "{ id : '" . $position['code'] . "', name : '" . $position['code'] . "', status : ''},"; } ?>];
    var agenteslista = [<? foreach($fullagents as $agent) { echo "{ id : '" . $agent['idagente'] . "', name : '" . $agent['shortname'] . "', status : '" . $agent['comment'] . "'},"; } ?>];
    
    $("#frmData").hide();
    $('#myPleaseWait').modal('hide');

    $(document).ready(function(){

        //RefreshList();
        $('#inputAgentShortname.typeahead').typeahead({
            source : agenteslista,
            display : 'name',
            val : 'id',
            onSelect : function(data){
                $("#inputAgenteIdPos").val(data.value);
            }
        });

        $('#inputPositionAgent.typeahead').typeahead({
            source : positions,
            onSelect : function(data){
                $("#typeaheadflag").val("1");
            }
        });
        
        
        $("#btnNewRow").click(function(){
            $("#insertFlag").val(1);
            $("#frmAgentPositionData").show();  
        });
        
        $("#btnCancelPosition").click(function(){
          $("#frmAgentPositionData").hide();  
          $("#inputAgentShortname").val('');
          $("#inputPositionAgent").val('');
          $("#inputAgenteIdPos").val('');
          $("#inputUniqueId").val('');
          $("#inputDatePosition").val('');
        });
        

        // al guardar un cambio en el agent schedule del dia
        $("#btnSubmitAgentPostionData").click(function(){

            //if($("#typeaheadflag").val()=="") return;

            var lidempresa = $("#inputIdEmpresa").val();
            var lidoficina = $("#inputIdOficina").val();
            var luniqueid = $("#inputUniqueId").val();
            var lfecha = $("#inputDatePosition").val();
            var lposicion = $("#inputPositionAgent").val();
            var lagente = $("#inputAgenteIdPos").val();
            var lshortname = $("#inputAgentShortname").val();
            var laslead = $("#inputAsLead").is(':checked')?'LEAD':'';
            var lusuario = $("#inputUsuario").val();
            var linsertflag = $("#insertFlag").val();
            
            $("#frmAgentPositionData").hide();  
            var agent = {
                idempresa : lidempresa,
                idoficina : lidoficina,
                uniqueid : luniqueid,
                insertflag : linsertflag,
                fecha : lfecha,
                posicion : lposicion,
                agenteid : lagente,
                shortname : lshortname,
                aslead : laslead,
                usuario : lusuario
            };
            
            // reset insertflag
            $("#insertFlag").val('');

            $.each(agent, function(index, value) {
                console.log(value);
            });
            
            var request = $.ajax({
                url: '<? echo base_url(); ?>webcunop/switchagente',
                type: 'POST',
                data: agent,
                beforeSend:function(){
                    console.log('sending...');
                    $('#myPleaseWait').modal('show');
                },
                success:function(result){
                    
                    console.log('sent!');

                    var timezone = (new Date().getTimezoneOffset()) / 60;
                    var estacion = $("#inputIATA").val()

                    // call processdaily
                    var data = {
                           idempresa : lidempresa,
                           idoficina : lidoficina,
                           fecha : lfecha,
                           estacion : estacion,
                           usuario : lusuario,
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

                            location.reload();
                        }, 
                        error:function(exception){
                          console.log(exception);}
                        
                      });
                    $('#myPleaseWait').modal('hide');
                  
                }, 
                error:function(exception){console.log(exception);}
                
            });
        });
        
            
            
        return false;
    });


    // borra una asignacion de agente en el schedule del dia
        $("#btnDeleteAgentPositionData").click(function(){

            //if($("#typeaheadflag").val()=="") return;

            var lidempresa = $("#inputIdEmpresa").val();
            var lidoficina = $("#inputIdOficina").val();
            var luniqueid = $("#inputUniqueId").val();
            var lfecha = $("#inputDatePosition").val();
            var lusuario = $("#inputUsuario").val();
            
            $("#frmAgentPositionData").hide();  
            var agent = {
                idempresa : lidempresa,
                idoficina : lidoficina,
                uniqueid : luniqueid,
                fecha : lfecha,
                usuario : lusuario
            };
            
            $.each(agent, function(index, value) {
                console.log(value);
            });
            
            var request = $.ajax({
                url: '<? echo base_url(); ?>webcunop/deleteposagente',
                type: 'POST',
                data: agent,
                beforeSend:function(){
                    console.log('sending...');
                    $('#myPleaseWait').modal('show');
                },
                success:function(result){
                    
                    console.log('sent!');
                    //console.log(result);
                    //RefreshScheduler();
                    //RefreshFlights();
                    location.reload();

                    $('#myPleaseWait').modal('hide');
                  
                }, 
                error:function(exception){console.log(exception);}
                
            });
        })

    

    function loadRowAgentPosition(uniqueid, posicion, idagente, fecha)
    {
        if(<? echo $isadmin=='1'?0:1; ?>)
            return;
        $("#frmAgentPositionData").show();
        $("html, body").animate({ scrollTop: 0 }, "fast");

        idempresa = $("#inputIdEmpresa").val();
        idoficina = $("#inputIdOficina").val();
        
        var infoData = { 
                  uniqueid : uniqueid,
                  idempresa : idempresa,
                  idoficina : idoficina,
                  posicion : posicion,
                  agenteid : idagente,
                  fecha : fecha
                 };
        $.ajax({
          url: '<? echo base_url(); ?>webcunop/loadagentschedule',
          type: 'POST',
          data : infoData,
          beforeSend:function(){
            console.log('loading data...'); 
            $('#myPleaseWait').modal('show');
          },
          success:function(data){
            console.log('show data...'); 
            if(data.length > 0)
            {
              //LoadAgentsAssigned(agents);
              console.log(data[0].idagente);
              $("#frmAgentPositionData").show();
              $("#inputAgentShortname").val(data[0].shortname);
              $("#inputPositionAgent").val(data[0].posicion);
              $("#inputAgenteIdPos").val(data[0].idagente);
              $("#inputUniqueId").val(data[0].uniqueid);
              $("#inputDatePosition").val(data[0].fecha);
            }
          
            $('#myPleaseWait').modal('hide');
          }
        });
    }
</script>