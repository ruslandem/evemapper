<?php

namespace App\Http\Controllers;

use App\Http\Resources\SignatureResource;
use App\Models\CosmicSignature;
use App\Services\CosmicSignatures;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            CosmicSignature::select([
                'signatures.id as id',
                'signatures.solarSystemName as solarSystemName',
                'signatures.signatureId as signatureId',
                'signatures.signatureName as signatureName',
                'signatures.groupName as groupName',
                'signatures.created_at as created_at',
                'signatures.updated_at as updated_at',
                'extLinks.name as linkName',
                'extLinks.url as linkUrl',
            ])
                ->where([
                    'signatures.characterId' => Auth::id(),
                    'signatures.solarSystemName' => $system
                ])
                ->leftJoin('extLinks', 'extLinks.name', '=', 'signatures.signatureName')
                ->orderBy('signatures.signatureId')->get()
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
