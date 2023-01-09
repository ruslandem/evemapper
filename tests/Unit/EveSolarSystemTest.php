<?php

namespace Tests\Unit;

use App\Core\EveSolarSystem;
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
}
