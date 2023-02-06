<?php


use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    public function test_add_new_item()
    {
        $trinity = \App\Models\Item::addNew('Trinity', 100, 30, 5);
        $this->assertInstanceOf(\App\Models\Item::class, $trinity);
        $search = \App\Models\Item::find("Trinity");
        $this->assertEquals(100,$search->hp);
        $this->assertEquals(30,$search->armor);
        $query = \App\Services\Db::get()->prepare("Delete from lelek.items where name='Trinity'");
        $query->execute();
    }

    public function test_find_item()
    {
        $lapis = \App\Models\Item::addNew('Lapis', 50, 10, 0);
        $this->assertEquals(50,$lapis->hp);
        $this->assertEquals(10,$lapis->armor);
        $query = \App\Services\Db::get()->prepare("Delete from lelek.items where name='Lapis'");
        $query->execute();
    }
}
