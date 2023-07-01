<?php

require_once "main/public/top.php";
// use PHPMailer\PHPMailer\{PHPMailer, SMTP, Exception};

// require '../PHPMailer/src/Exception.php';
// require '../PHPMailer/src/PHPMailer.php';
// require '../PHPMailer/src/SMTP.php';


require_once "../controladores/carritoControlador.php";
$ins_carrito = new carritoControlador();
$carrito = $ins_carrito->carrito_usuario_controlador($sesionIniciada, $direccionMAC);

if ($sesionIniciada) {
    require_once "../controladores/ventasControlador.php";
    $ins_ventas = new ventasControlador();
    $usuario = $ins_ventas->info_usuario_controlador();
}


// if (isset($_POST['submit'])) {

//     $ip = $_SERVER["REMOTE_ADDR"];
//     $captcha = $_POST['g-recaptcha-response'];
//     $secretKey = '6Lc5ZwYgAAAAAE_vBYcjJTPocT4LIdPluDRHz7ae';

//     $errors = array();

//     $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) . '&response=' . urlencode($captcha) . '&remoteip=' . urlencode($ip);
//     $response = file_get_contents($url);

//     $atributos = json_decode($response, true);

//     if (!$atributos['success']) {
//         $errors[] = 'Verifica el captcha';
//         $respuesta = 'Verificacion captcha de site verify NO lograda a la fecha y hora: ' . $atributos['challenge_ts'] . ' (DATO OBTENIDO DEL JSON DE LA API)';
//     } else {
//         $respuesta = 'Verificacion captcha de site verify lograda  a la fecha y hora: ' . $atributos['challenge_ts'] . ' (DATO OBTENIDO DEL JSON DE LA API)';
//     }
// }

?>
<!-- CONTENIDO -->
<!--CHECKOUT-->
<div class="container" style="margin-top: 100px; margin-bottom: 10px; border: 1px rgb(146, 146, 146) solid; border-radius: 4px;
    background-color: rgba(167, 251, 255, 0.275);">
    <div style="border: 1px solid black  ; border-radius: 4px; margin: auto; text-align: center; margin-top: 20px; background-color: #3AF0F7;" class="mb-5">
        <h2>Checkout </h2>
    </div>
    <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Carrito</span>
                <!-- <span class="badge badge-secondary badge-pill" style="color: black ;">2</span> -->
            </h4>

            <ul class="list-group mb-3">
                <?php
                $precio_total = 0;
                if ($carrito != 0) {
                    
                    // $firstItem = true;actualizar_cantidad_controlador
                    foreach ($carrito as $rows) {
                        if ((int)$rows['cantidadCarrito'] > (int)$rows['cantidadDisponible']) {
                            $cantidad = $rows['cantidadDisponible'];
                            $carrito = $ins_carrito->actualizar_cantidad_controlador($rows['id_carrito'], $cantidad);
                        } else {
                            $cantidad = $rows['cantidadCarrito'];
                        }

                        $precio_total += ((int)$rows['precioProducto'] * $cantidad);
                ?>
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <h6 class="my-0"><?= $rows['descripcionProducto'] ?></h6>
                                <small class="text-muted">Talla <?= $rows['nombreTalla'] ?> - Cantidad: <?= $cantidad ?></small>
                            </div>
                            <span class="text-muted">$<?= ((int)$rows['precioProducto'] * $cantidad) ?></span>
                        </li>
                    <?php
                    }  // end foreach
                } else {
                    ?>
                    <h3>Aún no tienes productos en tu carrito de compras</h3>
                <?php
                }
                // action="<echo htmlentities($_SERVER['PHP_SELF']); 
                ?>

                <!-- Rebaja
          <li class="list-group-item d-flex justify-content-between bg-light">
          <div class="text-success">
            <h6 class="my-0">Codigo promocional</h6>
            <small>APPSGRAFICAS</small>
          </div>
          <span class="text-success">-$5</span>
        </li> -->
                <li class="list-group-item d-flex justify-content-between">
                    <span>Total (USD)</span>
                    <strong>$<?= $precio_total ?></strong>
                    
                </li>
            </ul>

        </div>
        <div class="col-md-8 order-md-1">
            <h4 class="mb-3">Información de facturación</h4>


            <!-- AQUI ES EL FORM PRINCIPAL -->
            <form class="needs-validation FormularioAjax" id="formVenta" action="<?php echo SERVERURL; ?>ajax/ventasAjax.php" method="POST" data-form= "save" novalidate>
            <input type="hidden" name="total_price" value="<?= $precio_total ?>">
                <div class="mb-3">
                    <label for="firstName">Primeros nombres</label>
                    <input type="text" name="nombre" class="form-control" pattern="[a-zA-ZáéíóúÁÉÍÓÚ ]{3,100}" id="firstName" placeholder="" value="<?= $sesionIniciada ? $usuario['nombres'] : '' ?>" required <?= $sesionIniciada ? 'readonly' : '' ?>>
                    <div class="invalid-feedback">
                        Nombre ingresado no valido.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="lastName">Apellidos</label>
                    <input type="text" name="apellido" class="form-control" pattern="[a-zA-ZáéíóúÁÉÍÓÚ ]{3,100}" id="lastName" placeholder="" value="<?= $sesionIniciada ? $usuario['apellidos'] : '' ?>" required <?= $sesionIniciada ? 'readonly' : '' ?>>
                    <div class="invalid-feedback">
                        Apellido ingreado no valido.
                    </div>
                </div>


                <!-- <div class="mb-3">
                    <label for="username">Nombre de usuario</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">@</span>
                        </div>
                        <input type="text" class="form-control" id="username" pattern="[a-zA-Z@0-9]{3,70}" placeholder="Usuario" required>
                        <div class="invalid-feedback" style="width: 100%;">
                            Nombre de usuario requerido.
                        </div>
                    </div>
                </div> -->

                <div class="mb-3">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="you@ejemplo.com" value="<?= $sesionIniciada ? $usuario['email'] : '' ?>" required <?= $sesionIniciada ? 'readonly' : '' ?>>
                    <div class="invalid-feedback">
                        Por favor ingrese un email valido para actualizaciones de envío.
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address">Numero de teléfono</label>
                    <input type="text" name="telefono" class="form-control" pattern="[\d]{8,15}" id="phone" placeholder="78761201" value="<?= $sesionIniciada ? $usuario['telefono'] : '' ?>" required <?= $sesionIniciada ? 'readonly' : '' ?>>
                    <div class="invalid-feedback">
                        Por favor digite un numero de telefono valido.
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address">Dirección</label>
                    <input type="text" name="direccion" class="form-control" id="address" placeholder="Managua KM 14, Residencial Villa Asturias, casa #12" required>
                    <div class="invalid-feedback">
                        Por favor digite una dirección valida.
                    </div>
                </div>

                <input type="hidden" name="sesion" value="<?= $sesionIniciada ? 1 : 0 ?>">
                <input type="hidden" name="dir_mac" value="<?= $direccionMAC ?>">

                <!-- <div class="mb-3">
                    <label for="address2">Dirección 2 <span class="text-muted">(Opcional)</span></label>
                    <input type="text" class="form-control" pattern="[a-zA-ZáéíóúÁÉÍÓÚ.#0-9 ]{3,100}" id="address2" placeholder="Apartment or suite">
                </div> -->

                <div class="row">
                    <!-- <div class="col-md-5 mb-3">
                        <label for="country">País</label>
                        <select class="custom-select d-block w-100" id="country" required>
                            <option value="">Escoger...</option>
                            <option>United States</option>
                        </select>
                        <div class="invalid-feedback">
                            Por favor, seleccione un país valido.
                        </div>
                    </div> -->
                    <!-- <div class="col-md-4 mb-3">
                        <label for="state">Departamento</label>
                        <select class="custom-select d-block w-100" id="state" required>
                            <option value="">Escoger...</option>
                            <option>California</option>
                        </select>
                        <div class="invalid-feedback">
                            Por favor, seleccione un departamento valido.
                        </div>
                    </div> -->
                    <!-- <div class="col-md-3 mb-3">
                        <label for="zip">Código Postal</label>
                        <input type="text" class="form-control" id="zip" placeholder="" required>
                        <div class="invalid-feedback">
                            Ingrese código postal valido.
                        </div>
                    </div> -->
                </div>
                <hr class="mb-4">
                <!-- <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="same-address">
                    <label class="custom-control-label" for="same-address">Utilizar dirección para envíos.</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="save-info">
                    <label class="custom-control-label" for="save-info">Guardar información para próxima gestión.</label>
                </div>
                <hr class="mb-4"> -->

                <h4 class="mb-3">Tarjeta Bancaria</h4>

                <!-- <div class="d-block my-3">
                    <div class="custom-control custom-radio">
                        <input id="credit" name="paymentMethod" type="radio" class="custom-control-input" checked required>
                        <label class="custom-control-label" for="credit">Tarjeta de crédito</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input id="debit" name="paymentMethod" type="radio" class="custom-control-input" required>
                        <label class="custom-control-label" for="debit">Tarjeta de débito</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input id="paypal" name="paymentMethod" type="radio" class="custom-control-input" required>
                        <label class="custom-control-label" for="paypal">PayPal</label>
                    </div>
                </div> -->
                <div class="row">
                    <div class="col-md-6">
                        <label for="cc-name">Nombre en tarjeta</label>
                        <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚ ]{3,100}" class="form-control" id="cc-name" placeholder="" required>
                        <small class="text-muted">Nombre completo a como sale en la tarjeta</small>
                        <div class="invalid-feedback">
                            Name on card is required
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="cc-number">Número de tarjeta</label>
                        <input type="text" name="num_tarj" class="form-control" pattern="[0-9]{3,20}" id="cc-number" placeholder="" required>
                        <div class="invalid-feedback">
                            Registre un número de tarjeta valido.
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3" style="position: relative;">
                        <label for="cc-expiration">Fecha de vencimiento</label>
                        <input type="text" maxlength="5" class="form-control" pattern="\d{1,2}\d{1,2}/\d{1,2}\d{1,2}"" id="cc-expiration" placeholder="MM/YY" required>
                        <div class="weird">

                        </div>

                        <div class="invalid-feedback">
                            Ingrese fecha de vencimiento valida
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="cc-cvv">CVV</label>
                        <input type="text" class="form-control" pattern="[0-9]{3,4}" id="cc-cvv" placeholder="" required>
                        <div class="invalid-feedback">
                            Codigo de seguridad válido requerido
                        </div>
                    </div>
                </div>
                <!-- <div class="g-recaptcha" data-sitekey="6Lc5ZwYgAAAAACSSl_LcNUDnwHCJx2nkjyBsS5Fw" data-callback="verifyCaptcha"></div>
                <div id="g-recaptcha-error"></div> -->
                <hr class="mb-4">
                <!--
        <button class="g-recaptcha btn btn-primary btn-lg btn-block" 
        data-sitekey="6LfwWAQgAAAAABeRkPt-pTX7tPcc0KWa2kt2edLH" 
        data-callback='onSubmit' 
        data-action='submit'
        type="submit"
        >Submit</button>-->
                <!--<button class="btn btn-primary btn-lg btn-block" type="submit">Realizar pago</button>-->
                <!-- <label for="input"></label>
                <input class="btn btn-primary btn-lg btn-block hidden" id="input" type="submit" name="submit" value="Comprar"> -->
                <button type="submit" class="btn btn-primary btn-block mb-4" <?= ($precio_total <= 0) ? 'disabled' : '' ?>>Comprar</button>
            </form>



        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<!-- Validations -->

<script src="form-validation.js"></script>
<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
    })()
</script>
<!-- FUncion de abajo sospechosa 
    <script>
   function onSubmit(token) {
     document.getElementById("form-check").submit();
   }
 </script>-->

<script>
    // function submitUserForm() {
    //     var response = grecaptcha.getResponse();
    //     if (response.length == 0) {
    //         document.getElementById('g-recaptcha-error').innerHTML = '<span style="color:red;">This field is required.</span>';
    //         return false;
    //     }
    //     return true;
    // }

    // function verifyCaptcha() {
    //     document.getElementById('g-recaptcha-error').innerHTML = '';
    // }
</script>

<?php
require_once "main/public/bottom.php";
?>