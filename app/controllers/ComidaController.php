<?php

require_once './interfaces/IApiUsable.php';
require_once './models/Area.php';
require_once './models/Comida.php';
require_once './models/Pedido.php';

class ComidaController extends Comida implements IApiUsable
{ 

    public function TraerUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $id = $params['Id'];
        $Comida = Comida::getComidaPorId($id);
        $payload = json_encode($Comida); 
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

	public function TraerTodos($request, $response, $args)
    {
        $employee_type = UsuarioController::GetInfoByToken($request)->User_Type;
        $employee_type_id = Area::getAreaPorRoles($employee_type);
        $Comidas = Comida::getcomidasPorTipoDeUsuario($employee_type_id);
        $ComidasToPrint = array();

        foreach ($Comidas as $Comida)  
        {
            if($Comida->getComidaEstado() != 'Listo Para Servir')
            {
                array_push($ComidasToPrint, $Comida);
            }
        }

        echo 'Comidas Pendant/ In Preparation for Position: ['.$employee_type.']<br>';
        Comida::printUnaEntidadComomesa($ComidasToPrint);

        $payload = json_encode(array("Comidas" => $Comidas));
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }


	public function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $area = $params['Area'];
        $Pedido_id = $params['comidaPedidoAsociado'];
        $area = Area::getAreaPorNombre($area);
        $Pedido = Pedido::getPedidoPorId($Pedido_id);
        $Comida = Comida::createComida(
            $area->getAreaId(), 
            $Pedido->getId(), 
            $Pedido->getEstadoDePedido(), 
            $params['Description'], 
            $params['comida_precio'], 
            date("Y-m-d H:i:s")
        );
        
        echo 'Comida Created: <br>';
        $Comida->printUnaEntidadComomesa();

        $payload = json_encode($Comida);
        if(Comida::insertComida($Comida) > 0)
        {
            $Pedido_id = $params['comidaPedidoAsociado'];
            $Pedido = Pedido::getPedidoPorId($Pedido_id);
            $Pedido_cost = Comida::getSumaDePreciosPorPedido($Pedido->getId());
            $Pedido->setPrecioPedido($Pedido_cost);
            
            if(Pedido::updatePedido($Pedido) > 0)
            {
                echo 'Total price of the Pedido has been updated<br>';
                $Pedido->printUnaEntidadComomesa();
            }

            $payload = json_encode(array("mensaje" => "Comida creada con exito"));
            $response->getBody()->write("Comida creada con exito");
        }
        else
        {
            $response->getBody()->write("Something failed while creating Comida");
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }


	public function BorrarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $id = $params['Id'];
        $Comida = Comida::getComidaPorId($id);
        $payload = json_encode($Comida);
        if(Comida::deleteComida($id) > 0)
        {
            $payload = json_encode(array("mensaje" => "Comida eliminada con exito"));
            $response->getBody()->write("Comida deleted successfully");
        }
        else
        {
            $response->getBody()->write("Something failed while deleting Comida");
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }


	public function ModificarUno($request, $response, $args)
    {
        $this->TraerTodos($request, $response, $args);
        
        $params = $request->getParsedBody();
        
        if(isset($params['comida_id']) && isset($params['comida_estado'])) 
        {
            $id = $params['comida_id'];
            $comida_estado = $params['comida_estado'];
            $Comida = Comida::getComidaPorId($id);
            $Pedido = Pedido::getPedidoPorId($Comida->getComidaPedidoAsociado());

            echo 'Comida before modify: <br>';
            $Comida->printUnaEntidadComomesa();

            $Comida->setComidaEstado($comida_estado);
            if(strcmp($comida_estado, 'Listo Para Servir') == 0)
            {
                $Comida->setTiempoParaTerminar(0);
                echo '<h3>Comida "'.$Comida->getComidaDescripcion().'" waiting time have been changed to: 0 and its ready to deploy.</h3><br>';
            }
        }

        if(isset($params['tiempoParaTerminar']))
        {
            $tiempoParaTerminar = $params['tiempoParaTerminar'];
            $Comida->setTiempoParaTerminar($tiempoParaTerminar);
            $Comida->calcularTiempoParaTerminar();
        }

        if(isset($Comida))
        {
            echo 'Comida After modify: <br>';
            $Comida->printUnaEntidadComomesa();
        }
        
        if(isset($Comida) && Comida::updatecomida($Comida) > 0)
        {
            echo '<h3>Comida ID: '.$Comida->getComidaId().' Has been changed to: '.$Comida->getComidaEstado().'.</h3><br>';
        }

        if (isset($Pedido) && $Pedido->getEstadoDePedido() != 'Listo Para Servir' && $comida_estado != 'Listo Para Servir')
        {
            $Pedido->setEstadoDePedido($comida_estado);
            Pedido::updatePedido($Pedido);
        }

        if (isset($Pedido) && $Pedido->getEstadoDePedido() != 'Listo Para Servir' && $comida_estado == 'Listo Para Servir')
        {
            $Comidas = Comida::getComidasOrdenadasPorId($Pedido->getId());

            $filteredComidas = Comida::filtroParaComidaTerminada($Comidas, 'Listo Para Servir');
            

            echo 'Comidas of the Pedido: <br>';
            Comida::printUnaEntidadComomesa($Comidas);

            echo 'Finished Comidas: <br>';
            Comida::printUnaEntidadComomesa($filteredComidas);

            if(count($Comidas) == count($filteredComidas))
            {
                $Pedido->setEstadoDePedido($comida_estado);
                Pedido::updatePedido($Pedido);
                echo '<h3>Pedido ID: '.$Pedido->getId().' Has been changed to: '.$Pedido->getEstadoDePedido().' and is ready to deploy.</h3><br>';
                echo '<h3>All EL tiempo de espera ah sido cambiado a: 0.</h3><br>';
                
                $Mesaid = Mesa::getMesaPorIdDePedido($Pedido->getId());
                $Mesaid->setState('Con Clientes Comiendo');
                Mesa::updateMesa($Mesaid);
            }
        }

        if(isset($Comida) && Comida::updatecomida($Comida) > 0)
        {
            $payload = json_encode(array("mensaje" => "Comida modified successfully"));
        }
        else
        {
            $payload = json_encode(array("mensaje" => "Something failed while modifying the Comida."));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
?>