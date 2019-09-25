var count = 0;    
var Header =[{DSP_LOCN:"",SKU_ID:"",MIN_INVN_QTY: "",MAX_INVN_QTY: "",PROCESS_DATE_TIME:"",MESSAGE:""}];
var data = [];
$(function() {
    var Cabecera = true;
    var funcion = '';
    var dataSource = new kendo.data.DataSource({
        batch: true,
        change: function(e){
            $("#save").toggleClass("k-state-disabled", !this.hasChanges());
        },
        requestStart: function(e){
            setTimeout(function (e){
               if(Cabecera){
                    Cabecera = false;
                    var spreadsheet = $("#spreadsheet").data("kendoSpreadsheet");
                    var sheet = spreadsheet.activeSheet();
                    sheet.frozenRows(1);

                    sheet.batch(function () { 
                            sheet.range("A1").value("Locacion");
                            sheet.range("B1").value("Articulo");
                            sheet.range("C1").value("Minimo");
                            sheet.range("D1").value("Maximo");
                            sheet.range("E1").value("Fecha Proceso");
                            sheet.range("F1").value("Mensaje");

                            sheet.range("A1").enable(false);
                            sheet.range("B1").enable(false);
                            sheet.range("C1").enable(false);
                            sheet.range("D1").enable(false);
                            sheet.range("E1").enable(false);
                            sheet.range("F1").enable(false);

                        }, {recalc: true});
                }
            }, 0);
        }, 
        transport:{
            read: onRead,
            submit: onSubmit,
            create: function(e){
                header.push(e.data)
                e.success({"Data":[e.data]});
            }
        },
        schema: {
            model: {
                id: "Data",
                fields:{
                    DSP_LOCN: {type: "string"},
                    SKU_ID: {type: "string"},
                    MIN_INVN_QTY:   {type: "number"},
                    MAX_INVN_QTY:   {type: "number"},
                    PROCESS_DATE_TIME:   {type: "string"},
                    MESSAGE:   {type: "string"}
                }
            }
        }
    });
    

    $("#spreadsheet").kendoSpreadsheet({
        columns: 6,
        rows: 10000,
        sheetsbar: false,
        toolbar: {
                home:[
                {
                    type: "button",
                    text: "Limpiar",
                    showText: "both",
                    icon: "k-icon k-i-file",
                    click: Recargar
                },
                 {
                    type: "button",
                    text: "Consultar",
                    showText: "both",
                    icon: "k-icon k-i-search",
                    click: Filtrar
                },
                {
                    type: "button",
                    text: "Guardar",
                    showText: "both",
                    icon: "k-icon k-i-save",
                    click: Guardar
                },
                {
                    type: "button",
                    text: "Importar",
                    showText: "both",
                    icon: "k-icon k-i-file-excel",
                    click: Importar  
                }
                ],
                insert: false,
                data: false
        },
        sheets: [
            {
                name: "Ssheet",
                dataSource: dataSource,
                rows: [
                   {
                      
                        cells: [
                            { field: "Locacion", 
                              background: "rgb(167,214,255)", 
                              textAlign: "center", 
                              color: "rgb(0,62,117)", 
                              bold: true
                            },
                            { field: "Articulo", 
                              background: "rgb(167,214,255)", 
                              textAlign: "center", 
                              color: "rgb(0,62,117)", 
                              bold: true
                            },
                            { field: "Minimo", 
                              background: "rgb(167,214,255)", 
                              textAlign: "center", 
                              color: "rgb(0,62,117)", 
                              bold: true
                            },
                            { field: "Maximo", 
                              background: "rgb(167,214,255)", 
                              textAlign: "center", 
                              color: "rgb(0,62,117)", 
                              bold: true    
                            },
                            { field: "Fecha Proceso", background: "rgb(167,214,255)", textAlign: "center", color: "rgb(0,62,117)", bold: true},
                            { field: "Mensaje", background: "rgb(167,214,255)", textAlign: "center", color: "rgb(0,62,117)", bold: true}
                        ]
                    }
                 ],
                columns: [
                    {width: 215},
                    {width: 215},
                    {width: 115},
                    {width: 115},
                    {width: 150},
                    {width: 490}
                ]
            }
        ]
    });
    function onRead(e){
        console.log('read');
        if (count == 0) {
            e.success(Header);
        }else{
            if(count == 1){
                $.ajax({
                    type: "POST",
                    url: baseURL + 'articulolocacion/articulo_locacion/readArtLocacion',
                    dataType: 'json',
                    success: function(result){
                        e.success(result);
                        count = 0;
                    },
                    error: function(result){
                        alert(JSON.stringify(result));
                    }
                });

            }
            else if(count == 2){
                var dateObjIni = fechaIni.value();
                var dateObjFin = fechaFin.value();
                var fecIni = kendo.toString(dateObjIni, "dd/MM/yy");
                var fecFin = kendo.toString(dateObjFin, "dd/MM/yy");
                $.ajax({
                        type: "POST",
                        url: baseURL + 'articulolocacion/articulo_locacion/filtrarDatos',
                        data:{ fecIni: fecIni, fecFin: fecFin},
                        dataType: 'json',
                        success: function(result){
                            e.success(result);
                            var popupfactor = $("#POPUP_filtrar");
                            popupfactor.data("kendoWindow").close();
                            count = 2;
                        },
                        error: function(result){
                            alert(JSON.stringify(result));
                        }
                });
            }
            else if(count == 3){
                if(data == 0){
                    e.success(data);
                    var popupNotification = $("#popupNotification").kendoNotification().data("kendoNotification");
                    var popupfactor = $("#POPUP_importar");
                    popupfactor.data("kendoWindow").close();
                    popupNotification.getNotifications().parent().remove();
                    popupNotification.show(" El archivo debe tener una extencion excel valida (.xls, .xlsx)", "error");
                    data = [];
                }else if(data == 1){
                    e.success(data);
                    var popupNotification = $("#popupNotification").kendoNotification().data("kendoNotification");
                    var popupfactor = $("#POPUP_importar");
                    popupfactor.data("kendoWindow").close();
                    popupNotification.getNotifications().parent().remove();
                    popupNotification.show(" Debe seleccionar un archivo para importar", "error");
                    data = [];
                }else{
                    var popupNotification = $("#popupNotification").kendoNotification().data("kendoNotification");
                    var popupfactor = $("#POPUP_importar");
                    popupfactor.data("kendoWindow").close();
                    e.success(data);
                    popupNotification.getNotifications().parent().remove();
                    popupNotification.show(" Archivo Importado Correctamente", "success");
                    data = [];
                } 
            }
        }
    }
    function Guardar(){
        if(count != 2){
            count = 1;
            if (!$(this).hasClass("k-state-disabled")) {

                var spreadsheet = $("#spreadsheet").data("kendoSpreadsheet");
                var sheet = spreadsheet.activeSheet();
                sheet.dataSource.sync();
              }
        }
        else{
            var popupNotification = $("#popupNotification").kendoNotification().data("kendoNotification");
            popupNotification.getNotifications().parent().remove();
            popupNotification.show(" No puede guardar data consultada, debe limpiar y volver a ingresarla", "error"); 
        } 
    }
    function Recargar(){
        count = 0;
        var spreadsheet = $("#spreadsheet").data("kendoSpreadsheet");
        var sheet = spreadsheet.activeSheet();
        sheet.dataSource.read();
    }
    function Filtrar(){
        var POPUPFiltrar = $("#POPUP_filtrar");
        POPUPFiltrar.data("kendoWindow").open();
    }
    function Importar(){
        var POPUPImportar = $("#POPUP_importar");
        POPUPImportar.data("kendoWindow").open();
    }
    function onSubmit(e){
        var popupNotification = $("#popupNotification").kendoNotification().data("kendoNotification");
        var arregloGuardado = [];
        var i = 0;
        var ok = 0;
        
    
        for (i; i < e.data.created.length; i++) {
            if(e.data.created[i]["DSP_LOCN"] == ""){
                popupNotification.show(" Debe ingresar una Locacion en la linea "+(i+2)+".", "error");
                ok = 1;
            }
            if(e.data.created[i]["SKU_ID"] == ""){
                popupNotification.show(" Debe ingresar un Articulo en la linea "+(i+2)+".", "error");
                ok = 1;
            }
            if(e.data.created[i]["MIN_INVN_QTY"] == null){
                popupNotification.show(" Debe ingresar un valor Minimo valido en la linea "+(i+2)+".", "error");
                ok = 1;
            }
            if(e.data.created[i]["MAX_INVN_QTY"] == null){
                popupNotification.show(" Debe ingresar un valor Maximo valido en la linea "+(i+2)+".", "error");
                ok = 1;
            }
            if(e.data.created[i]["MAX_INVN_QTY"]<e.data.created[i]["MIN_INVN_QTY"]){
                popupNotification.show(" El valor Maximo no puede ser menor que el valor Minimo en la linea "+(i+2)+".", "error");
                ok = 1;
            }
            arregloGuardado.push({

                "Locacion":String(e.data.created[i]["DSP_LOCN"]),
                "Articulo":String(e.data.created[i]["SKU_ID"]),
                "Minimo":kendo.parseInt(e.data.created[i]["MIN_INVN_QTY"]),
                "Maximo":kendo.parseInt(e.data.created[i]["MAX_INVN_QTY"])
            });
        }
        
        if(ok == 0){
            $.post(baseURL + 'articulolocacion/articulo_locacion/insertarArtLocacion',{models: kendo.stringify(arregloGuardado)},function(data){
                if(data == 0){
                    var spreadsheet = $("#spreadsheet").data("kendoSpreadsheet");
                    var sheet = spreadsheet.activeSheet();
                    sheet.dataSource.read();

                    popupNotification.getNotifications().parent().remove();
                    popupNotification.show(" Cambios Almacenados Correctamente.", "success");
                }else{
                    popupNotification.getNotifications().parent().remove();
                    popupNotification.show(" Error en el Guardado.", "error");
                }
            });
        
        }  
       
    }
    var ventana_filtrar = $("#POPUP_filtrar");
    ventana_filtrar.kendoWindow({
        width: "300px",
        title: "Filtrar",
        visible: false,
        actions: [
            "Close"
        ]
    }).data("kendoWindow").center();
    var ventana_importar = $("#POPUP_importar");
    ventana_importar.kendoWindow({
        width: "300px",
        title: "Importar Archivo Excel",
        visible: false,
        actions: [
            "Close"
        ]
    }).data("kendoWindow").center();
    $("#DPFechaIni").kendoDatePicker({format: "dd/MM/yyyy"});

    var fechaIni = $("#DPFechaIni").data("kendoDatePicker");

    $("#DPFechaFin").kendoDatePicker({format: "dd/MM/yyyy"});

    var fechaFin = $("#DPFechaFin").data("kendoDatePicker");

    $("#btn_filtrar").click(function(){
        count = 2;
        var spreadsheet = $("#spreadsheet").data("kendoSpreadsheet");
        var sheet = spreadsheet.activeSheet();
        sheet.dataSource.read();

    });
    $("#files").kendoUpload();
    $("#import_form").on('submit' ,function(e){
        var spreadsheet = $("#spreadsheet").data("kendoSpreadsheet");
        var sheet = spreadsheet.activeSheet();
        e.preventDefault();
        count = 3; 
        $.ajax({
          url: baseURL + 'articulolocacion/articulo_locacion/importarEXCEL',
          type: 'POST',
          data: new FormData(this),
          dataType: 'json',
          contentType: false,
          cache: false,
          processData: false,
          success: function(result){
            $("#files").val('');
            data = result;
            console.log(count);
            console.log(data);
            sheet.dataSource.read();
            var upload = $("#files").data("kendoUpload");
            upload.removeAllFiles();
          },
          error: function(result){
            console.log('fuck this shit')
          }
        });
        
    });
});