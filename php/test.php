<?php

require_once('./ShittyDB.php');

$db = new ShittyDB;
$db->set('test', 'PHP is the best programming language evar');
echo $db->get('test');

echo "<hr />";
echo "Expireable Test:<br /><br />";

/*
if(!$db->exists('expires')) {
    $db->set('expires', 'Some test data that expires in 10 seconds', 10);
}
if(!$db->exists('expires2')) {
    $db->set('expires2', 'Some other data set to expire in 15 seconds');
    $db->ttl('expires2', 15);
}
var_dump($db->exists('expires'));
*/
$db->exists('expires');
$db->exists('expires2');
