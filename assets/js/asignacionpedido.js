$(document).ready(function () {
            let test = [];
            var skus = "";
            var selectedSku = [];
            var idField = "ARTICULO";
            var dataSource = new kendo.data.DataSource({
                pageSize: 100,
                transport: {
                    read:  onRead
                },
                schema: {
                    model: {
                        id: "ARTICULO",
                        fields: {
                                ARTICULO: {type: "string"},
                                CODIGO_BARRA: {type: "string"},
                                DESCRIPCION: {type: "string"},
                                DEPTO: {type: "string"},
                                TIPO_MERC: {type: "string"},
                                PREFIJO_LOCN_BRCD: {type: "string"},
                                CANTIDAD_SOLICITDA_PKT: {type: "string"},
                                CANTIDAD_SOLICITADA_DISTRO: {type: "string"},
                                TOTAL_SOLICITADO: {type: "string"},
                                MODA: {type: "string"},
                                TOTAL_CASEPICK: {type: "string"},
                                TOTAL_RELLENAR_CASEPICK: {type: "string"},
                                TOTAL_PICKEAR_CASEPICK: {type: "string"},
                                TOTAL_NO_CASEPICK: {type: "string"},
                                TOTAL_RELLENAR_NO_CASEPICK: {type: "string"},
                                TOTAL_PICKEAR_NO_CASEPICK: {type: "string"},
                                TOTAL_ACTIVO: {type: "string"},
                                TOTAL_RESERVA: {type: "string"},
                                TOTAL_BLOQUEO: {type: "string"},
                                SALDO_DISTRO_ACT: {type: "string"},
                                TOTAL_SOLICITUDES: {type: "string"},
                                TOTAL_ACTIVO_DISPONIBLE: {type: "string"},
                                REQUIERE_REABASTECIMIENTO: {type: "string"},
                                ACCION: {type: "string"},
                                CREATE_DATE_TIME: {type: "string"},
                                MOD_DATE_TIME: {type: "string"},
                                USER_ID:{type: "string"}

                            }
                    }
                }
                
            });
            $("#grid").kendoGrid({
                dataSource: dataSource,
                height: 600,
                groupable: false,
                sortable: true,
                filterable: true,
                persistSelection: true,
                pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
                },
                change: function (e, args) {
                    skus = ("'"+this.selectedKeyNames().join("','")+"'");
                    console.log(this.selectedKeyNames().join("','"));
                },
                columns: [
                {selectable: true, width: "50px" },
                {
                    field: "ARTICULO",
                    title: "ARTICULO",
                    width: 105,
                    filterable: {multi: true, search: true}
                }, 
                {
                   
                    field: "CODIGO_BARRA",
                    title: "CODIGO BARRA",
                    width: 120,
                    filterable: false
                }, 
                {
                    
                    field: "DESCRIPCION",
                    title: "DESCRIPCION",
                    width: 300,
                    filterable: false
                },
                { 
                    field: "DEPTO",
                    title: "DEPTO",
                    width: 60,
                    filterable: false
                },
                {
                    field: "TIPO_MERC",
                    title: "TIPO MERC",
                    width: 50,
                    filterable: false
                },
                    {
                    
                    field: "PREFIJO_LOCN_BRCD",
                    title: "PREFIJO LOCACION",
                    width: 80,
                    filterable: false
                },
                {
                    
                    field: "CANTIDAD_SOLICITDA_PKT",
                    title: "CANT SOL PKT",
                    width: 70,
                    filterable: false
                },
                {
                    field: "CANTIDAD_SOLICITADA_DISTRO",
                    title: "CANT SOL DISTRO",
                    width: 75,
                    filterable: false
                },
                {
                    
                    field: "TOTAL_SOLICITADO",
                    title: "TOTAL SOLICITADO",
                    width: 90,
                    filterable: false
                },
                {
                    
                    field: "MODA",
                    title: "MODA",
                    width: 60,
                    filterable: false
                },
                {
                   
                    field: "TOTAL_CASEPICK",
                    title: "TOTAL CASEPICK",
                    width: 75,
                    filterable: false
                },
                {
                   
                    field: "TOTAL_RELLENAR_CASEPICK",
                    title: "TOT RELLENAR CASEPICK",
                    width: 105,
                    filterable: false
                },
                {
                   
                    field: "TOTAL_PICKEAR_CASEPICK",
                    title: "TOT PICK CASEPICK",
                    width: 80,
                    filterable: false
                },
                {
                    field: "TOTAL_NO_CASEPICK",
                    title: "TOT NO CASEPICK",
                    width: 80,
                    filterable: false
                },
                {
                    
                    field: "TOTAL_RELLENAR_NO_CASEPICK",
                    title: "TOT RELLENAR NO CASEPICK",
                    width: 106,
                    filterable: false
                },
                {
                    
                    field: "TOTAL_ACTIVO",
                    title: "TOTAL ACTIVO",
                    width: 70,
                    filterable: false
                },
                {
                    
                    field: "TOTAL_RESERVA",
                    title: "TOTAL RESERVA",
                    width: 70,
                    filterable: false
                },
                {
                   
                    field: "TOTAL_BLOQUEO",
                    title: "TOTAL BLOQUEO",
                    width: 75,
                    filterable: false
                },
                {
                    field: "SALDO_DISTRO_ACT",
                    title: "SALDO DISTRO ACT",
                    width: 86,
                    filterable: false
                },
                {
                    field: "TOTAL_SOLICITUDES",
                    title: "TOTAL SOLICITUDES",
                    width: 100,
                    filterable: false
                },
                {
                    field: "TOTAL_ACTIVO_DISPONIBLE",
                    title: "TOT ACT DISPONIBLE",
                    width: 90,
                    filterable: false
                },
                {
                    field: "REQUIERE_REABASTECIMIENTO",
                    title: "REQ REAB",
                    width: 50,
                    filterable: false
                },
                {
                    field: "ACCION",
                    title: "ACCION",
                    width: 110,
                    filterable: false
                },
                {
                    field: "CREATE_DATE_TIME",
                    title: "FEC CRE",
                    width: 70,
                    filterable: false
                },
                {
                    field: "MOD_DATE_TIME",
                    title: "FEC MOD",
                    width: 70,
                    filterable: false
                },
                {
                    field: "USER_ID",
                    title: "ID USUARIO",
                    width: 90,
                    filterable: false
                }
                ]
            });

    function onRead(e){
        console.log(e);
        $.ajax({
            type: "POST",
            url: baseURL + 'index.php/asignacionpedido/asignacion_pedido/getAsignaciones',
            dataType: 'json',
            success: function(result){
                e.success(result);
            },
            error: function(result){
                alert(JSON.stringify(result));
            }
        });
    }

    var ventana_lead_time = $("#POPUP_seleccionarSKU");
    ventana_lead_time.kendoWindow({
        width: "750px",
        height: "550px",
        title: "Actualizar",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"     
        ]
    }).data("kendoWindow").center();

    function Actualizar(e){
        var popupNotification = $("#popupNotification").kendoNotification().data("kendoNotification");
        if(skus != ""){
            $.post(baseURL + 'index.php/asignacionpedido/asignacion_pedido/actualizarSKUS',{skus: skus},function(data){
                if(data == 0){
                    var grid = $("#grid").data("kendoGrid");
                    grid.dataSource.read();
                    grid.refresh();
                    popupNotification.getNotifications().parent().remove();
                    popupNotification.show(" Actualizado  Correctamente.", "success");
                }else{
                    popupNotification.getNotifications().parent().remove();
                    popupNotification.show(" Error en la Actualizacion.", "error");
                }
            });
        }
        else{
            popupNotification.getNotifications().parent().remove();
            popupNotification.show(" Debe seleccionar articulos para actualizar.", "error");
        }
    }
    $("#toolbar").kendoToolBar({
        items: [
            { type: "button", text: "Actualizar", icon: "k-icon k-i-reload" ,click: Actualizar},
            
        ]
    });
    
});