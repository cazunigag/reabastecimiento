$(document).ready(function(){
	var dataSourceinfo = new kendo.data.DataSource({
        transport: {
            read: onReadINFO
        },
        schema: {
            model: {
                id: "AISLE",
                fields: {
                        AISLE: {type: "string"},
                       	LOCN_CLASS: {type: "string"}, // number - string - date
                        PUTWY_TYPE: {type: "string"},
                        CODE_DESC: {type: "string"}
                    }
            }
        },
        pageSize: 100
    });
    $("#gridINFO").kendoGrid({
        dataSource: dataSourceinfo,
        height: "570px", 
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
            {field: "AISLE",title: "PASILLO", width: 70, filterable: {multi: true, search: true}},
            {field: "LOCN_CLASS",title: "CLASS", width:70, filterable: {multi: true, search: true}},
            {field: "PUTWY_TYPE",title: "PUTWY TYPE", width:70, filterable: {multi: true, search: true}},
            {field: "CODE_DESC",title: "DESCRIPCION", width:70, filterable: false}
        ]
    });
    function onReadINFO(e){
         $.ajax({
            type: "POST",
            url: baseURL + 'AlmLonc/info',
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