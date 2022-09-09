<?php

class FileWriteCSV{

    public function __construct(){
        include "header.php";
    }

    public function push(&$data, $item) {
        $quote = chr(34); // quote " character from ASCII table
        $data[] = $quote . addslashes(strval($item)) . $quote;
    }

    public function arrayToCSV($inputData){
        $file = fopen("data.csv","a");
        $data = [];
        //"id","name","state","zip","amount","qty","item"
        $this->push($data, $inputData['id']);
        $this->push($data, $inputData['name']);
        $this->push($data, $inputData['state']);
        $this->push($data, $inputData['zip']);
        $this->push($data, $inputData['amount']);
        $this->push($data, $inputData['qty']);
        $this->push($data, $inputData['item']);

        fputcsv($file,  $data, ',', chr(0));
        rewind($file);

        fclose($file);
        return 'Data added successfully';
    }
}

    function addNewDataToCSV(){
        $inputData = json_decode(file_get_contents('php://input'), true);
        $new = new FileWriteCSV();

         $new->arrayToCSV($inputData);
    }

        addNewDataToCSV();





