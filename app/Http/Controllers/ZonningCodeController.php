<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AppModel;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class ZonningCodeController extends Controller
{
    public  $Namex = "zonning_code";

    // get all zonning_code
    public function Get(Request $request)
    {

        $itemsPerPage = $request->itemsPerPage;
        $sortColumn = $request->sortColumn;
        $sortOrder = $request->sortOrder;
        $searchText = $request->searchText;


        $getQuery = DB::table("zonning_code")->select(['zonning_code_id', 'zonning_code', DB::raw("IF(zonning_code_status = 'Active', 'Active','Inactive')as zonning_code_status")])

            ->where('zonning_code', 'like', '%' . $searchText . '%')->orderBy($sortColumn, $sortOrder)
            ->paginate($itemsPerPage);
        return response()->json(['resultData' =>  $getQuery], 200);
    }
    //save zonning_code
    public function Save(Request $request)
    {


        $Name = trim($request->Name);
        $created_by = $request->created_by;

        $saveQuery = DB::table('zonning_code')->insertGetId(
            [
                'zonning_code' => $Name,
                'created_by' => $created_by,

            ]
        );
        if ($saveQuery > 0) {
            return response()->json(['message' => $Name . ' saved successfully'], 200);
        }
    }

    //Update zonning_code
    public function Update(Request $request)
    {


        $Name = $request->Name;
        $Id = $request->Id;
        $isActive = $request->isActive;
        $updated_by = $request->updated_by;
        $updateQuery = DB::table('zonning_code')
            ->where('zonning_code_id', $Id)
            ->update([
                'zonning_code' => $Name,
                'zonning_code_status' => $isActive,
                'updated_at' => now(),
                'updated_by' => $updated_by,

            ]);
        if ($updateQuery > 0) {

            return response()->json(['message' =>  $Name . ' updated successfully'], 200);
        }
    }

    //Delete zonning_code
    public function Delete(Request $request)
    {

        $Id = $request->Id;
        $deleteQuery = DB::table('zonning_code')->where('zonning_code_id', $Id)->delete();
        if ($deleteQuery > 0) {

            return response()->json(['message' => 'Item deleted successfully'], 200);
        }
    }
    //web end
}
