<?php

require __DIR__ . '/vendor/autoload.php';

$db = \App\Services\Db::get();

$mundo = \App\Models\Champion::find('Mundo');
$mundo->addExperience(560);
var_dump($mundo);

