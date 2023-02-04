<?php

namespace App\Models;

use PDO;

class Champion
{
    public static $experienceThreshold = [[0, 99], [100, 199], [200, 299], [300, 399], [400, 499], [500, 599],
        [600, 699], [700, 799], [800, 899], [900, 999], [1000, 1099], [1100, 1199], [1200, 1299],
        [1300, 1399], [1400, 1499], [1500, 1599], [1600, 1699], [1700, 100000]];
    public $name;
    public $actualHp;
    public $maxHp;
    public $baseHp;
    public $hpGrowth;
    public $armor;
    public $armorGrowth;
    public $items = [];
    public $magicResist;
    public $baseMr;
    public $baseArmor;
    public $magicResistGrowth;
    public $level;
    public $experience;

    public function __construct($data)
    {
        $this->name = $data["name"];
        $this->actualHp = $data["hp"];
        $this->baseHp = $data["hp"];
        $this->armor = $data["armor"];
        $this->baseArmor = $data["armor"];
        $this->magicResist = $data["magicResist"];
        $this->baseMr = $data["magicResist"];
        $this->maxHp = $this->baseHp;
        $this->level = 1;
        $this->hpGrowth = $data["hpGrowth"];
        $this->armorGrowth = $data["armorGrowth"];
        $this->magicResistGrowth = $data["magicResistGrowth"];
        $this->experience = 0;
    }

    public static function getAll()
    {
        $query = \App\Services\Db::get()->prepare('select * from lelek.champions;');
        $query->execute();
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $champions = [];
        foreach ($query as $row) {
            $champions [] = $row;
        }
        return $champions;
    }

    public static function addNew(string $name, int $hp, int $magicResist, int $armor, int $hpGrowth, int $armorGrowth, int $magicResistGrowth)

    {
        $query = \App\Services\Db::get()->prepare("insert into lelek.champions (name, hp, magicResist, armor, hpGrowth, armorGrowth, magicResistGrowth) values ('$name', '$hp','$magicResist', '$armor', '$hpGrowth',  '$armorGrowth', '$magicResistGrowth')");
        $query->execute();
        return static::find($name);
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

    public function addItem(Item $item)
    {
        if (count($this->items) > 5) {
            echo "MAX ITEMS";
            return;
        }
        $this->items [] = $item;
        $this->updateStats();
    }

    public function updateStats()
    {
        $bonusHp = 0;
        $bonusArmor = 0;
        $bonusMr = 0;
        foreach ($this->items as $item) {
            $bonusHp += $item->hp;
            $bonusArmor += $item->armor;
            $bonusMr += $item->magicResist;
        }

        $scalingHp = ($this->level - 1) * $this->hpGrowth;
        $scalingArmor = ($this->level - 1) * $this->armorGrowth;
        $scalingMr = ($this->level - 1) * $this->magicResistGrowth;

        $bonusHp += $scalingHp;
        $bonusArmor += $scalingArmor;
        $bonusMr += $scalingMr;

        $hpDiff = $this->maxHp - $this->actualHp;
        $this->maxHp = $this->baseHp + $bonusHp;
        $this->actualHp = $this->maxHp - $hpDiff;
        $this->armor = $this->baseArmor + $bonusArmor;
        $this->magicResist = $this->baseMr + $bonusMr;
    }

    public function deleteItem($name)
    {
        foreach ($this->items as $key => $item) {
            if ($item->name == $name) {
                unset($this->items[$key]);
                $this->updateStats();
                return true;
            }
        }
        return false;
    }

    public function addLevel()
    {
        $this->level = $this->level + 1;
        $this->experience = Champion::$experienceThreshold[$this->level - 1][0];
        $this->updateStats();
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

    public function addExperience(int $experience)
    {
        $this->experience = $this->experience + $experience;
        $this->checkLevel($experience);
    }

    public function checkLevel($experience = null)
    {
        for ($i = 0; $i <= 17; $i++) {
            if ($this->experience >= Champion::$experienceThreshold[$i][0] && $this->experience <= Champion::$experienceThreshold[$i][1]) {
                $this->setLevel(($i + 1), $experience);
                return;
            }
        }
    }

    public function setLevel(int $level, $experience = null)
    {
        $levelDiff = $level - $this->level;
        $this->level = $this->level + $levelDiff;
        if (is_null($experience)) {
            $this->experience = Champion::$experienceThreshold[$this->level - 1][0];
        }
        $this->updateStats();
    }

}