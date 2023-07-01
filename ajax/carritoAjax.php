<?php
    $peticionAjax = true;
    require_once "../config/app.php";

    
    /* Insttancia al controlador */
    require_once "../controladores/carritoControlador.php";
    $ins_carrito = new carritoControlador();
    /* agregar un usuario */
    if(isset($_POST['producto_carrito'])){
        echo $ins_carrito->agregar_prodCarrito_controlador();
    }
    if(isset($_POST['minun_cart_id'])){
        echo $ins_carrito->minun_controlador();
    }
    if(isset($_POST['plus_cart_id'])){
        echo $ins_carrito->plus_controlador();
    }
    if(isset($_POST['delete_cart_id'])){
        echo $ins_carrito->delete_controlador();
    }

    