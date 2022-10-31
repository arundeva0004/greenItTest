<?php

namespace App;


class CsvLog{

    const ROUTE_PATH = __DIR__ . "\..\logs";//directory path for logs

        /*
        @desc Write error logs to the file
        @param $fileName name of the given file
        @param $strData data to be appended to the file
        */
    public function Write($fileName, $strData)
    {
        if(!is_writeable(self::ROUTE_PATH.$fileName))
            die('Change your chmod permissions to '. $fileName);
        $handle = fopen(self::ROUTE_PATH.$fileName, 'a');
        fwrite($handle, "\r". $strData);
        fclose($handle);
    }
}