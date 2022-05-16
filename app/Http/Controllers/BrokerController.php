<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class BrokerController extends Controller
{
    public $Namex = "Broker";

    // get all user_skills
    public function Get(Request $request)
    {

        $itemsPerPage = $request->itemsPerPage;
        $sortColumn = $request->sortColumn;
        $sortOrder = $request->sortOrder;
        $searchText = $request->searchText;

        $getQuery = DB::table("user_skills")->select(['user_skills_id', 'user_skills', DB::raw("IF(user_skills_status = 'Active', 'Active','Inactive')as user_skills_status")])

            ->where('user_skills', 'like', '%' . $searchText . '%')->orderBy($sortColumn, $sortOrder)
            ->paginate($itemsPerPage);
        return response()->json(['resultData' => $getQuery], 200);
    }
    //save user_skills
    public function Save(Request $request)
    {

        $Name = trim($request->Name);
        $created_by = $request->created_by;

        $saveQuery = DB::table('user_skills')->insertGetId(
            [
                'user_skills' => $Name,
                'created_by' => $created_by,

            ]
        );
        if ($saveQuery > 0) {
            return response()->json(['message' => $Name . ' saved successfully'], 200);
        }
    }

    //Update user_skills
    public function Update(Request $request)
    {

        $Name = $request->Name;
        $Id = $request->Id;
        $isActive = $request->isActive;
        $updated_by = $request->updated_by;
        $updateQuery = DB::table('user_skills')
            ->where('user_skills_id', $Id)
            ->update([
                'user_skills' => $Name,
                'user_skills_status' => $isActive,
                'updated_at' => now(),
                'updated_by' => $updated_by,

            ]);
        if ($updateQuery > 0) {

            return response()->json(['message' => $Name . ' updated successfully'], 200);
        }
    }

    //Delete user_skills
    public function Delete(Request $request)
    {

        $Id = $request->Id;
        $deleteQuery = DB::table('user_skills')->where('user_skills_id', $Id)->delete();
        if ($deleteQuery > 0) {

            return response()->json(['message' => 'Item deleted successfully'], 200);
        }
    }
    //web end

    // get all agency without pagination
    public function GetWithoutPagination(Request $request)
    {

        $id = $request->id;
        $status = $request->status;
        $getQuery = DB::table("broker")->select(['broker_id', 'broker_name'])
            ->orderBy('broker_id');
        if (isset($id)) {
            $getQuery->where('broker_id', '=', $id);
            if (isset($status)) {
                $getQuery->where('status', '=', $status);
            } else {
                $getQuery->where('status', '=', 'Active');

            }
        } else {
            if (isset($status)) {
                $getQuery->where('status', '=', $status);
            } else {
                $getQuery->where('status', '=', 'Active');

            }

        }

        $getQuery = $getQuery->get();
        return response()->json(['resultData' => $getQuery], 200);
    }
}
