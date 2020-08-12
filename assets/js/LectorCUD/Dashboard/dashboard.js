$(document).ready(function(){

	tienda = "";
    desde = "";
    hasta = "";
	$("#grilla").hide();

	var dataSource = new kendo.data.DataSource({
	    transport: {
	        read: onRead
	    },
	    schema: {
	        model: {
	            id: "TIENDA",
	            fields: {
	                    TIENDA: {type: "string", editable: false}, // number - string - date
	                    INFORMADO: {type: "number", editable: false}, // number - string - date
	                    PLANIFICADO: {type: "number", editable: false},
	                    DIFERENCIA_PLAN_INFO: {type: "number", editable: false},
	                    POR_DIFERENCIA_PLAN_INFO: {type: "string", editable: false},
	                    POR_INFORMADO: {type: "float", editable: false},
	                    ASIGNADO: {type: "number", editable: false},
                        DIFERENCIA_PLAN_ASIGN: {type: "number", editable: false},
                        POR_PLAN_ASIGN: {type: "string", editable: false},
                        PORC_ASIGNADO: {type: "number", editable: false},
                        DEVUELTO: {type: "number", editable: false},
                        PORC_DEVUELTO: {type: "number", editable: false}
	                }
	        }
	    },
	    pageSize: 100
	});

	$("#grid").kendoGrid({
        autoBind: false,
        dataSource: dataSource,
        width: "100%",
        height: "600px",
        sortable: true, 
        filterable: true,
        scrollable: true,
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [
            {field: "TIENDA",title: "TIENDA",width: 120, filterable:false},
            {field: "PLANIFICADO",title: "COMPROMETIDO BT",width:80, filterable:false},
            {field: "INFORMADO",title: "INFORMADO COURIER",width:80, filterable:false},
            {field: "DIFERENCIA_PLAN_INFO",title: "DIFERENCIA",width:80, filterable:false},
            {field: "POR_DIFERENCIA_PLAN_INFO",title: "% DIFERENCIA",width:60, filterable:false,},
            {field: "POR_INFORMADO",title: "% INFORMADO",width:120, filterable:false, template:'<div class="progressI" style="width:100%;"></div><div class="progressIVAL" style="width:100%;"></div>'},
            {field: "ASIGNADO",title: "Q PISTOLEADO",width: 80,filterable: false},
            {field: "DIFERENCIA_PLAN_ASIGN",title: "RECHAZO",width: 80,filterable: false},
            {field: "POR_PLAN_ASIGN",title: "% RECHAZO",width: 60, filterable:false},
            {field: "PORC_ASIGNADO",title: "% PISTOLEADO",width: 120, filterable:false, template:'<div class="progressP" style="width:100%;"></div><div class="progressPVAL" style="width:100%;"></div>'},
            {field: "DEVUELTO",title: "DEVUELTO",width: 80, filterable:false},
            {field: "PORC_DEVUELTO",title: "% DEVUELTO",width: 120, filterable:false, template:'<div class="progressD" style="width:100%;"></div><div class="progressDVAL" style="width:100%;"></div>'}
        ],
        dataBound: function(e){
        	var grid = this;
        	$(".progressI").each(function(){
	            var row = $(this).closest("tr");
	            var model = grid.dataItem(row);
	              
	            $(this).kendoProgressBar({
	            	type: "percent",
                    showStatus: false,
	                value: model.POR_INFORMADO,
	                animation: {
                        duration: 600
                    }
	            });
            });
            $(".progressP").each(function(){
	            var row = $(this).closest("tr");
	            var model = grid.dataItem(row);
	              
	            $(this).kendoProgressBar({
	            	type: "percent",
                    showStatus: false,
	                value: model.PORC_ASIGNADO,
	                animation: {
                        duration: 600
                    }
	            });
            });
            $(".progressD").each(function(){
	            var row = $(this).closest("tr");
	            var model = grid.dataItem(row);
	              
	            $(this).kendoProgressBar({
	            	type: "percent",
                    showStatus: false,
	                value: model.PORC_DEVUELTO,
	                animation: {
                        duration: 600
                    }
	            });
            });
            $(".progressDIA").each(function(){
                var row = $(this).closest("tr");
                var model = grid.dataItem(row);
                  
                $(this).kendoProgressBar({
                    type: "percent",
                    value: model.POR_PLAN_ASIGN,
                    animation: {
                        duration: 600
                    }
                });
            });
            $(".progressIVAL").each(function(){
                var row = $(this).closest("tr");
                var model = grid.dataItem(row);
                  
                $(this).html(model.POR_INFORMADO+'%');
            });
            $(".progressPVAL").each(function(){
                var row = $(this).closest("tr");
                var model = grid.dataItem(row);
                  
                $(this).html(model.PORC_ASIGNADO+'%');
            });
            $(".progressDVAL").each(function(){
                var row = $(this).closest("tr");
                var model = grid.dataItem(row);
                  
                $(this).html(model.PORC_DEVUELTO+'%');
            });
        }
    });

	$('#datepicker').kendoDateRangePicker({
        "messages": {
            "startLabel": "Desde",
            "endLabel": "Hasta"
        },
        format: "dd/MM/yyyy",
        culture: "es-CL"
    });

    var daterangepicker = $("#datepicker").data("kendoDateRangePicker");
    daterangepicker.bind("change", function() {
        var range = this.range();
       	desde = range.start.getDate()+'/'+(range.start.getMonth() + 1) +'/'+range.start.getFullYear();
        hasta = range.end.getDate()+'/'+(range.end.getMonth() + 1)+'/'+range.end.getFullYear();;
        console.log(desde);
        if(desde !=  "" && hasta != ""){
            $("#grilla").fadeIn();
            var grid = $("#grid");
            grid.data("kendoGrid").dataSource.read();
        }
    });

	function onRead(e){
		$.ajax({
            type: "POST",
            url: baseURL + 'lector/dashboard/data',
            dataType: 'json',
            data: {desde: desde, hasta: hasta},
            success: function(result){
            	e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
	}
});