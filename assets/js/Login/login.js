$("#helpuser").hide();
$("#helppass").hide();
$("#errmessage").hide();
$(document).ready(function(){
	
	$("#submit").click(function(){

		var user = $("#username").val();
		var pass = $("#password").val();
		if(user == ""){
			$("#user").addClass("has-error");
			$("#helpuser").html("Campo Vacio");
			$("#helpuser").show();
			$("#username").focus();
		}else{
			var rutvalido = validaRut(user);
			if(!rutvalido){
				$("#user").addClass("has-error");
				$("#helpuser").html("El rut ingresado es invalido");
				$("#helpuser").show();
				$("#username").focus();
			}
			else{
				$("#user").removeClass("has-error");
				$("#helpuser").hide();
				$("#user").addClass("has-success");
				if(pass == ""){
					$("#pass").addClass("has-error");
					$("#helppass").html("Campo Vacio");
					$("#helppass").show();
					$("#password").focus();
				}else{
					$("#pass").removeClass("has-error");
					$("#helppass").hide();
					$("#pass").addClass("has-success");

					$.ajax({
						beforeSend: function () {
		                  	$("#iconbtn").toggleClass("fa");
		                  	$("#iconbtn").toggleClass("fa-refresh");
                			$("#iconbtn").toggleClass("fa-spin");
		              	},
			            type: "POST",
			            data: {user: user, pass: pass},
			            url: baseURL + 'auth',
			            dataType: 'json',
			            success: function(result){
			                if(result == 0){
			                	$("#errmessage").hide();
			                	window.location.href = baseURL + 'home';
			                }else{
			                	$("#text").html("Usuario o contraseña incorrectos, intentelo nuevamente");
			                	$("#user").removeClass("has-error");
			                	$("#pass").addClass("has-error");
			                	$("#errmessage").show();
			                }
			            },
			            error: function(result){
			                $("#error-modal").text("Ocurrio un error al cargar la pagina");
			                $("#modal-danger").modal('show');
			            }
			        });
				}
			}	
		}
	});
	$("#username").blur(function(){
		var user = $("#username").val();
		if(user == ""){
			$("#user").addClass("has-error");
			$("#helpuser").html("Campo Vacio");
			$("#helpuser").show();
			$("#username").focus();
		}else{
			var rutvalido = validaRut(user);
			if(!rutvalido){
				$("#user").addClass("has-error");
				$("#helpuser").html("El rut ingresado es invalido");
				$("#helpuser").show();
				$("#username").focus();
			}
			else{
				$("#user").removeClass("has-error");
				$("#helpuser").hide();
				$("#user").addClass("has-success");
			}	
		}
	});
	$("#password").blur(function(){
		var pass = $("#password").val();
		if(pass == ""){
			$("#pass").addClass("has-error");
			$("#helppass").html("Campo Vacio");
			$("#helppass").show();
			$("#password").focus();
		}else{
			$("#pass").removeClass("has-error");
			$("#helppass").hide();
			$("#pass").addClass("has-success");
		}
	});
 	function validaRut (user) {
		user = user.replace("‐","-");
		if (!/^[0-9]+[-|‐]{1}[0-9kK]{1}$/.test( user ))
			return false;
		var tmp 	= user.split('-');
		var digv	= tmp[1]; 
		var rut 	= tmp[0];
		if ( digv == 'K' ) digv = 'k' ;
		
		return (dv(rut) == digv );
	}
	function dv(T){
		var M=0,S=1;
		for(;T;T=Math.floor(T/10))
			S=(S+T%10*(9-M++%6))%11;
		return S?S-1:'k';
	}
});