<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

class Archivador
{
    public static function GuardarArchivo($archivo, $dir_subida, $newName){
        echo $archivo . "<br>";
        $pudoGuardar = false;

        $fichero_subido = $dir_subida . basename($_FILES[$archivo][$newName]);

        if (!file_exists($dir_subida)) 
        {
            mkdir($dir_subida, 0777, true);
            
        }

        if(is_file($fichero_subido))
        {
            echo "existe el file \n";
            die();
        }

        if (move_uploaded_file($_FILES[$archivo]['tmp_name'], "$fichero_subido/$newName")) 
        {
            $pudoGuardar = true;
            echo "Se subió con éxito.\n";
        } 
        else  
        {
            echo "Error!\n";
        }
        return $pudoGuardar;
    }



}
?>
