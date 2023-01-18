<?php

require __DIR__ . '/vendor/autoload.php';

try{
    $db = new PDO("mysql:host=localhost;dbname=lelek", 'baza', '1234');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    echo "Connected successfully";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$query = $db->prepare('select * from lelek.champions;');
$query->execute();
$result = $query->setFetchMode(PDO::FETCH_ASSOC);
$data = $query->fetchAll()[0];
$annie = new \App\Models\Champion($data);

//var_dump($annie);

$annie->addItem(["name"=>"sunfire","hp"=>"50","armor"=>"10"]);
$annie->addItem(["name"=>"sunfire","hp"=>"50","armor"=>"10"]);
$annie->addItem(["name"=>"sunfire","hp"=>"50","armor"=>"10"]);
$annie->addItem(["name"=>"sunfire","hp"=>"50","armor"=>"10"]);
$annie->addItem(["name"=>"sunfire","hp"=>"50","armor"=>"10"]);
$annie->addItem(["name"=>"sunfire","hp"=>"50","armor"=>"10"]);
$annie->addItem(["name"=>"sunfire","hp"=>"50","armor"=>"10"]);
$annie->addItem(["name"=>"randuin","hp"=>"20","armor"=>"150"]);

var_dump($annie);