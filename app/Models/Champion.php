<?php
namespace App\Models;
class Champion
{
    public $name;
    public $actualHp;
    public $maxHp;
    public $baseHp;
    public $armor;
    public $items = [];
    public function __construct($data)
    {
        $this->name = $data["name"];
        $this->actualHp = $data["hp"];
        $this->baseHp = $data["hp"];
        $this->armor = $data["armor"];
        $this->maxHp = $this->baseHp;
    }

    public function addItem($data)
    {
        if (count($this->items) > 5) {
            echo "MAX ITEMOW";
            return;
        }
        $this->items [] = $data;
        $this->addHp($data["hp"]); //do wyjebania, dodac funkcje co podlicza ile mam aktualnie max hp, odpalana po dodaniu itemu albo odjeciu
        $this->addArmor($data["armor"]); // same situation
    }

    public function deleteItem($name)
    {
        foreach ($this->items as $key=>$item)
        {
            if ($item["name"] == $name )
            {
                $this->decreaseArmor($item["armor"]);
                $this->decreaseHp($item["hp"]);
                unset($this->items[$key]);
                return true;
            }
        }
        return false;
    }
    public function receivePhysicalDamage($dmg)
    {
        $dmgReduction = $this->armor / ($this->armor + 100);
//        var_dump(round($dmgReduction, 2));
        $this->actualHp = $this->actualHp - ($dmg - round(($dmg * round($dmgReduction, 2)), 0));
    }

    public function addHp($data)
    {
        $this->actualHp = $this->actualHp + $data;
        $this->maxHp = $this->maxHp + $data;
    }
    public function addArmor($data)
    {
        $this->armor = $this->armor + $data;
    }

    public function decreaseHp($data)
    {
        $this->actualHp = $this->actualHp - $data;
        $this->maxHp = $this->maxHp - $data;
    }
    public function decreaseArmor($data)
    {
        $this->armor = $this->armor - $data;
    }

}