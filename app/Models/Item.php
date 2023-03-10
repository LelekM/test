<?php

namespace App\Models;

use PDO;

class Item
{
    public $name;
    public $hp;
    public $armor;
    public function __Construct($data)
    {
        $this->name = $data['name'];
        $this->hp = $data['hp'];
        $this->armor = $data['armor'];
    }

    public static function find($name)
    {
        $query = \App\Services\Db::get()->prepare("select * from lelek.items where name ='$name';");
        $query->execute();
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $data = $query->fetchAll()[0];
        $item = new Item($data);
        return $item;
    }

    public static function getAll()
    {
        $query = \App\Services\Db::get()->prepare("select * from lelek.items");
        $query->execute();
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $items = [];
        foreach ($query as $row){
            $items []= $row;
        }
        return $items;
    }
    public static function addNew(string $name, int $hp, int $armor)
    {
        $query = \App\Services\Db::get()->prepare("insert into lelek.items (name, hp, armor) values ('$name', '$hp', '$armor')");
        $query->execute();
        return static::find($name);
    }
}