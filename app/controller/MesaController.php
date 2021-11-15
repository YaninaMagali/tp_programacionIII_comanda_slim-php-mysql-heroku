<?php
require_once './model/Mesa.php';

class MesaController{

    public function CrearMesa($request, $response, $args){
    
        $parametros = $request->getParsedBody();
        $mesa = new Mesa();
        $mesa->estado = $parametros['estado'];
        try{
            $mesa->CrearMesa();
            $payload = json_encode(array("mensaje" => "Mesa creada con exito"));
        }
        catch(Exception $e){
            $payload = json_encode(array("Error" => $e->message));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ListarMesas($request, $response, $args){
        try{
            $itemsMesas = Mesa::ListarMesas();
            $payload = json_encode(array("Mesas" => $itemsMesas));
        }
        catch(Exception $e){
            $payload = json_encode(array("Error" => $e->message));
        }
        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function CambiarEstadoMesa($request, $response, $args){

        $parametros = $request->getParsedBody();
        $codigo = $parametros['codigo'];
        $estado = $parametros['estado'];
        try{
            if(Mesa::ActualizarEstadoMesa($estado, $codigo)){
                $payload = json_encode(array("Message" => "Se actualizo la mesa $codigo a estado $estado"));
            }
        }
        catch(Exception $e){
            $payload = json_encode(array("Error" => $e->message));
        }
        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function CerrarMesa($args){

        if($codigo = $args['codigo']){
            try{
                if(Mesa::ActualizarEstadoMesa('cerrada', $codigo)){
                    $payload = json_encode(array("Message" => "Se cerro la mesa $codigo"));
                }
            }
            catch(Exception $e){
                $payload = json_encode(array("Error" => $e->message));
            }

        }
    }


}
?>