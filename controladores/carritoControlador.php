<?php
        if($peticionAjax){
            require_once "../modelos/carritoModelo.php";
        }else if (isset($pagesFlag)){
            require_once "../modelos/carritoModelo.php";
        }else{
            require_once "../../modelos/carritoModelo.php";
        }

    class carritoControlador extends carritoModelo {

        public function agregar_prodCarrito_controlador(){


            $producto_carrito=mainModel::limpiar_cadena($_POST['producto_carrito']);
            $macAddress=mainModel::limpiar_cadena($_POST['macAddress']);
            $talla=mainModel::limpiar_cadena($_POST['talla']);
            $cantidadVenta=mainModel::limpiar_cadena($_POST['cantidadVenta']);
            $sesionIniciada=mainModel::limpiar_cadena($_POST['sesionIniciada']);

            // $alerta=[
            //     "Alerta"=>"simple",
            //     "Titulo"=>$producto_carrito,
            //     "Texto"=>$macAddress."\ ".$talla."\ ".$cantidadVenta."\ ".$sesionIniciada,
            //     "Tipo"=>"error"
            // ];
            // echo json_encode($alerta);
            // exit();

            /*----------------Comprobar campos vacíos -----------------*/  
            
            if($producto_carrito=="" || $macAddress=="" || $talla==""
            || $cantidadVenta=="" || $sesionIniciada=="")
            {
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se han llenado los campos obligatorios",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            if(!(filter_var($producto_carrito, FILTER_VALIDATE_INT))){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El producto no coinicide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            if(!(filter_var($talla, FILTER_VALIDATE_INT))){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"La talla no coinicide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            if(!(filter_var($cantidadVenta, FILTER_VALIDATE_INT))){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"La cantidad de productos no coinicide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }


            // Conseguir el detalle de producto
  
            /*Se establece la conexión con la bd */ 
            $conexion= mainModel::conectar();

            $detProducto="SELECT * FROM tbldetproducto WHERE producto = '$producto_carrito' AND tallaProducto = '$talla'";
            $detProducto = $conexion->query($detProducto);

            if ($detProducto->rowCount()> 0){
                $detProducto = $detProducto->fetch();
            }
            else
            {
                $detProducto = 0;//No hay datos
            }

            if ($detProducto == 0)
            {
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El producto no existe en esta talla",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            
            // Si el cliente tiene una sesion iniciada
            if ($sesionIniciada == 1)
            {
                session_start(['name'=>'cliente']);

                $existingRecord="SELECT * FROM tblcarrito WHERE det_producto_id = '".$detProducto['idDetProducto']."' AND cliente_id = '".$_SESSION['idCliente']."'";
                $existingRecord = $conexion->query($existingRecord);
                
                // Si ya existe un registro en el carrito
                if ($existingRecord->rowCount() > 0) {
                    $existingRecord = $existingRecord->fetch();
                    $datos_carrito = [
                        "cantidad"=>intval($cantidadVenta + $existingRecord['cantidad']),
                        "id_carrito" => $existingRecord['id_carrito'],
                    ];
                }
                // Si no existe un registro en el carrito
                else {
                    $datos_carrito = [
                        "cliente_id"=>$_SESSION['idCliente'],
                        "det_producto_id"=>$detProducto['idDetProducto'],
                        "cantidad"=>$cantidadVenta,
                    ];
                }    
            }
            // Si no tiene una sesion iniciada
            else
            {
                $existingRecord="SELECT * FROM tblcarrito WHERE det_producto_id = '".$detProducto['idDetProducto']."' AND client_mac = '$macAddress'";
                $existingRecord = $conexion->query($existingRecord);

                if ($existingRecord->rowCount() > 0) {
                    $existingRecord = $existingRecord->fetch();
                    $datos_carrito = [
                        "cantidad"=>intval($cantidadVenta + $existingRecord['cantidad']),
                        "id_carrito" => $existingRecord['id_carrito'],
                    ];
                }
                else {
                    $datos_carrito = [
                        "client_mac"=>$macAddress,
                        "det_producto_id"=>$detProducto['idDetProducto'],
                        "cantidad"=>$cantidadVenta,
                    ];
                }
            }

            $agregar_prodCarrito=carritoModelo::agregar_prodCarrito_modelo($datos_carrito);
            if($agregar_prodCarrito->rowCount()==1){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"¡Estupendo!",
                    "Texto"=>"Producto registrado en el carrito correctamente",
                    "Tipo"=>"success"
                ];
                echo json_encode($alerta);
            }
            else
            {
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se logró añadir el Producto al carrito.",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            ////
        }


        public function carrito_usuario_controlador($sesionIniciada, $direccionMAC)
        {
            if ($sesionIniciada)
            {
                session_start(['name'=>'cliente']);

                $consulta="SELECT id_carrito, descripcionProducto, precioProducto, codigoEstilo, nombreTalla,
                tblcarrito.cantidad as cantidadCarrito, cantidadDisponible, nombreColor FROM tblcarrito
                INNER JOIN tbldetproducto ON tblcarrito.det_producto_id = tbldetproducto.idDetProducto
                INNER JOIN tblproducto ON tbldetproducto.producto = tblproducto.idProducto
                INNER JOIN cattallas ON tbldetproducto.tallaProducto = cattallas.idTalla
                INNER JOIN catcolores ON tblproducto.colorProducto = catcolores.idColor
                WHERE cliente_id = '".$_SESSION['idCliente']."';
                ";
            }
            else
            {
                $consulta="SELECT id_carrito, descripcionProducto, precioProducto, codigoEstilo, nombreTalla,
                tblcarrito.cantidad as cantidadCarrito, cantidadDisponible, nombreColor FROM tblcarrito
                INNER JOIN tbldetproducto ON tblcarrito.det_producto_id = tbldetproducto.idDetProducto
                INNER JOIN tblproducto ON tbldetproducto.producto = tblproducto.idProducto
                INNER JOIN cattallas ON tbldetproducto.tallaProducto = cattallas.idTalla
                INNER JOIN catcolores ON tblproducto.colorProducto = catcolores.idColor
                WHERE client_mac = '".$direccionMAC."';
                ";
            }
            
            /*Se establece la conexión con la bd */ 
            $conexion= mainModel::conectar();
            $datos = $conexion->query($consulta);
            
            //$total = $conexion->query("SELECT FOUND_ROWS()");   

            if ($datos->rowCount()> 0){
                $datos = $datos->fetchAll();
            }
            else
            {
                $datos = 0;//No hay datos
            }

            return $datos;
        }

        public function actualizar_cantidad_controlador($id_carrito, $cantidadNueva)
        {
            $datos_carrito = [
                "id_carrito"=>$id_carrito,
                "cantidad"=>$cantidadNueva,
            ];

            $actualizar_cantidad=carritoModelo::actualizar_cantidad_modelo($datos_carrito);
            return $actualizar_cantidad;
        }

        public function minun_controlador()
        {
            $minun_cart_id=mainModel::limpiar_cadena($_POST['minun_cart_id']);

            if($minun_cart_id=="")
            {
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se han llenado los campos obligatorios",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            if(!(filter_var($minun_cart_id, FILTER_VALIDATE_INT))){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El id de carrito no coinicide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            $datos_carrito = [
                "id_carrito"=>$minun_cart_id,
            ];

            $minun_cart=carritoModelo::minun_cart_modelo($datos_carrito);
            return $minun_cart;
        }

        public function plus_controlador()
        {
            $plus_cart_id=mainModel::limpiar_cadena($_POST['plus_cart_id']);

            if($plus_cart_id=="")
            {
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se han llenado los campos obligatorios",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            if(!(filter_var($plus_cart_id, FILTER_VALIDATE_INT))){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El id de carrito no coinicide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            $datos_carrito = [
                "id_carrito"=>$plus_cart_id,
            ];

            $plus_cart=carritoModelo::plus_cart_modelo($datos_carrito);
            return $plus_cart;
        }

        public function delete_controlador()
        {
            $delete_cart_id=mainModel::limpiar_cadena($_POST['delete_cart_id']);

            if($delete_cart_id=="")
            {
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se han llenado los campos obligatorios",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            if(!(filter_var($delete_cart_id, FILTER_VALIDATE_INT))){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El id de carrito no coinicide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            $datos_carrito = [
                "id_carrito"=>$delete_cart_id,
            ];

            $delete_cart=carritoModelo::delete_cart_modelo($datos_carrito);
            return $delete_cart;
        }

        ////////////////////////////////
        
    } 