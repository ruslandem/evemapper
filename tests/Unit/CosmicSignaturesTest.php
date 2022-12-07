<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Signature;
use App\Models\User;
use App\Services\CosmicSignatures;

class CosmicSignaturesTest extends TestCase
{
    use RefreshDatabase;

    public const SOLAR_SYSTEM = 'Amarr';
    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable
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
            'characterId' => $this->user->characterId,
            'solarSystemName' => self::SOLAR_SYSTEM,
        ]);
    }

    public function test_request_to_get_signatures()
    {
        $response = $this->actingAs($this->user)
            ->getJson(route('api.getSignatures') . '?system=' . self::SOLAR_SYSTEM);

        $response->assertStatus(200)->assertJsonCount(10, 'data');
    }

    public function test_request_to_get_signatures_as_unauthenticated_user()
    {
        $response = $this->getJson(route('api.getSignatures') . '?system=' . self::SOLAR_SYSTEM);

        $response->assertStatus(401);
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

    public function test_insert_new_signatures_from_text()
    {
        // deleting signatures before insertion
        Signature::query()->delete();

        $service = new CosmicSignatures();
        $service->updateFromClipboardText(
            $this->user->characterId,
            self::SOLAR_SYSTEM,
            $this->getSampleSignaturesClipboardText(),
            false
        );

        $inserted = Signature::all();

        $this->assertCount(6, $inserted);

        $this->assertDatabaseHas(Signature::class, ['signatureId' => 'ISN-720']);
        $this->assertDatabaseHas(Signature::class, ['signatureId' => 'KOA-124']);
        $this->assertDatabaseHas(Signature::class, ['signatureId' => 'QPI-926']);
        $this->assertDatabaseHas(Signature::class, ['signatureId' => 'ROJ-096']);
        $this->assertDatabaseHas(Signature::class, ['signatureId' => 'XCO-255']);
        $this->assertDatabaseHas(Signature::class, ['signatureId' => 'KOL-024']);
    }

    public function test_update_signature_with_more_data()
    {
        // deleting signatures before insertion
        Signature::query()->delete();
        // seeding sample signatures
        $service = new CosmicSignatures();
        $service->updateFromClipboardText(
            $this->user->characterId,
            self::SOLAR_SYSTEM,
            $this->getSampleSignaturesClipboardText(),
            false
        );

        // updating with a signature with more data
        $text = "KOA-124	Cosmic Signature	Data Site	Regional Guristas Command Center	0.0%	23.13 AU";
        $service->updateFromClipboardText(
            $this->user->characterId,
            self::SOLAR_SYSTEM,
            $text,
            false
        );

        $result = Signature::where([
            'characterId' => $this->user->characterId,
            'solarSystemName' => self::SOLAR_SYSTEM,
            'signatureId' => 'KOA-124'
        ])->first();

        $this->assertNotNull($result);

        $this->assertEquals([
            'signatureId' => 'KOA-124',
            'signatureName' => 'Regional Guristas Command Center',
            'groupName' => 'Data Site'
        ], [
            'signatureId' => $result->signatureId,
            'signatureName' => $result->signatureName,
            'groupName' => $result->groupName,
        ]);
    }

    public function test_update_signature_with_less_data()
    {
        // deleting signatures before insertion
        Signature::query()->delete();
        // seeding sample signatures
        $service = new CosmicSignatures();
        $service->updateFromClipboardText(
            $this->user->characterId,
            self::SOLAR_SYSTEM,
            $this->getSampleSignaturesClipboardText(),
            false
        );

        // updating with a signature with more data
        $text = "ROJ-096	Cosmic Signature			0.0%	6.78 AU";
        $service->updateFromClipboardText(
            $this->user->characterId,
            self::SOLAR_SYSTEM,
            $text,
            false
        );

        $result = Signature::where([
            'characterId' => $this->user->characterId,
            'solarSystemName' => self::SOLAR_SYSTEM,
            'signatureId' => 'ROJ-096'
        ])->first();

        $this->assertEquals([
            'signatureId' => 'ROJ-096',
            'signatureName' => 'Regional Guristas Command Center',
            'groupName' => 'Data Site'
        ], [
            'signatureId' => $result->signatureId,
            'signatureName' => $result->signatureName,
            'groupName' => $result->groupName,
        ]);
    }

    protected function getSampleSignaturesClipboardText(): string
    {
        return implode("\n", [
            "ISN-720	Cosmic Signature			0.0%	2.58 AU",
            "KOA-124	Cosmic Signature			0.0%	3.73 AU",
            "QPI-926	Cosmic Signature			0.0%	6.78 AU",
            "ROJ-096	Cosmic Signature	Data Site	Regional Guristas Command Center	0.0%	29.53 AU",
            "XCO-255	Cosmic Signature			0.0%	1.79 AU",
            "KOL-024	Cosmic Signature	Data Site	Regional Guristas Command Center	0.0%	1.79 AU",
        ]);
    }
}
