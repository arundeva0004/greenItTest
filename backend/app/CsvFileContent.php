<?php

namespace App;
use Exception;
if(isset($_SERVER['REQUEST_METHOD'])){
    require_once "CsvHeader.php";
}
require_once "CsvLog.php";

interface CsvDataInterface {
    function getAllCsvRecords($listRecords);    //list all records from csv file
    function addNewRowToCSVFile($inputData);    //add new record to csv file
    function updateRowToCSVFile($postData);     //update record to csv file
    function removeRowFromCsvFile($postData);   //remove record from csv file
}

class CsvFileContent implements CsvDataInterface
{

    const CSV_FILE_PATH = __DIR__ . "\..\public\CsvItems.csv";
    const LOG_FILE_NAME = '\CsvLog.txt';
    const CSV_FILE_UPDATE = "update";
    const CSV_FILE_DELETE = "delete";
    protected $log;

    public function __construct(){

        $this->log = new CsvLog();//initialize log object here
        if(isset($_SERVER['REQUEST_METHOD'])){
        $this->logWrite('SERVER_NAME - '.$_SERVER['SERVER_NAME']);
        $this->logWrite('HTTP_HOST - '.$_SERVER['HTTP_HOST']);
        $this->logWrite('REQUEST_URI - '.$_SERVER['REQUEST_URI']);
        $this->logWrite('QUERY_STRING - '.$_SERVER['QUERY_STRING']);
        }
    }

    /* get all records from csv file
        @return data should be json format
    */
    public function getAllCsvRecords($listRecords = [])
    {
        try{

            $this->logWrite('*****getAllCsvRecords - Starts ******');
            if (($open = fopen(CsvFileContent::CSV_FILE_PATH, "r")) !== FALSE)
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

            $this->logWrite('*****getAllCsvRecords - Ends ******');

            if(isset($_SERVER['REQUEST_METHOD'])) {
                echo json_encode($listRecords, true);
            } else {
                return $listRecords;
            }
        } catch (Exception $e){
            $this->logWrite('CsvFileContent::getAllCsvRecords - Error Message - ' . $e->getMessage());
            $this->logWrite('CsvFileContent::getAllCsvRecords - Error File - ' . $e->getFile());
            $this->logWrite('CsvFileContent::getAllCsvRecords - Error Line - ' . $e->getLine());
            http_response_code('403');
            echo json_encode(['title' => 'Error', 'description' => "Failed to load data!"], true);
        }
    }

    public function addNewRowToCSVFile($postData)
    {
        try{

            $this->logWrite('*****addNewRowToCSVFile - Starts ******');
            $file = fopen(self::CSV_FILE_PATH,"a");
            $data = [];

            $this->push($data, $postData['id']);
            $this->push($data, $postData['name']);
            $this->push($data, $postData['state']);
            $this->push($data, $postData['zip']);
            $this->push($data, $postData['amount']);
            $this->push($data, $postData['qty']);
            $this->push($data, $postData['item']);


            $string = "\n";
            foreach($data as $key => $value){
                $string .= $value;
                if($key != (count($data) -1)){
                    $string .= ",";
                }
            }


            $myFile = fopen(self::CSV_FILE_PATH, "a") or die("Unable to open file!");
            fwrite($myFile,  $string);
            rewind($myFile);
            fclose($myFile);

            $this->logWrite('*****addNewRowToCSVFile - Ends ******');

            echo json_encode(['title' => 'Success', 'description' => 'New data added successfully'], true);
        } catch (Exception $e){
            $this->logWrite('CsvFileContent::addNewRowToCSVFile - Error Message - ' . $e->getMessage());
            $this->logWrite('CsvFileContent::addNewRowToCSVFile - Error File - ' . $e->getFile());
            $this->logWrite('CsvFileContent::addNewRowToCSVFile - Error Line - ' . $e->getLine());
            http_response_code('403');
            echo json_encode(['title' => 'Error', 'description' =>  $e->getMessage()], true);
        }
    }

    /*
     @description To update given row information to the csv file
     @postData $postData from input data for new row
     */
    public function updateRowToCSVFile($postData)
    {
        try{

            $this->logWrite('*****updateRowToCSVFile - Starts ******');

            $this->updateCSVFile($postData, self::CSV_FILE_UPDATE);

            $this->logWrite('*****updateRowToCSVFile - Ends ******');

            echo json_encode(['title' => 'Success', 'description' => "Data updated successfully"], true);
        } catch (Exception $e){
            $this->logWrite('CsvFileContent::updateRowToCSVFile - Error Message - ' . $e->getMessage());
            $this->logWrite('CsvFileContent::updateRowToCSVFile - Error File - ' . $e->getFile());
            $this->logWrite('CsvFileContent::updateRowToCSVFile - Error Line - ' . $e->getLine());
            http_response_code('403');
            echo json_encode(['title' => 'Error', 'description' => $e->getMessage()], true);
        }

    }

    /*remove row from csv file */
    public function removeRowFromCsvFile($postData)
    {
        try{

            $this->logWrite('*****updateRowToCSVFile - Starts ******');

            $this->updateCSVFile($postData, self::CSV_FILE_DELETE);
            $response = json_encode(['title' => 'Success', 'description' => "Data deleted successfully"], true);

            $this->logWrite('*****updateRowToCSVFile - Ends ******');

            if(isset($_SERVER['REQUEST_METHOD'])) {
                echo $response;
            } else {
                return $response;
            }

        } catch (Exception $e){
            $this->logWrite('CsvFileContent::removeRowFromCsvFile - Error Message - ' . $e->getMessage());
            $this->logWrite('CsvFileContent::removeRowFromCsvFile - Error File - ' . $e->getFile());
            $this->logWrite('CsvFileContent::removeRowFromCsvFile - Error Line - ' . $e->getLine());
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
            $handle = fopen(self::CSV_FILE_PATH, "r");

            // READ CSV
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                // UPDATE BASED ON SPECIFIC ID ROW
                if($i == $postData['id']) {
                    if ($type == self::CSV_FILE_UPDATE) {//Update
                        $this->push($newData[$i], $postData['id']);
                        $this->push($newData[$i], $postData['name']);
                        $this->push($newData[$i], $postData['state']);
                        $this->push($newData[$i], $postData['zip']);
                        $this->push($newData[$i], $postData['amount']);
                        $this->push($newData[$i], $postData['qty']);
                        $this->push($newData[$i], $postData['item']);
                        $i++;
                        continue;
                    }else if ($type == self::CSV_FILE_DELETE) { //remove
                            $i++;
                            continue;
                        }
                }

                $this->push($newData[$i], $data[0]);
                $this->push($newData[$i], $data[1]);
                $this->push($newData[$i], $data[2]);
                $this->push($newData[$i], $data[3]);
                $this->push($newData[$i], $data[4]);
                $this->push($newData[$i], $data[5]);
                $this->push($newData[$i], $data[6]);
                $i++;
            }

            $new_string = '';
            foreach ($newData as  $row ) {
                foreach ($row as $row_key => $each_element){
                    $new_string .=  $each_element;
                    if($row_key != count($row)-1)
                        $new_string .=  ',';
                }
                $new_string .= "\n";
            }

            $myfile = fopen(self::CSV_FILE_PATH, "w") or die("Unable to open file!");
            fwrite($myfile, $new_string);
            fclose($myfile);

        } catch (Exception $e){
            $this->logWrite('CsvFileContent::updateCSVFile - Error Message - ' . $e->getMessage());
            $this->logWrite('CsvFileContent::updateCSVFile - Error File - ' . $e->getFile());
            $this->logWrite('CsvFileContent::updateCSVFile - Error Line - ' . $e->getLine());
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

    /*@strData - to write logs */
    public function logWrite($strData){
        $this->log->Write(self::LOG_FILE_NAME,$strData);
    }


}


function CSVInit(){

    // initialize CsvLog object
    $file_content = new CsvFileContent(); //initialize object
    $errors = ""; //post data errors
    $postData = json_decode(file_get_contents('php://input'), true); // get request input data
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

if(isset($_SERVER['REQUEST_METHOD'])) CSVInit();




