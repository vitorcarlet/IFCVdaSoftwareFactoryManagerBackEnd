<?php

namespace App\Http\Controllers;

use App\Models\ProjectIdea;
use App\Models\MeetingParticipant;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function manager()
    {
        $userId = Auth::id();
        $meetings = MeetingParticipant::where('participant_id', $userId)
            ->with('meeting') // Carrega as informações dos meetings associados
            ->latest() // Ordena pela mais recente
            ->take(5) // Limita a 5 registros
            ->get()
            ->pluck('meeting'); // Extrai apenas os meetings relacionados

        $ideas = ProjectIdea::latest()->take(5)->get();

        return response()->json([
            'meetings' => $meetings,
            'ideas' => $ideas,
        ]);
    }

    public function student()
    {
        $user = Auth::user();
        $meetings = $user->meetings()->latest()->take(5)->get();
        $ideas = ProjectIdea::latest()->take(5)->get();

        return response()->json([
            'meetings' => $meetings,
            'ideas' => $ideas,
        ]);
    }
}
