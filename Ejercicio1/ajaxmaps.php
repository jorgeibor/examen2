<?php
/**
 * Desarrollo Web en Entorno Servidor
 * Tema 8 : Aplicaciones web híbridas
 * Ejemplo Rutas de reparto: ajaxmaps.php
 */
 
// No aseguramos que se usa UTF-8
mb_internal_encoding("UTF-8");
 
// Incluimos la lilbrería Xajax
require_once("libs/xajax_core/xajax.inc.php");
 
// Creamos el objeto xajax
$xajax = new xajax();
 
// Y registramos la función que vamos a llamar desde JavaScript
$xajax->register(XAJAX_FUNCTION,"obtenerCoordenadas");
$xajax->register(XAJAX_FUNCTION,"ordenarReparto");
 
// El método processRequest procesa las peticiones que llegan a la página
// Debe ser llamado antes del código HTML
$xajax->processRequest();
 
function obtenerCoordenadas($parametros) {

   $respuesta = new xajaxResponse();
   $search = 'http://maps.google.com/maps/api/geocode/xml?address='.$parametros['direccion'].'&sensor=false&appid=z9hiLa3e';
   $xml = simplexml_load_file($search);
   // latitud
   $latitud = (string) $xml->result[0]->geometry->location->lat;
   $longitud = (string) $xml->result[0]->geometry->location->lng; 
   // Para obtener la elevación
   $urlElevacion = "https://maps.googleapis.com/maps/api/elevation/xml?locations=".$latitud.",".$longitud."&sensor=false&key=AIzaSyBT-lkJm_puGvwbii42fXqaeyVToenZRDw";
   $xmlElevacion = simplexml_load_file($urlElevacion);
   
   $elevacion = (string) $xmlElevacion->result[0]->elevation;
   
   //$respuesta->assign("latitud", "value", (string) $xml->result[0]->geometry->location->lat);
   //$respuesta->assign("longitud", "value", (string) $xml->result[0]->geometry->location->lng);
   
   $respuesta->assign("latitud", "value", $latitud);
   $respuesta->assign("longitud", "value", $longitud);
   $respuesta->assign("elevacion", "value", $elevacion);
   
   $respuesta->assign("obtenerCoordenadas","value","Obtener coordenadas y elevación");
   $respuesta->assign("obtenerCoordenadas","disabled",false); 
   
   return $respuesta;
}


?>