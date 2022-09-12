<?php


interface CsvDataInterface{
    function getAllCsvRecords($listRecords);    //list all records from csv file
    function addNewRowToCSVFile($inputData);  //add new record to csv file
    function updateRowToCSVFile();  //update record to csv file
    function removeRowFromCsvFile();//remove record from csv file
}