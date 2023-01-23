<?php

require __DIR__ . '/vendor/autoload.php';

$db = \App\Services\Db::get();

$mundo = \App\Models\Champion::find('Mundo');

//$mundo->addItem(["name"=>"sunfire","hp"=>"70","armor"=>"80"]);

//$mundo->receivePhysicalDamage(300);

$sunfire = \App\Models\Item::find('sunfire');

$mundo->addItem($sunfire);

$champions = \App\Models\Champion::getAll();
//$items = \App\Models\Item::getAll($db);
//var_dump($items);

var_dump($mundo);
