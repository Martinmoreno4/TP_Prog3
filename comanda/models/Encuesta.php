<?php

use Fpdf\Fpdf;

class Encuesta  
{
    public $id;
    public $pedido_id; 
    public $puntuacion_mesa; 
    public $puntuacion_restaurante; 
    public $puntuacion_mozo;
    public $puntuacion_chef; 
    public $puntuacionPromedio;  
    public $comentario; 

    public function __construct() {}

    public static function createEncuesta($pedido_id, $puntuacion_mesa, $puntuacion_restaurante, $puntuacion_mozo, $puntuacion_chef, $comentario) 
    {
        $encuesta = new encuesta();
        $encuesta->setPedidoId($pedido_id); 
        $encuesta->setPuntuacionMesa($puntuacion_mesa); 
        $encuesta->setPuntuacionResto($puntuacion_restaurante); 
        $encuesta->setPuntuacionMozo($puntuacion_mozo); 
        $encuesta->setPuntuacionChef($puntuacion_chef); 
        $encuesta->setPuntuacionPromedio(); 
        $encuesta->setComentario($comentario);

        return $encuesta;
    }

    //--------------------------------------------------------------------------------------------------------------------------------------------
    
    public function getId()
    {
        return $this->id;
    }


    public function getPedidoId()  
    {
        return $this->pedido_id;
    }

    public function getPuntuacionMesa()  
    {
        return $this->puntuacion_mesa;
    }

    public function getPuntuacionResto()  
    {
        return $this->puntuacion_restaurante;
    }

    public function getPuntuacionMozo()  
    {
        return $this->puntuacion_mozo;
    }

    public function getPuntuacionChef() 
    {
        return $this->puntuacion_chef;
    }

    public function getPuntuacionPromedio()  
    {
        return $this->puntuacionPromedio;
    }

    public function getComentario()  
    {
        return $this->comentario;
    }

    //------------------------------------


    public function setId($id) 
    {
        $this->id = $id;
    }

    public function setPedidoId($pedido_id) 
    {
        $this->pedido_id = $pedido_id;
    }

    public function setPuntuacionMesa($puntuacion_mesa) 
    {
        $this->puntuacion_mesa = $puntuacion_mesa;
    }

    public function setPuntuacionResto($puntuacion_restaurante) 
    {
        $this->puntuacion_restaurante = $puntuacion_restaurante;
    }

    public function setPuntuacionMozo($puntuacion_mozo) 
    {
        $this->puntuacion_mozo = $puntuacion_mozo;
    }

    public function setPuntuacionChef($puntuacion_chef) 
    {
        $this->puntuacion_chef = $puntuacion_chef;
    }

    public function setPuntuacionPromedio() 
    {
        $promedio = 0;  
        $sumaArray = array($this->puntuacion_mesa, $this->puntuacion_restaurante, $this->puntuacion_mozo, $this->puntuacion_chef);

        if(count($sumaArray) > 0) 
        {
            $promedio = round(array_sum($sumaArray) / count($sumaArray), 2, PHP_ROUND_HALF_EVEN);
        }
        $this->puntuacionPromedio = $promedio; 
    }


    public function setComentario($comentario) 
    {
        $this->comentario = $comentario;
    }


    public function printUnaEntidadComomesa()
    {
        echo "<table border='2'>";
        echo '<caption>encuesta Data</caption>';
        echo "<th>[encuesta_ID]</th><th>[pedido_id]</th><th>[puntuacion_mesa]</th><th>[puntuacion_restaurante]</th><th>[puntuacion_mozo]</th><th>[puntuacion_chef]</th><th>[puntuacionPromedio]</th>";
        echo "<tr align='center'>";
        echo "<td>[".$this->getId()."]</td>";
        echo "<td>[".$this->getPedidoId()."]</td>";
        echo "<td>[".$this->getPuntuacionMesa()."]</td>";
        echo "<td>[".$this->getPuntuacionResto()."]</td>";
        echo "<td>[".$this->getPuntuacionMozo()."]</td>";
        echo "<td>[".$this->getPuntuacionChef()."]</td>";
        echo "<td>[".$this->getPuntuacionPromedio()."]</td>";
        echo "</tr>";
        echo "<th colspan='7' align='center'>[comentario]</th>";
        echo "<tr>";
        echo "<td colspan='7' align='center'>[".$this->getComentario()."]</td>";
        echo "</tr>";
        echo "</table>" ;
    }


    public static function printEntidadesComomesa($ListaEntidades)
    {
        echo "<table border='2'>";
        echo '<caption>encuestas List</caption>';
        echo "<th>[encuesta_ID]</th><th>[pedido_id]</th><th>[puntuacion_mesa]</th><th>[puntuacion_restaurante]</th><th>[puntuacion_mozo]</th><th>[puntuacion_chef]</th><th>[puntuacionPromedio]</th>";
        foreach($ListaEntidades as $entidad) 
        {
            echo "<tr align='center'>";
            echo "<td>[".$entidad->getId()."]</td>";
            echo "<td>[".$entidad->getPedidoId()."]</td>";
            echo "<td>[".$entidad->getPuntuacionMesa()."]</td>";
            echo "<td>[".$entidad->getPuntuacionResto()."]</td>";
            echo "<td>[".$entidad->getPuntuacionMozo()."]</td>";
            echo "<td>[".$entidad->getPuntuacionChef()."]</td>";
            echo "<td>[<strong>".$entidad->getPuntuacionPromedio()."</strong>]</td>";
            echo "</tr>";
            echo "<th colspan='7' align='center'>[comentario]</th>";
            echo "<tr>";
            echo "<td colspan='7' align='center'>[".$entidad->getComentario()."]</td>";
            echo "</tr>";
        }
        echo "</table><br>" ;
    }


    public static function DownloadPdf($directory, $cantidadencuestas)
    {
        $encuestas = self::getEncuestasMasAltas($cantidadencuestas);
        if ($encuestas) {
            if(!file_exists($directory)){
                mkdir($directory, 0777, true);
            }


            $pdf = new FPDF();
            $pdf->AddPage();

            $pdf->SetFont('Arial', '', 15);

            $pdf->Cell(60, 4, 'TP Final Programacion III', 0, 1, 'L');
            $pdf->Cell(60, 0, '', 'T');
            $pdf->Ln(3);

            $pdf->Cell(60, 4, 'Martin Moreno', 0, 1, 'L');
            $pdf->Cell(40, 0, '', 'T');
            $pdf->Ln(5);

            $header = array('ID', 'PEDIDO', 'PUNTUACION_M', 'PUNTUACION_R', 'PUNTUACION_MO', 'PUNTUACION_C', 'promedio', 'comentario');

            $pdf->SetFillColor(125, 0, 0);
            $pdf->SetTextColor(125);
            $pdf->SetDrawColor(50, 0, 0);
            $pdf->SetLineWidth(.3);
            $pdf->SetFont('Arial', 'B', 8);
            $w = array(10, 12, 15, 15, 15, 15, 15, 92);

            for ($i = 0; $i < count($header); $i++) 
            {
                $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
            }
            $pdf->Ln();

            $pdf->SetFillColor(215, 209, 235);
            $pdf->SetTextColor(0);
            $pdf->SetFont('');

            $fill = false;

            foreach ($encuestas as $encuesta) 
            {
                $pdf->Cell($w[0], 6, $encuesta->getId(), 'LR', 0, 'C', $fill);
                $pdf->Cell($w[1], 6, $encuesta->getPedidoId(), 'LR', 0, 'C', $fill);
                $pdf->Cell($w[2], 6, $encuesta->getPuntuacionMesa(), 'LR', 0, 'C', $fill);
                $pdf->Cell($w[3], 6, $encuesta->getPuntuacionResto(), 'LR', 0, 'C', $fill);
                $pdf->Cell($w[4], 6, $encuesta->getPuntuacionMozo(), 'LR', 0, 'C', $fill);
                $pdf->Cell($w[5], 6, $encuesta->getPuntuacionChef(), 'LR', 0, 'C', $fill);
                $pdf->Cell($w[6], 6, $encuesta->getPuntuacionPromedio(), 'LR', 0, 'C', $fill);
                $pdf->Cell($w[7], 6, $encuesta->getComentario(), 'LR', 0, 'C', $fill);
                $pdf->Ln();
                $fill = !$fill;
            }

            $pdf->Cell(array_sum($w), 0, '', 'T');

            $newFilename = $directory.'encuestas_' . date('Y_m_d') .'.pdf';
            $pdf->Output('F', $newFilename, 'I');

            $payload = json_encode(array("message" => 'pdf created ' . $newFilename));
        }
        else 
        {
            $payload = json_encode(array("error" => 'error getting data'));
        }
        
        return $payload;
    }

    public static function insertEncuesta($encuesta)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepareQuery('INSERT INTO `encuesta` (pedido_id, puntuacion_mesa, puntuacion_restaurante, puntuacion_mozo, puntuacion_chef, puntuacionPromedio, comentario) 
        VALUES (:pedido_id, :puntuacion_mesa, :puntuacion_restaurante, :puntuacion_mozo, :puntuacion_chef, :puntuacionPromedio, :comentario)');
        $query->bindValue(':pedido_id', $encuesta->getPedidoId());
        $query->bindValue(':puntuacion_mesa', $encuesta->getPuntuacionMesa());
        $query->bindValue(':puntuacion_restaurante', $encuesta->getPuntuacionResto());
        $query->bindValue(':puntuacion_mozo', $encuesta->getPuntuacionMozo());
        $query->bindValue(':puntuacion_chef', $encuesta->getPuntuacionChef());
        $query->bindValue(':puntuacionPromedio', $encuesta->getPuntuacionPromedio());
        $query->bindValue(':comentario', $encuesta->getComentario());
        $query->execute();

        return $objAccesoDatos->getLastInsertedID();
    }

    public static function getAll()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepareQuery('SELECT * FROM `encuesta`');
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS, 'encuesta');
    }

    public static function getencuestaPorId($id)
    { 
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepareQuery('SELECT * FROM `encuesta` WHERE id = :id');
        $query->bindParam(':id', $id);
        $query->execute();

        return $query->fetchObject('encuesta');
    }


    public static function getEncuestasMasAltas($cantidad)  
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $query = $objAccesoDatos->prepareQuery(
            'SELECT * FROM `encuesta` 
            ORDER BY puntuacionPromedio DESC 
            LIMIT :cantidad');
        $query->bindParam(':cantidad', $cantidad);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS, 'encuesta');
    }
}
?>