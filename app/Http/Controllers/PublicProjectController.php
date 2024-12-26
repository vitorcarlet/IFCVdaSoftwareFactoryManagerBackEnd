<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class PublicProjectController extends Controller
{
    public function index()
    {
        $projects = Project::where('is_public', true)->get();
        return response()->json($projects);
    }

    public function show($id)
    {
        $project = Project::where('is_public', true)->findOrFail($id);
        return response()->json($project);
    }
}