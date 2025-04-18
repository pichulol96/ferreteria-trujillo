<?php
    $host="localhost";
    $database="ferreteria_trujillo";
    $userDB="root";
    $password="";

    $conexion = new mysqli($host,$userDB,$password,$database);
    if($conexion->connect_errno) {
        echo "erro de conexion a la base de datos";
        exit();
    }
?>