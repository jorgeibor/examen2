<?php

    require_once('Server.php');

    $opt = ['uri'=>'http://localhost'];
    $servicio = new SoapServer(null, $opt);

    $servicio->setClass("Server");


    $servicio->handle();

      /*
          Celsius = (Fahrenheit-32)*5/9
          Celsius = Kelvin-273.15
          Fahrenheit = (Celsius*9/5)+32
          Fahrenheit = (Kelvin*9/5)-459.67
          Kelvin = Celsius+273.15
          Kelvin = (Fahrenheit+459.67)*5/9
      */