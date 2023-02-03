<?php

namespace App\Http\Controllers;

use App\Http\Resources\SignatureResource;
use App\Models\CosmicSignature;
use App\Services\CosmicSignatures;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\OAuth1\Client\Signature\Signature;

class SignaturesController extends Controller
{
    public function __construct()
    {
        $this->middleware('eve.auth');
    }
    /**
     * Lists signatures for the specific solar system.
     * 
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(string $system)
    {
        return SignatureResource::collection(
            CosmicSignature::where([
                'characterId' => Auth::id(),
                'solarSystemName' => $system
            ])
                ->with(['externalLink'])
                ->orderBy('signatureId')->get()
        );
    }


    /**
     * Updates signatures for the specified solar system.
     * 
     * @param Request $request
     * @param CosmicSignatures $service
     * @return JsonResponse
     */
    public function update(Request $request, CosmicSignatures $service): JsonResponse
    {
        $validated = $request->validate([
            'solarSystemName' => 'required|string',
            'text' => 'required|string',
            'replace' => 'boolean',
        ]);

        $result = $service->updateFromClipboardText(
            Auth::id(),
            $validated['solarSystemName'],
            $validated['text'],
            $validated['replace']
        );

        return response()->json($result);
    }


    /**
     * Deletes signature with the specified id and solar system.
     * 
     * @param Request $request Must contain systemName and id parameters
     * @param CosmicSignatures $service
     * @return JsonResponse
     */
    public function destroy(Request $request, CosmicSignatures $service): JsonResponse
    {
        $validated = $request->validate([
            'systemName' => 'required|string',
            'id' => 'required|string',
        ]);

        $deleted = $service->deleteSignature(
            Auth::id(),
            $validated['systemName'],
            $validated['id'],
        );

        return response()->json(['deleted' => $deleted]);
    }
}
