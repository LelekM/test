<?php
namespace App\Models;
class Champion
{
    public $name;
    public $hp;
    public $armor;
    public $items = [];
    public function __construct($data)
    {
        $this->name = $data["name"];
        $this->hp = $data["hp"];
        $this->armor = $data["armor"];
    }

    public function addItem($data)
    {
        if (count($this->items) > 6) {
            echo "MAX ITEMOW";
            return;
        }
        $this->items [] = $data;
        $this->hp = $this->hp + $data["hp"]; //do wyjebania, dodac funkcje co podlicza ile mam aktualnie max hp, odpalana po dodaniu itemu albo odjeciu
        $this->armor = $this->armor + $data["armor"]; // same situation
    }

    public function deleteItem($name)
    {
        foreach ($this->items as $key=>$item)
        {
            if ($item["name"] == $name )
            {
                unset($this->items[$key]);
                return true;
            }
        }
        return false;
    }
    public function receivePhysicalDamage($dmg)
    {

    }
}