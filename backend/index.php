<?php


class CsvFileContent{
    public function __construct(){
            include "header.php";
    }

    public function fetchCsvFileData(){
        include "load_csv_data.php";
    }
}

function loadCsvData(){
    $file = new CsvFileContent();
    $file->fetchCsvFileData();
}

loadCsvData();