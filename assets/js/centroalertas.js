$(document).ready(function(){

	var running = 0;
	var stoped = 0;
	var promises = [];
	var err = "";
	comprobarAlertas();

	function comprobarAlertas(){
		var alertasWMS = ["PKT", "PO", "BRCD", "ART", "OLA", "CITA", "ASN", "LPN", "DISTRO", "CARGA"];
		for (var i = 0; i <= alertasWMS.length -1; i++) {
			var request = 	$.ajax({
					            type: "POST",
					            url: baseURL + 'alertas/errores/cant'+alertasWMS[i],
					            dataType: 'json',
					            success: function(result){
					                err = result;

					            },
					            error: function(result){
					                console.log(JSON.stringify(result));
					            }
					        });
			promises.push(request);
		}	 
	}
	console.log(promises);
	$.when.apply(null, promises).done(function(){
		for (var i = 0; i < promises.length -1; i++) {
			if(promises[i].responseText > 0){
		 		if(running == 0){
			 		stoped = 0;
			 		intermitencia();
			 		break;
			 	}
			 	setTimeout(comprobarAlertas, 600000);
			}
			else{
			 	setTimeout(comprobarAlertas, 600000);
			 	if(stoped == 0 && running == 1){
			 		running = 0;
			 		stoped = 1;
			 	}
			}
		}
		console.log(err);
		
	});
	function intermitencia(){
		$("#boxWMS").toggleClass("bg-purple");
		$("#boxWMS").toggleClass("bg-red");
		if(stoped == 0){
			running = 1;
			setTimeout(intermitencia, 500);
		}
	}
	function stop(){
		$("#boxWMS").removeClass("bg-purple");
		$("#boxWMS").removeClass("bg-red");
		$("#boxWMS").addClass("bg-red");
	}
});