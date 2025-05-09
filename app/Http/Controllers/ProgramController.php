<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function getPrograms(){
        $programs = Program::all();
        return response()->json(['programs' => $programs]);
    }

    public function addProgram(Request $request){
        $request->validate([
            'program_name' => ['required', 'string', 'max:255', 'unique:programs,program_name'],
            'program_description' => ['nullable', 'string'],
            'program_department' => ['nullable', 'string', 'max:255'],
            'status_id' => ['required', 'integer', 'exists:statuses,id'],
        ]);

        $program = Program::create([
            'program_name' => $request->program_name,
            'program_description' => $request->program_description,
            'program_department' => $request->program_department,
            'status_id' => $request->status_id,
        ]);

        return response()->json(['message' => 'Program successfully created!', 'program' => $program]);
    }

    public function editProgram(Request $request, $id){
        $request->validate([
            'program_name' => ['required', 'string', 'max:255', 'unique:programs,program_name,' . $id],
            'program_description' => ['nullable', 'string'],
            'program_department' => ['nullable', 'string', 'max:255'],
            'status_id' => ['nullable', 'integer', 'exists:statuses,id'],
        ]);

        $program = Program::find($id);

        if(!$program){
            return response()->json(['message' => 'Program not found!'], 404);
        }

        $program->update([
            'program_name' => $request->program_name,
            'program_description' => $request->program_description,
            'program_department' => $request->program_department,
            'status_id' => $request->status_id,
        ]);

        return response()->json(['message' => 'Program successfully updated!', 'program' => $program]);
    }

    public function deleteProgram($id){
        $program = Program::find($id);

        if(!$program){
            return response()->json(['message' => 'Program not found!'], 404);
        }

        $program->delete();

        return response()->json(['message' => 'Program successfully deleted!']);
    }
}
