<?php
include("api/db/url_base.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/ferreteria-trujillo/plugins/bootstrap/bootstrap.min.css" rel="stylesheet">
    <script src="/ferreteria-trujillo/plugins/bootstrap/bootstrap.bundle.min.js"></script>
    <title>Ferreteria</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css">
</head>
<style>
    input{
        margin-top:10px;
        margin-bottom:10px;
    }
    body{
        background:orange;
        margin-bottom:20px;
    }
    img {
        width:100px;
        padding-top: 10px;
    }
    .card{
        margin-top:40px;
        background:#444343;
        color:white;
        padding: 0px;
    }
    .card img {
        /*width:350px;
        height: 350px;*/
        padding: 0px;
    }
    .carrito{
        width: 20px;
        cursor:pointer;
        color:white;
        float: right;

    }
    .contador{
        float: left;
    }
    .icon-delete {
        width: 15px;
        margin:0px;
        padding:0px;
    }
    .btn-danger{
        border-radius:50px;
    }
    .rigth-precio{
        text-align: right;
    }
    .rigth-cantidad{
        text-align: right;
    }
    #total_precio{
        text-align: right;
        width: 80px;
    }
    #total_cantidad{
        text-align: right;
    }
</style>
<body>
    <div class="container">
        <img src="archivos/logo.jpg" alt="">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
             <input id="input" class="form-control" placeholder="Buscar" type="text" onkeyup="buscarComida(event)" >
            </div>
        </div>
        <div class="row">
            <div class="col-md-1">
            <button onclick="cargarCarrito()"  type="button" class="btn btn-warning position-relative">
                <img class="carrito" data-bs-toggle="modal" data-bs-target="#exampleModal" src="archivos/kart_icon.svg" >
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <label id="contador" class="contador" >0</label>
                    <span class="visually-hidden">unread messages</span>
                </span>
            </button>
            </div>
            <!--<div class="col-md-2">
             <img class="carrito" onclick="cargarCarrito()" data-bs-toggle="modal" data-bs-target="#exampleModal" src="archivos/kart_icon.svg" >
            </div>
            <div class="col-md-1">
              <p id="contador" class="contador" >0</p>
            </div>-->
        </div>
        <div id="cont">

        </div>
        <div id="cards" class="row">
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Detalles de la orden</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <select name="mesa" id="mesa" class="form-control">
                    <option value="null">Seleccione una mesa</option>
                    <option value="uno">uno</option>
                    <option value="dos">dos</option>
                    <option value="tres">tres</option>
                    <option value="cuatro">cuatro</option>
                    <option value="cuatro">cinco</option>
                    <option value="cuatro">seis</option>
                    <option value="cuatro">siete</option>
                    <option value="cuatro">ocho</option>
                    <option value="cuatro">nueve</option>
                    <option value="diez">diez</option>
                    <option value="pedido">pedido</option>
                </select> <br>
                <table id="tabla" class="table">
                <thead>
                    <tr>
                    <th scope="col">Productos</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Opcion</th>
                    </tr>
                </thead>
                <tbody id="lista_productos">
                </tbody>
                </table>
                <div id="cont2" class="row">
                    <div class=" col-5 col-sm-5">
                    <input type="text" disabled class="form-control" placeholder="total">
                    </div>
                    <div class="col-3 col-sm-3">
                    <input type="text"id="total_precio" disabled class="form-control">
                    </div>
                    <div class="col-2 col-sm-2">
                    <input type="text" id="total_cantidad" disabled class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button"  onclick="limpiarCarrito()" class="btn btn-defautl">Limpiar carrito</button>
                <button id="imprimir" type="button" onclick="imprimir()" class="btn btn-primary">Imprimir</button>
            </div>
            </div>
        </div>
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
            const result = await request(`${url}/productos/productos.php`,data);
            if(result){
                let cards = document.getElementById("cards");
                if(cards == null) {
                    var newDiv = document.createElement("div");
                    var currentDiv = document.getElementById("cont");
                    newDiv.setAttribute("id","cards");
                    newDiv.setAttribute("class","row");
                    currentDiv.parentNode.insertBefore(newDiv, currentDiv);
                    let cards = document.getElementById("cards");
                    let id=0;
                    for(let comida of result) {
                        cards.innerHTML +=`
                        <div class="card" style="width: 10rem; margin:auto; margin-top:20px;">
                                    <img src="archivos/${comida.img}" class="card-img-top img-fluid" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title">${comida.producto}</h5>
                                        <p class="card-text">${comida.descripcion}</p>
                                        <p class="card-text">$${comida.precio}</p>
                                        <button id="button${id}" type="button" class="btn btn-primary agregar_producto" onclick="agregarProducto('${comida.producto}','${comida.precio}','button${id}')">Agregar</button>
                                    </div>
                                </div>
                        `;
                        id++;
                    }
                    return
                }
                else {
                    let id=0;
                    for(let comida of result) {
                    cards.innerHTML +=`
                    <div class="card" style="width: 10rem; margin:auto; margin-top:20px; ">
                                <img src="archivos/${comida.img}" class="card-img-top img-fluid" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title">${comida.producto}</h5>
                                    <p class="card-text">${comida.descripcion}</p>
                                    <p class="card-text">$${comida.precio}</p>
                                    <button id="button${id}" type="button" class="btn btn-primary agregar_producto" onclick="agregarProducto('${comida.producto}','${comida.precio}','button${id}')">Agregar</button>
                                </div>
                            </div>
                    `;
                    id++;
                }
                }
            }
        } catch (error) {
            console.error("Error:", error);
        }
    }
    function buscarComida(text){
        //console.log(text);
        let input = document.getElementById("input");
        let cards = document.getElementById("cards");
        cards.parentNode.removeChild(cards);
        //console.log(input.value);
        const data = { text: input.value};
        getProductos(data);
    }
    function agregarProducto(producto,precio,id) {
        
        let elemento = document.getElementById(id);
        elemento.classList.remove('btn-primary');
        elemento.innerText ="Agregado"
        elemento.classList += " btn-success";
        let carrito = document.getElementById("contador");
        contador++;
        carrito.textContent = contador;
        productos_carrito.push({id:idContador,producto,precio:parseFloat(precio),cantidad:1});
        idContador++;

        const resp = productos_carrito.map(function suma(obj){
            return obj;
        })
        console.log(resp);
        setTimeout(() => {
            elemento.classList.remove('btn-success');
            elemento.classList += " btn-primary";
            elemento.innerText ="Agregar"
        }, 1000);
    }
    function cargarCarrito() {
        let lista = document.getElementById("lista_productos");
        lista.parentNode.removeChild(lista);

        var newDiv = document.createElement("tbody");
        var currentDiv = document.getElementById("tabla");
            newDiv.setAttribute("id","lista_productos");
            //newDiv.setAttribute("class","row");
            currentDiv.appendChild(newDiv);
        
        let lista_productos = document.getElementById("lista_productos");

        let precio_total = 0;
        let cantidad_total = 0;
        let id=0;
        const resp = productos_carrito.map(function suma(obj){
            precio_total = parseFloat(precio_total) + parseFloat(obj.precio);
            cantidad_total = parseFloat(cantidad_total) + parseFloat(obj.cantidad);
            lista_productos.innerHTML +=`
            <tr id="${id}">
                    <td>${obj.producto}</td>
                    <td class="rigth-precio">${obj.precio}</td>
                    <td class="rigth-cantidad">${obj.cantidad} </td>
                    <td><button onclick="eliminarProducto('${id}')" class="btn btn-danger"><img class="icon-delete" src="archivos/trash_89366.svg" /></button></td>
            </tr>   
            `;
            id++
        });
        let input_total_precio = document.getElementById("total_precio");
        input_total_precio.value = precio_total ;

        let input_total_cantidad = document.getElementById("total_cantidad");
        input_total_cantidad.value = cantidad_total ;
    }


    function eliminarProducto(id){
        let tupla = document.getElementById(id);
        tupla.parentNode.removeChild(tupla);
        let carrito = document.getElementById("contador");
        contador--;
        carrito.textContent = contador;
        productos_carrito.splice(id, 1);
        cargarCarrito();
    }
    async function imprimir() {
        mesa = document.getElementById("mesa").value;
        if(mesa == 'null'){
            Swal.fire({
                icon: "error",
                title: "Seleccione la mesa",
                });
            return
        }
        document.getElementById("imprimir").disabled=true;
        let agrupado2 = []; 
        //var arrayProductos = productos_carrito.slice();
        var arrayProductos = [...productos_carrito];
        for (const iterator of arrayProductos) { 
            let existe = agrupado2.filter(x=> x.producto == iterator.producto).length ==0 ? true: false 
            if(existe){ 
                let filtrado = arrayProductos.filter(x=> x.producto == iterator.producto) 
                let buscar = structuredClone(arrayProductos.find(x=> x.producto == iterator.producto))  
                let cantidad = filtrado.length 
                let total = Object.values(filtrado).reduce((t, {precio}) => t + precio, 0);   
                buscar.precio = total 
                buscar.cantidad = cantidad 
                agrupado2.push(buscar) 
            } 
        } 
        try {
            const data = {productos:agrupado2, mesa}
            const result = await request(`${url}/impresion/impresion.php`,data);
            console.log(result);
        } catch (error) {
            console.error("Error:", error);
        }
        document.getElementById("imprimir").disabled=false;
    }
    function limpiarCarrito(){
        document.getElementById("mesa").value=null;
        let tupla;
        productos_carrito.map(function(obj){
            console.log(obj.id);
            tupla = document.getElementById(obj.id);
            if(tupla){
                tupla.parentNode.removeChild(tupla);
            }
        });
        let carrito = document.getElementById("contador");
        contador=0;
        idContador =0 ;
        carrito.textContent = contador;
        productos_carrito=[]
        cargarCarrito();
    }

</script>
</html>