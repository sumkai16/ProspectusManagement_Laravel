<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function getStudents(){
        $students = Student::with('course')->get();

        return response()->json(['students' => $students]);
    }  

    public function addStudent(Request $request){
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'program_id' => ['required', 'exists:programs,id'],
            'year_level' => ['nullable', 'string', 'max:255'],
            'status_id' => ['nullable', 'exists:statuses,id'],
        ]);

        $student = Student::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'program_id' => $request->program_id,
            'year_level' => $request->year_level,
            'status_id' => $request->status_id,
        ]);

        return response()->json(['message' => 'Student added successfully', 'student' => $student]);
    }

    public function editStudent(Request $request, $id){
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'program_id' => ['required', 'exists:programs,id'],
            'year_level' => ['nullable', 'string', 'max:255'],
            'status_id' => ['nullable', 'exists:statuses,id'],
        ]);

        $student = Student::find($id);

        if(!$student){
            return response()->json(['message' => 'Student not found'], 404);
        }

        $student->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'program_id' => $request->program_id,
            'year_level' => $request->year_level,
            'status_id' => $request->status_id,
        ]);

        return response()->json(['message' => 'Student updated successfully', 'student' => $student ]);
    }   

    public function deleteStudent($id){
        $student = Student::find($id);

        if(!$student){
            return response()->json(['message' => 'Student not found'], 404);
        }

        $student->delete();

        return response()->json(['message' => 'Student deleted successfully']);
    }
}
