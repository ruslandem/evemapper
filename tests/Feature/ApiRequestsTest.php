<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiRequestsTest extends TestCase
{
    public function test_get_main_page()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_reject_location_no_auth()
    {
        $response = $this->get('/api/getLocation');
        $response->assertStatus(401);
    }

    public function test_reject_location_history_no_auth()
    {
        $response = $this->get('/api/getLocationsHistory');
        $response->assertStatus(401);
    }

    public function test_reject_route_no_auth()
    {
        $response = $this->post('/api/getRoute');
        $response->assertStatus(401);
    }

    public function test_reject_solar_system_info_no_auth()
    {
        $response = $this->get('/api/getSolarSystemInfo/Jita');
        $response->assertStatus(401);
    }
}
