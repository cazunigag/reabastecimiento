$(document).ready(function(){

  	var sku = "";

  	var asns = [];

	var dataSource = new kendo.data.DataSource({
        transport: {
            read: onRead
        },
        schema: {
            model: {
                id: "SHPMT_NBR",
                fields: {
                        SHPMT_NBR: {type: "string"},
                        CREATE_DATE_TIME: {type: "string"},
                        VERF_DATE_TIME: {type: "string"},
                        UNITS_RCVD: {type: "string"},
                        CASES_RCVD: {type: "string"},
                        INSERT_DATE_TIME: {type: "string"},
                        INTERNAL_STATE: {type: "string"},
                        DESC_ESTADO: {type: "string"},
                        INTERNAL_STATE_LPN: {type: "string"},
                        VIPME_COD_EST_K_ORIGEN: {type: "string"},
                        VIPME_COD_EST_K_DESTINO: {type: "string"}
                    }
            }
        },
        pageSize: 100
    });

    var dataSourceDetASN = new kendo.data.DataSource({
        transport: {
            read: onReadDetASN
        },
        schema: {
            model: {
                id: "CASE_NBR",
                fields: {
                        CASE_NBR: {type: "string"},
                        SHPMT_NBR: {type: "string"},
                        INSERT_DATE_TIME: {type: "string"},
                        INTERNAL_STATE: {type: "string"},
                        INTERNAL_DATE_TIME: {type: "string"}
                    }
            }
        },
        pageSize: 100
    });

    var dataSourceDetInterfaz = new kendo.data.DataSource({
        transport: {
            read: onReadDetInterfaz
        },
        schema: {
            model: {
                id: "COR_MVM",
                fields: {
                        COR_MVM: {type: "string"},
                        COC_TRN: {type: "string"},
                        TRAN_NBR: {type: "string"},
                        ASN: {type: "string"},
                        TRAN_TYPE: {type: "string"},
                        TRAN_CODE: {type: "string"},
                        ACT_TRN: {type: "string"},
                        RSN_TRN: {type: "string"},
                        EST_ORE: {type: "string"},
                        GLS_ORE: {type: "string"},
                        EST_DTN: {type: "string"},
                        GLS_DTN: {type: "string"},
                        FCH_ORE: {type: "string"},
                        FCH_DTN: {type: "string"},
                        COR_DET: {type: "string"},
                        COR_MV2: {type: "string"},
                        COC_VAL: {type: "string"},
                        DES_VAL: {type: "string"},
                        DML: {type: "string"},
                        NRO: {type: "string"},
                        GLS: {type: "string"},
                        FCH: {type: "string"},
                        FCH_SIS: {type: "string"},
                        AHORA: {type: "string"}
                    }
            }
        },
        pageSize: 100
    });

    $("#toolbar").kendoToolBar({
        items: [
            { type: "button", text: "Reprocesar", icon: "k-icon k-i-refresh" ,click: Reprocesar},
            { type: "button", text: "Detalle ASN", icon: "k-icon k-i-search" ,click: DetalleASN},
            { type: "button", text: "Detalle Err Interfaz", icon: "k-icon k-i-search" ,click: DetalleErrInterfaz}
        ]
    });
    /*
     $("#toolbarDetalle").kendoToolBar({
        items: [
            { type: "button", text: "Exportar", icon: "k-icon k-i-file-excel" ,click: ExportarDetalle}
        ]
    });*/

    $("#grid").kendoGrid({
        height: "500px", 
        width: "600px",
        dataSource: dataSource,
        sortable: true, 
        filterable: true,
        scrollable: true,
        /*dataBound: function(e) {
			color();
		},*/
		    change: function (e, args) {
                    asns = [];
                    var rows = e.sender.select();
                    rows.each(function(e) {
                        var grid = $("#grid").data("kendoGrid");
                        var item = grid.dataItem(this);
                        asns.push({SHPMT_NBR: item.SHPMT_NBR, INTERNAL_STATE: item.INTERNAL_STATE});
                    }) 
        },
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [
        	{selectable: true, width: "15px" },
            {field: "SHPMT_NBR",title: "ASN",width: 70, filterable:{multi: true, search: true}},
            {field: "CREATE_DATE_TIME",title: "FECHA CREACION",width: 70, filterable:{multi: true, search: true}},
            {field: "VERF_DATE_TIME",title: "FECHA VERIFICACION",width: 70, filterable:{multi: true, search: true}},
            {field: "UNITS_RCVD",title: "UNIDADES RECIVIDAS",width: 70, filterable:false},
            {field: "CASES_RCVD",title: "CAJAS RECIVIDAS",width: 70, filterable:false},
            {field: "INSERT_DATE_TIME",title: "FECHA INSERICION",width: 70,filterable:{multi: true, search: true}},
            {field: "INTERNAL_STATE",title: "ESTADO INTERNO",width: 70,filterable:{multi: true, search: true}},
            {field: "DESC_ESTADO",title: "DESCRIPCION ESTADO",width: 70,filterable:{multi: true, search: true}},
            {field: "INTERNAL_STATE_LPN",title: "ESTADO LPN",width: 70,filterable:{multi: true, search: true}},
            {field: "VIPME_COD_EST_K_ORIGEN",title: "ESTADO INTERFAZ ORIGEN",width: 70,filterable:{multi: true, search: true}},
            {field: "VIPME_COD_EST_K_DESTINO",title: "ESTADO INTERFAZ DESTINO",width: 70,filterable:{multi: true, search: true}}
        ]

    });

    $("#gridDetalle").kendoGrid({
        autoBind: false,
        height: "500px", 
        width: "600px",
        dataSource: dataSourceDetASN,
        sortable: true, 
        filterable: true,
        scrollable: true,
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [
            {field: "CASE_NBR",title: "LPN",width: 70, filterable:{multi: true, search: true}},
            {field: "SHPMT_NBR",title: "ASN",width: 70, filterable:{multi: true, search: true}},
            {field: "INSERT_DATE_TIME",title: "FECHA INSERICION",width: 70, filterable:false},
            {field: "INTERNAL_STATE",title: "ESTADO",width: 70, filterable:false},
            {field: "INTERNAL_DATE_TIME",title: "FECHA INTERNA",width: 70, filterable:false}
        ]

    });

    $("#gridDetalleInterfaz").kendoGrid({
        autoBind: false,
        height: "100%", 
        width: "600px",
        dataSource: dataSourceDetInterfaz,
        sortable: true, 
        filterable: true,
        scrollable: true,
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [
            {field: "COR_MVM",title: "COR_MVM",width: 70, filterable:{multi: true, search: true}},
            {field: "COC_TRN",title: "COC_TRN",width: 70, filterable:{multi: true, search: true}},
            {field: "TRAN_NBR",title: "TRAN_NBR",width: 70, filterable:false},
            {field: "ASN",title: "ASN",width: 70, filterable:false},
            {field: "TRAN_TYPE",title: "TRAN_TYPE",width: 70, filterable:false},
            {field: "TRAN_CODE",title: "TRAN_CODE",width: 70, filterable:false},
            {field: "ACT_TRN",title: "ACT_TRN",width: 70, filterable:false},
            {field: "RSN_TRN",title: "RSN_TRN",width: 70, filterable:false},
            {field: "EST_ORE",title: "EST_ORE",width: 70, filterable:false},
            {field: "GLS_ORE",title: "GLS_ORE",width: 70, filterable:false},
            {field: "EST_DTN",title: "EST_DTN",width: 70, filterable:false},
            {field: "GLS_DTN",title: "GLS_DTN",width: 70, filterable:false},
            {field: "FCH_ORE",title: "FCH_ORE",width: 70, filterable:false},
            {field: "FCH_DTN",title: "FCH_DTN",width: 70, filterable:false},
            {field: "COR_DET",title: "COR_DET",width: 70, filterable:false},
            {field: "COR_MV2",title: "COR_MV2",width: 70, filterable:false},
            {field: "COC_VAL",title: "COC_VAL",width: 70, filterable:false},
            {field: "DES_VAL",title: "DES_VAL",width: 70, filterable:false},
            {field: "DML",title: "DML",width: 70, filterable:false},
            {field: "NRO",title: "NRO",width: 70, filterable:false},
            {field: "GLS",title: "GLS",width: 70, filterable:false},
            {field: "FCH",title: "FCH",width: 70, filterable:false},
            {field: "FCH_SIS",title: "FCH_SIS",width: 70, filterable:false},
            {field: "AHORA",title: "AHORA",width: 70, filterable:false}
        ]

    });

    var ventana_detalle_asn = $("#POPUP_Detalle_ASN");
    ventana_detalle_asn.kendoWindow({
        width: "750px",
        height: "550px",
        title: "Detalle ASN",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();

    var ventana_detalle_interfaz = $("#POPUP_Detalle_Interfaz");
    ventana_detalle_interfaz.kendoWindow({
        width: "750px",
        height: "550px",
        title: "Detalle Errores Interfaz",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").maximize();

    function onRead(e){
    	$.ajax({
            type: "POST",
            url: baseURL + 'aseguramientoCalidad/recepcion/read',
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }

    function onReadDetASN(e){
      $.ajax({
            type: "POST",
            url: baseURL + 'aseguramientoCalidad/recepcion/detalle',
            data: {data: JSON.stringify(asns)},
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }

     function onReadDetInterfaz(e){
      $.ajax({
            type: "POST",
            url: baseURL + 'aseguramientoCalidad/recepcion/interfaz',
            data: {data: JSON.stringify(asns)},
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }

    function Exportar(){
        var rows = [{
            cells: [
              { value: "SKU" },
              { value: "SKU LARGO" },
              { value: "DISPONIBLE BT" },
              { value: "DESCRIPCION SKU" },
              { value: "DEPTO" },
              { value: "DESCRIPCION DEPTO" },
              { value: "STOCK ACTIVO" },
              { value: "STOCK RESERVA" },
              { value: "BLOQUEO WMS" },
              { value: "PUBLICADO TV" }
            ]
          }];
        var data = dataSource.data();
        for (var i = 0; i < data.length; i++){
          // Push single row for every record.
          rows.push({
            cells: [
              { value: data[i].SKU_ID },
              { value: data[i].SKU_BRCD },
              { value: data[i].DISP_BT },
              { value: data[i].DESCRIPCION },
              { value: data[i].DEPTO },
              { value: data[i].DEPTO_DESC },
              { value: data[i].ACTIVO },
              { value: data[i].RESERVA },
              { value: data[i].BLOQUEO },
              { value: data[i].RESPUESTA }
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
                { autoWidth: true }
              ],
              // The title of the sheet.
              title: "Errores LPN Modificados",
              // The rows of the sheet.
              rows: rows
            }
          ]
        });
        // Save the file as an Excel file with the xlsx extension.
        kendo.saveAs({dataURI: workbook.toDataURL(), fileName: "Sku sin Stock WMS.xlsx"});
    }

    function ExportarDetalle(){
        var rows = [{
            cells: [
              { value: "LPN" },
              { value: "UBICACION" },
              { value: "CODIGO BLOQUEO" },
              { value: "ESTADO" },
              { value: "DESC ESTADO" },
              { value: "FECHA BLOQUEO" },
              { value: "STOCK" }
            ]
          }];
        var data = dataSourceDetalle.data();
        for (var i = 0; i < data.length; i++){
          // Push single row for every record.
          rows.push({
            cells: [
              { value: data[i].CASE_NBR },
              { value: data[i].DSP_LOCN },
              { value: data[i].INVN_LOCK_CODE },
              { value: data[i].STAT_CODE },
              { value: data[i].CODE_DESC },
              { value: data[i].MOD_DATE_TIME },
              { value: data[i].STOCK }
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
                { autoWidth: true },              ],
              // The title of the sheet.
              title: "Detalle",
              // The rows of the sheet.
              rows: rows
            }
          ]
        });
        // Save the file as an Excel file with the xlsx extension.
        kendo.saveAs({dataURI: workbook.toDataURL(), fileName: "Detalle Sku: "+sku+".xlsx"});
    }

   /* function color(){
    	var grid = $("#grid").data("kendoGrid");
        var data = grid.dataSource.data();
        $.each(data, function (i, row) {
            if (row.INTERNAL_STATE == 11 ){
                $('tr[data-uid="' + row.uid + '"] ').css("background-color", "red");
                $('tr[data-uid="' + row.uid + '"] ').css("color", "white");
            }
        })
    }*/

    function Reprocesar(){
    	var data = JSON.stringify(asns);
        if(Array.isArray(asns) && asns.length != 0){
            $.ajax({
                type: "POST",
                url: baseURL + 'aseguramientoCalidad/recepcion/reprocesar',
                data:{ data: data},
                dataType: 'json',
                success: function(result){
                    if(result == 0){                       
                        $("#success-modal").text("ASN Reprocesado Correctamente");
                        $("#modal-success").modal('show');
                        asns = [];
                        var grid = $("#grid").data("kendoGrid");
                        grid.dataSource.read();
                    }
                    else{
                        $("#error-modal").text("Error al reporocesar ASN");
                        $("#modal-danger").modal('show');
                        asns = [];
                    }
                },
                error: function(xhr){
                    $("#error-modal").text("Ocurrio un error con la funcion");
                    $("#modal-danger").modal('show');
                }
            });
        }
        else{
            $("#error-modal").text("Debe seleccionar al menos un ASN para reprocesar");
            $("#modal-danger").modal('show');
        }
    }

    function DetalleASN(){
      if(Array.isArray(asns) && asns.length != 0){
            var popupdetalleasn = $("#POPUP_Detalle_ASN");
            popupdetalleasn.data("kendoWindow").open();
            var grid = $("#gridDetalle");
            grid.data("kendoGrid").dataSource.read();
        }
        else{
            $("#error-modal").text("Debe seleccionar al menos un ASN");
            $("#modal-danger").modal('show');
        }
    }

    function DetalleErrInterfaz(){
      if(Array.isArray(asns) && asns.length != 0){
            var popupdetalleinterfaz = $("#POPUP_Detalle_Interfaz");
            popupdetalleinterfaz.data("kendoWindow").open();
            var grid = $("#gridDetalleInterfaz");
            grid.data("kendoGrid").dataSource.read();
        }
        else{
            $("#error-modal").text("Debe seleccionar al menos un ASN");
            $("#modal-danger").modal('show');
        }
    }

});