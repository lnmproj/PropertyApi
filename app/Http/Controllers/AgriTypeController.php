<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AppModel;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class AgriTypeController extends Controller
{
   

    // get all agri_type
    public function Get(Request $request)
    {

        $itemsPerPage = $request->itemsPerPage;
        $sortColumn = $request->sortColumn;
        $sortOrder = $request->sortOrder;
        $searchText = $request->searchText;


        $getQuery = DB::table("agri_type")->select(['agri_type_id', 'agri_type_name', DB::raw("IF(agri_type_status = 'Active', 'Active','Inactive')as agri_type_status")])

            ->where('agri_type_name', 'like', '%' . $searchText . '%')->orderBy($sortColumn, $sortOrder)
            ->paginate($itemsPerPage);
        return response()->json(['resultData' =>  $getQuery], 200);
    }
    //save agri_type
    public function Save(Request $request)
    {


        $agri_type_name = trim($request->agri_type_name);
        $saveQuery = DB::table('agri_type')->insertGetId(
            [
                'agri_type_name' => $agri_type_name,

            ]
        );
        if ($saveQuery > 0) {
            return response()->json(['message' => 'Agri Type saved successfully'], 200);
        }
    }

    //Update agri_type
    public function Update(Request $request)
    {


        $agri_type_name = $request->agri_type_name;
        $agri_type_id = $request->agri_type_id;
        $agri_type_status = $request->agri_type_status;
        $updateQuery = DB::table('agri_type')
            ->where('agri_type_id', $agri_type_id)
            ->update([
                'agri_type_name' => $agri_type_name,
                'agri_type_status' => $agri_type_status,
                'updated_at' => now(),

            ]);
        if ($updateQuery > 0) {

            return response()->json(['message' => 'Agri Type updated successfully'], 200);
        }
    }

    //Delete agri_type
    public function Delete(Request $request)
    {

        $agri_type_id = $request->agri_type_id;
        $deleteQuery = DB::table('agri_type')->where('agri_type_id', $agri_type_id)->delete();
        if ($deleteQuery > 0) {

            return response()->json(['message' => 'Agri Type deleted successfully'], 200);
        }
    }
    //web end
}
