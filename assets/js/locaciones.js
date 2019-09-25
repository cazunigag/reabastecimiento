$(document).ready(function(){ 
  console.log(flagAnt);
  $("#btnExportarAntiguedad").hide();
  $("#menuAdminLocn").hide();
  console.log(localStorage.getItem("persistencialocn"));
    $("#btnAntiguedad").click(function(){

	    dias = document.getElementById("txtdias").value;
      flagAntiguedadSku = 'on'
      flagAnt = $("input[name='radioAntiguedad']:checked").val();
      console.log(flagAnt);      
	    clearPreviousSearch();
      if(flagAnt == 'antSku'){
        $.ajax({
              type: "POST",
              url: baseURL + 'locaciones/antiguedad/Sku',
              data:{ dias: dias, pasillo: idpasillo},
              dataType: 'json',
              success: function(result){
                console.log('success btnAntiguedad');
                $("#btnExportarAntiguedad").show();
                $('td').css('background-color', '#b8b894');
                result.forEach(function(element){
                  busquedaAnterior.push(element.LOCN_ID)
                  $('#'+element.LOCN_ID).css("background-color", "#cca300");
                 });
              },
              error: function(xhr){
                  console.log('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
              }
        });
      }else if(flagAnt == 'contCicl'){
         $.ajax({
              type: "POST",
              url: baseURL + 'locaciones/antiguedad/ConteoCilcico',
              data:{ dias: dias, pasillo: idpasillo},
              dataType: 'json',
              success: function(result){
                $("#btnExportarAntiguedad").show();
                console.log('success btnAntiguedad');
                $('td').css('background-color', '#b8b894');
                result.forEach(function(element){
                  busquedaAnterior.push(element.LOCN_ID)
                  $('#'+element.LOCN_ID).css("background-color", "#33cccc");
                 });
              },
              error: function(xhr){
                  console.log('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
              }
        });
      }
	});
	if(localStorage.getItem("persistencialocn") != null){
    $('td').css("background-color", "#b8b894");
    var persistencialocn = JSON.parse(localStorage.getItem("persistencialocn"));
	  persistencialocn.forEach(function(element){
      console.log(element);
	     $('#'+element).css("background-color", "yellow");
	  });
	}else{
    emptyLocn();
  }
  $("#btnExportarAntiguedad").click(function(){
    console.log(flagAnt);
    if(flagAnt == 'antSku'){
      window.location.href = baseURL + 'locaciones/antiguedad/descSku/'+dias+'/'+idpasillo;
    }else if(flagAnt == 'contCicl'){
      window.location.href = baseURL + 'locaciones/antiguedad/descConteoCilcico/'+dias+'/'+idpasillo;
    } 
  });
  $("#btnLimpiarFiltros").click(function(){
    $("#btnExportarAntiguedad").hide();
    $('td').css('background-color', '#66ff66');
    $('#grid').data('kendoGrid').dataSource.sort({});
    $('#grid').data('kendoGrid').dataSource.filter({});
    localStorage.clear();
    persistenciasku = [];
    emptyLocn();
  });
  $("#btnAdministrarLocn").click(function(){
    $("#btnExportarAntiguedad").hide();
    $('td').css('background-color', '#66ff66');
    $('#grid').data('kendoGrid').dataSource.sort({});
    $('#grid').data('kendoGrid').dataSource.filter({});
    localStorage.clear();
    persistenciasku = [];
    emptyLocn();
    $("#menuGeneral").hide();
    $("#menuAdminLocn").show();  
    $("td").each(function(){
      var id  = $(this).attr("id");
      $(this).attr("onclick", "selectLocn(id)");
    });
  });
  $("#btnVolverMG").click(function(){
    $("#btnExportarAntiguedad").hide();
    $('td').css('background-color', '#66ff66');
    $('#grid').data('kendoGrid').dataSource.sort({});
    $('#grid').data('kendoGrid').dataSource.filter({});
    localStorage.clear();
    persistenciasku = [];
    selectedLocn = [];
    emptyLocn();
    $("#menuGeneral").show();
    $("#menuAdminLocn").hide();  
    $("td").each(function(){
        var id  = $(this).attr("id");
        $(this).attr("onclick", "detalleLocn(id)");
      });
  });
});
function clearPreviousSearch(){
    busquedaAnterior.forEach(function(element){
        $('#'+element).css("background-color", "#b8b894");
    });
    busquedaAnterior = [];
}
function detalleLocn(y){
     idLocacion = y;
     var titulo = '';
      if(y != null && y != ' '){
        var popupLeadTime = $("#POPUP_Detalle_LOCN");
        $.ajax({
          type: "POST",
          url: baseURL + 'locaciones/detalle/cabecera',
          data:{ idLocn: idLocacion},
          dataType: 'json',
          success: function(result){
              result.forEach(function(element){
                 popupLeadTime.data("kendoWindow").title(element.HEADER);
              });
          },
          error: function(result){
              alert(JSON.stringify(result));
          }
        });
        popupLeadTime.data("kendoWindow").open();
        var grid = $("#grid");
        grid.data('kendoGrid').dataSource.data([]);
        if(localStorage.getItem("persistenciasku") != null){
          persistenciasku = JSON.parse(localStorage.getItem("persistenciasku"));
          persistenciasku.forEach(function(element){
              grid.data('kendoGrid').dataSource.filter({field: "SKU_ID", operator: "equals", value: element});
          });
        }
        grid.data("kendoGrid").dataSource.read();
      }
}
function selectLocn(y){
  
  if(document.getElementById(y).style.backgroundColor == "rgb(102, 255, 102)" || document.getElementById(y).style.backgroundColor == "orange"){
    $("#modal-warning").modal('show');  
  }else if(document.getElementById(y).style.backgroundColor == 'yellow'){
    $("#"+y).css("background-color", "red");
    var index = selectedLocn.indexOf(y);
    selectedLocn.splice(index, 1);
  }
  else{
    $("#"+y).css("background-color", "yellow");
    selectedLocn.push(y);
  }
}
function emptyLocn(){
  $.ajax({
          type: "POST",
          url: baseURL + 'locaciones/vacias',
          data:{ pasillo: idpasillo},
          dataType: 'json',
          success: function(result){
              result.forEach(function(element){

                 if(element.COLOR == 'Rojo'){
                    $('#'+element.LOCN_ID).css("background-color", "red");
                 }
                 else if(element.COLOR == 'Naranjo'){
                    $('#'+element.LOCN_ID).css("background-color", "orange");
                 }
               });
          },
          error: function(result){
              console.log(JSON.stringify(result));
          }
    });
}