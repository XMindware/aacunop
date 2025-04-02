    <div class="container">
        <div class="panel-body">
            <div class="panel-heading">
                <div class="row">
                <h2><span class="label label-primary">Special Positions on Current Station</span></h2>
                <button type="button" id="btnNewRow" class="btn btn-link">New Position</button>
                </div>
            </div>           
            <form id="frmData" autocomplete="off">
                <input type="hidden" id="inputIdEmpresa" name="inputIdEmpresa" value="<?php echo $idempresa; ?>"/>
                <input type="hidden" id="inputUniqueId" name="inputUniqueId" value="" />
                <fieldset>
                    <legend>Position Information</legend>
                        <div class='row'>
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                    <label for="inputCode">Code</label>
                                    <input class="form-control" id="inputCode" name="inputCode" required="true" size="30" type="text" />
                                </div>
                            </div>
                            <div class='col-sm-4'>
                                <div class='form-group'>
                                    <label for="inputDescription">Description</label>
                                    <input class="form-control" id="inputDescription" name="inputDescription" required="true" size="30" type="text" />
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
                        <th>Code</th>
                        <th>Description</th>
                        
                      </tr>
                    </thead>
                    <?
                        if(sizeof($rowlist)> 0){
                                
                        ?>
                        <tbody>
                            <?
                            foreach($rowlist as $position)
                            {
                                ?>
                          <tr>
                            <td>
                                <button type="button" onclick="loadRow(<?php echo "'" . $position['idempresa'] . "','" . $position['uniqueid'] . 
                                    "'"; ?>);" class="btn btn-link"><?php echo $position['code']; ?></button>
                            
                            <td><?php echo $position['description']; ?></td>
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
    $(document).ready(function(){
        
        $("#btnSubmitData").click(function(){

            var luniqueid = $("#inputUniqueId").val();
            var lcode = $("#inputCode").val();
            var ldescription = $("#inputDescription").val();
            var lidempresa = $("#inputIdEmpresa").val();
            
                    
            $("#frmData").hide();   
            var agent = {
                         idempresa : lidempresa,
                         uniqueid : luniqueid,
                         code : lcode,
                         description : ldescription,
                         };

                    console.log(agent);
            
            var request = $.ajax({
                url: '<?php echo base_url(); ?>extrapositions/postposition',
                type: 'POST',
                data: agent,
                beforeSend:function(){
                    console.log('sending...');
                    $('#myPleaseWait').modal('show');
                },
                success:function(result){
                    location.reload();
                    console.log('sent!');
                    console.log(result);
                    $('#myPleaseWait').modal('hide');
                    
                }, 
                error:function(exception){console.log(exception);}
                
            });
            
            request.fail(function( jqXHR, textStatus ) {
            console.log( "Request failed: " + textStatus );
            });
            
        });
        
        $("#btnNewRow").click(function(){
            $("#frmData").show();   
            $("#inputUniqueId").val('-1');
        });
        
        $("#btnCancel").click(function(){
            $("#inputCode").val('');
            $("#frmData").hide();   
        });
        
        $("#btnDeleteRow").click(function(){
            
            var luniqueid = $("#inputUniqueId").val();
            var lidempresa = $("#inputIdEmpresa").val();
            
            var rowData = { 
                idempresa : lidempresa,
                uniqueid : luniqueid 
            };

            $.ajax({
                url: '<?php echo base_url(); ?>extrapositions/deletepositionid',
                type: 'POST',
                data : rowData,
                beforeSend:function(){
                    $('#myPleaseWait').modal('show');
                    $(document).scrollTop();
                },
                success:function(data){
                    $('#myPleaseWait').modal('hide');
                    location.reload();
                }
            });
            $("#frmData").hide();   
        });
            
            
        return false;
    });

    function loadRow(idempresa, uniqueid)
    {
        if(uniqueid<0) return;

        $("#frmData").show();   
        
        var rowData = { idempresa : idempresa,
                        uniqueid : uniqueid};
        $.ajax({
            url: '<?php echo base_url(); ?>extrapositions/loadpositionid',
            type: 'POST',
            data : rowData,
            beforeSend:function(){
                $('#myPleaseWait').modal('show');
                $(document).scrollTop();
            },
            success:function(position){
                console.log('position uniqueid ' + position.uniqueid);
                $("#inputUniqueId").val(position.uniqueid);
                $("#inputCode").val(position.code);
                $("#inputDescription").val(position.description);

                $("#inputIdEmpresa").val(position.idempresa);   
                $("html, body").animate({ scrollTop: 0 }, "fast");
                $('#myPleaseWait').modal('hide');
            }
        });
    }
</script>