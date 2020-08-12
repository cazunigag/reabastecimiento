$(document).ready(function(){

	var tienda = "";
    var fecha = "";	
    var escaneos = 0;

    var cud = "";
    var opl = "";
    var id = "";
    var conductor = "";
    var empresa = "";
    var patente = "";

    $("#scanner").hide();
    $("#ruta").hide(); 
    $("#datosTransporte").hide();

	$("#cud").on('change keyup', function(){

  		barcode =  $("#cud").val();
	      
  		if (barcode.length == 22){
  			$("#respuesta").empty();
  			cud = $("#cud").val();
  			console.log(cud);
            $("#cud").val('');
	        $.ajax({
	            type: "POST",
	            url: baseURL + 'lector/datacud',
	            dataType: 'json',
	            data: {barcode: barcode},
	            success: function(result){
                    escaneos ++;
                    console.log(escaneos);
                    if(escaneos == 1){
                        result.forEach(function(element){
                            $("#data").empty();
                            $("#comuna").html('COMUNA: '+element.DESCRIPCION_COMUNA);
                        });
                    }else if(escaneos == 2){
                        console.log('entro');
                        result.forEach(function(element){
                            console.log(element.INFORMADO);
                            if(element.INFORMADO == 'F'){
                                $.ajax({
                                    url: baseURL + 'lector/guardarinfodesp',
                                    type:"POST",
                                    data: {barcode: cud, id: id, chofer: conductor, empresa: empresa, patente: patente, fecha: fecha, tienda: tienda},
                                    dataType: "json",
                                    success: function(result){
                                        if(result == 1){
                                            escaneos = 0;
                                            $("#comuna").empty();
                                            $("#data").empty();
                                            $("#data").prepend('<tr><td colspan = "3" style="font-size: 200px; font-weight: bold; text-align: center; color: #990099;" >RUTA GUARDADA CORRECTAMENTE</td></tr>');
                                        }else if(result == 2){
                                            $("#error-modal").text("Ocurrio un error en el guardado, intentelo nuevamente");
                                            $("#modal-danger").modal('show');
                                        }
                                    },
                                    error: function(result){
                                        $("#error-modal").text("Ocurrio un error en el guardado, intentelo nuevamente");
                                        $("#modal-danger").modal('show');
                                    }
                                });
                            }
                            else if(element.INFORMADO == 'T'){
                                var ok = confirm('Cud ya posee ruta. Desea actualizar la data de despacho?');

                                if(ok){
                                    $.ajax({
                                        url: baseURL + 'lector/guardarinfodesp',
                                        type:"POST",
                                        data: {barcode: cud, id: id, chofer: conductor, empresa: opl, patente: patente, fecha: fecha, tienda: tienda},
                                        dataType: "json",
                                        success: function(result){
                                            if(result == 1){
                                                escaneos = 0;
                                                $("#comuna").empty();
                                                $("#data").empty();
                                                $("#data").prepend('<tr><td colspan = "3" style="font-size: 200px; font-weight: bold; text-align: center; color: #990099;" >RUTA GUARDADA CORRECTAMENTE</td></tr>');
                                            }else if(result == 2){
                                                $("#error-modal").text("Ocurrio un error en el guardado, intentelo nuevamente");
                                                $("#modal-danger").modal('show');
                                            }
                                        },
                                        error: function(result){
                                            $("#error-modal").text("Ocurrio un error en el guardado, intentelo nuevamente");
                                            $("#modal-danger").modal('show');
                                        }
                                    });
                                }
                            }
                            else if(result == 3){
                                $("#form").hide();
                                $("#formfooter").hide();
                                $("#respuesta").fadeIn();
                                $("#respuesta").empty();
                                $("#respuesta ").prepend('<div style="font-size: 200px; font-weight: bold; text-align: center; color: #990099;" class="col-xs-12">CUD NO EXISTE</div>');
                            }
                        });
                    }
	            },
	            error: function(result){
	                alert(JSON.stringify(result));
	            }
	        });
  		}
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
            $("#selectOPL").data("kendoComboBox").dataSource.read();
            $("#scanner").fadeIn();
        }
    });
    $("#datepicker").change(function(){
        tienda = $("#selectTienda").data("kendoComboBox").value();
        fecha = $("#datepicker").val(); 
        if(tienda != ""){
            $("#selectOPL").data("kendoComboBox").dataSource.read();
            $("#scanner").fadeIn();
        }
    });
    $("#selectOPL").change(function(){
        opl = $("#selectOPL").data("kendoComboBox").value();
        if(opl != ""){
            $("#selectId").data("kendoComboBox").dataSource.read();
            $("#ruta").fadeIn();
        }
    });

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
    $("#selectId").change(function(){
        id = $("#selectId").data("kendoComboBox").value();
        $.ajax({
            url: baseURL + 'lector/datosTransporte',
            type:"POST",
            dataType: "json",
            data: {id: id, tienda: tienda, fecha: fecha},
            success: function(result){
                $("#datosTransporte").fadeIn();
                result.forEach(function(element){
                    conductor = element.CHOFER;
                    empresa = element.NOMBRE_TRANSPORTISTA;
                    patente = element.PATENTE;
                    $("#data").empty();
                    $("#data").append('<tr style="font-size: 40px; "><td>'+element.CHOFER+'</td><td>'+opl+'</td><td>'+element.PATENTE+'</td></tr>')
                });
            },
            error: function(result){
                $("#error-modal").text("Ocurrio un error durante el proceso, intentelo nuevamente");
                $("#modal-danger").modal('show');
            }
        });
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