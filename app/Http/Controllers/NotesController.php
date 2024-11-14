<?php

namespace App\Http\Controllers;

use App\Models\Notes;
use Illuminate\Http\Request;

class NotesController extends Controller
{
    /**
     * Get All Notes
     */
    public function index()
    {
        $notes = Notes::all();
        return response()->json($notes);
    }


    /**
     * Create a Single Note
     */
    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'title' => 'required|string|max:255',
            'note' => 'required|string'
        ]);

        $note = Notes::create($validated_data);

        return response()->json($note, 201);
    }

    /**
     * Get Note By ID
     */
    public function show(Request $request, $id)
    {

        $note = Notes::findOrFail($id);
        return response()->json($note);
    }

    /**
     * Updaqte Note By ID
     */
    public function update(Request $request, $id)
    {
        $note = Notes::findOrFail($id);
        $note->update($request->all());
        return response()->json($note);
    }

    /**
     * Delete Note By ID
     */
    public function destroy(Request $request, $id)
    {
        $response = Notes::destroy($id);
        return response()->json($response);
    }
}
