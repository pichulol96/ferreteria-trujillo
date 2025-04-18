<?php
include("IP.php");
include "conexion.php";
$objetoIP = new IP();
$ip =$objetoIP->nuevaIP();

$query = "UPDATE config set descripcion_config = '$ip' where idconfig = 1";
$execute = mysqli_query($conexion,$query) or die(mysqli_error($conexion));
if($execute){
    try {
        echo "Nueva ip: $ip";
    }
    catch (Exception $e) {
        echo $e;
    }
}
else {
    echo json_encode("Hubo algun error al guardar el registro");
}
?>