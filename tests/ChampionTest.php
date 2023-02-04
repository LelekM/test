<?php


use PHPUnit\Framework\TestCase;

class ChampionTest extends TestCase
{
    public function test_champion_can_take_physical_damage()
    {
        $this->cwel = \App\Models\Champion::addNew('cwel', 100, 200, 200, 5, 5, 5);
        $this->assertInstanceOf(\App\Models\Champion::class, $this->cwel);
        $this->assertEquals(100, $this->cwel->baseHp);
        $this->assertEquals(100, $this->cwel->actualHp);
        $this->cwel->receivePhysicalDamage(150);
        $this->assertEquals(100, $this->cwel->baseHp);
        $this->assertEquals(50, $this->cwel->actualHp);
        $query = \App\Services\Db::get()->prepare("Delete from lelek.champions where name='cwel'");
        $query->execute();
    }
    public function test_champion_can_take_magic_damage()
    {
        $magic = \App\Models\Champion::addNew('Magic', 430,100,120,3,4,5);
        self::assertInstanceOf(\App\Models\Champion::class, $magic);
        self::assertEquals(430, $magic->baseHp);
        self::assertEquals(430, $magic->actualHp);
        $magic->receiveMagicDamage(150);
        self::assertEquals(430, $magic->baseHp);
        self::assertEquals(355, $magic->actualHp);
        $query = \App\Services\Db::get()->prepare("Delete from lelek.champions where name='Magic'");
        $query->execute();
    }
    public function test_champion_can_add_items()
    {
        $this->vayne = \App\Models\Champion::addNew('Vayne', 70, 30, 30, 5, 5, 5);
        $this->assertInstanceOf(\App\Models\Champion::class, $this->vayne);
        $this->sunfire = \App\Models\Item::find('sunfire');
        $this->vayne->addItem($this->sunfire);
        $this->assertCount(1, $this->vayne->items);
        $this->assertEquals(120, $this->vayne->maxHp);
        $this->assertEquals(40, $this->vayne->armor);
        $query = \App\Services\Db::get()->prepare("Delete from lelek.champions where name='Vayne'");
        $query->execute();
    }
    public function test_champion_can_delete_items()
    {
        $this->malfurion = \App\Models\Champion::addNew('Malfurion', 100, 10, 10, 5, 5, 5);
        $this->assertInstanceOf(\App\Models\Champion::class, $this->malfurion);
        $this->randuin = \App\Models\Item::find('randuin');
        $this->malfurion->addItem($this->randuin);
        $this->malfurion->addItem($this->randuin);
        $this->assertCount(2, $this->malfurion->items);
        $this->assertEquals(160, $this->malfurion->maxHp);
        $this->assertEquals(50, $this->malfurion->armor);
        $this->malfurion->deleteItem('randuin');
        $this->assertCount(1, $this->malfurion->items);
        $this->assertEquals(130, $this->malfurion->maxHp);
        $this->assertEquals(30, $this->malfurion->armor);
        $query = \App\Services\Db::get()->prepare("Delete from lelek.champions where name='Malfurion'");
        $query->execute();
    }

    public function test_champion_can_add_level()
    {
        $granny = \App\Models\Champion::addNew('Granny',120,2,21,3,4,5);
        self::assertInstanceOf(\App\Models\Champion::class, $granny);
        $granny->addLevel();
        self::assertEquals(2,$granny->level);
        self::assertEquals(123, $granny->maxHp);
        self::assertEquals(25, $granny->armor);
        self::assertEquals(7, $granny->magicResist);
    }

    public function test_champion_can_set_levels()
    {
        $granny = \App\Models\Champion::addNew('Granny',120,2,21,3,4,5);
        self::assertInstanceOf(\App\Models\Champion::class, $granny);
        $granny->setLevel(16);
        self::assertEquals(165, $granny->maxHp);
        self::assertEquals(81, $granny->armor);
        self::assertEquals(77, $granny->magicResist);
        self::assertEquals(16, $granny->level);
        self::assertEquals(1500, $granny->experience);
        $query = \App\Services\Db::get()->prepare("Delete from lelek.champions where name='Granny'");
        $query->execute();
    }

    public function test_champion_can_add_experience()
    {
        $granny = \App\Models\Champion::addNew('Granny',120,2,21,3,4,5);
        self::assertInstanceOf(\App\Models\Champion::class, $granny);
        $granny->addExperience(560);
        self::assertEquals(6, $granny->level);
        self::assertEquals(560, $granny->experience);
        self::assertEquals(135, $granny->maxHp);
        self::assertEquals(41, $granny->armor);
        self::assertEquals(27, $granny->magicResist);
        $query = \App\Services\Db::get()->prepare("Delete from lelek.champions where name='Granny'");
        $query->execute();
    }
}
