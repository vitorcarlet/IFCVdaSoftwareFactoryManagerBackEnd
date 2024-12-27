<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ReportController extends Controller
{
    /**
     * Generate a report for a specific project.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function projectReport($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        // Generate the report for the project
        $report = [
            'id' => $project->id,
            'name' => $project->name,
            'description' => $project->description,
            'created_at' => $project->created_at,
            'updated_at' => $project->updated_at,
        ];

        return response()->json($report);
    }

    /**
     * Generate a report for all projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function allProjects()
    {
        $projects = Project::all();

        // Generate the report for all projects
        $report = $projects->map(function ($project) {
            return [
                'id' => $project->id,
                'name' => $project->name,
                'description' => $project->description,
                'created_at' => $project->created_at,
                'updated_at' => $project->updated_at,
            ];
        });

        return response()->json($report);
    }
}