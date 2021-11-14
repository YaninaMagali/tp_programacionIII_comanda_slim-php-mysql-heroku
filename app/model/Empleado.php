<?php
/*
Aca hacer los metodos que hacen las queries a la DB*/ 

class Empleado
{
    public $id;
    public $usuario;
    public $clave;
    public $estado;
    public $sector;

    public function InsertarEmpleado(){

        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("INSERT INTO empleados (usuario, clave, estado, sector) VALUES (:usuario, :clave, :estado, :sector)");
            $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);     
            $query->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
            $query->bindValue(':clave', $claveHash);
            $query->bindValue(':estado', $this->estado, PDO::PARAM_STR);
            $query->bindValue(':sector', $this->sector, PDO::PARAM_STR);
            $query->execute();
    
            return $dao->obtenerUltimoId();
        }
        catch(Exception $e){
            throw $e;
        }

    }

    public static function ConsultarEmpleados(){

        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("SELECT * FROM empleados;");
            $query->execute();
    
            return $query->fetchAll(PDO::FETCH_CLASS, 'Empleado');
        }
        catch(Exception $e){
            throw $e;
        }
    }

    public static function ConsultarEmpleado($usuario){

        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("SELECT * FROM empleados WHERE usuario = :usuario;");
            $query->bindValue(':usuario', $usuario, PDO::PARAM_STR);
            $query->execute();
    
            return $query->fetchObject('Empleado');
        }
        catch(Exception $e){
            throw $e;
        }
    }

    public static function ValidarCredenciales($usuario, $clave){

        $esValida = false;
        $empleado = Empleado::ConsultarEmpleado($usuario);

        if($empleado && password_verify($clave, $empleado->clave))
        {
            $esValida = $empleado->id;  
        }
        
        return $esValida;
    }

    // public static function ConsultarSectorUsuario($usuario){

    //     $empleado = Empleado::ConsultarEmpleado($usuario);
    //     if($empleado){
    //         return $empleado->sector;
    //     }
    //     else{
    //         return false;
    //     }
        
    // }

    public static function InsertarIngresoAlSistema($id){
        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("INSERT INTO empleado_ingresos (id_empleado, fecha_ingreso) VALUES (:id_empleado, :fecha_ingreso)");   
            $fecha = date("Y-m-d H:i:s");
            $query->bindValue(':id_empleado', $id, PDO::PARAM_STR);
            $query->bindValue(':fecha_ingreso', $fecha, PDO::PARAM_STR);
            $query->execute();
    
            return $dao->obtenerUltimoId();
        }
        catch(Exception $e){
            throw $e;
        }   
    }

    public static function CrearEmpleado($usuario, $clave, $estado, $sector){
       
        $empleado = false;
        if($usuario != null && $clave != null && $estado != null && $sector != null){
            $empleado = new Empleado();
            $empleado->usuario = $usuario;
            $empleado->clave = $clave;
            $empleado->estado = $estado;
            $empleado->sector = $sector;
        }

        return $empleado;
    }




}
?>