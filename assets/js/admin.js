
  $(document).ready(function(){

    $("#inputCommentOther").hide();

    $('#inputCommentAgent.typeahead').typeahead({
        source : agenteslista,
        display : 'name',
        val : 'id',
        onSelect: function (obj) {
          $('#inputIdAgente').val(obj.value);
          $('#inputShortname').val(obj.text);
        }
    });

    $("#inputToOther").click(function()
    {
        $("#inputCommentAgent").hide();
        $("#inputCommentOther").show();
    });

    $("#inputToAgent").click(function()
    {
        $("#inputCommentAgent").show();
        $("#inputCommentOther").hide();
    });

        // al aceptar los terminos de uso
    $("#btnAcceptTerms").click(function(e){
        var lidempresa = $("#inputIdEmpresa").val();
        var lidoficina = $("#inputIdOficina").val();
        var lidagente = $("#inputIdAgente").val();

        var agent = {
            idempresa : lidempresa,
            idoficina : lidoficina,
            idagente : lidagente
        };
        
        
        var request = $.ajax({
            url: 'admin/acceptterms',
            type: 'POST',
            data: agent,
            beforeSend:function(){
                console.log('sending...');
                $('#myPleaseWait').modal('show');
            },
            success:function(result){
                $('#myPleaseWait').modal('hide');

            }, 
            error:function(exception){console.log(exception);}
            
        });
    });

    $("#btnSubmitComment").click(function(){

            // valida que haya un agente ingresado
            var lidempresa = $("#inputIdEmpresa").val();
            var lidoficina = $("#inputIdOficina").val();
            var lidagente = $("#inputIdAgente").val();
            var lshortname = $("#inputShortname").val();
            var toagent = $("#inputToAgent:checked").val();
            var OtherAgent = $("#inputCommentOther").val();
            var ltitle = $("#inputCommentTitle").val();
            var lcomment = $("#inputCommentContent").val();
            var lusuario = $("#inputUsuario").val();
            
            if(!toagent)
            {
                lidagente = '';
                lshortname = OtherAgent;
            }
           
            var agent = {
                idempresa : lidempresa,
                idoficina : lidoficina,
                idagente : lidagente,
                shortname : lshortname,
                title : ltitle,
                comment : lcomment,
                usuario : lusuario
            };
            
            
            var request = $.ajax({
                url: 'admin/placecomment',
                type: 'POST',
                data: agent,
                beforeSend:function(){
                    console.log('sending...');
                    $('#myPleaseWait').modal('show');
                },
                success:function(result){
                    $("#modalDialog").modal();
                    $("#inputCommentAgent").val('');
                    $("#inputCommentOther").val('');
                    $("#inputCommentTitle").val('');
                    $("#inputCommentContent").val('');
                    
                    $('#myPleaseWait').modal('hide');

                }, 
                error:function(exception){console.log(exception);}
                
            });

        });
  });