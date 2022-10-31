<?php

namespace tests;
use App\CsvFileContent;
use PHPUnit\Framework\TestCase;
class CsvFileTest extends TestCase{

    const CSV_FILE_PATH = __DIR__ . "\..\public";
    public $fileContent = null;

    public function  testTrueReturnsTrue() {

        $this->assertTrue(true);
    }

    /*Checking file exist or not */
    public function testFileExist(){
        $this->assertFileExists(self::CSV_FILE_PATH . "\CsvItems.csv");
    }

    /*Check given directory is writable or not */
    public function testDirectoryIsWritable(){
        $this->assertDirectoryIsWritable(self::CSV_FILE_PATH, "directory path either doesn't exists or not writable");
    }

    /*Check given directory is readable or not */
    public function testDirectoryIsReadable(){
        $this->assertDirectoryIsReadable(self::CSV_FILE_PATH,"directory path either doesn't exists or not readable");
    }

    /*get all csv records from the csv file*/
    public function testGetCsvData(){

        $this->assertEquals([
            [
                "id" => "1",
                "name" => "Liquid Saffron",
                "state"=> "NY",
                "zip" => "08998",
                "amount" => "25.43",
                "qty" => "7",
                "item" => "XCD45300"
            ],
            [
                "id" => "2",
                "name" => "Mostly Slugs",
                "state"=> "PA",
                "zip" => "19008",
                "amount" => "13.30",
                "qty" => "2",
                "item" => "AAH6748"
            ],

            [
                "id" => "3",
                "name" => "Jump Stain",
                "state"=> "CA",
                "zip" => "99388",
                "amount" => "56.00",
                "qty" => "3",
                "item" => "MKII4400"
            ],
            [
                "id" => "4",
                "name" => "Scheckled Sherlock",
                "state"=> "WA",
                "zip" => "88990",
                "amount" => "987.56",
                "qty" => "1",
                "item" => "TR909"
            ],
            [
                "id" => "5",
                "name" => "Virat Kohli",
                "state"=> "WA",
                "zip" => "88990",
                "amount" => "987.56",
                "qty" => "1",
                "item" => "TR909"
            ]
            ], $this->getCsvFileContent()->getAllCsvRecords(), "Csv file records doesn't match with specified array");
    }

    /*Add new data to csv file */
    public function testAddNewData()
    {
        $newArray = [
            "id" => "6",
            "name" => "Rishabh Pant",
            "state"=> "WA",
            "zip" => "88990",
            "amount" => "987.56",
            "qty" => "1",
            "item" => "TN5125"
        ];
        $this->assertCount(7, $newArray);
        $this->getCsvFileContent()->addNewRowToCSVFile($newArray);
        $this->assertContains($newArray, $this->getCsvFileContent()->getAllCsvRecords(),"New data is not contains the csv file");
    }

    /*update the csv data*/
    public function testUpdateData(){
        $updateArray = [
            "id" => "3",
            "name" => "Jump Update Stalin",
            "state"=> "CA",
            "zip" => "99388",
            "amount" => "56.00",
            "qty" => "3",
            "item" => "MKII4400"
        ];
        $this->getCsvFileContent()->updateRowToCSVFile($updateArray);
        $this->assertContains($updateArray, $this->getCsvFileContent()->getAllCsvRecords(),"There was problem while updating your data");
    }

    /*update the  data from csv file */
    public function testRemoveData(){

        $removeArray = [
            "id" => "6",
            "name" => "Rishabh Pant",
            "state"=> "WA",
            "zip" => "88990",
            "amount" => "987.56",
            "qty" => "1",
            "item" => "TN5125"
        ];

        $this->assertIsArray($removeArray);
        $result = $this->getCsvFileContent()->removeRowFromCsvFile($removeArray);
        $this->assertJson($result);
        $this->assertNotContains($removeArray, $this->getCsvFileContent()->getAllCsvRecords(),"There was problem removing your data");
    }

    /*validate the post data */
    public function testPostDataValidate(){
        $postData = [
            "id" => "7",
            "name" => "Virat Rohit",
            "state"=> "WA",
            "zip" => "88990",
            "amount" => "1200.00",
            "qty" => "1",
            "item" => "TR909"
        ];

        $this->assertEmpty($this->getCsvFileContent()->formDataValidations($postData, ""), "Your post data is not valid");

    }

    /*create object only one time and return */
    public function getCsvFileContent(){
        if($this->fileContent == null){
            $this->fileContent = new CsvFileContent();
        }
        return $this->fileContent;
    }
}
