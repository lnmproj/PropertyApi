<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AppModel;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class PropertyClassificationController extends Controller
{


    // get all Capability
    public function Get(Request $request)
    {

        $itemsPerPage = $request->itemsPerPage;
        $sortColumn = $request->sortColumn;
        $sortOrder = $request->sortOrder;
        $searchText = $request->searchText;


        $getQuery = DB::table("property_classification")->select(['property_classification_id', 'property_classification', 
        DB::raw("IF(property_classification_status = 'Active', 'Active','Inactive')as property_classification_status")])

            ->where('property_classification', 'like', '%' . $searchText . '%')->orderBy($sortColumn, $sortOrder)
            ->paginate($itemsPerPage);
        return response()->json(['resultData' =>  $getQuery], 200);
    }
    //save Capability
    public function Save(Request $request)
    {


        $property_classification = trim($request->property_classification);
        $created_by = $request->created_by;

        $saveQuery = DB::table('property_classification')->insertGetId(
            [
                'property_classification' => $property_classification,
                'created_by' => $created_by,

            ]
        );
        if ($saveQuery > 0) {
            return response()->json(['message' => 'Property classification saved successfully'], 200);
        }
    }

    //Update Capability
    public function Update(Request $request)
    {


       $property_classification = trim($request->property_classification);
        $property_classification_id = $request->property_classification_id;
        $property_classification_status = $request->property_classification_status;
        $updated_by = $request->updated_by;
        $updateQuery = DB::table('property_classification')
            ->where('property_classification_id', $property_classification_id)
            ->update([
                'property_classification' => $property_classification,
                'property_classification_status' => $property_classification_status,
                'updated_at' => now(),
                'updated_by' => $updated_by,

            ]);
        if ($updateQuery > 0) {

            return response()->json(['message' =>  'Property classification updated successfully'], 200);
        }
    }

    //Delete Capability
    public function Delete(Request $request)
    {

        $property_classification_id = $request->property_classification_id;
        $deleteQuery = DB::table('property_classification')->where('property_classification_id', $property_classification_id)->delete();
        if ($deleteQuery > 0) {

            return response()->json(['message' => 'Item deleted successfully'], 200);
        }
    }
    //web end
}
