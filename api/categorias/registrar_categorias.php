<?php
    include "../db/conexion.php";
    $JSONData = file_get_contents("php://input");
    $dataObject = json_decode($JSONData);
    if($dataObject->categoria == "" || $dataObject->categoria == " " || strlen($dataObject->categoria)<4){
        echo json_encode("Datos incorrectos");
        return;
    }
    else {
        $query = "INSERT into categorias(nombre) 
        values('$dataObject->categoria')";
        $execute = mysqli_query($conexion,$query) or die(mysqli_error($conexion));
        if($execute){
            try {
                echo json_encode("success");
            }
            catch (Exception $e) {
                echo json_encode('Excepción capturada: ',  $e->getMessage(), "\n");
            }
        }
        else {
            echo json_encode("Hubo algun error al guardar el registro");
        }
    }

?>