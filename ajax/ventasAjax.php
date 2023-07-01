<?php
    $peticionAjax = true;
    require_once "../config/app.php";

    
    /* Insttancia al controlador */
    require_once "../controladores/ventasControlador.php";
    $ins_ventas = new ventasControlador();
    /* agregar un usuario */
    
    if(isset($_POST['nombre'])){
        
        echo $ins_ventas->ventas_controlador();
    }

    
    // if(isset($_POST['minun_cart_id'])){
    //     echo $ins_ventas->minun_controlador();
    // }
    // if(isset($_POST['plus_cart_id'])){
    //     echo $ins_ventas->plus_controlador();
    // }
    // if(isset($_POST['delete_cart_id'])){
    //     echo $ins_ventas->delete_controlador();
    // }

    