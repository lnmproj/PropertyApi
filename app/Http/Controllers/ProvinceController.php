<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
   

    // get all user_skills
    public function Get(Request $request)
    {

        $itemsPerPage = $request->itemsPerPage;
        $sortColumn = $request->sortColumn;
        $sortOrder = $request->sortOrder;
        $searchText = $request->searchText;

        $getQuery = DB::table("province")->select(['province_id', 'province_name', DB::raw("IF(province_status = 'Active', 'Active','Inactive')as province_status")])

            ->where('province_name', 'like', '%' . $searchText . '%')->orderBy($sortColumn, $sortOrder)
            ->paginate($itemsPerPage);
        return response()->json(['resultData' => $getQuery], 200);
    }
    //save user_skills
    public function Save(Request $request)
    {

        $provinceName = trim($request->provinceName);
        $createdBy = $request->createdBy;

        $saveQuery = DB::table('province')->insertGetId(
            [
                'province_name' => $provinceName,
                'created_by' => $createdBy,

            ]
        );
        if ($saveQuery > 0) {
            return response()->json(['message' => $Name . ' saved successfully'], 200);
        }
    }

    //Update user_skills
    public function Update(Request $request)
    {

        $provinceName = $request->provinceName;
        $provinceId = $request->provinceId;
        $provinceStatus = $request->provinceStatus;
        $updatedBy = $request->updatedBy;
        $updateQuery = DB::table('province')
            ->where('province_id', $provinceId)
            ->update([
                'province_name' => $provinceName,
                'province_status' => $provinceStatus,
                'updated_at' => now(),
                'updated_by' => $updatedBy,

            ]);
        if ($updateQuery > 0) {

            return response()->json(['message' => 'Province updated successfully'], 200);
        }
    }

    //Delete user_skills
    public function Delete(Request $request)
    {

        $provinceId = $request->provinceId;
        $deleteQuery = DB::table('province')->where('province_id', $provinceId)->delete();
        if ($deleteQuery > 0) {

            return response()->json(['message' => 'Item deleted successfully'], 200);
        }
    }
    //web end

    // get all province without pagination
   public function GetWithoutPagination(Request $request)
    {

        $id = $request->id;
        $status = $request->status;
        $getQuery = DB::table("province")->select(['province_id', 'province_name'])
            ->orderBy('province_id');
        if (isset($id)) {
            $getQuery->where('province_id', '=', $id);
            if (isset($status)) {
                $getQuery->where('province_status', '=', $status);
            } else {
                $getQuery->where('province_status', '=', 'Active');

            }
        } else {
            if (isset($status)) {
                $getQuery->where('province_status', '=', $status);
            } else {
                $getQuery->where('province_status', '=', 'Active');

            }

        }

        $getQuery = $getQuery->get();
        return response()->json(['resultData' => $getQuery], 200);
    }
}
