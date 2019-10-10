$(document).ready(function(){
    var sku = "";
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
            		$("#skuinfo").append('<tr><td>'+element.SKU_ID+'</td><td>'+element.SKU_DESC+'</td><td>'+element.EXP_LICN_SYMBOL+'</td><td>'+element.MERCH_TYPE+'</td><td>'+element.CODE_DESC+'</td><td>'+element.SALE_GRP+'</td><td>'+element.COMMODITY_CODE+'</td><td>'+element.SPL_INSTR_1+'</td><td>'+element.COMMODITY_LEVEL_DESC+'</td></tr>');
            	});
            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
        var grid = $("#gridAtributos");
        grid.data("kendoGrid").dataSource.read();
	});
    var dataSourceCartonTypeCBO = new kendo.data.DataSource({
                                        transport: {
                                            read:  onReadCboCartonType
                                        }
                                    });
    var dataSourceAttrSku = new kendo.data.DataSource({
            transport: {
                read: onReadAttrSku
            },
            schema: {
                model: {
                    id: "SKU_ID",
                    fields: {
                            SKU_ID: {type: "string"}, // number - string - date
                            TIPO_OLA: {type: "string"},
                            TIPO_DINAMICA: {type: "string"}, // number - string - date
                            STORE_DEPT: {type: "string"},
                            PKT_CONSOL_ATTR: {type: "string"},
                            MERCH_GROUP: {type: "string"},
                            PROM_PACK_QTY: {type: "string"},
                            CARTON_TYPE: {type: "string"},
                            CONVEY_FLAG: {type: "string"},
                            MAX_UNITS_IN_DYNAMIC_CASE_PICK: {type: "string"},
                            MAX_CASES_IN_DYNAMIC_CASE_PICK: {type: "string"},
                            CARTON_BREAK_ATTR: {type: "string"},
                            ASSIGN_DYNAMIC_ACTV_PICK_SITE: {type: "string"},
                            ASSIGN_DYNAMIC_CASE_PICK_SITE: {type: "string"},
                            PUTWY_TYPE: {type: "string"},
                            QUAL_INSPCT_ITEM_GRP: {type: "string"},
                            PICKASSIGNZONE: {type: "string"},
                            PROD_GROUP: {type: "string"},
                            NBR_OF_DYN_ACTV_PICK_PER_SKU: {type: "string"},
                            NBR_OF_DYN_CASE_PICK_PER_SKU: {type: "string"},
                            PICK_LOCN_ASSIGN_TYPE: {type: "string"},
                            STORE_DEPT: {type: "string"},
                            ASIGNADO_A_SHELVING: {type: "string"},
                            CRITERIO: {type: "string"}
                        }
                }
            },
            pageSize: 15
        });

    $("#gridAtributos").kendoGrid({
        dataSource: dataSourceAttrSku,
        pageable: true,
        height: 430,
        scrollable: true,
        autoBind: false,
        columns: [
            { field:"SKU_ID",title:"SKU", width: 105 },
            { field:"TIPO_OLA",title:"TIPO OLA", width: 105 },
            { field:"TIPO_DINAMICA",title:"TIPO DINAMICA", width: 105 },
            { field:"STORE_DEPT",title:"STORE DEPARTMENT", width: 105 },
            { field:"PKT_CONSOL_ATTR",title:"PKT CONSOL ATTR", width: 105 },
            { field:"MERCH_GROUP",title:"MERCH GROUP", width: 105 },
            { field:"PROMPT_PACK_QTY",title:"PROM PACK QTY", width: 105 },
            { field:"CARTON_TYPE", title: "CARTON TYPE", width: "180px", editor: cartonTypeDropDownEditor },
            { field:"CONVEY_FLAG",title:"CONVEYABLE", width: 105 },
            { field:"MAX_UNITS_IN_DYNAMIC_CASE_PICK",title:"MAXUNITSDYNAMCP", width: 120 },
            { field:"MAX_CASES_IN_DYNAMIC_CASE_PICK",title:"MAXCASESDYNAMCP", width: 120 },
            { field:"CARTON_BREAK_ATTR",title:"CARTONBREAKATTRIB", width: 140 },
            { field:"ASSIGN_DYNAMIC_ACTV_PICK_SITE",title:"ASSIGNDYNAMICACTIVE", width: 140 },
            { field:"ASSIGN_DYNAMIC_CASE_PICK_SITE",title:"ASSIGNDYNAMICTOCAS", width: 140 },
            { field:"PUTWY_TYPE",title:"PUTAWAYTYPE2", width: 105 },
            { field:"QUAL_INSPCT_ITEM_GRP",title:"QUALITYINSPGROUP", width: 120 },
            { field:"PICKASSIGNZONE",title:"PICKASSIGNZONE", width: 120 },
            { field:"PROD_GROUP",title:"PRODUCTGROUP", width: 105 },
            { field:"NBR_OF_DYN_ACTV_PICK_PER_SKU",title:"NBROFDYNACTVPICKPE", width: 140 },
            { field:"NBR_OF_DYN_CASE_PICK_PER_SKU",title:"NBROFDYNCASEPICKPER", width: 145 },
            { field:"PICK_LOCN_ASSIGN_TYPE",title:"PICKASSIGTYPE", width: 120 },
            { field:"STORE_DEPT",title:"MUNDO", width: 105 },
            { field:"SPL_INSTR_CODE_2",title:"ASIGNADO A SHELVING", width: 105 },
            { field:"CRITERIO", title: "CRITERIO", width: "180px", editor: criterioDropDownEditor },
            { command: "destroy", title: " ", width: "150px" }],
        editable: true
    });
    
    function onReadCboCartonType(e){
        $.ajax({
                url: baseURL + 'SeteoAttr/cboCartonType',
                type:"POST",
                dataType: "json",
                success: function(result){
                    e.success(result);
                },
                error: function(result){
                    alert(JSON.stringify(result));
                }
            });
    }
    function onReadAttrSku(e){
        alert(sku);
        $.ajax({
                url: baseURL + 'SeteoAttr/attrSKU',
                type:"POST",
                data: {sku: sku},
                dataType: "json",
                success: function(result){
                    e.success(result);
                },
                error: function(result){
                    alert(JSON.stringify(result));
                }
            });
    }
    function cartonTypeDropDownEditor(container, options) {
        $('<input required name="' + options.field + '"/>')
            .appendTo(container)
            .kendoDropDownList({
                autoBind: false,
                dataTextField: "CARTON_TYPE",
                dataValueField: "CARTON_TYPE",
                dataSource: dataSourceCartonTypeCBO
            });
    }
    function criterioDropDownEditor(container, options) {
        $('<input required name="' + options.field + '"/>')
            .appendTo(container)
            .kendoDropDownList({
                dataTextField: "name",
                dataValueField: "name",
                dataSource: [
                    { name: "ESTILO" },
                    { name: "LINEA" },
                    { name: "SUBLINEA" }
                ],
            });
    }
});
