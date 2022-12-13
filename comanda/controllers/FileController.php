<?php

 require_once './models/HistorialIngresos.php';

 class FileController extends HistorialIngresos
 {

    public function Read($request, $response, $args)
    {
        $filename = './Reports_Files/HistorialIngresos.csv';
        $dataToRead = HistorialIngresos::ReadCsv($filename);
        $payload = json_encode(array("Error" => 'Something Failed'));
        if(!is_null($dataToRead))
        {
            echo "<h1>Data readed and inserted successfully</h1>";
            $payload = json_encode(array("Success" => 'File inserted to the table.', "Historical Logins" => $dataToRead));
        }
        
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }


    public function Write($request, $response, $args){
        $loginsFromDb = HistorialIngresos::getAll();
        $filename = './Reports_Files/HistorialIngresos.csv';
        $payload = json_encode(array("Error" => 'File not Saved',"Historical Logins" => 'Error While Writing The File'));
        if(HistorialIngresos::WriteCsv($loginsFromDb, $filename))
        {
            echo 'File Saved in '.$filename;
            HistorialIngresos::printEntidadesComoTable($loginsFromDb);
            $payload = json_encode(array("Success" => 'File Saved as HistorialIngresos.csv',"Historical Logins" => $loginsFromDb));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function DownloadPdf($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $directory = './Reports_Files/';
        $payload = json_encode(array("Error" => 'File not Saved',"Best Encuestas" => 'Error While Writing The File'));
        
        if($params['cantidad'])
        {
            $amountEncuestas = $params['cantidad'];
            $payload = Encuesta::DownloadPdf($directory, $amountEncuestas);
            echo 'File Saved in '.$directory;
        }
        
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
 }
?>