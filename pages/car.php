<?php
require_once "main/public/top.php";
?>
<?php
require_once "../controladores/carritoControlador.php";
$ins_carrito = new carritoControlador();

$carrito = $ins_carrito->carrito_usuario_controlador($sesionIniciada, $direccionMAC);
// $tallas = $ins_carrito->tallas_carrito_cliente_controlador($_GET['codProd']);

?>
<!-- CONTENIDO -->
<section class="h-100" style=" padding-top: 50px;" id="primerElemento">
    <div class="container h-100 py-5">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-10">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-normal mb-0 text-black">Carrito de compras</h3>
                    <!-- <div>
                        <p class="mb-0"><span class="text-muted">Ordenar por:</span> <a href="#!" class="text-body">Precio <i class="fas fa-angle-down mt-1"></i></a></p>
                    </div> -->
                </div>

                <?php
                if ($carrito != 0) {
                    // $firstItem = true;actualizar_cantidad_controlador
                    foreach ($carrito as $rows) {
                        if ((int)$rows['cantidadCarrito'] > (int)$rows['cantidadDisponible']) {
                            $cantidad = $rows['cantidadDisponible'];
                            $carrito = $ins_carrito->actualizar_cantidad_controlador($rows['id_carrito'], $cantidad);
                        } else {
                            $cantidad = $rows['cantidadCarrito'];
                        }
                ?>
                        <div container_id="<?= $rows['id_carrito'] ?>" class="card rounded-3 mb-4 profundidad">
                            <div class="card-body p-4">
                                <div class="row d-flex justify-content-between align-items-center">
                                    <div class="col-md-2 col-lg-2 col-xl-2">
                                        <img src="../img/imgProductos/<?= $rows['codigoEstilo'] ?>/main.jpeg" class="img-fluid rounded-3" alt="Cotton T-shirt">
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-xl-3">
                                        <p class="lead fw-normal mb-2"><?= $rows['descripcionProducto'] ?></p>
                                        <p><span class="text-muted">Talla: </span><?= $rows['nombreTalla'] ?> <span class="text-muted">Color: </span><?= $rows['nombreColor'] ?></p>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                                        <button class="btn btn-link px-2 min-cart" cart_id="<?= $rows['id_carrito'] ?>" onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                                            <i class="fas fa-minus" cart_id="<?= $rows['id_carrito'] ?>"></i>
                                        </button>

                                        <input min="1" max="<?= $rows['cantidadDisponible'] ?>" cart_id="<?= $rows['id_carrito'] ?>" name="quantity" value="<?= $cantidad ?>" type="number" class="form-control form-control-sm" disabled />

                                        <button class="btn btn-link px-2 plus-cart" cart_id="<?= $rows['id_carrito'] ?>" onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                                            <i class="fas fa-plus " cart_id="<?= $rows['id_carrito'] ?>"></i>
                                        </button>
                                    </div>
                                    <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                                        <h5 class="mb-0">$<?= $rows['precioProducto'] ?></h5>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                                        <a cart_id="<?= $rows['id_carrito'] ?>" class="text-danger delete-cart"><i class="fas fa-trash fa-lg" cart_id="<?= $rows['id_carrito'] ?>"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php
                    }  // end foreach
                } else {
                    ?>
                    <h3>Aún no tienes productos en tu carrito de compras</h3>
                <?php
                }
                ?>
                <!-- <div class="card rounded-3 mb-4 profundidad">
                    <div class="card-body p-4">
                        <div class="row d-flex justify-content-between align-items-center">
                            <div class="col-md-2 col-lg-2 col-xl-2">
                                <img src="../img/Camisa1.jpg" class="img-fluid rounded-3" alt="Cotton T-shirt">
                            </div>
                            <div class="col-md-3 col-lg-3 col-xl-3">
                                <p class="lead fw-normal mb-2">Round Red</p>
                                <p><span class="text-muted">Size: </span>M <span class="text-muted">Color: </span>Red</p>
                            </div>
                            <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                                <button class="btn btn-link px-2" onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                                    <i class="fas fa-minus"></i>
                                </button>

                                <input id="form1" min="0" name="quantity" value="1" type="number" class="form-control form-control-sm" />

                                <button class="btn btn-link px-2" onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                                <h5 class="mb-0">$45.00</h5>
                            </div>
                            <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                                <a href="#!" class="text-danger"><i class="fas fa-trash fa-lg"></i></a>
                            </div>
                        </div>
                    </div>
                </div> -->

                <div class="card">
                    <div class="card-body d-flex justify-content-center">
                        <a href="checkout.php" style="text-decoration: none; color: black ;">
                            <button type="button" class="btn btn-warning btn-block btn-lg">Proceder al pago</button>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<script>
    let aTag = document.getElementById("linkCart")
    aTag.classList.add("active");

    // Actions to server
    let minuns = document.querySelectorAll(".min-cart");
    minuns.forEach(element => {
        element.addEventListener("click", function(e) {
            if (this.parentNode.querySelector('input[type=number]').value > 1) {
                let cart_id = e.target.getAttribute("cart_id");
                let url = '<?php echo SERVERURL; ?>ajax/carritoAjax.php'
                let datos = new FormData();
                datos.append("minun_cart_id", cart_id);
                fetch(url, {
                    method: 'POST',
                    body: datos
                })
            }
        })
    })

    let pluses = document.querySelectorAll(".plus-cart");
    pluses.forEach(element => {
        element.addEventListener("click", function(e) {
            if (this.parentNode.querySelector('input[type=number]').value < this.parentNode.querySelector('input[type=number]').max) {
                let cart_id = e.target.getAttribute("cart_id");
                let url = '<?php echo SERVERURL; ?>ajax/carritoAjax.php'
                let datos = new FormData();
                datos.append("plus_cart_id", cart_id);
                fetch(url, {
                    method: 'POST',
                    body: datos
                })
            }
        })
    })

    let deletes = document.querySelectorAll(".delete-cart");
    deletes.forEach(element => {
        element.addEventListener("click", function(e) {
            let cart_id = e.target.getAttribute("cart_id");
            let url = '<?php echo SERVERURL; ?>ajax/carritoAjax.php'
            let datos = new FormData();
            datos.append("delete_cart_id", cart_id);
            fetch(url, {
                    method: 'POST',
                    body: datos
                })
                .then(() => {
                    // Obtener todos los elementos que tengan el atributo "container_id"
                    let elementos = document.querySelectorAll('[container_id]');
                    console.log(elementos);
                    // Iterar sobre los elementos y verificar su valor de "container_id"
                    for (let i = 0; i < elementos.length; i++) {
                        console.log(elementos[i]);
                        let elemento = elementos[i];

                        // Verificar si el valor de "container_id" es igual a 13
                        if (elemento.getAttribute('container_id') == cart_id) {
                            elemento.style.display = "none";                        
                        }
                    }
                })

        })
    })
</script>

<?php
require_once "main/public/bottom.php";
?>