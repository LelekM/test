<?php
namespace App\Models;
use PDO;

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

    public function addItem(Item $item)
    {
        if (count($this->items) > 5) {
            echo "MAX ITEMOW";
            return;
        }
        $this->items [] = $item;
        $this->addHp($item->hp); //do wyjebania, dodac funkcje co podlicza ile mam aktualnie max hp, odpalana po dodaniu itemu albo odjeciu
        $this->addArmor($item->armor); // same situation
    }

    public function deleteItem($name)
    {
        foreach ($this->items as $key=>$item)
        {
            if ($item->name == $name )
            {
                $this->decreaseArmor($item->armor);
                $this->decreaseHp($item->hp);
                unset($this->items[$key]);
                return true;
            }
        }
        return false;
    }
    public function receivePhysicalDamage(int $dmg)
    {
        $dmgReduction = $this->armor / ($this->armor + 100);
//        var_dump(round($dmgReduction, 2));
        $this->actualHp = $this->actualHp - ($dmg - round(($dmg * $dmgReduction), 0));
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

    public static function addNew(string $name, int $hp, int $armor)
    {
        $query = \App\Services\Db::get()->prepare("insert into lelek.champions (name, hp, armor) values ('$name', '$hp', '$armor')");
        $query->execute();
        return static::find($name);
}

}