var flagCartonType = 'off';
$(document).ready(function(){
var ventana_simbologia = $("#POPUP_simbologia");
    ventana_simbologia.kendoWindow({
        title: "Simbologia Locacion",
        width: "400px",
        height: "120px",
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
    });
$("#search-btn").click(function(){
    if(nivel == 'locaciones'){
    vsku = document.getElementById("q").value;
    console.log(vsku);
    clearPreviousSearch();
    $.ajax({
          type: "POST",
          url: baseURL + 'sku/locacion',
          data:{ sku: vsku},
          dataType: 'json',
          success: function(result){
            $('td').css("background-color", "#b8b894");
            result.forEach(function(element){
              busquedaAnterior.push(element.LOCN_ID)
              $('#'+element.LOCN_ID).css("background-color", "yellow");
              persistenciasku.push(element.SKU_ID); 
               
             });
            console.log(persistenciasku);
            localStorage.setItem("persistenciasku", JSON.stringify(persistenciasku));
          },
          error: function(result){
              console.log(JSON.stringify(result));
          }
    });
    console.log(busquedaAnterior);
  }
  else if(nivel == 'pasillos'){
    vsku = document.getElementById("q").value;
     $.ajax({
          type: "POST",
          url: baseURL + 'sku/pasillo',
          data:{ sku: vsku},
          dataType: 'json',
          success: function(result){
            console.log(result);
            var count = 0;
            result.forEach(function(element){
              index = result.indexOf(result);
              console.log(index);
              console.log(result[count + 1]);
              console.log(result[count]);
              if(result[count] == result[result.length - 1]){
                $('#'+element.PASILLOS).toggleClass("label label-warning");
              }else if(result[count + 1].PASILLOS != result[count].PASILLOS){
                console.log('entro');
                $('#'+element.PASILLOS).toggleClass("label label-warning");
              }
              count++;  
              persistencialocn.push(element.LOCN_ID);
              persistenciasku.push(element.SKU_ID);
            });
            localStorage.setItem("persistencialocn", JSON.stringify(persistencialocn));
            localStorage.setItem("persistenciasku", JSON.stringify(persistenciasku));
            console.log('result');
          },
          error: function(result){
              console.log(JSON.stringify(result));
          }
    });
     
     
  }
  });
});  
$("#btnsimbologia").click(function(){
        var popupsimbologia = $("#POPUP_simbologia");
        popupsimbologia.data("kendoWindow").open();
});

    