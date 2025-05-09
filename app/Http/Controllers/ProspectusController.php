<?php

namespace App\Http\Controllers;

use App\Models\Prospectus;
use Illuminate\Http\Request;

class ProspectusController extends Controller
{
    public function getProspectuses(){
        $prospectuses = Prospectus::with('program', 'courses', 'status')->get();
        return response()->json(['prospectuses' => $prospectuses]);
    }

    public function addProspectus(Request $request){
        $request->validate([
            'program_id' => ['required', 'exists:programs,id'],
            'courses' => ['required', 'array'],
            'courses.*.course_id' => ['required', 'exists:courses,id'],
            'courses.*.semester' => ['required', 'in:first,second,1st,2nd'],
            'status_id' => ['required', 'exists:statuses,id'],
            'effective_year' => ['required', 'string', 'max:255'],
        ]);

        $prospectus = Prospectus::create([
            'program_id' => $request->program_id,
            'status_id' => $request->status_id,
            'effective_year' => $request->effective_year,
        ]);

        $courses = [];
        foreach ($request->courses as $course) {
            $courses[$course['course_id']] = ['semester' => $course['semester']];
        }
        $prospectus->courses()->attach($courses);

        return response()->json(['message' => 'Prospectus successfully created!', 'prospectus' => $prospectus->load('courses')]);
    }

    public function editProspectus(Request $request, $id){
        $request->validate([
            'program_id' => ['required', 'exists:programs,id'],
            'courses' => ['required', 'array'],
            'courses.*.course_id' => ['required', 'exists:courses,id'],
            'courses.*.semester' => ['required', 'in:first,second,1st,2nd'],
            'status_id' => ['required', 'exists:statuses,id'],
            'effective_year' => ['required', 'string', 'max:255'],
        ]);

        $prospectus = Prospectus::find($id);

        if(!$prospectus){
            return response()->json(['message' => 'Prospectus not found!'], 404);
        }

        $prospectus->update([
            'program_id' => $request->program_id,
            'status_id' => $request->status_id,
            'effective_year' => $request->effective_year,
        ]);

        $courses = [];
        foreach ($request->courses as $course) {
            $courses[$course['course_id']] = ['semester' => $course['semester']];
        }
        $prospectus->courses()->sync($courses);

        return response()->json(['message' => 'Prospectus successfully updated!', 'prospectus' => $prospectus->load('courses')]);
    }

    public function deleteProspectus($id){
        $prospectus = Prospectus::find($id);

        if(!$prospectus){
            return response()->json(['message' => 'Prospectus not found!'], 404);
        }

        $prospectus->delete();

        return response()->json(['message' => 'Prospectus successfully deleted!']);
    }
}
