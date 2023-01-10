<?php

namespace Tests\Unit;

use App\Core\EveSolarSystem;
use App\Core\Exceptions\EveApiException;
use Tests\TestCase;

class EveSolarSystemTest extends TestCase
{
    /**
     * Check for correct determination of solar system being a wormhole
     * @return void
     */
    public function test_is_system_a_wormhole()
    {
        // Correct wormhole should be Jnnnnnn or Thera
        $this->assertTrue(EveSolarSystem::isWormholeSystem('J123456'));
        $this->assertTrue(EveSolarSystem::isWormholeSystem('Thera'));
        $this->assertFalse(EveSolarSystem::isWormholeSystem('j123456'));
        $this->assertFalse(EveSolarSystem::isWormholeSystem('J12E45A'));
        $this->assertFalse(EveSolarSystem::isWormholeSystem('123456J'));
        $this->assertFalse(EveSolarSystem::isWormholeSystem('123J456789'));
        $this->assertFalse(EveSolarSystem::isWormholeSystem('JAAAAAA'));
    }

    public function test_get_solar_system_by_id()
    {
        $system = EveSolarSystem::getById(30000142);
        $this->assertEquals('Jita', $system->solarSystemName);
        $this->assertEquals('The Forge', $system->regionName);
        $this->assertEquals('Kimotoro', $system->constellationName);
        $this->assertEquals('Guristas', $system->rats);
        $this->assertEquals(0.9, round($system->security, 1));
    }

    public function test_get_solar_system_with_invalid_id()
    {
        $this->expectException(EveApiException::class);

        EveSolarSystem::getById(9999999999);
    }

    public function test_get_solar_system_by_name()
    {
        $system = EveSolarSystem::getByName('Jita');
        $this->assertEquals(30000142, $system->solarSystemID);
        $this->assertEquals('The Forge', $system->regionName);
        $this->assertEquals('Kimotoro', $system->constellationName);
        $this->assertEquals('Guristas', $system->rats);
        $this->assertEquals(0.9, round($system->security, 1));
    }

    public function test_get_solar_system_with_invalid_name()
    {
        $this->expectException(EveApiException::class);

        EveSolarSystem::getByName('ABCDE');
    }
}
