$(document).ready(function(){

	//llamada a alertas

	actualizarAlertaSDI();

	//declaracion de variables

	var stopedSDI = 0;
	var runningSDI = 0;


	function intermiteciaSDI(){
      $("#SDIBTBox").toggleClass("bg-green");
      $("#SDIBTBox").toggleClass("bg-red");
      $("#iconSDIBT").toggleClass("glyphicon-ok");
      $("#iconSDIBT").toggleClass("ion-android-alert");
      if(stopedSDI == 0){
         runningSDI = 1;
         setTimeout(intermiteciaSDI, 500);
      }
      else{
        stopSDI();
      }
    }
    function actualizarAlertaSDI(){
        $.ajax({
            type: "POST",
            url: baseURL + 'alertas/bt/errores/sinProcSDI',
            dataType: 'json',
            success: function(result){
            	result.forEach(function(element){
	                if(element.CANTIDAD >= 2000){
	                    if(runningSDI == 0){
	                        stopedSDI = 0;
	                        intermiteciaSDI();
	                        
	                    }
	                    setTimeout(actualizarAlertaSDI, 600000);
	                }else{
	                    setTimeout(actualizarAlertaSDI, 600000);
	                    if(stopedSDI == 0 && runningSDI == 1){
	                        runningSDI = 0;
	                        stopedSDI = 1
	                    }
	                }
            	});

            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
        var numero = 1;
        $.ajax({
            type: "POST",
            url: baseURL + 'alertas/bt/errores/sinProcSDI',
            dataType: 'json',
            success: function(result){
            	result.forEach(function(element){
            		setInterval(function(){ if(numero <= element.CANTIDAD){$("#nSDIBT").html(numero);numero++;} }, 3);
            	});

            },
            error: function(result){
                console.log(JSON.stringify(result));
            }
        });
    }
    function stopSDI(){
        $("#SDIBTBox").removeClass("bg-green");
        $("#SDIBTBox").removeClass("bg-red");
        $("#iconSDIBT").removeClass("glyphicon-ok");
        $("#iconSDIBT").removeClass("ion-android-alert"); 
        $("#SDIBTBox").addClass("bg-green");
        $("#iconSDIBT").addClass("glyphicon-ok");
    }
    function contador(total, elemento){

    }
});