<?php
    include "../db/conexion.php";
    $idproducto =$_POST['editar_idproducto'];
    $nombre_producto =$_POST['editar_nombre_producto'];
    $descripcion =$_POST['editar_descripcion'];
    $precio =$_POST['editar_precio'];
    $categoria = $_POST['editar_categoria'];
    $actual_imagen = $_POST['actual_imagen'];

    if($nombre_producto == "" || $descripcion=="" || $precio==""){
        echo json_encode("LLene todos los campos");
        return;
    }
    else {
        $nombre_imagen=$_FILES['editar_imagen']['name'];
        $ruta = $nombre_imagen;
        if($nombre_imagen){
            if(strlen($nombre_imagen)>50){
                echo "Imagen muy grande";
                return;
            }
            else {
                $query = "UPDATE productos set nombre = '$nombre_producto', descripcion = '$descripcion', precio = $precio,
                img = '$ruta', id_categoria=$categoria where idproducto = $idproducto";
            }
        }
        if(!$nombre_imagen) {
            $query = "UPDATE productos set nombre = '$nombre_producto', descripcion = '$descripcion', precio = $precio, id_categoria=$categoria
            where idproducto = $idproducto";
        }
        $execute = mysqli_query($conexion,$query) or die(mysqli_error($conexion));
        if($execute){
            try {
                if($nombre_imagen){
                    $temporal = $_FILES['editar_imagen']['tmp_name'];
                    $carpeta = "../../archivos";
                    unlink('../../archivos/'.$actual_imagen);
                    move_uploaded_file($temporal,$carpeta.'/'.$nombre_imagen);
                }
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