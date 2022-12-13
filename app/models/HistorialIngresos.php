<?php


class HistorialIngresos
{
    public $id;
    public $idDeUsuario; 
    public $usuario; 
    public $fechaDeIngreso; 

    public function __construct() {}

    public static function createHistorialIngresos($idDeUsuario, $usuario, $fechaDeIngreso)
    {
        $historialIngresos = new HistorialIngresos();
        $historialIngresos->setIdDeUsuario($idDeUsuario); 
        $historialIngresos->setUsuario($usuario); 
        $historialIngresos->setFechaDeIngreso($fechaDeIngreso); 
        
        return $historialIngresos;
    }

    //get----------------------------------------------------

    public function getId()
    {
        return $this->id;
    }

    public function getIdDeUsuario() 
    {
        return $this->idDeUsuario;
    }

    public function getUsuario() 
    {
        return $this->usuario;
    }

    public function getFechaDeIngreso() 
    {
        return $this->fechaDeIngreso;
    }

    //set----------------------------------------------------

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setIdDeUsuario($idDeUsuario)
    {
        $this->idDeUsuario = $idDeUsuario;
    }

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    public function setFechaDeIngreso($fechaDeIngreso)
    {
        $this->fechaDeIngreso = $fechaDeIngreso;
    }

    //funciones----------------------------------------------------

    public static function printEntidadesComoTable($ListaEntidades)
    {
        echo "<table border='2'>";
        echo '<caption>Lista de ingresos</caption>'; 
        echo "<th>[id_ingreso]</th><th>[idDeUsuario]</th><th>[usuario]</th><th>[fecha]</th>";  
        foreach($ListaEntidades as $entidad)
        {
            echo "<tr align='center'>";
            echo "<td>[".$entidad->getId()."]</td>";
            echo "<td>[".$entidad->getIdDeUsuario()."]</td>";
            echo "<td>[".$entidad->getUsuario()."]</td>";
            echo "<td>[".$entidad->getFechaDeIngreso()."]</td>";
            echo "</tr>";
        }
        echo "</table><br>" ;
    }


    public static function ReadCsv($filename="./Reports_Files/HistorialIngresos.csv")
    {
        $file = fopen($filename, "r");
        $array = array();
        try 
        {
            if (!is_null($file) && self::borrarTable() > 0)
            {
                echo "<h2>Table borrada correctamente</h2>
                <h2>Se ingresara los datos del archivo.</h2>";
            }
            while (!feof($file)) 
            {
                $line = fgets($file);
                
                if (!empty($line)) 
                {
                    $line = str_replace(PHP_EOL, "", $line);
                    $loginsArray = explode(",", $line);
                    $hLogin = HistorialIngresos::createHistorialIngresos($loginsArray[0], $loginsArray[1], $loginsArray[2]);
                    array_push($array, $hLogin);
                    HistorialIngresos::insertHistorialIngresos($hLogin);
                }
            }
        } 
        catch (\Throwable $th) 
        {
            echo "Error while reading the file";
        }
        finally
        {
            fclose($file);
            return $array;
        }
    }

    public static function WriteCsv($ListaEntidades, $filename = './Reports_Files/HistorialIngresos.csv'):bool{
        $success = false;
        $directory = dirname($filename, 1);
        
        try {
            if(!file_exists($directory)){
                mkdir($directory, 0777, true);
            }
            $file = fopen($filename, "w");
            if ($file) {
                foreach ($ListaEntidades as $entidad) {
                    $line = $entidad->getIdDeUsuario() . "," . $entidad->getUsuario() . "," . $entidad->getFechaDeIngreso() . PHP_EOL;
                    fwrite($file, $line);
                    $success = true;
                }
            }
        } catch (\Throwable $th) {
            echo "Error saving the file<br>";
        }finally{
            fclose($file);
        }

        return $success;
    }

    //--- PDO Methods ---//


    public static function insertHistorialIngresos($HistorialIngresos)
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("INSERT INTO `HistorialIngresos` (idDeUsuario, nombreUsuario, fechaDeIngreso) 
        VALUES (:idDeUsuario, :nombreUsuario, :fechaDeIngreso);");
        $query->bindValue(':idDeUsuario', $HistorialIngresos->getIdDeUsuario(), PDO::PARAM_INT);
        $query->bindValue(':nombreUsuario', $HistorialIngresos->getUsuario(), PDO::PARAM_STR);
        $query->bindValue(':fechaDeIngreso', $HistorialIngresos->getFechaDeIngreso(), PDO::PARAM_STR);
        $query->execute();

        return $objDataAccess->obtenerUltimoId();
    }

    public static function getHistorialIngresosById($id)
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("SELECT * FROM `HistorialIngresos` WHERE id = :id;");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, 'HistorialIngresos');
    }

    public static function getAll()
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("SELECT * FROM `HistorialIngresos`;");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, 'HistorialIngresos');
    }

    public static function borrarIngresoPorId($id) 
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("DELETE FROM `HistorialIngresos` WHERE id = :id;");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();

        return $query->rowCount() > 0;
    }

    public static function borrarTable() 
    {
        $objDataAccess = DataAccess::getInstance();
        $query = $objDataAccess->prepareQuery("DELETE FROM `HistorialIngresos` WHERE 1=1;");
        $query->execute();

        return $query->rowCount() > 0;
    }
}
?>