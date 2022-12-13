<?php

require_once './db/AccesoDatos.php';
require_once './models/Empleado.php';
require_once './models/Mesa.php';

class Pedido{ 

    public $id;
    public $mesa_id; 
    public $estadoDePedido; 
    public $nombreCliente;  
    public $imagenDePedido; 
    public $precioPedido; 

    public function __construct(){}

    public static function crearPedido($mesa_id, $estadoDePedido, $nombreCliente, $imagenDePedido, $precioPedido = 0) 
    {
        $nuevoPedido = new Pedido();
        $nuevoPedido->setMesaId($mesa_id); 
        $nuevoPedido->setEstadoDePedido($estadoDePedido); 
        $nuevoPedido->setNombreCliente($nombreCliente); 
        $nuevoPedido->setImagenDePedido($imagenDePedido); 
        $nuevoPedido->setPrecioPedido($precioPedido); 

        return $nuevoPedido;
    }

    //-------------------------------------------------------

    public function getId()
    {
        return $this->id;
    }

    public function getMesaId()
    { 
        return $this->mesa_id;
    }

    public function getEstadoDePedido()
    {
        return $this->estadoDePedido;
    }

    public function getNombreCliente()
    { 
        return $this->nombreCliente;
    }

    public function getImagenDePedido()
    { 
        return $this->imagenDePedido;
    }

    public function getPrecioPedido() 
    {
        return $this->precioPedido;
    }
    
    //-------------------------------------------------------

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setMesaId($mesa_id){
        $this->mesa_id = $mesa_id;
    }

    public function setEstadoDePedido($estadoDePedido)
    {
        $this->estadoDePedido = $estadoDePedido;
    }

    public function setNombreCliente($nombreCliente)
    {
        $this->nombreCliente = $nombreCliente;
    }

    public function setImagenDePedido($imagenDePedido)
    {
        $this->imagenDePedido = $imagenDePedido;
    }

    public function setPrecioPedido($precioPedido)
    {
        $this->precioPedido = $precioPedido;
    }

    //-------------------------------------------------------

    public function printUnaEntidadComoMesa()
    {
        echo "<mesa border='2'>";
        echo '<caption>Pedido Data</caption>';
        echo "<th>[Pedido_ID]</th><th>[mesa_id]</th><th>[ESTADO]</th><th>[CLIENTE]</th><th>[IMAGEN]</th><th>[PRECIO]</th>";
        echo "<tr align='center'>";
        echo "<td>[".$this->getId()."]</td>";
        echo "<td>[".$this->getMesaId()."]</td>";
        echo "<td>[".$this->getEstadoDePedido()."]</td>";
        echo "<td>[".$this->getNombreCliente()."]</td>";
        echo "<td>[".$this->getImagenDePedido()."]</td>";
        echo "<td>[$".$this->getPrecioPedido()."]</td>";
        echo "</tr>";
        echo "</mesa>" ;
    }

    public static function printEntidadesComoMesa($ListaEntidades)
    {
        echo "<mesa border='2'>";
        echo '<caption>Pedidos List</caption>';
        echo "<th>[Pedido_ID]</th><th>[mesa_id]</th><th>[ESTADO]</th><th>[CLIENTE]</th><th>[IMAGEN]</th><th>[PRECIO]</th>";
        foreach($ListaEntidades as $entidad){
            echo "<tr align='center'>";
            echo "<td>[".$entidad->getId()."]</td>";
            echo "<td>[".$entidad->getMesaId()."]</td>";
            echo "<td>[".$entidad->getEstadoDePedido()."]</td>";
            echo "<td>[".$entidad->getNombreCliente()."]</td>";
            echo "<td>[".$entidad->getImagenDePedido()."]</td>";
            echo "<td>[$".$entidad->getPrecioPedido()."]</td>";
            echo "</tr>";
        }
        echo "</mesa><br>" ;
    }

    public static function printEntidadesEstandarComomesa($ListaEntidades) 
    {
        echo "<mesa border='2'>"; 
        echo '<caption>Pedidos List</caption>';
        echo "<th>[Pedido_ID]</th><th>[mesa_id]</th><th>[ESTADO]</th><th>[CLIENTE]</th><th>[IMAGEN]</th><th>[PRECIO]</th><th>[TIEMPO_DE_ESPERA]</th>";
        foreach($ListaEntidades as $entidad){
            echo "<tr align='center'>";
            echo "<td>[".$entidad->id."]</td>";
            echo "<td>[".$entidad->mesa_id."]</td>";
            echo "<td>[".$entidad->estadoDePedido."]</td>";
            echo "<td>[".$entidad->nombreCliente."]</td>";
            echo "<td>[".$entidad->imagenDePedido."]</td>";
            echo "<td>[$".$entidad->precioPedido."]</td>";
            echo "<td>[".$entidad->tiempo_de_espera." Minutes]</td>"; 
            echo "</tr>";
        }
        echo "</mesa><br>" ;
    }

    //crud

    public static function insertPedido($Pedido)
    {
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepareQuery('INSERT INTO Pedidos (mesa_id, estadoDePedido, nombreCliente, imagenDePedido, precioPedido) 
        VALUES (:mesa_id, :estadoDePedido, :nombreCliente, :imagenDePedido, :precioPedido)');
        $query->bindValue(':mesa_id', $Pedido->getMesaId());
        $query->bindValue(':estadoDePedido', $Pedido->getEstadoDePedido());
        $query->bindValue(':nombreCliente', $Pedido->getNombreCliente());
        $query->bindValue(':imagenDePedido', $Pedido->getImagenDePedido());
        $query->bindValue(':precioPedido', $Pedido->getPrecioPedido());
        $query->execute();

        return $objDataAccess->getLastInsertedID();
    }


    public static function getAll()
    {
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepareQuery('SELECT * FROM Pedidos');
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }


    public static function getPedidosConTiempo() 
    {
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepareQuery(
            'SELECT 
            p.id,
            p.mesa_id,
            p.estadoDePedido,
            p.nombreCliente,
            p.imagenDePedido,
            p.precioPedido,
            MAX(c.tiempo_Para_Terminar) AS TIEMPO_DE_ESPERA 
            FROM comida AS c
            LEFT JOIN Pedidos as p
            ON c.comida_Pedido_asociado = p.id
            GROUP BY p.id
            Pedido by TIEMPO_DE_ESPERA DESC;');
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS, "stdClass");
    }

    public static function getPedidoPorId($id) 
    {
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepareQuery('SELECT * FROM Pedidos WHERE id = :id');
        $query->bindParam(':id', $id);
        $query->execute();

        return $query->fetchObject('Pedido');
    }


    public static function getPedidosPorMesa($mesa) 
    {
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepareQuery('SELECT * FROM Pedidos WHERE mesa_id = :mesa_id');
        $query->bindParam(':mesa_id', $mesa);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC, 'Pedido');
    }

    public static function getPedidosPorTipoDeUsuario($tipo)
    { 
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepareQuery('SELECT p.id, p.mesa_id, p.estadoDePedido 
        FROM Pedidos AS p
        LEFT JOIN mesas AS m ON p.mesa_id = m.id
        LEFT JOIN empleados AS e ON m.empleado_id = e.id
        LEFT JOIN usuarios AS u ON e.usuario_id = u.id  
        WHERE u.usuario_tipo = :tipo;'); 
        $query->bindParam(':tipo', $tipo);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC, 'Pedido');
    }

    public static function getPedidosPorEmpleado($empleado) 
    { 
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepareQuery('SELECT p.id, p.mesa_id, p.estadoDePedido 
        FROM Pedidos AS p
        LEFT JOIN mesas AS m ON p.mesa_id = m.id
        LEFT JOIN empleados AS e ON m.empleado_id = :id');
        $query->bindValue(':id', $empleado->getId());
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC, 'Pedido');
    }



    public static function updatePedido($Pedido)
    {
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepareQuery('UPDATE Pedidos 
        SET estadoDePedido = :estadoDePedido, precioPedido = :precioPedido 
        WHERE id = :id');
        $query->bindValue(':id', $Pedido->getId());
        $query->bindValue(':estadoDePedido', $Pedido->getEstadoDePedido());
        $query->bindValue(':precioPedido', $Pedido->getPrecioPedido());
        $query->execute();

        return $query->rowCount() > 0;
    }

    public static function updateImagen($Pedido) 
    {
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepareQuery('UPDATE Pedidos SET imagenDePedido = :imagenDePedido WHERE id = :id');
        $query->bindValue(':id', $Pedido->getId());
        $query->bindValue(':imagenDePedido', $Pedido->getImagenDePedido());
        $query->execute();

        return $query->rowCount() > 0;
    }


    public static function deletePedidoPorId($id) 
    {
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepareQuery('DELETE FROM Pedidos WHERE id = :id');
        $query->bindParam(':id', $id);
        $query->execute();
        
        return $objDataAccess->rowCount() > 0;
    }

    public static function getPorIdDeMesa($mesa_id)
    { 
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepareQuery('SELECT * FROM Pedidos WHERE mesa_id = :mesa_id');
        $query->bindParam(':mesa_id', $mesa_id);
        $query->execute();

        return $query->fetchObject('Pedido');
    }

    public static function getTieampoMaxPorCodigoDeMesa($Pedido_id, $mesa_codigo)
    { 
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepareQuery(
            'SELECT 
            MAX(c.tiempo_Para_Terminar) AS tiempo_Pedido 
            FROM comida AS c
            LEFT JOIN Pedidos as p
            ON c.comida_Pedido_asociado = :Pedido_id 
            LEFT JOIN mesas AS m 
            ON o.mesa_id = m.id
            WHERE m.mesa_codigo = :mesa_codigo');
        $query->bindParam(':mesa_codigo', $mesa_codigo);
        $query->bindParam(':Pedido_id', $Pedido_id);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getPorEstado($estadoDePedido) 
    {
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepareQuery('SELECT * FROM Pedidos WHERE estadoDePedido = :estadoDePedido');
        $query->bindParam(':estadoDePedido', $estadoDePedido);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function getPorEstadoYMesaId($estadoDePedido, $mesa_id) 
    {
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepareQuery("SELECT * FROM Pedidos WHERE estadoDePedido = :estadoDePedido AND mesa_id = :mesa_id");
        $query->bindParam(':estadoDePedido', $estadoDePedido);
        $query->bindParam(':mesa_id', $mesa_id);
        $query->execute();

        return $query->fetchObject('Pedido');
    }
}
?>