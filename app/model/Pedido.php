<?php

class Pedido
{
    public $id;
    public $codigo;
    public $estado;
    public $imagen;
    public $nombreCliente;
    public $idMesa;
    public $totalAPagar;
    public $itemsMenu;

    public function InsertarPedido(){

        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("INSERT INTO pedidos (codigo, estado, imagen, nombreCliente, idMesa) VALUES (:codigo, :estado, :imagen, :nombreCliente, :idMesa)");
            $codigo = GenerarCodigoAlfanumerico(5);   
            $query->bindValue(':codigo', $codigo, PDO::PARAM_STR);
            $query->bindValue(':estado', $this->estado, PDO::PARAM_STR);
            $query->bindValue(':imagen', $this->imagen, PDO::PARAM_STR);
            $query->bindValue(':nombreCliente', $this->nombreCliente, PDO::PARAM_STR);
            $query->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
            $query->execute();

            return $dao->obtenerUltimoId();
        }
        catch(Exception $e){
            throw $e;
        }

    }

    public function InsertarMenuPedido($id_pedido){

        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("INSERT INTO menu_pedido (id_pedido, id_item_menu, cantidad, estado) VALUES (:id_pedido, :id_item_menu, :cantidad, :estado)");
            foreach($this->itemsMenu as $item){
                $query->bindValue(':id_pedido', $id_pedido, PDO::PARAM_INT);
                $query->bindValue(':id_item_menu', $item['id_item_menu'], PDO::PARAM_INT);
                $query->bindValue(':cantidad', $item['cantidad'], PDO::PARAM_INT);
                $query->bindValue(':estado', "NUEVO", PDO::PARAM_STR);
                $query->execute();
            }
        }
        catch(Exception $e){
            throw $e;
        }
    }

    public static function ConsultarPedidos(){

        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("SELECT pedidos.id, pedidos.codigo, menu_pedido.estado, pedidos.imagen, pedidos.nombreCliente, pedidos.idMesa, pedidos.totalAPagar, menu_pedido.cantidad, menu.descripcion, menu_pedido.tiempo_preparacion 
            FROM `menu_pedido` 
            INNER JOIN menu ON menu.id = menu_pedido.id_item_menu 
            INNER JOIN pedidos ON pedidos.id = menu_pedido.id_pedido;");
            $query->execute();
    
            return $query->fetchAll(PDO::FETCH_CLASS, 'Pedido');
        }
        catch(Exception $e){
            throw $e;
        }
    }

    public static function ConsultarPedido($codigo){

        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("SELECT pedidos.id, pedidos.codigo, menu_pedido.estado, pedidos.imagen, pedidos.nombreCliente, pedidos.idMesa, pedidos.totalAPagar, menu_pedido.cantidad, menu.descripcion, menu_pedido.tiempo_preparacion 
            FROM `menu_pedido` 
            INNER JOIN menu ON menu.id = menu_pedido.id_item_menu 
            INNER JOIN pedidos ON pedidos.id = menu_pedido.id_pedido 
            WHERE codigo = :codigo;");
            $query->bindValue(':codigo', $codigo, PDO::PARAM_STR);
            $query->execute();
            return $query->fetchObject('Pedido');
        }
        catch(Exception $e){
            throw $e;
        }
    }

    public static function ActualizarEstadoPedido($codigo, $estado, $sector, $tiempo){

        $pedido = Pedido::ConsultarPedido($codigo);

        if($pedido != false){
            try{
                $dao = new DAO();
                $query = $dao->prepararConsulta("UPDATE menu_pedido 
                INNER JOIN menu ON menu.id = menu_pedido.id_item_menu 
                SET estado = :estado, menu_pedido.tiempo_preparacion = :tiempo_preparacion 
                WHERE menu_pedido.id_pedido = :id 
                AND menu.sector = :sector;");
                $query->bindValue(':id', $pedido->id, PDO::PARAM_INT);
                $query->bindValue(':estado', $estado, PDO::PARAM_STR);
                $query->bindValue(':sector', $sector, PDO::PARAM_STR);
                $query->bindValue(':tiempo_preparacion', $tiempo, PDO::PARAM_STR); //, menu_pedido.tiempo_preparacion = :tiempo_preparacion
                if($query->execute())
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            catch(Exception $e){
                echo("Error en ActualizarEstadoPedido");
                throw $e;
            }
        }
        else{
            return false;
        }

    }
    
    public static function ConsultarPedidosPorSectorYEstado($sector, $estado){

        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("SELECT pedidos.id, pedidos.codigo, menu_pedido.estado, pedidos.imagen, pedidos.nombreCliente, pedidos.idMesa, pedidos.totalAPagar, menu_pedido.cantidad, menu.descripcion, menu_pedido.tiempo_preparacion 
            FROM `menu_pedido` 
            INNER JOIN menu ON menu.id = menu_pedido.id_item_menu 
            INNER JOIN pedidos ON pedidos.id = menu_pedido.id_pedido
            WHERE menu.sector = :sector
            AND menu_pedido.estado = :estado;");
            $query->bindValue(':sector', $sector, PDO::PARAM_STR);
            $query->bindValue(':estado', $estado, PDO::PARAM_STR);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_CLASS, 'Pedido');  
        }
        catch(Exception $e){
            throw $e;
        }
    }

    public static function ConsultarPedidosPorEstado($estado){

        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("SELECT pedidos.id, pedidos.codigo, menu_pedido.estado, pedidos.imagen, pedidos.nombreCliente, pedidos.idMesa, pedidos.totalAPagar, menu_pedido.cantidad, menu.descripcion, menu_pedido.tiempo_preparacion 
            FROM `menu_pedido` 
            INNER JOIN menu ON menu.id = menu_pedido.id_item_menu 
            INNER JOIN pedidos ON pedidos.id = menu_pedido.id_pedido
            WHERE menu_pedido.estado = :estado;");
            $query->bindValue(':estado', $estado, PDO::PARAM_STR);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_CLASS, 'Pedido');  
        }
        catch(Exception $e){
            throw $e;
        }
    }
}
?>