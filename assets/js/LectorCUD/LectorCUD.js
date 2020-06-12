$(document).ready(function(){

	var barcode = "";
	var excel;
	var tienda = "";
    var fecha = "";
    var cud = "";
    var escaneos = 0;	

    $("#cerrarCarga").hide();
    $("#boxscanner").hide();


    $("#cud").on('change keyup', function(){

       tienda = $("#selectTienda").data("kendoComboBox").value();
       fecha = $("#datepicker").val();	
      if(tienda != "" && fecha != ""){
      	  barcode =  $("#cud").val();
	      
	      if (barcode.length == 22){
            escaneos ++;
            $("#cud").val('');
	        $.ajax({
	            type: "POST",
	            url: baseURL + 'lector/buscar',
	            dataType: 'json',
	            data: {barcode: barcode, tienda: tienda, fecha: fecha},
	            success: function(result){
                    escaneos++;
	            	console.log(result);
                    $("#boosmapinfo").empty();
	              	if(result == 1){
	                	$("#boosmapinfo").append('<tr style="font-weight: bold; font-size: 90px; color: #990099;"><td style="font-size: 200px; text-align: center;" width="40%" colspan="3">CUD YA ESCANEADO</td></tr>');
	              	}
	              	else if(result == 2){
	                	$("#boosmapinfo").append('<tr style="font-weight: bold; font-size: 90px; color: #990099;"><td style="font-size: 200px; text-align: center;" width="40%" colspan="3">CUD DE OTRA FECHA</td></tr>');
                        
	              	}
	              	else if(result == 3){
	                	$("#boosmapinfo").append('<tr style="font-weight: bold; font-size: 90px; color: #990099;"><td style="font-size: 200px; text-align: center;" width="40%" colspan="3">CUD DE OTRA TIENDA</td></tr>');
                        
	              	}
                    else if(result == 4){
                        $("#boosmapinfo").append('<tr style="font-weight: bold; font-size: 90px; color: #990099;"><td style="font-size: 200px; text-align: center;" width="40%" colspan="3">CUD NO EXISTE</td></tr>');
                        
                    }
	              	else{
	              		if(result.length > 0){
	              			result.forEach(function(element){
			                  $("#boosmapinfo").prepend('<tr style="font-weight: bold; font-size: 90px; color: #990099;" id="'+element.CUD+'"><td style="font-size: 450px; text-align: center;" width="40%" >'+element.ID_BOOSTER+'</td><td width="30%">'+element.BOOSTER+'</td><td width="30%">'+element.PATENTE+'</td></tr>');
			                });
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

    var ventana_importar = $("#POPUP_Importar");
      ventana_importar.kendoWindow({
          width: "300px",
          title: "Importar Archivo Excel",
          visible: false,
          actions: [
              "Close"
          ]
    }).data("kendoWindow").center();


    $("#files").kendoUpload({
	    multiple: false
	});

	$("#importarEX").click(function(){
	    var POPUPImportar = $("#POPUP_Importar");
	    POPUPImportar.data("kendoWindow").open();
	});

	$("#importar").click(function(){
	    var popupfactor = $("#POPUP_Importar");
	    popupfactor.data("kendoWindow").close(); 
	});

	$("#import_form").on('submit' ,function(e){
        excel = new FormData(this);
        console.log(excel);
        e.preventDefault();
        $.ajax({
        	beforeSend: function () {
                $(".modalloading").show();
            },
            complete: function () {
                $(".modalloading").hide();
            },
            type: "POST",
            url: baseURL + 'lector/cargar',
            dataType: 'json',
            data: excel,
            contentType: false,
            cache: false,
            processData: false,
            success: function(result){
                $("#files").val('');
	            console.log(result.length);
	            if(result.length == 0){
	                var upload = $("#files").data("kendoUpload");
	                upload.removeAllFiles();
	                $("#error-modal").text("Ha ocurrido un error");
	                $("#modal-danger").modal('show');
	            }else if(result.length != 0){
	                var upload = $("#files").data("kendoUpload");
	                upload.removeAllFiles();
	                $("#success-modal").text("Proceso Finalizado. Planilla Excel Cargada Correctamente");
                    $("#modal-success").modal('show');
	            }
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    });

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
    	if(fecha != ""){
    		$("#cerrarCarga").fadeIn();
    		$("#boxscanner").fadeIn();
    	}
    });
    $("#datepicker").change(function(){
    	tienda = $("#selectTienda").data("kendoComboBox").value();
       	fecha = $("#datepicker").val();	
    	if(tienda != ""){
    		$("#cerrarCarga").fadeIn();
    		$("#boxscanner").fadeIn();
    	}
    });

    $("#cerrarCarga").click(function(){
    	var popupcierrecarga = $("#POPUP_Cierre_Carga");
        popupcierrecarga.data("kendoWindow").open();
        var grid = $("#grid");
        grid.data("kendoGrid").dataSource.read();
    });

    var dataSource = new kendo.data.DataSource({
	    transport: {
	        read: onRead
	    },
	    schema: {
	        model: {
	            id: "COURIER",
	            fields: {
	                    COURIER: {type: "string", editable: false}, // number - string - date
	                    INFORMADOS: {type: "string", editable: false}, // number - string - date
	                    ESCANEADOS: {type: "string", editable: false},
	                    TOTAL: {type: "string", editable: false}
	                }
	        }
	    },
	    pageSize: 100
	});

    var ventana_detalle_pp = $("#POPUP_Cierre_Carga");
    ventana_detalle_pp.kendoWindow({
        width: "1000px",
        height: "550px",
        title: "Detalle Cierre Carga",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();

    $("#grid").kendoGrid({
        autoBind: false,
        dataSource: dataSource,
        width: "100%",
        height: "92%",
        sortable: true, 
        filterable: true,
        scrollable: true,
        pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
        },
        columns: [
            {field: "COURIER",title: "COURIER",width: 100, filterable:false},
            {field: "INFORMADOS",title: "INFORMADOS",width:100, filterable:false},
            {field: "ESCANEADOS",title: "ESCANEADOS",width:100,filterable: false},
            {field: "TOTAL",title: "TOTAL",width: 100,filterable: false}
        ]
    });

    function onRead(e){
    	$.ajax({
            type: "POST",
            url: baseURL + 'lector/cierreCarga',
            dataType: 'json',
            data: {tienda: tienda, fecha: fecha},
            success: function(result){
            	e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }


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

	$("#Toolbar").kendoToolBar({
        items: [
            { type: "button", text: "Diferencias", icon: "k-icon k-i-search" ,click: Diferencias},
            { type: "button", text: "Cerrar Carga", icon: "k-icon k-i-check-circle" ,click: ExcelCierreCarga}
        ]
    });

    function Diferencias(){
    	var popupdetcierrecarga = $("#POPUP_det_Cierre_Carga");
        popupdetcierrecarga.data("kendoWindow").open();
        var grid = $("#gridDet");
        grid.data("kendoGrid").dataSource.read();
    }

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
            {field: "CUD",title: "CUD",width: 140, filterable:false},
            {field: "SKU",title: "SKU",width:100, filterable:false},
            {field: "SKU_DESC",title: "SKU DESC",width:140,filterable: false},
            {field: "FECHA",title: "FECHA",width: 80,filterable: false},
            {field: "TIENDA",title: "TIENDA",width: 80,filterable: false},
            {field: "DPTO",title: "DPTO",width: 70,filterable: false},
            {field: "OC",title: "OC",width: 80,filterable: false},
            {field: "GLOSA",title: "GLOSA",width: 140,filterable: false}
        ]
    });

    function onReadDet(e){
    	$.ajax({
            type: "POST",
            url: baseURL + 'lector/detcierreCarga',
            dataType: 'json',
            data: {tienda: tienda, fecha: fecha},
            success: function(result){
            	e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }

    var dataSource3  = new kendo.data.DataSource({
        transport:{
            read: onReadIds
        }
    });

    $("#selectId").kendoComboBox({
        dataSource: dataSource3,
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
            url: baseURL + 'lector/ids',
            type:"POST",
            dataType: "json",
            data: { tienda: tienda, fecha: fecha},
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
        window.location.href = baseURL + 'lector/resumen/'+id+'/'+tienda+'/'+fec;
    });

    function ExcelCierreCarga(){
        var popupcierrecarga = $("#POPUP_Id");
        popupcierrecarga.data("kendoWindow").open();
    }

});