<?php

namespace App;
use Exception;
if(isset($_SERVER['REQUEST_METHOD'])){
    if(file_exists('AppHeader.php')) require_once('AppHeader.php');
}
if(file_exists('OrderLog.php')) require_once "OrderLog.php";

if(file_exists('OrderInterface.php')) require_once "OrderInterface.php";


class OrderController implements OrderInterface
{

    const CSV_FILE_PATH = __DIR__ . "\..\public\OrderItems.csv";
    const LOG_FILE_NAME = '\OrderLog.txt';
    const CSV_FILE_UPDATE = "update";
    const CSV_FILE_DELETE = "delete";
    protected $log;

    public function __construct(){

        $this->log = new OrderLog();//initialize log object here
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
    function getAllOrders($listRecords = [])
    {
        try{

            $this->logWrite('*****getAllCsvRecords - Starts ******');

            if (!file_exists(self::CSV_FILE_PATH)) {
                throw new Exception("OrderItems.csv file was not found");
            }

            if (($open = fopen(self::CSV_FILE_PATH, "r")) !== FALSE)
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
            $this->logWrite('OrderController::getAllCsvRecords - Error Message - ' . $e->getMessage());
            $this->logWrite('OrderController::getAllCsvRecords - Error File - ' . $e->getFile());
            $this->logWrite('OrderController::getAllCsvRecords - Error Line - ' . $e->getLine());
            http_response_code('403');
            echo json_encode(['title' => 'Error', 'description' => "Failed to load data!"], true);
        }
    }

    public function newOrderToCSVFile($postData)
    {
        try{

            $this->logWrite('*****newOrderToCSVFile - Starts ******');
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

            if (!file_exists(self::CSV_FILE_PATH)) {
                throw new Exception("OrderItems.csv file was not found");
            }
            $myFile = fopen(self::CSV_FILE_PATH, "a") or die("Unable to open file!");
            fwrite($myFile,  $string);
            rewind($myFile);
            fclose($myFile);

            $this->logWrite('*****newOrderToCSVFile - Ends ******');
            http_response_code('200');
            echo json_encode(['title' => 'Success', 'description' => 'Ordered successfully'], true);
        } catch (Exception $e){
            $this->logWrite('OrderController::addNewRowToCSVFile - Error Message - ' . $e->getMessage());
            $this->logWrite('OrderController::addNewRowToCSVFile - Error File - ' . $e->getFile());
            $this->logWrite('OrderController::addNewRowToCSVFile - Error Line - ' . $e->getLine());
            http_response_code('403');
            header("Content-type: application/json; charset=UTF-8");
            echo json_encode(['title' => 'Error', 'description' =>  $e->getMessage()], true);
        }
    }

    /*
     @description UPDATE ORDER TO CSV FILE
     @postData $postData
     */
    public function updateOrderToCSVFile($postData)
    {
        try{

            $this->logWrite('*****updateOrderToCSVFile - Starts ******');

            $this->updateCSVFile($postData, self::CSV_FILE_UPDATE);

            $this->logWrite('*****updateOrderToCSVFile - Ends ******');

            http_response_code('200');
            echo json_encode(['title' => 'Success', 'description' => "Order updated successfully"], true);
        } catch (Exception $e){
            $this->logWrite('OrderController::updateRowToCSVFile - Error Message - ' . $e->getMessage());
            $this->logWrite('OrderController::updateRowToCSVFile - Error File - ' . $e->getFile());
            $this->logWrite('OrderController::updateRowToCSVFile - Error Line - ' . $e->getLine());
            http_response_code('403');
            echo json_encode(['title' => 'Error', 'description' => $e->getMessage()], true);
        }

    }

    /*
        @desc DELETE THE ORDER FROM CSV FILE
        @param postData
    */
    public function deleteOrderFromCsvFile($postData)
    {
        try{

            $this->logWrite('*****deleteOrderFromCsvFile - Starts ******');

            $this->updateCSVFile($postData, self::CSV_FILE_DELETE);
            http_response_code('200');
            $response = json_encode(['title' => 'Success', 'description' => "Data deleted successfully"], true);

            $this->logWrite('*****deleteOrderFromCsvFile - Ends ******');

            if(isset($_SERVER['REQUEST_METHOD'])) {
                echo $response;
            } else {
                return $response;
            }

        } catch (Exception $e){
            $this->logWrite('OrderController::removeRowFromCsvFile - Error Message - ' . $e->getMessage());
            $this->logWrite('OrderController::removeRowFromCsvFile - Error File - ' . $e->getFile());
            $this->logWrite('OrderController::removeRowFromCsvFile - Error Line - ' . $e->getLine());
            http_response_code('403');
            echo json_encode(['title' => 'Error', 'description' => $e->getMessage()], true);
        }
    }


    /*
        @desc DELETE THE ORDER FROM CSV FILE
        @param postData
    */
    public function deleteMultipleOrder($postData)
    {
        try{

            $this->logWrite('*****deleteMultipleOrder - Starts ******');

            $this->updateCSVFile($postData, self::CSV_FILE_DELETE);
            http_response_code('200');
            $response = json_encode(['title' => 'Success', 'description' => "Data deleted successfully"], true);

            $this->logWrite('*****deleteMultipleOrder - Ends ******');

            if(isset($_SERVER['REQUEST_METHOD'])) {
                echo $response;
            } else {
                return $response;
            }

        } catch (Exception $e){
            $this->logWrite('OrderController::removeRowFromCsvFile - Error Message - ' . $e->getMessage());
            $this->logWrite('OrderController::removeRowFromCsvFile - Error File - ' . $e->getFile());
            $this->logWrite('OrderController::removeRowFromCsvFile - Error Line - ' . $e->getLine());
            http_response_code('403');
            echo json_encode(['title' => 'Error', 'description' => $e->getMessage()], true);
        }
    }

    /* VALUE ADDED QUOTES LOGIC */
    public function push(&$data, $item) {
        $quote = chr(34); // quotes
        $data[] = $quote . addslashes($item) . $quote;

    }

    /*
        @desc UPDATE THE ORDER TO CSV FILE
        @param postData
        @param type
    */
    public function updateCSVFile($postData, $type){
        try{

           $i = 0;
            $newData = [];

            if (!file_exists(self::CSV_FILE_PATH)) {
                throw new Exception("OrderItems.csv file was not found");
            }

            $handle = fopen(self::CSV_FILE_PATH, "r");

            // READ CSV
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                $orderId = null;
                if(isset($postData['multiple_order_delete'])){
                    //MULTI SELECTED DELETE
                    $key = array_search($data[0],$postData['selected_rows']);
                    $orderId = $postData['selected_rows'][$key];
                } else if(isset($postData['id'])){
                    $orderId = $postData['id'];
                }


                // UPDATE BASED ON SPECIFIC ID ROW
                if($data[0] == $orderId) {

                    if ($type == self::CSV_FILE_UPDATE && isset($postData['id'])) {//Update
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

                //ADD ORDERS TO newData VARIABLE
                $this->push($newData[$i], $data[0]);
                $this->push($newData[$i], $data[1]);
                $this->push($newData[$i], $data[2]);
                $this->push($newData[$i], $data[3]);
                $this->push($newData[$i], $data[4]);
                $this->push($newData[$i], $data[5]);
                $this->push($newData[$i], $data[6]);
                $i++;
            }


            $this->logWrite('count-new data' . (count($newData)));
            //ADD COMMA SEPARATOR AND NEWLINE FOR EACH ORDER
            $new_string = '';
            foreach (array_values($newData) as $key => $row ) {
                foreach ($row as $row_key => $each_element){
                    $new_string .=  $each_element;
                    if($row_key != count($row)-1)
                        $new_string .=  ',';
                }

                $this->logWrite('key-delete' . $key);
               if((count($newData)-1) != $key){
                   $this->logWrite('key-append new line' . $key);
                   $new_string .= "\n";
               }
            }

            $myfile = fopen(self::CSV_FILE_PATH, "w") or die("Unable to open file!");
            fwrite($myfile, $new_string);
            fclose($myfile);

        } catch (Exception $e){
            $this->logWrite('OrderController::updateCSVFile - Error Message - ' . $e->getMessage());
            $this->logWrite('OrderController::updateCSVFile - Error File - ' . $e->getFile());
            $this->logWrite('OrderController::updateCSVFile - Error Line - ' . $e->getLine());
            throw new Exception("Failed to updated csv data");
        }
    }



    /*
        @formDataValidations - input form validations for acceptance
        @postData - request input data
        @errors - append error to this file
    */
    public function formDataValidations($postData, $errors){

        // DEFINE VARIABLES TO EMPTY VALUES
        $nameErr = $itemErr = $amountErr = $qtyErr = $stateErr = $zipErr = "";

        //STRING VALIDATION
        if (empty($postData["name"])) {
            $nameErr = "Name is required";
        } else {
            $name = $postData["name"];

            // CHECK IF NAME ONLY CONTAINS LETTERS AND WHITESPACE
            if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
                $nameErr = "Only alphabets and white space are allowed in name field";
            }
        }

        //APPEND THE ERROR MESSAGE
        if(!empty($nameErr)){
            $errors  .= $nameErr;
        }

        if (empty($postData["item"])) {
            $itemErr = "Item is required";
        } else {

            // CHECK IF ITEM ONLY CONTAINS LETTERS AND WHITESPACE
            if (!preg_match("/^[a-zA-Z ]/",$postData["item"])) {
                $itemErr = "Only alphabets and white space are allowed item field";
            }
        }

        //APPEND THE ERROR MESSAGE
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

            // CHECK IF STATE ONLY CONTAINS LETTERS AND WHITESPACE
            if (!preg_match("/^[a-zA-Z ]/",$postData["state"])) {
                $stateErr = "Only alphabets and white space are allowed state field";
            }
        }

        //APPEND THE ERROR MESSAGE
        if(!empty($stateErr)){
            if(!empty($errors)){
                $errors  .= " , " . $stateErr;
            }  else{
                $errors  .= $stateErr;
            }
        }

        if (empty($postData["zip"])) {
            $zipErr = "Zip is required";
        } else {

            // CHECK IF ZIP ONLY CONTAINS LETTERS AND WHITESPACE
            if (!preg_match("/[0-9]+/", $postData["zip"])) {
                $zipErr = "Only alphabets and white space  and numbers are allowed zip field";
            }
        }

        //APPEND THE ERROR MESSAGE
        if(!empty($zipErr)){
            if(!empty($errors)){
                $errors  .= " , " . $zipErr;
            }  else{
                $errors  .= $zipErr;
            }
        }

        //NUMBER  VALIDATIONS
        if (empty($postData["qty"])) {
            $qtyErr = "Quantity is required";
        } else {

            // IT WILL ALLOW ALL NUMERIC VALUES AND DECIMAL POINTS.
            if (!is_numeric($postData["qty"])){
                $qtyErr = "Only allowed numeric value in qty field";
            }
        }

        //APPEND THE ERROR MESSAGE
        if(!empty($qtyErr)){
            if(!empty($errors)){
                $errors  .= " , " . $qtyErr;
            }  else{
                $errors  .= $qtyErr;
            }
        }


        //DECIMAL VALUE VALIDATIONS
        if (empty($postData["amount"])) {
            $amountErr = "Amount is required";
        } else {
            // IT WILL ALLOW ALL NUMERIC VALUES AND DECIMAL POINTS.
            if (!preg_match('/[0-9.]+/', $postData["amount"])){
                $amountErr = "Only allowed decimal value in amount field";
            }
        }

        //APPEND THE ERROR MESSAGE
        if(!empty($amountErr)){
            if(!empty($errors)){
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

/* @desc OrderController Initialization
 * */
function OrderInit(){

    $order = new OrderController(); //INITIALIZE ORDER CONTROLLER
    $errors = ""; //POST DATA ERRORS
    $postData = json_decode(file_get_contents('php://input'), true); // GET REQUEST INPUT DATA

    //POST OR PUT REQUEST
    if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT') {

        //VALIDATE FORM INPUTS
        $errors =  $order->formDataValidations($postData, $errors);
        if(!empty($errors)){
            echo $errors;
            http_response_code('403');
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //CREATE A NEW ORDER
            $order->newOrderToCSVFile($postData);
        } else if($_SERVER['REQUEST_METHOD'] === 'PUT'){
            //UPDATE ORDER TO CSV FILE
            $order->updateOrderToCSVFile($postData);
        }

    } else if($_SERVER['REQUEST_METHOD'] === 'GET'){
        //GET ALL ORDERS
        $order->getAllOrders();
    } else if($_SERVER['REQUEST_METHOD'] === 'DELETE'){

        if(isset($postData['multiple_order_delete'])){
            //DELETE MULTIPLE THE ORDER
            $order->deleteMultipleOrder($postData);
        } else {
            //DELETE THE ORDER
            $order->deleteOrderFromCsvFile($postData);
        }

    }
}

if(isset($_SERVER['REQUEST_METHOD'])) OrderInit(); // FUNCTION CALL




