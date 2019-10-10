$(document).ready(function(){

	actualizarAlertaEIS();

	var stopedEIS = 0;
	var runningEIS = 0;

	var codigoEndpoint = "";


	var dataSourceDetEIS = new kendo.data.DataSource({
        transport: {
            read: onReadEIS
        },
        schema: {
            model: {
                id: "ENDPOINT_ID",
                fields: {
                        ENDPOINT_ID: {type: "string"}, // number - string - date
                        NAME: {type: "string"},
                        ESTADO: {type: "string"}, // number - string - date
                        TOTAL_MSG: {type: "string"}
                    }
            }
        },
        pageSize: 15
    });
    var dataSourceResEIS = new kendo.data.DataSource({
        transport: {
            read: onReadResEIS
        },
        schema: {
            model: {
                id: "MSG_ID",
                fields: {
                        MSG_ID: {type: "string"}, // number - string - date
                        DATA: {type: "string"}
                }
            }
        },
        pageSize: 15
    });
	function intermiteciaEIS(){
      $("#EISBox").toggleClass("bg-green");
      $("#EISBox").toggleClass("bg-red");
      $("#iconEIS").toggleClass("glyphicon-ok");
      $("#iconEIS").toggleClass("ion-android-alert");
      if(stopedEIS == 0){
         runningEIS = 1;
         setTimeout(intermiteciaEIS, 500);
      }
      else{
        stopEIS();
      }
    }
    function actualizarAlertaEIS(){
    	var numero = 1;
        $.ajax({
            beforeSend: function () {
                $("#iconEIS").toggleClass("fa");
                $("#iconEIS").toggleClass("fa-refresh");
                $("#iconEIS").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconEIS").toggleClass("fa");
                $("#iconEIS").toggleClass("fa-refresh");
                $("#iconEIS").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/EIS/errores/cantErrEIS',
            dataType: 'json',
            success: function(result){
                if(result > 0){
                	$("#nEIS").html(result);
                    if(runningEIS == 0){
                        stopedEIS = 0;
                        intermiteciaEIS();
                        
                    }
                    setTimeout(actualizarAlertaEIS, 600000);
                }else{
                    setTimeout(actualizarAlertaEIS, 600000);
                    if(stopedEIS == 0 && runningEIS == 1){
                        runningEIS = 0;
                        stopedEIS = 1
                    }
                }
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    function stopEIS(){
        $("#EISBox").removeClass("bg-green");
        $("#EISBox").removeClass("bg-red");
        $("#iconEIS").removeClass("glyphicon-ok");
        $("#iconEIS").removeClass("ion-android-alert"); 
        $("#EISBox").addClass("bg-green");
        $("#iconEIS").addClass("glyphicon-ok");
        $("#nEIS").html('0');
    }
     $("#EISDetalles").click(function(){
        var popupdetalleeis = $("#POPUP_Detalle_EIS");
        popupdetalleeis.data("kendoWindow").open();
        var grid = $("#gridDetEIS");
        grid.data("kendoGrid").dataSource.read();
    });
    var ventana_detalle_eis = $("#POPUP_Detalle_EIS");
    ventana_detalle_eis.kendoWindow({
        width: "1000px",
        height: "550px",
        title: "Mensajes con error EIS",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();
    $("#gridDetEIS").kendoGrid({
        autoBind: false,
        dataSource: dataSourceDetEIS,
        selectable: "row",
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
            {field: "ENDPOINT_ID",title: "ENDPOINT ",width: 70, filterable:false},
            {field: "NAME",title: "NOMBRE MSG",width:140, filterable:false},
            {field: "ESTADO",title: "ESTADO",width:50,filterable: false},
            {field: "TOTAL_MSG",title: "CANTIDAD MSG",width: 50,filterable: false}
        ]
    }).on("click", "tbody td", function(e) {
        var cell = $(e.currentTarget);
        var cellIndex = cell[0].cellIndex;
        var grid = $("#gridDetEIS").data("kendoGrid");
        var endpoint = grid.columns[0];
        var estado = grid.columns[2];
        var dataItem = grid.dataItem(cell.closest("tr"));
        var valorEstado = dataItem[estado.field];
        codigoEndpoint = dataItem[endpoint.field];
        if(valorEstado == 'FALLIDO'){
        	var popupresumencodeis = $("#POPUP_Resumen_EIS");
	        popupresumencodeis.data("kendoWindow").open();
	        var grid = $("#gridResEIS");
	        grid.data("kendoGrid").dataSource.read();
        }else{
        	var popupdetalleeis = $("#POPUP_Detalle_EIS");
            popupdetalleeis.data("kendoWindow").close();
            $("#error-modal").text("El estado del endpoint seleccionado debe ser FALLIDO");
            $("#modal-danger").modal('show');
        }
        
    });
    var ventana_resumen_eis = $("#POPUP_Resumen_EIS");
    ventana_resumen_eis.kendoWindow({
        width: "1000px",
        height: "550px",
        title: "Resumen error EIS",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();
    $("#gridResEIS").kendoGrid({
        autoBind: false,
        dataSource: dataSourceResEIS,
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
            {field: "MSG_ID",title: "ID MENSAJE",width: 70, filterable:false},
            {field: "DATA",title: "MENSAJE",width:200, filterable:false}
        ]
    });
    function onReadEIS(e){
         $.ajax({
            type: "POST",
            url: baseURL + 'alertas/EIS/errores/msgEIS',
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }
    function onReadResEIS(e){
         $.ajax({
            type: "POST",
            url: baseURL + 'alertas/EIS/errores/resumenEIS',
            data: {endpoint: codigoEndpoint},
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }
});