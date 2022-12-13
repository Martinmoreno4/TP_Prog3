<?php


 class Mesa
 {
        public $id;
        public $codigoDeMesa; 
        public $idDeEmpleado;
        public $estado; 

        public function __construct() {}

        public static function createMesa($codigoDeMesa, $idDeEmpleado, $estado) 
        {
            $mesa = new Mesa();
            $mesa->setCodigoDeMesa($codigoDeMesa);
            $mesa->setIdDeEmpleado($idDeEmpleado);
            $mesa->setEstado($estado);

            return $mesa;
        }

        //--- Getters ---//

        public function getId()
        {
            return $this->id;
        }


        public function getCodigoDeMesa() 
        {
            return $this->codigoDeMesa;
        }

        public function getIdDeEmpleado() 
        {
            return $this->idDeEmpleado;
        }

        public function getEstado() 
        {
            return $this->estado;
        }

    
        public function setId($id)
        {
            $this->id = $id;
        }

        public function setCodigoDeMesa($codigoDeMesa)
        {
            $this->codigoDeMesa = $codigoDeMesa;
        }
    
        public function setIdDeEmpleado($idDeEmpleado)
        {
            $this->idDeEmpleado = $idDeEmpleado;
        }

        public function setEstado($estado) 
        {
            $this->estado = $estado;
        }

        //--- Methods ---//

        public function printUnaEntidadComoTable() 
        {
            echo "<table border='2'>"; 
            echo '<caption>Table Data</caption>';
            echo "<th>[mesa_id]</th><th>[codigoDeMesa]</th><th>[idDeEmpleado]</th><th>[estado]</th>";
            echo "<tr align='center'>";
            echo "<td>[".$this->getId()."]</td>";
            echo "<td>[".$this->getCodigoDeMesa()."]</td>";
            echo "<td>[".$this->getIdDeEmpleado()."]</td>";
            echo "<td>[".$this->getEstado()."]</td>";
            echo "</tr>";
            echo "</table>" ;
        }

        public static function printEntidadesComoTable($ListaEntidades) 
        {
            echo "<table border='2'>";
            echo '<caption>Tables List</caption>';
            echo "<th>[mesa_id]</th><th>[codigoDeMesa]</th><th>[idDeEmpleado]</th><th>[estado]</th>";
            foreach($ListaEntidades as $entidad)
            {
                echo "<tr align='center'>";
                echo "<td>[".$entidad->getId()."]</td>";
                echo "<td>[".$entidad->getCodigoDeMesa()."]</td>";
                echo "<td>[".$entidad->getIdDeEmpleado()."]</td>";
                echo "<td>[".$entidad->getEstado()."]</td>";
                echo "</tr>";
            }
            echo "</table><br>" ;
        }

        public static function getTablesPorIdDeEmpleado($idDeEmpleado) 
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $query = $objAccesoDatos->prepareQuery('SELECT * FROM Mesas WHERE idDeEmpleado = :idDeEmpleado');
            $query->bindParam(':idDeEmpleado', $idDeEmpleado);
            $query->execute();

            return $query->fetchAll(PDO::FETCH_CLASS, 'Mesa');
        }

        public static function getTodasLasMesas() 
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $query = $objAccesoDatos->prepareQuery('SELECT * FROM Mesas');
            $query->execute();

            return $query->fetchAll(PDO::FETCH_CLASS, 'Mesa');
        }

        public static function getMesaPorId($id) 
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $query = $objAccesoDatos->prepareQuery('SELECT * FROM Mesas WHERE id = :id');
            $query->bindParam(':id', $id);
            $query->execute();

            return $query->fetchObject('Mesa');
        }

        public static function insertarMesa($mesa)  
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $query = $objAccesoDatos->prepareQuery('INSERT INTO Mesas (codigoDeMesa, idDeEmpleado, estado) 
            VALUES (:codigoDeMesa, :idDeEmpleado, :estado)');
            $query->bindValue(':codigoDeMesa', $mesa->getCodigoDeMesa());
            $query->bindValue(':idDeEmpleado', $mesa->getIdDeEmpleado());
            $query->bindValue(':estado', $mesa->getEstado());
            $query->execute();

            return $objAccesoDatos->getLastInsertedId();
        }

        public static function updateMesa($table) 
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $query = $objAccesoDatos->prepareQuery('UPDATE Mesas SET idDeEmpleado = :idDeEmpleado, estado = :estado WHERE id = :id');
            $query->bindValue(':idDeEmpleado', $table->getIdDeEmpleado(), PDO::PARAM_INT);
            $query->bindValue(':estado', $table->getEstado(), PDO::PARAM_STR);
            $query->bindValue(':id', $table->getId(), PDO::PARAM_INT);
            $query->execute();

            return $query->rowCount() > 0;
        }

        public static function getMesaCerrada() 
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $query = $objAccesoDatos->prepareQuery('SELECT * FROM Mesas WHERE estado = "Cerrada" LIMIT 1;');
            $query->execute();

            return $query->fetchObject('Mesa');
        }

        public static function getMesaPorIdDePedido($pedido_id)
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $query = $objAccesoDatos->prepareQuery(
                'SELECT * FROM Mesas
                WHERE id = (SELECT mesa_id FROM pedidos WHERE id = :pedidos_id)'); 
            $query->bindParam(':pedidos_id', $orden_id);
            $query->execute();
        
            return $query->fetchObject('Mesa');
        }

        public static function initMesaEstado($estado = 'Cerrada') 
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia(); 
            $mesaLibre = self::getMesaCerrada();
            if($mesaLibre){
                $query = $objAccesoDatos->prepareQuery('UPDATE Mesas SET estado = :estado WHERE id = :id;');
                $query->bindParam(':estado', $estado, PDO::PARAM_STR);
                $query->bindValue(':id', $mesaLibre->getId(), PDO::PARAM_INT);
                $query->execute();
                return $mesaLibre->getId();
            }
            return 0;
        }

        public static function updateMesaEstado($mesa, $estado)
        {  
            $objAccesoDatos = AccesoDatos::obtenerInstancia(); 
            $query = $objAccesoDatos->prepareQuery('UPDATE Mesas SET estado = :estado WHERE id = :id;');
            $query->bindParam(':estado', $estado, PDO::PARAM_STR);
            $query->bindValue(':id', $mesa->getId(), PDO::PARAM_INT);
            $query->execute();
            return $query->rowCount() > 0;
        }

        public static function deleteMesa($table) 
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $query = $objAccesoDatos->prepareQuery('DELETE FROM Mesas WHERE id = :id');
            $query->bindValue(':id', $table->getId());
            $query->execute();

            return $query->rowCount() > 0;
        }
 }
?>