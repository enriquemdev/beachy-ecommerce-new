<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($peticionAjax) {
    require_once "../modelos/ventasModelo.php";
    //Load Composer's autoloader
    require '../vendor/autoload.php';
} else if (isset($pagesFlag)) {
    require_once "../modelos/ventasModelo.php";
    //Load Composer's autoloader
    require '../vendor/autoload.php';
} else {
    require_once "../../modelos/ventasModelo.php";
    //Load Composer's autoloader
    require '../../vendor/autoload.php';
}





class ventasControlador extends ventasModelo
{

    public function ventas_controlador()
    {
        // $alerta=[
        //     "Alerta"=>"simple",
        //     "Titulo"=>"111",
        //     "Texto"=>"ddd",
        //     "Tipo"=>"error"
        // ];
        // echo json_encode($alerta);
        // exit();

        $nombre = mainModel::limpiar_cadena($_POST['nombre']);
        $apellido = mainModel::limpiar_cadena($_POST['apellido']);
        $email = mainModel::limpiar_cadena($_POST['email']);
        $telefono = mainModel::limpiar_cadena($_POST['telefono']);
        $direccion = mainModel::limpiar_cadena($_POST['direccion']);
        $num_tarj = mainModel::limpiar_cadena($_POST['num_tarj']);
        $sesion = mainModel::limpiar_cadena($_POST['sesion']);
        $dir_mac = mainModel::limpiar_cadena($_POST['dir_mac']);

        $total_price = mainModel::limpiar_cadena($_POST['total_price']);

        // $alerta=[
        //     "Alerta"=>"simple",
        //     "Titulo"=>"$sesion",
        //     "Texto"=>"ddd",
        //     "Tipo"=>"error"
        // ];
        // echo json_encode($alerta);
        // exit();

        $conexion = mainModel::conectar();
        /*----------------Comprobar campos vacíos -----------------*/

        if (
            $nombre == "" || $apellido == "" || $email == ""
            || $telefono == "" || $direccion == "" || $sesion == "" || $num_tarj == "" || $dir_mac == ""
        ) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se han llenado los campos obligatorios",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        if (!(filter_var($num_tarj, FILTER_VALIDATE_INT))) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El numero de tarjeta no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }


        if ($sesion == 1) {
            session_start(['name' => 'cliente']);
            $idCliente = $_SESSION['idCliente'];

            $datos_venta = [
                "usuarioVenta" => $idCliente,
                "direccionVenta" => $direccion,
                "rebajaTotal" => 0,
                "last4digits" => substr($num_tarj, -4),
            ];
        } else {
            $datos_venta = [
                "dir_mac" => $dir_mac,
                "direccionVenta" => $direccion,
                "rebajaTotal" => 0,
                "last4digits" => substr($num_tarj, -4),
            ];
        }


        ventasModelo::agregar_venta_modelo($datos_venta);

        if ($sesion == 1) {
            $id_venta = "SELECT MAX(idVenta) AS max_id FROM tblventas WHERE usuarioVenta = '$idCliente'";
            $id_venta = $conexion->query($id_venta);

            if ($id_venta->rowCount() > 0) {
                $id_venta = $id_venta->fetch();
            } else {
                $id_venta = 0; //No hay datos
            }

            $carrito = "SELECT * FROM tblcarrito WHERE cliente_id = '$idCliente'";
            $carrito = $conexion->query($carrito);

            if ($carrito->rowCount() > 0) {
                $carrito = $carrito->fetchAll();
            } else {
                $carrito = 0; //No hay datos
            }
        } else {
            $id_venta = "SELECT MAX(idVenta) AS max_id FROM tblventas WHERE dir_mac = '$dir_mac'";
            $id_venta = $conexion->query($id_venta);

            if ($id_venta->rowCount() > 0) {
                $id_venta = $id_venta->fetch();
            } else {
                $id_venta = 0; //No hay datos
            }

            $datos_ventaSU = [
                "ventaVSU" => $id_venta['max_id'],
                "nombresVSU" => $nombre,
                "apellidosVSU" => $apellido,
                "emailVSU" => $email,
                "telefonoVSU" => $telefono,
            ];
            ventasModelo::agregar_ventaSinUsuario_modelo($datos_ventaSU);

            $carrito = "SELECT * FROM tblcarrito WHERE client_mac = '$dir_mac'";
            $carrito = $conexion->query($carrito);

            if ($carrito->rowCount() > 0) {
                $carrito = $carrito->fetchAll();
            } else {
                $carrito = 0; //No hay datos
            }
        }

        foreach ($carrito as $key => $value) {
            $datos_detventa = [
                "venta" => $id_venta['max_id'],
                "detalleProducto" => $value['det_producto_id'],
                "cantidadVendida" => $value['cantidad'],
                "rebajaUnitaria" => 0,
            ];
            ventasModelo::agregar_detventa_modelo($datos_detventa);
        }

        if ($sesion == 1) {
            $eliminar_carrito = "DELETE FROM tblcarrito WHERE cliente_id = '$idCliente'";
        } else {
            $eliminar_carrito = "DELETE FROM tblcarrito WHERE client_mac = '$dir_mac'";
        }

        $eliminar_carrito = $conexion->query($eliminar_carrito);

        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                    
            $mail->CharSet = 'UTF-8';                        //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'marcosmartoc78@gmail.com';                     //SMTP username
            $mail->Password   = 'xedntlggubpwiogy';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('marcosmartoc78@gmail.com', 'Beachy Nicaragua');
            $mail->addAddress($email, $nombre);     //Add a recipient

            $cuerpo = '
            Estimado ' . $nombre . ' ' . $apellido . ',<br>

            ¡Es un placer para nosotros agradecerte por tu reciente compra en Beachy Nicaragua! Queremos expresar nuestro sincero agradecimiento por elegir nuestro ecommerce para tus necesidades playeras.<br>

            Valoramos tu confianza en nosotros y nos complace decir que tu envío con valor total de: $'. $total_price .' está en proceso. En Beachy Nicaragua nos esforzamos por ofrecer artículos de alta calidad que te brinden una experiencia única en tu dia a día.<br>

            Queremos asegurarnos de que disfrutes al máximo de tus productos y que estés satisfecho con tu compra. Si tienes alguna pregunta o necesitas asistencia adicional, no dudes en contactarnos. Nuestro equipo de atención al cliente está aquí para ayudarte en cualquier momento.<br>

            ¡Gracias de nuevo por tu preferencia!<br>

            Saludos cordiales,<br>

            Staff de Beachy Nicaragua
            ';

            $cuerpo2 = '
            Estimado ' . $nombre . ' ' . $apellido . ',

            ¡Es un placer para nosotros agradecerte por tu reciente compra en Beachy Nicaragua! Queremos expresar nuestro sincero agradecimiento por elegir nuestro ecommerce para tus necesidades playeras.

            Valoramos tu confianza en nosotros y nos complace decir que tu envío con valor total de: $'. $total_price .' está en proceso. En Beachy Nicaragua nos esforzamos por ofrecer artículos de alta calidad que te brinden una experiencia única en tu dia a día.

            Queremos asegurarnos de que disfrutes al máximo de tus productos y que estés satisfecho con tu compra. Si tienes alguna pregunta o necesitas asistencia adicional, no dudes en contactarnos. Nuestro equipo de atención al cliente está aquí para ayudarte en cualquier momento.

            ¡Gracias de nuevo por tu preferencia!

            Saludos cordiales,

            Staff de Beachy Nicaragua
            ';
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = '¡Muchas gracias por su compra!';
            $mail->Body    = $cuerpo;
            $mail->AltBody = $cuerpo2;

            $mail->send();
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Muchas gracias por su compra!",
                "Texto" => "Le llegará un correo electronico de confirmación",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        } catch (Exception $e) {
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Hubo un error al enviar el correo electronico",
                "Texto" => $mail->ErrorInfo,
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        }

        // $alerta = [
        //     "Alerta" => "simple",
        //     "Titulo" => "Muchas gracias por su compra!",
        //     "Texto" => "Le llegará un correo electronico de confirmación",
        //     "Tipo" => "success"
        // ];
        // echo json_encode($alerta);

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

        $consulta = "SELECT nombres, apellidos, email, telefono
                 FROM tblusuarios
                WHERE idUsuario = '" . $_SESSION['idCliente'] . "';
                ";

        /*Se establece la conexión con la bd */
        $conexion = mainModel::conectar();
        $datos = $conexion->query($consulta);

        //$total = $conexion->query("SELECT FOUND_ROWS()");   

        if ($datos->rowCount() > 0) {
            $datos = $datos->fetch();
        } else {
            $datos = 0; //No hay datos
        }

        return $datos;
    }
}
