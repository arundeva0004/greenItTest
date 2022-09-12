<?php

use PHPUnit\Framework\TestCase;

class CsvFileAddDataTest extends TestCase{

    public function testAdd(){
        $fileContent = new CsvFileContent();
        $result = $fileContent->addNewRowToCSVFile([['id' => 7, 'name' => "Kumaren", 'state' => "kerala", 'zip' => "1000", 'amount' =>  "1000", 'qty' => 1, 'item' => "dev"]]);
    }
}