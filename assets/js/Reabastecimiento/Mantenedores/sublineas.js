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
                                required: {
                                    message: "Este campo es requerido" 
                                },
                                min: 0,
                                max: 9999.99,
                                maxlengthminimo:
                                    function(input) { 
                                        return validarMAX(input)
                                    }
                            }
                        }, // number - string - date
                        MAXIMO: {
                            type: "number",
                            validation: {
                                required: {
                                    message: "Este campo es requerido" 
                                },
                                min: 0,
                                max: 9999.99,
                                maxlengthmaximo:
                                    function(input) { 
                                        if (input.val() == ""){
                                            flagSave = false;
                                            return true;
                                        }
                                        return true
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
            { type: "button", text: "", icon: "k-icon k-i-refresh" ,click: LimpiarFiltros}
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
        navigatable: true,
        pageable: {
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [ // Columnas a Listar
            {field: "SUBLINEA",title: "SUBLINEA",width: 70,  editable: false, resizable:false, filterable: {multi: true, search: true}},
            {field: "DES_SUBLINEA",title: "DESCRIPCION",width:70, editable: false, filterable: {multi: true, search: true}},
            {field: "MINIMO",title: "MINIMO",width:100, filterable: false},
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
        if(sublinea == ""){
            var grid = $("#gridMantMinMaxSubl").data("kendoGrid");
            grid.dataSource.data([]);
            grid.dataSource.filter({});
            grid.dataSource.read();
        }else{
        	var grid = $("#gridMantMinMaxSubl").data("kendoGrid");
        	grid.dataSource.data([]);
        	grid.dataSource.filter({field: "SUBLINEA", operator: "equals", value: sublinea});
        	grid.dataSource.read();
        }
    });

    function LimpiarFiltros(){
    	var grid = $("#gridMantMinMaxSubl").data("kendoGrid");
    	grid.dataSource.data([]);
    	grid.dataSource.filter({});
    	grid.dataSource.read();
    }
    function validarMAX(input){
        var row = input.closest("tr");
        var maximo = row.closest("[data-role=grid]").data("kendoGrid").dataItem(row).MAXIMO;
        var minimo = parseFloat(input.val().replace(",","."));
        if (minimo > maximo) {
          /* input.attr("data-maxlengthminimo-msg", "El valor MINIMO no puede ser mayor que el MAXIMO");
           flagSave = false;
           input.val(0);
           input.focus();
           /*setTimeout(function(){
                input.val(0);
           },200);
           return false;*/
           input.focus();   
           input.val(0);
           input.blur();
           $("#error-modal").text("El valor MINIMO no puede ser mayor que el MAXIMO");
           $("#modal-danger").modal('show');
           flagSave = false;
        }else if(input.val() == "") {
            flagSave = false;
            return true;
        }else{
            flagSave = true;                         
            return true;
        }             
        
    }
});