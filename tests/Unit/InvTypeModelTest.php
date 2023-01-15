<?php

namespace Tests\Unit;

use App\Models\InvType;
use Tests\TestCase;

class InvTypeModelTest extends TestCase
{
    public function test_find_inventory_item()
    {
        $expected = [
            'typeID' => 17716,
            'groupID' => 106,
            'typeName' => 'Gila Blueprint',
            'mass' => 0.0,
            'volume' => 0.01,
            'capacity' => 0.0,
            'basePrice' => null,
        ];

        $item = InvType::find($expected['typeID']);

        foreach ($expected as $key => $value) {
            $this->assertSame($value, $item->$key);
        }

        $this->assertTrue(count($item->materials) > 0);
    }

    public function test_find_unexistent_inventory_item()
    {
        $item = InvType::find(999999999);

        $this->assertNull($item);
    }

    public function test_materials_of_item_without_blueprint()
    {
        // 34 - Veldspar: doesn't have a blueprint
        $item = InvType::find(34);

        $this->assertEmpty($item->materials);
    }
}
