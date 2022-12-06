<?php

namespace App\Http\Controllers;

use App\Core\ApiResponseReport;
use App\Core\SignaturesHelper;
use App\Http\Resources\SignatureResource;
use App\Models\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $result = new ApiResponseReport();

        $validated = $request->validate([
            'solarSystemName' => 'required|string',
            'text' => 'required|string',
        ]);

        // deleting existing signatures (if needed)
        if ($request->boolean('replace')) {
            Signature::where([
                'characterId' => Auth::id(),
                'solarSystemName' => $validated['solarSystemName']
            ])->delete();
        }

        // getting array of inserting signatures
        $signatures = SignaturesHelper::decodeClipboardText(
            Auth::id(),
            $validated['solarSystemName'],
            $validated['text']
        );

        // updating/inserting signatures
        foreach ($signatures as $signature) {
            $existing = Signature::where([
                'characterId' => Auth::id(),
                'solarSystemName' => $validated['solarSystemName'],
                'signatureId' => $signature['signatureId']
            ])->first();

            $existing
                ? $existing->updateData($signature)->save()
                : Signature::create($signature);

            $result->increment($existing ? 'updated' : 'created');
        }

        return response()->json(
            $result->output()
        );
    }

    public function destroy(Request $request)
    {
        $result = new ApiResponseReport();

        $validated = $request->validate([
            'solarSystemName' => 'required|string',
            'signatureId' => 'required|string',
        ]);

        if ($validated['signatureId'] === '*') {
            $deleted = Signature::where([
                'characterId' => Auth::id(),
                'solarSystemName' => $validated['solarSystemName']
            ])->delete();
        } else {
            $deleted = Signature::where([
                'characterId' => Auth::id(),
                'solarSystemName' => $validated['solarSystemName'],
                'signatureId' => $validated['signatureId']
            ])->delete();
        }

        return response()->json(
            $result->increment('deleted', intval($deleted))->output()
        );
    }
}
