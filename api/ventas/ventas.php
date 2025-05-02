<?php
    $JSONData = file_get_contents("php://input");
    $dataObject = json_decode($JSONData);
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('content-type: application/json; charset=utf-8');
    include "../db/conexion.php";
    date_default_timezone_set('America/Mexico_City');
    $start=$dataObject->start;
    $end=$dataObject->end;
    if($dataObject->start == "" || $dataObject->end == ""){
        $start = date('Y-m-d');
        $end = date('Y-m-d');
    }
    $result = mysqli_query(
        $conexion,"select * from ventas where fechaventa BETWEEN '$start' and '$end';"
    );
    $array = array();
    while($consulta = mysqli_fetch_array($result)){ 
        array_push($array, $consulta);
    }
    echo json_encode($array);
?>