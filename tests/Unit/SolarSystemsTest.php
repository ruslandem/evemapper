<?php

namespace Tests\Unit;

use App\Core\EveSolarSystem;
use App\Core\Exceptions\EveApiException;
use App\Services\SolarSystems;
use Tests\TestCase;

class SolarSystemsTest extends TestCase
{
    public function test_is_system_a_wormhole(): void
    {
        $this->assertNotNull(SolarSystems::getByName('Thera')->wormhole);
        $this->assertNull(SolarSystems::getByName('Jita')->wormhole);
        $this->assertNotNull(SolarSystems::getByName('J122717')->wormhole);
    }

    public function test_get_solar_system_by_id(): void
    {
        $item = SolarSystems::getById(30000142);
        $this->assertEquals('Jita', $item->solarSystemName);
        $this->assertEquals('The Forge', $item->region->regionName);
        $this->assertEquals('Kimotoro', $item->constellation->constellationName);
        $this->assertEquals('Guristas', $item->region->rats->rats);
        $this->assertEquals(0.9, round($item->security, 1));
    }

    public function test_get_solar_system_by_name(): void
    {
        $this->assertEquals(
            SolarSystems::getById(30000142),
            SolarSystems::getByName('Jita')
        );
    }

    public function test_get_solar_system_with_invalid_id(): void
    {
        $this->expectException(EveApiException::class);

        EveSolarSystem::getById(9999999999);
    }

    public function test_get_solar_system_with_invalid_name(): void
    {
        $this->expectException(EveApiException::class);

        EveSolarSystem::getByName('ABCDE');
    }
}
