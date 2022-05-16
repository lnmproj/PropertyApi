<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AppModel;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class CategoryController extends Controller
{
   

    // get all product_category
    public function Get(Request $request)
    {

        $itemsPerPage = $request->itemsPerPage;
        $sortColumn = $request->sortColumn;
        $sortOrder = $request->sortOrder;
        $searchText = $request->searchText;


        $getQuery = DB::table("product_category")->select(['product_category_id', 'product_category_name', DB::raw("IF(product_category_status = 'Active', 'Active','Inactive')as product_category_status")])

            ->where('product_category_name', 'like', '%' . $searchText . '%')->orderBy($sortColumn, $sortOrder)
            ->paginate($itemsPerPage);
        return response()->json(['resultData' =>  $getQuery], 200);
    }
    //save product_category
    public function Save(Request $request)
    {


        $product_category_name = trim($request->product_category_name);
		$created_by = $request->created_by;
        $saveQuery = DB::table('product_category')->insertGetId(
            [
                'product_category_name' => $product_category_name,
				'created_by' => $created_by
            ]
        );
        if ($saveQuery > 0) {
            return response()->json(['message' => 'Product category saved successfully'], 200);
        }
    }

    //Update product_category
    public function Update(Request $request)
    {


        $product_category_name = $request->product_category_name;
        $product_category_id = $request->product_category_id;
        $product_category_status = $request->product_category_status;
		$updated_by = $request->updated_by;
        $updateQuery = DB::table('product_category')
            ->where('product_category_id', $product_category_id)
            ->update([
                'product_category_name' => $product_category_name,
                'product_category_status' => $product_category_status,
                'updated_at' => now(),
				'updated_by' => $updated_by,

            ]);
        if ($updateQuery > 0) {

            return response()->json(['message' => 'Product category updated successfully'], 200);
        }
    }

    //Delete product_category
    public function Delete(Request $request)
    {

        $product_category_id = $request->product_category_id;
        $deleteQuery = DB::table('product_category')->where('product_category_id', $product_category_id)->delete();
        if ($deleteQuery > 0) {

            return response()->json(['message' => 'Product category deleted successfully'], 200);
        }
    }
    //web end
}
