<?php


use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    public function test_add_new_item()
    {
        $trinity = \App\Models\Item::addNew('Trinity', 100, 30);
        $this->assertInstanceOf(\App\Models\Item::class, $trinity);
        $search = \App\Models\Item::find("Trinity");
        $this->assertEquals(100,$search->hp);
        $this->assertEquals(30,$search->armor);
    }

    public function test_find_item()
    {
        $search = \App\Models\Item::find("sunfire");
        $this->assertEquals(50,$search->hp);
        $this->assertEquals(10,$search->armor);
    }
}
