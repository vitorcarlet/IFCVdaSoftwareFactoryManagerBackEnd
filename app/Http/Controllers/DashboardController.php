<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectIdea;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller

{
    public function manager(){

        $user = Auth::user();
        $meetings = $user->meetings()->latest()->take(5)->get();
        $ideas = ProjectIdea::latest()->take(5)->get();

        return response()->json([
            'meetings' => $meetings,
            'ideas' => $ideas,
        ]);
    }

    public function student(){

    }
}
