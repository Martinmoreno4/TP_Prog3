<?php


require_once './db/AccesoDatos.php';
require_once './models/Area.php';

 class Comida
 {

        public $comida_id;
        public $comida_area;
        public $comidaPedidoAsociado;
        public $comida_estado; 
        public $comida_descripcion;
        public $comida_precio; 
        public $tiempoDeInicio;
        public $tiempoDeFinal; 
        public $tiempoParaTerminar;

        public function __construct(){} 


        public static function createComida($comida_area, $comidaPedidoAsociado, $comida_estado, $comida_descripcion, $comida_precio, $tiempoDeInicio){
            $comida = new comida();
            $comida->setComidaArea($comida_area); 
            $comida->setComidaPedidoAsociado($comidaPedidoAsociado);  
            $comida->setComidaEstado($comida_estado); 
            $comida->setComidaDescripcion($comida_descripcion); 
            $comida->setComidaPrecio($comida_precio); 
            $comida->setTiempoDeInicio($tiempoDeInicio);
            $comida->setTiempoDeFinal(null);
            $comida->setTiempoParaTerminar(null);
            
            return $comida;
        }


     //----------------------------------------------------------------------------------------------

        public function getcomidaId() 
        {
            return $this->comida_id;
        }

        public function getComidaArea() 
        {
            return $this->comida_area;
        }
 
        public function getComidaPedidoAsociado() 
        {
            return $this->comidaPedidoAsociado;
        }
   
        public function getComidaEstado() 
        {
            return $this->comida_estado;
        }

        public function getComidaDescripcion() 
        {
            return $this->comida_descripcion;
        }

        public function getComidaPrecio() 
        {
            return $this->comida_precio;
        }

        public function getTiempoDeInicio() 
        { 
            return $this->tiempoDeInicio;
        }

        public function getTiempoDeFinal() 
        {
            return $this->tiempoDeFinal;
        }

        public function getTiempoParaTerminar() 
        {
            return $this->tiempoParaTerminar;
        }

    //----------------------------------------------------------------------------------------------


        public function setcomidaId($comida_id)
        {
            $this->comida_id = $comida_id;
        }


        public function setComidaArea($comida_area)
        {
            $this->comida_area = $comida_area;
        }


        public function setComidaPedidoAsociado($comidaPedidoAsociado)
        {
            $this->comidaPedidoAsociado = $comidaPedidoAsociado;
        }


        public function setComidaEstado($comida_estado) 
        {
            $this->comida_estado = $comida_estado;
        }


        public function setComidaDescripcion($comida_descripcion)
        {
            $this->comida_descripcion = $comida_descripcion;
        }


        public function setComidaPrecio($comida_precio)
        {
            $this->comida_precio = $comida_precio;
        }


        public function setTiempoDeInicio($tiempoDeInicio)
        {
            $this->tiempoDeInicio = $tiempoDeInicio;
        }


        public function setTiempoDeFinal($tiempoDeFinal)
        {
            $this->tiempoDeFinal = $tiempoDeFinal;
        }


        public function setTiempoParaTerminar($tiempoParaTerminar)
        {
            $this->tiempoParaTerminar = $tiempoParaTerminar;
        }


        public function calcularTiempoParaTerminar() 
        {
            $newDate = new DateTime($this->getTiempoDeInicio());
            $newDate = $newDate->modify('+'.$this->getTiempoParaTerminar().' minutes');
            $this->setTiempoDeFinal($newDate->format('Y-m-d H:i:s'));
        }

    //----------------------------------------------------------------------------------------------


    public function printUnaEntidadComomesa()
    {
        echo "<table border='2'>";
        echo '<caption>comida Data</caption>'; 
        echo "<th>[AREA_ID]</th><th>[COMIDA_AOSC]</th><th>[estado]</th>
        <th>[descripcion]</th><th>[precio]</th><th>[tiempoDeInicio]</th><th>[tiempoParaTerminar]</th><th>[tiempoDeFinal]</th>";
        echo "<tr align='center'>";
        echo "<td>[".$this->getComidaArea()."]</td>";
        echo "<td>[".$this->getComidaPedidoAsociado()."]</td>";
        echo "<td>[".$this->getComidaEstado()."]</td>";
        echo "<td>[".$this->getComidaDescripcion()."]</td>";
        echo "<td>[$".$this->getComidaPrecio()."]</td>";
        echo "<td>[".$this->getTiempoDeInicio()."]</td>";
        echo "<td>[".$this->getTiempoParaTerminar()."]</td>";
        echo "<td>[".$this->getTiempoDeFinal()."]</td>";
        echo "</tr>";
        echo "</table>" ;
    }

    public static function printEntidadesComomesa($ListaEntidades)
    {
        echo "<table border='2'>";
        echo '<caption>comidaes List</caption>';
        echo "<th>[comida_ID]</th><th>[AREA_ID]</th><th>[COMIDA_AOSC]</th><th>[estado]</th>
        <th>[descripcion]</th><th>[precio]</th><th>[tiempoDeInicio]</th><th>[tiempoParaTerminar]</th><th>[tiempoDeFinal]</th>";
        foreach($ListaEntidades as $entidad)
        {
            echo "<tr align='center'>";
            echo "<td>[".$entidad->getcomidaId()."]</td>";
            echo "<td>[".$entidad->getComidaArea()."]</td>";
            echo "<td>[".$entidad->getComidaPedidoAsociado()."]</td>";
            echo "<td>[".$entidad->getComidaEstado()."]</td>"; 
            echo "<td>[".$entidad->getComidaDescripcion()."]</td>";
            echo "<td>[$".$entidad->getComidaPrecio()."]</td>";
            echo "<td>[".$entidad->getTiempoDeInicio()."]</td>";
            echo "<td>[".$entidad->getTiempoParaTerminar()."]</td>";
            echo "<td>[".$entidad->getTiempoDeFinal()."]</td>";
            echo "</tr>";
        }
        echo "</table><br>" ;
    }

    
    public static function filtroParaComidaTerminada($ListaEntidades, $estado)  
    {
        $filteredList = array();
        foreach($ListaEntidades as $entidad){
            if(strcmp($entidad->getComidaEstado(), $estado) == 0)
            {
                array_push($filteredList, $entidad);
            }
        }
        return $filteredList;
    }


    public static function insertComida($comida) 
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepareQuery("INSERT INTO `comida` (`comida_area`, `comidaPedidoAsociado`, `comida_estado`, `comida_descripcion`, `comida_precio`, `tiempoDeInicio`) 
        VALUES (:comida_area, :comidaPedidoAsociado, :comida_estado, :comida_descripcion, :comida_precio, :tiempoDeInicio)");
        $query->bindValue(':comida_area', $comida->getComidaArea());
        $query->bindValue(':comidaPedidoAsociado', $comida->getComidaPedidoAsociado()); 
        $query->bindValue(':comida_estado', $comida->getComidaEstado());
        $query->bindValue(':comida_descripcion', $comida->getComidaDescripcion());
        $query->bindValue(':comida_precio', $comida->getComidaPrecio());
        $query->bindValue(':tiempoDeInicio', $comida->getTiempoDeInicio());
        $query->execute();

        return $objAccesoDatos->getLastInsertedID();
    }


    public static function updatecomida($comida)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepareQuery("UPDATE `comida` 
        SET `comida_estado` = :estado, `tiempoDeFinal` = :tiempoDeFinal, `tiempoParaTerminar` = :tiempoParaTerminar 
        WHERE `comida_id` = :comida_id");
        $query->bindValue(':estado', $comida->getComidaEstado());
        $query->bindValue(':tiempoDeFinal', $comida->getTiempoDeFinal());
        $query->bindValue(':tiempoParaTerminar', $comida->getTiempoParaTerminar());
        $query->bindValue(':comida_id', $comida->getcomidaId());
        $query->execute();

        return $query->rowCount();
    }


    public static function getTodasLasComidaes() 
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepareQuery("SELECT * FROM `comida`");
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS, "comida");
    }

    public static function getComidaPorId($comida_id) 
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepareQuery("SELECT * FROM `comida` WHERE `comida_id` = :comida_id");
        $query->bindParam(':comida_id', $comida_id);
        $query->execute();

        return $query->fetchObject("comida");
    }


    public static function getcomidasPorTipoDeUsuario($userType)  
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepareQuery(
            "SELECT 
            c.comida_id AS comida_id,
            c.comida_area AS comida_area,
            c.comidaPedidoAsociado AS comidaPedidoAsociado,
            c.comida_estado AS comida_estado,
            c.comida_descripcion AS comida_descripcion,
            c.comida_precio AS comida_precio,
            c.tiempoDeInicio AS tiempoDeInicio,
            c.tiempoDeFinal AS tiempoDeFinal,
            c.tiempoParaTerminar AS tiempoParaTerminar
            FROM comida AS c
            WHERE c.comida_area = :user_type;");
        $query->bindParam(':user_type', $userType);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS, "comida");
    }

    public static function getComidasOrdenadasPorId($pedidoId) 
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepareQuery(
            "SELECT 
            c.comida_id AS comida_id,
            c.comida_area AS comida_area,
            c.comidaPedidoAsociado AS comidaPedidoAsociado,
            c.comida_estado AS comida_estado,
            c.comida_descripcion AS comida_descripcion,
            c.comida_precio AS comida_precio,
            c.tiempoDeInicio AS tiempoDeInicio,
            c.tiempoDeFinal AS tiempoDeFinal,
            c.tiempoParaTerminar AS tiempoParaTerminar
            FROM comida AS c
            WHERE c.comidaPedidoAsociado = :pedido_id;" 
        );
        $query->bindParam(':pedido_id', $pedidoId); 
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS, "comida");
    }

    public static function deleteComida($comida)
    { 
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepareQuery("DELETE FROM `comida` WHERE `comida_id` = :comida_id");
        $query->bindValue(':comida_id', $comida->getcomidaId());
        $query->execute();

        return $query->rowCount();
    }

    public static function getSumaDePreciosPorPedido($pedido_id) 
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepareQuery("SELECT SUM(c.comida_precio) AS total FROM `comida` AS c WHERE `comidaPedidoAsociado` = :pedido_id");
        $query->bindParam(':pedido_id', $pedido_id);
        $query->execute();

        return $query->fetchObject()->total;
    }
}
?>