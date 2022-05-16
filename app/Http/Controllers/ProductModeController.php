<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AppModel;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class ProductModeController extends Controller
{


    // get all Capability
    public function Get(Request $request)
    {

        $itemsPerPage = $request->itemsPerPage;
        $sortColumn = $request->sortColumn;
        $sortOrder = $request->sortOrder;
        $searchText = $request->searchText;


        $getQuery = DB::table("product_mode")->select(['product_mode_id', 'product_mode', DB::raw("IF(product_mode_status = 'Active', 'Active','Inactive')as product_mode_status")])

            ->where('product_mode', 'like', '%' . $searchText . '%')->orderBy($sortColumn, $sortOrder)
            ->paginate($itemsPerPage);
        return response()->json(['resultData' =>  $getQuery], 200);
    }
    //save Capability
    public function Save(Request $request)
    {


        $product_mode = trim($request->product_mode);
        $created_by = $request->created_by;

        $saveQuery = DB::table('product_mode')->insertGetId(
            [
                'product_mode' => $product_mode,
                'created_by' => $created_by,

            ]
        );
        if ($saveQuery > 0) {
            return response()->json(['message' => 'Product Mode saved successfully'], 200);
        }
    }

    //Update Capability
    public function Update(Request $request)
    {


       $product_mode = trim($request->product_mode);
        $product_mode_id = $request->product_mode_id;
        $product_mode_status = $request->product_mode_status;
        $updated_by = $request->updated_by;
        $updateQuery = DB::table('product_mode')
            ->where('product_mode_id', $product_mode_id)
            ->update([
                'product_mode' => $product_mode,
                'product_mode_status' => $product_mode_status,
                'updated_at' => now(),
                'updated_by' => $updated_by,

            ]);
        if ($updateQuery > 0) {

            return response()->json(['message' =>  'Product Mode updated successfully'], 200);
        }
    }

    //Delete Capability
    public function Delete(Request $request)
    {

        $product_mode_id = $request->product_mode_id;
        $deleteQuery = DB::table('product_mode')->where('product_mode_id', $product_mode_id)->delete();
        if ($deleteQuery > 0) {

            return response()->json(['message' => 'Item deleted successfully'], 200);
        }
    }
    //web end
}
