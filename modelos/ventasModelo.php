<?php
require_once "mainModel.php";
class ventasModelo extends mainModel
{

    protected static function agregar_venta_modelo($datos)
    {
        if (array_key_exists("usuarioVenta", $datos)) {
            $sql = mainModel::conectar()->prepare("INSERT INTO tblventas(usuarioVenta, direccionVenta, rebajaTotal, last4digits)
                VALUES(:usuarioVenta, :direccionVenta, :rebajaTotal, :last4digits)");
            $sql->bindParam(":usuarioVenta", $datos['usuarioVenta']);
            $sql->bindParam(":direccionVenta", $datos['direccionVenta']);
            $sql->bindParam(":rebajaTotal", $datos['rebajaTotal']);
            $sql->bindParam(":last4digits", $datos['last4digits']);
            $sql->execute();
        }
        else
        {
            $sql = mainModel::conectar()->prepare("INSERT INTO tblventas(dir_mac, direccionVenta, rebajaTotal, last4digits)
            VALUES(:dir_mac, :direccionVenta, :rebajaTotal, :last4digits)");
            $sql->bindParam(":dir_mac", $datos['dir_mac']);
            $sql->bindParam(":direccionVenta", $datos['direccionVenta']);
            $sql->bindParam(":rebajaTotal", $datos['rebajaTotal']);
            $sql->bindParam(":last4digits", $datos['last4digits']);
            $sql->execute();
        }
        return $sql;
    }

    protected static function agregar_ventaSinUsuario_modelo($datos)
    {
        
        $sql = mainModel::conectar()->prepare("INSERT INTO tblventasinusuario(ventaVSU, nombresVSU, apellidosVSU, emailVSU, telefonoVSU)
            VALUES(:ventaVSU, :nombresVSU, :apellidosVSU, :emailVSU, :telefonoVSU)");
        $sql->bindParam(":ventaVSU", $datos['ventaVSU']);
        $sql->bindParam(":nombresVSU", $datos['nombresVSU']);
        $sql->bindParam(":apellidosVSU", $datos['apellidosVSU']);
        $sql->bindParam(":emailVSU", $datos['emailVSU']);
        $sql->bindParam(":telefonoVSU", $datos['telefonoVSU']);
        $sql->execute();

        return $sql;
    }

    protected static function agregar_detventa_modelo($datos)
    {
        
        $sql = mainModel::conectar()->prepare("INSERT INTO tbldetventas(venta, detalleProducto, cantidadVendida, rebajaUnitaria)
            VALUES(:venta, :detalleProducto, :cantidadVendida, :rebajaUnitaria)");
        $sql->bindParam(":venta", $datos['venta']);
        $sql->bindParam(":detalleProducto", $datos['detalleProducto']);
        $sql->bindParam(":cantidadVendida", $datos['cantidadVendida']);
        $sql->bindParam(":rebajaUnitaria", $datos['rebajaUnitaria']);
        $sql->execute();

        return $sql;
    }
    ////////////////////////////

    protected static function actualizar_cantidad_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("UPDATE tblcarrito SET cantidad=:cantidad WHERE id_carrito=:id_carrito");
        $sql->bindParam(":id_carrito", $datos['id_carrito']);
        $sql->bindParam(":cantidad", $datos['cantidad']);
        $sql->execute();
        return $sql;
    }

    protected static function minun_cart_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("UPDATE tblcarrito SET cantidad=(cantidad - 1) WHERE id_carrito=:id_carrito");
        $sql->bindParam(":id_carrito", $datos['id_carrito']);
        $sql->execute();
        return $sql;
    }

    protected static function plus_cart_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("UPDATE tblcarrito SET cantidad=(cantidad + 1) WHERE id_carrito=:id_carrito");
        $sql->bindParam(":id_carrito", $datos['id_carrito']);
        $sql->execute();
        return $sql;
    }

    protected static function delete_cart_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("DELETE FROM tblcarrito WHERE id_carrito=:id_carrito");
        $sql->bindParam(":id_carrito", $datos['id_carrito']);
        $sql->execute();
        return $sql;
    }
}/*Aquí termina la clase */