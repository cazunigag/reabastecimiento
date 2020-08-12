$(document).ready(function(){

	var workarea = "";
	var workgroup = "";
	var pasillo = "";

	var dataSource = new kendo.data.DataSource({
        transport: {
            read: onRead
        },
        schema: {
            model: {
                id: "PASILLO",
                fields: {
                        PASILLO: {type: "string"}, // number - string - date
                        WORK_AREA: {type: "string"},
                        WORK_GRP: {type: "string"}
                    }
            }
        },
        pageSize: 50
    });

    var ventana_cartontype= $("#POPUP_WorkArea");
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

    function onRead(e){
    	$.ajax({
            type: "POST",
            url: baseURL + 'workarea/data',
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }

    $("#grid").kendoGrid({
        dataSource: dataSource,
        height: "500px", 
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
            {field: "PASILLO",title: "PASILLO",width: 40, filterable:false, resizable:false, height: 80},
            {field: "WORK_GRP",title: "WORK GROUP ACTUAL",width:50,filterable: false},
            {field: "WORK_AREA",title: "WORK AREA ACTUAL",width:50,filterable:false},
            {command: { text: "CONFIGURAR", click: ConfWorkArea}, title: "", width: "50px"}
        ]
    });

    $("#btnBuscarPasillo").click(function(){
    	var pasillo = $("#txtPasillo").val();
    	var grid = $("#grid").data("kendoGrid");
    	grid.dataSource.data([]);
    	grid.dataSource.filter({field: "PASILLO", operator: "startswith", value: pasillo});
    	grid.dataSource.read();
    });

    function ConfWorkArea(e){
    	var popupactwa = $("#POPUP_WorkArea");
    	var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
    	pasillo = dataItem.PASILLO;
    	popupactwa.data("kendoWindow").title('Actualizar Work Area Pasillo: '+ dataItem.PASILLO)
        popupactwa.data("kendoWindow").open();
    }

    var dataSourceWA  = new kendo.data.DataSource({
        transport:{
            read: onReadWA
        }
    });

    var dataSourceWG  = new kendo.data.DataSource({
        transport:{
            read: onReadWG
        }
    });

    $("#selectWorkArea").kendoComboBox({
                autoBind: false,
		dataSource: dataSourceWA,
		dataTextField: "WORK_AREA",
		dataValueField: "WORK_AREA"
	});

	$("#selectWorkGroup").kendoComboBox({
		dataSource: dataSourceWG,
		dataTextField: "WORK_GRP",
		dataValueField: "WORK_GRP"
	});

	$("#selectWorkArea").change(function(){
		workarea = $("#selectWorkArea").val();
	});

	$("#selectWorkGroup").change(function(){
		workgroup = $("#selectWorkGroup").val();
                $("#selectWorkArea").val('');
		$("#selectWorkArea").data("kendoComboBox").dataSource.read();
	});

	function onReadWA(e){
        $.ajax({
            url: baseURL + 'listar/wa',
            type:"POST",
            data: {workgroup: workgroup},
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

    function onReadWG(e){
    	$.ajax({
            url: baseURL + 'listar/wg',
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

    $("#btnActWorkArea").click(function(){
    	var popupcartontype = $("#POPUP_WorkArea");
		popupcartontype.data("kendoWindow").close();
    	$.ajax({
            url: baseURL + 'workarea/actualizar',
            type: 'POST', // POST or GET
            dataType: 'json', // Tell it we're retrieving JSON
            data: { pasillo: pasillo, workarea: workarea, workgroup: workgroup },
            success: function(data){
              if(data == 1){
	              $("#success-modal").text("Proceso Finalizado. Planilla Excel Cargada Correctamente");
	              $("#modal-success").modal('show');
                      var grid = $("#grid").data("kendoGrid");
                      grid.dataSource.read();
              }
            }
        });
    });
});