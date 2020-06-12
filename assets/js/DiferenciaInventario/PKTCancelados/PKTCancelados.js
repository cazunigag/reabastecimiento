$(document).ready(function(){

	var fecha = "";

	var dataSource = new kendo.data.DataSource({
        transport: {
            read: onRead
        },
        schema: {
            model: {
                id: "PKT",
                fields: {
                        BTATB_FCH_VTA: {type: "string", editable: false}, // number - string - date
                        TOTAL_PKT_CREADOS_BT: {type: "string", editable: false},
                        TOTAL_PKT_CANCELADOS_WMS: {type: "string", editable: false}
                    }
            }
        },
        pageSize: 50
    });

    var dataSourceDetalle = new kendo.data.DataSource({
        transport: {
            read: onReadDetalle
        },
        schema: {
            model: {
                id: "PKT",
                fields: {
                        FCHVTA: {type: "string", editable: false}, // number - string - date
                        NROPKT: {type: "string", editable: false},
                        NROCUD: {type: "string", editable: false},
                        CODSKU: {type: "string", editable: false},
                        CODVTA: {type: "string", editable: false},
                        CANTID: {type: "string", editable: false},
                        FECHA_MODIFICACION: {type: "string", editable: false},
                        TICKETAURIS: {type: "string", editable: false}
                    }
            }
        },
        pageSize: 50
    });

    var ventana_Detalle = $("#POPUP_Detalle");
    ventana_Detalle.kendoWindow({
        width: "700PX",
        height: "500PX",
        visible: false,
        actions: [
            "Close"     
        ]
    }).data("kendoWindow").maximize();

    $("#grid").kendoGrid({
		selectable: "cell", 	
        editable: true,
        dataSource: dataSource,
        height: "530px", 
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
            {field: "BTATB_FCH_VTA",title: "FECHA",width: 70, filterable:false, resizable:false, height: 80},
            {field: "TOTAL_PKT_CREADOS_BT",title: "PKTS CREADOS BT",width:70,filterable:false},
            {field: "TOTAL_PKT_CANCELADOS_WMS",title: "PKTS CANCELADOS WMS",width:70,filterable:false}
        ]
    }).on("click", "tbody td", function(e) {
      	var cell = $(e.currentTarget);
        var cellIndex = cell[0].cellIndex;
        var grid = $("#grid").data("kendoGrid");
        var column = grid.columns[cellIndex];
        var dataItem = grid.dataItem(cell.closest("tr"));
        fecha = dataItem[grid.columns[0].field];

        console.log(fecha);

        var popupdetallepkt = $("#POPUP_Detalle");
        popupdetallepkt.data("kendoWindow").title('Detalle PKTs Cancelados: '+fecha);
        popupdetallepkt.data("kendoWindow").open();
        var grid = $("#gridDetalle");
        grid.data("kendoGrid").dataSource.read();
    });

    $("#gridDetalle").kendoGrid({
    	autoBind: false,
		selectable: "cell", 	
        editable: true,
        dataSource: dataSourceDetalle,
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
            {field: "FCHVTA",title: "FECHA VENTA",width: 70, filterable:false, resizable:false, height: 80},
            {field: "NROPKT",title: "PKT",width:70,filterable:false},
            {field: "NROCUD",title: "CUD",width:70,filterable:false},
            {field: "CODSKU",title: "SKU",width:70,filterable:false},
            {field: "CODVTA",title: "COD VENTA",width:70,filterable:false},
            {field: "CANTID",title: "CANTIDAD VENTA",width:100,filterable:false},
            {field: "FECHA_MODIFICACION",title: "FEC CANCELACION",width:70,filterable:false},
            {field: "TICKETAURIS",title: "TICKET AURIS",width:70,filterable:false}
        ]
    });

    function onRead(e){
    	$.ajax({
          url: baseURL + 'PKTCancelados/read',
          type: 'GET',
          dataType: 'json',
          processData: false,
          success: function(result){
            if(result.length == 0){
                $("#error-modal").text("Ocurrio un error al cargar la pagina");
                $("#modal-danger").modal('show');
            }else if(result.length != 0){
                e.success(result);
            }
          },
          error: function(result){
            console.log('error');
          }
        });
    }

    function onReadDetalle(e){
    	$.ajax({
          url: baseURL + 'PKTCancelados/detalle',
          type: 'POST',
          data: {fecha: JSON.stringify(fecha)},
          dataType: 'json',
          success: function(result){
            if(result.length == 0){
                $("#error-modal").text("Ocurrio un error al cargar la pagina");
                $("#modal-danger").modal('show');
            }else if(result.length != 0){
                e.success(result);
            }
          },
          error: function(result){
            console.log('error');
          }
        });
    }
});