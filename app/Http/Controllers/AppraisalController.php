<?php

namespace App\Http\Controllers;

use App\Core\EveBlueprint;
use Illuminate\Http\Request;

class AppraisalController extends Controller
{
    public function __construct()
    {
        $this->middleware('eve.auth');
    }

    public function blueprintAppraisal(Request $request)
    {
        $validated = $request->validate([
            'blueprintName' => 'required|string',
        ]);

        $blueprint = new EveBlueprint();

        $typeId = $blueprint->getTypeId($validated['blueprintName']);

        if ($typeId === null) {
            return response()->json(['error' => 'blueprint not found'], 400);
        }
        
        $result = $blueprint->appraisal($typeId);
        $result['name'] = $validated['blueprintName'];

        return $result;
    }
}
