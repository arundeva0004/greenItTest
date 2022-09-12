<?php
class LoadCsvData {

    public function __construct(){
        // TODO: construct method.
    }

    /* getJSONData is a method to get lists of csv records
        @returnData default value is empty array
        return data should be json format
    */
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
        }


         echo json_encode($returnData, true);
    }
}

function getCsvRecords(){
    $new = new LoadCsvData();
    $new->getJSONData();
}

getCsvRecords();
