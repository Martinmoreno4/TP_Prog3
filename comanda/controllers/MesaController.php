<?php


require_once './interfaces/IApiUsable.php';
require_once './models/Mesa.php';

class MesaController extends Mesa implements IApiUsable
{

    public function TraerUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $id = $params['mesa_id'];
        $Mesa = Mesa::getMesaPorId($id);
        $Mesa->printUnaEntidadComoTable();
        $payload = json_encode($Mesa);
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }


	public function TraerTodos($request, $response, $args)
    {
        $Mesas = Mesa::getTodasLasMesas();

        echo 'Mesas: <br>';
        Mesa::printEntidadesComoTable($Mesas);

        $payload = json_encode(array("Mesas" => $Mesas));
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }


	public function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $Mesa = Mesa::createMesa($params['codigoDeMesa'], $params['employee_id'], $params['state']);
        
        $payload = json_encode($Mesa);
        $mesa_id = Mesa::insertarMesa($Mesa);
        if($mesa_id > 0)
        {
            echo 'Mesa creada: <br>'; 
            $Mesa->setId($mesa_id);
            $Mesa->printUnaEntidadComoTable();
            $payload = json_encode(array("mensaje" => "Mesa creada con exito"));
            $response->getBody()->write("Mesa creada con exito");
        }
        else
        {
            $response->getBody()->write("Something failed while creating the Mesa");
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');

    }

	public function BorrarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $id = $params['mesa_id'];
        $Mesa = Mesa::getMesaPorId($id);
        $payload = json_encode($Mesa);
        if(isset($Mesa) && Mesa::deleteMesa($id) > 0)
        {
            $payload = json_encode(array("mensaje" => "Mesa borrado con exito"));
        }
        else  
        {
            $payload = json_encode(array("mensaje" => "Error al borrar Mesa."));
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }


	public function ModificarUno($request, $response, $args)
    {

        $params = $request->getParsedBody();

        $this->TraerTodos($request, $response, $args);
        
        if (isset($params['mesa_id']) && isset($params['estado']) && isset($params['EmployeeId'])) 
        {
            $Mesaid = $params['mesa_id'];
            $employeeid = $params['EmployeeId'];

            $estado = $params['estado'];

            $employee = Employee::getEmployeeById($employeeid);
            
            if (isset($employee) && isset($Mesaid) && strcmp($estado, "Cerrada") != 0) {
                $Mesa = Mesa::getMesaPorId($Mesaid);
                $Mesa->setState($estado);
                $Mesa->setEmployeeId($params['EmployeeId']);
                echo 'Mesa seleccionada: <br>';
                $Mesa->printUnaEntidadComoTable();
            }
            else
            {
                echo '<h2>Invalid Action, check the parameters.</h2><br>';
            }
        }
        
        if (isset($Mesa) && Mesa::updateMesa($Mesa) > 0)  
        {
            $payload = json_encode(array("mensaje" => "Mesa actualizada con exito"));
        }
        else
        {
            $payload = json_encode(array("mensaje" => "Error al actualizar Mesa."));
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }


    public function CobrarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $payload = json_encode(array("mensaje" => "Error al actualizar Mesa."));

        if(!isset($params['mesa_id']) && !isset($params['estado'])) 
        {
            $Mesas = Mesa::getTodasLasMesas();
            echo 'All Mesas: <br>';
            Mesa::printEntidadesComoTable($Mesas);
        }

        if(isset($params['mesa_id']) && isset($params['estado']))
        {
            $id_Mesa = $params['mesa_id'];
            $estado = $params['estado'];
            $Mesa = Mesa::getMesaPorId($id_Mesa);

            echo 'Mesa seleccionada: <br>'; 
            $Mesa->printUnaEntidadComoTable();

            if(isset($Mesa))
            {
                $Mesa->setState($estado);
                echo 'Mesa actualizada: <br>';
                $Mesa->printUnaEntidadComoTable();
                if(Mesa::updateMesa($Mesa) > 0)
                {
                    $payload = json_encode(array("mensaje" => "Mesa actualizada con exito"));
                }
            }
        }
        
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }


	public function ModificarUnoAdmin($request, $response, $args)
    {

        $params = $request->getParsedBody();
        
        $this->TraerTodos($request, $response, $args);
        
        if (isset($params['mesa_id']) && isset($params['estado'])) 
        {
            $Mesaid = $params['mesa_id'];
            $estado = $params['estado'];

            if (isset($Mesaid)) 
            {
                $Mesa = Mesa::getMesaPorId($Mesaid);
                
                echo 'Mesa seleccionada: <br>';
                $Mesa->printUnaEntidadComoTable();

                if(strcmp($Mesa->getState(), "Cerrada") == 0 && strcmp($estado, "Cerrada") == 0)
                {
                    echo '<h2>La Mesa esta cerrada.</h2><br>';
                }
                else
                {
                    $Mesa->setState($estado);
                    echo 'Mesa modificada: <br>';
                    $Mesa->printUnaEntidadComoTable();
                }
            }
            else
            {
                echo '<h2>Error, accion invalida.</h2><br>';
            }
        }
        
        if (isset($Mesa) && Mesa::updateMesa($Mesa) > 0) 
        {
            $payload = json_encode(array("mensaje" => "Mesa actualizada con exito"));
        }
        else
        {
            $payload = json_encode(array("mensaje" => "Error al actualizar Mesa."));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerDemoraPedidoMesa($request, $response, $args)
    {

        $codigoDeMesa = $args['codigoDeMesa'];
        $Pedido_id = $args['Pedido_id'];
        $delay = Pedido::getTieampoMaxPorCodigoDeMesa($Pedido_id, $codigoDeMesa)[0]['tiempo_Pedido'];
        if ($delay == 0) 
        { 
            echo '<h2>Mesa Codigo: '.$codigoDeMesa.'<br>tiempo de espera: '.$delay.' minutos.</h2>
            <h2>Su pedido esta listo, por favor espere</h2><br>';
        }
        else
        {
            echo '<h2>Mesa Codigo: '.$codigoDeMesa.'<br>Su pedido estara listo en: '.$delay.' minutos.</h2><br>';
        }
        $payload = json_encode(array("mensaje" => "tiempo de espera: ".$delay." minutos")); 
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
?>