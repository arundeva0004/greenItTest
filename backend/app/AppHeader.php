<?php

/* SET HEADER CONFIGURATIONS */
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type');
/* THIS WILL ALLOW ANY REQUEST COMES FROM ANY ORIGIN */
header('Access-Control-Allow-Origin: *');
header('application/x-www-form-urlencoded');
/* COLLECT INPUT DATA FROM BROWSER REQUEST AND WHICH TYPE OF CONTENT*/
header('Content-Type: application/json, charset=utf-8');
/*IT WILL ALLOW ANY GET, POST, OR OPTIONS REQUESTS FROM ANY ORIGIN. */
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");