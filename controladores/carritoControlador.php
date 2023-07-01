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

        public function lista_productos_controlador()
        {
            
            $consulta="SELECT * FROM tblproducto
            INNER JOIN catcolores ON tblproducto.colorProducto = catcolores.idColor
            INNER JOIN catcategorias ON tblproducto.categoriaProducto = catcategorias.idCategoria
            INNER JOIN cattela ON tblproducto.telaProducto = cattela.idTela
            ";
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

        public function lista_catalogos_controlador()
        {
            $colores="SELECT * FROM catcolores";
            $categorias="SELECT * FROM catcategorias";
            $tela="SELECT * FROM cattela";
  
            /*Se establece la conexión con la bd */ 
            $conexion= mainModel::conectar();
            $colores = $conexion->query($colores);
            $categorias = $conexion->query($categorias);
            $tela = $conexion->query($tela);

            if ($colores->rowCount()> 0){
                $colores = $colores->fetchAll();
            }
            else
            {
                $colores = 0;//No hay datos
            }

            if ($categorias->rowCount()> 0){
                $categorias = $categorias->fetchAll();
            }
            else
            {
                $categorias = 0;//No hay datos
            }

            if ($tela->rowCount()> 0){
                $tela = $tela->fetchAll();
            }
            else
            {
                $tela = 0;//No hay datos
            }

            $datos = [
                "colores"=>$colores,
                "categorias"=>$categorias,
                "tela"=>$tela
            ];

            return $datos;
        }

        /*CONTRALADORES PARA EL CLIENTE*/
        public function lista_productos_cliente_controlador($tallas, $categorias, $telas, $colores, $limiteInferior, $cantRegistros)
        {
            
            $consulta="SELECT * FROM tblproducto
            INNER JOIN tbldetproducto ON tblproducto.idProducto = tbldetproducto.producto
            INNER JOIN cattallas ON tbldetproducto.tallaProducto = cattallas.idTalla
            INNER JOIN catcategorias ON tblproducto.categoriaProducto = catcategorias.idCategoria
            INNER JOIN cattela ON tblproducto.telaProducto = cattela.idTela
            INNER JOIN catcolores ON tblproducto.colorProducto = catcolores.idColor   
            WHERE (cantidadDisponible > 0) ";
            
            if($tallas != 0)
            {
                $cTalla = 0;
                $consulta= $consulta."AND (";//El AND
                foreach($tallas as $talla)
                {
                    if ($cTalla < 1)
                    {
                        $consulta= $consulta."(cattallas.idTalla = ".$talla.") ";
                    }
                    else
                    {
                        $consulta= $consulta."OR (cattallas.idTalla = ".$talla.") ";
                    }
                    $cTalla++;
                }
                $consulta= $consulta.") ";
            }

            if($categorias != 0)
            {
                $ccategoria = 0;
                $consulta= $consulta."AND (";//El AND
                foreach($categorias as $categoria)
                {
                    if ($ccategoria < 1)
                    {
                        $consulta= $consulta."(catcategorias.idCategoria = ".$categoria.") ";
                    }
                    else
                    {
                        $consulta= $consulta."OR (catcategorias.idCategoria = ".$categoria.") ";
                    }
                    $ccategoria++;
                }
                $consulta= $consulta.") ";
            }

            if($telas != 0)
            {
                $ctela = 0;
                $consulta= $consulta."AND (";//El AND
                foreach($telas as $tela)
                {
                    if ($ctela < 1)
                    {
                        $consulta= $consulta."(cattela.idTela = ".$tela.") ";
                    }
                    else
                    {
                        $consulta= $consulta."OR (cattela.idTela = ".$tela.") ";
                    }
                    $ctela++;
                }
                $consulta= $consulta.") ";
            }

            if($colores != 0)
            {
                $ccolor = 0;
                $consulta= $consulta."AND (";//El AND
                foreach($colores as $color)
                {
                    if ($ccolor < 1)
                    {
                        $consulta= $consulta."(catcolores.idColor = ".$color.") ";
                    }
                    else
                    {
                        $consulta= $consulta."OR (catcolores.idColor = ".$color.") ";
                    }
                    $ccolor++;
                }
                $consulta= $consulta.") ";
            }

            $consulta= $consulta."GROUP BY tbldetproducto.producto 
            LIMIT ".$limiteInferior.", ".$cantRegistros."";
            mainModel::console_log($consulta);
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

            mainModel::console_log($datos);
            return $datos;
        }

        public function lista_catalogos_cliente_controlador()
        {
            $colores="SELECT * FROM catcolores";
            $categorias="SELECT * FROM catcategorias";
            $tela="SELECT * FROM cattela";
            $tallas="SELECT * FROM cattallas";
  
            /*Se establece la conexión con la bd */ 
            $conexion= mainModel::conectar();
            $colores = $conexion->query($colores);
            $categorias = $conexion->query($categorias);
            $tela = $conexion->query($tela);
            $tallas = $conexion->query($tallas);

            if ($colores->rowCount()> 0){
                $colores = $colores->fetchAll();
            }
            else
            {
                $colores = 0;//No hay datos
            }

            if ($categorias->rowCount()> 0){
                $categorias = $categorias->fetchAll();
            }
            else
            {
                $categorias = 0;//No hay datos
            }

            if ($tela->rowCount()> 0){
                $tela = $tela->fetchAll();
            }
            else
            {
                $tela = 0;//No hay datos
            }

            if ($tallas->rowCount()> 0){
                $tallas = $tallas->fetchAll();
            }
            else
            {
                $tallas = 0;//No hay datos
            }

            $datos = [
                "colores"=>$colores,
                "categorias"=>$categorias,
                "tela"=>$tela,
                "tallas"=>$tallas
            ];

            return $datos;
        }

        public function totalProductos_controlador($tallas, $categorias, $telas, $colores)
        {
            $consulta="SELECT COUNT(DISTINCT(idProducto)) FROM tblproducto
            INNER JOIN tbldetproducto ON tblproducto.idProducto = tbldetproducto.producto
            INNER JOIN cattallas ON tbldetproducto.tallaProducto = cattallas.idTalla
            INNER JOIN catcategorias ON tblproducto.categoriaProducto = catcategorias.idCategoria
            INNER JOIN cattela ON tblproducto.telaProducto = cattela.idTela
            INNER JOIN catcolores ON tblproducto.colorProducto = catcolores.idColor   
            WHERE (cantidadDisponible > 0) ";
            
            if($tallas != 0)
            {
                $cTalla = 0;
                $consulta= $consulta."AND (";//El AND
                foreach($tallas as $talla)
                {
                    if ($cTalla < 1)
                    {
                        $consulta= $consulta."(cattallas.idTalla = ".$talla.") ";
                    }
                    else
                    {
                        $consulta= $consulta."OR (cattallas.idTalla = ".$talla.") ";
                    }
                    $cTalla++;
                }
                $consulta= $consulta.") ";
            }

            if($categorias != 0)
            {
                $ccategoria = 0;
                $consulta= $consulta."AND (";//El AND
                foreach($categorias as $categoria)
                {
                    if ($ccategoria < 1)
                    {
                        $consulta= $consulta."(catcategorias.idCategoria = ".$categoria.") ";
                    }
                    else
                    {
                        $consulta= $consulta."OR (catcategorias.idCategoria = ".$categoria.") ";
                    }
                    $ccategoria++;
                }
                $consulta= $consulta.") ";
            }

            if($telas != 0)
            {
                $ctela = 0;
                $consulta= $consulta."AND (";//El AND
                foreach($telas as $tela)
                {
                    if ($ctela < 1)
                    {
                        $consulta= $consulta."(cattela.idTela = ".$tela.") ";
                    }
                    else
                    {
                        $consulta= $consulta."OR (cattela.idTela = ".$tela.") ";
                    }
                    $ctela++;
                }
                $consulta= $consulta.") ";
            }

            if($colores != 0)
            {
                $ccolor = 0;
                $consulta= $consulta."AND (";//El AND
                foreach($colores as $color)
                {
                    if ($ccolor < 1)
                    {
                        $consulta= $consulta."(catcolores.idColor = ".$color.") ";
                    }
                    else
                    {
                        $consulta= $consulta."OR (catcolores.idColor = ".$color.") ";
                    }
                    $ccolor++;
                }
                $consulta= $consulta.") ";
            }

            //$consulta= $consulta."GROUP BY tbldetproducto.producto";
            /*Se establece la conexión con la bd */ 
            $conexion= mainModel::conectar();
            $productos = $conexion->query($consulta);

            $productos = $productos->fetch();

            // mainModel::console_log($consulta);
            // mainModel::console_log($productos['COUNT(DISTINCT(idProducto))']);

            return $productos['COUNT(DISTINCT(idProducto))'];
        }

        public function producto_cliente_controlador($codigoProducto)
        {
            $producto="SELECT * FROM tblproducto 
            WHERE idProducto = '".$codigoProducto."'
            ";
  
            /*Se establece la conexión con la bd */ 
            $conexion= mainModel::conectar();
            $producto = $conexion->query($producto);
            

            if ($producto->rowCount()> 0){
                $producto = $producto->fetch();
            }
            else
            {
                $producto = 0;//No hay datos
            }

            return $producto;
        }

        public function tallas_producto_cliente_controlador($codigoProducto)
        {
            $tallas="SELECT * FROM tbldetproducto
            INNER JOIN cattallas ON tbldetproducto.tallaProducto = cattallas.idTalla
            WHERE ((producto = '".$codigoProducto."')
            AND (cantidadDisponible > 0))
            ";
  
            /*Se establece la conexión con la bd */ 
            $conexion= mainModel::conectar();
            $tallas = $conexion->query($tallas);
            

            if ($tallas->rowCount()> 0){
                $tallas = $tallas->fetchAll();
            }
            else
            {
                $tallas = 0;//No hay datos
            }

            return $tallas;
        }


        
    } 