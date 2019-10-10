$(document).ready(function(){

    //LLAMADA A FUNCIONES DE ALERTA

	actualizarAlertaPO();
    actualizarAlertaPKT();
    actualizarAlertaBRCD();
    actualizarAlertaART();
    actualizarAlertaOLA();
    actualizarAlertaCITA();
    actualizarAlertaASN();
    actualizarAlertaLPN();
    actualizarAlertaDISTRO();
    actualizarAlertaCARGA();
    actualizarAlertaFASN();

    //DECLARACION DE VARIABLES

    var pkts = [];
    var pos = [];
    var brcds = [];
    var arts = [];
    var citas = [];
    var asns = [];
    var lpns = [];
    var distros = [];
    var cargas = [];
    var fasns = [];

    var stopedPKT = 0;
    var runningPKT = 0;
    var stopedPO = 0;
    var runningPO = 0;
    var stopedBRCD = 0;
    var runningBRCD = 0;
    var runningART = 0;
    var stopedART = 0;
    var stopedOLA = 0;
    var runningOLA = 0;
    var stopedCITA = 0;
    var runningCITA = 0;
    var stopedASN = 0;
    var runningASN = 0;
    var stopedLPN = 0;
    var runningLPN = 0;
    var stopedDISTRO = 0;
    var runningDISTRO = 0;
    var stopedCARGA = 0;
    var runningCARGA = 0;
    var stopedFASN = 0;
    var runningFASN = 0;
    var codigoASN = "";
    var codigoCITA = "";

    //FUNCIONALIDADES ALERTA PKT

    var dataSourceDetPKT = new kendo.data.DataSource({
        transport: {
            read: onReadErrPKT
        },
        schema: {
            model: {
                id: "PKT_CTRL_NBR",
                fields: {
                        PKT_CTRL_NBR: {type: "string"}, // number - string - date
                        MSG_HDR: {type: "string"},
                        SIZE_DESC: {type: "string"}, // number - string - date
                        MSG_DTL: {type: "string"}
                    }
            }
        },
        pageSize: 15
    });
    var dataSourceResPKT = new kendo.data.DataSource({
        transport: {
            read: onReadResPKT
        },
        schema: {
            model: {
                id: "STAT_CODE",
                fields: {
                        STAT_CODE: {type: "string"}, // number - string - date
                        DESC_ESTADO: {type: "string"},
                        TOTAL: {type: "string"}
                    }
            }
        },
        pageSize: 15
    });
    function intermitenciaPKT(){
      $("#pktBox").toggleClass("bg-green");
      $("#pktBox").toggleClass("bg-red");
      $("#iconPKT").toggleClass("glyphicon-ok");
      $("#iconPKT").toggleClass("ion-android-alert");
      if(stopedPKT == 0){
         runningPKT = 1;
         setTimeout(intermitenciaPKT, 500);
      } 
      else{
        stopPKT();
      }
    }
    function actualizarAlertaPKT(){
        $.ajax({
            beforeSend: function () {
                $("#iconPKT").toggleClass("fa");
                $("#iconPKT").toggleClass("fa-refresh");
                $("#iconPKT").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconPKT").toggleClass("fa");
                $("#iconPKT").toggleClass("fa-refresh");
                $("#iconPKT").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/wms/errores/cantPKT',
            dataType: 'json',
            success: function(result){
                if(result > 0){
                    if(runningPKT == 0){
                        stopedPKT = 0;
                        intermitenciaPKT();
                        
                    }
                    setTimeout(actualizarAlertaPKT, 600000);
                }else{
                    setTimeout(actualizarAlertaPKT, 600000);
                    if(stopedPKT == 0 && runningPKT == 1){
                        runningPKT = 0;
                        stopedPKT = 1
                    }
                }

            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
        $.ajax({
            beforeSend: function () {
                $("#iconPKT").toggleClass("fa");
                $("#iconPKT").toggleClass("fa-refresh");
                $("#iconPKT").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconPKT").toggleClass("fa");
                $("#iconPKT").toggleClass("fa-refresh");
                $("#iconPKT").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/wms/pkt/totPKT',
            dataType: 'json',
            success: function(result){
                result.forEach(function(element){
                    $("#npkt").html(element.TOT);
                });
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    function stopPKT(){
        $("#pktBox").removeClass("bg-green");
        $("#pktBox").removeClass("bg-red");
        $("#iconPKT").removeClass("glyphicon-ok");
        $("#iconPKT").removeClass("ion-android-alert"); 
        $("#pktBox").addClass("bg-green");
        $("#iconPKT").addClass("glyphicon-ok");
    }
    $("#gridDetPKT").kendoGrid({
        autoBind: false,
        dataSource: dataSourceDetPKT,
        height: "90%", 
        width: "600px",
        sortable: true, 
        filterable: true,
        scrollable: true,
        change: function (e, args) {
                    pkts = [];
                    var rows = e.sender.select();
                    rows.each(function(e) {
                        var grid = $("#gridDetPKT").data("kendoGrid");
                        var item = grid.dataItem(this);
                        pkts.push({PKT_CTRL_NBR: item.PKT_CTRL_NBR});
                    })  
        },
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {selectable: true, width: "15px" },
            {field: "PKT_CTRL_NBR",title: "NUMERO PEDIDO",width: 70, filterable:false, resizable:false, height: 80},
            {field: "MSG_HDR",title: "MENSAJE CABECERA",width:70,filterable:false},
            {field: "SIZE_DESC",title: "SKU",width:70,filterable: false},
            {field: "MSG_DTL",title: "MENSAJE DETALLE",width: 70,filterable: false}
        ]
    });
    $("#gridResPKT").kendoGrid({
        autoBind: false,
        dataSource: dataSourceResPKT,
        height: "100%", 
        width: "600px",
        sortable: true, 
        filterable: true,
        scrollable: true,
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {field: "STAT_CODE",title: "CODIGO ESTADO",width: 70, filterable:false, resizable:false, height: 80},
            {field: "DESC_ESTADO",title: "DESC ESTADO",width:70,filterable:false},
            {field: "TOTAL",title: "TOTAL",width:70,filterable: false}
        ]
    });
    $("#pktDetalles").click(function(){
        actualizarAlertaPKT();
        var popupdetallepkt = $("#POPUP_Detalle_PKT");
        popupdetallepkt.data("kendoWindow").open();
        var grid = $("#gridDetPKT");
        grid.data("kendoGrid").dataSource.read();
    });
    $("#PKTBajados").click(function(){
        var popupresumenpkt = $("#POPUP_Resumen_PKT");
        popupresumenpkt.data("kendoWindow").open();
        var grid = $("#gridResPKT");
        grid.data("kendoGrid").dataSource.read();
    });
    $("#PKTBajados").hover(function(){
        $("#iconPKT").toggleClass("ion-clipboard");
    });
    var ventana_detalle_pkt = $("#POPUP_Detalle_PKT");
    ventana_detalle_pkt.kendoWindow({
        width: "750px",
        height: "550px",
        title: "Detalle Errores PKT",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();
    var ventana_resumen_pkt = $("#POPUP_Resumen_PKT");
    ventana_resumen_pkt.kendoWindow({
        width: "750px",
        height: "550px",
        title: "Resumen PKT Bajados",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();
    function onReadErrPKT(e){
         $.ajax({
            type: "POST",
            url: baseURL + 'alertas/wms/errores/PKT',
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }
    function onReadResPKT(e){
         $.ajax({
            type: "POST",
            url: baseURL + 'alertas/wms/pkt/resumen',
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }
    $("#toolbarPKT").kendoToolBar({
        items: [
            { type: "button", text: "Reprocesar", icon: "k-icon k-i-reload" ,click: ActualizarPKT},
            { type: "button", text: "Eliminar", icon: "k-icon k-i-delete" ,click: EliminarPKT}
        ]
    });
    function ActualizarPKT(){
        var data = JSON.stringify(pkts);
        if(Array.isArray(pkts) && pkts.length != 0){
            $.ajax({
                type: "POST",
                url: baseURL + 'alertas/wms/pkt/actualizar',
                data:{ pkts: data},
                dataType: 'json',
                success: function(result){
                    if(result == 0){                       
                        var popupdetallepkt = $("#POPUP_Detalle_PKT");
                        popupdetallepkt.data("kendoWindow").close();
                        $("#success-modal").text("Pick Ticket Actualizado Correctamente");
                        $("#modal-success").modal('show');
                        actualizarAlertaPKT();
                        pkts = [];
                    }
                    else{
                        var popupdetallepkt = $("#POPUP_Detalle_PKT");
                        popupdetallepkt.data("kendoWindow").close();
                        $("#error-modal").text("Error al actualizar Pick Ticket");
                        $("#modal-danger").modal('show');
                        pkts = [];
                    }
                },
                error: function(xhr){
                    console.log('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
                }
            });
        }
        else{;
            var popupdetallepkt = $("#POPUP_Detalle_PKT");
            popupdetallepkt.data("kendoWindow").close();
            $("#error-modal").text("Debe seleccionar al menos un Pick Ticket para actualizar");
            $("#modal-danger").modal('show');
        }
    }
    function EliminarPKT(){
        if(Array.isArray(pkts) && pkts.length != 0){
            var data = JSON.stringify(pkts);
            var ok = confirm("Esta seguro que desea eliminar estos Pick Ticket?");
            if(ok){
                $.ajax({
                    type: "POST",
                    url: baseURL + 'alertas/wms/pkt/eliminar',
                    data:{ pkts: data},
                    dataType: 'json',
                    success: function(result){
                        if(result == 0){
                            var popupdetallepkt = $("#POPUP_Detalle_PKT");
                            popupdetallepkt.data("kendoWindow").close();
                            $("#success-modal").text("Pick Ticket Eliminado Correctamente");
                            $("#modal-success").modal('show');
                            actualizarAlertaPKT();
                            pkts = [];
                        }
                        else{
                            var popupdetallepkt = $("#POPUP_Detalle_PKT");
                            popupdetallepkt.data("kendoWindow").close();
                            $("#error-modal").text("Error al eliminar Pick Ticket");
                            $("#modal-danger").modal('show');
                            pkts = [];
                        }
                    },
                    error: function(xhr){
                        console.log('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
                    }
                });
            }
            else{
                var popupdetallepkt = $("#POPUP_Detalle_PKT");
                popupdetallepkt.data("kendoWindow").close();
                $("#modal-info").modal('show');
            }
        }    
        else{
            console.log(pkts);
            var popupdetallepkt = $("#POPUP_Detalle_PKT");
            popupdetallepkt.data("kendoWindow").close();
            $("#error-modal").text("Debe seleccionar al menos un pick ticket para eliminar");
            $("#modal-danger").modal('show');
        }  
    }

    //FUNCIONALIDADES ALERTA PO
	 
     var dataSourceDetPO = new kendo.data.DataSource({
        transport: {
            read: onReadErrPO
        },
        schema: {
            model: {
                id: "PO_NBR",
                fields: {
                        PO_NBR: {type: "string"}, // number - string - date
                        MSG_HDR: {type: "string"},
                        SIZE_DESC: {type: "string"}, // number - string - date
                        MSG_DTL: {type: "string"}
                    }
            }
        },
        pageSize: 15
    });
     var dataSourceVerPO = new kendo.data.DataSource({
        transport: {
            read: onReadVerPO
        },
        schema: {
            model: {
                id: "PO_NBR",
                fields: {
                        PO_NBR: {type: "string"}, // number - string - date
                        MOD_DATE_TIME: {type: "string"},
                        STAT_CODE: {type: "string"}, // number - string - date
                        CODE_DESC: {type: "string"}
                    }
            }
        },
        pageSize: 15
    });
	function intermitenciaPO(){
      $("#POBox").toggleClass("bg-green");
      $("#POBox").toggleClass("bg-red");
      $("#iconPO").toggleClass("glyphicon-ok");
      $("#iconPO").toggleClass("ion-android-alert");
      if(stopedPO == 0){
         runningPO = 1;
         setTimeout(intermitenciaPO, 500);
      }
      else{
        stopPO();
      }
    }
    function actualizarAlertaPO(){
        $.ajax({
            beforeSend: function () {
                $("#iconPO").toggleClass("fa");
                $("#iconPO").toggleClass("fa-refresh");
                $("#iconPO").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconPO").toggleClass("fa");
                $("#iconPO").toggleClass("fa-refresh");
                $("#iconPO").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/wms/errores/cantPO',
            dataType: 'json',
            success: function(result){
                if(result > 0){
                    if(runningPO == 0){
                        stopedPO = 0;
                        intermitenciaPO();
                        
                    }
                    setTimeout(actualizarAlertaPO, 600000);
                }else{
                    setTimeout(actualizarAlertaPO, 600000);
                    if(stopedPO == 0 && runningPO == 1){
                        runningPO = 0;
                        stopedPO = 1
                    }
                }

            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
        $.ajax({
            beforeSend: function () {
                $("#iconPO").toggleClass("fa");
                $("#iconPO").toggleClass("fa-refresh");
                $("#iconPO").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconPO").toggleClass("fa");
                $("#iconPO").toggleClass("fa-refresh");
                $("#iconPO").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/wms/PO/totPO',
            dataType: 'json',
            success: function(result){
                result.forEach(function(element){
                    $("#nPO").html(element.TOT);
                });
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    function stopPO(){
        $("#POBox").removeClass("bg-green");
        $("#POBox").removeClass("bg-red");
        $("#iconPO").removeClass("glyphicon-ok");
        $("#iconPO").removeClass("ion-android-alert"); 
        $("#POBox").addClass("bg-green");
        $("#iconPO").addClass("glyphicon-ok");
    }
    
     $("#gridDetPO").kendoGrid({
        autoBind: false,
        dataSource: dataSourceDetPO,
        height: "90%", 
        width: "600px",
        sortable: true, 
        filterable: true,
        scrollable: true,
        change: function (e, args) {
                    pos = [];
                    var rows = e.sender.select();
                    rows.each(function(e) {
                        var grid = $("#gridDetPO").data("kendoGrid");
                        var item = grid.dataItem(this);
                        pos.push({PO_NBR: item.PO_NBR});
                    }) 
        },
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {selectable: true, width: "15px" },
            {field: "PO_NBR",title: "ORDEN DE COMPRA",width: 70, filterable:false, resizable:false, height: 80},
            {field: "MSG_HDR",title: "MENSAJE OC",width:70,filterable:false},
            {field: "SIZE_DESC",title: "SKU",width:70,filterable: false},
            {field: "MSG_DTL",title: "MENSAJE SKU",width: 70,filterable: false}
        ]
    });
    $("#gridVerPO").kendoGrid({
        autoBind: false,
        dataSource: dataSourceVerPO,
        height: "90%", 
        width: "600px",
        sortable: true, 
        filterable: true,
        scrollable: true,
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {field: "PO_NBR",title: "ORDEN DE COMPRA",width: 70, filterable:false, resizable:false, height: 80},
            {field: "MOD_DATE_TIME",title: "ULTIMA MODIFICACION",width:70,filterable:false},
            {field: "STAT_CODE",title: "ESTADO",width:70,filterable: false},
            {field: "CODE_DESC",title: "DESCRIPCION",width: 70,filterable: false}
        ]
    });
     $("#PODetalles").click(function(){
        actualizarAlertaPO();
        var popupdetallepo = $("#POPUP_Detalle_PO");
        popupdetallepo.data("kendoWindow").open();
        var grid = $("#gridDetPO");
        grid.data("kendoGrid").dataSource.read();
    });
   
    var ventana_detalle_po = $("#POPUP_Detalle_PO");
    ventana_detalle_po.kendoWindow({
        width: "750px",
        height: "550px",
        title: "Detalle Errores PO",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();

    var ventana_verificar_po = $("#POPUP_Verificar_PO");
    ventana_verificar_po.kendoWindow({
        width: "750px",
        height: "550px",
        title: "Verificacion Tablas Finales OC",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();
   
    function onReadErrPO(e){
         $.ajax({
            type: "POST",
            url: baseURL + 'alertas/wms/errores/PO',
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }
     function onReadVerPO(e){
         $.ajax({
            type: "POST",
            url: baseURL + 'alertas/wms/PO/verificar',
            dataType: 'json',
            data:{ pos: JSON.stringify(pos)},
            success: function(result){
                if(result.length != 0){
                    e.success(result);
                    console.log(result.length);
                }else{
                    var popupverificarpo = $("#POPUP_Verificar_PO");
                    popupverificarpo.data("kendoWindow").close();
                    $("#warning-modal").text("La OC seleccionada no se encuentra en las tablas finales");
                    $("#modal-warning").modal('show');
                }
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }
    $("#toolbarPO").kendoToolBar({
        items: [
            { type: "button", text: "Reprocesar", icon: "k-icon k-i-reload" ,click: ActualizarPO},
            { type: "button", text: "Eliminar", icon: "k-icon k-i-delete" ,click: EliminarPO},
            { type: "button", text: "Verificar OC", icon: "k-icon k-i-check-circle" ,click: VerificarPO}
        ]
    });
    function VerificarPO(){
        var data = JSON.stringify(pos);
        if(Array.isArray(pos) && pos.length != 0){
            var popupverificarpo = $("#POPUP_Verificar_PO");
            popupverificarpo.data("kendoWindow").open();
            var grid = $("#gridVerPO");
            grid.data("kendoGrid").dataSource.read();
        }
        else{;
            var popupdetallepo = $("#POPUP_Detalle_PO");
            popupdetallepo.data("kendoWindow").close();
            $("#error-modal").text("Debe seleccionar al menos una Orden de Compra para verificar");
            $("#modal-danger").modal('show');
        }
    }
    function ActualizarPO(){
        var data = JSON.stringify(pos);
        if(Array.isArray(pos) && pos.length != 0){
            $.ajax({
                type: "POST",
                url: baseURL + 'alertas/wms/PO/actualizar',
                data:{ pos: data},
                dataType: 'json',
                success: function(result){
                    if(result == 0){                      
                        var popupdetallepo = $("#POPUP_Detalle_PO");
                        popupdetallepo.data("kendoWindow").close();
                        $("#success-modal").text("Orden de Compra Actualizada Correctamente");
                        $("#modal-success").modal('show');
                        pos = [];
                        actualizarAlertaPO();
                    }
                    else{
                        var popupdetallepo = $("#POPUP_Detalle_PO");
                        popupdetallepo.data("kendoWindow").close();
                        $("#error-modal").text("Error al actualizar Orden de Compra");
                        $("#modal-danger").modal('show');
                        pos = [];                    
                    }
                },
                error: function(xhr){
                    console.log('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
                }
            });
        }
        else{;
            var popupdetallepo = $("#POPUP_Detalle_PO");
            popupdetallepo.data("kendoWindow").close();
            $("#error-modal").text("Debe seleccionar al menos una Orden de Compra para actualizar");
            $("#modal-danger").modal('show');
        }
    }
    function EliminarPO(){
        if(Array.isArray(pos) && pos.length != 0){
            var data = JSON.stringify(pos);
            var ok = confirm("Esta seguro que desea eliminar estas Ordenes de Compra?");
            if(ok){
                $.ajax({
                    type: "POST",
                    url: baseURL + 'alertas/wms/PO/eliminar',
                    data:{ pos: data},
                    dataType: 'json',
                    success: function(result){
                        if(result == 0){                        
                            var popupdetallepo = $("#POPUP_Detalle_PO");
                            popupdetallepo.data("kendoWindow").close();
                            $("#success-modal").text("Orden de Compra Eliminada Correctamente");
                            $("#modal-success").modal('show');
                            actualizarAlertaPO();
                            pos = [];
                        }
                        else{
                            var popupdetallepo = $("#POPUP_Detalle_PO");
                            popupdetallepo.data("kendoWindow").close();
                            $("#error-modal").text("Error al eliminar Orden de Compra");
                            $("#modal-danger").modal('show');
                            pos = [];
                        }
                    },
                    error: function(xhr){
                        console.log('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
                    }
                });
            }
            else{
                var popupdetallepo = $("#POPUP_Detalle_PO");
                popupdetallepo.data("kendoWindow").close();
                $("#modal-info").modal('show');
            }
        }    
        else{
            var popupdetallepo = $("#POPUP_Detalle_PO");
            popupdetallepo.data("kendoWindow").close();
            $("#error-modal").text("Debe seleccionar al menos una Orden de Compra para eliminar");
            $("#modal-danger").modal('show');
        }   
    }

    // FUNCIONES ALERTA BRCD

    var dataSourceDetBRCD = new kendo.data.DataSource({
        transport: {
            read: onReadErrBRCD
        },
        schema: {
            model: {
                id: "VENDOR_BRCD",
                fields: {
                        VENDOR_BRCD: {type: "string"}, // number - string - date
                        SKU_BRCD: {type: "string"},
                        CREATE_DATE_TIME: {type: "string"}, // number - string - date
                        MSG: {type: "string"}
                    }
            }
        },
        pageSize: 15
    });
    function intermiteciaBRCD(){
      $("#BRCDBox").toggleClass("bg-green");
      $("#BRCDBox").toggleClass("bg-red");
      $("#iconBRCD").toggleClass("glyphicon-ok");
      $("#iconBRCD").toggleClass("ion-android-alert");
      if(stopedBRCD == 0){
         runningBRCD = 1;
         setTimeout(intermiteciaBRCD, 500);
      }
      else{
        stopBRCD();
      }
    }
	function actualizarAlertaBRCD(){
        $.ajax({
            beforeSend: function () {
                $("#iconBRCD").toggleClass("fa");
                $("#iconBRCD").toggleClass("fa-refresh");
                $("#iconBRCD").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconBRCD").toggleClass("fa");
                $("#iconBRCD").toggleClass("fa-refresh");
                $("#iconBRCD").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/wms/errores/cantBRCD',
            dataType: 'json',
            success: function(result){
                if(result > 0){
                    if(runningBRCD == 0){
                        stopedBRCD = 0;
                        intermiteciaBRCD();
                        
                    }
                    setTimeout(actualizarAlertaBRCD, 600000);
                }else{
                    setTimeout(actualizarAlertaBRCD, 600000);
                    if(stopedBRCD == 0 && runningBRCD == 1){
                        runningBRCD = 0;
                        stopedBRCD = 1
                    }
                }

            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
        $.ajax({
            beforeSend: function () {
                $("#iconBRCD").toggleClass("fa");
                $("#iconBRCD").toggleClass("fa-refresh");
                $("#iconBRCD").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconBRCD").toggleClass("fa");
                $("#iconBRCD").toggleClass("fa-refresh");
                $("#iconBRCD").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/wms/brcd/totBRCD',
            dataType: 'json',
            success: function(result){
                result.forEach(function(element){
                    $("#nBRCD").html(element.TOT);
                });
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    function stopBRCD(){
        $("#BRCDBox").removeClass("bg-green");
        $("#BRCDBox").removeClass("bg-red");
        $("#iconBRCD").removeClass("glyphicon-ok");
        $("#iconBRCD").removeClass("ion-android-alert"); 
        $("#BRCDBox").addClass("bg-green");
        $("#iconBRCD").addClass("glyphicon-ok");
    }
    $("#gridDEtBRCD").kendoGrid({
        autoBind: false,
        dataSource: dataSourceDetBRCD,
        height: "90%", 
        width: "600px",
        sortable: true, 
        filterable: true,
        scrollable: true,
        change: function (e, args) {
                    brcds = [];
                    var rows = e.sender.select();
                    rows.each(function(e) {
                        var grid = $("#gridDEtBRCD").data("kendoGrid");
                        var item = grid.dataItem(this);
                        brcds.push({VENDOR_BRCD: item.VENDOR_BRCD, SKU_BRCD: item.SKU_BRCD});
                    }) 
        },
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {selectable: true, width: "15px" },
            {field: "VENDOR_BRCD",title: "CODIGO BARRA VENDOR",width: 70, filterable:false, resizable:false, height: 80},
            {field: "SKU_BRCD",title: "CODIGO BARRA SKU",width:70,filterable:false},
            {field: "CREATE_DATE_TIME",title: "FECHA CREACION",width:70,filterable: false},
            {field: "MSG",title: "MENSAJE",width: 70,filterable: false}
        ]
    });
    $("#BRCDDetalles").click(function(){
        actualizarAlertaBRCD();
        var popupdetallebrcd = $("#POPUP_Detalle_BRCD");
        popupdetallebrcd.data("kendoWindow").open();
        var grid = $("#gridDEtBRCD");
        grid.data("kendoGrid").dataSource.read();
    });
    var ventana_detalle_po = $("#POPUP_Detalle_BRCD");
    ventana_detalle_po.kendoWindow({
        width: "750px",
        height: "550px",
        title: "Detalle Errores BRCD",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();
    function onReadErrBRCD(e){
        $.ajax({
            type: "POST",
            url: baseURL + 'alertas/wms/errores/BRCD',
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }
    $("#toolbarBRCD").kendoToolBar({
        items: [
            { type: "button", text: "Reprocesar", icon: "k-icon k-i-reload" ,click: ActualizarBRCD},
            { type: "button", text: "Eliminar", icon: "k-icon k-i-delete" ,click: EliminarBRCD}
        ]
    });
    function ActualizarBRCD(){
        var data = JSON.stringify(brcds);
        if(Array.isArray(brcds) && brcds.length != 0){
            $.ajax({
                type: "POST",
                url: baseURL + 'alertas/wms/brcd/actualizar',
                data:{ brcds: data},
                dataType: 'json',
                success: function(result){
                    if(result == 0){                      
                        var popupdetallebrcd = $("#POPUP_Detalle_BRCD");
                        popupdetallebrcd.data("kendoWindow").close();
                        $("#success-modal").text("XREF Actualizado Correctamente");
                        $("#modal-success").modal('show');
                        actualizarAlertaBRCD();
                        brcds = [];
                    }
                    else{
                        var popupdetallebrcd = $("#POPUP_Detalle_BRCD");
                        popupdetallebrcd.data("kendoWindow").close();
                        $("#error-modal").text("Error al actualizar XREF");
                        $("#modal-danger").modal('show');
                        brcds = [];
                    }
                },
                error: function(xhr){
                    console.log('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
                }
            });
        }
        else{;
            var popupdetallebrcd = $("#POPUP_Detalle_BRCD");
            popupdetallebrcd.data("kendoWindow").close();
            $("#error-modal").text("Debe seleccionar al menos un XREF para actualizar");
            $("#modal-danger").modal('show');
        }
    }
    function EliminarBRCD(){
        var data = JSON.stringify(brcds);
        if(Array.isArray(brcds) && brcds.length != 0){
            var ok = confirm("Esta seguro que desea eliminar estos XREF?");
            if(ok){
                $.ajax({
                    type: "POST",
                    url: baseURL + 'alertas/wms/brcd/eliminar',
                    data:{ brcds: data},
                    dataType: 'json',
                    success: function(result){
                        if(result == 0){                      
                            var popupdetallebrcd = $("#POPUP_Detalle_BRCD");
                            popupdetallebrcd.data("kendoWindow").close();
                            $("#success-modal").text("XREF eliminado Correctamente");
                            $("#modal-success").modal('show');
                            actualizarAlertaBRCD();
                            brcds = [];
                        }
                        else{
                            var popupdetallebrcd = $("#POPUP_Detalle_BRCD");
                            popupdetallebrcd.data("kendoWindow").close();
                            $("#error-modal").text("Error al eliminar XREF");
                            $("#modal-danger").modal('show');
                            brcds = [];
                        }
                    },
                    error: function(xhr){
                        console.log('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
                    }
                });
            }
            else{
                var popupdetallebrcd = $("#POPUP_Detalle_BRCD");
                popupdetallebrcd.data("kendoWindow").close();
                $("#modal-info").modal('show');
            }
        }
        else{
            var popupdetallebrcd = $("#POPUP_Detalle_BRCD");
            popupdetallebrcd.data("kendoWindow").close();
            $("#error-modal").text("Debe seleccionar al menos un XREF para eliminar");
            $("#modal-danger").modal('show');
        }        
    }

    // FUNCIONES ALERTA ARTICULO

    var dataSourceDetART = new kendo.data.DataSource({
        transport: {
            read: onReadErrART
        },
        schema: {
            model: {
                id: "SKU_ID",
                fields: {
                        SKU_ID: {type: "string"}, // number - string - date
                        MSG_WAREHOUSE: {type: "string"},
                        MSG_HEADER: {type: "string"}, // number - string - date
                        CREATE_DATE_TIME: {type: "string"}
                    }
            }
        },
        pageSize: 15
    });
    $("#gridDEtART").kendoGrid({
        autoBind: false,
        dataSource: dataSourceDetART,
        height: "90%", 
        width: "600px",
        sortable: true, 
        filterable: true,
        scrollable: true,
        change: function (e, args) {
                    arts = [];
                    var rows = e.sender.select();
                    rows.each(function(e) {
                        var grid = $("#gridDEtART").data("kendoGrid");
                        var item = grid.dataItem(this);
                        arts.push({SKU_ID: item.SKU_ID});
                    })
        },
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {selectable: true, width: "15px" },
            {field: "SKU_ID",title: "SKU",width: 35, filterable:false, resizable:false, height: 80},
            {field: "MSG_WAREHOUSE",title: "MENSAJE WAREHOUSE",width:150,filterable:false},
            {field: "MSG_HEADER",title: "MENSAJE HEADER",width:90,filterable: false},
            {field: "CREATE_DATE_TIME",title: "FECHA CREACION",width: 40,filterable: false}
        ]
    });
    $("#ARTDetalles").click(function(){
        actualizarAlertaART();
        var popupdetalleart = $("#POPUP_Detalle_ART");
        popupdetalleart.data("kendoWindow").open();
        var grid = $("#gridDEtART");
        grid.data("kendoGrid").dataSource.read();
    });
    var ventana_detalle_po = $("#POPUP_Detalle_ART");
    ventana_detalle_po.kendoWindow({
        width: "750px",
        height: "550px",
        title: "Detalle Errores Articulos",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();
    function intermiteciaART(){
      $("#ARTBox").toggleClass("bg-green");
      $("#ARTBox").toggleClass("bg-red");
      $("#iconART").toggleClass("glyphicon-ok");
      $("#iconART").toggleClass("ion-android-alert");
      if(stopedART == 0){
         runningART = 1;
         setTimeout(intermiteciaART, 500);
      }
      else{
        stopART();
      }
    }
    function actualizarAlertaART(){
        $.ajax({
            beforeSend: function () {
                $("#iconART").toggleClass("fa");
                $("#iconART").toggleClass("fa-refresh");
                $("#iconART").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconART").toggleClass("fa");
                $("#iconART").toggleClass("fa-refresh");
                $("#iconART").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/wms/errores/cantART',
            dataType: 'json',
            success: function(result){
                if(result > 0){
                    if(runningART == 0){
                        stopedART = 0;
                        intermiteciaART();
                        
                    }
                    setTimeout(actualizarAlertaART, 600000);
                }else{
                    setTimeout(actualizarAlertaART, 600000);
                    if(stopedART == 0 && runningART == 1){
                        runningART = 0;
                        stopedART = 1
                    }
                }

            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
        $.ajax({
            beforeSend: function () {
                $("#iconART").toggleClass("fa");
                $("#iconART").toggleClass("fa-refresh");
                $("#iconART").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconART").toggleClass("fa");
                $("#iconART").toggleClass("fa-refresh");
                $("#iconART").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/wms/errores/cantART',
            dataType: 'json',
            success: function(result){
                $("#nART").html(result);
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    function stopART(){
        $("#ARTBox").removeClass("bg-green");
        $("#ARTBox").removeClass("bg-red");
        $("#iconART").removeClass("glyphicon-ok");
        $("#iconART").removeClass("ion-android-alert"); 
        $("#ARTBox").addClass("bg-green");
        $("#iconART").addClass("glyphicon-ok");
    }
    function onReadErrART(e){
        $.ajax({
            type: "POST",
            url: baseURL + 'alertas/wms/errores/ART',
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }
    $("#toolbarART").kendoToolBar({
        items: [
            { type: "button", text: "Reprocesar", icon: "k-icon k-i-reload" ,click: ActualizarART},
            { type: "button", text: "Eliminar", icon: "k-icon k-i-delete" ,click: EliminarART}
        ]
    });
    function ActualizarART(){
        var data = JSON.stringify(arts);
        if(Array.isArray(arts) && arts.length != 0){
            $.ajax({
                type: "POST",
                url: baseURL + 'alertas/wms/art/actualizar',
                data:{ arts: data},
                dataType: 'json',
                success: function(result){
                    if(result == 0){                        
                        var popupdetalleart = $("#POPUP_Detalle_ART");
                        popupdetalleart.data("kendoWindow").close();
                        $("#success-modal").text("Articulo Actualizado Correctamente");
                        $("#modal-success").modal('show');
                        actualizarAlertaART();
                        arts = [];
                    }
                    else{
                        var popupdetalleart = $("#POPUP_Detalle_ART");
                        popupdetalleart.data("kendoWindow").close();
                        $("#error-modal").text("Error al actualizar Articulo");
                        $("#modal-danger").modal('show');
                        arts = [];
                    }
                },
                error: function(xhr){
                    console.log('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
                }
            });
        }
        else{;
            var popupdetalleart = $("#POPUP_Detalle_ART");
            popupdetalleart.data("kendoWindow").close();
            $("#error-modal").text("Debe seleccionar al menos un Articulo para actualizar");
            $("#modal-danger").modal('show');
        }
    }
    function EliminarART(){       
        var data = JSON.stringify(arts);
        if(Array.isArray(arts) && arts.length != 0){
            var ok = confirm("Esta seguro que desea eliminar estos Articulos?");
            if(ok){
                $.ajax({
                    type: "POST",
                    url: baseURL + 'alertas/wms/art/eliminar',
                    data:{ arts: data},
                    dataType: 'json',
                    success: function(result){
                        if(result == 0){                        
                            var popupdetalleart = $("#POPUP_Detalle_ART");
                            popupdetalleart.data("kendoWindow").close();
                            $("#success-modal").text("Articulo Eliminado Correctamente");
                            $("#modal-success").modal('show');
                            actualizarAlertaART();
                            arts = [];
                        }
                        else{
                            var popupdetallebrcd = $("#POPUP_Detalle_BRCD");
                            popupdetallebrcd.data("kendoWindow").close();
                            $("#error-modal").text("Error al eliminar Articulo");
                            $("#modal-danger").modal('show');
                            arts = [];
                        }
                    },
                    error: function(xhr){
                        console.log('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
                    }
                });
                }
                else{
                    var popupdetallebrcd = $("#POPUP_Detalle_BRCD");
                    popupdetallebrcd.data("kendoWindow").close();
                    $("#modal-info").modal('show');
                }
        }
        else{
            var popupdetalleart = $("#POPUP_Detalle_ART");
            popupdetalleart.data("kendoWindow").close();
            $("#error-modal").text("Debe seleccionar al menos un Articulo para eliminar");
            $("#modal-danger").modal('show');
        }       
    }

    // FUNCIONALIDADES ALERTA OLAS

    var dataSourceResOLA = new kendo.data.DataSource({
        transport: {
            read: onReadResOLA
        },
        schema: {
            model: {
                id: "NUMERO_OLA",
                fields: {
                        NUMERO_OLA: {type: "string"}, // number - string - date
                        DESC_OLA: {type: "string"},
                        ESTADO: {type: "string"}, // number - string - date
                        DESC_ESTADO: {type: "string"},
                        INICIO: {type: "string"},
                        TERMINO: {type: "string"},
                        DIFERENCIA_HHMMSS: {type: "string"}
                    }
            }
        },
        pageSize: 15
    });
     var dataSourceDetOLA = new kendo.data.DataSource({
        transport: {
            read: onReadDetOLA
        },
        schema: {
            model: {
                id: "NUMERO_OLA",
                fields: {
                        NUMERO_OLA: {type: "string"}, // number - string - date
                        DESC_OLA: {type: "string"},
                        ESTADO: {type: "string"}, // number - string - date
                        DESC_ESTADO: {type: "string"},
                        INICIO: {type: "string"},
                        TERMINO: {type: "string"},
                        DIFERENCIA_HHMMSS: {type: "string"}
                    }
            }
        },
        pageSize: 15
    });
    $("#gridResOLA").kendoGrid({
        autoBind: false,
        dataSource: dataSourceResOLA,
        height: "100%", 
        width: "600px",
        sortable: true, 
        filterable: true,
        scrollable: true,
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {field: "NUMERO_OLA",title: "NUMERO OLA",width: 90, filterable:false, resizable:false, height: 80},
            {field: "DESC_OLA",title: "DESC OLA",width:70,filterable:false},
            {field: "ESTADO",title: "COD ESTADO",width:70,filterable: false},
            {field: "DESC_ESTADO",title: "DESC ESTADO",width:70,filterable: false},
            {field: "INICIO",title: "INICIO",width:70,filterable: false},
            {field: "TERMINO",title: "TERMINO",width:70,filterable: false},
            {field: "DIFERENCIA_HHMMSS",title: "DURACION",width:70,filterable: false},
        ]
    });
    $("#gridDetOLA").kendoGrid({
        autoBind: false,
        dataSource: dataSourceDetOLA,
        height: "90%", 
        width: "600px",
        sortable: true, 
        filterable: true,
        scrollable: true,
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {field: "NUMERO_OLA",title: "NUMERO OLA",width: 90, filterable:false, resizable:false, height: 80},
            {field: "DESC_OLA",title: "DESC OLA",width:70,filterable:false},
            {field: "ESTADO",title: "COD ESTADO",width:70,filterable: false},
            {field: "DESC_ESTADO",title: "DESC ESTADO",width:70,filterable: false},
            {field: "INICIO",title: "INICIO",width:70,filterable: false},
            {field: "TERMINO",title: "TERMINO",width:70,filterable: false},
            {field: "DIFERENCIA_HHMMSS",title: "DURACION",width:70,filterable: false},
        ]
    });
    function onReadResOLA(e){
        $.ajax({
            type: "POST",
            url: baseURL + 'alertas/wms/ola/resumen',
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    function onReadDetOLA(e){
        $.ajax({
            type: "POST",
            url: baseURL + 'alertas/wms/errores/OLA',
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    $("#OLASEjecutadas").click(function(){
        var popupresumenola = $("#POPUP_Resumen_OLA");
        popupresumenola.data("kendoWindow").open();
        var grid = $("#gridResOLA");
        grid.data("kendoGrid").dataSource.read();
    });
    $("#OLASEjecutadas").hover(function(){
        $("#iconOLA").toggleClass("ion-clipboard");
    });
    var ventana_resumen_ola = $("#POPUP_Resumen_OLA");
    ventana_resumen_ola.kendoWindow({
        width: "750px",
        height: "550px",
        title: "Resumen Olas Ejecutadas",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();
    $("#OLADetalles").click(function(){
        actualizarAlertaOLA();
        var popupdetalleola = $("#POPUP_Detalle_OLA");
        popupdetalleola.data("kendoWindow").open();
        var grid = $("#gridDetOLA");
        grid.data("kendoGrid").dataSource.read();
    });
    var ventana_detalle_ola = $("#POPUP_Detalle_OLA");
    ventana_detalle_ola.kendoWindow({
        width: "750px",
        height: "550px",
        title: "Detalle Error Olas",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();
    function intermitenciaOLA(){
      $("#OLABox").toggleClass("bg-green");
      $("#OLABox").toggleClass("bg-red");
      $("#iconOLA").toggleClass("glyphicon-ok");
      $("#iconOLA").toggleClass("ion-android-alert");
      if(stopedOLA == 0){
         runningOLA = 1;
         setTimeout(intermitenciaOLA, 500);
      }
      else{
        stopOLA();
      }
    }
    function actualizarAlertaOLA(){
        $.ajax({
            beforeSend: function () {
                $("#iconOLA").toggleClass("fa");
                $("#iconOLA").toggleClass("fa-refresh");
                $("#iconOLA").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconOLA").toggleClass("fa");
                $("#iconOLA").toggleClass("fa-refresh");
                $("#iconOLA").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/wms/errores/cantOLA',
            dataType: 'json',
            success: function(result){
                if(result > 0){
                    if(runningOLA == 0){
                        stopedOLA = 0;
                        intermitenciaOLA();
                        
                    }
                    setTimeout(actualizarAlertaOLA, 600000);
                }else{
                    setTimeout(actualizarAlertaOLA, 600000);
                    if(stopedOLA == 0 && runningOLA == 1){
                        runningOLA = 0;
                        stopedOLA = 1
                    }
                }

            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
        $.ajax({
            beforeSend: function () {
                $("#iconOLA").toggleClass("fa");
                $("#iconOLA").toggleClass("fa-refresh");
                $("#iconOLA").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconOLA").toggleClass("fa");
                $("#iconOLA").toggleClass("fa-refresh");
                $("#iconOLA").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/wms/ola/totOLA',
            dataType: 'json',
            success: function(result){
                result.forEach(function(element){
                    $("#nOLA").html(element.TOT);
                });
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    function stopOLA(){
        $("#OLABox").removeClass("bg-green");
        $("#OLABox").removeClass("bg-red");
        $("#iconOLA").removeClass("glyphicon-ok");
        $("#iconOLA").removeClass("ion-android-alert"); 
        $("#OLABox").addClass("bg-green");
        $("#iconOLA").addClass("glyphicon-ok");
    }

    // FUNCIONALIDADES ALERTA CITAS


    var dataSourceResCITA = new kendo.data.DataSource({
        transport: {
            read: onReadResCITA
        },
        schema: {
            model: {
                id: "STAT_CODE",
                fields: {
                        STAT_CODE: {type: "string"}, // number - string - date
                        SHORT_DESC: {type: "string"},
                        CANTIDAD_CITAS: {type: "string"}
                    }
            }
        },
        pageSize: 15
    });
    var dataSourceDetCITA = new kendo.data.DataSource({
        transport: {
            read: onReadDetCITA
        },
        schema: {
            model: {
                id: "APPT_NBR",
                fields: {
                        APPT_NBR: {type: "string"}, // number - string - date
                        SHPMT_NBR: {type: "string"},
                        CREATE_DATE_TIME: {type: "string"},
                        MSG: {type: "string"}
                    }
            }
        },
        pageSize: 15
    });
    var dataSourceCodCITA = new kendo.data.DataSource({
        transport: {
            read: onReadCodCITA
        },
        schema: {
            model: {
                id: "APPT_NBR",
                fields: {
                        APPT_NBR: {type: "string"}, // number - string - date
                        SHPMT_NBR: {type: "string"},
                        STAT_CODE: {type: "string"},
                        SHORT_DESC: {type: "string"},
                        CREATE_DATE_TIME: {type: "string"}
                    }
            }
        },
        pageSize: 15
    });
    function onReadResCITA(e){
        $.ajax({
            type: "POST",
            url: baseURL + 'alertas/wms/cita/resumen',
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    function onReadDetCITA(e){
        $.ajax({
            type: "POST",
            url: baseURL + 'alertas/wms/errores/CITA',
            data: {codigoCITA: codigoCITA},
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    function onReadCodCITA(e){
        $.ajax({
            type: "POST",
            url: baseURL + 'alertas/wms/cita/resumenCod',
            data: {codigoCITA: codigoCITA},
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    $("#gridCodCITA").kendoGrid({
        autoBind: false,
        dataSource: dataSourceCodCITA,
        height: "100%", 
        width: "600px",
        sortable: true, 
        filterable: true,
        scrollable: true,
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {field: "APPT_NBR",title: "NUMERO CITA",width: 90, filterable:false, resizable:false, height: 80},
            {field: "SHPMT_NBR",title: "ASN",width:70,filterable:false},
            {field: "STAT_CODE",title: "CODIGO ESTADO",width:70,filterable: false},
            {field: "SHORT_DESC",title: "DESC ESTADO",width:70,filterable: false},
            {field: "CREATE_DATE_TIME",title: "FECHA CREACION",width:70,filterable: false}
        ]
    });
    $("#gridDetCITA").kendoGrid({
        autoBind: false,
        dataSource: dataSourceDetCITA,
        height: "90%", 
        width: "600px",
        sortable: true, 
        filterable: true,
        scrollable: true,
        change: function (e, args) {
                    citas = [];
                    var rows = e.sender.select();
                    rows.each(function(e) {
                        var grid = $("#gridDetCITA").data("kendoGrid");
                        var item = grid.dataItem(this);
                        citas.push({APPT_NBR: item.APPT_NBR});
                    }) 
        },
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {selectable: true, width: "15px" },
            {field: "APPT_NBR",title: "NUMERO CITA",width: 90, filterable:false, resizable:false, height: 80},
            {field: "SHPMT_NBR",title: "ASN",width:70,filterable:false},
            {field: "CREATE_DATE_TIME",title: "FECHA CREACION",width:70,filterable: false},
            {field: "MSG",title: "MENSAJE",width:80,filterable: false}
        ]
    });
    $("#gridResCITA").kendoGrid({
        autoBind: false,
        dataSource: dataSourceResCITA,
        height: "100%", 
        width: "600px",
        selectable: "row",
        sortable: true, 
        filterable: true,
        scrollable: true,
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {field: "STAT_CODE",title: "CODIGO ESTADO",width: 90, filterable:false, resizable:false, height: 80},
            {field: "SHORT_DESC",title: "DESC ESTADO",width:70,filterable:false},
            {field: "CANTIDAD_CITAS",title: "CANTIDAD CITAS",width:70,filterable: false}
        ]
    }).on("click", "tbody td", function(e) {
        var cell = $(e.currentTarget);
        var cellIndex = cell[0].cellIndex;
        var grid = $("#gridResCITA").data("kendoGrid");
        var column = grid.columns[0];
        var dataItem = grid.dataItem(cell.closest("tr"));
        codigoCITA = dataItem[column.field];
        var popupresumencodcita = $("#POPUP_Resumen_codCITA");
        popupresumencodcita.data("kendoWindow").open();
        var grid = $("#gridCodCITA");
        grid.data("kendoGrid").dataSource.read();
    });

    $("#CITASBajadas").click(function(){
        var popupresumencita = $("#POPUP_Resumen_CITA");
        popupresumencita.data("kendoWindow").open();
        var grid = $("#gridResCITA");
        grid.data("kendoGrid").dataSource.read();
    });
    $("#CITASBajadas").hover(function(){
        $("#iconCITA").toggleClass("ion-clipboard");
    });
    var ventana_resumen_cita = $("#POPUP_Resumen_CITA");
    ventana_resumen_cita.kendoWindow({
        width: "750px",
        height: "550px",
        title: "Resumen Citas Bajadas",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();

    $("#CITADetalles").click(function(){
        actualizarAlertaCITA();
        var popupdetallecita = $("#POPUP_Detalle_CITA");
        popupdetallecita.data("kendoWindow").open();
        var grid = $("#gridDetCITA");
        grid.data("kendoGrid").dataSource.read();
    });
    var ventana_detalle_cita = $("#POPUP_Detalle_CITA");
    ventana_detalle_cita.kendoWindow({
        width: "750px",
        height: "550px",
        title: "Detalle Errores Cita",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();

    var ventana_resumen_codCita = $("#POPUP_Resumen_codCITA");
    ventana_resumen_codCita.kendoWindow({
        width: "750px",
        height: "550px",
        title: "Resumen Codigo Estado Cita",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();
    function intermitenciaCITA(){
      $("#CITABox").toggleClass("bg-green");
      $("#CITABox").toggleClass("bg-red");
      $("#iconCITA").toggleClass("glyphicon-ok");
      $("#iconCITA").toggleClass("ion-android-alert");
      if(stopedCITA == 0){
         runningCITA = 1;
         setTimeout(intermitenciaCITA, 500);
      }
      else{
        stopCITA();
      }
    }
    function actualizarAlertaCITA(){
        $.ajax({
            beforeSend: function () {
                $("#iconCITA").toggleClass("fa");
                $("#iconCITA").toggleClass("fa-refresh");
                $("#iconCITA").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconCITA").toggleClass("fa");
                $("#iconCITA").toggleClass("fa-refresh");
                $("#iconCITA").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/wms/errores/cantCITA',
            dataType: 'json',
            success: function(result){
                if(result > 0){
                    if(runningCITA == 0){
                        stopedCITA = 0;
                        intermitenciaCITA();
                        
                    }
                    setTimeout(actualizarAlertaCITA, 600000);
                }else{
                    setTimeout(actualizarAlertaCITA, 600000);
                    if(stopedCITA == 0 && runningCITA == 1){
                        runningCITA = 0;
                        stopedCITA = 1
                    }
                }

            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
        $.ajax({
            beforeSend: function () {
                $("#iconCITA").toggleClass("fa");
                $("#iconCITA").toggleClass("fa-refresh");
                $("#iconCITA").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconCITA").toggleClass("fa");
                $("#iconCITA").toggleClass("fa-refresh");
                $("#iconCITA").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/wms/cita/totCITA',
            dataType: 'json',
            success: function(result){
                result.forEach(function(element){
                    $("#nCITA").html(element.TOT);  
                });
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    function stopCITA(){
        $("#CITABox").removeClass("bg-green");
        $("#CITABox").removeClass("bg-red");
        $("#iconCITA").removeClass("glyphicon-ok");
        $("#iconCITA").removeClass("ion-android-alert"); 
        $("#CITABox").addClass("bg-green");
        $("#iconCITA").addClass("glyphicon-ok");
    }
    $("#toolbarCITA").kendoToolBar({
        items: [
            { type: "button", text: "Reprocesar", icon: "k-icon k-i-reload" ,click: ActualizarCITA},
            { type: "button", text: "Eliminar", icon: "k-icon k-i-delete" ,click: EliminarCITA}
        ]
    });
    function ActualizarCITA(){
        var data = JSON.stringify(citas);
        if(Array.isArray(citas) && citas.length != 0){
            $.ajax({
                type: "POST",
                url: baseURL + 'alertas/wms/cita/actualizar',
                data:{ citas: data},
                dataType: 'json',
                success: function(result){
                    if(result == 0){                       
                        var popupdetallecita = $("#POPUP_Detalle_CITA");
                        popupdetallecita.data("kendoWindow").close();
                        $("#success-modal").text("Cita Actualizada Correctamente");
                        $("#modal-success").modal('show');
                        actualizarAlertaCITA();
                        citas = [];
                    }
                    else{
                        var popupdetallecita = $("#POPUP_Detalle_CITA");
                        popupdetallecita.data("kendoWindow").close();
                        $("#error-modal").text("Error al actualizar Cita");
                        $("#modal-danger").modal('show');
                        citas =[];
                    }
                },
                error: function(xhr){
                    console.log('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
                }
            });
        }
        else{;
            var popupdetallecita = $("#POPUP_Detalle_CITA");
            popupdetallecita.data("kendoWindow").close();
            $("#error-modal").text("Debe seleccionar al menos una Cita para actualizar");
            $("#modal-danger").modal('show');
        }
    }
    function EliminarCITA(){
        if(Array.isArray(citas) && citas.length != 0){
            var data = JSON.stringify(citas);
            var ok = confirm("Esta seguro que desea eliminar estas Citas?");
            if(ok){
                $.ajax({
                    type: "POST",
                    url: baseURL + 'alertas/wms/cita/eliminar',
                    data:{ citas: data},
                    dataType: 'json',
                    success: function(result){
                        if(result == 0){
                            var popupdetallecita = $("#POPUP_Detalle_CITA");
                            popupdetallecita.data("kendoWindow").close();
                            $("#success-modal").text("Citas Eliminadas Correctamente");
                            $("#modal-success").modal('show');
                            actualizarAlertaCITA();
                            citas =[];
                        }
                        else{
                            var popupdetallecita = $("#POPUP_Detalle_CITA");
                            popupdetallecita.data("kendoWindow").close();
                            $("#error-modal").text("Error al eliminar Citas");
                            $("#modal-danger").modal('show');
                            citas =[];
                        }
                    },
                    error: function(xhr){
                        console.log('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
                    }
                });
            }
            else{
                var popupdetallecita = $("#POPUP_Detalle_CITA");
                popupdetallecita.data("kendoWindow").close();
                $("#modal-info").modal('show');
            }
        }    
        else{
            console.log(citas);
            var popupdetallecita = $("#POPUP_Detalle_CITA");
            popupdetallecita.data("kendoWindow").close();
            $("#error-modal").text("Debe seleccionar al menos una cita para eliminar");
            $("#modal-danger").modal('show');
        }  
    }

    //FUNCIONALIDADES ALERTA ASN


    var dataSourceResASN = new kendo.data.DataSource({
        transport: {
            read: onReadResASN
        },
        schema: {
            model: {
                id: "STAT_CODE",
                fields: {
                        STAT_CODE: {type: "string"},
                        CODE_DESC: {type: "string"}, // number - string - date
                        ASNS: {type: "string"}
                    }
            }
        },
        pageSize: 15
    });
    var dataSourceResCodASN = new kendo.data.DataSource({
        transport: {
            read: onReadResCodASN
        },
        schema: {
            model: {
                id: "SHPMT_NBR",
                fields: {
                        SHPMT_NBR: {type: "string"}, // number - string - date
                        REF_FIELD_1: {type: "string"},
                        STAT_CODE: {type: "string"},
                        CODE_DESC: {type: "string"}
                    }
            }
        },
        pageSize: 15
    });
    var dataSourceDetASN = new kendo.data.DataSource({
        transport: {
            read: onReadDetASN
        },
        schema: {
            model: {
                id: "SHPMT_NBR",
                fields: {
                        SHPMT_NBR: {type: "string"},
                        REF_FIELD_1: {type: "string"}, // number - string - date
                        PO_NBR: {type: "string"},
                        SIZE_DESC: {type: "string"},
                        MSG_SHPMT: {type: "string"},  
                        MSG_SKU: {type: "string"}
                    }
            }
        },
        pageSize: 15
    });
    function intermitenciaASN(){
      $("#ASNBox").toggleClass("bg-green");
      $("#ASNBox").toggleClass("bg-red");
      $("#iconASN").toggleClass("glyphicon-ok");
      $("#iconASN").toggleClass("ion-android-alert");
      if(stopedASN == 0){
         runningASN = 1;
         setTimeout(intermitenciaASN, 500);
      }
      else{
        stopASN();
      }
    }
    function actualizarAlertaASN(){
        $.ajax({
            beforeSend: function () {
                $("#iconASN").toggleClass("fa");
                $("#iconASN").toggleClass("fa-refresh");
                $("#iconASN").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconASN").toggleClass("fa");
                $("#iconASN").toggleClass("fa-refresh");
                $("#iconASN").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/wms/errores/cantASN',
            dataType: 'json',
            success: function(result){
                if(result > 0){
                    if(runningASN == 0){
                        stopedASN = 0;
                        intermitenciaASN();
                        
                    }
                    setTimeout(actualizarAlertaASN, 600000);
                }else{
                    setTimeout(actualizarAlertaASN, 600000);
                    if(stopedASN == 0 && runningASN == 1){
                        runningASN = 0;
                        stopedASN = 1
                    }
                }

            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
        $.ajax({
            beforeSend: function () {
                $("#iconASN").toggleClass("fa");
                $("#iconASN").toggleClass("fa-refresh");
                $("#iconASN").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconASN").toggleClass("fa");
                $("#iconASN").toggleClass("fa-refresh");
                $("#iconASN").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/wms/asn/totASN',
            dataType: 'json',
            success: function(result){
                result.forEach(function(element){
                    $("#nASN").html(element.TOT);  
                });
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    function stopASN(){
        $("#ASNBox").removeClass("bg-green");
        $("#ASNBox").removeClass("bg-red");
        $("#iconASN").removeClass("glyphicon-ok");
        $("#iconASN").removeClass("ion-android-alert"); 
        $("#ASNBox").addClass("bg-green");
        $("#iconASN").addClass("glyphicon-ok");
    }
    $("#gridResASN").kendoGrid({
        autoBind: false,
        dataSource: dataSourceResASN,
        height: "100%", 
        width: "600px",
        selectable: "row",
        sortable: true, 
        filterable: true,
        scrollable: true,
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {field: "STAT_CODE",title: "CODIGO ESTADO",width: 90, filterable:false, resizable:false, height: 80},
            {field: "CODE_DESC",title: "DESCRIPCION",width:70,filterable:false},
            {field: "ASNS",title: "CANTIDAD",width:70,filterable:false}
        ]
    }).on("click", "tbody td", function(e) {
        var cell = $(e.currentTarget);
        var cellIndex = cell[0].cellIndex;
        var grid = $("#gridResASN").data("kendoGrid");
        var column = grid.columns[0];
        var dataItem = grid.dataItem(cell.closest("tr"));
        codigoASN = dataItem[column.field];
        var popupresumencodasn = $("#POPUP_Resumen_codASN");
        popupresumencodasn.data("kendoWindow").open();
        var grid = $("#gridRescodASN");
        grid.data("kendoGrid").dataSource.read();
    });
    $("#gridRescodASN").kendoGrid({
        autoBind: false,
        dataSource: dataSourceResCodASN,
        height: "100%", 
        width: "600px",
        selectable: "row",
        sortable: true, 
        filterable: true,
        scrollable: true,
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {field: "SHPMT_NBR",title: "ASN",width: 90, filterable:false, resizable:false, height: 80},
            {field: "REF_FIELD_1",title: "NRO CITA",width:70,filterable:false},
            {field: "STAT_CODE",title: "CODIGO ESTADO",width:70,filterable:false},
            {field: "CODE_DESC",title: "DESC ESTADO",width:70,filterable:false}
        ]
    });
    $("#gridDetASN").kendoGrid({
        autoBind: false,
        dataSource: dataSourceDetASN,
        height: "90%", 
        width: "600px",
        sortable: true, 
        filterable: true,
        scrollable: true,
        change: function (e, args) {
                    asns = [];
                    var rows = e.sender.select();
                    rows.each(function(e) {
                        var grid = $("#gridDetASN").data("kendoGrid");
                        var item = grid.dataItem(this);
                        asns.push({SHPMT_NBR: item.SHPMT_NBR});
                    }) 
        },
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {selectable: true, width: "15px" },
            {field: "SHPMT_NBR",title: "ASN",width: 60, filterable:false, resizable:false, height: 80},
            {field: "REF_FIELD_1",title: "CITA",width:60,filterable:false},
            {field: "PO_NBR",title: "OC",width:60,filterable:false},
            {field: "SIZE_DESC",title: "SKU",width:60,filterable: false},
            {field: "MSG_SHPMT",title: "MENSAJE CABECERA",width:90,filterable: false},
            {field: "MSG_SKU",title: "MENSAJE DETALLE",width:90,filterable: false}
        ]
    });
    $("#ASNBajados").click(function(){
        var popupresumenasn = $("#POPUP_Resumen_ASN");
        popupresumenasn.data("kendoWindow").open();
        var grid = $("#gridResASN");
        grid.data("kendoGrid").dataSource.read();
    });
    $("#ASNBajados").hover(function(){
        $("#iconASN").toggleClass("ion-clipboard");
    });
    var ventana_resumen_asn = $("#POPUP_Resumen_ASN");
    ventana_resumen_asn.kendoWindow({
        width: "750px",
        height: "550px",
        title: "Resumen ASN Bajados",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();
    var ventana_resumen_CODasn = $("#POPUP_Resumen_codASN");
    ventana_resumen_CODasn.kendoWindow({
        width: "750px",
        height: "550px",
        title: "Resumen Codigo Estado ASN",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();
    $("#ASNDetalles").click(function(){
        actualizarAlertaASN();
        var popupdetalleasn = $("#POPUP_Detalle_ASN");
        popupdetalleasn.data("kendoWindow").open();
        var grid = $("#gridDetASN");
        grid.data("kendoGrid").dataSource.read();
    });
    var ventana_resumen_CODasn = $("#POPUP_Detalle_ASN");
    ventana_resumen_CODasn.kendoWindow({
        width: "750px",
        height: "550px",
        title: "Detalle Error ASN",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();
    function onReadResASN(e){
        $.ajax({
            type: "POST",
            url: baseURL + 'alertas/wms/asn/resumen',
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    function onReadResCodASN(e){
        $.ajax({
            type: "POST",
            url: baseURL + 'alertas/wms/asn/resumencod',
            data: {codigoASN: codigoASN},
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    function onReadDetASN(e){
        $.ajax({
            type: "POST",
            url: baseURL + 'alertas/wms/errores/ASN',
            data: {codigoASN: codigoASN},
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    $("#toolbarASN").kendoToolBar({
        items: [
            { type: "button", text: "Reprocesar", icon: "k-icon k-i-reload" ,click: ActualizarASN},
            { type: "button", text: "Eliminar", icon: "k-icon k-i-delete" ,click: EliminarASN}
        ]
    });
    function ActualizarASN(){
        var data = JSON.stringify(asns);
        if(Array.isArray(asns) && asns.length != 0){
            $.ajax({
                type: "POST",
                url: baseURL + 'alertas/wms/asn/actualizar',
                data:{ asns: data},
                dataType: 'json',
                success: function(result){
                    if(result == 0){                       
                        var popupdetalleasn = $("#POPUP_Detalle_ASN");
                        popupdetalleasn.data("kendoWindow").close();
                        $("#success-modal").text("ASN Actualizada Correctamente");
                        $("#modal-success").modal('show');
                        actualizarAlertaASN();
                        asns = [];
                    }
                    else{
                        var popupdetalleasn = $("#POPUP_Detalle_ASN");
                        popupdetalleasn.data("kendoWindow").close();
                        $("#error-modal").text("Error al actualizar ASN");
                        $("#modal-danger").modal('show');
                        asns = [];
                    }
                },
                error: function(xhr){
                    console.log('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
                }
            });
        }
        else{;
            var popupdetalleasn = $("#POPUP_Detalle_ASN");
            popupdetalleasn.data("kendoWindow").close();
            $("#error-modal").text("Debe seleccionar al menos un ASN para actualizar");
            $("#modal-danger").modal('show');
        }
    }
    function EliminarASN(){
        if(Array.isArray(asns) && asns.length != 0){
            var data = JSON.stringify(asns);
            var ok = confirm("Esta seguro que desea eliminar estos ASN?");
            if(ok){
                $.ajax({
                    type: "POST",
                    url: baseURL + 'alertas/wms/asn/eliminar',
                    data:{ asns: data},
                    dataType: 'json',
                    success: function(result){
                        if(result == 0){
                            var popupdetalleasn = $("#POPUP_Detalle_ASN");
                            popupdetalleasn.data("kendoWindow").close();
                            $("#success-modal").text("ASN Eliminadas Correctamente");
                            $("#modal-success").modal('show');
                            actualizarAlertaASN();
                            asns = [];
                        }
                        else{
                            var popupdetalleasn = $("#POPUP_Detalle_ASN");
                            popupdetalleasn.data("kendoWindow").close();
                            $("#error-modal").text("Error al eliminar ASN");
                            $("#modal-danger").modal('show');
                            asns = [];
                        }
                    },
                    error: function(xhr){
                        console.log('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
                    }
                });
            }
            else{
                var popupdetalleasn = $("#POPUP_Detalle_ASN");
                popupdetalleasn.data("kendoWindow").close();
                $("#modal-info").modal('show');
            }
        }    
        else{
            console.log(asns);
            var popupdetalleasn = $("#POPUP_Detalle_ASN");
            popupdetalleasn.data("kendoWindow").close();
            $("#error-modal").text("Debe seleccionar al menos un ASN para eliminar");
            $("#modal-danger").modal('show');
        }  
    }

    //FUNCIONALIDADES ALERTA LPN

    var dataSourceResLPN = new kendo.data.DataSource({
        transport: {
            read: onReadResLPN
        },
        schema: {
            model: {
                id: "STAT_CODE",
                fields: {
                        STAT_CODE: {type: "string"}, // number - string - date
                        CODE_DESC: {type: "string"},
                        CANTDAD_LPN: {type: "string"}
                    }
            }
        },
        pageSize: 15
    });
    var dataSourceDetLPN = new kendo.data.DataSource({
        transport: {
            read: onReadDetLPN
        },
        schema: {
            model: {
                id: "CASE_NBR",
                fields: {
                        CASE_NBR: {type: "string"},
                        ORIG_SHPMT_NBR: {type: "string"},
                        SIZE_DESC: {type: "string"}, // number - string - date
                        MSG_LPN: {type: "string"},
                        MSG_SKU: {type: "string"}
                    }
            }
        },
        pageSize: 15
    });
    function intermitenciaLPN(){
      $("#LPNBox").toggleClass("bg-green");
      $("#LPNBox").toggleClass("bg-red");
      $("#iconLPN").toggleClass("glyphicon-ok");
      $("#iconLPN").toggleClass("ion-android-alert");
      if(stopedLPN == 0){
         runningLPN = 1;
         setTimeout(intermitenciaLPN, 500);
      }
      else{
        stopLPN();
      }
    }
    function actualizarAlertaLPN(){
        $.ajax({
            beforeSend: function () {
                $("#iconLPN").toggleClass("fa");
                $("#iconLPN").toggleClass("fa-refresh");
                $("#iconLPN").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconLPN").toggleClass("fa");
                $("#iconLPN").toggleClass("fa-refresh");
                $("#iconLPN").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/wms/errores/cantLPN',
            dataType: 'json',
            success: function(result){
                if(result > 0){
                    if(runningLPN == 0){
                        stopedLPN = 0;
                        intermitenciaLPN();
                        
                    }
                    setTimeout(actualizarAlertaLPN, 600000);
                }else{
                    setTimeout(actualizarAlertaLPN, 600000);
                    if(stopedLPN == 0 && runningLPN == 1){
                        runningLPN = 0;
                        stopedLPN = 1
                    }
                }

            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
        $.ajax({
            beforeSend: function () {
                $("#iconLPN").toggleClass("fa");
                $("#iconLPN").toggleClass("fa-refresh");
                $("#iconLPN").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconLPN").toggleClass("fa");
                $("#iconLPN").toggleClass("fa-refresh");
                $("#iconLPN").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/wms/lpn/totLPN',
            dataType: 'json',
            success: function(result){
                result.forEach(function(element){
                    $("#nLPN").html(element.TOT);  
                });
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    function stopLPN(){
        $("#LPNBox").removeClass("bg-green");
        $("#LPNBox").removeClass("bg-red");
        $("#iconLPN").removeClass("glyphicon-ok");
        $("#iconLPN").removeClass("ion-android-alert"); 
        $("#LPNBox").addClass("bg-green");
        $("#iconLPN").addClass("glyphicon-ok");
    }
    $("#gridResLPN").kendoGrid({
        autoBind: false,
        dataSource: dataSourceResLPN,
        height: "100%", 
        width: "600px",
        selectable: "row",
        sortable: true, 
        filterable: true,
        scrollable: true,
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {field: "STAT_CODE",title: "CODIGO ESTADO",width: 90, filterable:false, resizable:false, height: 80},
            {field: "CODE_DESC",title: "DESC ESTADO",width:70,filterable:false},
            {field: "CANTDAD_LPN",title: "CANTIDAD LPN",width:70,filterable: false}
        ]
    });
    $("#gridDetLPN").kendoGrid({
        autoBind: false,
        dataSource: dataSourceDetLPN,
        height: "90%", 
        width: "600px",
        sortable: true, 
        filterable: true,
        scrollable: true,
        change: function (e, args) {
                    lpns = [];
                    var rows = e.sender.select();
                    rows.each(function(e) {
                        var grid = $("#gridDetLPN").data("kendoGrid");
                        var item = grid.dataItem(this);
                        lpns.push({CASE_NBR: item.CASE_NBR, ORIG_SHPMT_NBR: item.ORIG_SHPMT_NBR});
                    }) 
        },
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {selectable: true, width: "15px" },
            {field: "CASE_NBR",title: "LPN",width: 90, filterable:false, resizable:false, height: 80},
            {field: "ORIG_SHPMT_NBR",title: "ASN",width:70,filterable:false},
            {field: "SIZE_DESC",title: "SKU",width:70,filterable: false},
            {field: "MSG_LPN",title: "MENSAJE CABECERA",width:70,filterable:false},
            {field: "MSG_SKU",title: "MENSAJE DETALLE",width:70,filterable: false}
        ]
    });
    $("#LPNBajados").click(function(){
        var popupresumenlpn = $("#POPUP_Resumen_LPN");
        popupresumenlpn.data("kendoWindow").open();
        var grid = $("#gridResLPN");
        grid.data("kendoGrid").dataSource.read();
    });
    $("#LPNBajados").hover(function(){
        $("#iconLPN").toggleClass("ion-clipboard");
    });
    var ventana_resumen_lpn = $("#POPUP_Resumen_LPN");
    ventana_resumen_lpn.kendoWindow({
        width: "750px",
        height: "550px",
        title: "Resumen LPN Bajados",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();
    $("#LPNDetalles").click(function(){
        actualizarAlertaLPN();
        var popupdetallelpn = $("#POPUP_Detalle_LPN");
        popupdetallelpn.data("kendoWindow").open();
        var grid = $("#gridDetLPN");
        grid.data("kendoGrid").dataSource.read();
    });
    var ventana_detalle_lpn = $("#POPUP_Detalle_LPN");
    ventana_detalle_lpn.kendoWindow({
        width: "750px",
        height: "550px",
        title: "Detalle Errores LPN",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();
    function onReadResLPN(e){
        $.ajax({
            type: "POST",
            url: baseURL + 'alertas/wms/lpn/resumen',
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    function onReadDetLPN(e){
        $.ajax({
            type: "POST",
            url: baseURL + 'alertas/wms/errores/LPN',
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    $("#toolbarLPN").kendoToolBar({
        items: [
            { type: "button", text: "Reprocesar", icon: "k-icon k-i-reload" ,click: ActualizarLPN},
            { type: "button", text: "Eliminar", icon: "k-icon k-i-delete" ,click: EliminarLPN}
        ]
    });
    function ActualizarLPN(){
        var data = JSON.stringify(lpns);
        if(Array.isArray(lpns) && lpns.length != 0){
            $.ajax({
                type: "POST",
                url: baseURL + 'alertas/wms/lpn/actualizar',
                data:{ lpns: data},
                dataType: 'json',
                success: function(result){
                    if(result == 0){                       
                        var popupdetallelpn = $("#POPUP_Detalle_LPN");
                        popupdetallelpn.data("kendoWindow").close();
                        $("#success-modal").text("LPN Actualizado Correctamente");
                        $("#modal-success").modal('show');
                        actualizarAlertaLPN();
                        lpns = [];
                    }
                    else{
                        var popupdetallelpn = $("#POPUP_Detalle_LPN");
                        popupdetallelpn.data("kendoWindow").close();
                        $("#error-modal").text("Error al actualizar LPN");
                        $("#modal-danger").modal('show');
                        lpns = [];
                    }
                },
                error: function(xhr){
                    console.log('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
                }
            });
        }
        else{;
            var popupdetallelpn = $("#POPUP_Detalle_LPN");
            popupdetallelpn.data("kendoWindow").close();
            $("#error-modal").text("Debe seleccionar al menos un LPN para actualizar");
            $("#modal-danger").modal('show');
        }
    }
    function EliminarLPN(){
        if(Array.isArray(lpns) && lpns.length != 0){
            var data = JSON.stringify(lpns);
            var ok = confirm("Esta seguro que desea eliminar estos LPN?");
            if(ok){
                $.ajax({
                    type: "POST",
                    url: baseURL + 'alertas/wms/lpn/eliminar',
                    data:{ lpns: data},
                    dataType: 'json',
                    success: function(result){
                        if(result == 0){
                            var popupdetallelpn = $("#POPUP_Detalle_LPN");
                            popupdetallelpn.data("kendoWindow").close();
                            $("#success-modal").text("LPN Eliminados Correctamente");
                            $("#modal-success").modal('show');
                            actualizarAlertaLPN();
                            lpns = [];
                        }
                        else{
                            var popupdetallelpn = $("#POPUP_Detalle_LPN");
                            popupdetallelpn.data("kendoWindow").close();
                            $("#error-modal").text("Error al eliminar LPN");
                            $("#modal-danger").modal('show');
                            lpns = [];
                        }
                    },
                    error: function(xhr){
                        console.log('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
                    }
                });
            }
            else{
                var popupdetallelpn = $("#POPUP_Detalle_LPN");
                popupdetallelpn.data("kendoWindow").close();
                $("#modal-info").modal('show');
            }
        }    
        else{
            console.log(lpns);
            var popupdetallelpn = $("#POPUP_Detalle_LPN");
            popupdetallelpn.data("kendoWindow").close();
            $("#error-modal").text("Debe seleccionar al menos un LPN para eliminar");
            $("#modal-danger").modal('show');
        }  
    }

    //FUNCIONALIDADES ALERTA DISTRO

    var dataSourceDetDISTRO = new kendo.data.DataSource({
        transport: {
            read: onReadErrDISTRO
        },
        schema: {
            model: {
                id: "DISTRO_NBR",
                fields: {
                        DISTRO_NBR: {type: "string"}, // number - string - date
                        SIZE_DESC: {type: "string"},
                        CREATE_DATE_TIME: {type: "string"}, // number - string - date
                        MSG: {type: "string"}
                    }
            }
        },
        pageSize: 15
    });
    function intermitenciaDISTRO(){
      $("#DISTROBox").toggleClass("bg-green");
      $("#DISTROBox").toggleClass("bg-red");
      $("#iconDISTRO").toggleClass("glyphicon-ok");
      $("#iconDISTRO").toggleClass("ion-android-alert");
      if(stopedDISTRO == 0){
         runningDISTRO = 1;
         setTimeout(intermitenciaDISTRO, 500);
      }
      else{
        stopDISTRO();
      }
    }
    function actualizarAlertaDISTRO(){
        $.ajax({
            beforeSend: function () {
                $("#iconDISTRO").toggleClass("fa");
                $("#iconDISTRO").toggleClass("fa-refresh");
                $("#iconDISTRO").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconDISTRO").toggleClass("fa");
                $("#iconDISTRO").toggleClass("fa-refresh");
                $("#iconDISTRO").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/wms/errores/cantDISTRO',
            dataType: 'json',
            success: function(result){
                if(result > 0){
                    if(runningDISTRO == 0){
                        stopedDISTRO = 0;
                        intermitenciaDISTRO();
                        
                    }
                    setTimeout(actualizarAlertaDISTRO, 600000);
                }else{
                    setTimeout(actualizarAlertaDISTRO, 600000);
                    if(stopedDISTRO == 0 && runningDISTRO == 1){
                        runningDISTRO = 0;
                        stopedDISTRO = 1
                    }
                }

            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
        $.ajax({
            beforeSend: function () {
                $("#iconDISTRO").toggleClass("fa");
                $("#iconDISTRO").toggleClass("fa-refresh");
                $("#iconDISTRO").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconDISTRO").toggleClass("fa");
                $("#iconDISTRO").toggleClass("fa-refresh");
                $("#iconDISTRO").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/wms/errores/cantDISTRO',
            dataType: 'json',
            success: function(result){
                        $("#nDISTRO").html(result);  
                
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    function stopDISTRO(){
        $("#DISTROBox").removeClass("bg-green");
        $("#DISTROBox").removeClass("bg-red");
        $("#iconDISTRO").removeClass("glyphicon-ok");
        $("#iconDISTRO").removeClass("ion-android-alert"); 
        $("#DISTROBox").addClass("bg-green");
        $("#iconDISTRO").addClass("glyphicon-ok");
    }
    $("#gridDetDISTRO").kendoGrid({
        autoBind: false,
        dataSource: dataSourceDetDISTRO,
        height: "90%", 
        width: "600px",
        sortable: true, 
        filterable: true,
        scrollable: true,
        change: function (e, args) {
                    distros = [];
                    var rows = e.sender.select();
                    rows.each(function(e) {
                        var grid = $("#gridDetDISTRO").data("kendoGrid");
                        var item = grid.dataItem(this);
                        distros.push({DISTRO_NBR: item.DISTRO_NBR});
                    }) 
        },
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {selectable: true, width: "15px" },
            {field: "DISTRO_NBR",title: "DISTRO",width: 90, filterable:false, resizable:false, height: 80},
            {field: "SIZE_DESC",title: "SKU",width:70,filterable:false},
            {field: "CREATE_DATE_TIME",title: "FECHA CREACION",width:70,filterable: false},
            {field: "MSG",title: "MENSAJE",width:70,filterable: false}
        ]
    });
    $("#DISTROSDetalles").click(function(){
        actualizarAlertaDISTRO();
        var popupdetalledistro = $("#POPUP_Detalle_DISTRO");
        popupdetalledistro.data("kendoWindow").open();
        var grid = $("#gridDetDISTRO");
        grid.data("kendoGrid").dataSource.read();
    });
    var ventana_detalle_distro = $("#POPUP_Detalle_DISTRO");
    ventana_detalle_distro.kendoWindow({
        width: "750px",
        height: "550px",
        title: "Detalle Errores DISTRO",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();
    function onReadErrDISTRO(e){
        $.ajax({
            type: "POST",
            url: baseURL + 'alertas/wms/errores/DISTRO',
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    $("#toolbarDISTRO").kendoToolBar({
        items: [
            { type: "button", text: "Reprocesar", icon: "k-icon k-i-reload" ,click: ActualizarDISTRO},
            { type: "button", text: "Eliminar", icon: "k-icon k-i-delete" ,click: EliminarDISTRO}
        ]
    });
    function ActualizarDISTRO(){
        var data = JSON.stringify(distros);
        if(Array.isArray(distros) && distros.length != 0){
            $.ajax({
                type: "POST",
                url: baseURL + 'alertas/wms/distro/actualizar',
                data:{ distros: data},
                dataType: 'json',
                success: function(result){
                    if(result == 0){                       
                        var popupdetalledistro = $("#POPUP_Detalle_DISTRO");
                        popupdetalledistro.data("kendoWindow").close();
                        $("#success-modal").text("DISTRO Actualizado Correctamente");
                        $("#modal-success").modal('show');
                        actualizarAlertaDISTRO();
                        distros = [];
                    }
                    else{
                        var popupdetalledistro = $("#POPUP_Detalle_DISTRO");
                        popupdetalledistro.data("kendoWindow").close();
                        $("#error-modal").text("Error al actualizar DISTRO");
                        $("#modal-danger").modal('show');
                        distros = [];
                    }
                },
                error: function(xhr){
                    console.log('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
                }
            });
        }
        else{;
            var popupdetalledistro = $("#POPUP_Detalle_DISTRO");
            popupdetalledistro.data("kendoWindow").close();
            $("#error-modal").text("Debe seleccionar al menos un DISTRO para actualizar");
            $("#modal-danger").modal('show');
        }
    }
    function EliminarDISTRO(){
        if(Array.isArray(distros) && distros.length != 0){
            var data = JSON.stringify(distros);
            var ok = confirm("Esta seguro que desea eliminar estos DISTROS?");
            if(ok){
                $.ajax({
                    type: "POST",
                    url: baseURL + 'alertas/wms/distro/eliminar',
                    data:{ distros: data},
                    dataType: 'json',
                    success: function(result){
                        if(result == 0){
                            var popupdetalledistro = $("#POPUP_Detalle_DISTRO");
                            popupdetalledistro.data("kendoWindow").close();
                            $("#success-modal").text("DISTROS Eliminados Correctamente");
                            $("#modal-success").modal('show');
                            actualizarAlertaDISTRO();
                            distros = [];
                        }
                        else{
                            var popupdetalledistro = $("#POPUP_Detalle_DISTRO");
                            popupdetalledistro.data("kendoWindow").close();
                            $("#error-modal").text("Error al eliminar DISTRO");
                            $("#modal-danger").modal('show');
                            distros = [];
                        }
                    },
                    error: function(xhr){
                        console.log('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
                    }
                });
            }
            else{
                var popupdetalledistro = $("#POPUP_Detalle_DISTRO");
                popupdetalledistro.data("kendoWindow").close();
                $("#modal-info").modal('show');
            }
        }    
        else{
            console.log(lpns);
            var popupdetalledistro = $("#POPUP_Detalle_DISTRO");
            popupdetalledistro.data("kendoWindow").close();
            $("#error-modal").text("Debe seleccionar al menos un DISTRO para eliminar");
            $("#modal-danger").modal('show');
        }  
    }

    //FUNCIONALIDADES ALERTA CARGA

    var dataSourceResCARGA = new kendo.data.DataSource({
        transport: {
            read: onReadResCARGA
        },
        schema: {
            model: {
                id: "STAT_CODE",
                fields: {
                        STAT_CODE: {type: "string"}, // number - string - date
                        CODE_DESC: {type: "string"},
                        CANTIDAD_CARGAS: {type: "string"}
                    }
            }
        },
        pageSize: 15
    });
    var dataSourceDetCARGA = new kendo.data.DataSource({
        transport: {
            read: onReadErrCARGA
        },
        schema: {
            model: {
                id: "LOAD_NBR",
                fields: {
                        LOAD_NBR: {type: "string"}, // number - string - date
                        STAT_CODE: {type: "string"},
                        CODE_DESC: {type: "string"}, // number - string - date
                        TRLR_NBR: {type: "string"}
                    }
            }
        },
        pageSize: 15
    });
    function intermitenciaCARGA(){
      $("#CARGABox").toggleClass("bg-green");
      $("#CARGABox").toggleClass("bg-red");
      $("#iconCARGA").toggleClass("glyphicon-ok");
      $("#iconCARGA").toggleClass("ion-android-alert");
      if(stopedCARGA == 0){
         runningCARGA = 1;
         setTimeout(intermitenciaCARGA, 500);
      }
      else{
        stopCARGA();
      }
    }
    function actualizarAlertaCARGA(){
        $.ajax({
            beforeSend: function () {
                $("#iconCARGA").toggleClass("fa");
                $("#iconCARGA").toggleClass("fa-refresh");
                $("#iconCARGA").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconCARGA").toggleClass("fa");
                $("#iconCARGA").toggleClass("fa-refresh");
                $("#iconCARGA").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/wms/errores/cantCARGA',
            dataType: 'json',
            success: function(result){
                if(result > 0){
                    if(runningCARGA == 0){
                        stopedCARGA = 0;
                        intermitenciaCARGA();
                        
                    }
                    setTimeout(actualizarAlertaCARGA, 600000);
                }else{
                    setTimeout(actualizarAlertaCARGA, 600000);
                    if(stopedCARGA == 0 && runningCARGA == 1){
                        runningCARGA = 0;
                        stopedCARGA = 1
                    }
                }

            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
        $.ajax({
            beforeSend: function () {
                $("#iconCARGA").toggleClass("fa");
                $("#iconCARGA").toggleClass("fa-refresh");
                $("#iconCARGA").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconCARGA").toggleClass("fa");
                $("#iconCARGA").toggleClass("fa-refresh");
                $("#iconCARGA").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/wms/carga/totCARGA',
            dataType: 'json',
            success: function(result){
                result.forEach(function(element){
                    $("#nCARGA").html(element.TOT);  
                });
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    function stopCARGA(){
        $("#CARGABox").removeClass("bg-green");
        $("#CARGABox").removeClass("bg-red");
        $("#iconCARGA").removeClass("glyphicon-ok");
        $("#iconCARGA").removeClass("ion-android-alert"); 
        $("#CARGABox").addClass("bg-green");
        $("#iconCARGA").addClass("glyphicon-ok");
    }
    $("#gridResCARGA").kendoGrid({
        autoBind: false,
        dataSource: dataSourceResCARGA,
        height: "100%", 
        width: "600px",
        sortable: true, 
        filterable: true,
        scrollable: true,
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {field: "STAT_CODE",title: "CODIGO ESTADO",width: 90, filterable:false, resizable:false, height: 80},
            {field: "CODE_DESC",title: "DESCRIPCION ESTADO",width:70,filterable:false},
            {field: "CANTIDAD_CARGAS",title: "CANTIDAD CARGAS",width:70,filterable: false}
        ]
    });
    $("#gridDetCARGA").kendoGrid({
        autoBind: false,
        dataSource: dataSourceDetCARGA,
        height: "90%", 
        width: "600px",
        sortable: true, 
        filterable: true,
        scrollable: true,
        change: function (e, args) {
                    cargas = [];
                    var rows = e.sender.select();
                    rows.each(function(e) {
                        var grid = $("#gridDetCARGA").data("kendoGrid");
                        var item = grid.dataItem(this);
                        cargas.push({LOAD_NBR: item.LOAD_NBR});
                    }) 
        },
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {selectable: true, width: "15px" },
            {field: "LOAD_NBR",title: "CARGA",width: 90, filterable:false, resizable:false, height: 80},
            {field: "STAT_CODE",title: "ESTADO",width:70,filterable:false},
            {field: "CODE_DESC",title: "DESC ESTADO",width:70,filterable: false},
            {field: "TRLR_NBR",title: "PATENTE",width:70,filterable: false}
        ]
    });
    $("#CARGADetalles").click(function(){
        actualizarAlertaCARGA();
        var popupdetallecarga = $("#POPUP_Detalle_CARGA");
        popupdetallecarga.data("kendoWindow").open();
        var grid = $("#gridDetCARGA");
        grid.data("kendoGrid").dataSource.read();
    });
    var ventana_detalle_carga = $("#POPUP_Detalle_CARGA");
    ventana_detalle_carga.kendoWindow({
        width: "750px",
        height: "550px",
        title: "Detalle Errores CARGA",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();
    $("#CARGASEjecutadas").click(function(){
        var popupresumencarga = $("#POPUP_Resumen_CARGA");
        popupresumencarga.data("kendoWindow").open();
        var grid = $("#gridResCARGA");
        grid.data("kendoGrid").dataSource.read();
    });
    $("#CARGASEjecutadas").hover(function(){
        $("#iconCARGA").toggleClass("ion-clipboard");
    });
    var ventana_resumen_carga = $("#POPUP_Resumen_CARGA");
    ventana_resumen_carga.kendoWindow({
        width: "750px",
        height: "550px",
        title: "Resumen Cargas Enviadas",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();
    function onReadErrCARGA(e){
        $.ajax({
            type: "POST",
            url: baseURL + 'alertas/wms/errores/CARGA',
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    function onReadResCARGA(e){
        $.ajax({
            type: "POST",
            url: baseURL + 'alertas/wms/carga/resumen',
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    $("#toolbarCARGA").kendoToolBar({
        items: [
            { type: "button", text: "Reprocesar", icon: "k-icon k-i-reload" ,click: ActualizarDISTRO}
        ]
    });
    function ActualizarCARGA(){
        var data = JSON.stringify(cargas);
        if(Array.isArray(cargas) && cargas.length != 0){
            $.ajax({
                type: "POST",
                url: baseURL + 'alertas/wms/carga/actualizar',
                data:{ cargas: data},
                dataType: 'json',
                success: function(result){
                    if(result == 0){                       
                        var popupdetallecarga = $("#POPUP_Detalle_CARGA");
                        popupdetallecarga.data("kendoWindow").close();
                        $("#success-modal").text("CARGA Actualizada Correctamente");
                        $("#modal-success").modal('show');
                        actualizarAlertaCARGA();
                        cargas = [];
                    }
                    else{
                        var popupdetallecarga = $("#POPUP_Detalle_CARGA");
                        popupdetallecarga.data("kendoWindow").close();
                        $("#error-modal").text("Error al actualizar CARGA");
                        $("#modal-danger").modal('show');
                        cargas = [];
                    }
                },
                error: function(xhr){
                    console.log('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
                }
            });
        }
        else{;
            var popupdetallecarga = $("#POPUP_Detalle_CARGA");
            popupdetallecarga.data("kendoWindow").close();
            $("#error-modal").text("Debe seleccionar al menos una CARGA para actualizar");
            $("#modal-danger").modal('show');
        }
    }

    //FUNCIONALIDADES ALERTA FASN

    var dataSourceDetFASN = new kendo.data.DataSource({
        transport: {
            read: onReadErrFASN
        },
        schema: {
            model: {
                id: "SHPMT_NBR",
                fields: {
                        SHPMT_NBR: {type: "string"}, // number - string - date
                        STAT_CODE: {type: "string"},
                        FECHA_CREACION: {type: "string"}, // number - string - date
                        FECHA_MOD: {type: "string"},
                        FECHA_VERIFICACION: {type: "string"},
                        MANIF_NBR: {type: "string"},
                        REP_NAME: {type: "string"},
                        PO_NBR: {type: "string"},
                    }
            }
        },
        pageSize: 15
    });
    function intermitenciaFASN(){
      $("#FASNBox").toggleClass("bg-green");
      $("#FASNBox").toggleClass("bg-red");
      $("#iconFASN").toggleClass("glyphicon-ok");
      $("#iconFASN").toggleClass("ion-android-alert");
      if(stopedFASN == 0){
         runningFASN = 1;
         setTimeout(intermitenciaFASN, 500);
      }
      else{
        stopFASN();
      }
    }
    function actualizarAlertaFASN(){
        $.ajax({
            beforeSend: function () {
                $("#iconFASN").toggleClass("fa");
                $("#iconFASN").toggleClass("fa-refresh");
                $("#iconFASN").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconFASN").toggleClass("fa");
                $("#iconFASN").toggleClass("fa-refresh");
                $("#iconFASN").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/wms/errores/cantFASN',
            dataType: 'json',
            success: function(result){
                if(result > 0){
                    if(runningFASN == 0){
                        stopedFASN = 0;
                        intermitenciaFASN();
                        
                    }
                    setTimeout(actualizarAlertaFASN, 600000);
                }else{
                    setTimeout(actualizarAlertaFASN, 600000);
                    if(stopedFASN == 0 && runningFASN == 1){
                        runningFASN = 0;
                        stopedFASN = 1
                    }
                }

            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
        $.ajax({
            beforeSend: function () {
                $("#iconFASN").toggleClass("fa");
                $("#iconFASN").toggleClass("fa-refresh");
                $("#iconFASN").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconFASN").toggleClass("fa");
                $("#iconFASN").toggleClass("fa-refresh");
                $("#iconFASN").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/wms/errores/cantFASN',
            dataType: 'json',
            success: function(result){
                $("#nFASN").html(result);  
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    function stopFASN(){
        $("#FASNBox").removeClass("bg-green");
        $("#FASNBox").removeClass("bg-red");
        $("#iconFASN").removeClass("glyphicon-ok");
        $("#iconFASN").removeClass("ion-android-alert"); 
        $("#FASNBox").addClass("bg-green");
        $("#iconFASN").addClass("glyphicon-ok");
    }
    function onReadErrFASN(e){
        $.ajax({
            type: "POST",
            url: baseURL + 'alertas/wms/errores/FASN',
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    $("#gridDetFASN").kendoGrid({
        autoBind: false,
        dataSource: dataSourceDetFASN,
        height: "90%", 
        width: "600px",
        sortable: true, 
        filterable: true,
        scrollable: true,
        change: function (e, args) {
                    fasns = [];
                    var rows = e.sender.select();
                    rows.each(function(e) {
                        var grid = $("#gridDetFASN").data("kendoGrid");
                        var item = grid.dataItem(this);
                        fasns.push({SHPMT_NBR: item.SHPMT_NBR});
                    }) 
        },
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {selectable: true, width: "15px" },
            {field: "SHPMT_NBR",title: "ASN",width: 50, filterable:false, resizable:false, height: 80},
            {field: "STAT_CODE",title: "ESTADO",width:40,filterable:false},
            {field: "FECHA_CREACION",title: "FECHA CREACION",width:70,filterable: false},
            {field: "FECHA_MOD",title: "FEC MODIFICACION",width:70,filterable: false},
            {field: "FECHA_VERIFICACION",title: "FEC VERIFICACION",width:70,filterable: false},
            {field: "MANIF_NBR",title: "FACTURA",width:70,filterable: false},
            {field: "REP_NAME",title: "PROVEEDOR",width:70,filterable: false},
            {field: "PO_NBR",title: "OC",width:50,filterable: false}
        ]
    });
    $("#FASNDetalles").click(function(){
        actualizarAlertaFASN();
        var popupdetallefasn = $("#POPUP_Detalle_FASN");
        popupdetallefasn.data("kendoWindow").open();
        var grid = $("#gridDetFASN");
        grid.data("kendoGrid").dataSource.read();
    });
    var ventana_detalle_fasn = $("#POPUP_Detalle_FASN");
    ventana_detalle_fasn.kendoWindow({
        width: "1000px",
        height: "550px",
        title: "Detalle Errores ASN Tabla Final",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();
    $("#toolbarFASN").kendoToolBar({
        items: [
            { type: "button", text: "Reprocesar", icon: "k-icon k-i-reload" ,click: ActualizarFASN}
        ]
    });
    function ActualizarFASN(){
        var data = JSON.stringify(fasns);
        if(Array.isArray(fasns) && fasns.length != 0){
            $.ajax({
                type: "POST",
                url: baseURL + 'alertas/wms/fasn/actualizar',
                data:{ fasns: data},
                dataType: 'json',
                success: function(result){
                    if(result == 0){                       
                        var popupdetallefasn = $("#POPUP_Detalle_FASN");
                        popupdetallefasn.data("kendoWindow").close();
                        $("#success-modal").text("ASN Actualizada Correctamente");
                        $("#modal-success").modal('show');
                        actualizarAlertaFASN();
                        fasns = [];
                    }
                    else{
                        var popupdetallefasn = $("#POPUP_Detalle_FASN");
                        popupdetallefasn.data("kendoWindow").close();
                        $("#error-modal").text("Error al actualizar ASN");
                        $("#modal-danger").modal('show');
                        fasns = [];
                    }
                },
                error: function(xhr){
                    console.log('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
                }
            });
        }
        else{
            var popupdetallefasn = $("#POPUP_Detalle_FASN");
            popupdetallefasn.data("kendoWindow").close();
            $("#error-modal").text("Debe seleccionar al menos una ASN para actualizar");
            $("#modal-danger").modal('show');
        }
    }
});
