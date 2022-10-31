<?php

namespace App;
interface CsvDataInterface {
    function getAllCsvRecords($listRecords);    //list all records from csv file
    function addNewRowToCSVFile($inputData);    //add new record to csv file
    function updateRowToCSVFile($postData);     //update record to csv file
    function removeRowFromCsvFile($postData);   //remove record from csv file
}

