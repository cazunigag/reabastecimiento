$(document).ready(function(){

	var data1 = "";

	var dataSource = new kendo.data.DataSource({
        transport: {
            read: onRead
        },
        schema: {
            model: {
                id: "PASILLO",
                fields: {
                        PASILLO: {type: "string"}, // number - string - date
                        CARTON_TYPE: {type: "string"}
                    }
            }
        },
        pageSize: 100
    });
    var dataSourceActCT = new kendo.data.DataSource({
        transport: {
            read: onReadActCT
        },
        schema: {
            model: {
                id: "CARTON_TYPE",
                fields: {
                        CARTON_TYPE: {type: "string"}, // number - string - date
                        TOTAL: {type: "string"}
                    }
            }
        },
        pageSize: 100
    });

    var dataSource2  = new kendo.data.DataSource({
		transport:{
			read: onReadCB
		}
	});

    $("#selectCartonType").kendoComboBox({
		dataSource: dataSource2,
		dataTextField: "CARTON_TYPE",
		dataValueField: "CARTON_TYPE"
	});

	var ventana_actual_ct = $("#POPUP_Actual_CT");
    ventana_actual_ct.kendoWindow({
        width: "400px",
        height: "300px",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();

    var ventana_cartontype= $("#POPUP_CartonType");
	ventana_cartontype.kendoWindow({
	    width: "350px",
	    height: "230px",
	    visible: false,
	    position:{
	        top: 45,
	        left:0
	    },
	    actions: [
	        "Minimize",
	        "Maximize",
	        "Close"
	    ]
	}).data("kendoWindow").center();

	$("#gridCT").kendoGrid({
        dataSource: dataSource,
        height: "545px", 
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
            {field: "PASILLO",title: "PASILLO",width: 70, filterable:false, resizable:false, height: 80},
            {command: { text: "Ver", click: ActCartonType}, title: "CARTON TYPE ACTUAL", width: "50px"},
            {field: "CARTON_TYPE",title: "CARTON TYPE CONFIGURADO",width:180,filterable: false},
            {command: { text: "CONFIGURAR", click: ConfCartonType}, title: "", width: "50px"}
        ]
    });
    $("#gridActCt").kendoGrid({
        dataSource: dataSourceActCT,
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
        columns: [ // Columnas a Listar
            {field: "CARTON_TYPE",title: "CARTON TYPE",width: 70, filterable:false, resizable:false, height: 80},
            {field: "TOTAL",title: "CANTIADAD ARTICULOS",width:180,filterable: false}
        ]
    });

    function onRead(e){
    	$.ajax({
            type: "POST",
            url: baseURL + 'cartontype/data',
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                $("#error-modal").text("Ocurrio un error al cargar la pagina");
                $("#modal-danger").modal('show');
            }
        });
    }
    function onReadActCT(e){
    	$.ajax({
            type: "POST",
            url: baseURL + 'pasillos/tipoCartones',
            data: {pasillo:  data1},
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                $("#error-modal").text("Ocurrio un error al cargar la pagina");
                $("#modal-danger").modal('show');
            }
        });
    }
    function onReadCB(e){
        $.ajax({
            url: baseURL + 'pasillos/tipoCartones/todos',
			type:"POST",
			dataType: "json",
            success: function(result){
                e.success(result);
            },
            error: function(result){
                $("#error-modal").text("Ocurrio un error durante el proceso, intentelo nuevamente");
                $("#modal-danger").modal('show');
            }
        });
    }
    function ActCartonType(e){
    	var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
    	data1 = dataItem.PASILLO;
    	var popupactcartontype = $("#POPUP_Actual_CT");
    	var grid = $("#gridActCt");
        grid.data("kendoGrid").dataSource.read();
    	popupactcartontype.data("kendoWindow").title('Carton Type Actuales Pasillo: '+ dataItem.PASILLO)
        popupactcartontype.data("kendoWindow").open();

    }
    function ConfCartonType(e){
    	var popupactcartontype = $("#POPUP_CartonType");
    	var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
    	data1 = dataItem.PASILLO;
    	popupactcartontype.data("kendoWindow").title('Actualizar Carton Type Pasillo: '+ dataItem.PASILLO)
        popupactcartontype.data("kendoWindow").open();
    }
    $("#btnActCartonType").click(function(){
    	var popupcartontype = $("#POPUP_CartonType");
		popupcartontype.data("kendoWindow").close();
    	var cartonType = $("#selectCartonType").data("kendoComboBox").value();
    	$.ajax({
            url: baseURL + 'pasillos/actTipoCarton',
            type: 'POST', // POST or GET
            dataType: 'json', // Tell it we're retrieving JSON
            data: {
                pasillo: data1, cartonType: cartonType// Pass through the ID of the current element matched by '.selector'
            },
            success: function(data){
              if(data > 0){
              	alert('Actualizado Correctamente');
              }
            }
        });
    });
    $("#btnBuscarPasillo").click(function(){
    	var pasillo = $("#txtPasillo").val();
    	var grid = $("#gridCT").data("kendoGrid");
    	grid.dataSource.data([]);
    	grid.dataSource.filter({field: "PASILLO", operator: "startswith", value: pasillo});
    	grid.dataSource.read();
    });
});