<?php

/* Set header configurations */
header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type');
/* This will allow any request comes from any origin */
header('Access-Control-Allow-Origin: *');
header('application/x-www-form-urlencoded');
/* Collect input data from browser request and which type of content*/
header('Content-Type: application/json, charset=utf-8');
/*It will allow any GET, POST, or OPTIONS requests from any origin. */
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

