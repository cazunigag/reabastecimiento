$(document).ready(function(){
	var excel;
    var flagsave = false;
    var flagverificar = false;
    kendo.culture("es-CL");
	var dataSource = new kendo.data.DataSource({
        transport: {
            read: onRead
        },
        schema: {
            model: {
                id: "LOCN",
                fields: {
                        LOCN: {type: "string", editable: false},
                        FECHA: {type: "date"}, // number - string - date
                        SYSFECHA: {type: "date", editable: false}
                    }
            }
        },
        pageSize: 100
    });

    var dataSource2 = new kendo.data.DataSource({
        transport: {
            read: onRead2
        },
        schema: {
            model: {
                id: "DSP_LOCN",
                fields: {
                        DSP_LOCN: {type: "string", editable: false},
                        FECHA_LIBERACION_ACTUAL_LOCN: {type: "string", editable: false}, // number - string - date
                        CARTON_NBR: {type: "string", editable: false},
                        FECHA_LIBERACION_ACTUAL_CARTON: {type: "string", editable: false},
                        STAT_CODE: {type: "string", editable: false},
                        CODE_DESC: {type: "string", editable: false}
                    }
            }
        },
        pageSize: 100
    });

	$("#toolbar").kendoToolBar({
        items: [
            { type: "button", text: "Importar", icon: "k-icon k-i-upload" ,click: Importar},
            { type: "button", text: "Aplicar Cambios", icon: "k-icon k-i-save" ,click: GuardarCambios},
            { type: "button", text: "Cartones Afectados", icon: "k-icon k-i-search" ,click: DetalleCartones}
        ]
    });

	$("#grid").kendoGrid({
        editable: true,
        autoBind: false,
        dataSource: dataSource,
        height: "530px", 
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
            {field: "LOCN",title: "UBICACION",width: 140, filterable:false, resizable:false, height: 80},
            {
                field: "FECHA",
                title: "FECHA LIBERACION A SETEAR",
                width:70,
                filterable: false,
                format: "{0:dd/MM/yyyy}",
                editor: dateTimeEditor2
            },
            {
                field: "SYSFECHA",
                title: "FECHA LIBERACION SISTEMA",
                width:70,
                filterable: false,
                format: "{0:dd/MM/yyyy}"
            }
        ]
    });
    $("#grid2").kendoGrid({
        editable: true,
        autoBind: false,
        dataSource: dataSource2,
        height: "100%", 
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
            {field: "DSP_LOCN",title: "UBICACION",width: 140, filterable:false, resizable:false, height: 80},
            {field: "FECHA_LIBERACION_ACTUAL_LOCN",title: "FECHA LIBERACION ACTUAL UBICACION",width: 140, filterable:false, resizable:false, height: 80},
            {field: "CARTON_NBR",title: "CARTON",width: 140, filterable:false, resizable:false, height: 80},
            {field: "FECHA_LIBERACION_ACTUAL_CARTON",title: "FECHA LIBERACION ACTUAL CARTON",width: 140, filterable:false, resizable:false, height: 80},
            {field: "STAT_CODE",title: "ESTADO",width: 140, filterable:false, resizable:false, height: 80},
            {field: "CODE_DESC",title: "DESCRIPCION",width: 140, filterable:false, resizable:false, height: 80}
           
        ]
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
    var ventana_detalles = $("#POPUP_Detalle_Locns");
    ventana_detalles.kendoWindow({
        width: "900px",
        height: "600px",
        title: "Cartones Afectados",
        visible: false,
        actions: [
            "Close"
        ]
    }).data("kendoWindow").center();
    $("#files").kendoUpload({
        multiple: false
    });
    function onRead(e){
        $.ajax({
          url: baseURL + 'CambioDemoraLOCN/read',
          type: 'POST',
          data: excel,
          dataType: 'json',
          contentType: false,
          cache: false,
          processData: false,
          success: function(result){
            $("#files").val('');
            console.log(result.length);
            if(result.length == 0){
                var upload = $("#files").data("kendoUpload");
                upload.removeAllFiles();
                $("#error-modal").text("Ocurrio un error al cargar la pagina");
                $("#modal-danger").modal('show');
                var popupfactor = $("#POPUP_Importar");
                popupfactor.data("kendoWindow").close();
            }else if(result.length != 0){
                var array = [];
                var upload = $("#files").data("kendoUpload");
                upload.removeAllFiles();
                var grid = $("#grid").data("kendoGrid");
                flagsave = true;
                flagverificar = true;
                grid.setOptions({editable: true});
                e.success(result);
            }
          },
          error: function(result){
            console.log('error');
          }
        });
    }
    function onRead2(e){
        var data = JSON.stringify(dataSource.data());
        $.ajax({
          url: baseURL + 'CambioDemoraLOCN/detalleLocns',
          type: 'POST',
          data: {data: data},
          dataType: 'json',
          success: function(result){
            e.success(result);
          },
          error: function(result){
            console.log('error');
          }
        });
    }
    function Importar(){
    	var POPUPImportar = $("#POPUP_Importar");
        POPUPImportar.data("kendoWindow").open();
    }
    $("#import_form").on('submit' ,function(e){
        excel = new FormData(this);
        console.log(excel);
        e.preventDefault();
        var grid = $("#grid");
        grid.data("kendoGrid").dataSource.read();
    });
    function dateTimeEditor2(container, options) {
        $('<input name="'+options.field+'" />')
            .appendTo(container)
            .kendoDatePicker({
                autoclose: true,
                format: 'dd/MM/yyyy'
            });
    }
    $("#importar").click(function(){
        var popupfactor = $("#POPUP_Importar");
        popupfactor.data("kendoWindow").close(); 
    });
    function GuardarCambios(){
        var data = JSON.stringify(dataSource.data());
        if(flagsave){
            $.ajax({
              beforeSend: function () {
                  $(".modalloading").show();
              },
              complete: function () {
                  $(".modalloading").hide();
              },
              url: baseURL + 'CambioDemoraLOCN/save',
              type: 'POST',
              data: {data: data},
              dataType: 'json',
              success: function(result){
                if(result == 0){
                    $("#success-modal").text("Proceso Finalizado. Ubicaciones actualizadas correctamente");
                    $("#modal-success").modal('show');
                }else if(result == 1){
                    $("#error-modal").text("Existen Ubicaciones en blanco en la planilla");
                    $("#modal-danger").modal('show');    
                }
              },
              error: function(result){
                console.log('error');
              }
            });
        }
    }
    function DetalleCartones(){
        if(flagverificar){
            var popupverificarpo = $("#POPUP_Detalle_Locns");
            popupverificarpo.data("kendoWindow").open();
            var grid = $("#grid2");
            grid.data("kendoGrid").dataSource.read();   
        } 
    }
});