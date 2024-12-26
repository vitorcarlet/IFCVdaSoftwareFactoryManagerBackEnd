<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meeting;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
    public function index()
    {
        $meetings = Meeting::all();
        return response()->json($meetings);
    }

    public function myMeetings()
    {
        $userId = Auth::id();
        $meetings = Meeting::whereHas('participants', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();

        return response()->json($meetings);
    }

    public function projectMeetings($projectId)
    {
        $meetings = Meeting::where('project_id', $projectId)->get();
        return response()->json($meetings);
    }

    public function show($id)
    {
        $meeting = Meeting::findOrFail($id);
        return response()->json($meeting);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'project_id' => 'required|exists:projects,id',
        ]);

        $meeting = Meeting::create($validated);

        // Attach participants if provided
        if ($request->has('participants')) {
            $meeting->participants()->sync($request->input('participants'));
        }

        return response()->json($meeting, 201);
    }

    public function update(Request $request, $id)
    {
        $meeting = Meeting::findOrFail($id);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'project_id' => 'nullable|exists:projects,id',
        ]);

        $meeting->update($validated);

        // Update participants if provided
        if ($request->has('participants')) {
            $meeting->participants()->sync($request->input('participants'));
        }

        return response()->json($meeting);
    }

    public function destroy($id)
    {
        $meeting = Meeting::findOrFail($id);
        $meeting->delete();

        return response()->json(['message' => 'Meeting deleted successfully.']);
    }

    public function uploadDocument(Request $request, $id)
    {
        $validated = $request->validate([
            'document_id' => 'required|exists:documents,id',
        ]);

        $meeting = Meeting::findOrFail($id);
        $meeting->documents()->attach($validated['document_id']);

        return response()->json(['message' => 'Document uploaded successfully.']);
    }
}

