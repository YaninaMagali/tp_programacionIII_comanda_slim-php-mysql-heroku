<?php
require_once './model/Empleado.php';
require_once './utils/Archivador.php';
require_once './utils/ArchivoCSV.php';


class EmpleadoController{

    public function CrearEmpleado($request, $response, $args){
        /*Voy a validar en el MDW
        - que existan todos los datos
        - que el usuario aun no este registrado
        - que el sector sea valido
        */
        $parametros = $request->getParsedBody();
        $empleado = Empleado::CrearEmpleado($parametros['usuario'], $parametros['clave'], $parametros['estado'], $parametros['sector']);

        try{
            $empleado->InsertarEmpleado();
            $payload = json_encode(array("mensaje" => $empleado));
        }
        catch(Exception $e){
            $payload = json_encode(array("Error" => $e->message));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ListarEmpleados($request, $response, $args){
        
        try{
            $empleados = Empleado::ConsultarEmpleados();
            $payload = json_encode(array("empleados" => $empleados));
        }
        catch(Exception $e){
            $payload = json_encode(array("Error" => $e->message));
        }        

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ObtenerEmpleado($request, $response, $args){

        $usuarioEmpleado = $args['usuario'];

        try{
            $empleado = Empleado::ConsultarEmpleado($usuarioEmpleado);
            if($empleado){
                $payload = json_encode($empleado);
            }
            else{
                $payload = json_encode(array("Usuario $usuarioEmpleado no existe"));
            }
        }
        catch(Exception $e){
            $payload = json_encode(array("Error" => $e));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ObtenerSectorEmpleadoDeJWT($request, $response, $args){

        $sector = false;

        if($bearer =  $request->getHeaderLine('Authorization'))
        {
        try{
            $bearer =  $request->getHeaderLine('Authorization');
            $aux = explode(" ", $bearer);
            $token = $aux[1];
            $payload = AutenticadorJWT::ObtenerPayLoad($token);
            $empleado = Empleado::ConsultarEmpleado($payload->data);
            $sector = $empleado->sector;
            return $sector;
        }
        catch(Exception $e){
            $payload = json_encode(array("Error" => $e->message));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }  
        }
        else
        {
            return false;
        }
    
    }

    public static function CrearEmpleadosDesdeCSV($request, $response, $args){

        $nombreArchivo = "empleados" . "_" . GenerarCodigoAlfanumerico(5);
        
        if(Archivador::GuardarArchivo('archivo','./cargas', $nombreArchivo)){
            $empleados = [];
            $empleados = ArchivoCSV::Leer2("./cargas/$nombreArchivo");
            foreach($empleados as $empleado){
                $e = Empleado::CrearEmpleado($empleado[0], $empleado[1], $empleado[2], $empleado[3]);
                $e->InsertarEmpleado();
            }
            $payload = json_encode(array("mensaje" => "Se cargaron empelados desde csv"));
        }
        else{
            $payload = json_encode(array("Error" => "No se pudo cargar empleados desde CSV"));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
            
        }
}
?>