$(document).ready(function(){
    kendo.culture("es-CL");
    var rowadded = false;
    var addeduid = [];
    var flagSave = false;
    var created = "";
    var updated = [];
    var aisle = [];
    var locn_class = [];
    var putwy_types = [];
    var destroyed = "";
    var old = [];
    var lastRow = "";

    validaisles();
    validlocn_class();
    validputwy_types();



	var dataSourceinfo = new kendo.data.DataSource({
        batch: true,
        transport: {
            read: onReadINFO,
            submit: onSubmit
        },
        schema: {
            model: {
                id: "AISLE",
                fields: {
                        AISLE: {
                            type: "string",
                            validation: {
                                required: {
                                    message: "Este campo es requerido"
                                },
                                   /* function(input) {
                                        if(input.val() == ""){
                                            input.attr("data-required-msg", "Este campo es requerido");
                                            return false;
                                        }
                                        return true;
                                    },*/
                                validaraisle:
                                    function(input) {
                                        if(!aisles.includes(input.val(),0)){
                                            input.attr("data-validaraisle-msg", "El PASILLO ingresado no existe");
                                            return false;
                                        }
                                        return true;
                                    }
                            }
                        },
                       	LOCN_CLASS: {
                            type: "string",
                            validation: {
                                required: {
                                    message: "Este campo es requerido"
                                },
                                validarlocnclass:
                                    function(input) {
                                        if(!locn_class.includes(input.val(),0)){
                                            input.attr("data-validarlocnclass-msg", "El CLASS ingresado no existe");
                                            return false;
                                        }
                                        return true;
                                    }
                            }
                        }, // number - string - date
                        PUTWY_TYPE: {
                            type: "string",
                            validation: {
                                required: {
                                    message: "Este campo es requerido"
                                },
                                validarputwytype:
                                    function(input) {
                                        if(!putwy_types.includes(input.val(),0)){
                                            input.attr("data-validarputwytype-msg", "El PUTWY_TYPE ingresado no existe");
                                            
                                            return false;
                                        }
                                        return true;
                                    }
                            }
                        },
                        CODE_DESC: {type: "string", editable: false}
                    }
            }
        },
        pageSize: 100
    });
    $("#toolbarAlmLocn").kendoToolBar({
        items: [
            { type: "button", text: "Guardar Cambios", icon: "k-icon k-i-save" ,click: GuardarCambios},
            { type: "button", text: "Agregar Configuracion", icon: "k-icon k-i-plus" ,click: AñadirRegistro},
            { type: "button", text: "", icon: "k-icon k-i-refresh" ,click: LimpiarFiltros}
        ]
    });
    $("#gridINFO").kendoGrid({
        dataSource: dataSourceinfo,
        height: "530px", 
        width: "600px",
        sortable: true,
        editable: {mode: "inline"},
        filterable: true,
        scrollable: true,
        navigatable: true,
        edit: function(e) {
            var grid = $("#gridINFO").data("kendoGrid");
            var cellValue = e.container.find("input").val();
            if(rowadded){
                addeduid.push(grid.dataItem($(e.container).closest("tr")).uid);
                rowadded = false;
                console.log(addeduid);
            }
            if(addeduid.includes(grid.dataItem($(e.container).closest("tr")).uid)){
                if(!grid.getOptions().editable){
                    grid.setOptions({editable: true});
                }
            }else{
                if(cellValue != ""){
                    grid.setOptions({editable: false});
                }
            }
            if(grid.dataItem($(e.container).closest("tr")).AISLE == "" || grid.dataItem($(e.container).closest("tr")).LOCN_CLASS == "" || grid.dataItem($(e.container).closest("tr")).PUTWY_TYPE == ""){
                flagSave = false;
            }else{
                flagSave = true;
            }
        },
        pageable: {
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {field: "AISLE", title: "PASILLO", width: 70, filterable: {multi: true, search: true}},
            {field: "LOCN_CLASS", title: "CLASS", width:70, filterable: {multi: true, search: true}},
            {field: "PUTWY_TYPE", title: "PUTWY TYPE", width:70, filterable: {multi: true, search: true}},
            {field: "CODE_DESC", title: "DESCRIPCION", width:70, filterable: false},
            {command: { text: "Eliminar", click: destroyRow }, width: "24px"},
        ]
    }).on("click", "tbody td", function(e) {
        var cell = $(e.currentTarget);
        var cellIndex = cell[0].cellIndex;
        cell[0].focus();
        var grid = $("#gridINFO").data("kendoGrid");
        var column = grid.columns[0];
        var dataItem = grid.dataItem(cell.closest("tr"));
        if(addeduid.includes(dataItem.uid) && !grid.getOptions().editable){
            grid.setOptions({editable: true});
        }  
    });
    var grid = $("#gridINFO").data("kendoGrid");
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
    function GuardarCambios(e){
        addeduid.forEach(function(element1){
            $("#gridINFO").data("kendoGrid")._data.forEach(function(element2){
                if(element1 == element2.uid){
                    if(element2.AISLE == "" || element2.LOCN_CLASS == "" || element2.PUTWY_TYPE == ""){
                        flagSave = false;
                        $("#info-modal").text("Hay campos en blanco, no se puede guardar");
                        $("#modal-info").modal('show');
                    }
                    else{
                        flagSave = true;
                    }
                }
            });
        });
        console.log(flagSave);   
        if(flagSave){
           var grid = $("#gridINFO").data("kendoGrid");
           grid.dataSource.sync();
        }
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
    function AñadirRegistro(e){
        rowadded = true;
        var grid = $("#gridINFO").data("kendoGrid");
        grid.setOptions({editable: true});
        grid.addRow();
    }
    function destroyRow(e){
        var grid = $("#gridINFO").data("kendoGrid");
        grid.setOptions({editable: true});
        grid.removeRow(e.target);
        flagSave = true;
    }
     function LimpiarFiltros(){
        var grid = $("#gridINFO").data("kendoGrid");
        grid.dataSource.data([]);
        grid.dataSource.filter({});
        grid.dataSource.read();
    }
    function validaisles(){
        $.ajax({
            type: "POST",
            url: baseURL + 'AlmLonc/valid/aisles',
            dataType: 'json',
            success: function(result){
                aisles = result;
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }
    function validlocn_class(){
        $.ajax({
            type: "POST",
            url: baseURL + 'AlmLonc/valid/locn_class',
            dataType: 'json',
            success: function(result){
                locn_class = result;
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }
    function validputwy_types(){
        $.ajax({
            type: "POST",
            url: baseURL + 'AlmLonc/valid/putwy_types',
            dataType: 'json',
            success: function(result){
                putwy_types = result;
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }
    $("#modalerrorCerrar").click(function(){
        console.log("modal cerrado");
        rowadded = true;
        var grid = $("#gridINFO").data("kendoGrid");
        grid.setOptions({editable: true});
        grid.addRow();
    });
});