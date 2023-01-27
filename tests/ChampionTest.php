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
    }
    public function test_champion_can_take_magic_damage()
    {
        $mundo = \App\Models\Champion::find("mundo");
        self::assertInstanceOf(\App\Models\Champion::class, $mundo);
        self::assertEquals(430, $mundo->baseHp);
        self::assertEquals(430, $mundo->actualHp);
        $mundo->receiveMagicDamage(150);
        self::assertEquals(430, $mundo->baseHp);
        self::assertEquals(355, $mundo->actualHp);
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
    }

    public function test_champion_can_add_level()
    {
        $annie = \App\Models\Champion::find("Annie");
        self::assertInstanceOf(\App\Models\Champion::class, $annie);
        $annie->addLevel();
        self::assertEquals(2,$annie->level);
        self::assertEquals(123, $annie->maxHp);
        self::assertEquals(25, $annie->armor);
        self::assertEquals(7, $annie->magicResist);
    }

    public function test_champion_can_set_levels()
    {
        $annie = \App\Models\Champion::find("Annie");
        self::assertInstanceOf(\App\Models\Champion::class, $annie);
        $annie->setLevel(16);
        self::assertEquals(165, $annie->maxHp);
        self::assertEquals(81, $annie->armor);
        self::assertEquals(77, $annie->magicResist);
    }
}
