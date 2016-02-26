<?php

    $url="http://localhost";
    $uri="http://localhost/examen2eva/Ejercicio3/";
    $opt=['uri'=>$uri,'location'=>$url];
    
    $cliente = new SoapClient(null, $opt);
    
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h1>Indicar los grados</h1>
        <fieldset>
        <legend>Grados Centigrados</legend>
            <form action="cliente.php" method="POST" >
            Grados Centigrados:
                <input type="text" name="gradosCel" maxlength="20" /><br/>
                <input type="submit" name="convertir" value="Convertir"/>
            </form>
        </fieldset>
        <fieldset>
            <form action="cliente.php" method="POST" >
            Grados Fahrenheit:
                <input type="text" name="gradosFen" maxlength="20" /><br/>
                <input type="submit" name="convertir" value="Convertir"/>
            </form>
        </fieldset>
        <fieldset>
            <form action="cliente.php" method="POST" >
            Grados Kelvin:
                <input type="text" name="gradosKel" maxlength="20" /><br/>
                <input type="submit" name="convertir" value="Convertir"/>
            </form>
        </fieldset>
        <?php
        //Dominio valido 'reeze.cn'
        if(isset($_POST['convertir'])){
            $respuesta;
            
            $gradosCel = $_POST['gradosCel'];
            $gradosFen = $_POST['gradosFen'];
            $gradosKel = $_POST['gradosKel'];
            
            if($gradosCel!= null || $gradosCel!= ""){
                $respuesta = $cliente->convertirCel($gradosCel);
            }
            if($gradosFen!= null || $gradosFen!= ""){
                $respuesta = $cliente->convertirFen($gradosFen);
            }
            if($gradosKel!= null || $gradosKel!= ""){
                $respuesta = $cliente->convertirKel($gradosKel);
            }
            
            echo "<fieldset>";
                echo "<legend>Especificación de conversión</legend>";
                echo $respuesta;
            echo "</fieldset>";
        }
        ?>
    </body>
</html>

