<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AppModel;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class PropertyTypeController extends Controller
{
   

    // get all property_type
    public function Get(Request $request)
    {

        $itemsPerPage = $request->itemsPerPage;
        $sortColumn = $request->sortColumn;
        $sortOrder = $request->sortOrder;
        $searchText = $request->searchText;


        $getQuery = DB::table("property_type")->select(['property_type_id', 'property_type', DB::raw("IF(property_type_status = 'Active', 'Active','Inactive')as property_type_status")])

            ->where('property_type', 'like', '%' . $searchText . '%')->orderBy($sortColumn, $sortOrder)
            ->paginate($itemsPerPage);
        return response()->json(['resultData' =>  $getQuery], 200);
    }
    //save property_type
    public function Save(Request $request)
    {


        $property_type = trim($request->property_type);
		$created_by = $request->created_by;
        $saveQuery = DB::table('property_type')->insertGetId(
            [
                'property_type' => $property_type,
				'created_by' => $created_by
            ]
        );
        if ($saveQuery > 0) {
            return response()->json(['message' => 'Property Type saved successfully'], 200);
        }
    }

    //Update property_type
    public function Update(Request $request)
    {


        $property_type = $request->property_type;
        $property_type_id = $request->property_type_id;
        $property_type_status = $request->property_type_status;
		$updated_by = $request->updated_by;
        $updateQuery = DB::table('property_type')
            ->where('property_type_id', $property_type_id)
            ->update([
                'property_type' => $property_type,
                'property_type_status' => $property_type_status,
                'updated_at' => now(),
				'updated_by' => $updated_by,

            ]);
        if ($updateQuery > 0) {

            return response()->json(['message' => 'Property Type updated successfully'], 200);
        }
    }

    //Delete property_type
    public function Delete(Request $request)
    {

        $property_type_id = $request->property_type_id;
        $deleteQuery = DB::table('property_type')->where('property_type_id', $property_type_id)->delete();
        if ($deleteQuery > 0) {

            return response()->json(['message' => 'Property Type deleted successfully'], 200);
        }
    }
    //web end
}
