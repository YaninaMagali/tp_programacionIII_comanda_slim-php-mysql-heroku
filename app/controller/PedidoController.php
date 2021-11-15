<?php
require_once './model/Pedido.php';
require_once './controller/EmpleadoController.php';

require_once './middleware/JsonBodyParserMiddleware.php';

class PedidoController{

    public function CrearPedido($request, $response, $args){

        $parametros = $request->getParsedBody();
        $pedido = new Pedido();
        $pedido->estado = $parametros['estado'];
        $pedido->nombreCliente = $parametros['nombreCliente'];
        $pedido->idMesa = $parametros['idMesa'];
        $pedido->itemsMenu = $parametros['menu'];
        $pedido->imagen = $parametros['archivo'];

        try{
            $idPedido = $pedido->InsertarPedido();
            $pedido->InsertarMenuPedido($idPedido);
            $payload = json_encode(array("Se creo el pedido: " => $pedido));
        }
        catch(Exception $e){
            $payload = json_encode(array("Error" => $e->message));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BuscarPedidoPorCodigo($request, $response, $args){

        try{
            $sector = EmpleadoController::ObtenerSectorEmpleadoDeJWT($request, $response, $args);
            $codigo = $args['codigo'];
            $pedido = Pedido::ConsultarPedido($codigo);
            if($sector != null){
                $payload = json_encode(array("Pedido" => $pedido));
            }
            else{
                $payload = json_encode(array("Tiempo de preparacion de pedido $codigo :" => $pedido->tiempo_preparacion));
            }
            
        }
        catch(Exception $e){
            $payload = json_encode(array("Error" => $e->message));
        }
        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function EditarEstadoPedido($request, $response, $args){

        try{
            $sector = EmpleadoController::ObtenerSectorEmpleadoDeJWT($request, $response, $args);
            $parametros = $request->getParsedBody();
            $codigo = $parametros['codigo'];
            $estado = $parametros['estado'];
            $tiempo = $parametros['tiempo_preparacion'];
            if(Pedido::ActualizarEstadoPedido($codigo, $estado, $sector, $tiempo)){
                $payload = json_encode(array("Message" => "Se actualizo el pedido " . $codigo));
                $response->getBody()->write($payload);
            }
            else{
                $payload = json_encode(array("Message error: " => "No se actualizo el pedido " . $codigo));
                $response->getBody()->write($payload);
            }
        }
        catch(Exception $e){
            $payload = json_encode(array("Error" => $e->message));
            $response->getBody()->write($payload);
        }
        
        
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ListarPedidos($request, $response, $args){
        
        $sector = EmpleadoController::ObtenerSectorEmpleadoDeJWT($request, $response, $args);
        
        if($sector !=  false){
            switch($sector){
                case 'SOCIO':
                    $pedidos = Pedido::ConsultarPedidos();
                    $payload = json_encode(array("Pedidos" => $pedidos)); 
                    $response->getBody()->write($payload);
                    break;
                case 'MOZO':
                    $pedidos = Pedido::ConsultarPedidosPorEstado('listo_para_servir');
                    $payload = json_encode(array("Pedidos" => $pedidos)); 
                    $response->getBody()->write($payload);
                    break;
                default:
                    $pedidos = Pedido::ConsultarPedidosPorSectorYEstado($sector, 'pendiente');
                    $payload = json_encode(array("Pedidos" => $pedidos)); 
                    $response->getBody()->write($payload);
                    break;
            }
        }  
        
        return $response->withHeader('Content-Type', 'application/json');
    }



}

?>