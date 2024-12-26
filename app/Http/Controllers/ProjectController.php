<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return response()->json($projects);
    }
    public function myProjects()
    {
        $userId = Auth::id();
        $projects = Project::where('manager_id', $userId)->get();
        return response()->json($projects);
    }

    public function show($id)
    {
        $project = Project::findOrFail($id);
        return response()->json($project);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'business_environment' => 'nullable|string',
            'business_need' => 'nullable|string',
            'objective' => 'nullable|string',
            'technologies' => 'nullable|string',
            'stakeholders' => 'nullable|string',
            'status' => 'required|string',
            'is_public' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'manager_id' => 'required|exists:users,id',
        ]);

        $project = Project::create($validated);
        return response()->json($project, 201);
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'business_environment' => 'nullable|string',
            'business_need' => 'nullable|string',
            'objective' => 'nullable|string',
            'technologies' => 'nullable|string',
            'stakeholders' => 'nullable|string',
            'status' => 'nullable|string',
            'is_public' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $project->update($validated);

        return response()->json($project);
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json(['message' => 'Project deleted successfully.']);
    }

    public function addHours(Request $request, $id)
    {
        $validated = $request->validate([
            'hours' => 'required|numeric|min:0',
        ]);

        $project = Project::findOrFail($id);
        // Logic to add hours to the project

        return response()->json(['message' => 'Hours added successfully.']);
    }

    public function hoursAndDeadlines($id)
    {
        $project = Project::findOrFail($id);

        // Logic to retrieve hours and deadlines
        return response()->json([/* Data */]);
    }

    public function pendingProjects()
    {
        $projects = Project::where('status', 'pending')->get();
        return response()->json($projects);
    }

    public function approve($id)
    {
        $project = Project::findOrFail($id);
        $project->status = 'approved';
        $project->save();

        return response()->json(['message' => 'Project approved successfully.']);
    }

    public function reject($id)
    {
        $project = Project::findOrFail($id);
        $project->status = 'rejected';
        $project->save();

        return response()->json(['message' => 'Project rejected successfully.']);
    }
}
