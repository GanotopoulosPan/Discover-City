<?php

namespace App\Http\Controllers;

use App\Models\Sight;

class SightController extends Controller
{
    public function show($id)
    {
        // Βρίσκουμε το αξιοθέατο με βάση το ID
        $sight = Sight::findOrFail($id);
        
        // Το στέλνουμε στο view 'sight-details'
        return view('sight-details', compact('sight'));
    }
}