<?php
    $JSONData = file_get_contents("php://input");
    $dataObject = json_decode($JSONData);
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('content-type: application/json; charset=utf-8');
    include "../db/conexion.php";
    $result = mysqli_query(
        $conexion,"SELECT idproducto,productos.nombre as producto, descripcion,precio,img, categorias.nombre as categorias
        FROM productos
        INNER JOIN categorias
        ON productos.id_categoria = categorias.idcategoria where productos.nombre LIKE '%$dataObject->text%' order by categorias.nombre;"
    );
    $array = array();
    $array2 = array();
    while($consulta = mysqli_fetch_array($result)){ 
        array_push($array, $consulta);
    }
    foreach($array as $arra) {
        $arra['descripcion']=utf8_decode($arra['descripcion']);
        array_push($array2, $arra);
    }
    echo json_encode($array);
?>