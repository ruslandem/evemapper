<?php

namespace App\Http\Controllers;

use App\Core\SignaturesHelper;
use App\Http\Resources\SignatureResource;
use App\Models\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SignaturesController extends Controller
{
    public function index(Request $request)
    {
        return SignatureResource::collection(
            Signature::where([
                'characterId' => Auth::id(),
                'solarSystemName' => $request->input('system')
            ])->get()
        );
    }


    public function update(Request $request)
    {
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

        // updating or inserting signatures
        foreach ($signatures as $signature) {
            $existing = Signature::where([
                'characterId' => Auth::id(),
                'solarSystemName' => $validated['solarSystemName'],
                'signatureId' => $signature['signatureId']
            ])->first();

            $existing
                ? $existing->updateData($signature)->save()
                : Signature::factory()->createOne($signature);
        }

        return redirect()
            ->action([SignaturesController::class, 'index'])
            ->withInput(['system' => $validated['solarSystemName']]);
    }

    public function destroy($id)
    {
        //
    }
}
