$(document).ready(function(){

	var tienda = "";
  var id = "";
  var fecha = "";	
  var escaneos = 0;
  var motivo = "";

  $("#boxscanner").hide();
  $('#selectMotivo').kendoComboBox({});
  $("#cud").hide();

	$("#cud").on('change keyup', function(){
 
  	  		barcode =  $("#cud").val();
		      
      		if (barcode.length == 22){
                motivo = $('#selectMotivo').val();
                $("#cud").val('');
                if(motivo != 'Seleccione...'){
                    $.ajax({
                        type: "POST",
                        url: baseURL + 'lector/devolver',
                        dataType: 'json',
                        data: {barcode: barcode, motivo: motivo, tienda: tienda},
                        success: function(result){
                            escaneos++;
                            if(result == 1){
                                $("#boosmapinfo").prepend('<tr style="font-weight: bold; font-size: 90px; color: #990099;"><td style="font-size: 200px; text-align: center;" width="40%" colspan="3">CUD NO ENCONTRADO</td></tr>');
                                if(escaneos == 4){
                                    document.getElementById("boosmapinfo").deleteRow(escaneos - 1);
                                    escaneos = escaneos - 1;
                                }
                            }
                            if(result == 2){
                                $("#boosmapinfo").prepend('<tr style="font-weight: bold; font-size: 90px; color: #990099;"><td style="font-size: 200px; text-align: center;" width="40%" colspan="3">CUD AUN NO ESCANEADO</td></tr>');
                                if(escaneos == 4){
                                    document.getElementById("boosmapinfo").deleteRow(escaneos - 1);
                                    escaneos = escaneos - 1;
                                }
                            }
                            if(result == 3){
                                $("#boosmapinfo").prepend('<tr style="font-weight: bold; font-size: 90px; color: #990099;"><td style="font-size: 200px; text-align: center;" width="40%" colspan="3">CUD YA DEVUELTO</td></tr>');
                                if(escaneos == 4){
                                    document.getElementById("boosmapinfo").deleteRow(escaneos - 1);
                                    escaneos = escaneos - 1;
                                }
                            }
                            else{
                                if(result.length > 0){
                                    result.forEach(function(element){
                                      $("#boosmapinfo").prepend('<tr style="font-weight: bold; font-size: 90px; color: #990099;" id="'+element.CUD+'"><td style="text-align: center;" width="40%" >'+element.ARTICULO+'</td><td width="40%">'+element.DESCRIPCION+'</td><td width="20%">'+element.CANTIDAD+'</td></tr>');
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
                }else{
                    escaneos++;
                    $("#boosmapinfo").prepend('<tr style="font-weight: bold; font-size: 90px; color: #990099;"><td style="font-size: 200px; text-align: center;" width="40%" colspan="3">SELECCIONE UN MOTIVO</td></tr>');
                    if(escaneos == 4){
                        document.getElementById("boosmapinfo").deleteRow(escaneos - 1);
                        escaneos = escaneos - 1;
                    }
                }
      		}
    });

    

    $('#selectMotivo').change(function(){
        motivo = $('#selectMotivo').val();
        $("#cud").fadeIn();
    });
     $("#selectTienda").change(function(){
        tienda = $("#selectTienda").data("kendoComboBox").value();
        $("#boxscanner").fadeIn();
        cantDevueltos();
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
                        FECHA_DESPACHO: {type: "string", editable: false},
                        TIENDA_ORIGEN: {type: "string", editable: false},
                        FECHA_DEVUELTO: {type: "string", editable: false},
                        TIENDA_DEVUELTO: {type: "string", editable: false},
                        MOTIVO: {type: "string", editable: false}
                    }
            }
        },
        pageSize: 100
    });

    $("#devueltos").click(function(){
        var popupdetcierrecarga = $("#POPUP_det_devueltos");
        popupdetcierrecarga.data("kendoWindow").open();
        
    });

    var ventana_detalle_cc = $("#POPUP_det_devueltos");
    ventana_detalle_cc.kendoWindow({
        width: "1000px",
        height: "550px",
        title: "Resumen Devueltos",
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
        height: "90%",
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
            {field: "FECHA_DESPACHO",title: "FECHA DESPACHO",width: 80,filterable: false},
            {field: "TIENDA_ORIGEN",title: "TIENDA ORIGEN",width: 80,filterable: false},
            {field: "FECHA_DEVUELTO",title: "FECHA DEVOLUCION",width: 80,filterable: false},
            {field: "TIENDA_DEVUELTO",title: "TIENDA DEVOLUCION",width: 90,filterable: false},
            {field: "MOTIVO",title: "MOTIVO",width: 120,filterable: false}
        ]
    });

    function onReadDet(e){
        $.ajax({
            type: "POST",
            url: baseURL + 'lector/detdevueltos',
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
    $("#toolbardetdevueltos").kendoToolBar({
        items: [
            { template: '<div class="input-group date"><div class="input-group-addon"><i class="fa fa-calendar"></i></div><input type="text" class="form-control pull-right" id="datepicker" autocomplete="off"></div>' }
        ]
    });
    $('#datepicker').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy'
    });
    $("#datepicker").change(function(){
        fecha = $("#datepicker").val();   
        var grid = $("#gridDet");
        grid.data("kendoGrid").dataSource.read();
    });
    function cantDevueltos(){
        $.ajax({
            url: baseURL + 'lector/cantdevueltos',
            type:"POST",
            dataType: "json",
            success: function(result){
                $("#cantdevueltos").html('Devueltos: '+result);
            },
            error: function(result){
                $("#error-modal").text("Ocurrio un error durante el proceso, intentelo nuevamente");
                $("#modal-danger").modal('show');
            }
        });
    }
});

