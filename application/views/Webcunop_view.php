        <?
        if(!isset($fecha) || $fecha=='')
            $fecha = date('Y-m-d');
    ?>
    <style>

        .table-borderless tbody tr td, .table-borderless tbody tr th, .table-borderless thead tr th {
            border: none;
        }

        #myBtn {
            display: none; /* Hidden by default */
            position: fixed; /* Fixed/sticky position */
            bottom: 20px; /* Place the button at the bottom of the page */
            right: 30px; /* Place the button 30px from the right */
            z-index: 99; /* Make sure it does not overlap */
            border: none; /* Remove borders */
            outline: none; /* Remove outline */
            color: white; /* Text color */
            cursor: pointer; /* Add a mouse pointer on hover */
            padding: 10 px; /* Some padding */
            border-radius: 10px; /* Rounded corners */
        }

        #myBtn:hover {
            background-color: #555; /* Add a dark-grey background on hover */
        }

        .greenc {
            color : #ABEBC6;
            font-size: 12px;
        }
        .redc {
            color : #F1948A;
            font-size: 12px;
        }
    </style>
    <script src="<? echo base_url(); ?>assets/js/custypeahead.js"></script>
    <button onclick="topFunction()" id="myBtn" title=""><img src="<? echo base_url(); ?>assets/images/Circled Up-50.png" /></button>
    <div class="container">
        <div class="panel-body">
            <div class="panel-heading">
                <div class="row">
                <h2><span class="label label-primary">CUNOP</span></h2>
                <input type="hidden" id="inputIdEmpresa" value="<? echo $idempresa; ?>" />
                <input type="hidden" id="inputIdOficina" value="<? echo $idoficina; ?>" />
                <input type="hidden" id="inputUsuario" value="<? echo $idusuario; ?>" />
                <input type="hidden" id="inputUniqueId"  />
                <input type="hidden" id="inputCurrentRow"  />
                <input type="hidden" id="typeaheadflag" value=""  />
                </div>
            </div>           
            <form id="frmDateData" method="post">
                <fieldset>
                    <legend>Load Date Schedule</legend>
                    <div class='row'>
                        <div class='col-sm-4'>
                            <div class='form-group'>
                                <label for="inputFecha">Date</label>
                                <input class="form-control" id="inputFecha" name="inputFecha"  value='<? echo $fecha; ?>' required="true" size="30" type="date" />
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-sm-6'>
                            <div class='form-group'>
                                <button type="submit" id="btnLoadDate" class="btn btn-success">Load</button>
                                <button type="button" id="btnPrintCUNOP" class="btn btn-info">Print CUNOP</button>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>


            <!-- FORM PARA MODIFICAR LA ASIGNACION DE UN VUELO -->
            <form id="frmVueloData" class="form-inline">
                <fieldset>
                    <legend>Flight assignments</legend>
                    <div class='row'>
                        <div class='col-sm-12'>
                            <div class='form-group'>
                                <label for="inputFlight">Flight</label>
                                <input class="form-control" id="inputFlight" name="inputFlight" required="true" size="30" type="text" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class='col-sm-12'>
                            <div class='form-group'>
                                <hr class="divider">
                                <label for="inputDeparture">Time of departure </label>
                                <input class="form-control" id="inputDeparture" name="inputDeparture" required="true" size="30" type="time" />
                                <button type="button" id="btnUpdateDeparture" class="btn btn-info">Update</button>
                            </div>
                        </div>
                    </div>
                    <div class='row' style="padding-top:10px">
                        <div class='col-sm-12'>
                            <div class='form-group'>
                                <label for="inputMensaje">Special Message  </label>
                                <input class="form-control" id="inputMensaje" name="inputMensaje" required="true" size="30" type="text" />
                                <button type="button" id="btnUpdateMessage" class="btn btn-info">Update</button>
                                <hr class="divider">
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-sm-12'>
                            <div class='form-group'>
                                <label for="inputAgent1">Agents Assigned</label>
                                </div>
                        </div>
                    </div>
                    <div class='row form-inline' >
                        <div class='col-sm-12'>
                            <div class='form-group'>
                                <label for="inputAgent1">1 -</label>
                                <input type="hidden" id="inputLinea" />
                                <input class="form-control typeahead" id="inputAgent1" name="inputAgent1" required="true" placeholder="Search Agents" size="30" type="text" />
                                
                                <label for="inputPosicion1">POSITION</label>
                                <select id="inputPosicion1" name="inputPosicion1" required="true" class="form-control">
                                    <?
                                    foreach($posiciones as $position)
                                    {
                                        $horainicio = intval($position['starttime']) - ($timezone * 3600);
                                        $hours = intval($horainicio / 3600) ;
                                        $minutes = (($horainicio / 3600) - $hours) * 60;
                                        $stime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                        
                                        $horafin = intval($position['endtime']) - ($timezone * 3600);
                                        $hours = intval($horafin / 3600);
                                        $minutes = (($horafin / 3600) - $hours) * 60;
                                        $etime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                  ?>
                                        <option value="<? echo $position['code']; ?>"><? echo $position['code'] . ' ' . $stime . ' TO ' . $etime; ?></option>
                                        <?
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class='row form-inline'>
                        <div class='col-sm-12'>
                            <div class='form-group'>
                                <label for="inputAgent1">2 -</label>
                                <input type="hidden" id="inputLinea" />
                                <input class="form-control typeahead" id="inputAgent2" name="inputAgent2" required="true" placeholder="Search Agents" size="30" type="text" />
                                
                                <label for="inputPosicion2">POSITION</label>
                                <select id="inputPosicion2" name="inputPosicion2" required="true" class="form-control">
                                     <?
                                    foreach($posiciones as $position)
                                    {
                                        $horainicio = intval($position['starttime']) - ($timezone * 3600);
                                        $hours = intval($horainicio / 3600) ;
                                        $minutes = (($horainicio / 3600) - $hours) * 60;
                                        $stime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                        
                                        $horafin = intval($position['endtime']) - ($timezone * 3600);
                                        $hours = intval($horafin / 3600);
                                        $minutes = (($horafin / 3600) - $hours) * 60;
                                        $etime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                  ?>
                                        <option value="<? echo $position['code']; ?>"><? echo $position['code'] . ' ' . $stime . ' TO ' . $etime; ?></option>                                        }
                                      <?
                                    }
                                  ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class='row form-inline' id='divAddNewHere'>
                        <div class='col-sm-12'>
                            <div class='form-group'>
                                <label for="inputAgentX">3 -</label>
                                <input type="hidden" id="inputLinea" />
                                <input type="hidden" id="inputUniqueIdEE" />
                                <input class="form-control typeahead" id="inputAgentX" name="inputAgentX" required="true" placeholder="Search Agents" size="30" type="text" />
                                
                                <label for="inputPosicionX">POSITION</label>
                                <select id="inputPosicionX" name="inputPosicionX" required="true" class="form-control">
                                    <?
                                    foreach($posiciones as $position)
                                    {
                                        $horainicio = intval($position['starttime']) - ($timezone * 3600);
                                        $hours = intval($horainicio / 3600) ;
                                        $minutes = (($horainicio / 3600) - $hours) * 60;
                                        $stime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                        
                                        $horafin = intval($position['endtime']) - ($timezone * 3600);
                                        $hours = intval($horafin / 3600);
                                        $minutes = (($horafin / 3600) - $hours) * 60;
                                        $etime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                  ?>
                                        <option value="<? echo $position['code']; ?>"><? echo $position['code'] . ' ' . $stime . ' TO ' . $etime; ?></option>                                        }
                                      <?
                                    }
                                  ?>                               
                                </select>
                                <button type="button" id="btnDoDeleteExtraAgent" class="btn btn-warning">Delete</button>
                                <button type="button" id="btnDoAddFlightAgent" class="btn btn-info">Ok</button>
                            </div>
                        </div>
                    </div>
                    <div class='row form-inline' id='divAddNewHere2'>
                        <div class='col-sm-12'>
                            <div class='form-group'>
                                <label for="inputAgentX4">4 -</label>
                                <input type="hidden" id="inputLinea" />
                                <input type="hidden" id="inputUniqueIdEE4" />
                                <input class="form-control typeahead" id="inputAgentX4" name="inputAgentX4" required="true" placeholder="Search Agents" size="30" type="text" />
                                
                                <label for="inputPosicionX4">POSITION</label>
                                <select id="inputPosicionX4" name="inputPosicionX4" required="true" class="form-control">
                                    <?
                                    foreach($posiciones as $position)
                                    {
                                        $horainicio = intval($position['starttime']) - ($timezone * 3600);
                                        $hours = intval($horainicio / 3600) ;
                                        $minutes = (($horainicio / 3600) - $hours) * 60;
                                        $stime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                        
                                        $horafin = intval($position['endtime']) - ($timezone * 3600);
                                        $hours = intval($horafin / 3600);
                                        $minutes = (($horafin / 3600) - $hours) * 60;
                                        $etime = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
                                  ?>
                                        <option value="<? echo $position['code']; ?>"><? echo $position['code'] . ' ' . $stime . ' TO ' . $etime; ?></option>                                        }
                                      <?
                                    }
                                  ?>                               
                                </select>
                                <button type="button" id="btnDoDeleteExtraAgent2" class="btn btn-warning">Delete</button>
                                <button type="button" id="btnDoAddFlightAgent2" class="btn btn-info">Ok</button>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-sm-12'>
                            <div class='form-group'>
                                <button type="button" id="btnAddFlightAgent" class="btn btn-info">Add Agent to Flight</button>
                                <button type="button" id="btnSubmitFlightData" class="btn btn-success">Save</button>
                                <button type="button" id="btnCancel" class="btn btn-default">Cancel</button>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>

            <!-- LOAD AGENT POSITIONS -->
            <form id="frmAgentPositionData">
                <fieldset>
                    <legend>Agent position assignment</legend>
                    <div class='row'>
                        <div class='col-sm-3'>
                            <div class='form-group'>
                                <input type="hidden" id="inputAgenteIdPos" />
                                <label for="inputAgentShortname">Agent</label>
                                <input class="form-control typeahead" id="inputAgentShortname" name="inputAgentShortname" required="true" placeholder="Search Agents" size="30" type="text" />
                            </div>
                        </div>
                        <div class='col-sm-2'>
                            <div class='form-group'>
                                <label for="inputPositionAgent">Position Assigned</label>
                                <input type="text" class="form-control typeahead" name="inputPositionAgent" id="inputPositionAgent" placeholder="Search Positions">
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-sm-6'>
                            <div class='form-group'>
                                <button type="button" id="btnSubmitAgentPostionData" class="btn btn-success">Save</button>
                                <button type="button" id="btnCancelPosition" class="btn btn-default">Cancel</button>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>

            <!-- LOAD LEAD POSITIONS -->
            <form id="frmLeadPositionData">
                <fieldset>
                    <legend>Lead position assignment</legend>
                    <div class='row'>
                        <div class='col-sm-3'>
                            <div class='form-group'>
                                <input type="hidden" id="inputLeadIdPos" />
                                <label for="inputLeadShortname">Agent</label>
                                <input class="form-control typeahead" id="inputLeadShortname" name="inputLeadShortname" required="true" placeholder="Search Agents" size="30" type="text" />
                            </div>
                        </div>
                        <div class='col-sm-2'>
                            <div class='form-group'>
                                <label for="inputPositionLead">Position Assigned</label>
                                <input type="text" class="form-control typeahead" name="inputPositionLead" id="inputPositionLead" placeholder="Search Positions">
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-sm-6'>
                            <div class='form-group'>
                                <button type="button" id="btnSubmitLeadPostionData" class="btn btn-success">Save</button>
                                <button type="button" id="btnCancelPosition" class="btn btn-default">Cancel</button>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>
            <? 
              if($fecha!='')
            { ?>
            <div id="divFull">
                <div class="row">
                    <div class="col-md-12">
                        <center><h3><? echo date('l jS \of F Y', strtotime($fecha)) ?></h3></center>
                        <input type="hidden" id="inputFlightDate" name="inputFlightDate" value="<? echo $fecha; ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <!-- DETAIL -->
                        <table class="table table-condensed">
                            <thead>
                              <tr>
                                <th>Flight</th>
                                <th>Agents</th>
                                <th>Departure</th>
                                <th>Positions</th>
                              </tr>
                            </thead>
                            <?

                                foreach($mainlist as $header)
                                {
                            ?>
                            <tbody id="tblVuelos">
                              <tr <? if(isset($header['errormsj'])) echo 'class="warning"'; ?>>
                                <td><button type="button" onclick="loadRowVuelo('<? echo $header['idvuelo']; ?>','<? echo $header['linea']; ?>','<? echo $header['fecha']; ?>');" class="btn btn-link"><? echo $header['idvuelo']; ?></button>
                                </td>
                                <?
                                    if(isset($header['errormsj']))
                                    {
                                        ?>
                                            <td colspan=3><strong><? echo $header['mensaje']; ?></strong></td>
                                        <?
                                    }
                                    else
                                    {
                                        ?>
                                            <td id="rwa<? echo $header['idvuelo']; ?>">
                                                <? 
                                                $ind=1;
                                                while(isset($header['idagent'.$ind]))
                                                {   
                                                    echo $header['idagent'.$ind]; 
                                                    if(isset($header['idagent'. ($ind+1)])) echo '/';
                                                    $ind++;
                                                }

                                                ?></td>
                                            <td ><?
                                                if($idusuario == 'ADMIN' /* || $idusuario == 'R.HUET'*/)
                                                {
                                                    if($header['estimatedDep']!='')
                                                        $estimated= " / <span style='color:red'>" . date_format(date_create($header['estimatedDep']),"H:i") . '</span>';  
                                                    else
                                                        $estimated=' <button class="btn btn-link" data-toggle="modal" onclick="showFlifoVuelo(' . "'" . $header['idvuelo'] . "','" . $header['depAirport'] . "','" . $header['arrAirport'] . "','" . $header['estimatedDep'] . "','" . $header['estimatedArr'] . "','" . $header['scheduleDep'] . "','" . $header['scheduleArr'] . "','" . $header['termDep'] . "','" . $header['termArr'] . "','" . $header['gateDep'] . "','" . $header['gateArr'] . "','" . $header['aircraft'] . "','" . $header['duration'] . "'" .');">FLIFO</button>';
                                                    echo $header['salida'] . $estimated; 
                                                }
                                                else
                                                {
                                                    echo $header['salida'];
                                                }?></td>
                                            <td id="rwp<? echo $header['idvuelo']; ?>">
                                                <? 
                                                $ind=1;
                                                while(isset($header['pos'.$ind]))
                                                {   
                                                    echo $header['pos'.$ind]; 
                                                    if(isset($header['pos'. ($ind+1)])) echo '/';
                                                    $ind++;
                                                }
                                                ?>
                                                </td>
                                        <?
                                    }
                                    ?>
                              </tr>
                              <? } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-12">
                        <!-- AGENTS X WORKDAY -->
                        <table class="table table-condensed">
                            <thead>
                              <tr>
                                <th colspan='9'><center>AGENTS / POSITIONS</center></th>
                              </tr>
                            </thead>
                            <tr>
                                <td><center>PART TIME 4 HR</center></td>
                                <td><center>PART TIME 6HR</center></td>
                                <td><center>FULL TIME 8HR</center></td>
                            </tr>
                            <!-- PT 4 -->
                            <tr>
                                <td>
                                    <table class="table table-condensed" id="tblPt4">
                                        <?
                                           
                                            $counter = 0;
                                            do
                                            {
                                                if(isset($pt4[$counter])) $header = $pt4[$counter++]; else unset($header);

                                                $paicon = ($header['perfect'] == '') ? '&nbsp;&nbsp;&nbsp;' : '<span class="glyphicon glyphicon-ok-sign greenc"></span>';
                                                ?>
                                            <tr>
                                                <td><? echo $paicon; ?><? if(isset($header))  echo $header['shortname'] .  " (" .  $header['workday'] . ")" . '</td><td>' . $header['asignacion'] .  "</td><td style='font-weight:bold'>" .
                                    "<button type='button' onclick=\"loadRowAgentPosition('" . $header['uniqueid'] . "','" . $header['posicion'] . "','" . $header['idagente'] . "','" . $header['fecha'] . "'); \" class='btn btn-link'>" . $header['posicion'] . "</button>";?></td>
                                             </tr>
                                                <?
                                             
                                            }while($counter<sizeof($pt4)) ?>
                                       
                                    </table>
                                </td>
                          
                            <!-- PT 6 -->
                
                                <td>
                                    <table class="table table-condensed" id="tblPt6">
                                        <?
                                            $counter = 0;
                                            do
                                            {
                                                if(isset($pt6[$counter])) $header = $pt6[$counter++]; else unset($header);

                                                $paicon = $header['perfect'] == '' ? '&nbsp;&nbsp;&nbsp;' : '<span class="glyphicon glyphicon-ok-sign greenc"></span>';
                                                ?>
                                            <tr>
                                                <td><? echo $paicon; ?><? if(isset($header))  echo $header['shortname'] .  " (" .  $header['workday'] . ")" . '</td><td>' . $header['asignacion'] .  "</td><td style='font-weight:bold'>" .
                                    "<button type='button' onclick=\"loadRowAgentPosition('" . $header['uniqueid'] . "','" . $header['posicion'] . "','" . $header['idagente'] . "','" . $header['fecha'] . "'); \" class='btn btn-link'>" . $header['posicion'] . "</button>";?></td>
                                                
                                             </tr>
                                                <?
                                              
                                            }while($counter<sizeof($pt6)) ?>
                                       
                                    </table>
                                </td>
                            
                            <!-- FT -->
                            
                                <td>
                                    <table class="table table-condensed" id="tblFt8">
                                        <?
                                            $counter = 0;
                                            do
                                            {
                                                if(isset($ft8[$counter])) $header = $ft8[$counter++]; else unset($header);

                                                $paicon = $header['perfect'] == '' ? '&nbsp;&nbsp;&nbsp;' : '<span class="glyphicon glyphicon-ok-sign greenc"></span>';
                                                ?>
                                            <tr>
                                                <td><? echo $paicon; ?><? if(isset($header))  echo $header['shortname'] .  " (" .  $header['workday'] . ")" . '</td><td>' . $header['asignacion'] .  "</td><td style='font-weight:bold'>" .
                                    "<button type='button' onclick=\"loadRowAgentPosition('" . $header['uniqueid'] . "','" . $header['posicion'] . "','" . $header['idagente'] . "','" . $header['fecha'] . "'); \" class='btn btn-link'>" . $header['posicion'] . "</button>";?></td>
                                                
                                             </tr>
                                                <?
                                              
                                            }while($counter<sizeof($ft8)) ?>
                                       
                                    </table>
                                </td>
                            </tr>
                        </table>
                        
                        <!-- BMAS -->
                        <table class="table table-condensed">
                            <thead>
                              <tr>
                                <th colspan='6'><center>BMAS / EQUIPAJES</center></th>
                              </tr>
                            </thead>
                            <?

                                $counter = 0;
                                do
                                {

                                    if(isset($bmaslist[$counter])) $header = $bmaslist[$counter++]; else unset($header);
                                    if(isset($bmaslist[$counter])) $header2 = $bmaslist[$counter++]; else unset($header2);
                                    if(isset($bmaslist[$counter])) $header3 = $bmaslist[$counter++]; else unset($header3);

                                    $paicon1 = $header['perfect'] == '' ? '&nbsp;&nbsp;&nbsp;' : '<span class="glyphicon glyphicon-ok-sign greenc"></span>';
                                    $paicon2 = $header2['perfect'] == '' ? '&nbsp;&nbsp;&nbsp;' : '<span class="glyphicon glyphicon-ok-sign greenc"></span>';
                                    $paicon3 = $header3['perfect'] == '' ? '&nbsp;&nbsp;&nbsp;' : '<span class="glyphicon glyphicon-ok-sign greenc"></span>';
                            ?>
                            <tbody id="tblBmas" name="tblBmas" >
                              <tr>
                                <td><? echo $paicon1; ?><? if(isset($header))  echo $header['shortname'] .  " (" .  $header['workday'] . ')</td>' . "<td style='font-weight:bold'>" .
                                    "<button type='button' onclick=\"loadRowAgentPosition('" . $header['uniqueid'] . "','" . $header['posicion'] . "','" . $header['idagente'] . "','" . $header['fecha'] . "'); \" class='btn btn-link'>" . $header['posicion'] . "</button>";?></td>
                                <td><? echo $paicon2; ?><? if(isset($header2))  echo $header2['shortname'] . " (" .  $header['workday'] . ')</td>' . "<td style='font-weight:bold'>" .
                                    "<button type='button' onclick=\"loadRowAgentPosition('" . $header2['uniqueid'] . "','" . $header2['posicion'] . "','" . $header2['idagente'] . "','" . $header2['fecha'] . "'); \" class='btn btn-link'>" . $header2['posicion'] . "</button>";?></td>
                                <td><? echo $paicon3; ?><? if(isset($header3))  echo $header3['shortname'] . " (" .  $header['workday'] . ')</td>' . "<td style='font-weight:bold'>" .
                                    "<button type='button' onclick=\"loadRowAgentPosition('" . $header3['posicion'] . "','" . $header3['idagente'] . "','" . $header3['fecha'] . "'); \" class='btn btn-link'>" . $header3['posicion'] . "</button>";?></td>
                              </tr>
                              <? }while($counter<sizeof($bmaslist)) ?>
                            </tbody>
                         </table>
                            <!-- LEADS -->
                         <table class="table table-condensed">
                            <thead>
                              <tr>
                                <th colspan='6'><center>LEADS</center></th>
                              </tr>
                            </thead>
                            <?
                                $counter = 0;
                                do
                                {

                                    if(isset($leadslist[$counter])) $header = $leadslist[$counter++]; else unset($header);
                                    if(isset($leadslist[$counter])) $header2 = $leadslist[$counter++]; else unset($header2);
                                    if(isset($leadslist[$counter])) $header3 = $leadslist[$counter++]; else unset($header3);

                                    $paicon1 = $header['perfect'] != '' ? '&nbsp;&nbsp;&nbsp;' : '<span class="glyphicon glyphicon-ok-sign redc"></span>';
                                    $paicon2 = $header2['perfect'] != '' ? '&nbsp;&nbsp;&nbsp;' : '<span class="glyphicon glyphicon-ok-sign redc"></span>';
                                    $paicon3 = $header3['perfect'] != '' ? '&nbsp;&nbsp;&nbsp;' : '<span class="glyphicon glyphicon-ok-sign redc"></span>';

                            ?>
                            <tbody id="tblLeads" name="tblLeads">
                              <tr>
                                <td><? if(isset($header))  echo $header['shortname'] .  " (" .  $header['workday'] . ')</td>' . "<td style='font-weight:bold'>" .
                                    "<button type='button' onclick=\"loadRowLeadPosition('" . $header['uniqueid'] . "','" . $header['posicion'] . "','" . $header['idagente'] . "','" . $header['fecha'] . "'); \" class='btn btn-link'>" . $header['posicion'] . "</button>";?></td>
                                
                                <td><? if(isset($header2))  echo $header2['shortname'] . " (" .  $header2['workday'] . ')</td>' . "<td style='font-weight:bold'>" .
                                    "<button type='button' onclick=\"loadRowLeadPosition('" . $header2['uniqueid'] . "','" . $header2['posicion'] . "','" . $header2['idagente'] . "','" . $header2['fecha'] . "'); \" class='btn btn-link'>" . $header2['posicion'] . "</button>";?></td>
                                
                                <td><? if(isset($header3))  echo $header3['shortname'] . " (" .  $header3['workday'] . ')</td>' . "<td style='font-weight:bold'>" .
                                    "<button type='button' onclick=\"loadRowLeadPosition('" . $header3['uniqueid'] . "','" . $header3['posicion'] . "','" . $header3['idagente'] . "','" . $header3['fecha'] . "'); \" class='btn btn-link'>" . $header3['posicion'] . "</button>";?></td>
                              </tr>
                              <? }while($counter<sizeof($leadslist)) ?>
                            </tbody>
                        </table>
                            <!-- FOOTER -->
                        <table class="table table-condensed">
                            <?

                                $counter = 0;
                                do
                                {

                                    if(isset($footerlist[$counter])) $header = $footerlist[$counter++]; else unset($header);
                                    if(isset($footerlist[$counter])) $header2 = $footerlist[$counter++]; else unset($header2);
                                    if(isset($footerlist[$counter])) $header3 = $footerlist[$counter++]; else unset($header3);
                            
                            ?>
                            <tbody>
                              <tr>
                                <td colspan='2'><center><? echo $header['comentario']; ?></center></td>
                                <td colspan='3'><center><? echo $header2['comentario']; ?></center></td>
                              </tr>
                              <? }while($counter<sizeof($footerlist)) ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?
              }
            ?>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div id="modalFlifo" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            
            <h2><span class="label label-primary" id="pFlightInfo">Flight Info</span></h2>
          </div>
          <div class="modal-body">
            <center>
                <table width='100%'>
                    <tr>
                        <td><center>From</center></td>
                        <td><center>Duration</center></td>
                        <td><center>To</center></td>
                    </tr>
                    <tr>
                        <td><center><h2><p id="pdepAirport"></p></h2></center></td>
                        <td><center><p id="pDuration">1:05</p></center></td>
                        <td><center><h2><p id="parrAirport"></p></h2></center></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><center><p id="pAircraft">Aircraft: 738</p></center></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><center>Departure</center></td>
                        <td><center></center></td>
                        <td><center>Arrival</center></td>
                    </tr>
                    <tr>
                        <td><center><h3><p id="pDeparture">00:00</p></h3></center></td>
                        <td><center></td>
                        <td><center><h3><p id="pArrival">00:00</p></h3></center></td>
                    </tr>
                    <tr>
                        <td><center>Scheduled Departure</center></td>
                        <td><center></center></td>
                        <td><center>Scheduled Arrival</center></td>
                    </tr>
                    <tr>
                        <td><center><h3><p id="pSDeparture">00:00</p></h3></center></td>
                        <td><center></td>
                        <td><center><h3><p id="pSArrival">00:00</p></h3></center></td>
                    </tr>
                    <tr>
                        <td><center>Terminal/Gate</center></td>
                        <td><center></center></td>
                        <td><center>Terminal/Gate</center></td>
                    </tr>
                    <tr>
                        <td><center><h3><p id="pDTermGate">T3/C1</p></h3></center></td>
                        <td><center></td>
                        <td><center><h3><p id="pATermGate">N/D11</p></h3></center></td>
                    </tr>
                </table>
            </center>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>
    <!-- End Horizontal Form -->
    
    
    <script type="text/javascript">
    $("#frmVueloData").hide();
    $("#divAddNewHere").hide();
    $("#divAddNewHere2").hide();
    
    $('#myPleaseWait').modal('show');
    var d = new Date();
    var ini = d.getTime();
    $('#frmLeadPositionData').hide()
    $("#frmAgentPositionData").hide();
    $(document).ready(function(){

        var positions = [<? foreach($posiciones as $position) { echo "'" . $position['code'] . "',"; } ?>];
        var agenteslista = [<? foreach($fullagents as $agent) { echo "{ id : '" . $agent['idagente'] . "', name : '" . $agent['shortname'] . "', status : '" . $agent['comment'] . "'},"; } ?>];

        $("#btnPrintCUNOP").click(function(){
            var headstr = "<html><head><title></title></head><body><center><h2><span class='label label-primary'>CUNOP</span></h2>";
            var footstr = "</body>";
            var newstr = $("#divFull")[0].innerHTML;
            var oldstr = document.body.innerHTML;
            document.body.innerHTML = headstr+newstr+footstr;
            document.body.style.paddingTop = '0px';
            document.body.style.paddingBottom = '0px';
            document.body.style.paddingBottom = '0px';
            $('body').css('font-size','12px');
            $('.table-condensed tbody tr td').css('padding','0px');
            window.print();
            document.body.innerHTML = oldstr;
            return false;
        });

        $("#btnPrintRoster").click(function(){
            var headstr = "<html><head><title></title></head><body><center><h2><span class='label label-primary'>CUN Roster</span></h2>";
            var footstr = "</body>";
            var newstr = $("#divRoster")[0].innerHTML;
            var oldstr = document.body.innerHTML;
            document.body.innerHTML = headstr+newstr+footstr;
            document.body.style.paddingTop = '0px';
            document.body.style.paddingBottom = '0px';
            document.body.style.paddingBottom = '0px';
            $('body').css('font-size','12px');
            $('.table-condensed tbody tr td').css('padding','0px');
            window.print();
            document.body.innerHTML = oldstr;
            return false;
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

        $('#inputAgentX4.typeahead').typeahead({
            source : agenteslista,
            display : 'name',
            val : 'id'
        });

        $('#inputPositionAgent.typeahead').typeahead({
            source : positions,
        });


        /*
         Para usarse cuando quieran bloquear cambios en agentes castigados
        $('#inputAgentShortname').on('blur',function(e){
            if($("#typeaheadflag").val()=="") 
            {
                e.stopImmediatePropagation();
                e.preventDefault();
                $('#inputAgentShortname').focus();
                console.log('stop blur');
            }
        });

        $('#inputAgentShortname').on('focus',function(e){
            $("#typeaheadflag").val("");
            console.log('focus');
        });

        */

        $('#inputAgentShortname.typeahead').typeahead({
            source : agenteslista,
            display : 'name',
            val : 'id',
            onSelect : function(item)
            {
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

                        $("#typeaheadflag").val("1");

                        var newpos = [];
                        for(var i=0;i<result.length;i++)
                        {
                            newpos.push({id : result[i]['code'], name : result[i]['description']});
                            //console.log(result[i]['code']); 
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

        $("#btnAddFlightAgent").click(function(){
            //return;
            if($('#divAddNewHere').is(":hidden"))
            {
                $('#divAddNewHere').show();
                return;
            }
            else if($('#divAddNewHere').is(":visible") && $('#divAddNewHere2').is(":hidden"))
            {
                $('#divAddNewHere2').show();
                $('#btnAddFlightAgent').hide();
            }
        });

        $('#btnUpdateDeparture').click(function(){

            var lidempresa = $("#inputIdEmpresa").val();
            var lidoficina = $("#inputIdOficina").val();
            var lidvuelo = $("#inputFlight").val();
            var lfecha = $("#inputFlightDate").val();
            var ldeparture = $("#inputDeparture").val();
            var lusuario = $("#inputUsuario").val();

            var agent = {
                idempresa : lidempresa,
                idoficina : lidoficina,
                idvuelo : lidvuelo,
                fecha : lfecha,
                usuario : lusuario,
                departure : ldeparture
            };
            var request = $.ajax({
                url: 'webcunop/updateflightdeparture',
                type: 'POST',
                data: agent,
                beforeSend:function(){
                    console.log('sending...');
                    $('#myPleaseWait').modal('show');
                },
                success:function(result){
                    
                    console.log('sent!');
                    //console.log(result);

                    RefreshScheduler();
                    RefreshFlights();
                    $("#frmVueloData").hide();

                }, 
                error:function(exception){console.log(exception);}
                
            });
        })

        $("#btnUpdateMessage").click(function(){

            var lidempresa = $("#inputIdEmpresa").val();
            var lidoficina = $("#inputIdOficina").val();
            var lidvuelo = $("#inputFlight").val();
            var lfecha = $("#inputFlightDate").val();
            var lmensaje = $("#inputMensaje").val();
            var lusuario = $("#inputUsuario").val();

            var agent = {
                idempresa : lidempresa,
                idoficina : lidoficina,
                idvuelo : lidvuelo,
                fecha : lfecha,
                usuario : lusuario,
                mensaje : lmensaje
            };
            var request = $.ajax({
                url: 'webcunop/updateflightmessage',
                type: 'POST',
                data: agent,
                beforeSend:function(){
                    console.log('sending...');
                    $('#myPleaseWait').modal('show');
                },
                success:function(result){
                    
                    console.log('sent!');
                    //console.log(result);

                    RefreshScheduler();
                    RefreshFlights();
                    $("#frmVueloData").hide();

                }, 
                error:function(exception){console.log(exception);}
                
            });
        })

        $("#btnDoDeleteExtraAgent").click(function(){

            var lidempresa = $("#inputIdEmpresa").val();
            var lidoficina = $("#inputIdOficina").val();
            var luniqueid = $("#inputUniqueIdEE").val();
            var lidvuelo = $("#inputFlight").val();
            var lfecha = $("#inputFlightDate").val();

            var agent = {
                idempresa : lidempresa,
                idoficina : lidoficina,
                uniqueid : luniqueid,
                idvuelo : lidvuelo,
                fecha : lfecha
            };
            var request = $.ajax({
                url: 'webcunop/deleteextraagent',
                type: 'POST',
                data: agent,
                beforeSend:function(){
                    console.log('sending...');
                    $('#myPleaseWait').modal('show');
                },
                success:function(result){
                    
                    console.log('sent!');
                    //console.log(result);

                    RefreshScheduler();
                    RefreshFlights();
                    $("#frmVueloData").hide();

                }, 
                error:function(exception){console.log(exception);}
                
            });

        });

        // con la info del nuevo agente que auxilia al vuelo, la ingresa
        $("#btnDoAddFlightAgent").click(function(){

            // valida que haya un agente ingresado
            var lidempresa = $("#inputIdEmpresa").val();
            var lidoficina = $("#inputIdOficina").val();

            var lfecha = $("#inputFlightDate").val();
            var lposicion = $("#inputPosicionX").val();
            var lshortname = $("#inputAgentX").val();
            var lusuario = $("#inputUsuario").val();
            var lidvuelo = $("#inputFlight").val();
            var llinea =$("#inputLinea").val();
           
            var agent = {
                idempresa : lidempresa,
                idoficina : lidoficina,
                idagente : lshortname,
                idvuelo : lidvuelo,
                fecha : lfecha,
                linea : llinea,
                posicion : lposicion,
                usuario : lusuario
            };
            
            
            var request = $.ajax({
                url: 'webcunop/addextraagent',
                type: 'POST',
                data: agent,
                beforeSend:function(){
                    console.log('sending...');
                    $('#myPleaseWait').modal('show');
                },
                success:function(result){
                    
                    console.log('sent!');
                    $("#frmVueloData").hide();
                    $('#divAddNewHere').hide();
                    $('#btnAddFlightAgent').show();
                    //console.log(result);
                    RefreshScheduler();
                    RefreshFlights();

                    $('#myPleaseWait').modal('hide');

                }, 
                error:function(exception){console.log(exception);}
                
            });

        });

               // con la info del nuevo agente que auxilia al vuelo, la ingresa
        $("#btnDoAddFlightAgent2").click(function(){

            console.log('add flight agent');

            // valida que haya un agente ingresado
            var lidempresa = $("#inputIdEmpresa").val();
            var lidoficina = $("#inputIdOficina").val();

            var lfecha = $("#inputFlightDate").val();
            var lposicion = $("#inputPosicionX4").val();
            var lshortname = $("#inputAgentX4").val();
            var lusuario = $("#inputUsuario").val();
            var lidvuelo = $("#inputFlight").val();
            var llinea =$("#inputLinea").val();
           
            var agent = {
                idempresa : lidempresa,
                idoficina : lidoficina,
                idagente : lshortname,
                idvuelo : lidvuelo,
                fecha : lfecha,
                linea : llinea,
                posicion : lposicion,
                usuario : lusuario
            };
            
            
            var request = $.ajax({
                url: 'webcunop/addextraagent',
                type: 'POST',
                data: agent,
                beforeSend:function(){
                    console.log('sending...');
                    $('#myPleaseWait').modal('show');
                },
                success:function(result){
                    
                    console.log('sent!');
                    $("#frmVueloData").hide();
                    $('#divAddNewHere').hide();
                    $('#btnAddFlightAgent').show();
                    //console.log(result);
                    RefreshScheduler();
                    RefreshFlights();

                    $('#myPleaseWait').modal('hide');

                }, 
                error:function(exception){console.log(exception);}
                
            });

        });

       
        $("#btnSubmitFlightData").click(function(){
            
            var r = false;
            var cancel = false;
            DoPostCambio(false);
            
        });
           

        // al guardar un cambio en el agent schedule del dia
        $("#btnSubmitAgentPostionData").click(function(){

            if($("#typeaheadflag").val()=="") return;

            var lidempresa = $("#inputIdEmpresa").val();
            var lidoficina = $("#inputIdOficina").val();
            var luniqueid = $("#inputUniqueId").val();
            var lfecha = $("#inputFlightDate").val();
            var lposicion = $("#inputPositionAgent").val();
            var lagente = $("#inputAgenteIdPos").val();
            var lshortname = $("#inputAgentShortname").val();
            var lusuario = $("#inputUsuario").val();
            
            $("#frmAgentPositionData").hide();  
            var agent = {
                idempresa : lidempresa,
                idoficina : lidoficina,
                uniqueid : luniqueid,
                fecha : lfecha,
                posicion : lposicion,
                agenteid : lagente,
                shortname : lshortname,
                usuario : lusuario
            };
            
            $.each(agent, function(index, value) {
                console.log(value);
            });
            
            var request = $.ajax({
                url: 'webcunop/switchagente',
                type: 'POST',
                data: agent,
                beforeSend:function(){
                    console.log('sending...');
                    $('#myPleaseWait').modal('show');
                },
                success:function(result){
                    
                    console.log('sent!');
                    //console.log(result);
                    RefreshScheduler();
                    RefreshFlights();
                    $('#myPleaseWait').modal('hide');
                  
                }, 
                error:function(exception){console.log(exception);}
                
            });
        });


        // al guardar un cambio en el agent schedule del dia
        $("#btnSubmitLeadPostionData").click(function(){

            var lidempresa = $("#inputIdEmpresa").val();
            var lidoficina = $("#inputIdOficina").val();
            var luniqueid = $("#inputUniqueId").val();
            var lfecha = $("#inputFlightDate").val();
            var lposicion = $("#inputPositionLead").val();
            var lagente = $("#inputLeadIdPos").val();
            var lshortname = $("#inputLeadShortname").val();
            var lusuario = $("#inputUsuario").val();
            
            $("#frmLeadPositionData").hide();  
            var agent = {
                idempresa : lidempresa,
                idoficina : lidoficina,
                uniqueid : luniqueid,
                fecha : lfecha,
                posicion : lposicion,
                agenteid : lagente,
                shortname : lshortname,
                usuario : lusuario
            };
            
            $.each(agent, function(index, value) {
                console.log(value);
            });
            
            var request = $.ajax({
                url: 'webcunop/switchlead',
                type: 'POST',
                data: agent,
                beforeSend:function(){
                    console.log('sending...');
                    $('#myPleaseWait').modal('show');
                },
                success:function(result){
                    
                    console.log('sent!');
                    //console.log(result);
                    RefreshScheduler();
                    RefreshFlights();
                    $('#myPleaseWait').modal('hide');
                  
                }, 
                error:function(exception){console.log(exception);}
                
            });
        });

        // cuando cambia el agente
        $("#inputAgentShortname").blur(function(e){
            // al salir de aqui ya debe haber un agente

        });
        
        $("#btnCancel").click(function(){
            $("#inputAgentId").val('');
            $("#inputDaysOff").val('');
            $("#frmVueloData").hide();  
        });

        $("#btnCancelPosition").click(function(){
            $("#frmAgentPositionData").hide();
        })
            
        $('#myPleaseWait').modal('hide');
        var d= new Date();
        var fin = d.getTime();

        console.log('loading ' + (fin - ini));
        return false;
    })

function showFlifoVuelo(idvuelo,depAirport,arrAirport,estimatedDep,estimatedArr,scheduleDep,scheduleArr,termDep,termArr,gateDep,gateArr,aircraft,duration)
{
    if(depAirport=='')
        return;
    $("#modalFlifo").modal();
    $("#pdepAirport")[0].innerHTML = depAirport;
    $("#parrAirport")[0].innerHTML = arrAirport;
    $("#pFlightInfo")[0].innerHTML = "Flight Info " + idvuelo;
    $("#pDeparture")[0].innerHTML = estimatedDep;
    $("#pArrival")[0].innerHTML = estimatedArr;
    $("#pSDeparture")[0].innerHTML = scheduleDep;
    $("#pSArrival")[0].innerHTML = scheduleArr;
    $("#pDTermGate")[0].innerHTML = ((termDep=='')?'NA':termDep) + "/" + ((gateDep=='')?'NA':gateDep);
    $("#pATermGate")[0].innerHTML = termArr + "/" + gateArr;
    $("#pAircraft")[0].innerHTML = "Aircraft " + aircraft;

    var hora = parseInt(duration/60);
    var min = duration - (hora*60);
    $("#pDuration")[0].innerHTML = (hora>9?hora:'0' + hora) + ":" + (min>9?min:'0'+min);

}

function RefreshScheduler()
{
    var lidempresa = $("#inputIdEmpresa").val();
    var lidoficina = $("#inputIdOficina").val();
    var lfecha = $("#inputFlightDate").val();

    var fields = {
        idempresa : lidempresa,
        idoficina : lidoficina,
        fecha : lfecha
    }
    var request = $.ajax({
        url: 'webcunop/asyncloadstationschedule',
        type: 'POST',
        data: fields,
        beforeSend:function(){
            console.log('sending...');
            $('#myPleaseWait').modal('show');
        },
        success:function(result){
            
            console.log('sent!');
            //console.log(result);
            $('#tblPt4 tr').remove();
            $('#tblPt6 tr').remove();
            $('#tblFt8 tr').remove();
            $('#tblBmas tr').remove();
            $('#tblLeads tr').remove();

            $.each(result[0],function(row,agents){

                var html = "<tr>" +
                           "<td><i class='fa fa-check-circle-o'></i>" + agents['shortname'] + " (" + agents['workday'] + ")" + "</td>" +
                           "<td>" + agents['asignacion'] + "</td><td style='font-weight:bold'>" +
                            "<button type='button' onclick=\"loadRowAgentPosition('" + agents['uniqueid'] + "','" + agents['posicion'] + "','" + agents['idagente'] + "','" + agents['fecha'] + "'); \" class='btn btn-link'>" + agents['posicion'] + "</button></td>" +
                            "</tr>";
                $("#tblPt4").append(html);
            });

            $.each(result[1],function(row,agents){

                var html = "<tr>" +
                           "<td>" + agents['shortname'] + " (" + agents['workday'] + ")" + "</td>" +
                           "<td>" + agents['asignacion'] + "</td><td style='font-weight:bold'>" +
                            "<button type='button' onclick=\"loadRowAgentPosition('" + agents['uniqueid'] + "','" + agents['posicion'] + "','" + agents['idagente'] + "','" + agents['fecha'] + "'); \" class='btn btn-link'>" + agents['posicion'] + "</button></td>" +
                            "</tr>";
                $("#tblPt6").append(html);
            });

            $.each(result[2],function(row,agents){

                var html = "<tr>" +
                           "<td>" + agents['shortname'] + " (" + agents['workday'] + ")" + "</td>" +
                           "<td>" + agents['asignacion'] + "</td><td style='font-weight:bold'>" +
                            "<button type='button' onclick=\"loadRowAgentPosition('" + agents['uniqueid'] + "','" + agents['posicion'] + "','" + agents['idagente'] + "','" + agents['fecha'] + "'); \" class='btn btn-link'>" + agents['posicion'] + "</button></td>" +
                            "</tr>";
                $("#tblFt8").append(html);
            });

            var bmas = '';
            var counter = 0;
            $.each(result[3], function(row, agents){
                console.log(agents['shortname'] + " " + agents['posicion']);
                if(counter == 0)
                {
                    bmas +=  "<tr>";
                }
                bmas +=      "<td>" + agents['shortname'] + " (" +  agents['workday'] + ")</td><td style='font-weight:bold'>" +
                             "<button type='button' onclick=\"loadRowAgentPosition('" + agents['uniqueid'] + "','" + agents['posicion'] + "','" + agents['idagente'] + "','" + agents['fecha'] + "'); \" class='btn btn-link'>" + agents['posicion'] + "</button></td>";
                if(counter == 2)
                {
                    bmas += "</tr>";
                    counter = 0;
                }
                else
                {
                    counter ++;
                }
            });
            $("#tblBmas").append(bmas);

            html = '';
            var counter = 0;
            $.each(result[4], function(row, agents){
                console.log(agents['shortname'] + " " + agents['posicion']);
                if(counter == 0)
                {
                    html +=  "<tr>";
                }
                html +=      "<td>" + agents['shortname'] + " (" +  agents['workday'] + ")</td><td style='font-weight:bold'>" +
                             "<button type='button' onclick=\"loadRowLeadPosition('" + agents['uniqueid'] + "','" + agents['posicion'] + "','" + agents['idagente'] + "','" + agents['fecha'] + "'); \" class='btn btn-link'>" + agents['posicion'] + "</button></td>";
                if(counter == 2)
                {
                    html += "</tr>";
                    counter = 0;
                }
                else
                {
                    counter ++;
                }
            });
            $("#tblLeads").append(html);
            $('#myPleaseWait').modal('hide');
        }, 
        error:function(exception){console.log(exception);}
        
    });
}

function RefreshFlights()
{
    var lidempresa = $("#inputIdEmpresa").val();
    var lidoficina = $("#inputIdOficina").val();
    var lfecha = $("#inputFlightDate").val();

    var fields = {
        idempresa : lidempresa,
        idoficina : lidoficina,
        fecha : lfecha
    }

    var request = $.ajax({
        url: 'webcunop/asyncloadstationdate',
        type: 'POST',
        data: fields,
        beforeSend:function(){
            console.log('sending...');
            $('#myPleaseWait').modal('show');
        },
        success:function(result){
            
            console.log('sent!');
            //console.log(result);
            $('#tblVuelos tr').remove();

            $.each(result, function(row, flight){

                var tdclass = flight['errormsj'] != undefined ? 'class="warning"' : '';

                var html = "<tr " + tdclass + ">" +
                    "<td><button type='button' onclick=\"loadRowVuelo('" + flight['idvuelo'] + "','" + flight['linea'] + "','" + flight['fecha'] + "');\" class='btn btn-link'>" + flight['idvuelo'] + "</button>";
                html += "</td>";
                if(flight['errormsj'])
                {
                    html += "<td colspan=3><strong>" + flight['errormsj'];
                    html += "</strong></td>";
                }
                else
                {
                    var ind=1;
                    var htmlagentes = '';
                    var htmlpos = '';
                    var separator = '';
                    while(typeof flight['idagent'+ind] !== 'undefined')
                    {   
                        if(typeof flight['idagent'+(ind+1)] !== 'undefined')
                            separator = " / ";
                        htmlagentes += flight['idagent'+ind] + separator;
                        htmlpos += flight['pos'+ind] + separator;

                        ind++;
                        separator = "";
                    }
                    html += "<td>" + htmlagentes + "</td>" +
                            "<td>" + flight['salida'] + "</td>" + 
                            "<td>" + htmlpos + "</td>";
                }
                
              html += "</tr>";
            $("#tblVuelos").append(html);
            });

            $('#myPleaseWait').modal('hide');
        }, 
        error:function(exception){console.log(exception);}
        
    });
   
}

function DoPostCambio(r)
{

    var lidempresa = $("#inputIdEmpresa").val();
    var lidoficina = $("#inputIdOficina").val();
    var lidvuelo = $("#inputFlight").val();
    var lfecha = $("#inputFlightDate").val();
    var llinea = $("#inputLinea").val();
    var lagente1 = $("#inputAgent1").val();
    var lposicion1 = $("#inputPosicion1").val();
    var lagente2 = $("#inputAgent2").val();
    var lposicion2 = $("#inputPosicion2").val();
    var lusuario = $("#inputUsuario").val();
    
    $("#frmAgentData").hide();  

    //var r = confirm("You wish to change all the agents flight assignments");
    var followup;
    if(r == true) { followup = 1; } else { followup = 0; }

    var agent = {
        idempresa : lidempresa,
        idoficina : lidoficina,
        idvuelo : lidvuelo,
        fecha : lfecha,
        linea : llinea,
        agente1 : lagente1,
        posicion1 : lposicion1,
        agente2 : lagente2,
        posicion2 : lposicion2,
        followup : followup,
        usuario : lusuario
    };
    
    $.each(agent, function(index, value) {
        console.log(value);
    });

    
    var request = $.ajax({
        url: 'webcunop/postcambio',
        type: 'POST',
        data: agent,
        beforeSend:function(){
            console.log('sending...');
            $('#myPleaseWait').modal('show');
        },
        success:function(result){
            
            console.log('sent!');
            console.log(result);
            
            $("#frmVueloData").hide(); 
            
            RefreshFlights();

            $('#myPleaseWait').modal('hide');
            
        }, 
        error:function(exception){console.log(exception);}
        
    });
}

function loadRowVuelo(idvuelo,linea,fecha)
  {
    if(<? echo $isadmin=='1'?0:1; ?>)
        return;
    $("#frmVueloData").show();
    $('#divAddNewHere').hide();
    $('#btnAddFlightAgent').show();
    $("html, body").animate({ scrollTop: 0 }, "fast");

    idempresa = $("#inputIdEmpresa").val();
    idoficina = $("#inputIdEmpresa").val();
    var offset = new Date().getTimezoneOffset();

    $("inputFlight").val(idvuelo);
    var infoData = { 
              idempresa : idempresa,
              idoficina : idoficina,
              idvuelo : idvuelo,
              fecha : fecha
             };
    $.ajax({
      url: '<? echo base_url(); ?>webcunop/loadflightdetail',
      type: 'POST',
      data : infoData,
      beforeSend:function(){
        $('#myPleaseWait').modal('show');
      },
      success:function(data){
        console.log('loading data...'); 
        
        var agents = data.flight;
        var posiciones = data.posiciones;


        $("#inputMensaje").val(data.mensaje);
        $("#inputDeparture").val(data.departure);

        if(agents!=null)
        {
            if(agents.length > 1)
            {
                //LoadAgentsAssigned(agents);
                console.log(agents[0].idagente);
                $("#inputFlight").val(agents[0].idvuelo);
                $("#inputLinea").val(agents[0].linea);
                $("#inputAgent1").val(agents[0].idagente);
                $("#inputPosicion1").val(agents[0].posicion);
                $("#inputAgent2").val(agents[1].idagente);
                $("#inputPosicion2").val(agents[1].posicion);


                if(agents.length > 2)
                {
                    $('#divAddNewHere').show();
                    $("#inputAgentX").val(agents[2].idagente);
                    $("#inputPosicionX").val(agents[2].posicion);
                    $("#inputUniqueIdEE").val(agents[2].uniqueid);
                }
                if(agents.length > 3)
                {
                    $('#divAddNewHere2').show();
                    $("#inputAgentX4").val(agents[3].idagente);
                    $("#inputPosicionX4").val(agents[3].posicion);
                    $("#inputUniqueIdEE4").val(agents[3].uniqueid);
                    $('#btnAddFlightAgent').hide();
                }
            }
        }
        else
        {
          $("#inputFlight").val(idvuelo);
          $("#inputLinea").val(linea);
          $("#inputAgent1").val('');
          $("#inputPosicion1").val('');
          $("#inputAgent2").val('');
          $("#inputPosicion2").val('');   
        }

        if(posiciones.length > 0)
        {
            $('#inputPosicion1')
                .find('option')
                .remove()
                .end();
            $('#inputPosicion2')
                .find('option')
                .remove()
                .end();

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
                        
                $("#inputPosicion1").append($("<option></option>").val(pos.code).html(pos.code + ' FROM ' + stime + ' TO ' + etime));
                $("#inputPosicion2").append($("<option></option>").val(pos.code).html(pos.code + ' FROM ' + stime + ' TO ' + etime));
            }
            if(agents!=null)
            {
                $("#inputPosicion1").val(agents[0].posicion);
                $("#inputPosicion2").val(agents[1].posicion);
            }
        }
      
        $('#myPleaseWait').modal('hide');
      }
    });
  }

function loadRowAgentPosition(uniqueid, posicion, agenteid, fecha)
{
    if(<? echo $isadmin=='1'?0:1; ?>)
        return;
    $("#frmAgentPositionData").show();
    $("html, body").animate({ scrollTop: 0 }, "fast");

    idempresa = $("#inputIdEmpresa").val();
    idoficina = $("#inputIdEmpresa").val();
    
    var infoData = { 
              uniqueid : uniqueid,
              idempresa : idempresa,
              idoficina : idoficina,
              posicion : posicion,
              agenteid : agenteid,
              fecha : fecha
             };
    $.ajax({
      url: 'webcunop/loadagentschedule',
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
          $("#inputAgentShortname").val(data[0].shortname);
          $("#inputPositionAgent").val(data[0].posicion);
          $("#inputAgenteIdPos").val(data[0].idagente);
          $("#inputUniqueId").val(data[0].uniqueid);
        }
      
        $('#myPleaseWait').modal('hide');
      }
    });
}

function loadRowLeadPosition(uniqueid, posicion, agenteid, fecha)
{
    if(<? echo $isadmin=='1'?0:1; ?>)
        return;
    $("#frmLeadPositionData").show();
    $("html, body").animate({ scrollTop: 0 }, "fast");

    idempresa = $("#inputIdEmpresa").val();
    idoficina = $("#inputIdEmpresa").val();
    
    var infoData = { 
              uniqueid : uniqueid,
              idempresa : idempresa,
              idoficina : idoficina,
              posicion : posicion,
              agenteid : agenteid,
              fecha : fecha
             };
    $.ajax({
      url: 'webcunop/loadleadschedule',
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
          $("#inputLeadShortname").val(data[0].shortname);
          $("#inputPositionLead").val(data[0].posicion);
          $("#inputLeadIdPos").val(data[0].idagente);
          $("#inputUniqueId").val(data[0].uniqueid);
        }
      
        $('#myPleaseWait').modal('hide');
      }
    });
}

function loadFlightDetail(idempresa, idoficina, idvuelo, qdate)
{
    if(<? echo $isadmin=='1'?0:1; ?>)
        return;
    var infoData = {  idempresa : idempresa,
                      idoficina : idoficina,
                      idvuelo : idvuelo,
                      fecha : qdate
                     };
    $.ajax({
        url: 'webcunop/loadflightdetail',
        type: 'POST',
        data : infoData,
        beforeSend:function(){
            $('#myPleaseWait').modal('show');
        },
        success:function(data){
            console.log('loading data...');
            var agent = jQuery.parseJSON(data)[0];
            console.log('shortname ' + agent.dia1);
            $("#inputAgentId").val(idagente);
            $("#inputDaysOff").val(agent.dia1);
            
            $('#myPleaseWait').modal('hide');
        }
    });
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