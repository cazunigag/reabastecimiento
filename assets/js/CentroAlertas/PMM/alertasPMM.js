$(document).ready(function(){

	//llamadas de alertas
    var codigoASN = "";
    var fecha = "";

	actualizarAlertaDPW();
    actualizarAlertaDCPW();

	//declaracion de variables

	var stopedDPW = 0;
	var runningDPW = 0;
    var stopedDCPW = 0;
    var runningDCPW = 0;
    var stopedELPND = 0;
    var runningELPND = 0;
    var stopedEALM = 0;
    var runningEALM = 0;

    var carga = "";

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
                        UNITS_RCVD: {type: "string"}
                    }
            }
        },
        pageSize: 15
    });
    var dataSourceDetErrDPW = new kendo.data.DataSource({
        transport: {
            read: onReadDetErrDPW
        },
        schema: {
            model: {
                id: "ASN",
                fields: {
                        ASN: {type: "string"}, // number - string - date
                        LPN: {type: "string"},
                        VERIFICACION: {type: "string"}, // number - string - date
                        MENU: {type: "string"},
                        FECHA_ALMACENAJE: {type: "string"}
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
            beforeSend: function () {
                $("#iconDPW").toggleClass("fa");
                $("#iconDPW").toggleClass("fa-refresh");
                $("#iconDPW").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconDPW").toggleClass("fa");
                $("#iconDPW").toggleClass("fa-refresh");
                $("#iconDPW").toggleClass("fa-spin");
            },
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
                $("#error-modal").text("Ocurrio un error durante el proceso, intentelo nuevamente");
                $("#modal-danger").modal('show');
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
        $("#nDPW").html('0');
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
        title: "Documentos no enviados a PMM",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();
    var ventana_detalle_errdpw = $("#POPUP_DetErr_DPW");
    ventana_detalle_errdpw.kendoWindow({
        width: "1000px",
        height: "550px",
        title: "Detalle error Documentos PMM",
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
            {field: "SHPMT_NBR",title: "ASN",width: 70, filterable:false},
            {field: "VENDOR_ID",title: "RUT PROVEEDOR",width:70, filterable:false},
            {field: "REP_NAME",title: "PROVEEDOR",width:100,filterable: {multi: true, search: true}},
            {field: "MANIF_NBR",title: "DOCUMENTO",width: 70,filterable: {multi: true, search: true}},
            {field: "PO_NBR",title: "OC",width:70,filterable: {multi: true, search: true}},
            {field: "VERF_DATE_TIME",title: "FEC VERIFICACION",width:70,filterable: false},
            {field: "UNITS_RCVD",title: "UND RECIBIDAS",width:70,filterable: false}
        ]
    }).on("click", "tbody td", function(e) {
        var cell = $(e.currentTarget);
        var cellIndex = cell[0].cellIndex;
        var grid = $("#gridDetDPW").data("kendoGrid");
        var column = grid.columns[0];
        var dataItem = grid.dataItem(cell.closest("tr"));
        codigoASN = dataItem[column.field];
        var popupdeterrdpw = $("#POPUP_DetErr_DPW");
        popupdeterrdpw.data("kendoWindow").open();
        var grid = $("#gridDetERRDPW");
        grid.data("kendoGrid").dataSource.read();
    });
     $("#gridDetERRDPW").kendoGrid({
        autoBind: false,
        dataSource: dataSourceDetErrDPW,
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
            {field: "ASN",title: "ASN",width: 70, filterable:false},
            {field: "LPN",title: "LPN",width:70, filterable:false},
            {field: "VERIFICACION",title: "FEC VERIFICACION",width:100,filterable: false},
            {field: "MENU",title: "MENU / N° TRAN",width: 70,filterable: false},
            {field: "FECHA_ALMACENAJE",title: "FEC DISP / ALM",width:70,filterable: false}
        ]
    });
    function onReadDPW(e){
         $.ajax({
            type: "POST",
            url: baseURL + 'alertas/pmm/errores/difPMMWMS',
            dataType: 'json',
            success: function(result){
            	if(result.length > 0){
            		if(runningDPW == 0){
            			stopedDPW = 0;
            			intermiteciaDPW();
            		}
                    $("#nDPW").html(result.length);
            	}else{
            		stopedDPW = 1;
            	}
                e.success(result);
            },
            error: function(result){
                $("#error-modal").text("Ocurrio un error durante el proceso, intentelo nuevamente");
                $("#modal-danger").modal('show');
            }
        });
    }
    function onReadDetErrDPW(e){
         $.ajax({
            type: "POST",
            url: baseURL + 'alertas/pmm/errores/detDifPMMWMS',
            data: {asn: codigoASN},
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                $("#error-modal").text("Ocurrio un error durante el proceso, intentelo nuevamente");
                $("#modal-danger").modal('show');
            }
        });
    }

    //FUNCIONALIDADES ALERTA CARGA PMM WMS

    var dataSourceDetDCPW = new kendo.data.DataSource({
        transport: {
            read: onReadDCPW
        },
        schema: {
            model: {
                id: "BATCH",
                fields: {
                        BATCH: {type: "string"}, // number - string - date
                        CARGA: {type: "string"},
                        SUC_DESTINO: {type: "string"}, // number - string - date
                        DESC_SUC_DESTINO: {type: "string"},
                        PATENTE: {type: "string"},
                        FECHA_CIERRE: {type: "string"}
                    }
            }
        },
        pageSize: 15
    });
    var dataSourceDetErrDCPW = new kendo.data.DataSource({
        transport: {
            read: onReadDetErrDCPW
        },
        schema: {
            model: {
                id: "ERROR_CODE",
                fields: {
                        ERROR_CODE: {type: "string"}, // number - string - date
                        REJ_DESC: {type: "string"},
                        MNFST_NUMBER: {type: "string"}, // number - string - date
                        CARTON_NUMBER: {type: "string"},
                        FROM_LOC: {type: "string"},
                        TO_LOC: {type: "string"},
                        TRF_NUMBER: {type: "string"},
                        DATE_CREATED: {type: "string"}
                    }
            }
        },
        pageSize: 15
    });
    function intermiteciaDCPW(){
      $("#DCPWBox").toggleClass("bg-green");
      $("#DCPWBox").toggleClass("bg-red");
      $("#iconDCPW").toggleClass("glyphicon-ok");
      $("#iconDCPW").toggleClass("ion-android-alert");
      if(stopedDCPW == 0){
         runningDCPW = 1;
         setTimeout(intermiteciaDCPW, 500);
      }
      else{
        stopDCPW();
      }
    }
    function actualizarAlertaDCPW(){
        var numero = 1;
        $.ajax({
            beforeSend: function () {
                $("#iconDCPW").toggleClass("fa");
                $("#iconDCPW").toggleClass("fa-refresh");
                $("#iconDCPW").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconDCPW").toggleClass("fa");
                $("#iconDCPW").toggleClass("fa-refresh");
                $("#iconDCPW").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/pmm/errores/cantDifCargaPMMWMS',
            dataType: 'json',
            success: function(result){
                console.log(result);
                if(result > 0){
                    $("#nDCPW").html(result);
                    if(runningDCPW == 0){
                        stopedDCPW = 0;
                        intermiteciaDCPW();
                        
                    }
                    setTimeout(actualizarAlertaDCPW, 600000);
                }else{
                    setTimeout(actualizarAlertaDCPW, 600000);
                    if(stopedDCPW == 0 && runningDCPW == 1){
                        runningDCPW = 0;
                        stopedDCPW = 1
                    }
                }
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    function stopDCPW(){
        $("#DCPWBox").removeClass("bg-green");
        $("#DCPWBox").removeClass("bg-red");
        $("#iconDCPW").removeClass("glyphicon-ok");
        $("#iconDCPW").removeClass("ion-android-alert"); 
        $("#DCPWBox").addClass("bg-green");
        $("#iconDCPW").addClass("glyphicon-ok");
        $("#nDCPW").html('0');
    }
    $("#DCPWDetalles").click(function(){
        var popupdetalledpw = $("#POPUP_Detalle_DCPW");
        popupdetalledpw.data("kendoWindow").open();
        var grid = $("#gridDetDCPW");
        grid.data("kendoGrid").dataSource.read();
    });
    var ventana_detalle_dpw = $("#POPUP_Detalle_DCPW");
    ventana_detalle_dpw.kendoWindow({
        width: "1000px",
        height: "550px",
        title: "Cargas no enviadas a PMM",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();
    var ventana_detalleErr_dpw = $("#POPUP_DetERR_DCPW");
    ventana_detalleErr_dpw.kendoWindow({
        width: "1000px",
        height: "550px",
        title: "Detalle Error Carga",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();
    $("#gridDetDCPW").kendoGrid({
        autoBind: false,
        dataSource: dataSourceDetDCPW,
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
            {field: "BATCH",title: "BATCH",width: 70, filterable:false},
            {field: "CARGA",title: "CARGA",width:70, filterable:false},
            {field: "SUC_DESTINO",title: "ID SUC DESTINO",width:100,filterable: {multi: true, search: true}},
            {field: "DESC_SUC_DESTINO",title: "SUC DESTINO",width: 70,filterable: {multi: true, search: true}},
            {field: "PATENTE",title: "PATENTE",width:70,filterable: {multi: true, search: true}},
            {field: "FECHA_CIERRE",title: "FEC CIERRE",width:70,filterable: false}
        ]
    }).on("click", "tbody td", function(e) {
        var cell = $(e.currentTarget);
        var cellIndex = cell[0].cellIndex;
        var grid = $("#gridDetDCPW").data("kendoGrid");
        var column = grid.columns[1];
        var dataItem = grid.dataItem(cell.closest("tr"));
        carga = dataItem[column.field];
        var popupdeterrcargapmm = $("#POPUP_DetERR_DCPW");
        popupdeterrcargapmm.data("kendoWindow").open();
        var grid = $("#gridDetERRDCPW");
        grid.data("kendoGrid").dataSource.read();
    });
     $("#gridDetERRDCPW").kendoGrid({
        autoBind: false,
        dataSource: dataSourceDetErrDCPW,
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
            {field: "ERROR_CODE",title: "ERROR",width: 70, filterable:false},
            {field: "REJ_DESC",title: "DESC ERROR",width:70, filterable:false},
            {field: "MNFST_NUMBER",title: "CARGA",width:100,filterable: {multi: true, search: true}},
            {field: "CARTON_NUMBER",title: "CARTON",width: 100,filterable: {multi: true, search: true}},
            {field: "FROM_LOC",title: "ORIGEN",width:70,filterable: {multi: true, search: true}},
            {field: "TO_LOC",title: "DESTINO",width:70,filterable: false},
            {field: "TRF_NUMBER",title: "TRANSFERENCIA",width:70,filterable: false},
            {field: "DATE_CREATED ",title: "FEC CREACION",width:70,filterable: false}
        ]
    });
    function onReadDCPW(e){
         $.ajax({
            type: "POST",
            url: baseURL + 'alertas/pmm/errores/difCargaPMMWMS',
            dataType: 'json',
            success: function(result){
                if(result.length > 0){
                    if(runningDCPW == 0){
                        stopedDCPW = 0;
                        intermiteciaDCPW();
                    }
                    $("#nDCPW").html(result.length);
                }else{
                    stopedDCPW = 1;
                }
                e.success(result);
            },
            error: function(result){
                $("#error-modal").text("Ocurrio un error durante el proceso, intentelo nuevamente");
                $("#modal-danger").modal('show');
            }
        });
    }
    function onReadDetErrDCPW(e){
         $.ajax({
            type: "POST",
            url: baseURL + 'alertas/pmm/errores/detErrCarga',
            dataType: 'json',
            data: {carga: carga},
            success: function(result){
                e.success(result);
            },
            error: function(result){
                $("#error-modal").text("Ocurrio un error durante el proceso, intentelo nuevamente");
                $("#modal-danger").modal('show');
            }
        });
    }
    var ventana_filtrar = $("#POPUP_calendarioDispo");
    ventana_filtrar.kendoWindow({
        width: "300px",
        title: "Buscar Errores LPN Diposicion",
        visible: false,
        actions: [
            "Close"
        ]
    }).data("kendoWindow").center();
    $('#datepicker').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy'
    })
    $("#UpdELPND").click(function(){
        var ventanafiltrar = $("#POPUP_calendarioDispo");
        ventanafiltrar.data("kendoWindow").open();
    });
    $("#btnActualizarAlertDispo").click(function(){
        stopedELPND = 1;
        fecha = $("#datepicker").val();
        var grid = $("#gridELPND");
        grid.data("kendoGrid").dataSource.read();
        var ventanafiltrar = $("#POPUP_calendarioDispo");
        ventanafiltrar.data("kendoWindow").close();
        
    });
    function ReadErrDispo(e){
        $.ajax({
            beforeSend: function () {
                $("#iconELPND").toggleClass("fa-refresh");
                $("#iconELPND").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconELPND").toggleClass("fa-refresh");
                $("#iconELPND").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/pmm/errores/errLPND',
            data: {fecha: fecha},
            dataType: 'json',
            success: function(result){
                if(result.length > 0){
                    if(runningELPND == 0){
                        stopedELPND = 0;
                        intermiteciaELPND();
                    }
                    $("#nELPND").html(result.length);
                }else{
                    stopedELPND = 1;
                }
                e.success(result);
            },
            error: function(result){
                $("#error-modal").text("Ocurrio un error al cargar la grilla");
                $("#modal-danger").modal('show');
            }
        });
    }
    var dataSourceELPND = new kendo.data.DataSource({
        transport: {
            read: ReadErrDispo
        },
        schema: {
            model: {
                id: "ASN",
                fields: {
                        ASN: {type: "string"}, // number - string - date
                        LPN: {type: "string"},
                        SKU_ID: {type: "string"},
                        ORIG_QTY: {type: "string"},
                        VERIFICACION: {type: "string"}, // number - string - date
                        MENU: {type: "string"},
                        FECHA_ALMACENAJE: {type: "string"}
                    }
            }
        },
        pageSize: 200
    });
    var ventana_filtrar = $("#POPUP_ELPND");
    ventana_filtrar.kendoWindow({
        width: "1000px",
        height: "550px",
        title: "Errores LPN Disposicion",
        visible: false,
        actions: [
            "Close"
        ]
    }).data("kendoWindow").center();
    $("#gridELPND").kendoGrid({
        autoBind: false,
        dataSource: dataSourceELPND,
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
            {field: "ASN",title: "ASN",width: 70, filterable: {multi: true, search: true}},
            {field: "LPN",title: "LPN",width:90, filterable:false},
            {field: "SKU_ID",title: "SKU",width:70, filterable:false},
            {field: "ORIG_QTY",title: "UNIDADES",width:70, filterable:false},
            {field: "VERIFICACION",title: "FEC VERIFICACION",width:100,filterable: false},
            {field: "MENU",title: "MENU / N° TRAN",width: 70,filterable: false},
            {field: "FECHA_ALMACENAJE",title: "FEC DISP",width:70,filterable: false}
        ]
    });
    function intermiteciaELPND(){
      $("#ELPNDBox").toggleClass("bg-aqua");
      $("#ELPNDBox").toggleClass("bg-red");
      $("#iconELPND").toggleClass("fa-download");
      $("#iconELPND").toggleClass("ion-android-alert");
      if(stopedELPND == 0){
         runningELPND = 1;
         setTimeout(intermiteciaELPND, 500);
      }
      else{
        runningELPND = 0;
        stopELPND();
      }
    }
    function stopELPND(){
        $("#ELPNDBox").removeClass("bg-aqua");
        $("#ELPNDBox").removeClass("bg-red");
        $("#iconELPND").removeClass("fa-download");
        $("#iconELPND").removeClass("ion-android-alert"); 
        $("#ELPNDBox").addClass("bg-aqua");
        $("#iconELPND").addClass("fa-download");
        $("#nELPND").html('0');
    }
    $("#ELPNDetalles").click(function(){
        var ventanaErrLpnDispo = $("#POPUP_ELPND");
        ventanaErrLpnDispo.data("kendoWindow").open();
    });

    $("#toolbarELPND").kendoToolBar({
        items: [
            { type: "button", text: "Exportar", icon: "k-icon k-i-file-excel" ,click: ExportarELPND}
        ]
    });

    function ExportarELPND(){
       var rows = [{
            cells: [
               // The first cell.
              { value: "ASN" },
               // The second cell.
              { value: "LPN" },
              // The third cell.
              { value: "SKU_ID" },
              // The fifth cell.
              { value: "ORIG_QTY" },
              { value: "VERIFICACION" },
              { value: "MENU" },
              { value: "FECHA DISPOCISION" }
            ]
          }];
        var data = dataSourceELPND.data();
        for (var i = 0; i < data.length; i++){
          // Push single row for every record.
          rows.push({
            cells: [
              { value: data[i].ASN },
              { value: data[i].LPN },
              { value: data[i].SKU_ID },
              { value: data[i].ORIG_QTY },
              { value: data[i].VERIFICACION },
              { value: data[i].MENU },
              { value: data[i].FECHA_ALMACENAJE }
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
                { autoWidth: true }
              ],
              // The title of the sheet.
              title: "Errores Almacenaje",
              // The rows of the sheet.
              rows: rows
            }
          ]
        });
        // Save the file as an Excel file with the xlsx extension.
        kendo.saveAs({dataURI: workbook.toDataURL(), fileName: "Errores_LPN_Diposicion.xlsx"}); 
    }
    var ventana_filtrar = $("#POPUP_calendarioALM");
    ventana_filtrar.kendoWindow({
        width: "300px",
        title: "Buscar Errores Almacenaje",
        visible: false,
        actions: [
            "Close"
        ]
    }).data("kendoWindow").center();
    $('#datepickerALM').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy'
    })
    $("#UpdEALM").click(function(){
        var ventanafiltrar = $("#POPUP_calendarioALM");
        ventanafiltrar.data("kendoWindow").open();
    });
    $("#btnActualizarAlertAlm").click(function(){
        stopedEALM = 1;
        fecha = $("#datepickerALM").val();
        var grid = $("#gridEALM");
        grid.data("kendoGrid").dataSource.read();
        var ventanafiltrar = $("#POPUP_calendarioALM");
        ventanafiltrar.data("kendoWindow").close();
        
    });
    function ReadErrAlm(e){
        $.ajax({
            beforeSend: function () {
                $("#iconEALM").toggleClass("fa-refresh");
                $("#iconEALM").toggleClass("fa-spin");
            },
            complete: function () {
                $("#iconEALM").toggleClass("fa-refresh");
                $("#iconEALM").toggleClass("fa-spin");
            },
            type: "POST",
            url: baseURL + 'alertas/pmm/errores/errAlm',
            data: {fecha: fecha},
            dataType: 'json',
            success: function(result){
                if(result.length > 0){
                    if(runningEALM == 0){
                        stopedEALM = 0;
                        intermiteciaEALM();
                    }
                    $("#nEALM").html(result.length);
                }else{
                    stopedEALM = 1;
                }
                e.success(result);
            },
            error: function(result){
                $("#error-modal").text("Ocurrio un error al cargar la grilla");
                $("#modal-danger").modal('show');
            }
        });
    }
    var dataSourceEALM = new kendo.data.DataSource({
        transport: {
            read: ReadErrAlm
        },
        schema: {
            model: {
                id: "ASN",
                fields: {
                        ASN: {type: "string"}, // number - string - date
                        LPN: {type: "string"},
                        SKU_ID: {type: "string"},
                        ORIG_QTY: {type: "string"},
                        VERIFICACION: {type: "string"}, // number - string - date
                        MENU: {type: "string"},
                        FECHA_ALMACENAJE: {type: "string"}
                    }
            }
        },
        pageSize: 200
    });
    var ventana_filtrar = $("#POPUP_EALM");
    ventana_filtrar.kendoWindow({
        width: "1000px",
        height: "550px",
        title: "Errores Almacenaje",
        visible: false,
        actions: [
            "Close"
        ]
    }).data("kendoWindow").center();

    $("#toolbarEALM").kendoToolBar({
        items: [
            { type: "button", text: "Exportar", icon: "k-icon k-i-file-excel" ,click: ExportarEALM}
        ]
    });

    $("#gridEALM").kendoGrid({
        autoBind: false,
        dataSource: dataSourceEALM,
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
            {field: "ASN",title: "ASN",width: 70, filterable: {multi: true, search: true}},
            {field: "LPN",title: "LPN",width:70, filterable:false},
            {field: "SKU_ID",title: "SKU",width:70, filterable:false},
            {field: "ORIG_QTY",title: "UNIDADES",width:70, filterable:false},
            {field: "VERIFICACION",title: "FEC VERIFICACION",width:100,filterable: false},
            {field: "MENU",title: "MENU / N° TRAN",width: 70,filterable: false},
            {field: "FECHA_ALMACENAJE",title: "FEC DISP",width:70,filterable: false}
        ]
    });
    function intermiteciaEALM(){
      $("#EALMBox").toggleClass("bg-aqua");
      $("#EALMBox").toggleClass("bg-red");
      $("#iconEALM").toggleClass("fa-download");
      $("#iconEALM").toggleClass("ion-android-alert");
      if(stopedEALM == 0){
         runningEALM = 1;
         setTimeout(intermiteciaEALM, 500);
      }
      else{
        runningEALM = 0;
        stopEALM();
      }
    }
    function stopEALM(){
        $("#EALMBox").removeClass("bg-aqua");
        $("#EALMBox").removeClass("bg-red");
        $("#iconEALM").removeClass("fa-download");
        $("#iconEALM").removeClass("ion-android-alert"); 
        $("#EALMBox").addClass("bg-aqua");
        $("#iconEALM").addClass("fa-download");
        $("#nELPND").html('0');
    }
    $("#EALMDetalles").click(function(){
        var ventanaErrLpnDispo = $("#POPUP_EALM");
        ventanaErrLpnDispo.data("kendoWindow").open();
    });

    function ExportarEALM(){
        var rows = [{
            cells: [
               // The first cell.
              { value: "ASN" },
               // The second cell.
              { value: "LPN" },
              // The third cell.
              { value: "SKU_ID" },
              // The fifth cell.
              { value: "ORIG_QTY" },
              { value: "VERIFICACION" },
              { value: "MENU" },
              { value: "FECHA_ALMACENAJE" }
            ]
          }];
        var data = dataSourceEALM.data();
        for (var i = 0; i < data.length; i++){
          // Push single row for every record.
          rows.push({
            cells: [
              { value: data[i].ASN },
              { value: data[i].LPN },
              { value: data[i].SKU_ID },
              { value: data[i].ORIG_QTY },
              { value: data[i].VERIFICACION },
              { value: data[i].MENU },
              { value: data[i].FECHA_ALMACENAJE }
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
                { autoWidth: true }
              ],
              // The title of the sheet.
              title: "Errores Almacenaje",
              // The rows of the sheet.
              rows: rows
            }
          ]
        });
        // Save the file as an Excel file with the xlsx extension.
        kendo.saveAs({dataURI: workbook.toDataURL(), fileName: "ErroresAlmacenaje.xlsx"});
    }
});