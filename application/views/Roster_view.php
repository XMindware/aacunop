        <?
        if(!isset($fecha) || $fecha=='')
            $fecha = date('Y-m-d');
    ?>
    <script src="<? echo base_url(); ?>assets/js/typeahead.min.js"></script>
    <button onclick="topFunction()" id="myBtn" title=""><img src="assets/images/Circled Up-50.png" /></button>


    <div class="container">
        <div class="panel-body">
            <div class="panel-heading">
                <div class="row">
                <h2><span class="label label-primary">Query Weekly Roster</span></h2>
                <input type="hidden" id="inputIdEmpresa" value="<? echo $idempresa; ?>" />
                <input type="hidden" id="inputIdOficina" value="<? echo $idoficina; ?>" />
                <input type="hidden" id="inputUsuario" value="<? echo $idusuario; ?>" />
                <input type="hidden" id="inputEmployeeId" value="0"/>
                <input type="hidden" id="inputUniqueId"  />
                <input type="hidden" id="inputCurrentRow"  />
                </div>
            </div>      

            <div id="divReport" style="display: none;">
                <fieldset>
                    <legend>Roster Report</legend>
                    
                    
                    <div class='row'>
                        <div class='col-sm-6'>
                            <div class='form-group'>
                                <button type="reset" id="btnDownloadCSV" class="btn btn-info">Get Excel</button>
                                <button type="reset" id="btnResetReport" class="btn btn-warning">Restart</button>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <!--
                Employee ID
            -->
            <div id="divStep1">
                <fieldset>
                    <legend>Please type in the Employee you want to query</legend>
                    
                    <div class='row'>
                        <div class='col-sm-3'>
                            <div id='dividagente' class='form-group'>
                                <label for="inputAgent">By Employee</label>
                                <input class="form-control typeahead" id="inputAgent" name="inputAgent" placeholder="Search Agents" size="30" type="text" />
                                <div class='form-group has-success'>
                                    <span id="inputShortname" class="help-block"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-sm-12'>
                            <div class='form-group'>
                                <div class="checkbox">
                                    <label>
                                      <input type="checkbox" id="checkboxAllAgents" value="option1" />
                                        All Station Agents
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='row' id="inputShowError" style="display: none;">
                        <div class='col-sm-12'>
                            <div class='form-group has-warning'>
                                <span id="helpBlock2" class="help-block">Selected Employee ID does not exists</span>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-sm-6'>
                            <div class='form-group'>
                                <button type="button" id="btnSubmit1" class="btn btn-success" disabled>Next</button>
                                <button type="reset" id="btnReset1" class="btn btn-warning">Clear</button>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>

            <!--
                Dates
            -->
            <div id="divStep2" style="display: none;">
                <fieldset>
                    <legend>Select the Week to view</legend>
                    
                    <div class='row'>
                        <input type='hidden' id='inputSelector' name='inputSelector' />
                        <div class='col-sm-4'>
                            <div class='form-group'>
                                <label for="inputFecha">Select Date</label>
                                <input class="form-control" id="inputFecha" name="inputFecha"  value='<? echo $fecha; ?>' required size="30" type="date" />
                            </div>
                        </div>
                        <div class='col-sm-4'>
                            <div class='form-group'>
                                <label for="inputWeek">Selected Week</label>
                                <input class="form-control" id="inputWeek" name="inputWeek" value='<? echo $week; ?>' size="30" type="text" />
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-sm-12'>
                            <div class='form-group'>
                                <div class="checkbox">
                                    <label>
                                      <input type="checkbox" id="checkboxAllWeeks" value="option1" />
                                        All Weeks of selected month
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-sm-6'>
                            <div class='form-group'>
                                <button type="button" id="btnPrev2" class="btn btn-info">Prev</button>
                                <button type="button" id="btnSubmit2" class="btn btn-success">Next</button>
                                <button type="reset" id="btnReset2" class="btn btn-warning">Clear</button>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>

            <!--
                Weekday
            -->
            <div id="divStep3" style="display: none;">
                <fieldset>
                    <legend>Filter by Weekday</legend>
                    <div class='row'>
                        <div class='col-sm-6'>
                            <div class='form-group'>
                                <label for="inputWeekday">By Day</label>
                                <select id="inputWeekday" name="inputWeekday" class="form-control">
                                    <option value=""></option>
                                    <option value="1" >MON</option>
                                    <option value="2" >TUE</option>
                                    <option value="3" >WED</option>
                                    <option value="4" >THU</option>
                                    <option value="5" >FRI</option>
                                    <option value="6" >SAT</option>
                                    <option value="0" >SUN</option>  
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-sm-6'>
                            <div class='form-group'>
                                <button type="button" id="btnPrev3" class="btn btn-info">Prev</button>
                                <button type="button" id="btnSubmit3" class="btn btn-success">Next</button>
                                <button type="reset" id="btnReset3" class="btn btn-warning">Clear</button>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>

            <!--
                Posicion
            -->
            <div id="divStep4" style="display: none;">
                <fieldset>
                    <legend>Filter by position</legend>
                    
                    <div class='row'>
                        <div class='col-sm-3'>
                            <div class='form-group'>
                                <label for="inputPosicion">Position</label>
                                <select id="inputPosicion" name="inputPosicion" class="form-control">
                                    <option value=""></option>
                                     <?
                                    foreach($posiciones as $position)
                                    {
                                      ?>
                                        <option value="<? echo $position['code']; ?>" <? echo $inputPosicion== $position['code'] ? 'selected' : ''; ?>><? echo $position['description']; ?></option>                                        

                                      <?
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-sm-6'>
                            <div class='form-group'>
                                <button type="button" id="btnPrev4" class="btn btn-info">Prev</button>
                                <button type="button" id="btnSubmit4" class="btn btn-success">Next</button>
                                <button type="reset" id="btnReset4" class="btn btn-warning">Clear</button>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="panel-body" id="divMain" style="display: block;">
            <div class="row">
                <div class="col-md-12">
                    <center><h3 id="txtFechas"></h3></center>
                    
                    <!-- DETAIL -->
                    <table class="table table-condensed table-bordered" id="tblRoster">
                        
                    </table>
                </div>
            </div>
      </div>
      <div class='row' id="divNoRows" style="display: none;">
            <div class='col-sm-12'>
                <div class='form-group has-warning'>
                    <span id="helpBlock2" class="help-block">No information available with this selection</span>
                </div>
            </div>
      </div>

    </div>

    <script type="text/javascript">
    
    $('#myPleaseWait').modal('show');

    $("#frmAgentData").show();
    $("#frmVueloData").hide();
    $("#divAddNewHere").hide();
    $("#inputShowError").hide();
    
    $('#frmLeadPositionData').hide()
    $("#frmAgentPositionData").hide();
    $('#myPleaseWait').modal('hide');


    $("#btnSubmit1").attr("disabled",true);
    $(document).ready(function(){

        $('#myPleaseWait').modal('hide');
        var positions = [<? foreach($posiciones as $position) { echo "'" . $position['code'] . "',"; } ?>];
        var agenteslista = [<? foreach($fullagents as $agent) { echo "{ id : '" . $agent['idagente'] . "', name : '" . $agent['shortname'] . "'},"; } ?>];

        $('#inputAgent.typeahead').typeahead({
            source : agenteslista,
            display : 'name',
            val : 'id',
            onSelect : function(item)
            {
                console.log(item.value);
                $("#inputEmployeeId").val(item.value);
                var lidempresa = $("#inputIdEmpresa").val();
                var lidoficina = $("#inputIdOficina").val();
                var lidagente = $("#inputEmployeeId").val();

                var agent = {
                    idempresa : lidempresa,
                    idoficina : lidoficina,
                    agenteid : lidagente
                };

                var request = $.ajax({
                    url: 'roster/validateagentid',
                    type: 'POST',
                    data: agent,
                    beforeSend:function(){
                        console.log('sending...');
                        $('#myPleaseWait').modal('show');
                    },
                    success:function(result){
                        console.log(result);
                        console.log(result.status == 'OK');
                        // regresa el resultado
                        if(result.status == 'OK')
                        {
                            $("#checkboxAllAgents").attr('checked', false);
                            $("#dividagente").addClass('has-success').removeClass('has-error');
                            $("#btnSubmit1").attr("disabled",false);
                            $("#inputShortname").text(result.shortname);
                            $("#inputShowError").hide();
                        }
                        else
                        {
                            //$("#dividagente").toggleClass('form-group has-error');
                            $("#dividagente").addClass('has-error').removeClass('has-success');
                            $("#inputShortname").text('');
                            $("#btnSubmit1").attr("disabled",true);
                            // lanzar tooltip de aviso que el numero es incorrecto
                            $("#inputShowError").show();
     
                        }
                        $('#myPleaseWait').modal('hide');
                    }
                });
            }
        });

        // bandera de mensaje de error 
        var hayErrorFiltros = 0;

        Date.prototype.getWeek = function() {
            var onejan = new Date(this.getFullYear(),0,1);
            var today = new Date(this.getFullYear(),this.getMonth(),this.getDate());
            var dayOfYear = ((today - onejan +1)/86400000);
            return Math.ceil(dayOfYear/7)
        };

        function getDateByWeek( weeks, year ) {
           var date = new Date(year, 0, 1);
           var dayNum = date.getDay();
           var requiredDate = --weeks * 7;
           // If 1 Jan is Friday to Sunday, go to next week 
           if (((dayNum!=0) || dayNum > 4)) {
               requiredDate += 7;
            }
          // Add required number of days
           date.setDate(1 - date.getDay() + ++requiredDate );
           var m = date.getMonth() + 1;
           m = m>9 ? m : '0' + m;
           var d = date.getDate()>9 ? date.getDate() : '0' + date.getDate(); 
           return date.getFullYear() + '-' + m + '-' + d;
         }

        function getFormatedDate(fecha)
        {
            var m = fecha.getMonth() + 1;
            m = m>9 ? m : '0' + m;
            var d = fecha.getDate()>9 ? fecha.getDate() : '0' + fecha.getDate(); 
            return fecha.getFullYear() + '-' + m + '-' + d;
        }


        $('#inputFecha').on('change', function() {
            var fecha = new Date(this.valueAsDate);
            console.log(fecha.getDay());
            var currentDay = fecha.getDay();
            var distance = 1 - currentDay;
            fecha.setDate(fecha.getDate() + distance);

            $("#inputFecha").val(getFormatedDate(fecha));
            $('#inputSelector').val(1);
            $('#inputWeek').val(fecha.getWeek());
        });

        $('#inputWeek').on('change', function() {
            var week = $('#inputWeek').val();
            var fecha =  new Date();
            var year = fecha.getFullYear();

            // calcular el lunes de esa semana

            $('#inputFecha').val(getDateByWeek(week,year));
        });

        $('#checkboxAllAgents').change(function() {
            if(this.checked) {

                // vacia el campo de agente id y habilita el submit
                $("#inputShortname").text('');
                $('#inputAgentID').val('0');
                $("#inputShowError").hide();
                $("#btnSubmit1").attr("disabled",false);
            }

        });

        // validar horas filtro .. hora 1
        $('#inputFromTime').on('change', function() {

            var isValid = /^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$/.test($('#inputFromTime').val());

            if(!isValid) return;

            console.log($('#inputToTime').val());
            // si no ha capturado la hora 2 entonces termina
            if($('#inputToTime').val() == '')
                return;

            // si ya existe
            var hora1 = $('#inputFromTime').val();
            var hora2 = $('#inputToTime').val();

            if(hora1<=hora2)
            {
                // todo en orden
                $("#inputShowError2").hide();
                $("#inputFromTime").addClass('has-success').removeClass('has-error');
                $("#inputToTime").addClass('has-success').removeClass('has-error');
                $("#btnSubmit5").attr("disabled",false);
            }
            else
            {
                // alerta mediante un msj que la hora es incorrecta
                $("#inputShowError2").show();
                $("#inputFromTime").addClass('has-error').removeClass('has-success');
                $("#inputToTime").addClass('has-error').removeClass('has-success');
                $("#btnSubmit5").attr("disabled",true);
            }            
        });

        // validar horas filtro .. hora 1
        $('#inputToTime').on('change', function() {
            
            var isValid = /^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$/.test($('#inputToTime').val());

            if(!isValid) return;

            // si no ha capturado la hora 2 entonces termina
            if($('#inputFromTime').val() == '')
                return;

            // si ya existe
            var hora1 = $('#inputFromTime').val();
            var hora2 = $('#inputToTime').val();

            if(hora1<=hora2)
            {
                // todo en orden
                $("#inputShowError2").hide();
                $("#inputFromTime").addClass('has-success').removeClass('has-error');
                $("#inputToTime").addClass('has-success').removeClass('has-error');
                $("#btnSubmit5").attr("disabled",false);
            }
            else
            {
                // alerta mediante un msj que la hora es incorrecta
                $("#inputShowError2").show();
                $("#inputFromTime").addClass('has-error').removeClass('has-success');
                $("#inputToTime").addClass('has-error').removeClass('has-success');
                $("#btnSubmit5").attr("disabled",true);
            }            
        });

        $('#inputAgent1.typeahead').typeahead({
            source : agenteslista,
            display : 'name',
            val : 'id'
        });

        $('#inputLeadShortname.typeahead').typeahead({
            source : agenteslista,
            display : 'name',
            val : 'id',
            onSelect : function(item)
            {
                console.log(item.value);
                $("#inputAgenteIdPos").val(item.value);

              

            }
        });

        $('#inputAgent2.typeahead').typeahead({
            source : agenteslista,
            display : 'name',
            val : 'id'
        });

        $('#inputAgentX.typeahead').typeahead({
            source : agenteslista,
            display : 'name',
            val : 'id'
        });

        $('#inputPositionAgent.typeahead').typeahead({
            source : positions,
        });

        $('#inputAgentShortname.typeahead').typeahead({
            source : agenteslista,
            display : 'name',
            val : 'id',
            onSelect : function(item)
            {
                console.log(item.value);
                $("#inputAgenteIdPos").val(item.value);

                var lidempresa = $("#inputIdEmpresa").val();
                var lidoficina = $("#inputIdOficina").val();

                var agent = {
                    idempresa : lidempresa,
                    idoficina : lidoficina,
                    idagente : item.value
                };
                
                
                var request = $.ajax({
                    url: 'webcunop/posicionesparaagente',
                    type: 'POST',
                    data: agent,
                    beforeSend:function(){
                        console.log('sending...');
                        $('#myPleaseWait').modal('show');
                    },
                    success:function(result){
                        
                        console.log('sent!');
                        //console.log(result);

                        var newpos = [];
                        for(var i=0;i<result.length;i++)
                        {
                            newpos.push({id : result[i]['code'], name : result[i]['description']});
                            console.log(result[i]['code']); 
                        }

                        $('#myPleaseWait').modal('hide');
                        var options = {
                            source : newpos,
                            display : 'name',
                            val : 'id'
                        };
                        $('#inputPositionAgent').typeahead('destroy').typeahead(options);
                        //autocomplete.data('typeahead').source = newpos;
                        //$('#inputPositionAgent').source = (null);
                        
                        //$('#inputPositionAgent.typeahead').typeahead({
                        //    source : newpos,
                        //    display : 'name',
                        //    val : 'id'
                        //});

                    }, 
                    error:function(exception){console.log(exception);}
                    
                });

            }
        });

        $("#btnSubmit1").click(function(){
            // employee id -> fechas
            $("#divStep1").hide();
            $("#divStep2").show();
        });

        $("#btnSubmit2").click(function(){
            // fechas -> weekday
            $("#divStep2").hide();
            $("#divStep3").show();
        });

        $("#btnPrev2").click(function(){
            // weekday -> fechas
            $("#divStep2").hide();
            $("#divStep1").show();
        });

        $("#btnSubmit3").click(function(){
            // weekday -> posicion
            $("#divStep3").hide();
            $("#divStep4").show();
        });

        $("#btnPrev3").click(function(){
            // posicion -> weekday
            $("#divStep3").hide();
            $("#divStep2").show();
        });

        $("#btnSubmit4").click(function(){
            // posicion -> time range
            $("#divStep4").hide();
            $("#divReport").show();
            //RefreshScheduler();
            if($("#checkboxAllWeeks")[0].checked)
                GetMonthRoster();
            else
                RefreshScheduler();
        });

        $("#btnPrev4").click(function(){
            // time range -> posicion
            $("#divStep4").hide();
            $("#divStep3").show();
        });

        $("#btnSubmit5").click(function(){
            // posicion -> time range
            $("#divStep5").hide();
            $("#divReport").show();
            //RefreshScheduler();
            if($("#checkboxAllWeeks")[0].checked)
                GetMonthRoster();
            else
                RefreshScheduler();
        });

        $("#btnPrev5").click(function(){
            // time range -> posicion
            $("#divStep5").hide();
            $("#divStep4").show();
        });

        $("#btnReset5").click(function(){
            // time range -> posicion
            $("#inputFromTime").val('');
            $("#inputToTime").val('');
        });

        $("#btnResetReport").click(function(){
            //return;
            $("#divMain").hide();
            $("#divReport").hide();
            $("#divStep1").show();
            $("#divNoRows").hide();
        });

        $("#btnDownloadCSV").click(function(){
            //return;
            GetCSV();
        });
     
            
        return false;
    })

function RefreshScheduler()
{
    var lidempresa = $("#inputIdEmpresa").val();
    var lidoficina = $("#inputIdOficina").val();
    var lfiltroweekday = $("#inputWeekday").val();
    var lfiltroposicion = $("#inputPosicion").val();
    var lfecha = $("#inputFecha").val();
    var lidagent = $("#inputAgentID").val();
    var lhora1 = $("#inputFromTime").val();
    var lhora2 = $("#inputToTime").val();

    var fields = {
        idempresa : lidempresa,
        idoficina : lidoficina,
        fechaini : lfecha,
        filtroagente : lidagent==0?'':lidagent,
        filtroweekday : lfiltroweekday,
        filtroposicion : lfiltroposicion,
        filtrohora1 : lhora1,
        filtrohora2 : lhora2
    }
    var request = $.ajax({
        url: 'roster/asyncroster',
        type: 'POST',
        data: fields,
        beforeSend:function(){
            console.log('sending...');
            $('#myPleaseWait').modal('show');
        },
        success:function(result){
            
            console.log('lines returned ' + Object.keys(result).length) -1;
            if((Object.keys(result).length -1 ) <= 0)
            {   
                $("#divNoRows").show();
            }
            else
            {
                $("#divMain").show();
                //console.log(result);
                $('#tblRoster tr').remove();
                var html = '';
                var head = '<thead>';

                var ishead = 1;

                var today = moment(lfecha);
                lidagent = (lidagent==0 || lidagent=='')?'':lidagent;

                if((Object.keys(result).length -1 ) > 0)
                {
                    $.each(result,function(row,agents){
                        var today = moment(lfecha);

                        if(ishead)
                        {
                            head = "<tr>" +
                                   "<th>Date</th>" +
                                   (lidagent==''?"<th>ID</th>":'') +
                                   (lidagent==''?"<th>Shortname</th>":'') +
                                   (lidagent==''?"<th>Workday</th>":'') +
                                   "<th>Day</th>" + 
                                   "<th>Position</th>";
                        }
                        else
                        {
                        html    += "<tr>" +
                                   "<td class='text-nowrap'>" + mfecha(today,agents[4]) + "</td>" + 
                                   (lidagent==''?"<td class='text-nowrap'>" + agents[0] + "</td>":'') +
                                   (lidagent==''?"<td class='text-nowrap'>" + agents[2] + "</td>":'') +
                                   (lidagent==''?"<td class='text-nowrap'>" + agents[3] + "</td>":'') +
                                   
                                   "<td>" + agents[4] + "</td>" + 
                                   "<td>" + agents[5] + "</td>";
                        }

                        ishead = 0;
                        html +="</tr>";
                    });
                }

                $("#txtFechas").text(moment($("#inputFecha").val()).format("MMMM Do") + " to " + moment($("#inputFecha").val()).add(6,'days').format("MMMM Do YYYY"));
                $("#tblRoster").append(head);
                $("#tblRoster").append(html);
            }
            $('#myPleaseWait').modal('hide');
        }, 
        error:function(exception){console.log(exception);}
        
    });
}

function GetMonthRoster()
{
    var lidempresa = $("#inputIdEmpresa").val();
    var lidoficina = $("#inputIdOficina").val();
    var lfiltroweekday = $("#inputWeekday").val();
    var lfiltroposicion = $("#inputPosicion").val();
    var lfecha = $("#inputFecha").val();
    var lidagent = $("#inputAgentID").val();
    var lhora1 = $("#inputFromTime").val();
    var lhora2 = $("#inputToTime").val();

    var fields = {
        idempresa : lidempresa,
        idoficina : lidoficina,
        fechaini : lfecha,
        filtroagente : lidagent==0?'':lidagent,
        filtroweekday : lfiltroweekday,
        filtroposicion : lfiltroposicion,
        filtrohora1 : lhora1,
        filtrohora2 : lhora2
    }
    var request = $.ajax({
        url: 'roster/asyncrostermonth',
        type: 'POST',
        data: fields,
        beforeSend:function(){
            console.log('sending...');
            $('#myPleaseWait').modal('show');
        },
        success:function(result){
            
            console.log('lines returned ' + Object.keys(result).length) -1;
            if((Object.keys(result).length -1 ) == 0)
            {   
                $("#divNoRows").show();
            }
            else
            {
                $("#divMain").show();
                //console.log(result);
                $('#tblRoster tr').remove();

                var html = '';
                var head = '';
                lidagent = (lidagent==0 || lidagent=='')?'':lidagent;

                $.each(result, function(row,weeks){
                
                    html = '<tbody>';
                    head = '<thead>';

                    var data = weeks[1];

                   

                    var ishead = 1;
                    if((Object.keys(data).length -1 ) > 0)
                    {
                        $.each(data,function(row,agents){

                            var today = moment(weeks[0]);
                            var size = Object.keys(agents).length;

                            if(ishead)
                            {
                                head += "<tr>" +
                                       "<th colspan=" + size + ">" + moment(weeks[0]).format('MMMM Do YYYY, wo') + " Week</th></tr>";
                                head += "<tr>" +
                                       "<th>Date</th>" +
                                       (lidagent==''?"<th>ID</th>":'') +
                                       (lidagent==''?"<th>Shortname</th>":'') +
                                       (lidagent==''?"<th>Workday</th>":'') +
                                       "<th>Day</th>" + 
                                       "<th>Position</th>";
                                       

                            }
                            else
                            {
                            html    += "<tr>" +
                                       "<td class='text-nowrap'>" + mfecha(today,agents[4]) + "</td>" + 
                                       (lidagent==''?"<td class='text-nowrap'>" + agents[0] + "</td>":'') +
                                       (lidagent==''?"<td class='text-nowrap'>" + agents[2] + "</td>":'') +
                                       (lidagent==''?"<td class='text-nowrap'>" + agents[3] + "</td>":'') +
                                       "<td>" + agents[4] + "</td>";
                                       "<td>" + agents[5] + "</td>";
                                       
                            }

                            console.log('size ' + size);
                            for(var i=5;i<size;i++)
                            {
                                horas=agents[i];
                                if(ishead)
                                {
                                    head += "<th>" + horas[2] + "</th>";
                                }
                                else
                                {
                                    html += "<td style='background-color:" + ((horas[1] == "0" || horas[1] == "") ? '#FFCCCC' : '') + "'>" + horas[0] + "</td>";
                                }
                            };

                            ishead = 0;
                            html +="</tr>";
                        });
                    }
                    html += '</tbody>';
                    head += '</thead>';


                    $("#txtFechas").text(moment($("#inputFecha").val()).format("MMMM Do") + " to " + moment($("#inputFecha").val()).add(6,'days').format("MMMM Do YYYY"));
                    $("#tblRoster").append(head);
                    if(html.length>20)
                        $("#tblRoster").append(html);
                    else
                        $("#tblRoster").append('<tbody><th colspan=3>' + moment(weeks[0]).format('MMMM Do YYYY, wo') + ' Week</th><tr><td colspan=5>No information for this week</td></tr></tbody>');

                    html = '';
                    head = '';
                });
            }
            $('#myPleaseWait').modal('hide');
        }, 
        error:function(exception){console.log(exception);}
        
    });
}

function GetCSV()
{
    var lidempresa = $("#inputIdEmpresa").val();
    var lidoficina = $("#inputIdOficina").val();
    var lfiltroweekday = $("#inputWeekday").val();
    var lfiltroposicion = $("#inputPosicion").val();
    var lfecha = $("#inputFecha").val();
    var lidagent = $("#inputAgentID").val();
    var lhora1 = $("#inputFromTime").val();
    var lhora2 = $("#inputToTime").val();

    var fields = {
        idempresa : lidempresa,
        idoficina : lidoficina,
        fechaini : lfecha,
        filtroagente : lidagent==0?'':lidagent,
        filtroweekday : lfiltroweekday,
        filtroposicion : lfiltroposicion,
        filtrohora1 : lhora1,
        filtrohora2 : lhora2
    }
    var request = $.ajax({
        url: 'roster/asyncrostercsv',
        type: 'POST',
        data: fields,
        beforeSend:function(){
            console.log('sending...');
            $('#myPleaseWait').modal('show');
        },
        success:function(result){
            
            var pom = document.createElement('a');
            var url = 'http://www.mindware.com.mx/lhr/uploads/' + result + '.csv'; 
            pom.href = url;
            pom.click();
            $('#myPleaseWait').modal('hide');
        }, 
        error:function(exception){console.log(exception);}
        
    });
}

function mfecha(fecha,weekday)
{
    var add = 0;
    switch(weekday)
    {
        case 'Mon': {add = 0; break;}
        case 'Tue': {add = 1; break;}
        case 'Wed': {add = 2; break;}
        case 'Thu': {add = 3; break;}
        case 'Fri': {add = 4; break;}
        case 'Sat': {add = 5; break;}
        case 'Sun': add = 6;
    }
    return fecha.add(add,'days').format('MMMM Do');
}


// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("myBtn").style.display = "block";
    } else {
        document.getElementById("myBtn").style.display = "none";
    }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    document.body.scrollTop = 0; // For Chrome, Safari and Opera 
    document.documentElement.scrollTop = 0; // For IE and Firefox
}
</script>