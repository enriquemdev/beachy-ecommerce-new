<?php
require_once "mainModel.php";
class carritoModelo extends mainModel
{

    protected static function agregar_prodCarrito_modelo($datos)
    {

        if (array_key_exists("cliente_id", $datos)) {
            if (array_key_exists("id_carrito", $datos)) {
                $sql = mainModel::conectar()->prepare("UPDATE tblcarrito SET cantidad=:cantidad WHERE id_carrito=:id_carrito");
                $sql->bindParam(":id_carrito", $datos['id_carrito']);
                $sql->bindParam(":cantidad", $datos['cantidad']);
            } else {
                $sql = mainModel::conectar()->prepare("INSERT INTO tblcarrito(cliente_id, det_producto_id, cantidad)
                    VALUES(:cliente_id, :det_producto_id, :cantidad)");
                $sql->bindParam(":cliente_id", $datos['cliente_id']);
                $sql->bindParam(":det_producto_id", $datos['det_producto_id']);
                $sql->bindParam(":cantidad", $datos['cantidad']);
            }
        } else {
            if (array_key_exists("id_carrito", $datos)) {
                $sql = mainModel::conectar()->prepare("UPDATE tblcarrito SET cantidad=:cantidad WHERE id_carrito=:id_carrito");
                $sql->bindParam(":id_carrito", $datos['id_carrito']);
                $sql->bindParam(":cantidad", $datos['cantidad']);
            } else {
                $sql = mainModel::conectar()->prepare("INSERT INTO tblcarrito(client_mac, det_producto_id, cantidad)
                    VALUES(:client_mac, :det_producto_id, :cantidad)");
                $sql->bindParam(":client_mac", $datos['client_mac']);
                $sql->bindParam(":det_producto_id", $datos['det_producto_id']);
                $sql->bindParam(":cantidad", $datos['cantidad']);
            }
        }

        $sql->execute();
        return $sql;
    }

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