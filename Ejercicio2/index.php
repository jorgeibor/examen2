<?php 
    require_once("xajax/xajax_core/xajax.inc.php");

    $xajax = new xajax();

    //$xajax->configure("debug",true);
    $xajax->configure( 'javascript URI', 'xajax/' );

    $xajax->register(XAJAX_FUNCTION, "comprobarUsuario");
    $xajax->register(XAJAX_FUNCTION, "insertarUsuario");

    $xajax->processRequest();
    $xajax->printJavascript();

    require_once "claseBD.php";
    $conexion = claseBD::connectPDO($dns,$username,$password,$options);
    
    function comprobarUsuario() {
        $respuesta = new xajaxResponse();

        $nom = $datos['nombre'];
        $pass = $datos['password'];
        
        $usuario = new Usuario();
        $resultado = $usuario::validar($nom,$pass);

        if($resultado->fetch()){
            $msj = "<h1>Bienvenido usuario ".$usuario->getnombre()."</h1>";
            $respuesta->assign("form", "innerHTML", $msj);
            header("Refresh:3, url=productos.php");
        }else{
            $msj = "<label class='mensajeError'>Usuario y Contraseña incorrectos. Vuelva a intentarlo.</label>";
            $respuesta->assign("errorNombre", "innerHTML", $msj);
            header("login.php");
        }
    
    }
    
    function insertarUsuario() {
        $respuesta2 = new xajaxResponse();

        $nom = $datos2['nombre'];
        $pass = $datos2['password'];
        $usuario = $datos2['usuario'];
        $email = $datos2['email'];
        $fnac = $datos2['fnac'];
        $edad = $datos2['edad'];
        
        $usuario = new Usuario();
        $resultado = $usuario::insertarBD($nom,$pass,$usuario,$email,$fnac,$edad);

        if ($count == 1) {
            $msj = "<label class='mensajeCorrecto'>Se ha registrado correctamente. Ya puede acceder.</label>";
            $respuesta->assign("form2", "innerHTML", $msj);
            header("login.php");
        } else if ($count == "Error") {
            $msj = "<label class='mensajeError'>El usuario ya existe en la base de datos.</label>";
            $respuesta->assign("errorNombre", "innerHTML", $msj);
            header("login.php");
        }
    
    }
    
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>Validación formulario con Xajax</title>
    <link rel="stylesheet" href="estilo.css" type="text/css" />
 
</head>
<body>
    <div id='form'>
        <h1>Esta página da información sobre....</h1>
        <h2>Espero que te encuentres a gusto... Registrate para obtener mas información</h2>
    <form id='datos' action="javascript:void(null);" onsubmit="enviarFormulario();">
    <fieldset >
        <legend>Introducción de datos</legend>
        <div class='campo'>
            <label for='nombre' >Nombre:</label><br />
            <input type='text' name='nombre' id='nombre' maxlength="50" /><br />
            <span id="errorNombre" class="error" for="nombre"></span>
        </div>
        <div class='campo'>
            <label for='password' >Contraseña:</label><br />
            <input type='password' name='password' id='password' maxlength="50" /><br />
        </div>
        
        <div class='campo'>
            <input type='submit' id='enviar' name='enviar' value='Enviar' />
        </div>
    </fieldset>
    </form>
    </div>
    <div id='form2'>
    <form id='datos2' action="javascript:void(null);" onsubmit="enviarFormulario2();">
    <fieldset >
        <legend>Registrar un nuevo Usuario</legend>
        <div class='campo'>
            <label for='nombre' >Nombre:</label><br />
            <input type='text' name='nombre' id='nombre' maxlength="50" /><br />
            <span id="errorNombre" class="error" for="nombre"></span>
        </div>
        <div class='campo'>
            <label for='password' >Contraseña:</label><br />
            <input type='password' name='password' id='password' maxlength="50" /><br />
        </div>
        <div class='campo'>
            <label for='usuario'>Usuario:</label><br />
            <input type='text' name='usuario' id='usuario' maxlength="50" /><br />
        </div>
        <div class='campo'>
            <label for='email' >Email:</label><br />
            <input type='text' name='email' id='email' maxlength="100" /><br />
        </div>
        <div class='campo'>
            <label for='fnac' >Fnac:</label><br />
            <input type='text' name='fnac' id='fnac' maxlength="50" /><br />
        </div>
        <div class='campo'>
            <label for='edad' >Edad:</label><br />
            <input type='text' name='edad' id='edad' maxlength="2" /><br />
        </div>
        
        <div class='campo'>
            <input type='submit' id='enviar' name='enviar' value='Enviar' />
        </div>
    </fieldset>
    </form>
    </div>
    <script>
        function enviarFormulario(){
            xajax_comprobarUsuario(xajax.getFormValues("datos"));
            return false;
        }
        function enviarFormulario2(){
            xajax_insertarUsuario(xajax.getFormValues("datos2"));
            return false;
        }
    </script>
</body>
</html>
