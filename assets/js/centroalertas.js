$(document).ready(function(){

	var runningWMS = 0;
	var stopedWMS = 0;
	var promisesWMS = [];
	var runningBT = 0;
	var stopedBT = 0;
	var promisesBT = [];
	var err = "";
	comprobarAlertasWMS();
	comprobarAlertasBT();

	function comprobarAlertasWMS(){
		var alertasWMS = ["PKT", "PO", "BRCD", "ART", "OLA", "CITA", "ASN", "LPN", "DISTRO", "CARGA"];
		for (var i = 0; i <= alertasWMS.length -1; i++) {
			var request = 	$.ajax({
					            type: "POST",
					            url: baseURL + 'alertas/wms/errores/cant'+alertasWMS[i],
					            dataType: 'json',
					            success: function(result){
					            	err = result;
					            },
					            error: function(result){
					                console.log(JSON.stringify(result));
					            }
					        });
			promisesWMS.push(request);
		}	 
	}
	$.when.apply(null, promisesWMS).done(function(){
		for (var i = 0; i < promisesWMS.length -1; i++) {
			if(promisesWMS[i].responseText > 0){
		 		if(runningWMS == 0){
			 		stopedWMS = 0;
			 		intermitenciaWMS();
			 		break;
			 	}
			 	setTimeout(comprobarAlertasWMS, 600000);
			}
			else{
			 	setTimeout(comprobarAlertasWMS, 600000);
			 	if(stopedWMS == 0 && runningWMS == 1){
			 		runningWMS = 0;
			 		stopedWMS = 1;
			 	}
			}
		}
		
	});
	function intermitenciaWMS(){
		$("#boxWMS").toggleClass("bg-purple");
		$("#boxWMS").toggleClass("bg-red");
		if(stopedWMS == 0){
			runningWMS = 1;
			setTimeout(intermitenciaWMS, 500);
		}
	}
	function stopWMS(){
		$("#boxWMS").removeClass("bg-purple");
		$("#boxWMS").removeClass("bg-red");
		$("#boxWMS").addClass("bg-red");
	}
	function comprobarAlertasBT(){
		var alertasBT = ["SinProcSDI"];
		for (var i = 0; i <= alertasBT.length -1; i++) {
			var request = 	$.ajax({
					            type: "POST",
					            url: baseURL + 'alertas/bt/errores/cant'+alertasBT[i],
					            dataType: 'json',
					            success: function(result){
					            	err = result;
					            },
					            error: function(result){
					                console.log(JSON.stringify(result));
					            }
					        });
			promisesBT.push(request);
		}	 
	}

	$.when.apply(null, promisesBT).done(function(){
		for (var i = 0; i < promisesBT.length -1; i++) {
			console.log(promisesBT[i].responseText);
			if(promisesBT[i].responseText > 0){
		 		if(runningBT == 0){
			 		stopedBT = 0;
			 		intermitenciaBT();
			 		break;
			 	}
			 	setTimeout(comprobarAlertasBT, 600000);
			}
			else{
			 	setTimeout(comprobarAlertasBT, 600000);
			 	if(stopedBT == 0 && runningBT == 1){
			 		runningBT = 0;
			 		stopedBT = 1;
			 	}
			}
		}
	});
	function intermitenciaBT(){
		$("#boxBT").toggleClass("bg-yellow");
		$("#boxBT").toggleClass("bg-red");
		if(stopedBT == 0){
			runningBT = 1;
			setTimeout(intermitenciaBT, 500);
		}
	}
	function stopBT(){
		$("#boxBT").removeClass("bg-yellow");
		$("#boxBT").removeClass("bg-red");
		$("#boxBT").addClass("bg-yellow");
	}
});