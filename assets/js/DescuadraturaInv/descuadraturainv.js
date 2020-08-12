$(document).ready(function(){

    var excel;

    var data_grafico = [];

    var ok = confirm('Desea actualizar los datos de descuadratura actuales?');

    kendo.culture("es-CL");

    var dataSource = new kendo.data.DataSource({
        transport: {
            read: onRead
        },
        schema: {
            model: {
                id: "CUD",
                fields: {
                    CUD: {type: "string", editable: false}, // number - string - date
                    ARTICULO_CORTO: {type: "string", editable: false}, // number - string - date
                    ARTICULO_LARGO: {type: "string", editable: false},
                    DESCRIPCION_ARTICULO: {type: "string", editable: false},
                    CANTIDAD: {type: "string", editable: false},
                    SUCURSAL_STOCK: {type: "string", editable: false},
                    SUCURSAL_DESPACHO: {type: "string", editable: false},
                    NOMBRE_CLIENTE: {type: "string", editable: false},
                    DIRECCION_DESPACHO: {type: "string", editable: false},
                    FECHA_VENTA: {type: "string", editable: false},
                    FECHA_PLANIFICACION: {type: "string", editable: false},
                    ESTADO: {type: "string", editable: false},
                    MOTIVO: {type: "string", editable: false},
                    RESERVA: {type: "string", editable: false},
                    FECHA_RESERVA: {type: "string", editable: false},
                    STOCK_VENDIBLE: {type: "string", editable: false},
                    ESTADO_INTERNO: {type: "string", editable: false},
                    DISP_CASE_PICK: {type: "string", editable: false},
                    DISP_ACTIVO: {type: "string", editable: false},
                    RESERVA: {type: "string", editable: false},
                    PP: {type: "string", editable: false},
                    TOTAL: {type: "string", editable: false}
                }
            }
        },
        pageSize: 100
    });

    $("#grid").kendoGrid({
        autoBind: false,
        dataSource: dataSource,
        height: "500px", 
        width: "100%",
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
            {field: "ARTICULO_CORTO",title: "ARTICULO CORTO",width:80, filterable:false},
            {field: "ARTICULO_LARGO",title: "ARTICULO LARGO",width:110, filterable:false},
            {field: "DESCRIPCION_ARTICULO",title: "DESCRIPCION ARTICULO",width:140,filterable: false},
            {field: "CANTIDAD",title: "CANTIDAD",width: 70,filterable: false},
            {field: "SUCURSAL_STOCK",title: "SUC STOCK",width:60,filterable: false},
            {field: "SUCURSAL_DESPACHO",title: "SUC DESP",width:60,filterable: false},
            {field: "NOMBRE_CLIENTE",title: "NOMBRE CLIENTE",width:120,filterable: false},
            {field: "DIRECCION_DESPACHO",title: "DIR DESP",width:140,filterable: false},
            {field: "FECHA_VENTA",title: "FECHA VENTA",width:70,filterable: false},
            {field: "FECHA_PLANIFICACION",title: "FECHA PLAN",width:70,filterable: false},
            {field: "ESTADO",title: "ESTADO",width:80,filterable: false},
            {field: "MOTIVO",title: "MOTIVO",width:80,filterable: false},
            {field: "RESERVA",title: "RESERVA",width:80,filterable: false},
            {field: "FECHA_RESERVA",title: "FECHA RESERVA",width:70,filterable: false},
            {field: "STOCK_VENDIBLE",title: "VENDIBLE",width:70,filterable: false},
            {field: "ESTADO_INTERNO",title: "ESTADO INT",width:80,filterable: false},
            {field: "DISP_CASE_PICK",title: "CASE PICK",width:70,filterable: false},
            {field: "DISP_ACTIVO",title: "ACTIVO",width:70,filterable: false},
            {field: "RESERVA",title: "RESERVA",width:70,filterable: false},
            {field: "PP",title: "PP",width:70,filterable: false},
            {field: "TOTAL",title: "TOTAL",width:70,filterable: false}
        ]
    });

    function onRead(e){
        $.ajax({
            type: "GET",
            url: baseURL + 'descuadraturainv/data',
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }

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

    $("#importar").click(function(){
        var popupfactor = $("#POPUP_Importar");
        popupfactor.data("kendoWindow").close(); 
    });

    if(ok){

        var POPUPImportar = $("#POPUP_Importar");
        POPUPImportar.data("kendoWindow").open();

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
                url: baseURL + 'descuadraturainv/actualizar',
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
                        var grid = $("#grid");
                        grid.data("kendoGrid").dataSource.read();
                    }
                },
                error: function(result){
                    alert(JSON.stringify(result));
                }
            });
        });

    }else{
        var grid = $("#grid");
        grid.data("kendoGrid").dataSource.read();
    }

    $("#toolbar").kendoToolBar({
        items: [
            { type: "button", text: "Grafico Resumen", icon: "k-icon k-i-arrows-dimensions" ,click: Graficar}
        ]
    });

    var ventana_grafico = $("#POPUP_Grafico");
    ventana_grafico.kendoWindow({
        width: "900px",
        height: "550px",
        title: "Grafico Diferencia Inventario",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();

    var dataSourceGrafico = new kendo.data.DataSource({
        transport: {
            read: onReadGrafico
        },
        schema: {
            model: {
                id: "CUD",
                fields: {
                    FECHA: {type: "string", editable: false}, // number - string - date
                    POR_TOTAL: {type: "number", editable: false}, // number - string - date
                    POR_STS: {type: "number", editable: false}
                }
            }
        },
        pageSize: 100
    });

    function Graficar(){
    	$("#line-chart").kendoChart({
            dataSource: dataSourceGrafico,
            legend: {
                    position: "bottom"
            },
            title: {
                text: 'Diferencia Inventario'
            },
            legend: {
                position: "top"
            },
            seriesDefaults: {
                type: "line",
                style: "smooth"
            },
            series: [{
                field: "POR_TOTAL",
                name: "PORCENTAJE TOTAL",
                color: "red"
            }, {
                field: "POR_STS",
                name: "PORCENTAJE STS",
                color: "blue"
            }],
            categoryAxis: {
            	field: "FECHA",
                crosshair: {
                    visible: true
                },
                labels:{
                    rotation: 315
                }
            },
            valueAxis: {
                labels: {
                    format: "{0}%"
                },
                line: {
                    visible: false
                },
                axisCrossingValue: -10
            },
            tooltip: {
                visible: true,
                shared: true,
                format: "{0}%"
            }
        });
    	var ventana_grafico = $("#POPUP_Grafico");
        ventana_grafico.data("kendoWindow").open();
    }

    

    function onReadGrafico(e){
        $.ajax({
            url: baseURL + 'descuadraturainv/grafico',
            type: 'GET',
            dataType: 'json',
            processData: false,
            success: function(result){
              if(result.length == 0){
                  $("#error-modal").text("Ocurrio un error al cargar la pagina");
                  $("#modal-danger").modal('show');
              }else if(result.length != 0){
                  result.forEach(function(element){
                      data_grafico.push({y: element.FECHA.toString(), POR_TOTAL: element.POR_TOTAL, POR_STS: element.POR_STS});
  
                  });
                  console.log(data_grafico);
                  e.success(result);
              }
            },
            error: function(result){
              console.log('error');
            }
        });
    }

});