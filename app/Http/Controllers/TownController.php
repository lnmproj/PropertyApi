<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class TownController extends Controller
{

    // get all user_skills
    public function Get(Request $request)
    {

        $itemsPerPage = $request->itemsPerPage;
        $sortColumn = $request->sortColumn;
        $sortOrder = $request->sortOrder;
        $searchText = $request->searchText;
        $getQuery = DB::table("town as t1")
            ->leftJoin('province', 't1.province_id', '=', 'province.province_id')
            ->leftJoin('town as t2', DB::raw("FIND_IN_SET(t2.town_id,t1.adjacent_town)"), ">", \DB::raw("'0'"))
            ->groupBy('t1.town_id')
            ->
        select(['t1.town_id', 't1.town_name', DB::raw("IF(t1.town_status = 'Active', 'Active','Inactive')as town_status"),
            't1.is_town_city',

            DB::raw("GROUP_CONCAT(t2.town_name) as adjacent_town"),
              DB::raw("GROUP_CONCAT(t2.town_id) as adjacent_town_id"),
            DB::raw('DATE_FORMAT(t1.created_at, "%d-%m-%Y") as created_at', "%d-%m-%Y"),
            'province.province_id', 'province.province_name', 

        ])

            ->where('t1.town_name', 'like', '%' . $searchText . '%')->orderBy($sortColumn, $sortOrder)
            ->paginate($itemsPerPage);
        return response()->json(['resultData' => $getQuery], 200);
    }
    //save user_skills
    public function Save(Request $request)
    {
        $townName = $request->townName;
        $isTownCity = $request->isTownCity;
        $provinceId = $request->provinceId;
        $adjacentTown = $request->adjacentTown;
        $createdBy = $request->createdBy;

        $saveQuery = DB::table('town')->insertGetId(
            [
                'town_name' => $townName,
                'is_town_city' => $isTownCity,
                'province_id' => $provinceId,
                'adjacent_town' => $adjacentTown,
                'created_by' => $createdBy,

            ]
        );
        if ($saveQuery > 0) {
           return response()->json(['message' => 'Town saved successfully'], 200);
        }
    }

    //Update user_skills
    public function Update(Request $request)
    {
        $townName = $request->townName;
        $isTownCity = $request->isTownCity;
        $provinceId = $request->provinceId;
        $adjacentTown = $request->adjacentTown;

        $townId = $request->townId;
        $status = $request->status;
        $updatedBy = $request->updatedBy;
        $updateQuery = DB::table('town')
            ->where('town_id', $townId)
            ->update([
                'town_name' => $townName,
                'is_town_city' => $isTownCity,
                'province_id' => $provinceId,
                'adjacent_town' => $adjacentTown,
                'town_status' => $status,
                'updated_at' => now(),
                'updated_by' => $updatedBy,

            ]);
        if ($updateQuery > 0) {

            return response()->json(['message' => 'Town updated successfully'], 200);
        }
    }

    //Delete user_skills
    public function Delete(Request $request)
    {

        $townId = $request->townId;
        $deleteQuery = DB::table('town')->where('town_id', $townId)->delete();
        if ($deleteQuery > 0) {

            return response()->json(['message' => 'Item deleted successfully'], 200);
        }
    }
    //web end

    // get all subdivisionwithout pagination
    public function GetWithoutPagination(Request $request)
    {

        $provinceId = $request->provinceId;

        $status = $request->status;
        $getQuery = DB::table("town")->select(['town_id', 'town_name'])
            ->orderBy('town_id');
        if (isset($provinceId)) {
            $getQuery
                ->where('province_id', '=', $provinceId);
            if (isset($status)) {
                $getQuery->where('town_status', '=', $status);
            } else {
                $getQuery->where('town_status', '=', 'Active');

            }
        } else {
            if (isset($status)) {
                $getQuery->where('town_status', '=', $status);
            } else {
                $getQuery->where('town_status', '=', 'Active');

            }

        }

        $getQuery = $getQuery->get();
        return response()->json(['resultData' => $getQuery], 200);
    }
}
