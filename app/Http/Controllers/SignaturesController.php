<?php

namespace App\Http\Controllers;

use App\Core\ResponseReport;
use App\Http\Resources\SignatureResource;
use App\Models\Signature;
use App\Services\CosmicSignatures;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SignaturesController extends Controller
{
    /**
     * Lists signatures for the specific solar system.
     * 
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return SignatureResource::collection(
            Signature::where([
                'characterId' => Auth::id(),
                'solarSystemName' => $request->input('system')
            ])->orderBy('signatureId')->get()
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
        $result = $service->updateFromClipboardText(
            Auth::id(),
            $request->input('solarSystemName'),
            $request->input('text'),
            $request->boolean('replace')
        );

        return response()->json(
            $result->output()
        );
    }

   
    /**
     * Deletes signature with the specified id and solar system.
     * 
     * @param Request $request Must contain solarSystemName and signatureId parameters
     * @param CosmicSignatures $service
     * @return JsonResponse
     */
    public function destroy(Request $request, CosmicSignatures $service): JsonResponse
    {
        $validated = $request->validate([
            'solarSystemName' => 'required|string',
            'signatureId' => 'required|string',
        ]);

        $deleted = $service->deleteSignature(
            Auth::id(),
            $validated['solarSystemName'],
            $validated['signatureId'],
        );

        return response()->json(
            (new ResponseReport())->increment('deleted', $deleted)->output()
        );
    }
}
