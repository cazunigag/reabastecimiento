$(document).ready(function(){
	actualizarAlertaPO();
    actualizarAlertaPKT();
    actualizarAlertaBRCD();
    actualizarAlertaART();
    actualizarAlertaOLA();
    actualizarAlertaCITA();
    actualizarAlertaASN();

    var pkts = [];
    var pos = [];
    var brcds = [];
    var arts = [];
    var citas = [];
    var asns = [];

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
      $("#iconPKT").toggleClass("ion-clipboard");
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
            type: "POST",
            url: baseURL + 'alertas/errores/cantPKT',
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
            type: "POST",
            url: baseURL + 'alertas/pkt/totPKT',
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
        $("#iconPKT").removeClass("ion-clipboard");
        $("#iconPKT").removeClass("ion-android-alert"); 
        $("#pktBox").addClass("bg-green");
        $("#iconPKT").addClass("ion-clipboard");
    }
    $("#gridDetPKT").kendoGrid({
        autoBind: false,
        dataSource: dataSourceDetPKT,
        height: "100%", 
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
            url: baseURL + 'alertas/errores/PKT',
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
            url: baseURL + 'alertas/pkt/resumen',
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
                url: baseURL + 'alertas/pkt/actualizar',
                data:{ pkts: data},
                dataType: 'json',
                success: function(result){
                    if(result == 0){                       
                        var popupdetallepkt = $("#POPUP_Detalle_PKT");
                        popupdetallepkt.data("kendoWindow").close();
                        $("#success-modal").text("Pick Ticket Actualizado Correctamente");
                        $("#modal-success").modal('show');
                        actualizarAlertaPKT();
                    }
                    else{
                        var popupdetallepkt = $("#POPUP_Detalle_PKT");
                        popupdetallepkt.data("kendoWindow").close();
                        $("#error-modal").text("Error al actualizar Pick Ticket");
                        $("#modal-danger").modal('show');
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
                    url: baseURL + 'alertas/pkt/eliminar',
                    data:{ pkts: data},
                    dataType: 'json',
                    success: function(result){
                        if(result == 0){
                            var popupdetallepkt = $("#POPUP_Detalle_PKT");
                            popupdetallepkt.data("kendoWindow").close();
                            $("#success-modal").text("Pick Ticket Eliminado Correctamente");
                            $("#modal-success").modal('show');
                            actualizarAlertaPKT();
                        }
                        else{
                            var popupdetallepkt = $("#POPUP_Detalle_PKT");
                            popupdetallepkt.data("kendoWindow").close();
                            $("#error-modal").text("Error al eliminar Pick Ticket");
                            $("#modal-danger").modal('show');
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
	function intermitenciaPO(){
      $("#POBox").toggleClass("bg-green");
      $("#POBox").toggleClass("bg-red");
      $("#iconPO").toggleClass("ion-clipboard");
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
            type: "POST",
            url: baseURL + 'alertas/errores/cantPO',
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
            type: "POST",
            url: baseURL + 'alertas/PO/totPO',
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
        $("#iconPO").removeClass("ion-clipboard");
        $("#iconPO").removeClass("ion-android-alert"); 
        $("#POBox").addClass("bg-green");
        $("#iconPO").addClass("ion-clipboard");
    }
    
     $("#gridDetPO").kendoGrid({
        autoBind: false,
        dataSource: dataSourceDetPO,
        height: "100%", 
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
   
    function onReadErrPO(e){
         $.ajax({
            type: "POST",
            url: baseURL + 'alertas/errores/PO',
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }
    $("#toolbarPO").kendoToolBar({
        items: [
            { type: "button", text: "Reprocesar", icon: "k-icon k-i-reload" ,click: ActualizarPO},
            { type: "button", text: "Eliminar", icon: "k-icon k-i-delete" ,click: EliminarPO}
        ]
    });
    function ActualizarPO(){
        var data = JSON.stringify(pos);
        if(Array.isArray(pos) && pos.length != 0){
            $.ajax({
                type: "POST",
                url: baseURL + 'alertas/PO/actualizar',
                data:{ pos: data},
                dataType: 'json',
                success: function(result){
                    if(result == 0){                      
                        var popupdetallepo = $("#POPUP_Detalle_PO");
                        popupdetallepo.data("kendoWindow").close();
                        $("#success-modal").text("Orden de Compra Actualizada Correctamente");
                        $("#modal-success").modal('show');
                        actualizarAlertaPO();
                    }
                    else{
                        var popupdetallepo = $("#POPUP_Detalle_PO");
                        popupdetallepo.data("kendoWindow").close();
                        $("#error-modal").text("Error al actualizar Orden de Compra");
                        $("#modal-danger").modal('show');
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
                    url: baseURL + 'alertas/PO/eliminar',
                    data:{ pos: data},
                    dataType: 'json',
                    success: function(result){
                        if(result == 0){                        
                            var popupdetallepo = $("#POPUP_Detalle_PO");
                            popupdetallepo.data("kendoWindow").close();
                            $("#success-modal").text("Orden de Compra Eliminada Correctamente");
                            $("#modal-success").modal('show');
                            actualizarAlertaPO();
                        }
                        else{
                            var popupdetallepo = $("#POPUP_Detalle_PO");
                            popupdetallepo.data("kendoWindow").close();
                            $("#error-modal").text("Error al eliminar Orden de Compra");
                            $("#modal-danger").modal('show');
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
      $("#iconBRCD").toggleClass("ion-clipboard");
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
            type: "POST",
            url: baseURL + 'alertas/errores/cantBRCD',
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
            type: "POST",
            url: baseURL + 'alertas/brcd/totBRCD',
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
        $("#iconBRCD").removeClass("ion-clipboard");
        $("#iconBRCD").removeClass("ion-android-alert"); 
        $("#BRCDBox").addClass("bg-green");
        $("#iconBRCD").addClass("ion-clipboard");
    }
    $("#gridDEtBRCD").kendoGrid({
        autoBind: false,
        dataSource: dataSourceDetBRCD,
        height: "100%", 
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
            url: baseURL + 'alertas/errores/BRCD',
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
                url: baseURL + 'alertas/brcd/actualizar',
                data:{ brcds: data},
                dataType: 'json',
                success: function(result){
                    if(result == 0){                      
                        var popupdetallebrcd = $("#POPUP_Detalle_BRCD");
                        popupdetallebrcd.data("kendoWindow").close();
                        $("#success-modal").text("XREF Actualizado Correctamente");
                        $("#modal-success").modal('show');
                        actualizarAlertaBRCD();
                    }
                    else{
                        var popupdetallebrcd = $("#POPUP_Detalle_BRCD");
                        popupdetallebrcd.data("kendoWindow").close();
                        $("#error-modal").text("Error al actualizar XREF");
                        $("#modal-danger").modal('show');
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
                    url: baseURL + 'alertas/brcd/eliminar',
                    data:{ brcds: data},
                    dataType: 'json',
                    success: function(result){
                        if(result == 0){                      
                            var popupdetallebrcd = $("#POPUP_Detalle_BRCD");
                            popupdetallebrcd.data("kendoWindow").close();
                            $("#success-modal").text("XREF eliminado Correctamente");
                            $("#modal-success").modal('show');
                            actualizarAlertaBRCD();
                        }
                        else{
                            var popupdetallebrcd = $("#POPUP_Detalle_BRCD");
                            popupdetallebrcd.data("kendoWindow").close();
                            $("#error-modal").text("Error al eliminar XREF");
                            $("#modal-danger").modal('show');
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
        height: "100%", 
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
      $("#iconART").toggleClass("ion-clipboard");
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
            type: "POST",
            url: baseURL + 'alertas/errores/cantART',
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
            type: "POST",
            url: baseURL + 'alertas/errores/cantART',
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
        $("#iconART").removeClass("ion-clipboard");
        $("#iconART").removeClass("ion-android-alert"); 
        $("#ARTBox").addClass("bg-green");
        $("#iconART").addClass("ion-clipboard");
    }
    function onReadErrART(e){
        $.ajax({
            type: "POST",
            url: baseURL + 'alertas/errores/ART',
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
                url: baseURL + 'alertas/art/actualizar',
                data:{ arts: data},
                dataType: 'json',
                success: function(result){
                    if(result == 0){                        
                        var popupdetalleart = $("#POPUP_Detalle_ART");
                        popupdetalleart.data("kendoWindow").close();
                        $("#success-modal").text("Articulo Actualizado Correctamente");
                        $("#modal-success").modal('show');
                        actualizarAlertaART();
                    }
                    else{
                        var popupdetalleart = $("#POPUP_Detalle_ART");
                        popupdetalleart.data("kendoWindow").close();
                        $("#error-modal").text("Error al actualizar Articulo");
                        $("#modal-danger").modal('show');
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
                    url: baseURL + 'alertas/art/eliminar',
                    data:{ arts: data},
                    dataType: 'json',
                    success: function(result){
                        if(result == 0){                        
                            var popupdetalleart = $("#POPUP_Detalle_ART");
                            popupdetalleart.data("kendoWindow").close();
                            $("#success-modal").text("Articulo Eliminado Correctamente");
                            $("#modal-success").modal('show');
                            actualizarAlertaART();
                        }
                        else{
                            var popupdetallebrcd = $("#POPUP_Detalle_BRCD");
                            popupdetallebrcd.data("kendoWindow").close();
                            $("#error-modal").text("Error al eliminar Articulo");
                            $("#modal-danger").modal('show');
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
    function onReadResOLA(e){
        $.ajax({
            type: "POST",
            url: baseURL + 'alertas/ola/resumen',
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
            url: baseURL + 'alertas/errores/OLA',
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
      $("#iconOLA").toggleClass("ion-clipboard");
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
            type: "POST",
            url: baseURL + 'alertas/errores/cantOLA',
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
            type: "POST",
            url: baseURL + 'alertas/ola/totOLA',
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
        $("#iconOLA").removeClass("ion-clipboard");
        $("#iconOLA").removeClass("ion-android-alert"); 
        $("#OLABox").addClass("bg-green");
        $("#iconOLA").addClass("ion-clipboard");
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
            url: baseURL + 'alertas/cita/resumen',
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
            url: baseURL + 'alertas/errores/CITA',
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
            url: baseURL + 'alertas/cita/resumenCod',
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
        height: "100%", 
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
            {field: "MSG",title: "MENSAJE",width:70,filterable: false}
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
      $("#iconCITA").toggleClass("ion-clipboard");
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
            type: "POST",
            url: baseURL + 'alertas/errores/cantCITA',
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
            type: "POST",
            url: baseURL + 'alertas/cita/totCITA',
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
        $("#iconCITA").removeClass("ion-clipboard");
        $("#iconCITA").removeClass("ion-android-alert"); 
        $("#CITABox").addClass("bg-green");
        $("#iconCITA").addClass("ion-clipboard");
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
                url: baseURL + 'alertas/cita/actualizar',
                data:{ citas: data},
                dataType: 'json',
                success: function(result){
                    if(result == 0){                       
                        var popupdetallecita = $("#POPUP_Detalle_CITA");
                        popupdetallecita.data("kendoWindow").close();
                        $("#success-modal").text("Cita Actualizada Correctamente");
                        $("#modal-success").modal('show');
                        actualizarAlertaPKT();
                    }
                    else{
                        var popupdetallecita = $("#POPUP_Detalle_CITA");
                        popupdetallecita.data("kendoWindow").close();
                        $("#error-modal").text("Error al actualizar Cita");
                        $("#modal-danger").modal('show');
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
                    url: baseURL + 'alertas/cita/eliminar',
                    data:{ citas: data},
                    dataType: 'json',
                    success: function(result){
                        if(result == 0){
                            var popupdetallecita = $("#POPUP_Detalle_CITA");
                            popupdetallecita.data("kendoWindow").close();
                            $("#success-modal").text("Citas Eliminadas Correctamente");
                            $("#modal-success").modal('show');
                            actualizarAlertaPKT();
                        }
                        else{
                            var popupdetallecita = $("#POPUP_Detalle_CITA");
                            popupdetallecita.data("kendoWindow").close();
                            $("#error-modal").text("Error al eliminar Citas");
                            $("#modal-danger").modal('show');
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
                        SHPMT_NBR: {type: "string"}, // number - string - date
                        MSG_SHPMT: {type: "string"},
                        SIZE_DESC: {type: "string"},
                        MSG_SKU: {type: "string"}
                    }
            }
        },
        pageSize: 15
    });
    function intermitenciaASN(){
      $("#ASNBox").toggleClass("bg-green");
      $("#ASNBox").toggleClass("bg-red");
      $("#iconASN").toggleClass("ion-clipboard");
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
            type: "POST",
            url: baseURL + 'alertas/errores/cantASN',
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
            type: "POST",
            url: baseURL + 'alertas/asn/totASN',
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
        $("#iconASN").removeClass("ion-clipboard");
        $("#iconASN").removeClass("ion-android-alert"); 
        $("#ASNBox").addClass("bg-green");
        $("#iconASN").addClass("ion-clipboard");
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
        height: "100%", 
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
            {field: "SHPMT_NBR",title: "ASN",width: 90, filterable:false, resizable:false, height: 80},
            {field: "MSG_SHPMT",title: "MENSAJE ASN",width:70,filterable:false},
            {field: "SIZE_DESC",title: "SKU",width:70,filterable: false},
            {field: "MSG_SKU",title: "MENSAJE SKU",width:70,filterable: false}
        ]
    });
    $("#ASNBajados").click(function(){
        var popupresumenasn = $("#POPUP_Resumen_ASN");
        popupresumenasn.data("kendoWindow").open();
        var grid = $("#gridResASN");
        grid.data("kendoGrid").dataSource.read();
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
            url: baseURL + 'alertas/asn/resumen',
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
            url: baseURL + 'alertas/asn/resumencod',
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
            url: baseURL + 'alertas/errores/ASN',
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
                url: baseURL + 'alertas/cita/actualizar',
                data:{ asns: data},
                dataType: 'json',
                success: function(result){
                    if(result == 0){                       
                        var popupdetalleasn = $("#POPUP_Detalle_ASN");
                        popupdetalleasn.data("kendoWindow").close();
                        $("#success-modal").text("ASN Actualizada Correctamente");
                        $("#modal-success").modal('show');
                        actualizarAlertaPKT();
                    }
                    else{
                        var popupdetalleasn = $("#POPUP_Detalle_ASN");
                        popupdetalleasn.data("kendoWindow").close();
                        $("#error-modal").text("Error al actualizar ASN");
                        $("#modal-danger").modal('show');
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
                    url: baseURL + 'alertas/cita/eliminar',
                    data:{ asns: data},
                    dataType: 'json',
                    success: function(result){
                        if(result == 0){
                            var popupdetalleasn = $("#POPUP_Detalle_ASN");
                            popupdetalleasn.data("kendoWindow").close();
                            $("#success-modal").text("ASN Eliminadas Correctamente");
                            $("#modal-success").modal('show');
                            actualizarAlertaPKT();
                        }
                        else{
                            var popupdetalleasn = $("#POPUP_Detalle_ASN");
                            popupdetalleasn.data("kendoWindow").close();
                            $("#error-modal").text("Error al eliminar ASN");
                            $("#modal-danger").modal('show');
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
});
