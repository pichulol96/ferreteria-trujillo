<?php
    include "../db/conexion.php";
    $nombre_producto =$_POST['nombre_producto'];
    $descripcion =$_POST['descripcion'];
    $precio =$_POST['precio'];
    $categoria =$_POST['categoria'];

    if($nombre_producto == "" || $descripcion=="" || $precio=="" || $categoria=="" ){
        echo json_encode("LLene todos los campos");
        return;
    }
    else {
        $nombre_imagen=$_FILES['imagen']['name'];
        $ruta = $nombre_imagen;
        if($ruta == '') {
            $ruta = 'default-image.jpg';
        }
        $query = "INSERT into productos(nombre,descripcion,precio,img,id_categoria) 
        values('$nombre_producto','$descripcion', $precio, '$ruta', $categoria)";
        $execute = mysqli_query($conexion,$query) or die(mysqli_error($conexion));
        if($execute){
            try {
                $temporal = $_FILES['imagen']['tmp_name'];
                $carpeta = "../../archivos";
                move_uploaded_file($temporal,$carpeta.'/'.$nombre_imagen);
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