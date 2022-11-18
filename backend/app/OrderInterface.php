<?php

namespace App;
interface OrderInterface {
    function getAllOrders($listRecords);    //list all records from csv file
    function newOrderToCSVFile($inputData);    //add new record to csv file
    function updateOrderToCSVFile($postData);     //update record to csv file
    function deleteOrderFromCsvFile($postData);   //remove record from csv file
}

