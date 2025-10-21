
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
            margin: 1em 0;
            border-collapse: collapse;
            border: 0.1em solid #d6d6d6;
        }

        .tablecontainer {
          width: 900px;
          height: 600px;
          padding-left:0px;
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

        th,
        td {
            padding: 0.25em 0.5em 0.25em 1em;
            vertical-align: text-top;
            text-align: left;
            white-space: nowrap;            
            font-family: Arial;
        }

        th {
            vertical-align: bottom;
            background-color: #666;
            color: #fff;
        }

        .cell-selected {
            border: 4px dashed #ffcccc !important;
        }

        tr:nth-child(even) th[scope=row] {
        background-color: #f2f2f2;
        }

        tr:nth-child(odd) th[scope=row] {
        background-color: #fff;
        }

        tr:nth-child(even) {
        background-color: rgba(0, 0, 0, 0.05);
        }

        tr:nth-child(odd) {
        background-color: rgba(255, 255, 255, 0.05);
        }

        td:nth-of-type(2) {
        font-style: italic;
        }

        th:nth-of-type(3),
        td:nth-of-type(3) {
        text-align: right;
        }

        /* Fixed Headers */

        th {
        position: -webkit-sticky;
        position: sticky;
        top: 0;
        z-index: 2;
        }

        th[scope=row] {
        position: -webkit-sticky;
        position: sticky;
        left: 0;
        z-index: 1;
        }

        th[scope=row] {
        vertical-align: top;
        color: inherit;
        background-color: inherit;
        background: linear-gradient(90deg, transparent 0%, transparent calc(100% - .05em), #d6d6d6 calc(100% - .05em), #d6d6d6 100%);
        }

        table:nth-of-type(2) th:not([scope=row]):first-child {
        left: 0;
        z-index: 3;
        background: linear-gradient(90deg, #666 0%, #666 calc(100% - .05em), #ccc calc(100% - .05em), #ccc 100%);
        }

        /* Strictly for making the scrolling happen. */

        th[scope=row] + td {
        min-width: 24em;
        }

        th[scope=row] {
        min-width: 20em;
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

        .sticky-col {
            position: sticky;
            left: 0;
            background-color: white;
            z-index: 2;
        }
        
        /* Ensure the first column is always above other content */
        thead .sticky-col {
            z-index: 3;
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
            
            <div class="row">
                <div class="col-12">
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
                    <!-- END LOAD AGENT POSITIONS -->
                </div>
            </div>
            <div class="row" style="margin-top: 2em;">                
                <div class="col-sm-8">
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
                </div>
                <div class="col-sm-4">
                    <input type="text" id="inputSearch" class="form-control" placeholder="Search agent" />
                </div>
            </div>
            <div class="row" style="margin-top: 2em;">
                <div class="col-md-12 tablecontainer">
                    <table id="tbMonth" class="table table-striped table-bordered">
                        <?
                        if($fullagents){
                            $estafecha = $fechaini;
                            ?>
                            <thead>
                                <tr>
                                    <th class="vuelos headcol"><? echo date('M',$estafecha); ?></th><?
                            while($estafecha <= $fechafin){
                                ?>
                                <th style="word-wrap: normal;"><? echo date('D d',$estafecha); ?></th>
                                <?
                                 $estafecha += (24*3600);
                            }
                            ?>
                                </tr>
                            </thead>
                            <tbody>
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

        $('.tablecontainer').width($('.tablecontainer').parent().width());

        RefreshList();


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

          $("#" + $("#inputUniqueId").val()).parent().removeClass('cell-selected');
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

            $("#" + luniqueid).parent().removeClass('cell-selected');
            
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
                success:function(row){
                    
                    console.log('sent!');

                    // reload agent row
                    var changed_cell = $("#ag_"+row.idagente);
                    // get parent row
                    var parent_row = changed_cell.parent();

                    // add cells to row
                    var tableRowContent = '<th scope="row" class="sticky-col">' + row.idagente + '  ' +row.shortname + '</td>';
                    tableRowContent += '<td id="ag_' + row.idagente + '" style="display:none;">' + row.idagente + '</td>';
                    tableRowContent += '<td style="display:none;">' + row.shortname + '</td>';

                    $.each(row.resultado, function(index, cell){
            
                        tableRowContent += FillAgent(cell, row);
                        
                    });        

                    // add row to table
                    parent_row.replaceWith('<tr>' + tableRowContent + '</tr>');

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
                                          
                      var request = $.ajax({
                        url: 'fillcunopdate/processdates',
                        type: 'POST',
                        data: data,
                        beforeSend:function(){                          
                          $("#divloading").show();
                        },
                        success:function(result){
                          //location.reload();
                          console.log('sent!');
                          console.log(result);
                          // update cell
                          var uniqueid = $("#inputUniqueId").val();
                          var cell = $("#" + uniqueid);                            
                        }, 
                        error:function(exception){
                          console.log(exception);}
                        
                      });
                    $('#myPleaseWait').modal('hide');
                  
                }, 
                error:function(exception){console.log(exception);}
                
            });
        });
        
        $("#inputSearch").keyup(function(){
            // if value length is more than 5 then search
            if($("#inputSearch").val().length > 5){
                SearchAgent($("#inputSearch").val());   
            }            
        });



        return false;
    });

    function SearchAgent(agentstring){
        // str to uppercase
        agentstring = agentstring.toUpperCase();
        console.log('searching...' + agentstring);
        // search agent in table
        $("#tbMonth tbody").find("tr").each(function(){
            var agent = $(this).find("td:eq(1)").text();
            if(agent.indexOf(agentstring) > 0 ){                
                console.log('found! ' + agent);
                $(this).get(0).scrollIntoView(false);
                //$(this).get(0).scrollTop = 10;

                
                /*
                var ypos = $(this).offset().top;
                
                $('#tbMonth').animate({
                    scrollTop: $('#tbMonth').scrollTop()+ypos
                }, 500);
                    */
                //$('html, body').animate({scrollTop:row}, 'slow');
                // highlight row
                //$(this).addClass('cell-selected');
            }
        });
    }

    function RefreshList(){

        $.ajax({
                url: '<? echo base_url(); ?>MonthlyRoster/asyncloadmonthlyroster',
                type: 'POST',
                data: {
                    selectedmonth : $("#inputSelectedMonth").val()                            
                },
                beforeSend:function(){
                    console.log('geting monthly info...');
                    $('#myPleaseWait').modal('show');
                },
                success:function(result){
                    
                    console.log('sent!');
                    console.log(result);

                    var table = $('#tbMonth tbody');
                    
                    
                    // foreach all rows
                    $.each(result, function(index, row){
                        
                        console.log(row.shortname);

                        FillAgentMonth(row, table);

                    });
                    $('#myPleaseWait').modal('hide');
                  
                }, 
                error:function(exception){console.log(exception);}
                
            });

        

    }

    function FillAgentMonth(row, table){
        
        // add cells to row
        var tableRowContent = '<td scope="row" class="sticky-col">' + row.idagente + '  ' +row.shortname + '</td>';
        tableRowContent += '<td id="ag_' + row.idagente + '" style="display:none;">' + row.idagente + '</td>';
        tableRowContent += '<td style="display:none;">' + row.shortname + '</td>';

        $.each(row.resultado, function(index, cell){
            
            tableRowContent += FillAgent(cell, row);
            
        });        

        // add row to table
        table.append('<tr>' + tableRowContent + '</tr>');
    }


    function FillAgent(cell, row){

        var tableRowContent = ''
        
        if(cell.asignaciones.agent_data.length > 0){

            var fecha = cell.fecha;

            var uniqueid = cell.asignaciones.agent_data[0].uniqueid;
            var posicion = '';
            var asignacion  = '';
            var horas = 0;

            var updatedlast = [];

            for(var i=0; i < cell.asignaciones.agent_data.length; i++){
                var cur_posicion = cell.asignaciones.agent_data[i];
                if(posicion.substr(0,2) == 'XX' || posicion.substr(0,2) == 'V-'){
                    posicion = cur_posicion.posicion + ( ( i + 1 == cell.asignaciones.agent_data.length ) ? '' : ',' );
                }
                else if(posicion == '' && cur_posicion.posicion != 'XX'){
                    posicion = cur_posicion.posicion + ( ( i + 1 == cell.asignaciones.agent_data.length ) ? '' : ',' );
                }
                else{
                    posicion = cur_posicion.posicion + ( ( i + 1 == cell.asignaciones.agent_data.length ) ? '' : ',' );
                }

                if(cur_posicion.workday == 'FT' && cur_posicion.regular == 1)
                    horas += 8;
                else if(cur_posicion.workday == 'PT' && cur_posicion.regular == 1)
                    horas += 4;

                // if cur_posicion.usuario is in updatedlast array, then update it
                if(updatedlast.indexOf(cur_posicion.usuario) == -1){
                    updatedlast.push(cur_posicion.usuario);                        
                }
                
                asignacion = asignacion == '' ? cur_posicion.asignacion : asignacion;

            }

            horas = horas == 0 ? '' : horas;
            var sidagente = row.idagente;
            var cambioinfo = '';
            var sclass = '';
            var scolor = '';
            var idagentecambio = '';
            var agenteinicial = '';
            var posicionsolicitada = '';
            var agentecambio = '';
            var posicioninicial = '';
            var status = '';

            // trade 1

            if( cell.asignaciones.trade1 ){
                for(index = 0; index < cell.asignaciones.trade1.length; index ++){
                    $cur_trade = cell.asignaciones.trade1[index];                    
                    if( $cur_trade.idagente == sidagente ){
                        idagentecambio = $cur_trade.idagentecambio;
                        agenteinicial = $cur_trade.shortname;
                        posicionsolicitada = $cur_trade.posicionsolicitada;
                        agentecambio = $cur_trade.agentecambio;
                        posicioninicial = $cur_trade.posicioninicial;
                        status = $cur_trade.status;

                        otheragent = '';
                        otherpos = '';

                        if(status){
                            if( idagentecambio == sidagente){
                                otheragent = agenteinicial;
                                otherpos = posicionsolicitada;
                            }
                            else{
                                otheragent = agentecambio;
                                otherpos = posicioninicial;
                            }
                        }

                        cambioinfo += (status == '') ? '' : $cur_trade.tipocambio + ' with ' + otheragent + ' for ' + otherpos + '\n';
                        if( $cur_trade.usuarioflagged != '')
                            cambioinfo += 'SPV flagged ' + $cur_trade.usuarioflagged + '\n';
                        if( updatedlast.length > 0 )
                            cambioinfo += 'SPV updated ' + updatedlast.join(', ') + '\n';
                        sclass = "agenteinicial";
                    }
                }
            }

            // end trade 1

            // trade 2
            if( cell.asignaciones.trade2 ){
                for( index = 0; index < cell.asignaciones.trade2.length; index++ ){
                    $cur_trade = cell.asignaciones.trade2[index];                    
                    if( $cur_trade.idagentecambio == sidagente ){
                        idagentecambio = $cur_trade.idagentecambio;
                        agenteinicial = $cur_trade.shortname;
                        posicionsolicitada = $cur_trade.posicionsolicitada;
                        agentecambio = $cur_trade.agentecambio;
                        posicioninicial = $cur_trade.posicioninicial;
                        status = $cur_trade.status;

                        otheragent = '';
                        otherpos = '';

                        if(status){
                            if( idagentecambio == sidagente){
                                otheragent = agenteinicial;
                                otherpos = posicionsolicitada;
                            }
                            else{
                                otheragent = agentecambio;
                                otherpos = posicioninicial;
                            }
                        }

                        cambioinfo += (status == '') ? '' : $cur_trade.tipocambio + ' with ' + otheragent + ' for ' + otherpos + '\n';
                        if( $cur_trade.usuarioflagged != '')
                            cambioinfo += 'SPV flagged ' + $cur_trade.usuarioflagged + '\n';
                        if( updatedlast.length > 0 )
                            cambioinfo += 'SPV updated ' + updatedlast.join(', ') + '\n';
                        sclass = "agenterecibe";
                    }
                }
            }

            // end trade 2

            var status = cell.asignaciones.trade1.length > 0 ? cell.asignaciones.trade1[0].status : '';

            var color = posicion == 'XX' || posicion == '' ? '' : '#eeeeee';
            var weight = 'normal';
            if(status && cell.asignaciones.trade1[0].tipocambio == 'Cover'){
                scolor = '#ABEBC6';
                weight = 'bold';
            }
            else if(status && cell.asignaciones.trade1[0].tipocambio == 'Switch'){
                scolor = '#FADBD8';
                weight = 'bold';
            }

            // format cell
            var tcell = '<td class="poscell ' + sclass + '" align="center" style="position: relative;background-color: ' + scolor + '">';
            $.each(cell.asignaciones.agent_data, function( index,$cur_posicion ){
                //console.log(fecha + ' ' + $cur_posicion.posicion + ' ' + cambioinfo);
                tcell += '<button id="' + $cur_posicion.uniqueid + '"class="btn btn-link celllink ' + ( asignacion == 'LEAD' ? 'text-decoration-underline' : '' ) +
                        '" style="font-weight: ' + weight + '" onclick="loadRowAgentPosition(\'' + $cur_posicion.uniqueid + "','" + $cur_posicion.posicion + 
                        "','" + sidagente + "','" + fecha + "');\" data-toggle='tooltip' data-placement='top' title='" + cambioinfo + "'>" +
                        $cur_posicion.posicion + '</button>';
            });
            tcell += '<span class="superscript">' + horas + '</span></td>';
                                
            tableRowContent += tcell;
            }

    return tableRowContent;
    }

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
                url: '<? echo base_url(); ?>timeswitch/deleteposagente',
                type: 'POST',
                data: agent,
                beforeSend:function(){
                    console.log('sending...');
                    $('#myPleaseWait').modal('show');
                },
                success:function(row){
                    
                    console.log('sent!');

                    // reload agent row
                    var changed_cell = $("#ag_"+row.idagente);
                    // get parent row
                    var parent_row = changed_cell.parent();

                    // add cells to row
                    var tableRowContent = '<th scope="row" class="sticky-col">' + row.idagente + '  ' +row.shortname + '</td>';
                    tableRowContent += '<td id="ag_' + row.idagente + '" style="display:none;">' + row.idagente + '</td>';
                    tableRowContent += '<td style="display:none;">' + row.shortname + '</td>';

                    $.each(row.resultado, function(index, cell){
            
                        tableRowContent += FillAgent(cell, row);
                        
                    });        

                    // add row to table
                    parent_row.replaceWith('<tr>' + tableRowContent + '</tr>');

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
        var idempresa = $("#inputIdEmpresa").val();
        var idoficina = $("#inputIdOficina").val();

        //$("html, body").animate({ scrollTop: 0 }, "fast");

        $("#" + uniqueid).parent().addClass('cell-selected');
               
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