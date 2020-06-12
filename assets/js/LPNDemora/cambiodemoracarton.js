$(document).ready(function(){
    var excel;
    var flagsave = false;
    kendo.culture("es-CL");
	var dataSource = new kendo.data.DataSource({
        transport: {
            read: onRead
        },
        schema: {
            model: {
                id: "LPN",
                fields: {
                        CARTON: {type: "string", editable: false}, // number - string - date
                        ESTADO_INICIAL: {
                            type: "number",
                            editable: false,
                            validation: {
                                min: 20,
                                max: 21
                            }
                        },
                        ESTADO_FINAL: {
                            type: "number",
                            validation: {
                                min: 20,
                                max: 21
                            }
                        },
                        FECHA: {type: "date"}, // number - string - date
                    }
            }
        },
        pageSize: 100
    });

	$("#toolbar").kendoToolBar({
        items: [
            { type: "button", text: "Importar", icon: "k-icon k-i-upload" ,click: Importar},
            { type: "button", text: "Guardar Cambios", icon: "k-icon k-i-save" ,click: GuardarCambios}
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
            {field: "CARTON",title: "CARTON",width: 140, filterable:false, resizable:false, height: 80},
            {field: "ESTADO_INICIAL",title: "ESTADO SISTEMA",width:70,filterable:false},
            {field: "ESTADO_FINAL",title: "ESTADO A SETEAR",width:70,filterable:false},
            {
                field: "FECHA",
                title: "FECHA LIBERACION A SETEAR",
                width:70,
                filterable: false,
                format: "{0:dd/MM/yyyy}",
                editor: dateTimeEditor2
            }
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
    $("#files").kendoUpload({
        multiple: false
    });
    function onRead(e){
        $.ajax({
          url: baseURL + 'CambioDemoraCarton/read',
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
                grid.setOptions({editable: true});
                e.success(result);
            }
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
              url: baseURL + 'CambioDemoraCarton/save',
              type: 'POST',
              data: {data: data},
              dataType: 'json',
              success: function(result){
                if(result == 0){
                    $("#success-modal").text("Proceso Finalizado. Cartones actualizados correctamente");
                    $("#modal-success").modal('show');
                    var grid = $("#grid");
                    grid.data("kendoGrid").dataSource.read();
                }else if(result == 2){
                    $("#error-modal").text("Existen Cartones en blanco en la planilla");
                    $("#modal-danger").modal('show');    
                }else if(result == 3){
                    $("#error-modal").text("Existen fechas en blanco en la planilla para el proceso de cambio de estado de 20 a 21");
                    $("#modal-danger").modal('show'); 
                }
                else{
                	$("#error-modal").text("Se esta intentando realizar una configuracion de estados invalida");
                    $("#modal-danger").modal('show'); 
                }
              },
              error: function(result){
                console.log('error');
              }
            });
        }
    }
});