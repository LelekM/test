<?php

require __DIR__ . '/vendor/autoload.php';

try{
    $db = new PDO("mysql:host=localhost;dbname=lelek", 'baza', '1234');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    echo "Connected successfully";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$mundo = \App\Models\Champion::find($db, 'Mundo');

//$mundo->addItem(["name"=>"sunfire","hp"=>"70","armor"=>"80"]);

//$mundo->receivePhysicalDamage(300);

$sunfire = \App\Models\Item::find($db,'sunfire');

$mundo->addItem($sunfire);

$champions = \App\Models\Champion::getAll($db);
$items = \App\Models\Item::getAll($db);
var_dump($items);