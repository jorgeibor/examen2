<?php
session_start();

//set_include_path("google-api-php-client-master/src/".PATH_SEPARATOR. get_include_path());
require_once 'libs/xajax_core/xajax.inc.php';
require_once 'google-api-php-client-master/vendor/autoload.php';
require_once 'google-api-php-client-master/src/Google/Client.php';
require_once 'google-api-php-client-master/src/Google/Service/Tasks.php';
require_once'google-api-php-client-master/src/Google/Service/Calendar.php';

// Creamos el objeto xajax
$xajax = new xajax('ajaxmaps.php');

$xajax->configure("javascript URI", "libs/");
// Configuramos la ruta en que se encuentra la carpeta xajax_js
// Y registramos las funciones que vamos a llamar desde JavaScript
//Estas funciones vienen implementadas en el fichero facilitado '''''ajaxmaps.php'''''
$xajax->register(XAJAX_FUNCTION, "obtenerCoordenadas");
$xajax->register(XAJAX_FUNCTION, "ordenarReparto");

$idCliente = '287333606958-vmsl2jcle2alpg5721e1elotbnq2s7k0.apps.googleusercontent.com';
$passCliente = 'XXWUYp1xJguunzD-A6wpguQx';
$keyDeveloper = 'AIzaSyDUS7upiwtzxDrGpw3gHopqjukmnfgKmJ8';

//URL Donde google redirigirá la aplicación una vez que se haya autentificado
//En mi caso el mismo fichero php que contiene la aplicación
$urlRedirect = 'http://localhost/Repartos/index.php';


// Creamos el objeto de la API de Google, primero un objeto de la clase Client
$cliente = new Google_Client();


// Y lo configuramos con los nuestros identificadores

$cliente->setApplicationName("Gestor de repartos");

//Establecemos las credenciales para este cliente
$cliente->setClientId($idCliente);
$cliente->setClientSecret($passCliente);
$cliente->setDeveloperKey($keyDeveloper);

//Este método especificará la url donde queremos que google redirija la aplicación una vez que se haya logeado correctamente el usuario y que se hayan establecido de manera correcta las credenciales correspondiente. En nuestro caso será al mismo fichero.
$cliente->setRedirectUri($urlRedirect);


//Establecemos los permisos que queremos otorgar. En este caso queremos conceder acceso a tasks y a calendar para que el usuario pueda acceder a tareas y 
$cliente->setScopes(array('https://www.googleapis.com/auth/tasks', 'https://www.googleapis.com/auth/calendar'));


if((!isset($_SESSION['token']) || (($_SESSION['token'])==null)) ){
    // we ask the code
    if (!isset($_GET['code'])) {

        //we get the url where the token is going to be created
        $url = $cliente->createAuthUrl();

        //we redirect to that url
        header("Location:" . filter_var($url, FILTER_SANITIZE_URL));
    } else {
        //if we have token, we just use it
        $cliente->authenticate($_GET['code']);
        $_SESSION['token'] = $cliente->getAccessToken();
    }
}else{
    $cliente->setAccessToken($_SESSION['token']);
}   

//Objeto con el api que queremos trabajar en este caso task
$apiTareas= new Google_Service_Tasks($cliente);
 
//Objeto con el api que queremos trabajar con el calendario
$apiCalendario = new Google_Service_Calendar($cliente);

//echo "Estoy aqui con mi token: " . $_SESSION['token']['access_token'];

//Si ejecutamos el fichero habiendo dando al botón de un formulario llamado accion

if (isset($_GET['accion'])){
    switch ($_GET['accion']) {
        case 'nuevalista':
            //Si no está vacio el titulo cremaos una nueva lista de reparto
            if(!empty($_GET['fechaReparto'])){
                try{
                    $fecha = explode('/',$_GET['fechaReparto']);
                    if(count($fecha)==3 && checkdate($fecha[1], $fecha[0], $fecha[2])){
                        //Creamos un nuevo evento para incorporar en el calendario
                        $evento = new Google_Service_Calendar_Event();
                        $evento->setSummary('Reparto');

                        //Establecemos el comienzo del evento
                        $comienzo = new Google_Service_Calendar_EventDateTime();
                        //Ponemos la fecha al comienzo del evento
                            /* EXAMEN Ejercicio 1 Formato Fecha*/
                        $comienzo->setDateTime("$fecha[2]/$fecha[1]/$fecha[0]T09:00:00.000");
                        $comienzo->setTimeZone("Europe/Madrid");
                        $evento->setStart($comienzo);

                        //Establecemos el final del evento
                        $final = new Google_Service_Calendar_EventDateTime();
                        //Ponemos la fecha al comienzo del evento
                            /* EXAMEN Ejercicio 1 Formato Fecha */
                        $final->setDateTime("$fecha[2]/$fecha[1]/$fecha[0]T20:00:00.000");
                        $final->setTimeZone("Europe/Madrid");
                        $evento->setEnd($final);

                        $crearEvento = $apiCalendario->events->insert('primary', $evento);

                    }
                    //Crear una nueva lista de reparto
                    $listaTareas = new Google_Service_Tasks_TaskList();
                    /* EXAMEN Ejercicio 1 Solo Reparto*/
                    $listaTareas->setTitle("Reparto ".$_GET['fechaReparto']);
                    $apiTareas->tasklists->insert($listaTareas);
                } catch (Exception $e) {
                    $error = "Se ha producido un error al intentar crear un nuevo reparto. " . $e->getMessage();
                }      
            }
            break; 
        case 'nuevatarea':
            $nuevaTarea = new Google_Service_Tasks_Task();
            $nuevaTarea->setTitle($_GET['nuevotitulo']);
            $apiTareas->tasks->insert($_GET['idreparto'],$nuevaTarea);
            break;
    }
}
 
?>


<!DOCTYPE html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <title>Ejemplo Tema 8: Rutas de reparto</title>
    <link href="estilos.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="codigo.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <?php
        $xajax->printJavascript();
    ?>
    </head>
<body>
    <div id="fondonegro"></div>
    <div id="dialogo">
        <a id="cerrarDialogo" onclick="ocultarDialogo();">x</a>
        <h1>Datos del nuevo envío</h1>
        <form id="formenvio" name="formenvio" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
            <fieldset>
                <div id="datosDireccion">
                    <p>
                        <label for='direccion' >Dirección:</label>
                        <input type='text' size="45" name='direccion' id='direccion' />
                    </p>
                    <input type='button' id='obtenerCoordenadas' value='Obtener coordenadas' onclick="getCoordenadas();"/><br />
                </div>
                <div id="datosEnvio">
                    <p>
                        <label for='latitud' >Latitud:</label>
                        <input type='text' size="10" name='latitud' id='latitud' />
                    </p>
                    <p>
                        <label for='longitud' >Longitud:</label>
                        <input type='text' size="10" name='longitud' id='longitud' />
                    </p>
                    <p>
                        <label for='nuevotitulo' >Título:</label>
                        <input type='text' size="40" name='nuevotitulo' id='titulo' />
                    </p>
                    <input type='hidden' name='accion' value='nuevatarea' />
                    <input type='hidden' name='idreparto' id='idrepartoactual' />
                    <input type='submit' id='nuevoEnvio' value='Crear nuevo Envío' />
                    <a href="#" onclick="abrirMaps();">Ver en Google Maps</a><br />
                </div>
            </fieldset>
        </form>
    </div>  <!-- end div dialogo-->
    <div class="contenedor">
        <div class="encabezado">
            <h1>Ejemplo Tema 8: Rutas de reparto</h1>
            <form id="nuevoreparto" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
                <fieldset>
                    <input type='hidden' name='accion' value='nuevalista' />
                    <input type='submit' id='crearnuevotitulo' value='Crear Nueva Lista de Reparto' />
                    <label for='nuevotitulo' >Fecha de reparto:</label>
                    <input type='text' name='fechaReparto' id='fechaReparto' />
                </fieldset>
            </form>
        </div>
        <div class="contenido">
            <?php 
            
            //Aquí incorporamos las listas de tareas de mi gmail
            $repartos = $apiTareas->tasklists->listTaskLists();
            foreach($repartos['items'] as $reparto){
                //echo $reparto['id'];
                echo '<div id="' . $reparto['id'] . '">';
                    echo '<span class="titulo">' . $reparto['title'] . '</span>';
                    $idreparto = "'" . $reparto['id'] . "'";
                    echo '<span class="accion">(<a href="#" onclick="nuevoEnvio(' . $idreparto . ');">Nuevo Envío</a>)</span>';
                    print '<ul>';
                        // Cogemos de la lista de reparto las tareas de envío
                        $envios = $apiTareas->tasks->listTasks($reparto['id']);
                        // Por si no hay tareas de envío en la lista
                        if (!empty($envios['items'])) {
                            foreach ($envios['items'] as $envio) {
                                /* EXAMEN Ejercicio 1 Solo Reparto*/
                                if(substr($envio['title'], "Reparto")){
                                    // Creamos un elemento para cada una de las tareas de envío
                                    $idenvio = "'" . $envio['id'] . "'";
                                    echo '<li title="' . $envio['notes'] . '" id="' . $idenvio . '">' . $envio['title'];
                                        $coordenadas = "'" . $envio['notes'] . "'";
                                        echo '<span class="accion">  (<a href="#" onclick="abrirMaps(' . $coordenadas . ');">Ver mapa</a>)</span>';
                                        echo '<span class="accion">  (<a href="' . $_SERVER['PHP_SELF'] . '?accion=borrartarea&reparto=' . $reparto['id'] . '&envio=' . $envio['id'] . '">Borrar</a>)</span>';
                                    echo '</li>';
                                }   
                            }
                        }
                    print '</ul>';
                echo "</div>";
                
            }
            
            ?>
        </div>
        <div class="pie">
            <?php print $error; ?>
        </div>
    </div>
    
</body>
</html>