    <script src="<? echo base_url(); ?>assets/js/typeahead.min.js"></script>
    <div class="container">
        <div class="panel-body">
            <div class="panel-heading">
                <div class="row">
                <h2><span class="label label-primary">Perfect Attendance Monthly</span></h2>
                <button type="button" id="btnNewData" class="btn btn-link">Import Data</button>
                <input type="hidden" id="inputUsuario" value="<? echo $usuario; ?>" />
                </div>
            </div>  
            <div id="frmImportData">         
                <?
                  if(!isset($agentesok))
                  { ?> 
                <form action="<? echo base_url(); ?>PerfectAttendance/uploadcsv" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                    <input type="hidden" id="inputIdEmpresa" name="inputIdEmpresa" value="<? echo $idempresa; ?>" />
                    <input type="hidden" id="inputIdOficina" name="inputIdOficina" value="<? echo $idoficina; ?>" />
                    <center>
                        <!--
                        <div id="divloading" style="position:fixed; z-index:10; background-color:#FFF">
                            <img src="<? echo base_url(); ?>assets/images/loading.gif" />
                        </div>-->
                        <fieldset>
                            <legend>Fill info</legend>
                            <div class='row'>
                                <div class='col-sm-2'>
                                    <div class='form-group'>
                                        <label for="inputFechaIni">Month</label>
                                        <input class="form-control" id="inputFechaMonth" name="inputFechaMonth" value="<? echo $numbermes; ?>" required="true" size="30" type="number" />
                                    </div>
                                </div>
                                <div class='col-sm-2'>
                                    <div class='form-group'>
                                        <label for="inputFechaFin">Year</label>
                                        <input class="form-control" id="inputFechaYear" name="inputFechaYear" value="<? echo $numberyear; ?>" required="true" size="30" type="number" />
                                    </div>
                                </div>
                                <div class='col-sm-6'>
                                    <div class='form-group'>
                                        <p class="text-left">
                                            <label for="userfile">CSV Perfect Attendance File</label>
                                            <input type="file" id="userfile" name="userfile" accept=".csv">
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class='row'>
                                <div class='col-sm-8'>
                                    <div class='form-group'>
                                        <button type="submit" id="btnSubmitData" class="btn btn-success">Import Data</button>
                                        <button type="button" id="btnCancelImport" class="btn btn-default">Cancel</button>
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
                                        echo $not . PHP_EOL;
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
            <form id="frmLoadData"action="<? echo base_url(); ?>PerfectAttendance" enctype="multipart/form-data" method="get" accept-charset="utf-8">
                    <input type="hidden" id="inputIdEmpresa" name="inputIdEmpresa" value="<? echo $idempresa; ?>" />
                    <input type="hidden" id="inputIdOficina" name="inputIdOficina" value="<? echo $idoficina; ?>" />
                    <center>
                        <div class='row'>
                            <div class='col-sm-2'>
                                <div class='form-group'>
                                    <label for="inputFechaIni">Month</label>
                                    <input class="form-control" id="inputFechaMonth" name="inputFechaMonth" value="<? echo $numbermes; ?>" required="true" size="30" type="number" />
                                </div>
                            </div>
                            <div class='col-sm-2'>
                                <div class='form-group'>
                                    <label for="inputFechaFin">Year</label>
                                    <input class="form-control" id="inputFechaYear" name="inputFechaYear" value="<? echo $numberyear; ?>" required="true" size="30" type="number" />
                                </div>
                            </div>
                            <div class='col-sm-3'>
                                <div class='form-group'>
                                    <button type="submit" id="btnSubmitData" class="btn btn-success">Load Monthly PA</button>
                                </div>
                            </div>
                            <div class='col-sm-3'>
                                <div class='form-group'>
                                    <button type="submit" id="btnClearMonth" class="btn btn-warning">Clear Monthly PA</button>
                                </div>
                            </div>
                        </div>
                    </center>
                </form>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-condensed">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Name</th>
                      </tr>
                    </thead>
                    <?
                        foreach($perfect as $row)
                        {
                    ?>
                    <tbody id="tblData">
                      <tr>
                        <td><? echo $row['idagente']; ?></td>
                        <td><? echo $row['shortname']; ?></td>
                        
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
  
     <?
      if(!isset($agentesok))
      {
        ?>
       $("#frmImportData").hide();
       <?
      }
      ?>
    $('#myPleaseWait').modal('hide');
    $(document).ready(function(){

        
        $("#btnNewData").click(function(){
            $("#frmImportData").show();
            $("#frmLoadData").hide();   
        });

        $("#btnClearMonth").click(function(){

            var lidempresa = $("#inputIdEmpresa").val();
            var lidoficina = $("#inputIdOficina").val();
            var lmonth = $("#inputFechaMonth").val();
            var lyear = $("#inputFechaYear").val();
            
            var agentData = {
                idempresa : lidempresa,
                idoficina : lidoficina,
                month : lmonth,
                year : lyear
            };

            $.ajax({
                url: 'PerfectAttendance/clearmonth',
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
        
        $("#btnCancelImport").click(function(){
            
            $("#frmImportData").hide(); 
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
            url: 'PerfectAttendance/asynclist',
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
            url: 'PerfectAttendance/loadrow',
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