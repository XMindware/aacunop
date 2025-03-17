<!-- Begin page content -->
<script src="<? echo base_url(); ?>assets/js/typeahead.min.js"></script>
<div class="container lhr777">
  
<style>

  #wrap {
    width: 1100px;
    margin: 0 auto;
    }

  #external-events {
    float: left;
    width: 150px;
    padding: 0 10px;
    text-align: left;
    }

  #external-events h4 {
    font-size: 16px;
    margin-top: 0;
    padding-top: 1em;
    }

  .external-event { /* try to mimick the look of a real event */
    margin: 10px 0;
    padding: 2px 4px;
    background: #3366CC;
    color: #fff;
    font-size: .85em;
    cursor: pointer;
    }

  #external-events p {
    margin: 1.5em 0;
    font-size: 11px;
    color: #666;
    }

  #external-events p input {
    margin: 0;
    vertical-align: middle;
    }

  #calendar {
    width: 300px;
    background-color: #FFFFFF;
      border-radius: 6px;
        box-shadow: 0 1px 2px #C3C3C3;
    }

  .glyphicon { margin-right:5px; }
.glyphicon-new-window { margin-left:5px; }
.panel-body .radio,.panel-body .checkbox {margin-top: 0px;margin-bottom: 0px;}
.panel-body .list-group {margin-bottom: 0;}
.margin-bottom-none { margin-bottom: 0; }
.panel-body .radio label,.panel-body .checkbox label { display:block; }

</style>
  <script>


    $(document).ready(function() {

      var date = new Date();
      var d = date.getDate();
      var m = date.getMonth();
      var y = date.getFullYear();

      /*  className colors

      className: default(transparent), important(red), chill(pink), success(green), info(blue)

      */


      /* initialize the external events
      -----------------------------------------------------------------*/

      $('#external-events div.external-event').each(function() {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        };

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject);

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex: 999,
          revert: true,      // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        });

      });


      /* initialize the calendar
      -----------------------------------------------------------------*/

      var calendar =  $('#calendar').fullCalendar({
        header: {
          left: 'title',
          center: false,
          right: 'prev,next today'
        },
        aspectRatio :1,
        editable: false,
        height : 'parent',
        firstDay: 1, //  1(Monday) this can be changed to 0(Sunday) for the USA system
        selectable: false,
        defaultView: 'month',
        axisFormat: 'h:mm',
        columnFormat: {
                  month: 'ddd',    // Mon
                  week: 'ddd d', // Mon 7
                  day: 'dddd M/d',  // Monday 9/7
                  agendaDay: 'dddd d'
              },
              titleFormat: {
                  month: 'MMMM yyyy', // September 2009
                  week: "MMMM yyyy", // September 2009
                  day: 'MMMM yyyy'                  // Tuesday, Sep 8, 2009
              },
        allDaySlot: false,
        weekMode : false,
        selectHelper: true,
        select: function(start, end, allDay) {
          var title = prompt('Event Title:');
          if (title) {
            calendar.fullCalendar('renderEvent',
              {
                title: title,
                start: start,
                end: end,
                allDay: allDay
              },
              true // make the event "stick"
            );
          }
          calendar.fullCalendar('unselect');
        },
        droppable: true, // this allows things to be dropped onto the calendar !!!
        drop: function(date, allDay) { // this function is called when something is dropped

          // retrieve the dropped element's stored Event Object
          var originalEventObject = $(this).data('eventObject');

          // we need to copy it, so that multiple events don't have a reference to the same object
          var copiedEventObject = $.extend({}, originalEventObject);

          // assign it the date that was reported
          copiedEventObject.start = date;
          copiedEventObject.allDay = allDay;

          // render the event on the calendar
          // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
          $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

          // is the "remove after drop" checkbox checked?
          if ($('#drop-remove').is(':checked')) {
            // if so, remove the element from the "Draggable Events" list
            $(this).remove();
          }

        },
        navLinks : true,
        navLinkDayClick : function(date, jsEvent) {
            console.log('day', date.toISOString());
            console.log('coords', jsEvent.pageX, jsEvent.pageY);
        },
        
        events: [

        <?

          foreach($monthlyschedule as $pos)
          {

            $color = 'important';
            $posicion = $pos['posicion'];
            switch ($pos['tipocambio']) {
              case 'Cover':
                $tipocambio = 'Cover';
                break;
              case 'Switch':
                $tipocambio = 'SW HR';
                break;
              case 'Day Off':
                $tipocambio = 'DAY OFF';
                break;
              default:
                
                break;
            }

            if($pos['idagentec'] != '')
            {
              if($pos['idagente'] != $pos['idagentec'] )
                $agentecambio = $pos['shortname'];
              else
                $agentecambio = $pos['agentecambio'];
            }
            if($pos['status'] == 'AUT'){
              $color = 'bg-success';
              $posicion = $pos['posicion'] . ' ' . $tipocambio. ' ' . $agentecambio;
            }
            else if($pos['status'] == 'ACC' ){
              $color='bg-danger';
              $posicion = $pos['posicion'] . ' ' . $tipocambio . ' ' . $agentecambio;
            }
            else if($pos['status'] == 'REQ'){
              $color='warning';
              $posicion = $pos['posicion'] . ' ' . $tipocambio . ' ' . $agentecambio;
            }
            else if($pos['status'] == 'DEC'){
              $color='bg-danger';
              $posicion = $pos['posicion'] . ' Declined';
            }
            else
              $color='bg-info';
            ?>

          {
            title: '<? echo $posicion; ?>',
            start: '<? echo $pos['fecha']; ?>',
            url : '<? echo base_url(); ?>webcunop/index?fecha=<? echo $pos['fecha']; ?>',
            className : '<? echo $color; ?>'
          },
          <?
        }
        ?>
        ],
      });


    });

  </script>
  <div class="page-header">
    <h1 style="text-align: center">Bienvenido de nuevo <?=$this->session->userdata('fullname') ?></h1>
     
     <input type="hidden" id="inputIdEmpresa" value="<? echo $idempresa; ?>" />
     <input type="hidden" id="inputIdOficina" value="<? echo $idoficina; ?>" />
     <input type="hidden" id="inputUsuario" value="<? echo $idusuario; ?>" />
     <input type="hidden" id="inputIdAgente" value="<? echo $this->session->userdata('idagente'); ?>" />
    <?
    if($this->session->userdata('shortname')=="R.HUET"){
      echo "<h1 style='text-align: center; font-color:\"red\"'>YOUR MAJESTY</h1>";
      ?>
        <center><img src="<? echo base_url(); ?>assets/images/crown.jpg" height="100"/></center>
        <?
    }
    ?>

  </div>
  <div class="panel-body">
    <div class="panel-heading">
      <div class='row'>
        <div class='col-sm-6'>
          <div class='form-group'>
          <?
              if(sizeof($newslist)> 0){ ?>
            <h2><span class="label label-primary"> News </span></h2>
              
              <ul>
                <?

                  foreach($newslist as $row)
                  {
        
                ?>
                  <li><b><? echo $row['author']; ?> - <? echo $row['title']; ?></b><br/>
                  <? echo $row['fullnews']; ?></li>
                  
                  <? }
                  ?>      
                  
              </ul>
              <?
              }
                ?>
            <h2><span class="label label-primary"> Shift Changes</span></h2>
              <ul>
                <?
             
                if(sizeof($requestpending)> 0){
                  ?>
                  <h4>Shifts to accept</h4>
                  <?
                  foreach($requestpending as $row)
                  {
                ?>
                  <li><b>Switch pending: from <? echo $row['shortname']; ?>, Type of Switch: <? echo $row['tipocambio']; ?>, Requested on <? echo date('m/d/Y',strtotime($row['fechasolicitud'])); ?></b> - <a href="<? echo base_url(); ?>timeswitch/reviewRequestAgent?uid=<? echo $row['uniqueid']; ?>">Click here to accept</a></li>
                  
                  <? }
                        
                  }
                if(sizeof($requestaccepted)> 0){
                  ?>
                  <h4>Old Shifts</h4>
                  <?
                  foreach($requestaccepted as $row)
                  {
                    if(strtotime($row['fechacambio']) >= strtotime("today"))
                      $msg = "Waiting for authorization";
                    else
                      $msg = "<span style='color:red;'>Request outdated</span>";
                ?>
                  <li><? echo $row['tipocambio']; ?> on <b><? echo date('D, m/d/Y',strtotime($row['fechacambio'])) . ', Position ' . $row['posicioninicial']; ?></b>. Accepted by <? echo $row['agentecambio'] . ' on ' . date('m/d/Y',strtotime($row['fechaacepta'])); ?>. <? echo $msg; ?></li>
                  
                  <? }
                        
                  } ?>
              </ul> 
            
              <? if($isadmin=='1')
              { ?>
              <h4>Shifts to authorize</h4>
              <ul> 
                <?
                  if(sizeof($requesttoauthorize)> 0){
                    $limit = 0;
                    foreach($requesttoauthorize as $row)
                    {
                  ?>
                    <li><? echo $row['tipocambio']; ?> to authorize: <b><? echo $row['agentecambio'] . ' &rarr; ' . $row['shortname']; ?></b>,
                      <? echo $row['posicionsolicitada'] . ' &rarr; ' . $row['posicioninicial'] . ', ' . date('d/m/Y',strtotime($row['fechacambio'])) . '<br/> ' ?> <a href="<? echo base_url(); ?>timeswitch/reviewRequestLead?uid=<? echo $row['uniqueid']; ?>">Click here to review</a></li>
                    

                    <?
                      if(++$limit >= 10) break;
                     }
                          
                    }
                }
                  ?>
                    <h5><a href="<? echo base_url() . ($isadmin == '1' ? 'timeswitch/pending' : 'timeswitch') ; ?>">...See more</a></h5>
              </ul>
              <ul>
                <div class="panel-heading">
                  <h3><span class="label label-info">Assignments</span></h3>
                  <div>
                    <div class='row top-buffer'>
                      <div class='col-sm-8'>
                        <div class='form-group'>
                          <table class="table table-condensed table-bordered">
                            <?
                            foreach($quickscheduler1 as $eachday)
                            {
                              $stime = $this->utilities->convertIntTimeToString($eachday['starttime']);
                              $etime = $this->utilities->convertIntTimeToString($eachday['endtime']);
                            ?>
                            <tr>
                              <td><? echo date('D, F jS',strtotime($eachday['fecha'])); ?></td>
                              <td><? echo $eachday['posicion']; ?></td>
                              <td><? echo $stime . ' to ' . $etime; ?>
                            </tr>
                            
                            <?
                            }
                            ?>
                            <?
                            foreach($quickgates1 as $eachday)
                            {
                              $stime = $this->utilities->convertIntTimeToString($eachday['horasalida']);
                            ?>
                            <tr>
                              <td></td>
                              <td><? echo $eachday['idvuelo']; ?></td>
                              <td><? echo $stime; ?>
                            </tr>
                            
                            <?
                            }
                            ?>

                            <?
                            foreach($quickscheduler2 as $eachday)
                            {
                              $stime = $this->utilities->convertIntTimeToString($eachday['starttime']);
                              $etime = $this->utilities->convertIntTimeToString($eachday['endtime']);
                            ?>
                            <tr>
                              <td><? echo date('D, F jS',strtotime($eachday['fecha'])); ?></td>
                              <td><? echo $eachday['posicion']; ?></td>
                              <td><? echo $stime . ' to ' . $etime; ?>
                            </tr>
                            
                            <?
                            }
                            ?>
                            <?
                            foreach($quickgates2 as $eachday)
                            {
                              $stime = $this->utilities->convertIntTimeToString($eachday['horasalida']);
                            ?>
                            <tr>
                              <td></td>
                              <td><? echo $eachday['idvuelo']; ?></td>
                              <td><? echo $stime; ?>
                            </tr>
                            
                            <?
                            }
                            ?>
                          </table>
                          
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </ul>
            </div>
        </div>
        <div class='col-sm-6'>
          <div class='form-group'>
            <h2><span class="label label-primary"> Polls </span></h2>
            <?
              if($currentpolls)
              {
                foreach($currentpolls as $thispoll)
                {                  ?>
                  <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <span class="glyphicon glyphicon-arrow-right"></span><? echo $thispoll['nombre']; ?></span>
                        </h3>
                    </div>
                    <div class="panel-body" id="pollpanel_<? echo $thispoll['uniqueid']; ?>">
                      <ul class="list-group">
                        <? if($thispoll['opcion1'])
                        { ?>
                        <li class="list-group-item">
                          <div class="radio">
                              <label>
                                  <input type="radio" value="<? echo $thispoll['opcion1']; ?>" name="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_1"; ?>" id="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_1"; ?>">
                                  <? echo $thispoll['opcion1']; ?>
                              </label>
                          </div>
                        </li>
                        <? 
                        } 
                        if($thispoll['opcion2'])
                        { ?>
                        <li class="list-group-item">
                          <div class="radio">
                              <label>
                                  <input type="radio" value="<? echo $thispoll['opcion2']; ?>" name="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_2"; ?>" id="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_2"; ?>">
                                  <? echo $thispoll['opcion2']; ?>
                              </label>
                          </div>
                        </li>
                        <? 
                        } 
                        if($thispoll['opcion3'])
                        { ?>
                        <li class="list-group-item">
                            <div class="radio">
                                <label>
                                    <input type="radio" value="<? echo $thispoll['opcion3']; ?>" name="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_3"; ?>" id="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_3"; ?>">
                                    <? echo $thispoll['opcion3']; ?>
                                </label>
                            </div>
                        </li>
                        <? 
                        } 
                        if($thispoll['opcion4'])
                        { ?>
                        <li class="list-group-item">
                          <div class="radio">
                              <label>
                                  <input type="radio" value="<? echo $thispoll['opcion4']; ?>" name="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_4"; ?>" id="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_4"; ?>">
                                  <? echo $thispoll['opcion4']; ?>
                              </label>
                          </div>
                        </li>
                        <? 
                        } 
                        if($thispoll['opcion5'])
                        { ?>
                        <li class="list-group-item">
                            <div class="radio">
                                <label>
                                    <input type="radio" value="<? echo $thispoll['opcion5']; ?>" name="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_5"; ?>" id="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_5"; ?>">
                                    <? echo $thispoll['opcion5']; ?>
                                </label>
                            </div>
                        </li>
                        <? 
                        } 
                        if($thispoll['opcion6'])
                        { ?>
                        <li class="list-group-item">
                            <div class="radio">
                                <label>
                                    <input type="radio" value="<? echo $thispoll['opcion6']; ?>" name="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_6"; ?>" id="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_6"; ?>">
                                    <? echo $thispoll['opcion6']; ?>
                                </label>
                            </div>
                        </li>
                        <? 
                        } 
                        if($thispoll['opcion7'])
                        { ?>
                        <li class="list-group-item">
                            <div class="radio">
                                <label>
                                    <input type="radio" value="<? echo $thispoll['opcion7']; ?>" name="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_7"; ?>" id="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_7"; ?>">
                                    <? echo $thispoll['opcion7']; ?>
                                </label>
                            </div>
                        </li>
                        <? 
                        } 
                         if($thispoll['opcion8'])
                        { ?>
                        <li class="list-group-item">
                            <div class="radio">
                                <label>
                                    <input type="radio" value="<? echo $thispoll['opcion8']; ?>" name="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_8"; ?>" id="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_8"; ?>">
                                    <? echo $thispoll['opcion8']; ?>
                                </label>
                            </div>
                        </li>
                        <? 
                        } 
                         if($thispoll['opcion9'])
                        { ?>
                        <li class="list-group-item">
                            <div class="radio">
                                <label>
                                    <input type="radio" value="<? echo $thispoll['opcion9']; ?>" name="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_9"; ?>" id="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_9"; ?>">
                                    <? echo $thispoll['opcion9']; ?>
                                </label>
                            </div>
                        </li>
                        <? 
                        } 
                         if($thispoll['opcion10'])
                        { ?>
                        <li class="list-group-item">
                            <div class="radio">
                                <label>
                                    <input type="radio" value="<? echo $thispoll['opcion10']; ?>" name="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_10"; ?>" id="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_10"; ?>">
                                    <? echo $thispoll['opcion10']; ?>
                                </label>
                            </div>
                        </li>
                        <? 
                        } 
                        if($thispoll['opcion11'])
                        { ?>
                        <li class="list-group-item">
                            <div class="radio">
                                <label>
                                    <input type="radio" value="<? echo $thispoll['opcion11']; ?>" name="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_11"; ?>" id="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_1"; ?>">
                                    <? echo $thispoll['opcion11']; ?>
                                </label>
                            </div>
                        </li>
                        <? 
                        } 
                        
                        if($thispoll['opcion12'])
                        { ?>
                        <li class="list-group-item">
                          <div class="radio">
                              <label>
                                  <input type="radio" value="<? echo $thispoll['opcion12']; ?>" name="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_12"; ?>" id="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_12"; ?>">
                                  <? echo $thispoll['opcion12']; ?>
                              </label>
                          </div>
                        </li>
                        <? 
                        } 
                        if($thispoll['opcion13'])
                        { ?>
                        <li class="list-group-item">
                            <div class="radio">
                                <label>
                                    <input type="radio" value="<? echo $thispoll['opcion13']; ?>" name="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_13"; ?>" id="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_13"; ?>">
                                    <? echo $thispoll['opcion13']; ?>
                                </label>
                            </div>
                        </li>
                        <? 
                        } 
                        if($thispoll['opcion14'])
                        { ?>
                        <li class="list-group-item">
                          <div class="radio">
                              <label>
                                  <input type="radio" value="<? echo $thispoll['opcion14']; ?>" name="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_14"; ?>" id="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_14"; ?>">
                                  <? echo $thispoll['opcion14']; ?>
                              </label>
                          </div>
                        </li>
                        <? 
                        } 
                        if($thispoll['opcion15'])
                        { ?>
                        <li class="list-group-item">
                            <div class="radio">
                                <label>
                                    <input type="radio" value="<? echo $thispoll['opcion15']; ?>" name="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_5"; ?>" id="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_15"; ?>">
                                    <? echo $thispoll['opcion15']; ?>
                                </label>
                            </div>
                        </li>
                        <? 
                        } 
                        if($thispoll['opcion16'])
                        { ?>
                        <li class="list-group-item">
                            <div class="radio">
                                <label>
                                    <input type="radio" value="<? echo $thispoll['opcion16']; ?>" name="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_16"; ?>" id="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_16"; ?>">
                                    <? echo $thispoll['opcion16']; ?>
                                </label>
                            </div>
                        </li>
                        <? 
                        } 
                        if($thispoll['opcion17'])
                        { ?>
                        <li class="list-group-item">
                            <div class="radio">
                                <label>
                                    <input type="radio" value="<? echo $thispoll['opcion17']; ?>" name="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_17"; ?>" id="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_17"; ?>">
                                    <? echo $thispoll['opcion17']; ?>
                                </label>
                            </div>
                        </li>
                        <? 
                        } 
                         if($thispoll['opcion18'])
                        { ?>
                        <li class="list-group-item">
                            <div class="radio">
                                <label>
                                    <input type="radio" value="<? echo $thispoll['opcion18']; ?>" name="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_18"; ?>" id="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_18"; ?>">
                                    <? echo $thispoll['opcion18']; ?>
                                </label>
                            </div>
                        </li>
                        <? 
                        } 
                         if($thispoll['opcion19'])
                        { ?>
                        <li class="list-group-item">
                            <div class="radio">
                                <label>
                                    <input type="radio" value="<? echo $thispoll['opcion19']; ?>" name="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_19"; ?>" id="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_19"; ?>">
                                    <? echo $thispoll['opcion19']; ?>
                                </label>
                            </div>
                        </li>
                        <? 
                        } 
                         if($thispoll['opcion20'])
                        { ?>
                        <li class="list-group-item">
                            <div class="radio">
                                <label>
                                    <input type="radio" value="<? echo $thispoll['opcion20']; ?>" name="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_20"; ?>" id="<? echo "optionsRadios_" . $thispoll['uniqueid'] . "_20"; ?>">
                                    <? echo $thispoll['opcion20']; ?>
                                </label>
                            </div>
                        </li>
                        <? 
                        } 
                        ?>
                      </ul>
                    </div>
                    <div class="panel-footer">
                        <button type="button" class="btn btn-primary btn-sm" onclick="votePoll('<? echo $thispoll['uniqueid']; ?>');">
                            Vote</button>
                    </div>
                  </div>
                  <?
                }
              }
              else
              {
                ?>
                  <text>No polls right now</text>
                <?
              }
            ?>
          </div>
        </div>
      </div>
    </div>
    <div class="panel-heading text-left">
      <h2><span class="label label-primary"> Monthly Assignments </span></h2>
      <div id="calendar"></div>
    </div><br/>
    <div class="panel-heading">
      <h2><span class="label label-primary"> Shortcuts </span></h2>
      <ul>
        <li><a href="<? echo base_url() . 'webcunop';?>">Today's CUNOP</a></li>
      </ul>
    </div>
    <?
      if($birthday && sizeof($birthday) > 0)
      {
        ?>
        <div class="panel-heading">
          <h2><span class="label label-primary"> Upcoming Birthdays </span></h2>
          <div>
            <div class='row top-buffer'>
              <div class='col-sm-8'>
                  <div class='form-group'>
                      <ul>
                        <?
                          foreach($birthday as $row)
                          {
                              ?>
                                <li><? echo $row['shortname'] . ' on ' . date('l jS \of F',strtotime( date( 'd M ', strtotime($row['birthday']) ) . date( 'Y' ))); ?> </li>
                              <?
                          }
                        ?>
                      </ul>
                  </div>
              </div>
            </div>
          </div>
        </div>
        <?
      }
      ?>
        <div class="panel-heading">
          <h2><span class="label label-primary"> Share a comment </span></h2>
          <div>
            <div class='row top-buffer'>
              <div class='col-sm-8'>
                  <div class='form-group'>
                      <input type="hidden" id="inputIdAgente"/>
                      <input type="hidden" id="inputShortname" />
                      <label for="inputCommentAgent">Recipient</label>
                      <label for="inputToAgent">Agent</label>
                      <input type="radio" id="inputToAgent" name="options" checked>
                      <label for="inputToOther">Other</label>
                      <input type="radio" id="inputToOther" name="options">
                      <input class="form-control typeahead" id="inputCommentAgent" name="inputCommentAgent" required placeholder="Search Agents" type="text" />
                      <input class="form-control" id="inputCommentOther" name="inputCommentOther" required placeholder="Type Recipient Name" type="text" />
                  </div>
              </div>
              
            </div>
            <div class='row'>
              <div class='col-sm-8'>
                  <div class='form-group'>
                      <label for="inputCommentTitle">Title</label>
                      <input class="form-control typeahead" id="inputCommentTitle" name="inputCommentTitle" required placeholder="Short title" type="text" />
                  </div>
              </div>
            </div>
            <div class='row'>
              <div class='col-sm-8'>
                  <div class='form-group'>
                      <label for="inputCommentContent">Comment</label>
                      <textarea class="form-control typeahead" id="inputCommentContent" name="inputCommentContent" required placeholder="Write your comment here"></textarea>
                  </div>
              </div>
          </div>
          <div class='row'>
            <div class='col-sm-12'>
                <div class='form-group'>
                    <button type="button" id="btnSubmitComment" class="btn btn-success">Save</button>
                    <button type="button" id="btnCancel" class="btn btn-default">Cancel</button>
                </div>
            </div>
          </div>
         
        </div>
  </div>
</div>
</div>

<div id="modalDialog" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        
        <h2><span class="label label-primary" id="pFlightTitle">Comments</span></h2>
      </div>
      <div class="modal-body">
        <h3><span class="label label-primary" id="pFlightInfo">Comment saved. Thank you</span></h3>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!-- Modal -->
<div id="dlgTerms" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        
        <h2><span class="label label-primary" id="pFlightInfo">Terms of Service / Privacy Policy</span></h2>
        <p>Please review the following Terms of service, by requirement users must accept them to continue using the WebCUNOP app.</p>
      </div>
      <div class="modal-body">
        <center>
            <table width='100%'>
                <tr>
                    <td>
                        <!-- 16:9 aspect ratio -->
                        <div class="embed-responsive embed-responsive-16by9">
                          <iframe class="embed-responsive-item" src="https://apps.mindware.com.mx/ppolicy_en_gen_view.php"></iframe>
                        </div>
                    </td>
                </tr>
            </table>
        </center>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btnAcceptTerms" data-dismiss="modal">Accept Terms</button>
        <button type="button" class="btn btn-dark" id="btnCancelTerms" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<script>
  var agenteslista = [<? foreach($fullagentes as $agent) { echo "{ id : '" . $agent['idagente'] . "', name : '" . $agent['shortname'] . "'},"; } ?>];
  if(<? echo $termsaccepted != '1' ? '1':'0'; ?>)
  $("#dlgTerms").modal();
</script>
<script src="<? echo base_url(); ?>assets/js/admin.js"></script>
<script>

  function votePoll(pollid)
  {
    console.log(pollid);

    var lidempresa = $("#inputIdEmpresa").val();
    var lidoficina = $("#inputIdOficina").val();


    var lopcion; 
    var ltexto;

    if($("#optionsRadios_" + pollid + "_1")[0].checked)
    {
      lopcion=1; ltexto = $("#optionsRadios_" + pollid + "_1")[0].value;
    }
    if($("#optionsRadios_" + pollid + "_2")[0].checked)
    {
      lopcion=2; ltexto = $("#optionsRadios_" + pollid + "_2")[0].value;
    }
    if($("#optionsRadios_" + pollid + "_3").length>0)
      if($("#optionsRadios_" + pollid + "_3")[0].checked)
      {
        lopcion=3; ltexto = $("#optionsRadios_" + pollid + "_3")[0].value;
      }
    if($("#optionsRadios_" + pollid + "_4").length>0)
      if($("#optionsRadios_" + pollid + "_4")[0].checked)
      {
        lopcion=4; ltexto = $("#optionsRadios_" + pollid + "_4")[0].value;
      }
    if($("#optionsRadios_" + pollid + "_5").length>0)
      if($("#optionsRadios_" + pollid + "_5")[0].checked)
      {
        lopcion=5; ltexto = $("#optionsRadios_" + pollid + "_5")[0].value;
      }
    if($("#optionsRadios_" + pollid + "_6").length>0)
      if($("#optionsRadios_" + pollid + "_6")[0].checked)
      {
        lopcion=6; ltexto = $("#optionsRadios_" + pollid + "_6")[0].value;
      }
    if($("#optionsRadios_" + pollid + "_7").length>0)
      if($("#optionsRadios_" + pollid + "_7")[0].checked)
      {
        lopcion=7; ltexto = $("#optionsRadios_" + pollid + "_7")[0].value;
      }
    if($("#optionsRadios_" + pollid + "_8").length>0)
      if($("#optionsRadios_" + pollid + "_8")[0].checked)
      {
        lopcion=8; ltexto = $("#optionsRadios_" + pollid + "_8")[0].value;
      }
    if($("#optionsRadios_" + pollid + "_9").length>0)
      if($("#optionsRadios_" + pollid + "_9")[0].checked)
      {
        lopcion=9; ltexto = $("#optionsRadios_" + pollid + "_9")[0].value;
      }
    if($("#optionsRadios_" + pollid + "_10").length>0)
      if($("#optionsRadios_" + pollid + "_10")[0].checked)
      {
        lopcion=10; ltexto = $("#optionsRadios_" + pollid + "_10")[0].value;
      }
    if($("#optionsRadios_" + pollid + "_11").length>0)
      if($("#optionsRadios_" + pollid + "_11")[0].checked)
      {
        lopcion=11; ltexto = $("#optionsRadios_" + pollid + "_11")[0].value;
      }
    if($("#optionsRadios_" + pollid + "_12").length>0)
      if($("#optionsRadios_" + pollid + "_12")[0].checked)
      {
        lopcion=12; ltexto = $("#optionsRadios_" + pollid + "_12")[0].value;
      }
    if($("#optionsRadios_" + pollid + "_13").length>0)
      if($("#optionsRadios_" + pollid + "_13")[0].checked)
      {
        lopcion=13; ltexto = $("#optionsRadios_" + pollid + "_13")[0].value;
      }
    if($("#optionsRadios_" + pollid + "_14").length>0)
      if($("#optionsRadios_" + pollid + "_14")[0].checked)
      {
        lopcion=14; ltexto = $("#optionsRadios_" + pollid + "_14")[0].value;
      }
    if($("#optionsRadios_" + pollid + "_15").length>0)
      if($("#optionsRadios_" + pollid + "_15")[0].checked)
      {
        lopcion=15; ltexto = $("#optionsRadios_" + pollid + "_15")[0].value;
      }
    if($("#optionsRadios_" + pollid + "_16").length>0)
      if($("#optionsRadios_" + pollid + "_16")[0].checked)
      {
        lopcion=16; ltexto = $("#optionsRadios_" + pollid + "_16")[0].value;
      }
    if($("#optionsRadios_" + pollid + "_17").length>0)
      if($("#optionsRadios_" + pollid + "_17")[0].checked)
      {
        lopcion=17; ltexto = $("#optionsRadios_" + pollid + "_17")[0].value;
      }
    if($("#optionsRadios_" + pollid + "_18").length>0)
      if($("#optionsRadios_" + pollid + "_18")[0].checked)
      {
        lopcion=18; ltexto = $("#optionsRadios_" + pollid + "_18")[0].value;
      }
    if($("#optionsRadios_" + pollid + "_19").length>0)
      if($("#optionsRadios_" + pollid + "_19")[0].checked)
      {
        lopcion=19; ltexto = $("#optionsRadios_" + pollid + "_19")[0].value;
      }
    if($("#optionsRadios_" + pollid + "_20").length>0)
      if($("#optionsRadios_" + pollid + "_20")[0].checked)
      {
        lopcion=20; ltexto = $("#optionsRadios_" + pollid + "_20")[0].value;
      }

    var fields = {
        idempresa : lidempresa,
        idoficina : lidoficina,
        pollid : pollid,
        opcion : lopcion,
        texto : ltexto
    }

    var request = $.ajax({
        url: 'polls/postpollrecord',
        type: 'POST',
        data: fields,
        beforeSend:function(){
            console.log('sending...');
            $('#myPleaseWait').modal('show');
        },
        success:function(result){
            
            console.log('sent!');
            $("#pollpanel_" + pollid).replaceWith('<div class="panel-body">Vote received</div>');
            

            $('#myPleaseWait').modal('hide');
        }, 
        error:function(exception){console.log(exception);}
        
    });
  }
</script>

