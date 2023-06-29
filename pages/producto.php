<?php
require_once "main/public/top.php";
?>
<?php
require_once "../controladores/productosControlador.php";
$ins_producto = new productosControlador();

$producto = $ins_producto->producto_cliente_controlador($_GET['codProd']);
$tallas = $ins_producto->tallas_producto_cliente_controlador($_GET['codProd']);

?>

<style>
  @media (max-width: 576px) {
  /* Estilos para dispositivos móviles */
  .mobile-center {
    display: flex;
    justify-content: center;
  }
}

</style>

  <div class="container-fluid" id="primerElemento">
      <div class="row" id="contenedor">
         
          <div class="col-md-2 col-4" id="fotos">
                <div class="row">
                    <div class="imgSmall">
                    <img src="../img/imgProductos/<?= $producto['codigoEstilo'] ?>/main.jpeg" class="fotoSmall" alt="" width="80%">
                    </div>
                
                </div>
              <div class="row">
                  <div class="imgSmall">
                    <img src="../img/imgProductos/<?= $producto['codigoEstilo'] ?>/front.jpeg" class="fotoSmall" alt="" width="80%">
                  </div>
                
              </div>
              <div class="row">
                  <div class="imgSmall">
                    <img src="../img/imgProductos/<?= $producto['codigoEstilo'] ?>/back.jpeg" class="fotoSmall" alt="" width="80%">
                  </div>
                
              </div>
              
          </div>
          <div class="col-md-6 col-8 d-flex align-items-center">
            <div class="row">
                <img src="../img/imgProductos/<?= $producto['codigoEstilo'] ?>/main.jpeg" alt="" id="mainPhoto">
              </div>
          </div>
          <div class="col-md-4 col-12 mobile-center">
            <form class="needs-validation" novalidate>
              <div class="text-center">
                <h1><?= $producto['descripcionProducto'] ?></h1>
                <h3>Precio: <span><?= mainModel::formatDollar($producto['precioProducto']) ?></span></h3>
                
                <br>
                <h4>Escoja Su talla:</h4>

                <ul class="text-start mb-0" style="padding-left: 45%;">
                    <!-- <li><input type="checkbox" name="" id="XS" value="1" required> XS HOMBRE
                      <div class="invalid-feedback">
                        Seleccione una talla
                      </div>
                    </li> -->
                    <?php 
                    if ($tallas != 0)
                    {
                      foreach($tallas as $rows)
                      { ?>

                      <li><input type="checkbox" name="tallasProducto[]" value="<?= $rows['tallaProducto'] ?>"> <?= $rows['nombreTalla'] ?></li>
                      <?php 
                      }
                    }else{
                    ?>
                      <li>No hay tallas disponibles para este producto</li>
                    <?php 
                    } 
                    ?>
                </ul>

                <br>
             

                <h4>Cantidad:</h4>
                <div class="d-flex justify-content-center">
                <div class="d-flex" style="width: 30%; min-width: 120px;">
                      <button class="btn btn-link px-2 me-2"
                        onclick="this.parentNode.querySelector('input[type=number]').stepDown(); return false;">
                        <i class="fas fa-minus"></i>
                      </button>
      
                      <input id="form1" min="0" name="quantity" value="1" type="number"
                        class="form-control form-control-sm" />
      
                      <button class="btn btn-link px-2 ms-2"
                        onclick="this.parentNode.querySelector('input[type=number]').stepUp(); return false;">
                        <i class="fas fa-plus"></i>
                      </button>
                </div>
                </div>
                
                
                <br>

                <button type="submit" class="btn btn-primary btn-block mb-4">Agregar a carrito</button>
              </div>
            </form>
          </div>

          <!-- TABLA DE TALLAS -->
          <div class="card rounded-3 mb-4 profundidad">
                <div class="card-body p-4">
                  <div class="row d-flex justify-content-between align-items-center">
                    <div class="col-md-2 col-lg-2 col-xl-2">
                      <img
                        src="../img/Camisa1.jpg"
                        class="img-fluid rounded-3" alt="Cotton T-shirt">
                    </div>
                    <div class="col-md-3 col-lg-3 col-xl-3">
                      <p class="lead fw-normal mb-2">Round Red</p>
                      <p><span class="text-muted">Size: </span>M <span class="text-muted">Color: </span>Red</p>
                    </div>
                    <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                      <button class="btn btn-link px-2"
                        onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                        <i class="fas fa-minus"></i>
                      </button>
      
                      <input id="form1" min="0" name="quantity" value="1" type="number"
                        class="form-control form-control-sm" />
      
                      <button class="btn btn-link px-2"
                        onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
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
              </div>
          
            <!-- REVIEWS -->
            <div class="card rounded-3 mb-4 profundidad">
                <div class="card-body p-4">
                  <div class="row d-flex justify-content-between align-items-center">
                    <div class="col-md-2 col-lg-2 col-xl-2">
                      <img
                        src="../img/Camisa1.jpg"
                        class="img-fluid rounded-3" alt="Cotton T-shirt">
                    </div>
                    <div class="col-md-3 col-lg-3 col-xl-3">
                      <p class="lead fw-normal mb-2">Round Red</p>
                      <p><span class="text-muted">Size: </span>M <span class="text-muted">Color: </span>Red</p>
                    </div>
                    <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                      <button class="btn btn-link px-2"
                        onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                        <i class="fas fa-minus"></i>
                      </button>
      
                      <input id="form1" min="0" name="quantity" value="1" type="number"
                        class="form-control form-control-sm" />
      
                      <button class="btn btn-link px-2"
                        onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
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
              </div>

      </div>
  </div>

      <script>
          document.onclick = evento;

        function evento(e){
            let elementoClicked;

            if (e == null)
            {
                elementoClicked = event.srcElement;
            }
            else
            {
                elementoClicked = e.target;
            }

            if (elementoClicked.className == 'fotoSmall')
            {
                document.getElementById("mainPhoto").src = elementoClicked.src;
            }
            
        }
      </script>
  
      <!-- Validations -->

      <script src="form-validation.js"></script>
      <script>
          // Example starter JavaScript for disabling form submissions if there are invalid fields
          (function () {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
              .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                  if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                  }

                  form.classList.add('was-validated')
                }, false)
              })
          })()

          
      </script>

<?php
    require_once "main/public/bottom.php";
    ?>