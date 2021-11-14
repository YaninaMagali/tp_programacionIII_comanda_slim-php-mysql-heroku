<?php
require_once './model/Empleado.php';

class ValidadorSectoresController{
    
    public static function ValidarSector($usuario, $sector){

        try{
            $empleado = Empleado::ConsultarEmpleado($usuario);
            if($empleado->sector && $sector && $empleado->sector == $sector){
                return true;
            } 
        }
        catch(Exception $e){
            $payload = json_encode(array("Message" => "Error en ValidadorSectoresController"));
        }
    }
}



?>