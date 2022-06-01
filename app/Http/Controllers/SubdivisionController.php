<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class SubdivisionController extends Controller
{
    public $Namex = "Subdivision";

    // get all user_skills
    public function Get(Request $request)
    {


        $itemsPerPage = $request->itemsPerPage;
        $sortColumn = $request->sortColumn;
        $sortOrder = $request->sortOrder;
        $searchText = $request->searchText;
        $getQuery = DB::table("subdivisions as t1")
            ->leftJoin('province', 't1.province_id', '=', 'province.province_id')
            ->leftJoin('town', 't1.town_id', '=', 'town.town_id')
            ->leftJoin('barangay as t2', 't1.barangay_id', '=', 't2.barangay_id')

            ->
            select(['t1.subdivision_id', 't1.subdivision_name', DB::raw("IF(t1.subdivision_status = 'Active', 'Active','Inactive')as subdivision_status"),
                't1.zip_code','t1.province_id','t1.town_id','t1.barangay_id','t1.town_id','province.province_name',

                DB::raw("GROUP_CONCAT(t2.barangay_name) as adjacent_barangay"),
                DB::raw("GROUP_CONCAT(t2.barangay_id) as adjacent_barangay_id"),
                DB::raw('DATE_FORMAT(t1.created_at, "%d-%m-%Y") as created_at', "%d-%m-%Y"),

            ])

            ->where('t1.subdivision_name', 'like', '%' . $searchText . '%')->orderBy($sortColumn, $sortOrder)
            ->paginate($itemsPerPage);

        return response()->json(['resultData' => $getQuery], 200);

    }
    //save user_skills
    public function Save(Request $request)
    {
        $barangay_id = $request->barangay_id;
        $town_id = $request->town_id;
        $province_id = $request->province_id;
        $zip_code = $request->zipcode;
        $subdivision_name = $request->subdivision_name;
        $created_by = $request->created_by;
        $adjacent_subdivision=$request->adjacent_subdivision;

        $saveQuery = DB::table('subdivisions')->insertGetId(
            [
                'barangay_id'=> $barangay_id,
                'town_id' => $town_id,
                'province_id' =>  $province_id,
                'zip_code' =>$zip_code,
                'subdivision_name' => $subdivision_name,
//                'subdivision_status' => $request->subdivision_status,
                'created_by' =>  $created_by,
                'adjacent_subdivision' =>  $adjacent_subdivision

            ]
        );
        if ($saveQuery > 0) {
            return response()->json(['message' =>'Subdivision saved successfully'], 200);
        }
    }

    //Update user_skills
    public function Update(Request $request)
    {

        $Name = $request->Name;
        $Id = $request->subdivision_id;

        $updated_by = $request->updated_by;
        $barangay_id = $request->barangay_id;
        $town_id = $request->town_id;
        $province_id = $request->province_id;
        $zip_code = $request->zipcode;
        $subdivision_name = $request->subdivision_name;
        $created_by = $request->created_by;
        $adjacent_subdivision=$request->adjacent_subdivision;
        $updateQuery = DB::table('subdivisions')
            ->where('subdivision_id', $Id)
            ->update([
                'barangay_id'=> $barangay_id,
                'town_id' => $town_id,
                'province_id' =>  $province_id,
                'zip_code' =>$zip_code,
                'subdivision_name' => $subdivision_name,
                'subdivision_status' => $request->subdivision_status,
                'created_by' =>  $created_by,
                'adjacent_subdivision' =>  $adjacent_subdivision,
                 'updated_by' =>  $updated_by,
            ]);
        if ($updateQuery) {

            return response()->json(['message' => $Name . ' updated successfully'], 200);
        }
    }

    //Delete
    public function Delete(Request $request)
    {

        $Id = $request->subdivision_id;
        $deleteQuery = DB::table('subdivisions')->where('subdivision_id', $Id)->delete();
        if ($deleteQuery) {

            return response()->json(['message' => 'Item deleted successfully'], 200);
        }
    }
    //web end

    // get all subdivisionwithout pagination
    public function GetWithoutPagination(Request $request)
    {

        $Name = $request->Name;
        $Id = $request->subdivision_id;

        $updated_by = $request->updated_by;
        $barangay_id = $request->barangay_id;
        $town_id = $request->town_id;
        $province_id = $request->province_id;
        $zip_code = $request->zipcode;
        $subdivision_name = $request->subdivision_name;
        $created_by = $request->created_by;
        $adjacent_subdivision=$request->adjacent_subdivision;
        $getQuery = DB::table("subdivisions")->select(['subdivision_id', 'subdivision_name'])
            ->orderBy('subdivision_id');
        if (isset($barangayId) && isset($townId) && isset($provinceId)) {
            $getQuery->where('barangay_id', '=',$barangayId)
            ->where('town_id','=',$townId)
            ->where('province_id','=', $provinceId);
            if (isset($status)) {
                $getQuery->where('subdivision_status', '=', $status);
            } else {
                $getQuery->where('subdivision_status', '=', 'Active');

            }
        } else {
            if (isset($status)) {
                $getQuery->where('subdivision_status', '=', $status);
            } else {
                $getQuery->where('subdivision_status', '=', 'Active');

            }

        }

        $getQuery = $getQuery->get();
        return response()->json(['resultData' => $getQuery], 200);
    }
}
