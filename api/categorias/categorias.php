<?php
    $JSONData = file_get_contents("php://input");
    $dataObject = json_decode($JSONData);
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('content-type: application/json; charset=utf-8');
    include "../db/conexion.php";
    $result = mysqli_query(
        $conexion,"SELECT idcategoria, nombre from categorias order by nombre;"
    );
    $array = array();
    while($consulta = mysqli_fetch_array($result)){ 
        array_push($array, $consulta);
    }
    echo json_encode($array);
?>