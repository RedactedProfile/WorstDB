<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use TheWorst\WorstDB;

$db = new WorstDB(__DIR__ . '/../db/');

$db->set('test', 'hi there');
$db->set('test2', 'hello again', 50);
