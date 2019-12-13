$(document).ready(function(){
    localStorage.clear();
	var depto = "";
	var putwy = "";
	var aisle = "";
    var data = []; 

	$("#selectDepto").change(function(){
		depto = $(this).children("option:selected").val();
		var grid = $("#gridPutwyType");
        grid.data("kendoGrid").dataSource.read();
	});
	var dataSource = new kendo.data.DataSource({
        transport: {
            read: onRead
        },
        schema: {
            model: {
                id: "SKU_ID",
                fields: {
                        SKU_ID: {type: "string"}, // number - string - date
                        MERCH_TYPE: {type: "string"},
                        PUTWY_TYPE: {type: "string"},
                        SUBLINEA: {type: "string"},
                        MODA: {type: "string"}, // number - string - date
                        TOT_RESERVA: {type: "string"},
                        TOT_ACTIVO: {type: "string"}
                    }
            }
        },
        pageSize: 50
    });

    var dataSourcePasillosputwy = new kendo.data.DataSource({
        transport: {
            read: onReadPP
        },
        schema: {
            model: {
                id: "AISLE",
                fields: {
                        AISLE: {type: "string"}, // number - string - date
                        LOCN_CLASS: {type: "string"},
                        PUTWY_TYPE: {type: "string"}
                    }
            }
        },
        pageSize: 20
    });

    var dataSourceLocnputwy = new kendo.data.DataSource({
        transport: {
            read: onReadLP
        },
        schema: {
            model: {
                id: "LOCN_ID",
                fields: {
                        LOCN_ID: {type: "string"}, // number - string - date
                        REPL_LOCN_BRCD: {type: "string"},
                        MAX_NBR_OF_SKU: {type: "string"},
                        TOT_ACT_SKU: {type: "string"}
                    }
            }
        },
        pageSize: 50
    });

    var ventana_pasillos_putwy = $("#POPUP_Pasillos_Putwy");
    ventana_pasillos_putwy.kendoWindow({
        width: "750px",
        height: "550px",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();

    var ventana_locn_putwy = $("#POPUP_Locn_Putwy");
    ventana_locn_putwy.kendoWindow({
        width: "750px",
        height: "550px",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();

    $("#toolbarsubl").kendoToolBar({
        items: [
            { type: "button", text: "Pasillos Putwy", icon: "k-icon k-i-search" ,click: Pasillosputwy},
            { type: "button", text: "Configurar", icon: "k-icon k-i-download" ,click: Configurar}
        ]
    });

    $("#gridPutwyType").kendoGrid({
        autoBind: false,
        dataSource: dataSource,
       	height: "420px", 
        width: "100%",
        sortable: true, 
        filterable: true,
        scrollable: true,
        change: function (e, args) {
                    data = [];
                    var rows = e.sender.select();
                    rows.each(function(e) {
                        var grid = $("#gridPutwyType").data("kendoGrid");
                        var item = grid.dataItem(this);
                        data.push({SKU_ID: item.SKU_ID, MERCH_TYPE: item.MERCH_TYPE, PUTWY_TYPE: item.PUTWY_TYPE, MODA: item.MODA});
                    })
                    console.log(data);  
        },
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {selectable: true, width: "15px" },
            {field: "SKU_ID",title: "SKU",width: 70, filterable:false, resizable:false, height: 80},
            {field: "MERCH_TYPE",title: "DEPTO",width:70,filterable:false},
            {field: "PUTWY_TYPE",title: "PUTWY TYPE",width:70,filterable: false},
            {field: "SUBLINEA",title: "SUBLINEA",width:70,filterable: false},
            {field: "MODA",title: "MODA",width:70,filterable: false},
            {field: "TOT_RESERVA",title: "TOTAL RACK",width:70,filterable: false},
            {field: "TOT_ACTIVO",title: "TOTAL ACTIVO",width:70,filterable: false}
        ]
    });

    $("#gridPasillosPutwy").kendoGrid({
        autoBind: false,
        dataSource: dataSourcePasillosputwy,
       	height: "100%", 
        width: "100%",
        sortable: true, 
        filterable: true,
        scrollable: true,
        selectable: "row",
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {field: "AISLE",title: "PASILLO",width: 70, filterable:false, resizable:false, height: 80},
            {field: "LOCN_CLASS",title: "LOCN CLASS",width:70,filterable:false},
            {field: "PUTWY_TYPE",title: "PUTWY TYPE",width:70,filterable: false}
        ]
    }).on("click", "tbody td", function(e) {
        var cell = $(e.currentTarget);
        var cellIndex = cell[0].cellIndex;
        var grid = $("#gridPasillosPutwy").data("kendoGrid");
        var column = grid.columns[0];
        var dataItem = grid.dataItem(cell.closest("tr"));
        aisle = dataItem[column.field];
        var popuplocnputwy = $("#POPUP_Locn_Putwy");
        popuplocnputwy.data("kendoWindow").title('Locaciones Disponibles Pasillo: '+aisle);
        popuplocnputwy.data("kendoWindow").open();
        var grid = $("#gridLocnPutwy");
        grid.data("kendoGrid").dataSource.read();
    });

    $("#gridLocnPutwy").kendoGrid({
        autoBind: false,
        dataSource: dataSourceLocnputwy,
       	height: "100%", 
        width: "100%",
        sortable: true, 
        filterable: true,
        scrollable: true,
        //selectable: "row",
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {field: "LOCN_ID",title: "ID UBICACION",width: 70, filterable:false, resizable:false, height: 80},
            {field: "REPL_LOCN_BRCD",title: "UBICACION",width:70,filterable:false},
            {field: "MAX_NBR_OF_SKU",title: "MAX SKU UBICACION",width:70,filterable: false},
            {field: "TOT_ACT_SKU",title: "SKU ACTUALES",width:70,filterable: false}
        ]
    });/*.on("click", "tbody td", function(e) {
        var cell = $(e.currentTarget);
        var cellIndex = cell[0].cellIndex;
        var grid = $("#gridPutwyType").data("kendoGrid");
        var column = grid.columns[2];
        var dataItem = grid.dataItem(cell.closest("tr"));
        putwy = dataItem[column.field];
        var popuppasillosputwy = $("#POPUP_Pasillos_Putwy");
        popuppasillosputwy.data("kendoWindow").title('Pasillos PUTWY: '+putwy);
        popuppasillosputwy.data("kendoWindow").open();
        var grid = $("#gridPasillosPutwy");
        grid.data("kendoGrid").dataSource.read();
    });*/

    function Configurar(e){
    	e.preventDefault();

    	var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
    	alert(dataItem.SKU_ID);
    }

    function onRead(e){
         $.ajax({
            type: "POST",
            url: baseURL + 'Deptos/select',
            data: { depto: depto },
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }

    function onReadPP(e){
         $.ajax({
            type: "POST",
            url: baseURL + 'Deptos/pasillos',
            data: { data: JSON.stringify(data) },
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }

    function onReadLP(e){
        $.ajax({
            type: "POST",
            url: baseURL + 'Deptos/locaciones',
            data: { aisle: aisle },
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }
    function Pasillosputwy(){
        var popuppasillosputwy = $("#POPUP_Pasillos_Putwy");
        popuppasillosputwy.data("kendoWindow").title('Pasillos PUTWY: '+putwy);
        popuppasillosputwy.data("kendoWindow").open();
        var grid = $("#gridPasillosPutwy");
        grid.data("kendoGrid").dataSource.read();
    }
    function Configurar(){
        if(data.length > 0){
            $.ajax({
                type: "POST",
                url: baseURL + 'Deptos/configurar',
                data: { data: JSON.stringify(data) },
                dataType: 'json',
                success: function(result){
                    localStorage.setItem("datos", JSON.stringify(result));
                    console.log(localStorage.getItem("datos"));
                    var confirmar = confirm("Desea ir al seteo Articulo-Locacion?")
                    if(confirmar){
                        window.location.href = baseURL + 'articuloLocacion';
                    }
                },
                error: function(result){
                    alert(JSON.stringify(result));
                }
            });
        }
        else{
            $("#error-modal").text("Debe seleccionar al menos 1 SKU para configurar");
            $("#modal-danger").modal('show');
        }
    }
});