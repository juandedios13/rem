<?php


    $host = 'localhost';
    $db = 'mydb';
    $user = 'root';
    $password = '';
    $TablaCarro = 'carro';
    $TablaPedido = 'pedidos';

    $conexion = new mysqli($host,$user,$password,$db);

    if($conexion->connect_errno){
        echo "Error";
        exit();
    }

    function cerrar($conexion){

        mysqli_close($conexion);

    }

?>