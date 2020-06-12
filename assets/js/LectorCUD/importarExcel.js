$(document).ready(function(){

	var excel;

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
            url: baseURL + 'lector/cargarv2',
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
});