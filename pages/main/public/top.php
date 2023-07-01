<?php
require_once "../config/app.php";
session_start(['name' => 'cliente']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0">\ -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>Beachy</title>

    <?php
    require_once "../dependencias/css/link.php";
    require_once "../dependencias/js/scriptHead.php";

    $peticionAjax = false;
    $pagesFlag = 1;

    //Vverificar inicio de sesion
    if (isset($_SESSION['nombresCliente'])) {
        $sesionIniciada = true;
    } else {
        $sesionIniciada = false;
    }

    $direccionMAC = exec('getmac');
    $direccionMAC = strtok($direccionMAC, ' ');
    require_once "../controladores/loginUsuariosControlador.php";
    $lc = new loginUsuarioControlador();

    ?>

    <style>
        .nav-link {
            margin: 0;
        }
    </style>


</head>

<body onload="">
    <!-- NAV BAR -->
    <nav class="navbar navbar-expand-md navbar-light bg-light fixed-top">
        <div class="container-fluid" style="background-color: #3AF0F7; margin-bottom: -6px;">
            <a class="navbar-brand" href="home.php">
                <img src="../img/LOGO SIN FONDO.png" alt="" width="80" height="70">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a id="linkHome" class="nav-link h4" aria-current="page" href="home.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a id="linkProd" class="nav-link h4" href="products.php">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a id="linkAcerca" class="nav-link h4 " href="acerca.php">Nosotros</a>
                    </li>
                    <li class="nav-item nav-item-icon">
                        <a id="linkCart" class="nav-link " href="car.php"><i class="fa-solid fa-cart-shopping"></i></a>
                    </li>

                    <!-- <li class="nav-item nav-item-icon">
              <i class="fa-solid fa-cart-shopping js-show-cart"></i>
            </li> -->

                    <li class="nav-item nav-item-icon">
                        <a id="linkUser" class="nav-link <?= $sesionIniciada ? 'btn-exit-system' : '' ?>" href="<?= $sesionIniciada ? '#' : 'login.php' ?>">
                            <i class="<?= $sesionIniciada ? 'fas fa-power-off' : 'fa-solid fa-user' ?>" title="<?= $sesionIniciada ? 'Cerrar Sesion' : 'Iniciar Sesion/Registrarse' ?>"></i>
                        </a>
                    </li>

                </ul>


                <form id="searchBar" class="d-flex"">
                    <input class=" form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <!-- <button class="btn btn-outline-success" type="submit">Search</button> -->
                </form>
                <div id="nombreSesion">
                    <h6 style="font-size: 1.15em; color:#454545;"><?php if (isset($_SESSION['nombresCliente'])) {
                                                                        echo "Bienvenido " . ($_SESSION['nombresCliente']);
                                                                    } ?></h6>
                </div>
            </div>
        </div>
    </nav>

    <script>
        let btn_salir = document.querySelector(".btn-exit-system");

        btn_salir.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Quieres cerrar sesión?',
                text: "La sesion actual se cerrará",
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Salir!',
                cancelButtonText: 'No, Cancelar'
            }).then((result) => {
                if (result.value) {

                    let url = '<?php echo SERVERURL; ?>controladores/cerrarSesionUsuarioControlador.php'
                    let token = '<?php echo $lc->encryption($_SESSION['token']) ?>';
                    let usuario = '<?php echo $lc->encryption($_SESSION['idCliente']) ?>';

                    let datos = new FormData();
                    datos.append("token", token);
                    datos.append("usuario", usuario);

                    fetch(url, {
                            method: 'POST',
                            body: datos
                        })
                        .then(respuesta => respuesta.json())
                        .then(respuesta => {
                            return alertas_ajax(respuesta);
                        });
                }
            });
        });
    </script>

    <!-- Toasts -->
    <div class="toast-container" style="position: fixed; top: 20px; right: 10px; z-index: 1050;">
        <!-- <div class="toast" id="myToast">
            <div class="toast-header">
                <strong id="toastTitle" class="me-auto"><i class="bi-gift-fill"></i> We miss you!</strong>
                <small>10 mins ago</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="toastText">
                It's been a long time since you visited us. We've something special for you.
            </div>
        </div> -->

        <!-- <div class="toast bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true" id="myToast" data-bs-delay="1000">
            <div class="toast-header">

                <strong id="toastTitle" class="me-auto">Bootstrap</strong>
                <small>Ahora</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastText">
                ¡Hola Mundo! Este es un mensaje de toast.
            </div>
        </div> -->

        <div id="myToast" data-bs-delay="3000" class="toast align-items-center bg-success text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastText">
                    ¡Hola Mundo! Este es un mensaje de toast.
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        // Toasts
        let element = document.getElementById("myToast");

        // Create toast instance
        let myToast = new bootstrap.Toast(element);

        function showToast(text) {
            // document.getElementById("toastTitle").innerHTML = title;
            document.getElementById("toastText").innerHTML = text;
            $("#myToast").toast("show");
        }

        // function simpleAlert(title = "Alerta", text = "Alerta Simple", type = "success", btnText = "Ok") {
        //     return new Promise((resolve) => {
        //         Swal.fire({
        //             title: title,
        //             text: text,
        //             type: type,
        //             confirmButtonColor: '#3085d6',
        //             confirmButtonText: btnText,

        //         }).then(() => {
        //             resolve();
        //         });
        //     });
        // }
    </script>