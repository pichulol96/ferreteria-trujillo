<?php
include("api/db/url_base.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <title>Panel Administrativo</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css">
    <style>
        .icon-delete{
            width: 15px;
        }
        .imagen{
            width:50px;
            border-radius:10px;
        }
        .input{
            margin-top:10px;
            margin-bottom:10px;
        }
        #imgPreview{
            width:200px;
        }
        #editar_imgPreview{
            width:200px;
        }
        textarea {
            height: 150px;
        }
        .icon-save-categoria{
            width:40px;
            cursor: pointer;
        }
        .contenedor-categoria-hide{
            display: none;
        }
        .contenedor-editar-categoria-hide{
            display: none;
        }
        .contenedor-categoria-show{
            display: block;
        }
        .contenedor-editar-categoria-show{
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Estadisticas de ventas</h1>
        <div class="row" style="display: flex;justify-content: center;">
            <div class="col-md-5">
            <label>DEL</label>
                <input
                type="date"
                id="start"
                name="trip-start"/>
                <label>HASTA</label>
                <input
                type="date"
                id="end"
                name="trip-start"/>
            </div>
            <div class="col-md-2">
                <label for="">Venta Total</label>
            </div>
            <div class="col-md-4">
                <input type="text" id="total_venta" disabled class="form-control">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-primary" onclick="consultarVentas()">
                  Consultar
                </button>
            </div>
        </div>
        <div class="shadow p-3 mb-5 bg-body-tertiary rounded table-responsive">
        <table id="tabla" class="table table-striped table-hover">
                <thead>
                    <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Fecha de la venta</th>
                    <th scope="col">Importe</th>
                    <th scope="col">Opciones</th>
                    </tr>
                </thead>
                <tbody id="lista_productos">
                </tbody>
        </table>
        </div>
    </div>
</body>
<script type="text/javascript" src="js/request.js"></script>
<script>
    var contador = 0;
    var idContador =0 ;
    var productos_carrito = [];
    const data = { text: "" };
    const url = "<?php echo $url;?>"

    getProductos(data);
    async function getProductos(data) {
        try {
             const data = {start:'',end:''};
            const result = await request(`${url}/ventas/ventas.php`,data);
            productos_carrito = result;
            llenarTabla();
        } catch (error) {
            console.error("Error:", error);
        }
    }

    function llenarTabla(){
        console.log(productos_carrito);
        let lista = document.getElementById("lista_productos");
        lista.parentNode.removeChild(lista);

        var newDiv = document.createElement("tbody");
        var currentDiv = document.getElementById("tabla");
            newDiv.setAttribute("id","lista_productos");
            //newDiv.setAttribute("class","row");
            currentDiv.appendChild(newDiv);
        
        let lista_productos = document.getElementById("lista_productos");
        let total_venta = document.getElementById("total_venta");
        let total = 0;

        const resp = productos_carrito.map(function suma(obj){
            total = parseFloat(total) + parseFloat(obj.importe);
            lista_productos.innerHTML +=`
            <tr>
                    <td>${obj.idventa}</td>
                    <td>${obj.fechaventa}</td>
                    <td>${obj.importe}</td>
                    <td>
                    <button class="btn btn-success" onclick="editar_confirm('${obj.idproducto}','${obj.producto}','${obj.descripcion}','${obj.precio}','${obj.img}','${obj.categorias}')"><img class="icon-delete" src="/wafleria/archivos/pencil.svg" /></button>
                    <button class="btn btn-danger" onclick="eliminar_confirm('${obj.idproducto}','${obj.img}')"><img class="icon-delete" src="/ferreteria-trujillo/archivos/trash_89366.svg" /></button>
                    </td>
                    
            </tr>   
            `;

        });
        total_venta.value = total.toFixed(2);
    }
    async function consultarVentas(){
        const start = document.getElementById("start").value;
        const end = document.getElementById("end").value;
        if(start =="" || end == "")
        {
            Swal.fire({
                icon: "error",
                title: "Ocurrio algun error",
                text: "los campos de fecha no tiienen que estar vacios",
            });
            return;
        }
        const fecha1 = new Date(start);
        const fecha2 = new Date(end);
        if(fecha2 < fecha1)
        {
            Swal.fire({
                icon: "error",
                title: "Ocurrio algun error",
                text: "la fecha de fin no tiene que ser menor a la de inicio ",
            });
        }
        else {
            try {
            const data = {start,end}
            const result = await request(`${url}/ventas/ventas.php`,data);
            Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Consulta!",
            text: "Consulta por fecha",
            showConfirmButton: false,
            timer: 2500
            });
            productos_carrito = result;
            llenarTabla();
            } catch (error) {
                console.error("Error:", error);
            }
        }
    }
</script>
</html>