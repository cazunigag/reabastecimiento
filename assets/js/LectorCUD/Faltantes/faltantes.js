$(document).ready(function(){

	var tienda = "";
    var id = "";
    var opl = "";
    var fecha = "";	
    var escaneos = 0;

    $("#boxscanner").hide();
    $("#ruta").hide();
    $("#opl").hide();

	$("#cud").on('change keyup', function(){
       tienda = $("#selectTienda").data("kendoComboBox").value();
       fecha = $("#datepicker").val();  

        if(tienda != "" && fecha != ""){
            barcode =  $("#cud").val();
              
            if (barcode.length == 22){
                $("#cud").val('');
                $.ajax({
                    type: "POST",
                    url: baseURL + 'lector/pickfaltantes',
                    dataType: 'json',
                    data: {barcode: barcode, tienda: tienda, fecha: fecha},
                    success: function(result){
                    escaneos++;
                    actTotal();
                    if(result == 1){
                        $("#boosmapinfo").prepend('<tr style="font-weight: bold; font-size: 90px; color: #990099;"><td style="font-size: 200px; text-align: center;" width="40%" colspan="3">CUD YA ESCANEADO</td></tr>');
                        if(escaneos == 4){
                            document.getElementById("boosmapinfo").deleteRow(escaneos - 1);
                            escaneos = escaneos - 1;
                        }
                    }
                    else if(result == 2){
                        $("#boosmapinfo").prepend('<tr style="font-weight: bold; font-size: 90px; color: #990099;"><td style="font-size: 200px; text-align: center;" width="40%" colspan="3">CUD DE OTRA FECHA</td></tr>');
                        if(escaneos == 4){
                            document.getElementById("boosmapinfo").deleteRow(escaneos - 1);
                            escaneos = escaneos - 1;
                        }
                    }
                    else if(result == 3){
                        $("#boosmapinfo").prepend('<tr style="font-weight: bold; font-size: 90px; color: #990099;"><td style="font-size: 200px; text-align: center;" width="40%" colspan="3">CUD DE OTRA TIENDA</td></tr>');
                        if(escaneos == 4){
                            document.getElementById("boosmapinfo").deleteRow(escaneos - 1);
                            escaneos = escaneos - 1;
                        }
                    }
                    else if(result == 4){
                        $("#boosmapinfo").prepend('<tr style="font-weight: bold; font-size: 90px; color: #990099;"><td style="font-size: 200px; text-align: center;" width="40%" colspan="3">EL CUD NO EXISTE</td></tr>');
                        if(escaneos == 4){
                            document.getElementById("boosmapinfo").deleteRow(escaneos - 1);
                            escaneos = escaneos - 1;
                        }
                    }
                    else if(result == 5){
                        $("#boosmapinfo").prepend('<tr style="font-weight: bold; font-size: 90px; color: #990099;"><td style="font-size: 200px; text-align: center;" width="40%" colspan="3">SIN INFORMACION DE DESPACHO</td></tr>');
                        if(escaneos == 4){
                            document.getElementById("boosmapinfo").deleteRow(escaneos - 1);
                            escaneos = escaneos - 1;
                        }
                    }
                    else{
                        if(result.length > 0){
                            result.forEach(function(element){
                              $("#boosmapinfo").prepend('<tr style="font-weight: bold; font-size: 90px; color: #990099;" id="'+element.CUD+'"><td style="font-size: 450px; text-align: center;" width="30%">'+element.ID+'</td><td height="100%" width="30%"><div class="col-xs-12">'+element.NOMBRE_TRANSPORTISTA+'</div><div class="col-xs-12" style="color: black;">SKU</div><div class="col-xs-12">'+element.ARTICULO+'</div></td><td height="100% width="30%"><div class="col-xs-12">'+element.PATENTE+'</div><div class="col-xs-12" style="color: black;">CANTIDAD</div><div class="col-xs-12">'+element.CANTIDAD+'</div></td></tr>');
                            });
                            if(escaneos == 4){
                                document.getElementById("boosmapinfo").deleteRow(escaneos - 1);
                                escaneos = escaneos - 1;
                            }
                        }
                    }           
                    },
                    error: function(result){
                        alert(JSON.stringify(result));
                    }
                });
            }
        }
    });

    function actTotal(){
        $.ajax({
            url: baseURL + 'lector/totalfaltantes',
            type:"POST",
            data: {id: id, tienda: tienda, fecha: fecha, opl: opl},
            dataType: "json",
            success: function(result){
                $("#totales").html("Pickeados: "+result);
            },
            error: function(result){
                $("#error-modal").text("Ocurrio un error durante el proceso, intentelo nuevamente");
                $("#modal-danger").modal('show');
            }
        });
    }

    $('#datepicker').datepicker({
		autoclose: true,
		format: 'dd/mm/yyyy'
    });

    var dataSource2  = new kendo.data.DataSource({
        transport:{
            read: onReadCB
        }
    });

    $("#selectTienda").kendoComboBox({
        dataSource: dataSource2,
        dataTextField: "NAME",
        dataValueField: "STORE_NBR"
    });

    function onReadCB(e){
    	$.ajax({
            url: baseURL + 'lector/tiendas',
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

    $("#selectTienda").change(function(){
    	tienda = $("#selectTienda").data("kendoComboBox").value();
       	fecha = $("#datepicker").val();
       	id = $("#selectId").data("kendoComboBox").value();	
    	if(fecha != ""){
            $("#selectOPL").data("kendoComboBox").dataSource.read();
    		$("#opl").fadeIn();
    	}
    });
    $("#datepicker").change(function(){
    	tienda = $("#selectTienda").data("kendoComboBox").value();
       	fecha = $("#datepicker").val();
       	id = $("#selectId").data("kendoComboBox").value();		
    	if(tienda != ""){
            $("#selectOPL").data("kendoComboBox").dataSource.read();
    		$("#opl").fadeIn();
    	}
    });
    $("#selectOPL").change(function(){
        tienda = $("#selectTienda").data("kendoComboBox").value();
       	fecha = $("#datepicker").val();
       	opl = $("#selectOPL").data("kendoComboBox").value();	
    	if(tienda != "" && fecha != ""){
            $("#selectId").data("kendoComboBox").dataSource.read();
            $("#ruta").fadeIn();
    	}
    });
    $("#selectId").change(function(){
    	tienda = $("#selectTienda").data("kendoComboBox").value();
        fecha = $("#datepicker").val();
        opl = $("#selectOPL").data("kendoComboBox").value();	
       	id = $("#selectId").data("kendoComboBox").value();	
    	if(tienda != "" && fecha != "" && opl != ""){
            actTotal();
    		$("#cerrarCarga").fadeIn();
    		$("#boxscanner").fadeIn();
    	}
    });

    var dataSourceDet = new kendo.data.DataSource({
        transport: {
            read: onReadDet
        },
        schema: {
            model: {
                id: "CUD",
                fields: {
                        CUD: {type: "string", editable: false}, // number - string - date
                        SKU: {type: "string", editable: false}, // number - string - date
                        SKU_DESC: {type: "string", editable: false},
                        FECHA: {type: "string", editable: false},
                        TIENDA: {type: "string", editable: false},
                        DPTO: {type: "string", editable: false},
                        OC: {type: "string", editable: false},
                        GLOSA: {type: "string", editable: false}
                    }
            }
        },
        pageSize: 100
    });

    $("#Faltantes").click(function(){
        var popupdetcierrecarga = $("#POPUP_det_Cierre_Carga");
        popupdetcierrecarga.data("kendoWindow").open();
        var grid = $("#gridDet");
        grid.data("kendoGrid").dataSource.read();
    });

    var ventana_detalle_cc = $("#POPUP_det_Cierre_Carga");
    ventana_detalle_cc.kendoWindow({
        width: "1000px",
        height: "550px",
        title: "Diferencias Cierre Carga",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();

    $("#gridDet").kendoGrid({
        autoBind: false,
        dataSource: dataSourceDet,
        width: "100%",
        height: "100%",
        sortable: true, 
        filterable: true,
        scrollable: true,
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [
            {field: "CUD",title: "CUD",width: 150, filterable:false},
            {field: "SKU",title: "SKU",width:100, filterable:false},
            {field: "SKU_DESC",title: "SKU DESC",width:140,filterable: false},
            {field: "FECHA",title: "FECHA",width: 80,filterable: false},
            {field: "TIENDA",title: "TIENDA",width: 80,filterable: false},
            {field: "DPTO",title: "DPTO",width: 70,filterable: false},
            {field: "OC",title: "OC",width: 80,filterable: false},
            {field: "GLOSA",title: "GLOSA",width: 100,filterable: false}
        ]
    });

    function onReadDet(e){
        $.ajax({
            type: "POST",
            url: baseURL + 'lector/detfaltantes',
            dataType: 'json',
            data: {id: id, tienda: tienda, fecha: fecha, opl: opl},
            success: function(result){
                e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }

    var dataSource4  = new kendo.data.DataSource({
        transport:{
            read: onReadIds
        }
    });

    $("#selectId").kendoComboBox({
        dataSource: dataSource4,
        autoBind: false,
        dataTextField: "ID",
        dataValueField: "ID"
    });

    var ventana_id = $("#POPUP_Id");
      ventana_id.kendoWindow({
          width: "300px",
          title: "Seleccionar ID Transporte",
          visible: false,
          actions: [
              "Close"
          ]
    }).data("kendoWindow").center();

    $("#selectId").change(function(){
        id = $("#selectId").data("kendoComboBox").value();
    });

    function onReadIds(e){
        $.ajax({
            url: baseURL + 'lector/idsV2',
            type:"POST",
            dataType: "json",
            data: { tienda: tienda, fecha: fecha, opl: opl},
            success: function(result){
                e.success(result);
            },
            error: function(result){
                $("#error-modal").text("Ocurrio un error durante el proceso, intentelo nuevamente");
                $("#modal-danger").modal('show');
            }
        });
    }
    $("#Seleccionar").click(function(){
        fec = fecha.replace(/[&\/\\#,+()$~%.'":*?<>{}]/g,'-');
        window.location.href = baseURL + 'lector/resumenV2/'+id+'/'+tienda+'/'+fec+'/'+opl;
    });

    $("#cerrarCarga").click(function(){
        fec = fecha.replace(/[&\/\\#,+()$~%.'":*?<>{}]/g,'-');
        window.location.href = baseURL + 'lector/resumenV2/'+id+'/'+tienda+'/'+fec+'/'+opl;
    });

    var dataSource3  = new kendo.data.DataSource({
        transport:{
            read: onReadOPL
        }
    });

    $("#selectOPL").kendoComboBox({
        autoBind: false,
        dataSource: dataSource3,
        dataTextField: "NOMBRE_TRANSPORTISTA",
        dataValueField: "NOMBRE_TRANSPORTISTA"
    });

    function onReadOPL(e){
    	$.ajax({
            url: baseURL + 'lector/getopl',
            type:"POST",
            data: {tienda: tienda, fecha: fecha},
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

    
});