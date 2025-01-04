<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

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
        // Validate the input
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'business_environment' => 'nullable|string',
            'business_need' => 'nullable|string',
            'objective' => 'nullable|string',
            'technologies.*' => 'nullable|string',
            'stakeholders' => 'nullable|string',
            'status' => 'required|string',
            'is_public' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'manager_id' => 'required|exists:users,id',
            'participants' => 'array',
            'participants.*' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        $technologies = $this->transformTechnologies($validated['technologies']);

        if ($validated)

            // Use a database transaction for consistency
            try {
                DB::beginTransaction();

                // Create the project
                $project = Project::create([
                    'title' => $validated['title'],
                    'business_environment' => $validated['business_environment'] ?? null,
                    'business_need' => $validated['business_need'] ?? null,
                    'objective' => $validated['objective'] ?? null,
                    'technologies' => $technologies ?? null,
                    'stakeholders' => $validated['stakeholders'] ?? null,
                    'status' => $validated['status'],
                    'is_public' => $validated['is_public'] ?? false,
                    'start_date' => $validated['start_date'] ?? null,
                    'end_date' => $validated['end_date'] ?? null,
                    'manager_id' => $validated['manager_id'],
                ]);

                // Attach participants if provided
                if (!empty($validated['participants'])) {
                    $project->participants()->sync($validated['participants']);
                }

                DB::commit();

                // Return the created project with its relationships
                return response()->json($project->load('participants'), 201);
            } catch (Exception $e) {
                DB::rollBack();
                // Log the error for debugging
                Log::error('Error creating project: ' . $e->getMessage());

                return response()->json([
                    'message' => 'An error occurred while creating the project.',
                    'error' => $e->getMessage(),
                ], 500);
            }
    }
    protected function transformTechnologies($technologies)
    {
        if (empty($technologies)) {
            return null;
        }
        return implode(', ', $technologies);
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
