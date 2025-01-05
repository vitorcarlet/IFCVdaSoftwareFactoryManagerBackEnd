<?php

namespace App\Http\Controllers;

use App\Models\ProjectIdea;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IdeaController extends Controller
{
    /**
     * Display a listing of ideas.
     */
    public function index()
    {
        $ideas = ProjectIdea::all();
        return response()->json($ideas, Response::HTTP_OK);
    }

    /**
     * Store a newly created idea in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'proponent_id' => 'required|exists:users,id',
        ]);

        $idea = ProjectIdea::create($validated);

        return response()->json($idea, Response::HTTP_CREATED);
    }

    /**
     * Display the specified idea.
     */
    public function show($id)
    {
        $idea = ProjectIdea::find($id);

        if (!$idea) {
            return response()->json(['message' => 'Idea not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($idea, Response::HTTP_OK);
    }

    /**
     * Update the specified idea in storage.
     */
    public function update(Request $request, $id)
    {
        $idea = ProjectIdea::find($id);

        if (!$idea) {
            return response()->json(['message' => 'Idea not found'], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'proponent_id' => 'sometimes|exists:users,id',
        ]);

        $idea->update($validated);

        return response()->json($idea, Response::HTTP_OK);
    }

    /**
     * Remove the specified idea from storage.
     */
    public function destroy($id)
    {
        $idea = ProjectIdea::find($id);

        if (!$idea) {
            return response()->json(['message' => 'Idea not found'], Response::HTTP_NOT_FOUND);
        }

        $idea->delete();

        return response()->json(['message' => 'Idea deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}
