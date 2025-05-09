<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Models\Student;

class EnrollmentController extends Controller
{
    public function getEnrollments(){
        $enrollments = Enrollment::with('user', 'program', 'status')->get();
        return response()->json(['enrollments' => $enrollments]);
    }

    public function addEnrollment(Request $request){
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'program_id' => ['required', 'exists:programs,id'],
            'academic_year' => ['required', 'string'],
            'semester' => ['required', 'string'],
            'enrollment_date' => ['required', 'date'],
            'birth_date' => ['nullable', 'date'],
            'year_level' => ['nullable', 'string'],
        ]);

        // Check if user already has an ongoing enrollment (Pending or Enrolled)
        $pendingStatus = \App\Models\Status::where('name', 'Pending')->first();
        $enrolledStatus = \App\Models\Status::where('name', 'Enrolled')->first();

        $ongoingStatusIds = [];
        if ($pendingStatus) $ongoingStatusIds[] = $pendingStatus->id;
        if ($enrolledStatus) $ongoingStatusIds[] = $enrolledStatus->id;

        $existingEnrollment = \App\Models\Enrollment::where('user_id', $request->user_id)
            ->whereIn('status_id', $ongoingStatusIds)
            ->first();

        if ($existingEnrollment) {
            return response()->json(['message' => 'User already has an ongoing enrollment.'], 400);
        }

        // Get Pending status id
        $pendingStatus = \App\Models\Status::where('name', 'Pending')->first();
        if (!$pendingStatus) {
            return response()->json(['message' => 'Pending status not found'], 500);
        }

        $enrollment = Enrollment::create([
            'user_id' => $request->user_id,
            'program_id' => $request->program_id,
            'academic_year' => $request->academic_year,
            'semester' => $request->semester,
            'enrollment_date' => $request->enrollment_date,
            'status_id' => $pendingStatus->id,
            'birth_date' => $request->birth_date,
            'year_level' => $request->year_level,
        ]);

        return response()->json(['message' => 'Enrollment successfully created and set to pending!', 'enrollment' => $enrollment]);
    }

    public function editEnrollment(Request $request, $enrollment_id){
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'program_id' => ['required', 'exists:programs,id'],
            'academic_year' => ['required', 'string'],
            'semester' => ['required', 'string'],
            'enrollment_date' => ['required', 'date'],
            'status_id' => ['required', 'exists:statuses,id'],
        ]);

        // Find the enrollment by enrollment_id
        $enrollment = Enrollment::find($enrollment_id);

        if(!$enrollment){
            return response()->json(['message' => 'Enrollment not found!'], 404);
        }

        // Update the enrollment details
        $enrollment->update([
            'user_id' => $request->user_id,
            'program_id' => $request->program_id,
            'academic_year' => $request->academic_year,
            'semester' => $request->semester,
            'enrollment_date' => $request->enrollment_date,
            'status_id' => $request->status_id,
        ]);

        return response()->json(['message' => 'Enrollment successfully updated!', 'enrollment' => $enrollment]);
    }

    public function deleteEnrollment($enrollment_id){
        // Find the enrollment by enrollment_id
        $enrollment = Enrollment::find($enrollment_id);

        if(!$enrollment){
            return response()->json(['message' => 'Enrollment not found!'], 404);
        }

        // Delete the student record associated with this enrollment's user and program
        $student = \App\Models\Student::where('program_id', $enrollment->program_id)
            ->where('first_name', $enrollment->user->user_fname)
            ->where('last_name', $enrollment->user->user_lname)
            ->first();

        if ($student) {
            $student->delete();
        }

        // Delete the enrollment
        $enrollment->delete();

        return response()->json(['message' => 'Enrollment and associated student successfully deleted!']);
    }

    public function approveEnrollment($enrollment_id)
    {
        // Find the enrollment by enrollment_id
        $enrollment = Enrollment::find($enrollment_id);

        if (!$enrollment) {
            return response()->json(['message' => 'Enrollment not found!'], 404);
        }

        // Use the known enrolled status id 3
        $enrolledStatusId = 3;

        // Update the enrollment status to Enrolled
        $enrollment->status_id = $enrolledStatusId;
        $enrollment->save();

        // Add user/student data to students table after enrolling
        $user = $enrollment->user;

        if ($user) {
            $activeStatus = \App\Models\Status::where('name', 'Active')->first();
            $activeStatusId = $activeStatus ? $activeStatus->id : 1; // default to 1 if not found

            \App\Models\Student::create([
                'first_name' => $user->user_fname,
                'middle_name' => $user->user_mname,
                'last_name' => $user->user_lname,
                'program_id' => $enrollment->program_id,
                'birth_date' => $enrollment->birth_date,
                'year_level' => $enrollment->year_level,
                'status_id' => $activeStatusId,
            ]);
        }

        return response()->json(['message' => 'Enrollment successfully approved and student record created!', 'enrollment' => $enrollment]);
    }
}
