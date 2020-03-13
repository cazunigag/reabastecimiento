$(document).ready(function(){
  var sku = "";

	var dataSource = new kendo.data.DataSource({
        transport: {
            read: onRead
        },
        schema: {
            model: {
                id: "SKU_ID",
                fields: {
                        SKU_ID: {type: "string"},
                        SKU_BRCD: {type: "string"},
                        DISP_BT: {type: "string"},
                        DESCRIPCION: {type: "string"},
                        DEPTO: {type: "string"},
                        DEPTO_DESC: {type: "string"},
                        ACTIVO: {type: "string"},
                        RESERVA: {type: "string"},
                        BLOQUEO: {type: "string"},
                        RESPUESTA: {type: "string"}
                    }
            }
        },
        pageSize: 100
    });

    var dataSourceDetalle = new kendo.data.DataSource({
          transport: {
              read: onReadDetalle
          },
          schema: {
              model: {
                  id: "CASE_NBR",
                  fields: {
                          CASE_NBR: {type: "string"},
                          DSP_LOCN: {type: "string"},
                          INVN_LOCK_CODE: {type: "string"},
                          STAT_CODE: {type: "string"},
                          CODE_DESC: {type: "string"},
                          MOD_DATE_TIME: {type: "string"},
                          STOCK: {type: "string"}
                      }
              }
          },
          pageSize: 100
    });

    $("#toolbar").kendoToolBar({
        items: [
            { type: "button", text: "Exportar", icon: "k-icon k-i-file-excel" ,click: Exportar}
        ]
    });
     $("#toolbarDetalle").kendoToolBar({
        items: [
            { type: "button", text: "Exportar", icon: "k-icon k-i-file-excel" ,click: ExportarDetalle}
        ]
    });

    $("#grid").kendoGrid({
        height: "500px", 
        width: "600px",
        selectable: 'row',
        dataSource: dataSource,
        sortable: true, 
        filterable: true,
        scrollable: true,
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [
            {field: "SKU_ID",title: "SKU",width: 70, filterable:{multi: true, search: true}},
            {field: "SKU_BRCD",title: "SKU LARGO",width: 70, filterable:{multi: true, search: true}},
            {field: "DISP_BT",title: "DISPONIBLE BT",width: 70, filterable:false},
            {field: "DESCRIPCION",title: "DESCRIPCION SKU",width: 70, filterable:false},
            {field: "DEPTO",title: "DEPTO",width: 70, filterable:{multi: true, search: true}},
            {field: "DEPTO_DESC",title: "DESCRIPCION DEPTO",width: 70,filterable:{multi: true, search: true}},
            {field: "ACTIVO",title: "STOCK ACTIVO",width: 70, filterable:false},
            {field: "RESERVA",title: "STOCK RESERVA",width: 70, filterable:false},
            {field: "BLOQUEO",title: "BLOQUEO WMS",width: 70, filterable:{multi: true, search: true}},
            {field: "RESPUESTA",title: "PUBLICADO TV",width: 70, filterable:{multi: true, search: true}}
        ]
    }).on("click", "tbody td", function(e) {
        var cell = $(e.currentTarget);
        var cellIndex = cell[0].cellIndex;
        var grid = $("#grid").data("kendoGrid");
        var column = grid.columns[cellIndex];
        var dataItem = grid.dataItem(cell.closest("tr"));
        sku = dataItem[grid.columns[0].field];

        var popupdetallesku = $("#POPUP_Detalle_Sku");
        popupdetallesku.data("kendoWindow").title("Detalle SKU: "+sku);
        popupdetallesku.data("kendoWindow").open();
        var grid = $("#gridDetalleSku");
        grid.data("kendoGrid").dataSource.read();

    });

    var ventana_detalle_sku = $("#POPUP_Detalle_Sku");
    ventana_detalle_sku.kendoWindow({
        width: "1000px",
        height: "550px",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();

     $("#gridDetalleSku").kendoGrid({
        autoBind: false,
        dataSource: dataSourceDetalle,
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
            {field: "CASE_NBR",title: "LPN",width: 120, filterable:{multi: true, search: true}},
            {field: "DSP_LOCN",title: "UBICACION",width: 70, filterable:false},
            {field: "INVN_LOCK_CODE",title: "CODIGO BLOQUEO",width: 70, filterable:{multi: true, search: true}},
            {field: "STAT_CODE",title: "ESTADO",width: 40, filterable:{multi: true, search: true}},
            {field: "CODE_DESC",title: "DESC ESTADO",width: 70, filterable:{multi: true, search: true}},
            {field: "MOD_DATE_TIME",title: "FECHA BLOQUEO",width: 70, filterable:false},
            {field: "STOCK",title: "STOCK",width: 70, filterable:false}
        ]
    })

    function onRead(e){
    	$.ajax({
            type: "POST",
            url: baseURL + 'DiffBT-WMS/read',
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }

    function onReadDetalle(e){
      $.ajax({
            type: "POST",
            data: {sku: sku},
            url: baseURL + 'DiffBT-WMS/detalle',
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
});