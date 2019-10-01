$(document).ready(function(){
	$('td').mousedown(function(event){
    	switch (event.which) { 
			case 3: 
				if(flagCartonType == 'on'){
					idpasillorc = $(this).attr('id');
					var popupcartontype = $("#POPUP_CartonType");
					popupcartontype.data("kendoWindow").title("Actualizar Carton Type Pasillo: "+ idpasillorc);
	        		popupcartontype.data("kendoWindow").open();
        		}
                break; 
            default: 
                break; 
        } 
    });
	var dataSource  = new kendo.data.DataSource({
		transport:{
			read: onReadCB
		}
	});
	$("#selectPasillos").kendoMultiSelect();
	$("#selectClasificacion").kendoDropDownList();
	$("#selectCartonType").kendoComboBox({
		dataSource: dataSource,
		dataTextField: "CARTON_TYPE",
		dataValueField: "CARTON_TYPE"
	});
	var ventana_classPasillo= $("#POPUP_ClassPasillo");
	ventana_classPasillo.kendoWindow({
	    title: "Actualizar Clasificacion Pasillo",
	    width: "600px",
	    height: "230px",
	    visible: false,
	    position:{
	        top: 45,
	        left:0
	    },
	    actions: [
	        "Minimize",
	        "Maximize",
	        "Close"
	    ]
	}).data("kendoWindow").center();
	var ventana_cartontype= $("#POPUP_CartonType");
	ventana_cartontype.kendoWindow({
	    width: "350px",
	    height: "230px",
	    visible: false,
	    position:{
	        top: 45,
	        left:0
	    },
	    actions: [
	        "Minimize",
	        "Maximize",
	        "Close"
	    ]
	}).data("kendoWindow").center();
	$("#ActClassPasillo").click(function(){
        var popupclasspasillo = $("#POPUP_ClassPasillo");
        popupclasspasillo.data("kendoWindow").open();
	});
	$('.popup').qtip({
      overwrite: true,
      style:{
        classes: 'qtip-dark'
      },
      content: {
          text: onRead           
      },
      position: {
      	my: 'bottom center',
      	at: 'center'
      }
  	});
  	$("#btnCartonType").click(function(){
	     $.ajax({
            beforeSend: function () {
                $(".modalloading").show();
            },
            complete: function () {
                $(".modalloading").hide();
            },
	          type: "POST",
	          url: baseURL + 'pasillos/tipoCarton',
	          data:{ pasillos: pasillospiso},
	          dataType: 'json',
	          success: function(result){
	            flagCartonType = 'on';
	            var contenidonuevo = '';
	             result.forEach(function(element){
	              if($('#'+element.PASILLO).data('qtip').rendered == true){
	                $('#'+element.PASILLO).data('qtip').set('content.title', 'CARTON TYPE');
	                $.ajax({
	                    url: baseURL + 'pasillos/tipoCartones',
	                    type: 'POST', // POST or GET
	                    dataType: 'json', // Tell it we're retrieving JSON
	                    data: {
	                        pasillo: element.PASILLO // Pass through the ID of the current element matched by '.selector'
	                    },
	                    success: function(data){
	                      data.forEach(function(element){
	                         contenidonuevo = contenidonuevo + element.CARTON_TYPE + ': '+element.TOTAL + '<br>';
	                      });
	                       $('#'+element.PASILLO).data('qtip').set('content.text', contenidonuevo);
	                       contenidonuevo = '';
	                    }

	                });
	               
	              }
	              if(element.CANTIDAD > 1){
	                  
	                  $('#'+element.PASILLO).toggleClass("label label-danger");
	                  

	              }
	            });
	          },
	          error: function(xhr){
	              alert('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
	          }
	    });
	});
  	function onRead(event, api){
    	if(flagCartonType == 'off'){
            $.ajax({

                url: baseURL + 'pasillos/utilizacion',
                type: 'POST',
                dataType: 'json', // Tell it we're retrieving JSON
                data: {
                    pasillo: $(this).attr('id') // Pass through the ID of the current element matched by '.selector'
                },
            })
            .then(function(data) {
              var content = '';
                data.forEach(function(element){
                   content = content + 'TOTAL UBICACIONES: ' + element.TOTAL_LOC + '<br> UBICACIONES SIN ART:    ' + element.ROJO +'<br> UBICACIONES SIN STOCK: ' + element.NARANJO + '<br> UBICACIONES CON STOCK: ' + element.VERDE;
                });
                api.set('rendered', false);
                // Now we set the content manually (required!)
                api.set('content.title', 'UTILIZACION PASILLO');
                api.set('content.text', content);
            }, function(xhr, status, error) {
                // Upon failure... set the tooltip content to the status and error value
                api.set('content.text', status + ': ' + error);
            });

            return 'Loading...';
        }else if(flagCartonType == 'on'){
            $.ajax({
                url: baseURL + 'pasillos/tipoCartones',
                type: 'POST', // POST or GET
                dataType: 'json', // Tell it we're retrieving JSON
                data: {
                    pasillo: $(this).attr('id') // Pass through the ID of the current element matched by '.selector'
                },
            })
            .then(function(data) {
              var content = '';
                data.forEach(function(element){
                   content = content + element.CARTON_TYPE + ': '+element.TOTAL + '<br>';
                });
                // Now we set the content manually (required!)
                api.set('content.title', 'CARTON TYPE');
                api.set('content.text', content);
            }, function(xhr, status, error) {
                // Upon failure... set the tooltip content to the status and error value
                api.set('content.text', status + ': ' + error);
            });
            return 'Loading...'; // Set some initial loading text
       	}
    }
    $("#btnActualizarClass").click(function(){
    	var popupclasspasillo = $("#POPUP_ClassPasillo");
        popupclasspasillo.data("kendoWindow").close();
    	var SelectPasillos = $("#selectPasillos").data("kendoMultiSelect").value();
    	var SelectCat = $("#selectClasificacion").data("kendoDropDownList").value();
    	$.ajax({
            url: baseURL + 'pasillos/actClase',
            type: 'POST', // POST or GET
            dataType: 'json', // Tell it we're retrieving JSON
            data: {
                pasillos: SelectPasillos, class: SelectCat// Pass through the ID of the current element matched by '.selector'
            },
            success: function(data){
              if(data > 0){
              	$("#modal-success").modal('show');
              }
            }
        });
    });
    $("#btnActCartonType").click(function(){
    	var popupcartontype = $("#POPUP_CartonType");
		popupcartontype.data("kendoWindow").close();
    	var cartonType = $("#selectCartonType").data("kendoComboBox").value();
    	$.ajax({
            url: baseURL + 'pasillos/actTipoCarton',
            type: 'POST', // POST or GET
            dataType: 'json', // Tell it we're retrieving JSON
            data: {
                pasillo: idpasillorc, cartonType: cartonType// Pass through the ID of the current element matched by '.selector'
            },
            success: function(data){
              if(data > 0){
              	alert('Actualizado Correctamente');
              	location.reload();
              }
            }
        });
    });
    $("#closemodal").click(function(){
    	location.reload();
    });
    function onReadCB(e){
        $.ajax({
            url: baseURL + 'pasillos/tipoCartones/todos',
			type:"POST",
			dataType: "json",
			data:{ pasillo: idpasillorc},
            success: function(result){
                e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }
});