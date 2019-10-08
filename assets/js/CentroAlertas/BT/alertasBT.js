$(document).ready(function(){

	//llamada a alertas

	actualizarAlertaSDI();
    actualizarAlertaVBT();
    actualizarAlertaCUDD();

	//declaracion de variables

	var stopedSDI = 0;
	var runningSDI = 0;
    var stopedVBT = 0;
    var runningVBT = 0;
    var stopedCUDD = 0;
    var runningCUDD = 0;

    var resultcudd = [];


	function intermiteciaSDI(){
      $("#SDIBTBox").toggleClass("bg-green");
      $("#SDIBTBox").toggleClass("bg-red");
      $("#iconSDIBT").toggleClass("glyphicon-ok");
      $("#iconSDIBT").toggleClass("ion-android-alert");
      if(stopedSDI == 0){
         runningSDI = 1;
         setTimeout(intermiteciaSDI, 500);
      }
      else{
        stopSDI();
      }
    }
    function actualizarAlertaSDI(){
    	var numero = 1;
        $.ajax({
            beforeSend: function () {
                $("#iconSDIBT").toggleClass("fa");
                $("#iconSDIBT").toggleClass("fa-refresh");
                $("#iconSDIBT").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconSDIBT").toggleClass("fa");
                $("#iconSDIBT").toggleClass("fa-refresh");
                $("#iconSDIBT").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/bt/errores/sinProcSDI',
            dataType: 'json',
            success: function(result){
            	result.forEach(function(element){
	                if(element.CANTIDAD >= 2000){
	                    if(runningSDI == 0){
	                        stopedSDI = 0;
	                        intermiteciaSDI();
	                        
	                    }
	                    setTimeout(actualizarAlertaSDI, 600000);
	                }else{
	                    setTimeout(actualizarAlertaSDI, 600000);
	                    if(stopedSDI == 0 && runningSDI == 1){
	                        runningSDI = 0;
	                        stopedSDI = 1
	                    }
	                }
	                setInterval(function(){ if(numero <= element.CANTIDAD){$("#nSDIBT").html(numero);numero++;} }, 3);
            	});

            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    function stopSDI(){
        $("#SDIBTBox").removeClass("bg-green");
        $("#SDIBTBox").removeClass("bg-red");
        $("#iconSDIBT").removeClass("glyphicon-ok");
        $("#iconSDIBT").removeClass("ion-android-alert"); 
        $("#SDIBTBox").addClass("bg-green");
        $("#iconSDIBT").addClass("glyphicon-ok");
    }

    //FUNCIONALIDADES ALERTA MAL ENVIADOS BT A WMS

    var dataSourceDetVBT = new kendo.data.DataSource({
        transport: {
            read: onReadVBT
        },
        schema: {
            model: {
                id: "PKT_CTRL_NBR",
                fields: {
                        PKT: {type: "string"}, // number - string - date
                        CUD: {type: "string"},
                        DIRECCION: {type: "string"}, // number - string - date
                        RUTA: {type: "string"},
                        JORNADA: {type: "string"},
                        COMUNA: {type: "string"},
                        FECHA_CREACION: {type: "string"}
                    }
            }
        },
        pageSize: 15
    });
    function intermiteciaVBT(){
      $("#VBTBox").toggleClass("bg-green");
      $("#VBTBox").toggleClass("bg-red");
      $("#iconVBT").toggleClass("glyphicon-ok");
      $("#iconVBT").toggleClass("ion-android-alert");
      if(stopedVBT == 0){
         runningVBT = 1;
         setTimeout(intermiteciaVBT, 500);
      }
      else{
        stopVBT();
      }
    }
    function actualizarAlertaVBT(){
      var numero = 1;
        $.ajax({
            beforeSend: function () {
                $("#iconVBT").toggleClass("fa");
                $("#iconVBT").toggleClass("fa-refresh");
                $("#iconVBT").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconVBT").toggleClass("fa");
                $("#iconVBT").toggleClass("fa-refresh");
                $("#iconVBT").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/bt/errores/cantMalEnviadosBT',
            dataType: 'json',
            success: function(result){
                if(result > 0){
                    if(runningVBT == 0){
                        stopedVBT = 0;
                        intermiteciaVBT();
                        
                    }
                    setTimeout(actualizarAlertaVBT, 600000);
                }else{
                    setTimeout(actualizarAlertaVBT, 600000);
                    if(stopedVBT == 0 && runningVBT == 1){
                        runningVBT = 0;
                        stopedVBT = 1
                    }
                }
                setInterval(function(){ if(numero <= result){$("#nVBT").html(numero);numero++;} }, 3);
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    function stopVBT(){
        $("#VBTBox").removeClass("bg-green");
        $("#VBTBox").removeClass("bg-red");
        $("#iconVBT").removeClass("glyphicon-ok");
        $("#iconVBT").removeClass("ion-android-alert"); 
        $("#VBTBox").addClass("bg-green");
        $("#iconVBT").addClass("glyphicon-ok");
    }
    $("#VBTDetalles").click(function(){
        actualizarAlertaVBT();
        var popupdetallevbt = $("#POPUP_Detalle_VBT");
        popupdetallevbt.data("kendoWindow").open();
        var grid = $("#gridDetVBT");
        grid.data("kendoGrid").dataSource.read();
    });
    var ventana_detalle_dpw = $("#POPUP_Detalle_VBT");
    ventana_detalle_dpw.kendoWindow({
        width: "1000px",
        height: "550px",
        title: "Pedidos mal enviados a WMS",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();
    $("#gridDetVBT").kendoGrid({
        autoBind: false,
        dataSource: dataSourceDetVBT,
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
            {field: "PKT",title: "PKT",width: 70, filterable:false},
            {field: "CUD",title: "CUD",width:70, filterable:false},
            {field: "DIRECCION",title: "DIRECCION",width:100,filterable: false},
            {field: "RUTA",title: "RUTA",width: 70,filterable: false},
            {field: "JORNADA",title: "JORNADA",width:70,filterable: false},
            {field: "COMUNA",title: "COMUNA",width:70,filterable: false},
            {field: "FECHA_CREACION",title: "FECHA CREACION",width:70,filterable: false}
        ]
    });
    function onReadVBT(e){
         $.ajax({
            type: "POST",
            url: baseURL + 'alertas/bt/errores/malEnviadosBT',
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }

    //FUNCIONALIDADES ALERTA PICK TICKET DUPLICADOS


    var dataSourceDetCUDD = new kendo.data.DataSource({
        transport: {
            read: onReadCUDD
        },
        schema: {
            model: {
                id: "CUD",
                fields: {
                        CUD: {type: "string"}, // number - string - date
                        PKT_CTRL_NBR: {type: "string"},
                        STAT_CODE: {type: "string"}, // number - string - date
                        FECHA_CREACION: {type: "string"}
                }
            }
        },
        pageSize: 15
    });
    function intermiteciaCUDD(){
      $("#CUDDBox").toggleClass("bg-green");
      $("#CUDDBox").toggleClass("bg-red");
      $("#iconCUDD").toggleClass("glyphicon-ok");
      $("#iconCUDD").toggleClass("ion-android-alert");
      if(stopedCUDD == 0){
         runningCUDD = 1;
         setTimeout(intermiteciaCUDD, 500);
      }
      else{
        stopCUDD();
      }
    }
    function actualizarAlertaCUDD(){
      var numero = 1;
        $.ajax({
            beforeSend: function () {
                $("#iconCUDD").toggleClass("fa");
                $("#iconCUDD").toggleClass("fa-refresh");
                $("#iconCUDD").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconCUDD").toggleClass("fa");
                $("#iconCUDD").toggleClass("fa-refresh");
                $("#iconCUDD").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/bt/errores/cantPickTicketDuplicados',
            dataType: 'json',
            success: function(result){
                result.forEach(function(element){
                    if(element.CANTIDAD > 0){
                        if(runningCUDD == 0){
                            stopedCUDD = 0;
                            intermiteciaCUDD();
                            
                        }
                        setTimeout(actualizarAlertaCUDD, 600000);
                    }else{
                        setTimeout(actualizarAlertaCUDD, 600000);
                        if(stopedCUDD == 0 && runningCUDD == 1){
                            runningCUDD = 0;
                            stopedCUDD = 1
                        }
                    }
                    setInterval(function(){ if(numero <= element.CANTIDAD){$("#nCUDD").html(numero);numero++;} }, 3);
                });
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    function stopCUDD(){
        $("#CUDDBox").removeClass("bg-green");
        $("#CUDDBox").removeClass("bg-red");
        $("#iconCUDD").removeClass("glyphicon-ok");
        $("#iconCUDD").removeClass("ion-android-alert"); 
        $("#CUDDBox").addClass("bg-green");
        $("#iconCUDD").addClass("glyphicon-ok");
    }
    $("#CUDDDetalles").click(function(){
        actualizarAlertaCUDD();
        var popupdetallecudd = $("#POPUP_Detalle_CUDD");
        popupdetallecudd.data("kendoWindow").open();
        var grid = $("#gridDetCUDD");
        grid.data("kendoGrid").dataSource.read();
    });
    var ventana_detalle_cudd = $("#POPUP_Detalle_CUDD");
    ventana_detalle_cudd.kendoWindow({
        width: "1000px",
        height: "550px",
        title: "CUD Duplicados",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();
    $("#gridDetCUDD").kendoGrid({
        autoBind: false,
        dataSource: dataSourceDetCUDD,
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
            {field: "CUD",title: "CUD",width: 70,filterable: {multi: true, search: true}},
            {field: "PKT_CTRL_NBR",title: "PKT",width:70, filterable:false},
            {field: "STAT_CODE",title: "ESTADO",width:100,filterable: false},
            {field: "FECHA_CREACION",title: "FECHA CREACION",width: 70,filterable: false}
        ]
    });
    function onReadCUDD(e){
        $.ajax({
            type: "POST",
            url: baseURL + 'alertas/bt/errores/pickTicketDuplicados',
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