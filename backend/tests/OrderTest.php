<?php

namespace tests;
use App\OrderController;
use PHPUnit\Framework\TestCase;
class OrderTest extends TestCase{

    const CSV_FILE_PATH = __DIR__ . "\..\public";
    public $orderController = null;

    public function  testTrueReturnsTrue() {

        $this->assertTrue(true);
    }

    /* @desc CHECKING FILE EXIST OR NOT */
    public function testFileExist(){
        $this->assertFileExists(self::CSV_FILE_PATH . "\OrderItems.csv");
    }

    /* @desc CHECK GIVEN DIRECTORY IS WRITABLE OR NOT */
    public function testDirectoryIsWritable(){
        $this->assertDirectoryIsWritable(self::CSV_FILE_PATH, "directory path either doesn't exists or not writable");
    }

    /* @desc CHECK GIVEN DIRECTORY IS READABLE OR NOT */
    public function testDirectoryIsReadable(){
        $this->assertDirectoryIsReadable(self::CSV_FILE_PATH,"directory path either doesn't exists or not readable");
    }

    /* @desc GET ALL CSV RECORDS FROM THE CSV FILE */
    public function testGetAllOrders(){

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
                "name" => "Jump Stalin",
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
            ]
            ], $this->getOrderController()->getAllOrders(), "Csv file records doesn't match with specified array");
    }

    /* @desc ADD NEW ORDER TO CSV FILE */
    public function testAddOrder()
    {
        $newOrder = [
            "id" => "6",
            "name" => "Rishabh Pant",
            "state"=> "WA",
            "zip" => "88990",
            "amount" => "987.56",
            "qty" => "1",
            "item" => "TN5125"
        ];
        $this->assertCount(7, $newOrder);
        $this->getOrderController()->newOrderToCSVFile($newOrder);
        $this->assertContains($newOrder, $this->getOrderController()->getAllOrders(),"New data is not contains the csv file");
    }

    /* @desc UPDATE ORDER TO CSV FILE*/
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
        $this->getOrderController()->updateOrderToCSVFile($updateArray);
        $this->assertContains($updateArray, $this->getOrderController()->getAllOrders(),"There was problem while updating your data");
    }

    /* @desc UPDATE THE  DATA FROM CSV FILE */
    public function testRemoveData(){

        $removeOrder = [
            "id" => "6",
            "name" => "Rishabh Pant",
            "state"=> "WA",
            "zip" => "88990",
            "amount" => "987.56",
            "qty" => "1",
            "item" => "TN5125"
        ];

        $this->assertIsArray($removeOrder);
        $result = $this->getOrderController()->deleteOrderFromCsvFile($removeOrder);
        $this->assertJson($result);
        $this->assertNotContains($removeOrder, $this->getOrderController()->getAllOrders(),"There was problem removing your data");
    }

    /* @desc VALIDATE THE POST DATA */
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

        $this->assertEmpty($this->getOrderController()->formDataValidations($postData, ""), "Your post data is not valid");

    }

    /* @desc  SET ORDER CONTROLLER*/
    public function getOrderController(): OrderController
    {
        if($this->orderController == null){
            $this->orderController = new OrderController();
        }
        return $this->orderController;
    }
}
