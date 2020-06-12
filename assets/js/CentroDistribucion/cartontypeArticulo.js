$(document).ready(function(){


    var dataSource2  = new kendo.data.DataSource({
        transport:{
            read: onReadCB
        }
    });
    $("#selectCartonType").kendoComboBox({
        dataSource: dataSource2,
        dataTextField: "CARTON_TYPE",
        dataValueField: "CARTON_TYPE"
    });
    var sku = "";
    var ventana_cartontype= $("#POPUP_CartonType");
    ventana_cartontype.kendoWindow({
        width: "400px",
        height: "150px",
        visible: false,
        position:{
            top: 45,
            left:0
        },
        actions: [
            "Minimize",
            "Maximize",
            "Close"
        ]
    }).data("kendoWindow").center();
	$("#btnBuscarSku").click(function(){
		sku = $("#txtsku").val();
		console.log(sku);
		$.ajax({
            type: "POST",
            url: baseURL + 'SeteoAttr/infoSku',
            data: {sku: sku},
            dataType: 'json',
            success: function(result){
            	result.forEach(function(element){
                    $("'#"+element.SKU_ID+"'").remove();
            		$("#skuinfo").append('<tr id="'+element.SKU_ID+'"><td>'+element.SKU_ID+'</td><td>'+element.SKU_DESC+'</td><td id="ESTILO'+element.SKU_ID+'">'+element.EXP_LICN_SYMBOL+'</td><td>'+element.MERCH_TYPE+'</td><td>'+element.CODE_DESC+'</td><td>'+element.SALE_GRP+'</td><td>'+element.COMMODITY_CODE+'</td><td>'+element.SPL_INSTR_1+'</td><td>'+element.COMMODITY_LEVEL_DESC+'</td><td>'+element.CARTON_TYPE+'</td><td><button onclick="configurarCT('+element.SKU_ID+')" type="button" class="btn btn-block btn-primary">Configurar</button></td></tr>');
            	});
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
	});
    function onReadCB(e){
        $.ajax({
            url: baseURL + 'pasillos/tipoCartones/todos',
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
    $("#btnActCartonType").click(function(){
        var popupcartontype = $("#POPUP_CartonType");
        popupcartontype.data("kendoWindow").close();
        var cartonType = $("#selectCartonType").data("kendoComboBox").value();
        $.ajax({
            url: baseURL + 'pasillos/actTipoCartonArticulo',
            type: 'POST', // POST or GET
            dataType: 'json', // Tell it we're retrieving JSON
            data: {
                sku: sku, cartonType: cartonType// Pass through the ID of the current element matched by '.selector'
            },
            success: function(data){
              if(data > 0){
                alert('Actualizado Correctamente');
                $.ajax({
                    type: "POST",
                    url: baseURL + 'SeteoAttr/infoSku',
                    data: {sku: sku},
                    dataType: 'json',
                    success: function(result){
                        result.forEach(function(element){
                            $("#"+element.SKU_ID+"").remove();
                            $("#skuinfo").append('<tr id="'+element.SKU_ID+'"><td>'+element.SKU_ID+'</td><td>'+element.SKU_DESC+'</td><td id="ESTILO'+element.SKU_ID+'">'+element.EXP_LICN_SYMBOL+'</td><td>'+element.MERCH_TYPE+'</td><td>'+element.CODE_DESC+'</td><td>'+element.SALE_GRP+'</td><td>'+element.COMMODITY_CODE+'</td><td>'+element.SPL_INSTR_1+'</td><td>'+element.COMMODITY_LEVEL_DESC+'</td><td>'+element.CARTON_TYPE+'</td><td><button onclick="configurarCT('+element.SKU_ID+')" type="button" class="btn btn-block btn-primary">Configurar</button></td></tr>');
                        });
                    },
                    error: function(result){
                        console.log(JSON.stringify(result));
                    }
                });
              }
            }
        });
    });
});
function configurarCT(y){
    sku = y;
    var popupactcartontype = $("#POPUP_CartonType");
    popupactcartontype.data("kendoWindow").title('Actualizar Carton Type SKU: '+ y);
    popupactcartontype.data("kendoWindow").open();
}