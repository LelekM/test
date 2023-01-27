<?php
namespace App\Models;
use PDO;

class Champion
{
    public $name;
    public $actualHp;
    public $maxHp;
    public $baseHp;
    public $hpGrowth;
    public $armor;
    public $armorGrowth;
    public $items = [];
    public $magicResist;
    public $magicResistGrowth;
    public $level;

    public function __construct($data)
    {
        $this->name = $data["name"];
        $this->actualHp = $data["hp"];
        $this->baseHp = $data["hp"];
        $this->armor = $data["armor"];
        $this->magicResist = $data["magicResist"];
        $this->maxHp = $this->baseHp;
        $this->level = 1;
        $this->hpGrowth = $data["hpGrowth"];
        $this->armorGrowth = $data["armorGrowth"];
        $this->magicResistGrowth = $data["magicResistGrowth"];
    }

    public function addItem(Item $item)
    {
        if (count($this->items) > 5) {
            echo "MAX ITEMOW";
            return;
        }
        $this->items [] = $item;
        $this->addHp($item->hp); //do wyjebania, dodac funkcje co podlicza ile mam aktualnie max hp, odpalana po dodaniu itemu albo odjeciu
        $this->addArmor($item->armor); // same situation
        $this->addMagicResist($item->magicResist);
    }

    public function deleteItem($name)
    {
        foreach ($this->items as $key=>$item)
        {
            if ($item->name == $name )
            {
                $this->decreaseArmor($item->armor);
                $this->decreaseHp($item->hp);
                $this->decreaseMagicResist($item->magicResist);
                unset($this->items[$key]);
                return true;
            }
        }
        return false;
    }

    public function addLevel()
    {
        $this->level = $this->level + 1;
        $armor = $this->armorGrowth * 1;
        $magicResist = $this->magicResistGrowth *1;
        $hp = $this->hpGrowth *1;
        $this->addArmor($armor);
        $this->addMagicResist($magicResist);
        $this->addHp($hp);
    }

    public function setLevel(int $level)
    {
        $level = $level - $this->level;
        $armor = $this->armorGrowth * $level;
        $magicResist = $this->magicResistGrowth * $level;
        $hp = $this->hpGrowth * $level;
        $this->addArmor($armor);
        $this->addMagicResist($magicResist);
        $this->addHp($hp);
        $this->level = $this->level - $level;
    }

    public function receivePhysicalDamage(int $dmg)
    {
        $dmgReduction = $this->armor / ($this->armor + 100);
        $this->actualHp = $this->actualHp - ($dmg - round(($dmg * $dmgReduction), 0));
    }

    public function receiveMagicDamage(int $dmg)
    {
        $dmgReduction = $this->magicResist / ($this->magicResist + 100);
        $this->actualHp = $this->actualHp - ($dmg - round(($dmg * $dmgReduction), 0));
    }

    public function addHp($hp)
    {
        $this->actualHp = $this->actualHp + $hp;
        $this->maxHp = $this->maxHp + $hp;
    }
    public function addArmor($armor)
    {
        $this->armor = $this->armor + $armor;
    }

    public function addMagicResist($mr)
    {
        $this->magicResist = $this->magicResist + $mr;
    }

    public function decreaseHp($hp)
    {
        $this->actualHp = $this->actualHp - $hp;
        $this->maxHp = $this->maxHp - $hp;
    }
    public function decreaseArmor($armor)
    {
        $this->armor = $this->armor - $armor;
    }

    public function decreaseMagicResist($mr)
    {
        $this->magicResist = $this->magicResist - $mr;
    }

    public static function getAll()
    {
        $query = \App\Services\Db::get()->prepare('select * from lelek.champions;');
        $query->execute();
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $champions = [];
        foreach ($query as $row){
            $champions []= $row;
        }
        return $champions;
}
    public static function find($name)
    {
        $query = \App\Services\Db::get()->prepare("select * from lelek.champions where name ='$name';");
        $query->execute();
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $data = $query->fetchAll()[0];
        $champion = new \App\Models\Champion($data);
        return $champion;
    }

    public static function addNew(string $name, int $hp,int $magicResist, int $armor, int $hpGrowth, int $armorGrowth, int $magicResistGrowth )
    {
        $query = \App\Services\Db::get()->prepare("insert into lelek.champions (name, hp, magicResist, armor, hpGrowth, armorGrowth, magicResistGrowth) values ('$name', '$hp','$magicResist', '$armor', '$hpGrowth',  '$armorGrowth', '$magicResistGrowth')");
        $query->execute();
        return static::find($name);
}

}