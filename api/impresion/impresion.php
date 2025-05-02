<?php
    include "../db/conexion.php";
    $JSONData = file_get_contents("php://input");
    $dataObject = json_decode($JSONData);
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('content-type: application/json; charset=utf-8');
    /* Call this file 'hello-world.php' */
    require __DIR__ . '../../../vendor/autoload.php';
    //$imagen= include("archivos/logo.png");
    use Mike42\Escpos\Printer;
    use Mike42\Escpos\EscposImage;
    use Mike42\Escpos\PrintConnectors\FilePrintConnector;
    use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
    date_default_timezone_set('America/Mexico_City');
    $fecha_hora = new DateTime(); 
    $hoy= $fecha_hora->format('Y-m-d H:i:s'); 
    $fecha = date('Y-m-d');
    if($dataObject->productos !=[]){
        $connector = new WindowsPrintConnector("POS-80C-test");
        $printer = new Printer($connector);
        //$logo = EscposImage::load("archivos/impre.jpg");
        /*if($dataObject->mesa =="pedido")
        {
           $mesa =$dataObject->mesa;
        }
        if($dataObject->mesa !="pedido"){
            $mesa = "Mesa: numero $dataObject->mesa";
        }*/
        //(dirname(FILE) . "/logo.png", false);
        //$printer -> text("$imagen\n");
        //$img = EscposImage::load("archivos/logo.png");
        //$printer -> graphics($img);
        $printer -> setTextSize(2,2);
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> text("Ferreteria Trujillo\n\n\n");
        //$printer -> bitImage($logo);
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> setTextSize(1,1);
        $printer -> text("Fecha: $hoy\n\n");
        //$printer -> text("$mesa\n\n");
        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> text("Articulo                           Precio   Cant\n\n");
        $precio_total=0;
        $cantidad_total=0;
        foreach($dataObject->productos  as $item ){
            //$productos=($item->producto);
            $printer -> setJustification(Printer::JUSTIFY_RIGHT);
            $printer -> text("$item->producto"."                  "."$item->precio"."     "."$item->cantidad\n");
            $precio_total=$precio_total+$item->precio;
            $cantidad_total=$cantidad_total+$item->cantidad;
            //$printer -> setJustification(Printer::JUSTIFY_CENTER);
           // $printer -> text("$item->precio");
            //$printer -> feed();
            //$printer -> setJustification(Printer::JUSTIFY_RIGHT);
            //$printer -> text("$item->cantidad\n");
            //echo json_encode($item->producto);
        }
        $printer -> text("\n\n");
        $printer -> setJustification(Printer::JUSTIFY_RIGHT);
        $printer -> text("Total"."--------------------------------$ "."$precio_total"."  "."$cantidad_total");
        $printer -> text("\n\n");
        $printer -> setTextSize(1,1);
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> text("Gracias por su compra, vuelva pronto.\n\n");
        //$printer -> text("Gracias por su compra, vuelva pronto.\n\n");
        $printer -> cut();
        $printer -> close();
        //opcion = 1 impresion opcion = 2 reimpresion de ticket
        if($dataObject->opcion == 1){
            $query = "INSERT into ventas(fechaventa,importe) 
            values('$hoy',$precio_total)";
            $execute = mysqli_query($conexion,$query) or die(mysqli_error($conexion));
            if($execute){
                try {
                    $last_id = $conexion->insert_id;
                    foreach($dataObject->productos  as $item ){
                        $query = "INSERT into detalle_ventas(cantidad,id_producto,id_venta) 
                        values($item->cantidad,$item->idproducto,$last_id)";
                        $execute = mysqli_query($conexion,$query) or die(mysqli_error($conexion));
                    }
                    echo json_encode("impresion");
                }
                catch (Exception $e) {
                    echo json_encode('Excepción capturada: ',  $e->getMessage(), "\n");
                }
            }
            else {
                echo json_encode("Hubo algun error al guardar el registro");
            }
        }
        else {
            echo json_encode("reimpresion");
        }
    }
    else{
        echo json_encode("sin datos para imprimir");
    }
?>