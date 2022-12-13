<?php

require_once './db/AccesoDatos.php';
require_once './models/Empleado.php';

class Usuario
{ 
    
    public $id;
    public $nombreUsuario; 
    public $contrasena;
    public $esAdmin; 
    public $tipoDeUsuario; 
    public $estado; 
    public $fechaDeInicio;
    public $fechaDeFin;


    public function __construct(){}


    public static function createUsuario($nombreUsuario, $contrasena, $esAdmin, $tipoDeUsuario, $estado, $fechaDeInicio){
        $usuario = new usuario();
        $usuario->setNombreUsuario($nombreUsuario); 
        $usuario->setContrasena($contrasena); 
        $usuario->setEsAdmin($esAdmin); 
        $usuario->setUsuarioTipo($tipoDeUsuario);  
        $usuario->setEstado($estado); 
        $usuario->setFechaDeInicio($fechaDeInicio); 
        return $usuario;
    }

    //----------------------------------------------------------------------------------------------------------------------------------------------------------


    public function getId()
    {
        return $this->id;
    }

    public function getNombreUsuario() 
    {
        return $this->nombreUsuario;
    }

    public function getContrasena() 
    {
        return $this->contrasena;
    }

    public function getEmpleadoId() 
    {
        return $this->employee_id;
    }

    public function getEsAdmin()
    {
        return $this->esAdmin;
    }

    public function getUsuarioTipo() 
    {
        return $this->tipoDeUsuario;
    }

    public function getEstado() 
    {
        return $this->estado;
    }

    public function getFechaDeInicio()
    {
        return $this->fechaDeInicio;
    }

    public function getfechaDeFin()
    {
        return $this->fechaDeFin;
    }

    //--- Setters ---//

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setNombreUsuario($nombreUsuario)
    {
        $this->nombreUsuario = $nombreUsuario;
    }

    public function setContrasena($contrasena)
    {
        $this->contrasena = $contrasena;
    }

    public function setEmployeeId($employeeId)
    {
        $this->employee_id = $employeeId;
    }

    public function setEsAdmin($esAdmin)
    {
        $this->esAdmin = $this->validateBool($esAdmin);
    }

    public function setUsuarioTipo($tipoDeUsuario)
    {
        $this->tipoDeUsuario = $tipoDeUsuario;
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;
    }
    
    public function setFechaDeInicio($fechaDeInicio)
    {
        $this->fechaDeInicio = $fechaDeInicio;
    }

    public function setFechaDeFin($fechaDeFin) 
    {
        $this->fechaDeFin = $fechaDeFin;
    }

    //--- Other Methods ---//

    public function printUnaEntidadComoMesa() 
    {
        echo "<table border='2'>";
        echo '<caption>usuarios Data</caption>'; 
        echo "<th>[nombreUsuario]</th><th>[contrasena]</th><th>[es_admin]</th><th>[tipoDeUsuario]</th><th>[estado]</th><th>[creado_en]</th>";
        echo "<tr align='center'>";
        echo "<td>[".$this->getNombreUsuario()."]</td>";
        echo "<td>[".$this->getContrasena()."]</td>";
        echo "<td>[".$this->getEsAdmin()."]</td>";
        echo "<td>[".$this->getUsuarioTipo()."]</td>";
        echo "<td>[".$this->getEstado()."]</td>";
        echo "<td>[".$this->getFechaDeInicio()."]</td>";
        echo "</tr>";
        echo "</table>" ;
    }

    public static function printEntidadesComoMesa($ListaEntidades)
    {
        echo "<table border='2'>";
        echo '<caption>usuarios List</caption>';
        echo "<th>[ID]</th><th>[nombreUsuario]</th><th>[contrasena]</th><th>[es_admin]</th><th>[tipoDeUsuario]</th><th>[estado]</th><th>[creado_en]</th>";
        foreach($ListaEntidades as $entidad)
        {
            echo "<tr align='center'>";
            echo "<td>[".$entidad->getId()."]</td>";
            echo "<td>[".$entidad->getNombreUsuario()."]</td>";
            echo "<td>[".$entidad->getContrasena()."]</td>";
            echo "<td>[".$entidad->getEsAdmin()."]</td>";
            echo "<td>[".$entidad->getUsuarioTipo()."]</td>";
            echo "<td>[".$entidad->getEstado()."]</td>";
            echo "<td>[".$entidad->getFechaDeInicio()."]</td>";
            echo "</tr>";
        }
        echo "</table><br>" ;
    }

    public static function PrintsAllEntitiesFromTheDB()
    {
        $listEntities = array();
        $listEntities = usuario::getTodosUsuarios();
        usuario::printEntidadesComoMesa($listEntities);
    }

    public function esAdmin()
    {
        return $this->getEsAdmin();
    }

    private function validateBool($bool)
    {
        return strtolower($bool) == "true";
    }

    //--- Create usuario Table ---//

    //--- PDO Methods ---//
    public static function insertUsuario($usuario) 
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepareQuery("INSERT INTO usuarios (nombreUsuario, contrasena, esAdmin, tipoDeUsuario, estado, fechaDeInicio) 
        VALUES (:nombreUsuario, :contrasena, :esAdmin, :tipoDeUsuario, :estado, :fechaDeInicio)");
        $contrasenaHash = contrasena_hash($usuario->getContrasena(), contrasena_DEFAULT);
        $query->bindValue(':nombreUsuario', $usuario->getNombreUsuario(), PDO::PARAM_STR);
        $query->bindValue(':contrasena', $contrasenaHash);
        $query->bindValue(':esAdmin', $usuario->getEsAdmin(), PDO::PARAM_INT);
        $query->bindValue(':tipoDeUsuario', $usuario->getUsuarioTipo(), PDO::PARAM_STR);
        $query->bindValue(':estado', $usuario->getEstado(), PDO::PARAM_STR);
        $query->bindValue(':fechaDeInicio', $usuario->getFechaDeInicio(), PDO::PARAM_STR);
        $query->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }


    public static function insertHistorialIngresos($usuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepareQuery("INSERT INTO HistorialIngresos (idDeUsuario, nombreUsuario, fechaDeIngreso) 
        VALUES (:idDeUsuario, :nombreUsuario, :fechaDeIngreso)");
        $query->bindValue(':idDeUsuario', $usuario->getId(), PDO::PARAM_INT);
        $query->bindValue(':nombreUsuario', $usuario->getNombreUsuario(), PDO::PARAM_STR);
        $query->bindValue(':fechaDeIngreso', date("Y-m-d H:i:s"), PDO::PARAM_STR);
        $query->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }


    public static function getTodosUsuarios()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepareQuery("SELECT * FROM usuarios");
        $query->execute();
        
        return $query->fetchAll(PDO::FETCH_CLASS, 'usuario');
    }

    public static function getUsuario($employee)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepareQuery("SELECT * FROM usuarios AS u
        JOIN empleados AS e 
        ON :id = u.id");
        $query->bindValue(':id', $employee->getUsuarioId(), PDO::PARAM_INT);
        $query->execute();

        return $query->fetchObject('usuario');
    }

    
    public static function getUsuarioPorId($id) 
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepareQuery("SELECT * FROM usuarios WHERE id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $usuario = $query->fetchObject('usuario');
        if(is_null($usuario))
        {
            throw new Exception("usuario not found");
        }
        return $usuario;
    }

    public static function getUsuarioBynombreUsuario($nombreUsuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepareQuery("SELECT * FROM usuarios WHERE nombreUsuario = :nombreUsuario");
        $query->bindParam(':nombreUsuario', $nombreUsuario, PDO::PARAM_STR);
        $query->execute();
        $usuario = $query->fetchObject('usuario');
        if(is_null($usuario))
        {
            throw new Exception("usuario not found");
        }
        return $usuario;
    }

    public static function modifyUsuario($usuario) 
    {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepareQuery("UPDATE usuarios SET nombreUsuario = :nombreUsuario, contrasena = :contrasena WHERE id = :id");
        try 
        {
            $query->bindValue(':nombreUsuario', $usuario->getNombreUsuario(), PDO::PARAM_STR);
            $query->bindValue(':contrasena', $usuario->getContrasena(), PDO::PARAM_STR);
            $query->bindValue(':id', $usuario->getId(), PDO::PARAM_INT);
            $query->execute();
        } 
        catch (\Throwable $th) 
        {
            echo $th->getMessage();
        }
        return $query->getRowCount() > 0;
    }

    public static function deleteUsuario($usuario) 
    {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepareQuery("DELETE FROM usuarios WHERE id = :id");
        $query->bindValue(':id', $usuario->getId(), PDO::PARAM_INT);
        $query->execute();

        return $query->getRowCount() > 0;
    }
}
?>