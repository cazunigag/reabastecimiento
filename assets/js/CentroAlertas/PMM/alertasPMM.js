$(document).ready(function(){

	//llamadas de alertas

	actualizarAlertaDPW();

	//declaracion de variables

	var stopedDPW = 0;
	var runningDPW = 0;

	var dataSourceDetDPW = new kendo.data.DataSource({
        transport: {
            read: onReadDPW
        },
        schema: {
            model: {
                id: "PKT_CTRL_NBR",
                fields: {
                        SHPMT_NBR: {type: "string"}, // number - string - date
                        VENDOR_ID: {type: "string"},
                        REP_NAME: {type: "string"}, // number - string - date
                        MANIF_NBR: {type: "string"},
                        PO_NBR: {type: "string"},
                        VERF_DATE_TIME: {type: "string"},
                        DIFERENCIA_HHMMSS: {type: "string"},
                        UNITS_RCVD: {type: "string"}
                    }
            }
        },
        pageSize: 15
    });
	function intermiteciaDPW(){
      $("#DPWBox").toggleClass("bg-green");
      $("#DPWBox").toggleClass("bg-red");
      $("#iconDPW").toggleClass("glyphicon-ok");
      $("#iconDPW").toggleClass("ion-android-alert");
      if(stopedDPW == 0){
         runningDPW = 1;
         setTimeout(intermiteciaDPW, 500);
      }
      else{
        stopDPW();
      }
    }
    function actualizarAlertaDPW(){
    	var numero = 1;
        $.ajax({
            type: "POST",
            url: baseURL + 'alertas/pmm/errores/cantDifPMMWMS',
            dataType: 'json',
            success: function(result){
        		console.log(result);
                if(result > 0){
                	$("#nDPW").html(result);
                    if(runningDPW == 0){
                        stopedDPW = 0;
                        intermiteciaDPW();
                        
                    }
                    setTimeout(actualizarAlertaDPW, 600000);
                }else{
                    setTimeout(actualizarAlertaDPW, 600000);
                    if(stopedDPW == 0 && runningDPW == 1){
                        runningDPW = 0;
                        stopedDPW = 1
                    }
                }
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    function stopDPW(){
        $("#DPWBox").removeClass("bg-green");
        $("#DPWBox").removeClass("bg-red");
        $("#iconDPW").removeClass("glyphicon-ok");
        $("#iconDPW").removeClass("ion-android-alert"); 
        $("#DPWBox").addClass("bg-green");
        $("#iconDPW").addClass("glyphicon-ok");
    }
    $("#DPWDetalles").click(function(){
        var popupdetalledpw = $("#POPUP_Detalle_DPW");
        popupdetalledpw.data("kendoWindow").open();
        var grid = $("#gridDetDPW");
        grid.data("kendoGrid").dataSource.read();
    });
    var ventana_detalle_dpw = $("#POPUP_Detalle_DPW");
    ventana_detalle_dpw.kendoWindow({
        width: "1000px",
        height: "550px",
        title: "Documentos no enviados",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();
    $("#gridDetDPW").kendoGrid({
        autoBind: false,
        dataSource: dataSourceDetDPW,
        height: "100%", 
        width: "1000px",
        sortable: true, 
        filterable: true,
        scrollable: true,
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [
            {field: "SHPMT_NBR",title: "ASN",width: 70, filterable:false},
            {field: "VENDOR_ID",title: "RUT PROVEEDOR",width:70, filterable:false},
            {field: "REP_NAME",title: "PROVEEDOR",width:100,filterable: {multi: true, search: true}},
            {field: "MANIF_NBR",title: "DOCUMENTO",width: 70,filterable: {multi: true, search: true}},
            {field: "PO_NBR",title: "OC",width:70,filterable: {multi: true, search: true}},
            {field: "VERF_DATE_TIME",title: "FEC VERIFICACION",width:70,filterable: false},
            {field: "DIFERENCIA_HHMMSS",title: "TRANSCURRIDO",width:70,filterable: false},
            {field: "UNITS_RCVD",title: "UND RECIVIDAS",width:70,filterable: false}
        ]
    });
    function onReadDPW(e){
         $.ajax({
            type: "POST",
            url: baseURL + 'alertas/pmm/errores/difPMMWMS',
            dataType: 'json',
            success: function(result){
            	if(result.length > 0){
            		if(runningDPW == 1){
            			stopedDPW = 0;
            			intermiteciaDPW();
            		}
            	}else{
            		stopedDPW = 1;
            	}
                e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }
});