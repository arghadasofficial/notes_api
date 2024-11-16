<?php

namespace App\Http\Controllers;

use App\Models\Notes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotesController extends Controller
{
    /**
     * Get All Notes
     */
    public function index(Request $request)
    {
        $per_page = $request->query('per_page', 10);
        $notes = Notes::latest()->paginate($per_page ?? 10); // simplePaginate(5) to display simple "Next" and "Previous" links
        return $this->sendSuccessResponse($notes, 'Notes retrieved successfully');
    }

    /**
     * Create a Single Note
     */
    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                "title" => "required|string|max:255",
                "note" => "required|string"
            ]);

            if ($validator->fails()) {
                return $this->sendErrorResponse($validator->errors(), 'Validation error');
            }

            $note = Notes::create($request->all());
            return $this->sendSuccessResponse($note, 'Notes created successfully');
        } catch (\Exception $e) {
            return $this->sendErrorResponse($validator->errors(), 'Failed to create note');
        }
    }

    /**
     * Get Note By ID
     */
    public function show(Request $request, $id)
    {
        try {
            $note = Notes::findOrFail($id);
            return $this->sendSuccessResponse($note, "Note retrieved successfully");
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), 'Failed to retrive note');
        }
    }

    /**
     * Updaqte Note By ID
     */
    public function update(Request $request, $id)
    {
        try {

            $note = Notes::findOrFail($id);

            if (empty($note)) {
                return $this->sendErrorResponse([], 'Note not found', 401);
            }

            $note->update($request->all());
            return $this->sendSuccessResponse($note, 'Note updated successfully');

            return response()->json($note);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), 'Failed to update note');
        }
    }

    /**
     * Delete Note By ID
     */
    public function destroy(Request $request, $id)
    {

        try {

            $response = Notes::destroy($id);
            if ($response == 0) {
                return $this->sendErrorResponse('Unable to find note', 'Note not found', 404);
            } else {
                return $this->sendSuccessResponse([], 'Note deleted successfully');
            }
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), 'Failed to delete note');
        }
    }

    public function searchQuery(Request $request)
    {
        try {

            $query = $request->query('query');
            $per_page = $request->query('per_page', 10);

            if (empty($query)) {
                return $this->sendErrorResponse([], 'query paramater is required', 422);
            } else {
                $notes = Notes::where('title', 'LIKE', "%{$query}%")
                    ->orWhere('note', 'LIKE', "%{$query}%")
                    ->latest()
                    ->paginate($per_page ?? 10);
                return $this->sendSuccessResponse($notes, 'Notes search success');
            }
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), 'Failed to search');
        }
    }

    public function sendSuccessResponse($data, $message = 'Success', $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'response' => $data
        ], $status);
    }

    public function sendErrorResponse($message = 'Success', $error = 'Error', $status = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'error' => $error
        ], $status);
    }
}
