<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meeting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'desc' => 'nullable|string',
                'status' => 'required|string',
                'start_date' => 'required|date',
                'project_id' => 'required|exists:projects,id',
                'participants' => 'array|nullable|exists:users,id',
                'manager' => 'required|exists:users,id',
            ]);
            $validated['start_date'] = Carbon::parse($request->input('start_date'))->format('Y-m-d H:i:s');
            // Check if any participant has a conflicting meeting
            $startDate = Carbon::parse($request->input('start_date'));
            // Check conflicts for participants


            // Check conflicts for manager
            $conflictingManagerMeetings = Meeting::whereHas('participants', function ($query) use ($request) {
                $query->where('participant_id', $request->input('manager'));
            })
                ->where(function ($query) use ($startDate) {
                    $query->whereBetween('start_date', [
                        $startDate->copy()->subMinutes(30),
                        $startDate->copy()->addMinutes(30)
                    ]);
                })->exists();

            if ($conflictingManagerMeetings) {
                throw new Exception('One or more participants or the manager have a meeting scheduled within 30 minutes of this time.');
            }
            $meeting = Meeting::create($validated);



            // Attach participants if provided
            if ($request->has('participants')) {
                $conflictingParticipantMeetings = Meeting::whereHas('participants', function ($query) use ($request) {
                    $query->whereIn('participant_id', $request->input('participants', []));
                })
                    ->where(function ($query) use ($startDate) {
                        $query->whereBetween('start_date', [
                            $startDate->copy()->subMinutes(30),
                            $startDate->copy()->addMinutes(30)
                        ]);
                    })->exists();
                if ($conflictingParticipantMeetings) {
                    throw new Exception('One or more participants or the manager have a meeting scheduled within 30 minutes of this time.');
                }
                $meeting->participants()->sync($request->input('participants'));
            }

            DB::commit();
            return response()->json($meeting, 201);
        } catch (ValidationException $e) {
            DB::rollback();
            return response()->json(['error' => $e->errors()], 422);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => 'An error occurred while creating the meeting',
                'message' => $e->getMessage() // Inclui a mensagem do erro para depuração
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

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

            DB::commit();
            return response()->json($meeting);
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            return response()->json(['error' => 'Meeting not found'], 404);
        } catch (ValidationException $e) {
            DB::rollback();
            return response()->json(['error' => $e->errors()], 422);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'An error occurred while updating the meeting'], 500);
        }
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
