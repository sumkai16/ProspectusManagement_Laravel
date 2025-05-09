<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function getCourses(){
        $courses = Course::all();
        return response()->json(['courses' => $courses]);
    }

    public function addCourse(Request $request){
        $request->validate([
            'course_code' => ['required', 'string', 'max:255', 'unique:courses'],
            'course_description' => ['required', 'string'],
            'course_units' => ['required', 'integer'],
        ]);

        $course = Course::create([
            'course_code' => $request->course_code,
            'course_description' => $request->course_description,
            'course_units' => $request->course_units,
        ]);

        return response()->json(['message' => 'Course successfully created!', 'course' => $course]);
    }

    public function editCourse(Request $request, $id){
        $request->validate([
            'course_code' => ['required', 'string', 'max:255', 'unique:courses,course_code,' . $id],
            'course_description' => ['required', 'string'],
            'course_units' => ['required', 'integer'],
        ]);

        $course = Course::find($id);

        if(!$course){
            return response()->json(['message' => 'Course not found!'], 404);
        }

        $course->update([
            'course_code' => $request->course_code,
            'course_description' => $request->course_description,
            'course_units' => $request->course_units,
        ]);

        return response()->json(['message' => 'Course successfully updated!', 'course' => $course]);
    }

    public function deleteCourse($id){
        $course = Course::find($id);

        if(!$course){
            return response()->json(['message' => 'Course not found!'], 404);
        }

        $course->delete();

        return response()->json(['message' => 'Course successfully deleted!']);
    }
}
