<?php


require_once './models/Pedido.php';
require_once './db/AccesoDatos.php';

 class Empleado 
 {

    public $id;
    public $id_usuario;
    public $empleado_area_id;
    public $nombre; 
    public $fechaDeInicio; 
    public $fechaDeFin;

    public function __construct(){}

    public static function crearEmpleado($id_usuario, $empleado_area_id, $nombre, $fechaDeInicio)
    {
        $nuevoEmpleado = new empleado();
        $nuevoEmpleado->setIdUsuario($id_usuario); 
        $nuevoEmpleado->setEmpleadoAreaId($empleado_area_id); 
        $nuevoEmpleado->setNombre($nombre); 
        $nuevoEmpleado->setFechaDeInicio($fechaDeInicio);

        return $nuevoEmpleado; 
    }

    //-------------------------------------------------------------------------

    public function getId()
    {
        return $this->id;
    }

    public function getempleadoAreaID()
    {
        return $this->empleado_area_id;
    }

    public function getIdUsuario(
        
    ){
        return $this->id_usuario;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getFechaDeInicio() 
    {
        return $this->fechaDeInicio;
    }

    public function getFechaDeFin()  
    {
        return $this->fechaDeFin;
    }

    //-------------------------------------------------------------------------

    public function setId($id){
        $this->id = $id;
    }

    public function setEmpleadoAreaId($empleado_area_id)
    {
        $this->empleado_area_id = $empleado_area_id;
    }

    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setFechaDeInicio($fechaDeInicio)
    {
        $this->fechaDeInicio = $fechaDeInicio;
    }

    public function setFechaDeFin($fechaDeFin)
    { 
        $this->fechaDeFin = $fechaDeFin;
    }

    //-------------------------------------------------------------------------

    public function printUnaEntidadComoMesa() 
    {
        echo "<table border='2'>";
        echo '<caption>empleado Data</caption>';
        echo "<th>[nombre]</th><th>[id_usuario]</th><th>[area]</th><th>[fechaDeInicio]</th>";
        echo "<tr align='center'>";
        echo "<td>[".$this->getNombre()."]</td>";
        echo "<td>[".$this->getIdUsuario()."]</td>";
        echo "<td>[".$this->getempleadoAreaID()."]</td>";
        echo "<td>[".$this->getFechaDeInicio()."]</td>";
        echo "</tr>";
        echo "</table>" ;
    }


    public static function printEntidadesComoMesa($ListaEntidades) 
    {
        echo "<table border='2'>";
        echo '<caption>empleados Lista</caption>'; 
        echo "<th>[ID]</th><th>[nombre]</th><th>[id_usuario]</th><th>[area]</th><th>[fechaDeInicio]</th>";
        foreach($ListaEntidades as $entidad)
        {
            echo "<tr align='center'>";
            echo "<td>[".$entidad->getId()."]</td>";
            echo "<td>[".$entidad->getNombre()."]</td>";
            echo "<td>[".$entidad->getIdUsuario()."]</td>";
            echo "<td>[".$entidad->getempleadoAreaID()."]</td>";
            echo "<td>[".$entidad->getFechaDeInicio()."]</td>";
            echo "</tr>";
        }
        echo "</table><br>" ;
    }

    public static function insertempleado($empleado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepareQuery("INSERT INTO `empleados` (`id_usuario`, `empleado_area_id`, `nombre`, `fechaDeInicio`)
        VALUES (:id_usuario, :empleado_area_id, :nombre, :fechaDeInicio);");
        $query->bindValue(':id_usuario', $empleado->getIdUsuario());
        $query->bindValue(':empleado_area_id', $empleado->getempleadoAreaID());
        $query->bindValue(':nombre', $empleado->getNombre());
        $query->bindValue(':fechaDeInicio', $empleado->getFechaDeInicio());
        try {
            $query->execute();
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
        
        return $objAccesoDatos->getLastInsertedID();
    }

    public static function getEmpleadPorId($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepareQuery("SELECT * FROM `empleados` WHERE id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        
        return $query->fetchObject('empleado');
    }


    public static function getTodosEmpleados() 
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepareQuery("SELECT * FROM `empleados`");
        $query->execute();
        $empleados = $query->fetchAll(PDO::FETCH_CLASS, 'empleado');

        return $empleados;
    }

    public static function deleteEmpleado($id) 
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepareQuery("DELETE FROM `empleados` WHERE id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();

        return $query->rowCount();
    }


    public static function updateempleado($empleado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepareQuery("UPDATE `empleados` SET id_usuario = :id_usuario, empleado_area_id = :empleado_area_id, nombre = :nombre WHERE id = :id");
        $query->bindValue(':id_usuario', $empleado->getIdUsuario());
        $query->bindValue(':empleado_area_id', $empleado->getempleadoAreaID());
        $query->bindValue(':nombre', $empleado->getNombre());
        $query->bindValue(':id', $empleado->getId());
        $query->execute();

        return $query->rowCount();
    }

 }
?>