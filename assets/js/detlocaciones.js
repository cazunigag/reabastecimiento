var idLocacion = ' ';
$(function(){
    var dataSource = new kendo.data.DataSource({
        transport: {
            read: onRead
        },
        schema: {
            model: {
                id: "SKU_ID",
                fields: {
                        SKU_ID: {type: "string"}, // number - string - date
                        SKU_DESC: {type: "string"},
                        MERCH_TYPE: {type: "string"}, // number - string - date
                        PUTWY_TYPE: {type: "string"},    // number - string - date
                        ACTL_INVN_QTY: {type: "string"},    // number - string - date
                        TO_BE_PIKD_QTY: {type: "string"},    // number - string - date
                        TO_BE_FILLD_QTY: {type: "string"},    // number - string - date
                        MOD_DATE_TIME: {type: "string"}
                    }
            }
        },
        pageSize: 5
    });
	var ventana_detalle_locn = $("#POPUP_Detalle_LOCN");
    ventana_detalle_locn.kendoWindow({
        draggable: false,
        resizable: false,
        width: "800px",
        height: "400px",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"
        ]
    }).data("kendoWindow").center();
    var ventana_img_sku = $("#POPUP_img");
    ventana_img_sku.kendoWindow({
        width: "400px",
        height: "400px",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"
        ]
    }).data("kendoWindow").center();
    var ventana_simbologia = $("#POPUP_simbologia");
    ventana_simbologia.kendoWindow({
        title: "Simbologia Locacion",
        width: "350px",
        height: "120px",
        visible: false,
        resizable: false,
        position:{
            top: 45,
            left:0
        },
        actions: [
            "Minimize",
            "Maximize",
            "Close"
        ]
    });
    $("#grid").kendoGrid({
        autoBind: false,
        selectable: "row",
        dataSource: dataSource,
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
            {field: "SKU_ID",title: "SKU",width: 40, filterable:false, resizable:false, height: 80},
            {field: "SKU_DESC",title: "DESCRIPCION",width:70,filterable:false},
            {field: "MERCH_TYPE",title: "DEPTO",width:25,filterable: false},
            {field: "PUTWY_TYPE",title: "TIPO ALM.",width: 25,filterable: false},
            {field: "CARTON_TYPE",title: "TIPO CARTON.",width: 25,filterable: false},
            {field: "ACTL_INVN_QTY",title: "CANT ACTUAL",width: 50,filterable: {multi: true, search: true}},
            {field: "TO_BE_PIKD_QTY",title: "CANT PICK",width: 50,filterable: {multi: true, search: true}},
            {field: "TO_BE_FILLD_QTY",title: "CANT RELLENAR",width: 50,filterable: {multi: true, search: true}},
            {field: "MOD_DATE_TIME",title: "FECHA MODIFICACION",width: 50,filterable: {multi: true, search: true}}

        ],
        edit: function(e){
            e.container.data("kendoWindow").title(idLocacion);
        }
    }).on("click", "tbody td", function(e) {
        var cell = $(e.currentTarget);
        var cellIndex = cell[0].cellIndex;
        var grid = $("#grid").data("kendoGrid");
        var column = grid.columns[0];
        var dataItem = grid.dataItem(cell.closest("tr"));
        $.ajax({
            type: "POST",
            url: baseURL + 'locaciones/detalle/imagen',
            data:{ sku: dataItem[column.field]},
            dataType: 'json',
            success: function(result){
                result.forEach(function(element){
                    $("#POPUP_img").html("<img src='https://home.ripley.cl/store/Attachment/WOP/D"+element.MERCH_TYPE+"/"+element.SKU_BRCD+"/"+element.SKU_BRCD+"_2.jpg' onerror='errorimg()' style='width: 100%; height: 100%' >");
                });
                
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
        var popupimg = $("#POPUP_img");
        popupimg.data("kendoWindow").open();
    });
    
    function onRead(e){
        $.ajax({
            type: "POST",
            url: baseURL + 'locaciones/detalle',
            data:{ idLocn: idLocacion},
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }
    

});
function errorimg(){
    $("#POPUP_img").html("<div style='height: 100%;'><b>IMAGEN NO DISPONIOBLE</b></div>");  
}
$("#btnsimbologia").click(function(){
        var popupsimbologia = $("#POPUP_simbologia");
        popupsimbologia.data("kendoWindow").open();
});

 