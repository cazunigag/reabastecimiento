$(document).ready(function(){
    var created = "";
    var updated = [];
    var destroyed = "";
    var old = [];
    var lastRow = "";
	var dataSourceinfo = new kendo.data.DataSource({
        batch: true,
        transport: {
            read: onReadINFO,
            update: onUpdate,
            submit: onSubmit
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
    $("#toolbarAlmLocn").kendoToolBar({
        items: [
            { type: "button", text: "Guardar Cambios", icon: "k-icon k-i-save" ,click: GuardarCambios},
            { type: "button", text: "Agregar Configuracion", icon: "k-icon k-i-plus" ,click: AñadirRegistro}
        ]
    });
    $("#gridINFO").kendoGrid({
        dataSource: dataSourceinfo,
        height: "500px", 
        width: "600px",
        sortable: true,
        editable: true,
        filterable: true,
        scrollable: true,
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {field: "AISLE", editable: false, title: "PASILLO", width: 70, filterable: {multi: true, search: true}},
            {field: "LOCN_CLASS", editable: false, title: "CLASS", width:70, filterable: {multi: true, search: true}},
            {field: "PUTWY_TYPE", editable: false, title: "PUTWY TYPE", width:70, filterable: {multi: true, search: true}},
            {field: "CODE_DESC", editable: false, title: "DESCRIPCION", width:70, filterable: false},
            {command: { name: "destroy" , text: " " }, width: "24px"},
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
    function GuardarCambios(){
        var grid = $("#gridINFO").data("kendoGrid");
        grid.dataSource.sync();
    }
    function onSubmit(e){
        console.log(e.data);
        var grid = $("#gridINFO").data("kendoGrid");
        var rows = grid.select();
        console.log(rows.attr("data-uid"));
        if(e.data.updated.length > 0){
            updated = JSON.stringify(e.data.updated);
            $.ajax({
                type: "POST",
                url: baseURL + 'AlmLonc/update',
                dataType: 'json',
                data: {updated: updated, old: old},
                success: function(result){
                    if(result == 0){
                        $("#success-modal").text("Cambios Guardados Correctamente");
                        $("#modal-success").modal('show');
                        var grid = $("#gridINFO").data("kendoGrid");
                        grid.dataSource.read();
                    }
                    else{
                        alert("Error al Guardar Cambios");
                    }
                },
                error: function(result){
                    $("#error-modal").text("Ocurrio un error al cargar la pagina");
                    $("#modal-danger").modal('show');
                }
            });
            console.log(updated);
        }
        if(e.data.created.length > 0){
            created = JSON.stringify(e.data.created);
            $.ajax({
                type: "POST",
                url: baseURL + 'AlmLonc/create',
                dataType: 'json',
                data: {created: created},
                success: function(result){
                    if(result == 0){
                        $("#success-modal").text("Cambios Guardados Correctamente");
                        $("#modal-success").modal('show');
                        var grid = $("#gridINFO").data("kendoGrid");
                        grid.dataSource.read();
                    }
                    else if(result == 2){
                        $("#error-modal").text("Error al guardar la configuracion");
                        $("#modal-danger").modal('show');
                        var grid = $("#gridINFO").data("kendoGrid");
                        grid.dataSource.read();
                    }
                     else if(result == 3){
                        $("#error-modal").text("El pasillo que esta intentado configurar no existe");
                        $("#modal-danger").modal('show');
                        var grid = $("#gridINFO").data("kendoGrid");
                        grid.dataSource.read();
                    }
                     else if(result == 4){
                        $("#error-modal").text("El Putwy_Type que esta intentando configurar no existe");
                        $("#modal-danger").modal('show');
                        var grid = $("#gridINFO").data("kendoGrid");
                        grid.dataSource.read();
                    }
                    else{
                        $("#error-modal").text("No se puede agregar una configuracion ya existente");
                        $("#modal-danger").modal('show');
                        var grid = $("#gridINFO").data("kendoGrid");
                        grid.dataSource.read();
                    }
                },
                error: function(result){
                    $("#error-modal").text("Ocurrio un error al cargar la pagina");
                    $("#modal-danger").modal('show');
                }
            });
        }
        if(e.data.destroyed.length > 0){
            destroyed = JSON.stringify(e.data.destroyed);
            $.ajax({
                type: "POST",
                url: baseURL + 'AlmLonc/delete',
                dataType: 'json',
                data: {destroyed: destroyed},
                success: function(result){
                    if(result == 0){
                        $("#success-modal").text("Cambios Guardados Correctamente");
                        $("#modal-success").modal('show');
                        var grid = $("#gridINFO").data("kendoGrid");
                        grid.dataSource.read();
                    }
                    else{
                        $("#error-modal").text("Error al eliminar");
                        $("#modal-danger").modal('show');
                        var grid = $("#gridINFO").data("kendoGrid");
                        grid.dataSource.read();
                    }
                },
                error: function(result){
                    $("#error-modal").text("Ocurrio un error al cargar la pagina");
                    $("#modal-danger").modal('show');
                }
            });
        }
    }
    function LimpiarFiltros(){
        var grid = $("#gridINFO").data("kendoGrid");
        grid.dataSource.data([]);
        grid.dataSource.filter({});
        grid.dataSource.read();
    }
    function AñadirRegistro(){
        var grid = $("#gridINFO").data("kendoGrid");
        grid.addRow();
    }
    function onUpdate(e){
        console.log(e);
    }
    function destroyRow(e){
        var grid = $("#gridINFO").data("kendoGrid");
        grid.removeRow(e.target);
    }

});