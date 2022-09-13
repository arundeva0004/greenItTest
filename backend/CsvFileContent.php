<?php

class CsvFileContent {

    const CSV_FILE_NAME = "data.csv";
    const CSV_FILE_UPDATE = "update";
    const CSV_FILE_DELETE = "delete";
    public function __construct(){
        require "header.php";

    }

    /* get all records from csv file
        @return data should be json format
    */
    public function getAllCsvRecords($listRecords = [])
    {
        try{

            if (($open = fopen(CsvFileContent::CSV_FILE_NAME, "r")) !== FALSE)
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
                    $listRecords[] = array_combine($headers[0], $dataArray[$i]);
                }
            }

            echo json_encode($listRecords, true);
        } catch (Exception $e){
            http_response_code('403');
            echo json_encode(['title' => 'Error', 'description' => "Failed to load data!"], true);
        }
    }

    public function addNewRowToCSVFile($postData)
    {
        try{
            $file = fopen(self::CSV_FILE_NAME,"a");
            $data = [];

            $this->push($data, $postData['id']);
            $this->push($data, $postData['name']);
            $this->push($data, $postData['state']);
            $this->push($data, $postData['zip']);
            $this->push($data, $postData['amount']);
            $this->push($data, $postData['qty']);
            $this->push($data, $postData['item']);

            fputcsv($file,  $data, ',', chr(0));
            rewind($file);

            fclose($file);

            echo json_encode(['title' => 'Success', 'description' => 'New data added successfully'], true);
        } catch (Exception $e){
            http_response_code('403');
            echo json_encode(['title' => 'Error', 'description' => "Failed add new data!"], true);
        }
    }

    public function updateRowToCSVFile($postData)
    {
        try{

            $this->updateCSVFile($postData, self::CSV_FILE_UPDATE);

            echo json_encode(['title' => 'Success', 'description' => "Data updated successfully"], true);
        } catch (Exception $e){
            http_response_code('403');
            echo json_encode(['title' => 'Error', 'description' => $e->getMessage()], true);
        }

    }

    public function removeRowFromCsvFile($postData)
    {
        try{
            $this->updateCSVFile($postData, self::CSV_FILE_DELETE);
            echo json_encode(['title' => 'Success', 'description' => "Data deleted successfully"], true);

        } catch (Exception $e){
            http_response_code('403');
            echo json_encode(['title' => 'Error', 'description' => $e->getMessage()], true);
        }
    }

    /* value added quotes logic */
    public function push(&$data, $item) {
        $quote = chr(34); // quotes
        $data[] = $quote . addslashes($item) . $quote;
    }

    /*
        @updateCSVFile class is basically get the lists of records from
            the csv file then update or remove to csv file
        @postData is updated or deleted row
        @type is used to get user action
    */
    public function updateCSVFile($postData, $type){
        try{
            $i = 0;
            $newData = [];
            $handle = fopen(self::CSV_FILE_NAME, "r");

            // READ CSV
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                // UPDATE BASED ON SPECIFIC ID ROW
                if ($i == $postData['id'] && $type == self::CSV_FILE_UPDATE) {


                    $newData[$i][] = $postData['id'];
                    $newData[$i][] = $postData['name'];
                    $newData[$i][] = $postData['state'];
                    $newData[$i][] = $postData['zip'];
                    $newData[$i][] = $postData['amount'];
                    $newData[$i][] = $postData['qty'];
                    $newData[$i][] = $postData['item'];

                    $i++;
                    continue;
                }

                // remove id row
                if($i == $postData['id'] && $type == self::CSV_FILE_DELETE){
                    $i++;
                    continue;
                }

                $newData[$i][] = $data[0];
                $newData[$i][] = $data[1];
                $newData[$i][] = $data[2];
                $newData[$i][] = $data[3];
                $newData[$i][] = $data[4];
                $newData[$i][] = $data[5];
                $newData[$i][] = $data[6];

                $i++;
            }

            // WRITE CSV
            $file = fopen(self::CSV_FILE_NAME, 'w');
            foreach ($newData as  $row ) {
                fputcsv($file, $row);
            }
            fclose($file);
        } catch (Exception $e){
            throw new Exception("Failed to updated csv data");
        }
    }



    /*
        @formDataValidations - input form validations for acceptance
        @postData - request input data
        @errors - append error to this file
    */
    public function formDataValidations($postData, $errors){

        // define variables to empty values
        $nameErr = $itemErr = $amountErr = $qtyErr = $stateErr = $zipErr = "";

        //String Validation
        if (empty($postData["name"])) {
            $nameErr = "Name is required";
        } else {
            $name = $postData["name"];

            // check if name only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
                $nameErr = "Only alphabets and white space are allowed in name field";
            }
        }

        if(!empty($nameErr)){
            $errors  .= $nameErr;
        }

        if (empty($postData["item"])) {
            $itemErr = "Item is required";
        } else {

            // check if item only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z ]/",$postData["item"])) {
                $itemErr = "Only alphabets and white space are allowed item field";
            }
        }

        if(!empty($itemErr)){
           if(!empty($errors)){
               $errors  .= " , " . $itemErr;
           }  else{
               $errors  .= $itemErr;
           }
        }

        if (empty($postData["state"])) {
            $stateErr = "State is required";
        } else {

            // check if state only contains letters and whitespace and
            if (!preg_match("/^[a-zA-Z ]/",$postData["state"])) {
                $stateErr = "Only alphabets and white space are allowed state field";
            }
        }

        if(!empty($stateErr)){
            if(!empty($stateErr)){
                $errors  .= " , " . $stateErr;
            }  else{
                $errors  .= $stateErr;
            }
        }

        if (empty($postData["zip"])) {
            $zipErr = "Zip is required";
        } else {

            // check if zip only contains letters and whitespace
            if (!preg_match("/[A-Za-z0-9]+/", $postData["zip"])) {
                $zipErr = "Only alphabets and white space  and numbers are allowed zip field";
            }
        }

        if(!empty($zipErr)){
            if(!empty($zipErr)){
                $errors  .= " , " . $zipErr;
            }  else{
                $errors  .= $zipErr;
            }
        }

        //Number  validations
        if (empty($postData["qty"])) {
            $qtyErr = "Quantity is required";
        } else {

            // It will allow all numeric values and decimal points.
            if (!is_numeric($postData["qty"])){
                $qtyErr = "Only allowed numeric value in qty field";
            }
        }

        if(!empty($qtyErr)){
            if(!empty($qtyErr)){
                $errors  .= " , " . $qtyErr;
            }  else{
                $errors  .= $qtyErr;
            }
        }


        //decimal value validations
        if (empty($postData["amount"])) {
            $amountErr = "Amount is required";
        } else {
            // It will allow all numeric values and decimal points.
            if (!preg_match('/[0-9.]+/', $postData["amount"])){
                $amountErr = "Only allowed decimal value in amount field";
            }
        }

        if(!empty($amountErr)){
            if(!empty($amountErr)){
                $errors  .= " , " . $amountErr;
            }  else{
                $errors  .= $amountErr;
            }
        }

        return $errors;
    }
}

function CSVInit(){

    $file_content = new CsvFileContent(); //initialize object
    $postData = json_decode(file_get_contents('php://input'), true); // get request input data
    $errors = ""; //post data errors

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $errors =  $file_content->formDataValidations($postData, $errors);
       if(!empty($errors)){
           echo $errors;
           http_response_code('403');
           exit;
       }
        $file_content->addNewRowToCSVFile($postData);

    } else if($_SERVER['REQUEST_METHOD'] === 'GET'){
        $file_content->getAllCsvRecords();
    } else if($_SERVER['REQUEST_METHOD'] === 'PUT'){
        $errors =  $file_content->formDataValidations($postData, $errors);
        if(!empty($errors)){
            http_response_code('403');
            echo $errors;
            exit;
        }
        $file_content->updateRowToCSVFile($postData);
    } else if($_SERVER['REQUEST_METHOD'] === 'DELETE'){

        $file_content->removeRowFromCsvFile($postData);
    }
}
CSVInit();