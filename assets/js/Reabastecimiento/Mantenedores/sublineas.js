$(document).ready(function(){
    kendo.culture("es-CL");
    var flagSave = true;
	var changes = [];
	var dataSource = new kendo.data.DataSource({
		batch: true,
        transport: {
            read: onRead,
            submit: onSubmit
        },
        schema: {
            model: {
                id: "SUBLINEA",
                fields: {
                        SUBLINEA: {type: "string", editable: false}, // number - string - date
                        DES_SUBLINEA: {type: "string", editable: false},
                        MINIMO: {
                            type: "number",
                            validation: {
                                required: true,
                                maxlength:
                                    function(input) { 
                                        if (input.val() > 9999.99) {
                                           input.attr("data-maxlength-msg", "El valor maximo es 9999.99");
                                           flagSave = false;
                                           return false;
                                        }              
                                        flagSave = true;                         
                                        return true;
                                    }
                            }
                        }, // number - string - date
                        MAXIMO: {
                            type: "number",
                            validation: {
                                required: true,
                                maxlength:
                                    function(input) { 
                                        if (input.val() > 9999.99) {
                                           input.attr("data-maxlength-msg", "El valor maximo es 9999.99");
                                           flagSave = false;
                                           return false;
                                        }
                                        flagSave = true;                                   
                                        return true;
                                    }
                            }
                        }
                    }
            },
        },
        pageSize: 50
    }); 
    console.log(dataSource);

    $("#toolbarsubl").kendoToolBar({
        items: [
            { type: "button", text: "Guardar Cambios", icon: "k-icon k-i-save" ,click: GuardarCambios},
            { type: "button", text: "Limpiar Filtros", icon: "k-icon k-i-file" ,click: LimpiarFiltros}
        ]
    });

	$("#gridMantMinMaxSubl").kendoGrid({
        dataSource: dataSource,
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
            {field: "SUBLINEA",title: "SUBLINEA",width: 70,  editable: false, resizable:false, filterable: {multi: true, search: true}},
            {field: "DES_SUBLINEA",title: "DESCRIPCION",width:70, editable: false, filterable: {multi: true, search: true}},
            {field: "MINIMO",title: "MINIMO",width:70, filterable: false},
            {field: "MAXIMO",title: "MAXIMO",width:100, filterable: false}
        ]
    });
    var culture = kendo.culture();
    console.log(culture);
    function onRead(e){
    	$.ajax({
          type: "POST",
          url: baseURL + 'sublineas/read',
          dataType: 'json',
          success: function(result){
              e.success(result);
          },
          error: function(result){
              console.log(JSON.stringify(result));
          }
      });
    }
    function GuardarCambios(){
        if(flagSave){
    	   var grid = $("#gridMantMinMaxSubl").data("kendoGrid");
    	   grid.dataSource.sync();
        }else{
            $("#error-modal").text("Existen valores invalidos, revise y vuelva a intentarlo");
            $("#modal-danger").modal('show');
        }
    }
    function onSubmit(e){
    		$.ajax({
                type: "POST",
                url: baseURL + 'sublineas/save',
                dataType: 'json',
                data: {changes: JSON.stringify(e.data.updated)},
                success: function(result){
                	if(result == 0){
                		$("#success-modal").text("Cambios Guardados Correctamente");
                    	$("#modal-success").modal('show');
    	                var grid = $("#gridMantMinMaxSubl").data("kendoGrid");
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
    }
    $("#btnBuscarSublinea").click(function(){
    	var sublinea = $("#txtsubl").val();
    	alert(sublinea);
    	var grid = $("#gridMantMinMaxSubl").data("kendoGrid");
    	grid.dataSource.data([]);
    	grid.dataSource.filter({field: "SUBLINEA", operator: "equals", value: sublinea});
    	grid.dataSource.read();
    });

    function LimpiarFiltros(){
    	var grid = $("#gridMantMinMaxSubl").data("kendoGrid");
        console.log(grid.options.editable);
    	grid.dataSource.data([]);
    	grid.dataSource.filter({});
    	grid.dataSource.read();
    }
});