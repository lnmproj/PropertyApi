<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class BarangayController extends Controller
{
    public $Namex = "Barangay";

    // get all user_skills
    public function Get(Request $request)
    {

       $itemsPerPage = $request->itemsPerPage;
$sortColumn = $request->sortColumn;
$sortOrder = $request->sortOrder;
$searchText = $request->searchText;
$getQuery = DB::table("barangay as t1")
    ->leftJoin('province', 't1.province_id', '=', 'province.province_id')
      ->leftJoin('town', 't1.town_id', '=', 'town.town_id')
    ->leftJoin('barangay as t2', DB::raw("FIND_IN_SET(t2.barangay_id,t1.adjacent_barangay)"), ">", \DB::raw("'0'"))
    ->groupBy('t1.town_id')
    ->
select(['t1.barangay_id', 't1.barangay_name', DB::raw("IF(t1.barangay_status = 'Active', 'Active','Inactive')as barangay_status"),
    't1.zip_code','t1.province_id','t1.town_id','town.town_name','province.province_name',

    DB::raw("GROUP_CONCAT(t2.barangay_name) as adjacent_barangay"),
    DB::raw("GROUP_CONCAT(t2.barangay_id) as adjacent_barangay_id"),
    DB::raw('DATE_FORMAT(t1.created_at, "%d-%m-%Y") as created_at', "%d-%m-%Y"),

])

    ->where('t1.barangay_name', 'like', '%' . $searchText . '%')->orderBy($sortColumn, $sortOrder)
    ->paginate($itemsPerPage);
return response()->json(['resultData' => $getQuery], 200);

    }
    //save user_skills
    public function Save(Request $request)
    {

       $barangay_name = $request->barangay_name;
$town_id = $request->town_id;
$province_id = $request->province_id;
$zip_code = $request->zip_code;
$adjacent_barangay = $request->adjacent_barangay;
$created_by = $request->created_by;


        $saveQuery = DB::table('barangay')->insertGetId(
            [
                'barangay_name' => $barangay_name,
                'town_id' => $town_id,
                 'province_id' => $province_id,
                  'zip_code' => $zip_code,
                   'adjacent_barangay' => $adjacent_barangay,
                    'created_by' => $created_by

            ]
        );
        if ($saveQuery > 0) {
            return response()->json(['message' =>'Barangay saved successfully'], 200);
        }
    }

    //Update user_skills
    public function Update(Request $request)
    {
        
$barangay_id = $request->barangay_id;
$barangay_name = $request->barangay_name;
$town_id = $request->town_id;
$province_id = $request->province_id;
$zip_code = $request->zip_code;
$adjacent_barangay = $request->adjacent_barangay;
$barangay_status = $request->barangay_status;
$updated_by = $request->updated_by;

        $updateQuery = DB::table('barangay')
            ->where('barangay_id', $barangay_id)
            ->update([
                'barangay_name' => $barangay_name,
                 'town_id' => $town_id,
                  'province_id' => $province_id,
                   'zip_code' => $zip_code,
                    'adjacent_barangay' => $adjacent_barangay,
                     'barangay_status' => $barangay_status,
                'updated_at' => now(),
                'updated_by' => $updated_by,

            ]);
        if ($updateQuery > 0) {

            return response()->json(['message' => 'Barangay updated successfully'], 200);
        }
    }

    //Delete user_skills
    public function Delete(Request $request)
    {

        $barangay_id = $request->barangay_id;
        $deleteQuery = DB::table('barangay')->where('barangay_id', $barangay_id)->delete();
        if ($deleteQuery > 0) {

            return response()->json(['message' => 'Item deleted successfully'], 200);
        }
    }
    //web end

    // get all subdivisionwithout pagination
    public function GetWithoutPagination(Request $request)
    {

        $townId = $request->townId;
        $provinceId = $request->provinceId;

        $status = $request->status;
        $getQuery = DB::table("barangay")->select(['barangay_id', 'barangay_name'])
            ->orderBy('barangay_id');
        if (isset($townId) && isset($provinceId)) {
            $getQuery
            ->where('town_id','=',$townId)
            ->where('province_id','=', $provinceId);
            if (isset($status)) {
                $getQuery->where('barangay_status', '=', $status);
            } else {
                $getQuery->where('barangay_status', '=', 'Active');

            }
        } else {
            if (isset($status)) {
                $getQuery->where('barangay_status', '=', $status);
            } else {
                $getQuery->where('barangay_status', '=', 'Active');

            }

        }

        $getQuery = $getQuery->get();
        return response()->json(['resultData' => $getQuery], 200);
    }
}
