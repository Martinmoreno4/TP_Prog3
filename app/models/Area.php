<?php

require_once './db/AccesoDatos.php';

 class Area 
 {
        public $area_id;
        public $area_descripcion; 
        public static $AREA_ROLES = array
        ( 
            'Camarera' => 1,
            'Cocinero' => 2,
            'Barman' => 3,
            'Admin' => 4
        );

        public function __construct(){}

        //--- Getters ---//

        public function getAreaId()
        {
            return $this->area_id;
        }


        public function getAreaDescripcion() 
        {
            return $this->area_descripcion;
        }


        public Static function getAreaPorRoles($rol) 
        {
            return intval(self::$AREA_ROLES[$rol]);
        }

        //--- Setters ---//


        public function setAreaId($area_id)
        {
            $this->area_id = $area_id;
        }


        public function setAreaDescripcion($area_descripcion)
        {
            $this->area_descripcion = $area_descripcion;
        }


        public function insertarArea()
        {
            $objDataAccess = DataAccess::getInstance();
            $sql = "INSERT INTO area (area_descripcion) VALUES (:area_descripcion);";
            $query = $objDataAccess->prepareQuery($sql);
            $query->bindValue(':area_descripcion', $this->getAreaDescripcion());
            $query->execute();

            return $objDataAccess->obtenerUltimoId();
        }

        //--- Update Area ---//

        public static function updateArea($area)
        {
            $objDataAccess = DataAccess::getInstance();
            $sql = "UPDATE area SET area_descripcion = ':area_descripcion' WHERE area_id = :area_id;";
            $query = $objDataAccess->prepareQuery($sql);
            $query->bindValue(':area_id', $area->getAreaId());
            $query->bindValue(':area_descripcion', $area->getAreaDescripcion());
            return $query->execute();
        }

        //--- Delete Area ---//

        public static function deleteArea($area)
        {
            $objDataAccess = DataAccess::getInstance();
            $sql = "DELETE FROM area WHERE area_id = :area_id";
            $query = $objDataAccess->prepareQuery($sql);
            $query->bindValue(':area_id', $area->getAreaId());
            return $query->execute();
        }

        //--- Get Area ---//

        public static function getAreaPorId($area_id) 
        {
            $objDataAccess = DataAccess::getInstance();
            $query = $objDataAccess->prepareQuery("SELECT * FROM area WHERE area_id = :area_id;");
            $query->bindParam(':area_id', $area_id);
            $query->execute();
            $area = $query->fetchObject('Area');
            if(is_null($area))
            {
                throw new Exception("no existe el area.");
            }
            
            return $area;
        }


        public static function getAreaPorNombre($area_nombre) 
        {
            $objDataAccess = DataAccess::getInstance();
            $query = $objDataAccess->prepareQuery("SELECT area_id, area_descripcion FROM area WHERE area_descripcion = :area_descripcion;");
            $query->bindParam(':area_descripcion', $area_nombre);
            $query->execute();
            $area = $query->fetchObject('Area');
            
            return $area;
        }

        public static function getTodasLasAreas() 
        {
            $objDataAccess = DataAccess::getInstance();
            $sql = "SELECT * FROM area;";
            $query = $objDataAccess->prepareQuery($sql);
            $query->execute();
            $areas = $query->fetchAll(PDO::FETCH_CLASS, 'Area');
            return $areas;
        }

        public static function getAreasPordescripcion($area_descripcion)
        {
            $objDataAccess = DataAccess::getInstance();
            $sql = "SELECT * FROM area WHERE area_descripcion = ':area_descripcion';";
            $query = $objDataAccess->prepareQuery($sql);
            $query->bindParam(':area_descripcion', $area_descripcion);
            $query->execute();
            $areas = $query->fetchAll(PDO::FETCH_CLASS, 'Area');
            return $areas;
        } 
 }
?>