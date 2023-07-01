<?php
        if($peticionAjax){
            require_once "../modelos/ventasModelo.php";
        }else if (isset($pagesFlag)){
            require_once "../modelos/ventasModelo.php";
        }else{
            require_once "../../modelos/ventasModelo.php";
        }

    class ventasControlador extends ventasModelo {

        public function ventas_controlador(){

            $nombre=mainModel::limpiar_cadena($_POST['nombre']);
            $apellido=mainModel::limpiar_cadena($_POST['apellido']);
            $email=mainModel::limpiar_cadena($_POST['email']);
            $telefono=mainModel::limpiar_cadena($_POST['telefono']);
            $direccion=mainModel::limpiar_cadena($_POST['direccion']);
            $num_tarj=mainModel::limpiar_cadena($_POST['num_tarj']);
            $sesion=mainModel::limpiar_cadena($_POST['sesion']);
            $dir_mac=mainModel::limpiar_cadena($_POST['dir_mac']);

            $total_price=mainModel::limpiar_cadena($_POST['total_price']);

            // $alerta=[
            //     "Alerta"=>"simple",
            //     "Titulo"=>$producto_carrito,
            //     "Texto"=>$macAddress."\ ".$talla."\ ".$cantidadVenta."\ ".$sesionIniciada,
            //     "Tipo"=>"error"
            // ];
            // echo json_encode($alerta);
            // exit();

            $conexion= mainModel::conectar();
            /*----------------Comprobar campos vacíos -----------------*/  
            
            if($nombre=="" || $apellido=="" || $email==""
            || $telefono=="" || $direccion=="" || $sesion=="" || $num_tarj=="" || $dir_mac=="")
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

            if(!(filter_var($num_tarj, FILTER_VALIDATE_INT))){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El numero de tarjeta no coincide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }


            if ($sesion == 1)
            {
                session_start(['name'=>'cliente']);
                $idCliente = $_SESSION['idCliente'];

                $datos_venta = [
                    "usuarioVenta"=>$idCliente,
                    "direccionVenta" => $direccion,
                    "rebajaTotal" => 0,
                    "last4digits" => substr($num_tarj, -4),
                ];
            }
            else
            {
                $datos_venta = [
                    "dir_mac"=>$dir_mac,
                    "direccionVenta" => $direccion,
                    "rebajaTotal" => 0,
                    "last4digits" => substr($num_tarj, -4),
                ];
            }


            ventasModelo::agregar_venta_modelo($datos_venta);

            if ($sesion == 1)
            {
                $id_venta="SELECT MAX(idVenta) AS max_id FROM tblventas WHERE idUsuario = '$idCliente'";
                $id_venta = $conexion->query($id_venta);

                if ($id_venta->rowCount()> 0){
                    $id_venta = $id_venta->fetch();
                }
                else
                {
                    $id_venta = 0;//No hay datos
                }

                $carrito="SELECT * FROM tblcarrito WHERE cliente_id = '$idCliente'";
                $carrito = $conexion->query($carrito);

                if ($carrito->rowCount()> 0){
                    $carrito = $carrito->fetchAll();
                }
                else
                {
                    $carrito = 0;//No hay datos
                }
            }
            else
            {
                $id_venta="SELECT MAX(idVenta) AS max_id FROM tblventas WHERE dir_mac = '$dir_mac'";
                $id_venta = $conexion->query($id_venta);

                if ($id_venta->rowCount()> 0){
                    $id_venta = $id_venta->fetch();
                }
                else
                {
                    $id_venta = 0;//No hay datos
                }

                $datos_ventaSU = [
                    "ventaVSU"=>$id_venta['max_id'],
                    "nombresVSU" => $nombre,
                    "apellidosVSU" => $apellido,
                    "emailVSU" => $email,
                    "telefonoVSU" => $telefono,
                ];
                ventasModelo::agregar_ventaSinUsuario_modelo($datos_ventaSU);

                $carrito="SELECT * FROM tblcarrito WHERE client_mac = '$dir_mac'";
                $carrito = $conexion->query($carrito);

                if ($carrito->rowCount()> 0){
                    $carrito = $carrito->fetchAll();
                }
                else
                {
                    $carrito = 0;//No hay datos
                }

            }

            foreach ($carrito as $key => $value) {
                $datos_detventa = [
                    "venta"=>$id_venta['max_id'],
                    "detalleProducto" => $value['det_producto_id'],
                    "cantidadVendida" => $value['cantidad'],
                    "rebajaUnitaria" => 0,
                ];
                ventasModelo::agregar_detventa_modelo($datos_detventa);
            }

            if ($sesion == 1)
            {
                $eliminar_carrito = "DELETE FROM tblcarrito WHERE cliente_id = '$idCliente'";
            }
            else
            {
                $eliminar_carrito = "DELETE FROM tblcarrito WHERE client_mac = '$dir_mac'";
            }

            $eliminar_carrito = $conexion->query($eliminar_carrito);

            
            $alerta=[
                "Alerta"=>"simple",
                "Titulo"=>"Muchas gracias por su compra!",
                "Texto"=>"Le llegará un correo electronico de confirmación",
                "Tipo"=>"success"
            ];
            echo json_encode($alerta);


            

            

            // if($agregar_prodCarrito->rowCount()==1){
            //     $alerta=[
            //         "Alerta"=>"simple",
            //         "Titulo"=>"¡Estupendo!",
            //         "Texto"=>"Producto registrado en el carrito correctamente",
            //         "Tipo"=>"success"
            //     ];
            //     echo json_encode($alerta);
            // }
            // else
            // {
            //     $alerta=[
            //         "Alerta"=>"simple",
            //         "Titulo"=>"Ocurrió un error inesperado",
            //         "Texto"=>"No se logró añadir el Producto al carrito.",
            //         "Tipo"=>"error"
            //     ];
            //     echo json_encode($alerta);
            //     exit();
            // }
   ///////////////////////

        }


        public function info_usuario_controlador()
        {

                // session_start(['name'=>'cliente']);

                $consulta="SELECT nombres, apellidos, email, telefono
                 FROM tblusuarios
                WHERE idUsuario = '".$_SESSION['idCliente']."';
                ";
            
            /*Se establece la conexión con la bd */ 
            $conexion= mainModel::conectar();
            $datos = $conexion->query($consulta);
            
            //$total = $conexion->query("SELECT FOUND_ROWS()");   

            if ($datos->rowCount()> 0){
                $datos = $datos->fetch();
            }
            else
            {
                $datos = 0;//No hay datos
            }

            return $datos;
        }

    }