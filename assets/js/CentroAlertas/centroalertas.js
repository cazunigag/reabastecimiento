$(document).ready(function(){

	var runningWMS = 0;
	var stopedWMS = 0;
	var promisesWMS = [];
	var runningBT = 0;
	var stopedBT = 0;
	var promisesBT = [];
	var runningPMM = 0;
	var stopedPMM = 0;
	var promisesPMM = [];
	var stopedEIS = 0;
	var runningEIS = [];
	var promisesEIS = [];
	var err = "";
	comprobarAlertasWMS();
	comprobarAlertasBT();
	comprobarAlertasPMM();
	comprobarAlertasEIS();

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
		for (var i = 0; i <= promisesWMS.length -1; i++) {
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
		}else{
			stopWMS();
		}
	}
	function stopWMS(){
		$("#boxWMS").removeClass("bg-purple");
		$("#boxWMS").removeClass("bg-red");
		$("#boxWMS").addClass("bg-red");
	}
	function comprobarAlertasBT(){
		var alertasBT = ["SinProcSDI","MalEnviadosBT","PickTicketDuplicados"];
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
		for (var i = 0; i <= promisesBT.length -1; i++) {
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
		else{
			stopBT();
		}
	}
	function stopBT(){
		$("#boxBT").removeClass("bg-yellow");
		$("#boxBT").removeClass("bg-red");
		$("#boxBT").addClass("bg-yellow");
	}
	function comprobarAlertasPMM(){
		var alertasPMM = ["DifPMMWMS", "DifCargaPMMWMS"];
		for (var i = 0; i <= alertasPMM.length -1; i++) {
			var request = 	$.ajax({
					            type: "POST",
					            url: baseURL + 'alertas/pmm/errores/cant'+alertasPMM[i],
					            dataType: 'json',
					            success: function(result){
					            	err = result;
					            },
					            error: function(result){
					                console.log(JSON.stringify(result));
					            }
					        });
			promisesPMM.push(request);
			console.log(promisesPMM);
		}	 
	}

	$.when.apply(null, promisesPMM).done(function(){
		console.log(promisesPMM.length);
		for (var i = 0; i <= promisesPMM.length -1; i++) {
			console.log(promisesPMM[i].responseText);
			if(promisesPMM[i].responseText > 0){
				console.log('hola');
		 		if(runningPMM == 0){
			 		stopedPMM = 0;
			 		intermitenciaPMM();
			 		break;
			 	}
			 	setTimeout(comprobarAlertasPMM, 600000);
			}
			else{
			 	setTimeout(comprobarAlertasPMM, 600000);
			 	if(stopedPMM == 0 && runningPMM == 1){
			 		runningPMM = 0;
			 		stopedPMM = 1;
			 	}
			}
		}
	});
	function intermitenciaPMM(){
		$("#boxPMM").toggleClass("bg-aqua");
		$("#boxPMM").toggleClass("bg-red");
		if(stopedPMM == 0){
			runningPMM = 1;
			setTimeout(intermitenciaPMM, 500);
		}
		else{
			stopPMM();
		}
	}
	function stopPMM(){
		$("#boxEIS").removeClass("bg-olive");
		$("#boxEIS").removeClass("bg-red");
		$("#boxEIS").addClass("bg-olive");
	}
	function comprobarAlertasEIS(){
		var alertasEIS = ["ErrEIS"];
		for (var i = 0; i <= alertasEIS.length -1; i++) {
			var request = 	$.ajax({
					            type: "POST",
					            url: baseURL + 'alertas/EIS/errores/cant'+alertasEIS[i],
					            dataType: 'json',
					            success: function(result){
					            	err = result;
					            },
					            error: function(result){
					                console.log(JSON.stringify(result));
					            }
					        });
			promisesEIS.push(request);
			console.log(promisesEIS);
		}	 
	}

	$.when.apply(null, promisesEIS).done(function(){
		console.log(promisesEIS.length);
		for (var i = 0; i <= promisesEIS.length -1; i++) {
			console.log(promisesEIS[i].responseText);
			if(promisesEIS[i].responseText > 0){
				console.log('hola');
		 		if(runningEIS == 0){
			 		stopedEIS = 0;
			 		intermitenciaEIS();
			 		break;
			 	}
			 	setTimeout(comprobarAlertasEIS, 600000);
			}
			else{
			 	setTimeout(comprobarAlertasEIS, 600000);
			 	if(stopedEIS == 0 && runningEIS == 1){
			 		runningEIS = 0;
			 		stopedEIS = 1;
			 	}
			}
		}
	});
	function intermitenciaEIS(){
		$("#boxEIS").toggleClass("bg-olive");
		$("#boxEIS").toggleClass("bg-red");
		if(stopedEIS == 0){
			runningEIS = 1;
			setTimeout(intermitenciaEIS, 500);
		}
		else{
			stopEIS();
		}
	}
	function stopEIS(){
		$("#boxEIS").removeClass("bg-olive");
		$("#boxEIS").removeClass("bg-red");
		$("#boxEIS").addClass("bg-olive");
	}
});