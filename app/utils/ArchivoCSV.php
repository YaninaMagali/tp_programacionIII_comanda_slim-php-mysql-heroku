<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

class ArchivoCSV
{

    /*
    Lee archivo CSV e imprime a medida que va leyendo
    Recibe la ruta del archivo a ser leido
    NO devuelve nada
     */
    public static function Leer($archivo){
        try
        {
            $file = fopen($archivo, "r");
            while(!feof($file))
            {
                print_r(fgetcsv($file));
                echo "<br>";
            }
        }
        catch(Exception $e) 
        {
            echo 'Message: No se pudo abrir el arhivo para leer <br>';
        }
        finally
        {
            if($file)
            {
                fclose($file);
                // echo "desp del close";
                // echo $file;
            }            
        }
    } 

    /*
    Lee un archivo CSV y devuelve un array con la info leida
    Recibe el archivo CSV que va a leer y donde va a guardar la inforamacion leida
    Devuelve un array con la informacion obtenida del CSV
    */ 
    public static function Leer2($archivo){
        $data = [];
        try
        {
            $file = fopen($archivo, "r");
            while(!feof($file))
            {
                $dato = fgetcsv($file);
                array_push($data,$dato);
            }
        }
        catch(Exception $e) 
        {
            echo 'Message: No se pudo abrir el arhivo para leer <br>';
        }
        finally
        {
            if($file)
            {
                fclose($file);
            }            
        }
        return $data;
    } 

    /*
    Escribe en CSV informacion recibida por parametro
    Recibe la informacion a escribir en el CSV y el path de archivo que se va a escribir
    No devuelve nada
    */
    static function Escribir($data, $archivo)
    {
        $file = null;
        $datos = [(array)$data];
        try
        {
            $file = fopen($archivo, "a");
            foreach ( $datos as $dato ) 
            {

                fputcsv($file, $dato);
            }
        }
        catch(Exception $e) 
        {
            echo 'Message: No se pudo abriri file';
        }
        finally
        {
            echo "en finnaly";
            echo $file;
            if($file)
            {
                fclose($file);
            }
            
        }
    }

}

?>