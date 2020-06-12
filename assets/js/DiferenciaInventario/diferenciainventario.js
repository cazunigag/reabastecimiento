$(document).ready(function(){
	var data_grafico = [];
	var fecha;
	var dataSource = new kendo.data.DataSource({
        transport: {
            read: onRead
        },
        schema: {
            model: {
                id: "FECHA",
                fields: {
                        FECHAS: {type: "string", editable: false}, // number - string - date
                        MAYOR_PMM: {type: "string", editable: false},
                        MAYOR_WMS: {type: "string", editable: false}
                    }
            }
        },
        pageSize: 50
    });

    var dataSourceDiffPMM = new kendo.data.DataSource({
        transport: {
            read: onReadDiffPMM
        },
        schema: {
            model: {
                id: "FECHA",
                fields: {
                        FECHA: {type: "string", editable: false}, // number - string - date
                        PRD_LVL_NUMBER: {type: "string", editable: false},
                        MAYOR_PMM: {type: "number", editable: false}
                    }
            }
        },
        pageSize: 50
    });

    var dataSourceDiffWMS = new kendo.data.DataSource({
        transport: {
            read: onReadDiffWMS
        },
        schema: {
            model: {
                id: "FECHA",
                fields: {
                        FECHA: {type: "string", editable: false}, // number - string - date
                        PRD_LVL_NUMBER: {type: "string", editable: false},
                        MAYOR_WMS: {type: "number", editable: false}
                    }
            }
        },
        pageSize: 50
    });

    $("#toolbar").kendoToolBar({
        items: [
            { type: "button", text: "Graficar", icon: "k-icon k-i-arrows-dimensions" ,click: Graficar},
        ]
    });

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
            {field: "FECHA",title: "FECHA",width: 140, filterable:false, resizable:false, height: 80},
            {field: "MAYOR_PMM",title: "MAYOR PMM",width:70,filterable:false},
            {field: "MAYOR_WMS",title: "MAYOR WMS",width:70,filterable:false}
        ]
    }).on("click", "tbody td", function(e) {
      	var cell = $(e.currentTarget);
        var cellIndex = cell[0].cellIndex;
        var grid = $("#grid").data("kendoGrid");
        var column = grid.columns[cellIndex];
        var dataItem = grid.dataItem(cell.closest("tr"));

      	if(column.field == 'MAYOR_WMS'){

      		fecha = dataItem[grid.columns[0].field];
      		console.log(fecha);
      		var grid = $("#gridDiffWMS");
       		grid.data("kendoGrid").dataSource.read();
      		var ventana_mayorWMS = $("#POPUP_mayorWMS");
        	ventana_mayorWMS.data("kendoWindow").open();

      	}else if(column.field == 'MAYOR_PMM'){

      		fecha = dataItem[grid.columns[0].field];
      		console.log(fecha);
      		var grid = $("#gridDiffPMM");
       		grid.data("kendoGrid").dataSource.read();
      		var ventana_mayorPMM = $("#POPUP_mayorPMM");
        	ventana_mayorPMM.data("kendoWindow").open();

      	}
    });

    $("#gridDiffPMM").kendoGrid({
    	autoBind: false,
        editable: true,
        dataSource: dataSourceDiffPMM,
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
            {field: "FECHA",title: "FECHA",width: 140, filterable:false, resizable:false, height: 80},
            {field: "PRD_LVL_NUMBER",title: "SKU PRODUCTO",width:70,filterable:false},
            {field: "MAYOR_PMM",title: "DIFERENCIA CON WMS",width:70,filterable:false}
        ]
    });

    $("#gridDiffWMS").kendoGrid({
    	autoBind: false,
        editable: true,
        dataSource: dataSourceDiffWMS,
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
            {field: "FECHA",title: "FECHA",width: 140, filterable:false, resizable:false, height: 80},
            {field: "PRD_LVL_NUMBER",title: "SKU PRODUCTO",width:70,filterable:false},
            {field: "MAYOR_WMS",title: "DIFERENCIA CON PMM",width:70,filterable:false}
        ]
    });

    function onRead(e){
    	$.ajax({
          url: baseURL + 'difinvn/read',
          type: 'GET',
          dataType: 'json',
          processData: false,
          success: function(result){
            if(result.length == 0){
                $("#error-modal").text("Ocurrio un error al cargar la pagina");
                $("#modal-danger").modal('show');
            }else if(result.length != 0){
            	result.forEach(function(element){
            		data_grafico.push({y: element.FECHA.toString(), MAYOR_PMM: element.MAYOR_PMM, MAYOR_WMS: element.MAYOR_WMS, SIN_DIFERENCIA: element.SIN_DIFERENCIA});

            	});
            	console.log(data_grafico);
                e.success(result);
            }
          },
          error: function(result){
            console.log('error');
          }
        });
    }
    function onReadDiffPMM(e){
    	 $.ajax({
            type: "POST",
            url: baseURL + 'difinvn/readDiffPMM',
            data: {fecha: fecha},
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }

    function onReadDiffWMS(e){
    	 $.ajax({
            type: "POST",
            url: baseURL + 'difinvn/readDiffWMS',
            data: {fecha: fecha},
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }

    var ventana_grafico = $("#POPUP_Grafico");
    ventana_grafico.kendoWindow({
        width: "900px",
        height: "550px",
        title: "Grafico Diferencia Inventario",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();

    var ventana_mayorWMS = $("#POPUP_mayorWMS");
    ventana_mayorWMS.kendoWindow({
        width: "900px",
        height: "550px",
        title: "Detalle Mayor WMS",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();

    var ventana_mayorPMM = $("#POPUP_mayorPMM");
    ventana_mayorPMM.kendoWindow({
        width: "900px",
        height: "550px",
        title: "Detalle Mayor PMM",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();

    var ctx = $('#line-chart');

    


    function Graficar(){
    	$("#line-chart").kendoChart({
            dataSource: {
                transport: {
                    read: onRead
                },
                sort: {
                    field: "year",
                    dir: "asc"
                }
            },
            legend: {
                    position: "bottom"
            },
            title: {
                text: 'Diferencia Inventario'
            },
            legend: {
                position: "top"
            },
            seriesDefaults: {
                type: "line",
                style: "smooth"
            },
            series: [{
                field: "MAYOR_PMM",
                name: "MAYOR PMM",
                color: "red"
            }, {
                field: "MAYOR_WMS",
                name: "MAYOR WMS",
                color: "blue"
            }],
            categoryAxis: {
            	field: "FECHA",
                crosshair: {
                    visible: true
                },
                labels:{
                    rotation: 315
                }
            },
            valueAxis: {
                labels: {
                    format: "N0"
                },
                majorUnit: 10000
            },
            tooltip: {
                visible: true,
                shared: true,
                format: "N0"
            }
        });
    	var ventana_grafico = $("#POPUP_Grafico");
        ventana_grafico.data("kendoWindow").open();
    }
});

