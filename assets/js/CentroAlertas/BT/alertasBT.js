$(document).ready(function(){

	//llamada a alertas

	actualizarAlertaSDI();
    actualizarAlertaVBT();
    actualizarAlertaCUDD();

	//declaracion de variables

    var pkts = [];
    var sku = "";

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
                if(result > 0){
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
                    $("#nCUDD").html(result);
                }
                setInterval(function(){ if(numero <= result){$("#nCUDD").html(numero);numero++;} }, 3);
               
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
    $("#toolbarCUD").kendoToolBar({
        items: [
            { type: "button", text: "Actualizar PickTicket", icon: "k-icon k-i-change-manually" ,click: ActualizarPKT}
        ]
    });
    $("#gridDetCUDD").kendoGrid({
        autoBind: false,
        dataSource: dataSourceDetCUDD,
        height: "100%", 
        width: "1000px",
        sortable: true, 
        filterable: true,
        scrollable: true,
        change: function (e, args) {
                    pkts = [];
                    var rows = e.sender.select();
                    rows.each(function(e) {
                        var grid = $("#gridDetCUDD").data("kendoGrid");
                        var item = grid.dataItem(this);
                        pkts.push({PKT_CTRL_NBR: item.PKT_CTRL_NBR});
                    })
                    console.log(pkts);  
        },
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [
            {selectable: true, width: "15px" },
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
    function ActualizarPKT(){
        $.ajax({
            type: "POST",
            url: baseURL + 'alertas/bt/actualizarPKT',
            dataType: 'json',
            data: {pkts: JSON.stringify(pkts)},
            success: function(result){
                if(result == 0){
                    var grid = $("#gridDetCUDD");
                    grid.data("kendoGrid").dataSource.read();
                    $("#success-modal").html("PickTicket Actualizado Correctamente");
                    $("#modal-success").modal('show');
                }else{
                    alert("Error al actualizar PickTickets");
                }
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }

    // PEDIDOS SIN STOCK

    var dataSourcePSS = new kendo.data.DataSource({
        transport: {
            read: onReadPSS
        },
        schema: {
            model: {
                id: "BODEGA",
                fields: {
                        BODEGA: {type: "string"}, // number - string - date
                        PKT: {type: "string"},
                        PALNIFICAION_AUTOMATICA: {type: "string"},
                        BATCHNBR: {type: "string"}, // number - string - date
                        LPN: {type: "string"},
                        OLA: {type: "string"},
                        DESC_OLA: {type: "string"},
                        SKU: {type: "string"},
                        SKU_DESC: {type: "string"},
                        DEPTO: {type: "string"},
                        DESCP_DEPTO: {type: "string"},
                        CANT: {type: "string"},
                        FECHA_OLA: {type: "string"},
                        USUARIO: {type: "string"},
                        DISP_CASE_PICK: {type: "string"},
                        DISP_ACTIVO: {type: "string"},
                        WR: {type: "string"},
                        PISO: {type: "string"},
                        RACK: {type: "string"},
                        PP: {type: "string"},
                        TOTAL: {type: "string"}
                    }
            }
        },
        pageSize: 100
    });

    var dataSourceSR = new kendo.data.DataSource({
        transport: {
            read: onReadSR
        },
        schema: {
            model: {
                id: "ART",
                fields: {
                        ART: {type: "string"}, // number - string - date
                        DESC_ART: {type: "string"},
                        CANTIDAD: {type: "string"},
                        LPN: {type: "string"}, // number - string - date
                        ESTADO_LPN: {type: "string"},
                        AREA: {type: "string"},
                        DSP_LOCN: {type: "string"}
                    }
            }
        },
        pageSize: 100
    });

    var dataSourcePP = new kendo.data.DataSource({
        transport: {
            read: onReadPP
        },
        schema: {
            model: {
                id: "CASE_NBR",
                fields: {
                        CASE_NBR: {type: "string"}, // number - string - date
                        CODE_DESC: {type: "string"},
                        FECHA_RECEPCION: {type: "string"},
                        DSP_LOCN: {type: "string"}, // number - string - date
                        INVN_LOCK_CODE: {type: "string"},
                        SKU_ID: {type: "string"},
                        SKU_DESC: {type: "string"},
                        ACTL_QTY: {type: "string"},
                        USER_ID: {type: "string"}
                    }
            }
        },
        pageSize: 100
    });

    var dataSourceRR = new kendo.data.DataSource({
        transport: {
            read: onReadRR
        },
        schema: {
            model: {
                id: "LPN",
                fields: {
                        LPN: {type: "string"}, // number - string - date
                        ESTADO: {type: "string"},
                        FECHA_RECEPCION: {type: "string"},
                        LOCACION: {type: "string"}, // number - string - date
                        INVN_LOCK_CODE: {type: "string"},
                        ARTICULO: {type: "string"},
                        DESCRIPCION: {type: "string"},
                        CANTIDAD: {type: "string"},
                        USER_ID: {type: "string"}
                    }
            }
        },
        pageSize: 100
    });

    $("#gridSR").kendoGrid({
        selectable: "cell",
        autoBind: false,
        dataSource: dataSourceSR,
        height: "90%", 
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
            {field: "ART",title: "ARTICULO",width: 70, filterable:false},
            {field: "DESC_ART",title: "DESC ARTICULO",width:130, filterable:false},
            {field: "CANTIDAD",title: "CANTIDAD",width:80, filterable:false},
            {field: "LPN",title: "LPN",width: 120,filterable: false},
            {field: "ESTADO_LPN",title: "ESTADO LPN",width:130,filterable: false},
            {field: "AREA",title: "AREA",width:120,filterable: false},
            {field: "DSP_LOCN",title: "UBICACION",width:100,filterable: false}
        ]
    });

    $("#gridDetPSS").kendoGrid({
        selectable: "cell",
        autoBind: false,
        dataSource: dataSourcePSS,
        height: "90%", 
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
            {field: "BODEGA",title: "BODEGA",width: 80, filterable:false},
            {field: "PKT",title: "PKT",width:100, filterable:false},
            {field: "PALNIFICAION_AUTOMATICA",title: "PA",width:40, filterable:false},
            {field: "BATCHNBR",title: "BATCH NBR",width:130,filterable: false},
            {field: "LPN",title: "LPN",width: 160,filterable: false},
            {field: "OLA",title: "OLA",width:120,filterable: false},
            {field: "DESC_OLA",title: "DESC OLA",width:100,filterable: false},
            {field: "SKU",title: "SKU",width:100,filterable: false},
            {field: "SKU_DESC",title: "SKU DESC",width:140,filterable: false},
            {field: "DEPTO",title: "DEPTO",width:70,filterable: false},
            {field: "DESCP_DEPTO",title: "DESC DEPTO",width:120,filterable: false},
            {field: "CANT",title: "CANT",width:70,filterable: false},
            {field: "FECHA_OLA",title: "FECHA OLA",width:100,filterable: false},
            {field: "USUARIO",title: "USUARIO",width:100,filterable: false},
            {field: "DISP_CASE_PICK",title: "CASE PICK",width:100,filterable: false},
            {field: "DISP_ACTIVO",title: "ACTIVO",width:100,filterable: false},
            {field: "WR",title: "WR",width:80,filterable: false},
            {field: "PISO",title: "PISO",width:80,filterable: false},
            {field: "RACK",title: "RACK",width:80,filterable: false},
            {field: "PP",title: "PP",width:40,filterable: false},
            {field: "TOTAL",title: "TOTAL",width:120,filterable: false}
        ]
    }).on("click", "tbody td", function(e) {
        var cell = $(e.currentTarget);
        var cellIndex = cell[0].cellIndex;
        var grid = $("#gridDetPSS").data("kendoGrid");
        var column = grid.columns[cellIndex];
        var columnsku = grid.columns[7];
        var columnpp = grid.columns[19];
        var columnrr = grid.columns[18];
        var dataItem = grid.dataItem(cell.closest("tr"));

        if(column.field == 'PP'){

            if(dataItem[columnpp.field] =! "" && dataItem[columnpp.field] > 0){
                sku = dataItem[columnsku.field];
                console.log(sku);
                var popupPP = $("#POPUP_Detalle_PP");
                popupPP.data("kendoWindow").open();
                var grid = $("#gridPP");
                grid.data("kendoGrid").dataSource.read();
            }
        }
        if(column.field == 'RACK'){

            if(dataItem[columnrr.field] =! "" && dataItem[columnrr.field] > 0){
                sku = dataItem[columnsku.field];
                console.log(sku);
                var popupPP = $("#POPUP_Detalle_RR");
                popupPP.data("kendoWindow").open();
                var grid = $("#gridRR");
                grid.data("kendoGrid").dataSource.read();
            }
        }        
    });

    $("#gridPP").kendoGrid({
        autoBind: false,
        dataSource: dataSourcePP,
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
            {field: "CASE_NBR",title: "LPN",width: 130, filterable:false},
            {field: "CODE_DESC",title: "ESTADO LPN",width: 80, filterable:false},
            {field: "FECHA_RECEPCION",title: "FECHA RECEPCION",width:80, filterable:false},
            {field: "DSP_LOCN",title: "UBICACION",width:80,filterable: false},
            {field: "INVN_LOCK_CODE",title: "CODIGO BLOQUEO",width: 70,filterable: false},
            {field: "SKU_ID",title: "SKU",width:80,filterable: false},
            {field: "SKU_DESC",title: "SKU DESC",width:100,filterable: false},
            {field: "ACTL_QTY",title: "CANTIDAD",width:60,filterable: false},
            {field: "USER_ID",title: "USUARIO",width:80,filterable: false}
        ]
    });

    $("#gridRR").kendoGrid({
        autoBind: false,
        dataSource: dataSourceRR,
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
            {field: "LPN",title: "LPN",width: 130, filterable:false},
            {field: "ESTADO",title: "ESTADO LPN",width: 80, filterable:false},
            {field: "FECHA_RECEPCION",title: "FECHA RECEPCION",width:80, filterable:false},
            {field: "LOCACION",title: "UBICACION",width:80,filterable: false},
            {field: "INVN_LOCK_CODE",title: "CODIGO BLOQUEO",width: 70,filterable: false},
            {field: "ARTICULO",title: "SKU",width:80,filterable: false},
            {field: "DESCRIPCION",title: "SKU DESC",width:100,filterable: false},
            {field: "CANTIDAD",title: "CANTIDAD",width:60,filterable: false},
            {field: "USER_ID",title: "USUARIO",width:80,filterable: false}
        ]
    });

    $("#toolbarPSS").kendoToolBar({
        items: [
            { type: "button", text: "Exportar", icon: "k-icon k-i-file-excel" ,click: ExportarPSS},
            { type: "button", text: "Reabastecer", icon: "k-icon k-i-search" ,click: SoloReabastecer}
        ]
    });

    var ventana_detalle_pss = $("#POPUP_Detalle_PSS");
    ventana_detalle_pss.kendoWindow({
        width: "1000px",
        height: "550px",
        title: "Pedidos No Asignados WMS",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").maximize();

    var ventana_detalle_sr = $("#POPUP_Detalle_SR");
    ventana_detalle_sr.kendoWindow({
        width: "1000px",
        height: "550px",
        title: "Solo Reabastecer",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();

    var ventana_detalle_pp = $("#POPUP_Detalle_PP");
    ventana_detalle_pp.kendoWindow({
        width: "1000px",
        height: "550px",
        title: "Detalle Stock PP",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();

    var ventana_detalle_rr = $("#POPUP_Detalle_RR");
    ventana_detalle_rr.kendoWindow({
        width: "1000px",
        height: "550px",
        title: "Detalle Stock Reserva",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();

    $("#UpdPSS").click(function(){
        var grid = $("#gridDetPSS");
        grid.data("kendoGrid").dataSource.read();
    });

    function SoloReabastecer(){
        var popupdetallepss = $("#POPUP_Detalle_SR");
        popupdetallepss.data("kendoWindow").open();
        var grid = $("#gridSR");
        grid.data("kendoGrid").dataSource.read();
    }

    $("#PSSDetalles").click(function(){
        var popupdetallepss = $("#POPUP_Detalle_PSS");
        popupdetallepss.data("kendoWindow").open();
    });

    function onReadPSS(e){
        $.ajax({
            beforeSend: function () {
                $("#iconPSS").toggleClass("fa-refresh");
                $("#iconPSS").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconPSS").toggleClass("fa-refresh");
                $("#iconPSS").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/bt/PSinStock',
            dataType: 'json',
            data: {pkts: JSON.stringify(pkts)},
            success: function(result){
                if(result.length > 0){
                    e.success(result);

                }
                $("#iconPSS").toggleClass("fa-download");
                $("#iconPSS").toggleClass("fa");
                $("#iconPSS").addClass("glyphicon");
                $("#iconPSS").toggleClass("glyphicon-ok");
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }

    function onReadPP(e){
        $.ajax({
            type: "POST",
            url: baseURL + 'alertas/bt/PP',
            dataType: 'json',
            data: {sku: sku},
            success: function(result){
                e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }    

    function onReadSR(e){
        $.ajax({
            type: "POST",
            url: baseURL + 'alertas/bt/soloreabastecer',
            dataType: 'json',
            data: {sku: sku},
            success: function(result){
                e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }

    function onReadRR(e){
        $.ajax({
            type: "POST",
            url: baseURL + 'alertas/bt/RR',
            dataType: 'json',
            data: {sku: sku},
            success: function(result){
                e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }
    
    function ExportarPSS(){
       var rows = [{
            cells: [
              { value: "BODEGA" },
              { value: "PKT" },
              { value: "PA" },
              { value: "BATCH NBR" },
              { value: "LPN" },
              { value: "OLA" },
              { value: "DESC_OLA" },
              { value: "SKU" },
              { value: "SKU DESC" },
              { value: "DEPTO" },
              { value: "DESC DEPTO" },
              { value: "CANT" },
              { value: "FECHA OLA" },
              { value: "USUARIO" },
              { value: "CASE PICK" },
              { value: "ACTIVO" },
              { value: "WR" },
              { value: "PISO" },
              { value: "RACK" },
              { value: "PP" },
              { value: "TOTAL" }
            ]
          }];
        var data = dataSourcePSS.data();
        for (var i = 0; i < data.length; i++){
          // Push single row for every record.
          rows.push({
            cells: [
              { value: data[i].BODEGA },
              { value: data[i].PKT },
              { value: data[i].PALNIFICAION_AUTOMATICA },
              { value: data[i].BATCHNBR },
              { value: data[i].LPN },
              { value: data[i].OLA },
              { value: data[i].DESC_OLA },
              { value: data[i].SKU },
              { value: data[i].SKU_DESC },
              { value: data[i].DEPTO },
              { value: data[i].DESCP_DEPTO },
              { value: data[i].CANT },
              { value: data[i].FECHA_OLA },
              { value: data[i].USUARIO },
              { value: data[i].DISP_CASE_PICK },
              { value: data[i].DISP_ACTIVO },
              { value: data[i].WR },
              { value: data[i].PISO },
              { value: data[i].RACK },
              { value: data[i].PP },
              { value: data[i].TOTAL }
            ]
          })
        }
        var workbook = new kendo.ooxml.Workbook({
          sheets: [
            {
              columns: [
                // Column settings (width).
                { autoWidth: true },
                { autoWidth: true },
                { autoWidth: true },
                { autoWidth: true },
                { autoWidth: true },
                { autoWidth: true },
                { autoWidth: true },
                { autoWidth: true },
                { autoWidth: true },
                { autoWidth: true },
                { autoWidth: true },
                { autoWidth: true },
                { autoWidth: true },
                { autoWidth: true },
                { autoWidth: true },
                { autoWidth: true },
                { autoWidth: true },
                { autoWidth: true },
                { autoWidth: true },
                { autoWidth: true },
                { autoWidth: true }
              ],
              // The title of the sheet.
              title: "Pedidos sin stock",
              // The rows of the sheet.
              rows: rows
            }
          ]
        });
        // Save the file as an Excel file with the xlsx extension.
        kendo.saveAs({dataURI: workbook.toDataURL(), fileName: "Pedidos Sin Stock.xlsx"}); 
    }

    $("#toolbarSR").kendoToolBar({
        items: [
            { type: "button", text: "Exportar", icon: "k-icon k-i-file-excel" ,click: ExportarSR},
        ]
    });

    function ExportarSR(){
       var rows = [{
            cells: [
              { value: "ARTICULO" },
              { value: "DESC ARTICULO" },
              { value: "CANTIDAD" },
              { value: "LPN" },
              { value: "ESTADO LPN" },
              { value: "AREA" },
              { value: "UBICACION" }
            ]
          }];
        var data = dataSourceSR.data();
        for (var i = 0; i < data.length; i++){
          // Push single row for every record.
          rows.push({
            cells: [
              { value: data[i].ART },
              { value: data[i].DESC_ART },
              { value: data[i].CANTIDAD },
              { value: data[i].LPN },
              { value: data[i].ESTADO_LPN },
              { value: data[i].AREA },
              { value: data[i].DSP_LOCN },
            ]
          })
        }
        var workbook = new kendo.ooxml.Workbook({
          sheets: [
            {
              columns: [
                // Column settings (width).
                { autoWidth: true },
                { autoWidth: true },
                { autoWidth: true },
                { autoWidth: true },
                { autoWidth: true },
                { autoWidth: true },
                { autoWidth: true }
              ],
              // The title of the sheet.
              title: "Solo Reserva",
              // The rows of the sheet.
              rows: rows
            }
          ]
        });
        // Save the file as an Excel file with the xlsx extension.
        kendo.saveAs({dataURI: workbook.toDataURL(), fileName: "Solo Reserva.xlsx"}); 
    }

    // PEDIDOS SIN STOCK BIG TICKET

    var dataSourcePSSBT = new kendo.data.DataSource({
        transport: {
            read: onReadPSSBT
        },
        schema: {
            model: {
                id: "CUD",
                fields: {
                        CUD: {type: "string"}, // number - string - date
                        SKU: {type: "string"},
                        SKU_DESC: {type: "string"},
                        CANT: {type: "string"}, // number - string - date
                        SUC_STOCK: {type: "string"},
                        SUC_DESP: {type: "string"},
                        FECHAVTA: {type: "string"},
                        FECHA_PLAN: {type: "string"},
                        ESTADO: {type: "string"},
                        MOTIVO: {type: "string"},
                        DISP_CASE_PICK: {type: "string"},
                        DISP_ACTIVO: {type: "string"},
                        RESERVA: {type: "string"},
                        PP: {type: "string"},
                        TOTAL: {type: "string"}
                    }
            }
        },
        pageSize: 100
    });

    function onReadPSSBT(){
        $.ajax({
            beforeSend: function () {
                $("#iconPSSBT").toggleClass("fa-refresh");
                $("#iconPSSBT").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconPSSBT").toggleClass("fa-refresh");
                $("#iconPSSBT").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/bt/PSinStockBT',
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }

    $("#gridDetPSSBT").kendoGrid({
        autoBind: false,
        dataSource: dataSourcePSSBT,
        height: "100%", 
        width: "100%",
        sortable: true, 
        filterable: true,
        scrollable: true,
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [
            {field: "CUD",title: "CUD",width: 130, filterable:false},
            {field: "SKU",title: "SKU",width: 80, filterable:false},
            {field: "SKU_DESC",title: "DESC SKU",width:80, filterable:false},
            {field: "CANT",title: "CANTIDAD",width:80,filterable: false},
            {field: "SUC_STOCK",title: "SUC STOCK",width: 70,filterable: false},
            {field: "SUC_DESP",title: "SUC DESP",width:80,filterable: false},
            {field: "FECHAVTA",title: "FECHA VTA",width:60,filterable: false},
            {field: "FECHA_PLAN",title: "FECHA PLAN",width:80,filterable: false},
            {field: "ESTADO",title: "ESTADO",width:80,filterable: false},
            {field: "MOTIVO",title: "MOTIVO",width:80,filterable: false},
            {field: "DISP_CASE_PICK",title: "CASE PICK",width:80,filterable: false},
            {field: "DISP_ACTIVO",title: "ACTIVO",width:80,filterable: false},
            {field: "RESERVA",title: "RESERVA",width:80,filterable: false},
            {field: "PP",title: "PP",width:80,filterable: false},
            {field: "TOTAL",title: "TOTAL",width:80,filterable: false}
        ]
    });

    var ventana_detalle_pss = $("#POPUP_Detalle_PSSBT");
    ventana_detalle_pss.kendoWindow({
        width: "1000px",
        height: "550px",
        title: "Pedidos No Asignados BT",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").maximize();

    $("#UpdPSSBT").click(function(){
        var grid = $("#gridDetPSSBT");
        grid.data("kendoGrid").dataSource.read();
    });

    /*$("#PSSBTDetalles").click(function(){
        var popupdetallepss = $("#POPUP_Detalle_PSSBT");
        popupdetallepss.data("kendoWindow").open();
    });¨*/
    
    $("#PSSBTDetalles").click(function(){
        $.ajax({
            beforeSend: function () {
                $("#iconPSSBT").toggleClass("fa-refresh");
                $("#iconPSSBT").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconPSSBT").toggleClass("fa-refresh");
                $("#iconPSSBT").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/bt/cargar',
            dataType: 'json',
            success: function(result){
                if(result == 1){
                    alert('cargo');
                }
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    });

});