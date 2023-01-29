<?php

namespace App\Http\Controllers;

use App\Actions\BlueprintAppraisal;
use App\Core\EveBlueprint;
use App\Models\InvType;
use App\Services\Api\EveMarketer\EveMarketer;
use Illuminate\Http\Request;

class AppraisalController extends Controller
{
    public function __construct()
    {
        $this->middleware('eve.auth');
    }

    public function blueprintAppraisal(Request $request)
    {
        $request->validate([
            'blueprintName' => 'required|string',
        ]);

        $item = InvType::where([
            'typeName' => $request->input('blueprintName')
        ])->get()->first();

        if (!$item) {
            return response()->json(
                ['error' => 'blueprint not found'],
                400
            );
        }

        return response()->json(
            (new BlueprintAppraisal(new EveMarketer))->appraise($item->typeID)
        );
    }
}
