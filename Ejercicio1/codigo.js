// Indica si se está mostrando o no el diálogo de dirección / coordenadas
//  para la introducción de un nuevo envío
var estadoDialogo = false;
 
 
function nuevoEnvio(idReparto) {
    $('#idrepartoactual').val(idReparto);
    mostrarDialogo();
}

function borrartarea(idTarea){
    
}
 
 
function mostrarDialogo() {
//Centramos en pantalla
var anchoVentana = document.documentElement.clientWidth;
var altoVentana = document.documentElement.clientHeight;
var altoDialogo = $("#dialogo").height();
var anchoDialogo = $("#dialogo").width();
 
    $("#dialogo").css({
        "position": "absolute",
        "top": altoVentana/2-altoDialogo/2,
        "left": anchoVentana/2-anchoDialogo/2
    });
 
    //Para IE6
    $("#fondonegro").css({"height": altoVentana});
 
    //Si no está visible el diálogo
    if(!estadoDialogo){
        // Se muestra el fondo negro
        $("#fondonegro").css({"opacity": "0.7"});
        $("#fondonegro").fadeIn("slow");
        //  y el diálogo
        $("#dialogo").fadeIn("slow");
 
        $("#datosenvio").hide();
        estadoDialogo = true;
    }
}
function ocultarDialogo() {
    // Si está visible
    if(estadoDialogo){
        // Se oculta el fondo y el diálogo
        $("#fondonegro").fadeOut("slow");
        $("#dialogo").fadeOut("slow");
        estadoDialogo = false;
    }
}

function getCoordenadas() {
    // Comprobamos que se haya introducido una dirección
    if($("#direccion").val().length < 10) {
        alert("Introduzca una dirección válida.");
        return false;
    }
 
    // Se cambia el botón de Enviar y se deshabilita
    //  hasta que llegue la respuesta
    xajax.$('obtenerCoordenadas').disabled=true;
    xajax.$('obtenerCoordenadas').value="Un momento...";
 
    // Aquí se hace la llamada a la función registrada de PHP
    xajax_obtenerCoordenadas (xajax.getFormValues("formenvio"));
 
    return false;    
}

function abrirMaps(coordenadas) {
    var url = "http://maps.google.com/maps?hl=es&t=h&z=17&output=embed&ll=";
 
    if(!coordenadas) {
        // Cogemos las coordenadas del diálogo
        url+=$("#latitud").val()+","+$("#longitud").val();
    }
    else {
        // Si hay coordenadas, las usamos
        url+=coordenadas;
    }
 
    window.open(url,'nuevaventana','width=425,height=350');
}