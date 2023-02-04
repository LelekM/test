<?php


use PHPUnit\Framework\TestCase;

class ChampionTest extends TestCase
{
    public function test_champion_can_take_physical_damage()
    {
        $champion = \App\Models\Champion::addNew('cwel', 100, 200, 200, 5, 5, 5);
        $this->assertInstanceOf(\App\Models\Champion::class, $champion);
        $this->assertEquals(100, $champion->baseHp);
        $this->assertEquals(100, $champion->actualHp);
        $champion->receivePhysicalDamage(150);
        $this->assertEquals(100, $champion->baseHp);
        $this->assertEquals(50, $champion->actualHp);
    }

    public function test_champion_can_take_magic_damage()
    {
        $champion = \App\Models\Champion::addNew('Magic', 430, 100, 120, 3, 4, 5);
        self::assertInstanceOf(\App\Models\Champion::class, $champion);
        self::assertEquals(430, $champion->baseHp);
        self::assertEquals(430, $champion->actualHp);
        $champion->receiveMagicDamage(150);
        self::assertEquals(430, $champion->baseHp);
        self::assertEquals(355, $champion->actualHp);
    }

    public function test_champion_can_add_items()
    {
        $champion = \App\Models\Champion::addNew('Vayne', 70, 30, 30, 5, 5, 5);
        $this->assertInstanceOf(\App\Models\Champion::class, $champion);
        $item1 = \App\Models\Item::addNew('test', 50, 10, 0);
        $champion->addItem($item1);
        $this->assertCount(1, $champion->items);
        $this->assertEquals(120, $champion->maxHp);
        $this->assertEquals(40, $champion->armor);
    }

    public function test_champion_can_delete_items()
    {
        $champion = \App\Models\Champion::addNew('Malfurion', 100, 10, 10, 5, 5, 5);
        $this->assertInstanceOf(\App\Models\Champion::class, $champion);
        $item2 = \App\Models\Item::addNew('test', 30, 20, 5);
        $champion->addItem($item2);
        $champion->addItem($item2);
        $this->assertCount(2, $champion->items);
        $this->assertEquals(160, $champion->maxHp);
        $this->assertEquals(50, $champion->armor);
        $champion->deleteItem($item2->name);
        $this->assertCount(1, $champion->items);
        $this->assertEquals(130, $champion->maxHp);
        $this->assertEquals(30, $champion->armor);
    }

    public function test_champion_can_add_level()
    {
        $champion = \App\Models\Champion::addNew('Granny', 120, 2, 21, 3, 4, 5);
        self::assertInstanceOf(\App\Models\Champion::class, $champion);
        $champion->addLevel();
        self::assertEquals(2, $champion->level);
        self::assertEquals(123, $champion->maxHp);
        self::assertEquals(25, $champion->armor);
        self::assertEquals(7, $champion->magicResist);
    }

    public function test_champion_can_set_levels()
    {
        $champion = \App\Models\Champion::addNew('Granny', 120, 2, 21, 3, 4, 5);
        self::assertInstanceOf(\App\Models\Champion::class, $champion);
        $champion->setLevel(16);
        self::assertEquals(165, $champion->maxHp);
        self::assertEquals(81, $champion->armor);
        self::assertEquals(77, $champion->magicResist);
        self::assertEquals(16, $champion->level);
        self::assertEquals(1500, $champion->experience);
    }

    public function test_champion_can_add_experience()
    {
        $champion = \App\Models\Champion::addNew('Granny', 120, 2, 21, 3, 4, 5);
        self::assertInstanceOf(\App\Models\Champion::class, $champion);
        $champion->addExperience(560);
        self::assertEquals(6, $champion->level);
        self::assertEquals(560, $champion->experience);
        self::assertEquals(135, $champion->maxHp);
        self::assertEquals(41, $champion->armor);
        self::assertEquals(27, $champion->magicResist);
    }

    public function test_champion_with_items_and_level()
    {
        $championName = 'Granny';
        $baseHp = 120;
        $champion = \App\Models\Champion::addNew($championName, $baseHp, 2, 25, 3, 4, 5);
        self::assertInstanceOf(\App\Models\Champion::class, $champion);
        self::assertEquals($baseHp, $champion->actualHp);
        self::assertEquals($baseHp, $champion->maxHp);
        $champion->receivePhysicalDamage(120);
        $hpDiff = $baseHp - $champion->actualHp;
        $expectedDiff = 96;
        self::assertEquals($expectedDiff, $hpDiff);
        self::assertEquals($baseHp - $hpDiff, $champion->actualHp);
        self::assertEquals($baseHp, $champion->maxHp);

        $item3 = \App\Models\Item::addNew('test', 100, 45, 15);
        $item2 = \App\Models\Item::addNew('test2', 30, 20, 5);
        $item1 = \App\Models\Item::addNew('test3', 50, 10, 0);
        $champion->addItem($item3);
        $champion->addItem($item2);
        $champion->addItem($item1);

        self::assertEquals($baseHp + 180, $champion->maxHp);
        self::assertEquals($champion->maxHp - $hpDiff, $champion->actualHp);
    }

    public function tearDown(): void
    {
        $query = \App\Services\Db::get()->prepare("Delete from lelek.champions where 1=1");
        $query->execute();
        $query = \App\Services\Db::get()->prepare("Delete from lelek.items where 1=1");
        $query->execute();
        parent::tearDown(); // TODO: Change the autogenerated stub
    }
}
