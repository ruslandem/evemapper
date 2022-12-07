<?php

namespace Tests\Unit;

use App\Models\Signature;
use App\Models\User;
use Tests\TestCase;
use App\Services\CosmicSignatures;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class CosmicSignaturesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $user;

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected $signatures;


    public function setUp(): void
    {
        parent::setUp();

        User::query()->delete();
        Signature::query()->delete();

        $this->user = User::factory()->createOne();

        $this->signatures = Signature::factory()->count(10)->create([
            'characterId' => $this->user->characterId
        ]);
    }

    public function test_delete_signature_from_database()
    {
        $service = new CosmicSignatures();

        $signature = $this->signatures->first();

        $deleted = $service->deleteSignature(
            $this->user->characterId,
            $signature->solarSystemName,
            $signature->signatureId,
        );

        $this->assertEquals(1, $deleted);

        $found = Signature::query()->where([
            'characterId' => $this->user->characterId,
            'solarSystemName' => $signature->solarSystemName,
            'signatureId' => $signature->signatureId,
        ])->get();

        $this->assertEquals(0, $found->count());
    }

    public function test_delete_nonexisting_signature()
    {
        $service = new CosmicSignatures();

        $signature = $this->signatures->first();

        $service->deleteSignature(
            $this->user->characterId,
            $signature->solarSystemName,
            $signature->signatureId,
        );

        $deleted = $service->deleteSignature(
            $this->user->characterId,
            $signature->solarSystemName,
            $signature->signatureId,
        );

        $this->assertEquals(0, $deleted);
    }

    // Sample clipboard text:
    // 
    // ISN-720	Cosmic Signature			0.0%	2.58 AU
    // KOL-024	Cosmic Signature			0.0%	3.73 AU
    // QPI-926	Cosmic Signature			0.0%	6.78 AU
    // ROJ-096	Cosmic Signature	Regional Guristas Command Center	Data Site   0.0%	29.53 AU
    // XCO-255	Cosmic Signature			0.0%	1.79 AU
    // KOL-024	Cosmic Signature	Regional Guristas Command Center	Data Site	0.0%	1.79 AU
}
