<?php

class fetchCSVData{

    public function __construct(){

    }

    public function getJSONData(){

        $returnData = [];
        if (($open = fopen("data.csv", "r")) !== FALSE)
        {

            $dataArray =[];
            $headers = [];
            $count = 0;
            while (($data = fgetcsv($open, 1000, ",")) !== FALSE)
            {
                if($count == 0){
                    $headers[] = $data;
                } else{
                    $dataArray[] = $data;
                }

                $count++;
            }

            fclose($open);

            for($i= 0; $i <= (count($dataArray)-1); $i++){
                $returnData[] = array_combine($headers[0], $dataArray[$i]);
            }
            echo json_encode($returnData, true);
        }
    }
}

function getCSVData(){
    $jsonData = new fetchCSVData();
    $jsonData->getJSONData();
}

getCSVData();

