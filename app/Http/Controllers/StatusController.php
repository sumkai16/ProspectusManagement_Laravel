<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function getStatuses(){
        $statuses = Status::all();
        return response()->json(['statuses' => $statuses]);
    }

    public function addStatus(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:statuses'],
        ]);

        $status = Status::create([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Status successfully created!', 'status' => $status]);
    }

    public function editStatus(Request $request, $id){
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:statuses,name,' . $id],
        ]);

        $status = Status::find($id);

        if(!$status){
            return response()->json(['message' => 'Status not found!'], 404);
        }

        $status->update([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Status successfully updated!', 'status' => $status]);
    }

    public function deleteStatus($id){
        $status = Status::find($id);

        if(!$status){
            return response()->json(['message' => 'Status not found!'], 404);
        }

        $status->delete();

        return response()->json(['message' => 'Status successfully deleted!']);
    }
}
