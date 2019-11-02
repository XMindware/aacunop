    <script src="<? echo base_url(); ?>assets/js/typeahead.min.js"></script>
    <div class="container">
        <div class="panel-body">
            <div class="panel-heading">
                <div class="row">
                <h2><span class="label label-primary">Positions Enabled per Flight</span></h2>
                <button type="button" id="btnNewRow" class="btn btn-link">New Flight</button>
                </div>
            </div>           
            <form id="frmData" method="post">
                <input type="hidden" id="inputIdEmpresa" name="inputIdEmpresa" value="<? echo $idempresa; ?>"/>
                <input type="hidden" id="inputIdOficina" name="inputIdOficina" value="<? echo $idoficina; ?>"/>
                <input type="hidden" id="inputTimezone" name="inputTimezone" value="<? echo $timezone; ?>"/>
                <input type="hidden" id="inputUsuario" name="inputUsuario" value="<? echo $usuario; ?>"/>
                <input type="hidden" id="inputIATA" name="inputIATA" value="<? echo $siatacode; ?>"/>
                <fieldset>
                    <legend>Position / Flight Information</legend>
                        <div class='row'>
                            <div class='col-sm-8'>
                                <div class='form-group'>
                                    <label for="inputCode">Flight Code</label>
                                    <select id="inputCode" name="inputCode" required="true" class="form-control">
                                    <?
                                        foreach($flights as $flight)
                                        {
                                            $horasalida = $flight['horasalida'] - ($timezone * 3600);
                                            $hours = intval($horasalida / 3600);
                                            $minutes = (($horasalida / 3600) - $hours) * 60;
                                            $departure = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                    ?>
                                      <option value="<? echo $flight['idvuelo']; ?>"><? echo $flight['idvuelo'] . ' DEST ' . $flight['destino'] . ' DEPARTURE ' . $departure ; ?></option>
                                      <?
                                        }
                                    ?>
                                    </select>
                                    <select id="inputSinCode" name="inputSinCode" required="true" class="form-control">
                                    <?
                                        foreach($sinflights as $flight)
                                        {
                                            $horasalida = $flight['horasalida'] - ($timezone * 3600);
                                            $hours = intval($horasalida / 3600);
                                            $minutes = (($horasalida / 3600) - $hours) * 60;
                                            $departure = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                    ?>
                                      <option value="<? echo $flight['idvuelo']; ?>"><? echo $flight['idvuelo'] . ' DEST ' . $flight['destino'] . ' DEPARTURE ' . $departure ; ?></option>
                                      <?
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-sm-1'>
                                <div class='form-group'>
                                    <label for="inputPositionMonday">Monday</label>
                                    <select id="inputPositionMonday" name="inputPositionMonday" required="true" class="form-control">
                                        <option value="null">None</option>
                                    <?
                                        foreach($posiciones as $posicion)
                                        {
                                            $horainicio = $posicion['starttime'] - ($timezone * 3600);
                                            $hours = intval($horainicio / 3600);
                                            $minutes = (($horainicio / 3600) - $hours) * 60;
                                            $stime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                            $horafin = $posicion['endtime'] - ($timezone * 3600);
                                            $hours = intval($horafin / 3600);
                                            $minutes = (($horafin / 3600) - $hours) * 60;
                                            $etime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                    ?>
                                      <option value="<? echo $posicion['code']; ?>"><? echo $posicion['description'] . ' FROM ' . $stime . ' TO ' . $etime; ?></option>
                                      <?
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class='col-sm-1'>
                                <div class='form-group'>
                                    <label for="inputPositionTuesday">Tuesday</label>
                                    <select id="inputPositionTuesday" name="inputPositionTuesday" required="true" class="form-control">
                                        <option value="null">None</option>
                                    <?
                                        foreach($posiciones as $posicion)
                                        {
                                            $horainicio = $posicion['starttime'] - ($timezone * 3600);
                                            $hours = intval($horainicio / 3600);
                                            $minutes = (($horainicio / 3600) - $hours) * 60;
                                            $stime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                            $horafin = $posicion['endtime'] - ($timezone * 3600);
                                            $hours = intval($horafin / 3600);
                                            $minutes = (($horafin / 3600) - $hours) * 60;
                                            $etime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                    ?>
                                      <option value="<? echo $posicion['code']; ?>"><? echo $posicion['description'] . ' FROM ' . $stime . ' TO ' . $etime; ?></option>
                                      <?
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class='col-sm-1'>
                                <div class='form-group'>
                                    <label for="inputPositionWednesday">Wednesday</label>
                                    <select id="inputPositionWednesday" name="inputPositionWednesday" required="true" class="form-control">
                                        <option value="null">None</option>
                                    <?
                                        foreach($posiciones as $posicion)
                                        {
                                            $horainicio = $posicion['starttime'] - ($timezone * 3600);
                                            $hours = intval($horainicio / 3600);
                                            $minutes = (($horainicio / 3600) - $hours) * 60;
                                            $stime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                            $horafin = $posicion['endtime'] - ($timezone * 3600);
                                            $hours = intval($horafin / 3600);
                                            $minutes = (($horafin / 3600) - $hours) * 60;
                                            $etime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                    ?>
                                      <option value="<? echo $posicion['code']; ?>"><? echo $posicion['description'] . ' FROM ' . $stime . ' TO ' . $etime; ?></option>
                                      <?
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class='col-sm-1'>
                                <div class='form-group'>
                                    <label for="inputPositionThursday">Thursday</label>
                                    <select id="inputPositionThursday" name="inputPositionThursday" required="true" class="form-control">
                                        <option value="null">None</option>
                                    <?
                                        foreach($posiciones as $posicion)
                                        {
                                            $horainicio = $posicion['starttime'] - ($timezone * 3600);
                                            $hours = intval($horainicio / 3600);
                                            $minutes = (($horainicio / 3600) - $hours) * 60;
                                            $stime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                            $horafin = $posicion['endtime'] - ($timezone * 3600);
                                            $hours = intval($horafin / 3600);
                                            $minutes = (($horafin / 3600) - $hours) * 60;
                                            $etime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                    ?>
                                      <option value="<? echo $posicion['code']; ?>"><? echo $posicion['description'] . ' FROM ' . $stime . ' TO ' . $etime; ?></option>
                                      <?
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class='col-sm-1'>
                                <div class='form-group'>
                                    <label for="inputPositionFriday">Friday</label>
                                    <select id="inputPositionFriday" name="inputPositionFriday" required="true" class="form-control">
                                        <option value="null">None</option>
                                    <?
                                        foreach($posiciones as $posicion)
                                        {
                                            $horainicio = $posicion['starttime'] - ($timezone * 3600);
                                            $hours = intval($horainicio / 3600);
                                            $minutes = (($horainicio / 3600) - $hours) * 60;
                                            $stime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                            $horafin = $posicion['endtime'] - ($timezone * 3600);
                                            $hours = intval($horafin / 3600);
                                            $minutes = (($horafin / 3600) - $hours) * 60;
                                            $etime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                    ?>
                                      <option value="<? echo $posicion['code']; ?>"><? echo $posicion['description'] . ' FROM ' . $stime . ' TO ' . $etime; ?></option>
                                      <?
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class='col-sm-1'>
                                <div class='form-group'>
                                    <label for="inputPositionSaturday">Saturday</label>
                                    <select id="inputPositionSaturday" name="inputPositionSaturday" required="true" class="form-control">
                                        <option value="null">None</option>
                                    <?
                                        foreach($posiciones as $posicion)
                                        {
                                            $horainicio = $posicion['starttime'] - ($timezone * 3600);
                                            $hours = intval($horainicio / 3600);
                                            $minutes = (($horainicio / 3600) - $hours) * 60;
                                            $stime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                            $horafin = $posicion['endtime'] - ($timezone * 3600);
                                            $hours = intval($horafin / 3600);
                                            $minutes = (($horafin / 3600) - $hours) * 60;
                                            $etime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                    ?>
                                      <option value="<? echo $posicion['code']; ?>"><? echo $posicion['description'] . ' FROM ' . $stime . ' TO ' . $etime; ?></option>
                                      <?
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class='col-sm-1'>
                                <div class='form-group'>
                                    <label for="inputPositionSunday">Sunday</label>
                                    <select id="inputPositionSunday" name="inputPositionSunday" required="true" class="form-control">
                                        <option value="null">None</option>
                                    <?
                                        foreach($posiciones as $posicion)
                                        {
                                            $horainicio = $posicion['starttime'] - ($timezone * 3600);
                                            $hours = intval($horainicio / 3600);
                                            $minutes = (($horainicio / 3600) - $hours) * 60;
                                            $stime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                            $horafin = $posicion['endtime'] - ($timezone * 3600);
                                            $hours = intval($horafin / 3600);
                                            $minutes = (($horafin / 3600) - $hours) * 60;
                                            $etime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                    ?>
                                      <option value="<? echo $posicion['code']; ?>"><? echo $posicion['description'] . ' FROM ' . $stime . ' TO ' . $etime; ?></option>
                                      <?
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- ADD EXTRA AGENT -->
                        <div class='row'>
                            <div class='col-sm-8'>
                                <div class='form-group'>
                                    <h3>Extra Agents Assignations</h3>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-sm-1'>
                                <div class='form-group'>
                                    <label for="inputEPositionMonday">Monday</label>
                                    <select id="inputEPositionMonday" name="inputEPositionMonday" required="true" class="form-control">
                                        <option value="null">None</option>
                                    <?
                                        foreach($eposiciones as $posicion)
                                        {
                                            $horainicio = $posicion['starttime'] - ($timezone * 3600);
                                            $hours = intval($horainicio / 3600);
                                            $minutes = (($horainicio / 3600) - $hours) * 60;
                                            $stime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                            $horafin = $posicion['endtime'] - ($timezone * 3600);
                                            $hours = intval($horafin / 3600);
                                            $minutes = (($horafin / 3600) - $hours) * 60;
                                            $etime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                    ?>
                                      <option value="<? echo $posicion['code']; ?>"><? echo $posicion['description'] . ' FROM ' . $stime . ' TO ' . $etime; ?></option>
                                      <?
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class='col-sm-1'>
                                <div class='form-group'>
                                    <label for="inputEPositionTuesday">Tuesday</label>
                                    <select id="inputEPositionTuesday" name="inputEPositionTuesday" required="true" class="form-control">
                                        <option value="null">None</option>
                                    <?
                                        foreach($eposiciones as $posicion)
                                        {
                                            $horainicio = $posicion['starttime'] - ($timezone * 3600);
                                            $hours = intval($horainicio / 3600);
                                            $minutes = (($horainicio / 3600) - $hours) * 60;
                                            $stime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                            $horafin = $posicion['endtime'] - ($timezone * 3600);
                                            $hours = intval($horafin / 3600);
                                            $minutes = (($horafin / 3600) - $hours) * 60;
                                            $etime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                    ?>
                                      <option value="<? echo $posicion['code']; ?>"><? echo $posicion['description'] . ' FROM ' . $stime . ' TO ' . $etime; ?></option>
                                      <?
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class='col-sm-1'>
                                <div class='form-group'>
                                    <label for="inputEPositionWednesday">Wednesday</label>
                                    <select id="inputEPositionWednesday" name="inputEPositionWednesday" required="true" class="form-control">
                                        <option value="null">None</option>
                                    <?
                                        foreach($eposiciones as $posicion)
                                        {
                                            $horainicio = $posicion['starttime'] - ($timezone * 3600);
                                            $hours = intval($horainicio / 3600);
                                            $minutes = (($horainicio / 3600) - $hours) * 60;
                                            $stime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                            $horafin = $posicion['endtime'] - ($timezone * 3600);
                                            $hours = intval($horafin / 3600);
                                            $minutes = (($horafin / 3600) - $hours) * 60;
                                            $etime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                    ?>
                                      <option value="<? echo $posicion['code']; ?>"><? echo $posicion['description'] . ' FROM ' . $stime . ' TO ' . $etime; ?></option>
                                      <?
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class='col-sm-1'>
                                <div class='form-group'>
                                    <label for="inputEPositionThursday">Thursday</label>
                                    <select id="inputEPositionThursday" name="inputEPositionThursday" required="true" class="form-control">
                                        <option value="null">None</option>
                                    <?
                                        foreach($eposiciones as $posicion)
                                        {
                                            $horainicio = $posicion['starttime'] - ($timezone * 3600);
                                            $hours = intval($horainicio / 3600);
                                            $minutes = (($horainicio / 3600) - $hours) * 60;
                                            $stime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                            $horafin = $posicion['endtime'] - ($timezone * 3600);
                                            $hours = intval($horafin / 3600);
                                            $minutes = (($horafin / 3600) - $hours) * 60;
                                            $etime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                    ?>
                                      <option value="<? echo $posicion['code']; ?>"><? echo $posicion['description'] . ' FROM ' . $stime . ' TO ' . $etime; ?></option>
                                      <?
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class='col-sm-1'>
                                <div class='form-group'>
                                    <label for="inputEPositionFriday">Friday</label>
                                    <select id="inputEPositionFriday" name="inputEPositionFriday" required="true" class="form-control">
                                        <option value="null">None</option>
                                    <?
                                        foreach($eposiciones as $posicion)
                                        {
                                            $horainicio = $posicion['starttime'] - ($timezone * 3600);
                                            $hours = intval($horainicio / 3600);
                                            $minutes = (($horainicio / 3600) - $hours) * 60;
                                            $stime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                            $horafin = $posicion['endtime'] - ($timezone * 3600);
                                            $hours = intval($horafin / 3600);
                                            $minutes = (($horafin / 3600) - $hours) * 60;
                                            $etime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                    ?>
                                      <option value="<? echo $posicion['code']; ?>"><? echo $posicion['description'] . ' FROM ' . $stime . ' TO ' . $etime; ?></option>
                                      <?
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class='col-sm-1'>
                                <div class='form-group'>
                                    <label for="inputEPositionSaturday">Saturday</label>
                                    <select id="inputEPositionSaturday" name="inputEPositionSaturday" required="true" class="form-control">
                                        <option value="null">None</option>
                                    <?
                                        foreach($eposiciones as $posicion)
                                        {
                                            $horainicio = $posicion['starttime'] - ($timezone * 3600);
                                            $hours = intval($horainicio / 3600);
                                            $minutes = (($horainicio / 3600) - $hours) * 60;
                                            $stime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                            $horafin = $posicion['endtime'] - ($timezone * 3600);
                                            $hours = intval($horafin / 3600);
                                            $minutes = (($horafin / 3600) - $hours) * 60;
                                            $etime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                    ?>
                                      <option value="<? echo $posicion['code']; ?>"><? echo $posicion['description'] . ' FROM ' . $stime . ' TO ' . $etime; ?></option>
                                      <?
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class='col-sm-1'>
                                <div class='form-group'>
                                    <label for="inputEPositionSunday">Sunday</label>
                                    <select id="inputEPositionSunday" name="inputEPositionSunday" required="true" class="form-control">
                                        <option value="null">None</option>
                                    <?
                                        foreach($eposiciones as $posicion)
                                        {
                                            $horainicio = $posicion['starttime'] - ($timezone * 3600);
                                            $hours = intval($horainicio / 3600);
                                            $minutes = (($horainicio / 3600) - $hours) * 60;
                                            $stime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                            $horafin = $posicion['endtime'] - ($timezone * 3600);
                                            $hours = intval($horafin / 3600);
                                            $minutes = (($horafin / 3600) - $hours) * 60;
                                            $etime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                    ?>
                                      <option value="<? echo $posicion['code']; ?>"><? echo $posicion['description'] . ' FROM ' . $stime . ' TO ' . $etime; ?></option>
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
                                    <button type="button" id="btnSubmitDataNew" class="btn btn-success">Save</button>
                                    <button type="button" id="btnSubmitDataEdit" class="btn btn-success">Save</button>
                                    <button type="button" id="btnCancel" class="btn btn-default">Cancel</button>
                                </div>
                            </div>
                            <div class='col-sm-2'>
                                <div class='form-group'>
                                    <button type="button" id="btnDeleteRow" class="btn btn btn-warning">Release</button>
                                </div>
                            </div>
                        </div>
                    </fieldset>
            </form>
            <div class="row">
                <div class="col-md-12">
                    <h3>Flights available: <? echo $vuelos; ?> Positions assigned: <? echo $asignados; ?></h3>
                    <table class="table table-condensed">
                    <thead>
                      <tr>
                        <th>Code</th>
                        <th>Flight Detail</th>
                        <th>Position assigned</th>
                      </tr>
                    </thead>
                    <?

                        if(sizeof($rowlist)> 0){
                            foreach($rowlist as $row)
                            {
                                

                                $horasalida = $row['horasalida'] - ($timezone * 3600);
                                $hours = intval($horasalida / 3600);
                                $minutes = (($horasalida / 3600) - $hours) * 60;
                                $departure = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                $hours = intval($row['duracionvuelo'] / 3600);
                                $minutes = (($row['duracionvuelo'] / 3600) - $hours) * 60;
                                $duration = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                
                        ?>
                        <tbody>
                          <tr>
                            <td><button type="button" onclick="loadRow('<? echo $row['idvuelo']; ?>','<? echo $row['linea']; ?>');" class="btn btn-link">
                                <? echo $row['idvuelo']; ?></button>
                                </td>
                            <td><? echo $departure . ' ' . $row['destino'] . ' Duration ' . $duration; ?></td>
                            <?
                                if($row['posmon'] == $row['postue'] && $row['postue'] == $row['poswed'] && $row['poswed'] == $row['posthu'] &&
                                   $row['posthu'] == $row['posfri'] && $row['posfri'] == $row['possat'] && $row['possat'] == $row['possun'])
                                {
                                    ?>
                                        <td><? echo $row['posmon'] . ' all week'; ?></td>
                                    <?
                                }
                                else
                                {
                                    ?>
                                        <td><? echo $row['posmon'] . ' ' . $row['postue'] . ' ' . $row['poswed'] . ' ' . $row['posthu'] . ' ' . $row['posfri'] . ' ' . $row['possat'] . ' ' . $row['possun']; ?></td>
                                    <?
                                }
                            ?>
                          </tr>
                          <? } ?>
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
    $("#inputCode").hide();
    $("#btnSubmitDataEdit").hide();
    
    $(document).ready(function(){
        
        // grabar cambios
        $("#btnSubmitDataEdit").click(function(){

            var offset = new Date().getTimezoneOffset();
        
            var lcode = $("#inputCode").val();
            var lposmon = $("#inputPositionMonday").val();
            var lpostue = $("#inputPositionTuesday").val();
            var lposwed = $("#inputPositionWednesday").val();
            var lposthu = $("#inputPositionThursday").val();
            var lposfri = $("#inputPositionFriday").val();
            var lpossat = $("#inputPositionSaturday").val();
            var lpossun = $("#inputPositionSunday").val();
            var lidempresa = $("#inputIdEmpresa").val();
            var lidoficina = $("#inputIdOficina").val();
            var lusuario = $("#inputUsuario").val();
        
            //console.log('lposicion ' + lposition);
            $("#frmData").hide();   
            var data = {
                         idempresa : lidempresa,
                         idoficina : lidoficina,
                         idvuelo : lcode,
                         posmon : lposmon=='null'?'':lposmon,
                         postue : lpostue=='null'?'':lpostue,
                         poswed : lposwed=='null'?'':lposwed,
                         posthu : lposthu=='null'?'':lposthu,
                         posfri : lposfri=='null'?'':lposfri,
                         possat : lpossat=='null'?'':lpossat,
                         possun : lpossun=='null'?'':lpossun,
                         linea : 0,
                         usuario : lusuario
                         };
            var request = $.ajax({
                url: 'posicionesvuelos/postrow',
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

            var lposmon = $("#inputEPositionMonday").val();
            var lpostue = $("#inputEPositionTuesday").val();
            var lposwed = $("#inputEPositionWednesday").val();
            var lposthu = $("#inputEPositionThursday").val();
            var lposfri = $("#inputEPositionFriday").val();
            var lpossat = $("#inputEPositionSaturday").val();
            var lpossun = $("#inputEPositionSunday").val();

            //if(lposmon != 'null' || lpostue != 'null' || lposwed != 'null' || lposthu != 'null' || lposfri != 'null' || lpossat != 'null' || 
            //   lpossun != 'null' )
            if(1)
            {
                 // si hay posicion extra!
                 var data = {
                         idempresa : lidempresa,
                         idoficina : lidoficina,
                         idvuelo : lcode,
                         posmon : lposmon=='null'?'':lposmon,
                         postue : lpostue=='null'?'':lpostue,
                         poswed : lposwed=='null'?'':lposwed,
                         posthu : lposthu=='null'?'':lposthu,
                         posfri : lposfri=='null'?'':lposfri,
                         possat : lpossat=='null'?'':lpossat,
                         possun : lpossun=='null'?'':lpossun,
                         linea : 1,
                         usuario : lusuario
                         };
                var request = $.ajax({
                    url: 'posicionesvuelos/postrow',
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
            }
            
        });


        //submit nuevo vuelo
        // grabar cambios
        $("#btnSubmitDataNew").click(function(){

            var offset = new Date().getTimezoneOffset();
              
            var lcode = $("#inputSinCode").val();
            var lposmon = $("#inputPositionMonday").val();
            var lpostue = $("#inputPositionTuesday").val();
            var lposwed = $("#inputPositionWednesday").val();
            var lposthu = $("#inputPositionThursday").val();
            var lposfri = $("#inputPositionFriday").val();
            var lpossat = $("#inputPositionSaturday").val();
            var lpossun = $("#inputPositionSunday").val();
            var lidempresa = $("#inputIdEmpresa").val();
            var lidoficina = $("#inputIdOficina").val();
            var lusuario = $("#inputUsuario").val();
        
            //console.log('lposicion ' + lposition);
            $("#frmData").hide();   
            var data = {
                         idempresa : lidempresa,
                         idoficina : lidoficina,
                         idvuelo : lcode,
                         posmon : lposmon=='null'?'':lposmon,
                         postue : lpostue=='null'?'':lpostue,
                         poswed : lposwed=='null'?'':lposwed,
                         posthu : lposthu=='null'?'':lposthu,
                         posfri : lposfri=='null'?'':lposfri,
                         possat : lpossat=='null'?'':lpossat,
                         possun : lpossun=='null'?'':lpossun,
                         linea : 0,
                         usuario : lusuario
                         };
            var request = $.ajax({
                url: 'posicionesvuelos/postrow',
                type: 'POST',
                data: data,
                beforeSend:function(){
                    //console.log('sending...');
                    $('#myPleaseWait').modal('show');
                },
                complete:function(result){
                    location.reload();
                    console.log('actualizado!');
                    console.log(result);
                    
                    $('#myPleaseWait').modal('hide');
                }, 
                error:function(exception){console.log(exception);}
                
            });

            var lposmon = $("#inputEPositionMonday").val();
            var lpostue = $("#inputEPositionTuesday").val();
            var lposwed = $("#inputEPositionWednesday").val();
            var lposthu = $("#inputEPositionThursday").val();
            var lposfri = $("#inputEPositionFriday").val();
            var lpossat = $("#inputEPositionSaturday").val();
            var lpossun = $("#inputEPositionSunday").val();

            if(lposmon != 'null' || lpostue != 'null' || lposwed != 'null' || lposthu != 'null' || lposfri != 'null' || lpossat != 'null' || 
               lpossun != 'null' )
            {
                 // si hay posicion extra!
                 var data = {
                         idempresa : lidempresa,
                         idoficina : lidoficina,
                         idvuelo : lcode,
                         posmon : lposmon=='null'?'':lposmon,
                         postue : lpostue=='null'?'':lpostue,
                         poswed : lposwed=='null'?'':lposwed,
                         posthu : lposthu=='null'?'':lposthu,
                         posfri : lposfri=='null'?'':lposfri,
                         possat : lpossat=='null'?'':lpossat,
                         possun : lpossun=='null'?'':lpossun,
                         linea : 1,
                         usuario : lusuario
                         };
                var request = $.ajax({
                    url: 'posicionesvuelos/postrow',
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
            }
            
        });

        $("#inputCode").change(function(){

            var idvuelo = $("#inputCode").val();
            var offset = new Date().getTimezoneOffset();

            var rowData = {
                idvuelo : idvuelo
            };

            $.ajax({
                url: 'posicionesvuelos/posicioneshorario',
                type: 'POST',
                data : rowData,
                beforeSend:function(){
                    $('#myPleaseWait').modal('show');
                    $(document).scrollTop();
                },
                success:function(data){
                    console.log('posiciones de vuelo')
                    
                    $('#inputPosition')
                        .find('option')
                        .remove()
                        .end();
                    var posiciones = jQuery.parseJSON(data)
                    //$("#inputMySkills").empty()
                    for(var i=0;i<posiciones.length;i++){
                        var pos = posiciones[i];

                        pos.starttime = parseInt(pos.starttime) + parseInt(offset * 60);

                        var hours = parseInt(pos.starttime / 3600);
                        var minut = Math.ceil(((pos.starttime / 3600) - hours ) * 60);
                        
                        var stime = (hours<=9?('0' + hours) : hours) + ':' + (minut<=9?('0' + minut) : minut);
                        
                        pos.endtime = parseInt(pos.endtime) + parseInt(offset * 60);
                        hours = parseInt(pos.endtime / 3600);
                        minut = Math.ceil(((pos.endtime / 3600) - hours) * 60);
                        var etime = (hours<=9?('0' + hours) : hours) + ':' + (minut<=9?('0' + minut) : minut);
                                
                        $("#inputPosition").append($("<option></option>").val(pos.code).html(pos.code + ' FROM ' + stime + ' TO ' + etime));
                    }
                    $('#myPleaseWait').modal('hide');
                }
            })
        })

        // lee las posiciones habilitadas para cuando un vuelo no esta asignado
        $("#inputSinCode").change(function(){

            var idvuelo = $("#inputSinCode").val();
            var offset = new Date().getTimezoneOffset();

            var rowData = {
                idvuelo : idvuelo
            };

            $.ajax({
                url: 'posicionesvuelos/posicioneshorario',
                type: 'POST',
                data : rowData,
                beforeSend:function(){
                    $('#myPleaseWait').modal('show');
                    $(document).scrollTop();
                },
                success:function(data){
                    console.log('posiciones de vuelo')
                    
                    $('#inputPosition')
                        .find('option')
                        .remove()
                        .end();

                    
                    var posiciones = jQuery.parseJSON(data)
                    //$("#inputMySkills").empty()
                    for(var i=0;i<posiciones.length;i++){
                        var pos = posiciones[i];

                        pos.starttime = parseInt(pos.starttime) + parseInt(offset * 60);

                        var hours = parseInt(pos.starttime / 3600);
                        var minut = Math.ceil(((pos.starttime / 3600) - hours ) * 60);
                        
                        var stime = (hours<=9?('0' + hours) : hours) + ':' + (minut<=9?('0' + minut) : minut);
                        
                        pos.endtime = parseInt(pos.endtime) + parseInt(offset * 60);
                        hours = parseInt(pos.endtime / 3600);
                        minut = Math.ceil(((pos.endtime / 3600) - hours) * 60);
                        var etime = (hours<=9?('0' + hours) : hours) + ':' + (minut<=9?('0' + minut) : minut);
                                
                        $("#inputPosition").append($("<option></option>").val(pos.code).html(pos.code + ' FROM ' + stime + ' TO ' + etime));
                    }
                    $('#myPleaseWait').modal('hide');
                }
            })
        })
        
        $("#btnNewRow").click(function(){
            $("#frmData").show();
            $("#inputCode").hide();
            $("#btnSubmitDataEdit").hide();
            $("#inputSinCode").show();
            $("#btnSubmitDataNew").show();  
        });
        
        $("#btnCancel").click(function(){
            $("#inputCode").val('');
            $("#frmData").hide();   
        });
        
        $("#btnDeleteRow").click(function(){
            
            var lidvuelo = $("#inputCode").val();
            var lposicion = $("#inputPositionMonday").val();
            var lidempresa = $("#inputIdEmpresa").val();
            var lidoficina = $("#inputIdOficina").val();
            var lsdate = $("#inputStartDate").val();
            
            var rowData = { idempresa : lidempresa,
                            idoficina : lidoficina,
                            idvuelo : lidvuelo,
                            posicion : lposicion };
            $.ajax({
                url: 'posicionesvuelos/deletepositionid',
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

    function loadRow(idvuelo, linea)
    {
        $("#frmData").show();   
        
        var lidempresa = $("#inputIdEmpresa").val();
        var lidoficina = $("#inputIdOficina").val();

        var rowData = { 
            idempresa : lidempresa,
            idoficina : lidoficina,
            linea : '',
            idvuelo : idvuelo,
        };

        $.ajax({
            url: 'posicionesvuelos/loadvuelocode',
            type: 'POST',
            data : rowData,
            beforeSend:function(){
                $('#myPleaseWait').modal('show');
                $(document).scrollTop();
            },
            success:function(data){
                console.log('loading data...');
                var row = jQuery.parseJSON(data)[0];

                var offset = new Date().getTimezoneOffset();

                $("#inputSinCode").hide();
                $("#inputCode").show();
                
                $("#inputCode").val(row.idvuelo);
                $("#inputPositionMonday").val(row.posmon);
                $("#inputPositionTuesday").val(row.postue);
                $("#inputPositionWednesday").val(row.poswed);
                $("#inputPositionThursday").val(row.posthu);
                $("#inputPositionFriday").val(row.posfri);
                $("#inputPositionSaturday").val(row.possat);
                $("#inputPositionSunday").val(row.possun);


                if(jQuery.parseJSON(data).length>1)
                {
                    var row = jQuery.parseJSON(data)[1];

                    var offset = new Date().getTimezoneOffset();
  
                    $("#inputEPositionMonday").val(row.posmon==''?'null':row.posmon);
                    $("#inputEPositionTuesday").val(row.postue==''?'null':row.postue);
                    $("#inputEPositionWednesday").val(row.poswed==''?'null':row.poswed);
                    $("#inputEPositionThursday").val(row.posthu==''?'null':row.posthu);
                    $("#inputEPositionFriday").val(row.posfri==''?'null':row.posfri);
                    $("#inputEPositionSaturday").val(row.possat==''?'null':row.possat);
                    $("#inputEPositionSunday").val(row.possun==''?'null':row.possun);
                }
                else
                {
                    $("#inputEPositionMonday").val('null');
                    $("#inputEPositionTuesday").val('null');
                    $("#inputEPositionWednesday").val('null');
                    $("#inputEPositionThursday").val('null');
                    $("#inputEPositionFriday").val('null');
                    $("#inputEPositionSaturday").val('null');
                    $("#inputEPositionSunday").val('null');
                }
                $("#btnSubmitDataEdit").show(); 
                $("#btnSubmitDataNew").hide();  
                $("html, body").animate({ scrollTop: 0 }, "fast");
  
                $('#myPleaseWait').modal('hide');
            }
        });
    }
</script>