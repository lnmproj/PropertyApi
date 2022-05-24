<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Exception;


class BrokerAssociationController extends Controller
{
    public $Namex = "Broker";


    public function Get(Request $request)
    {
        $itemsPerPage = $request->itemsPerPage;
        $sortColumn = $request->sortColumn;
        $sortOrder = $request->sortOrder;
        $searchText = $request->searchText;

        $getQuery = DB::table("broker_association")->select(['*', DB::raw("IF(status = 'Active', 'Active','Inactive')as broker_association_status")])

            ->join('province','province.province_id','=','broker_association.province_id')


            ->join('subdivisions','subdivisions.subdivision_id','=','broker_association.subdivision_id')
            ->join('town','town.town_id','=','broker_association.town_id')
            ->join('barangay','barangay.barangay_id','=','broker_association.barangay_id')

            ->where('broker_association.broker_association_name', 'like', '%' . $searchText . '%')
            ->orderBy($sortColumn, $sortOrder)
            ->paginate($itemsPerPage);
        return response()->json(['resultData' => $getQuery], 200);
    }

    public function Save(Request $request)
    {

        try {
            $saveQuery = DB::table('broker_association')->insertGetId(
                [
                    'broker_association_name' => $request->broker_association_name,

                    'contact_person' => $request->contact_person,
                    'email_address' => $request->email_address,
                    'phone_1' => $request->phone_1,
                    'phone_2' => $request->phone_2,

                    'province_id' => $request->province_id,

                    'status' => $request->status,
                    'unit_number' => $request->unit_number,
                    'house_number' => $request->house_number,
                    'street_name' => $request->street_name,
                    'building_name' => $request->building_name,
                    'subdivision_id' => $request->subdivision_id,
                    'barangay_id' => $request->barangay_id,
                    'town_id' => $request->town_id,
                    'zip_code' => $request->zip_code,

                    'floor' => $request->floor,
                    'created_by' => '1'

                ]
            );
            if ($saveQuery > 0) {
                return response()->json(['message' => $request->broker_association_name .' broker association added successfully'], 200);
            }
        }
        catch (Exception $ex) {

            return response()->json(['message' => 'Something went wrong']);
        }
    }


    public function Update(Request $request)
    {

            $db=DB::table('broker')


                ->where('broker.broker_asociation_id',$request->broker_asociation_id)
                ->where('broker.status','Active')->get();


           if(count($db)>0){
               $updateQuery1 = DB::table('broker_association')
                   ->where('broker_association.broker_association_id', $request->broker_asociation_id)
                   ->update([
                       'broker_association_name' => $request->broker_association_name,
                       'contact_person' => $request->contact_person,
                       'email_address' => $request->email_address,
                       'phone_1' => $request->phone_1,
                       'phone_2' => $request->phone_2,

                       'province_id' => $request->province_id,

                       'status' => 'Active',
                       'unit_number' => $request->unit_number,
                       'house_number' => $request->house_number,
                       'street_name' => $request->street_name,
                       'building_name' => $request->building_name,
                       'subdivision_id' => $request->subdivision_id,
                       'barangay_id' => $request->barangay_id,
                       'town_id' => $request->town_id,
                       'zip_code' => $request->zip_code,

                       'floor' => $request->floor,
                       'created_by' => '1',
                       'broker_association.status' => 'Active'
                   ]);
               return response()->json(['message'=>'Cannot change status']);
           }

           else {
               $updateQuery = DB::table('broker_association')
                   ->where('broker_association.broker_association_id', $request->broker_asociation_id)
                   ->update([
                       'broker_association_name' => $request->broker_association_name,

                       'contact_person' => $request->contact_person,
                       'email_address' => $request->email_address,
                       'phone_1' => $request->phone_1,
                       'phone_2' => $request->phone_2,

                       'province_id' => $request->province_id,

                       'status' => $request->status,
                       'unit_number' => $request->unit_number,
                       'house_number' => $request->house_number,
                       'street_name' => $request->street_name,
                       'building_name' => $request->building_name,
                       'subdivision_id' => $request->subdivision_id,
                       'barangay_id' => $request->barangay_id,
                       'town_id' => $request->town_id,
                       'zip_code' => $request->zip_code,

                       'floor' => $request->floor,
                       'created_by' => '1'

                   ]);

               if ($updateQuery > 0) {
//
                   return response()->json(['message' => 'data updated'], 200);
               }
           }
     }

    //Delete user_skills
    public function Delete(Request $request)
    {

        if ($request->broker_asociation_id) {
            $table = DB::table('broker')->where('broker_asociation_id', $request->broker_asociation_id)->count();
            if ($table > 0) {
                return response()->json(['message' => 'cannot delete the broker association associated with multiple brokers'], 200);
            }
            else {
                $deleteQuery = DB::table('broker_association')->where('broker_association.broker_association_id', $request->broker_asociation_id)->delete();
                if ($deleteQuery > 0) {

                    return response()->json(['message' => 'Item deleted successfully'], 200);
                }
            }
        }
    }
    //web end

    // get all agency without pagination
    public function GetWithoutPagination(Request $request)
    {

        $id = $request->id;
        $status = $request->status;
        $getQuery = DB::table("broker")->select(['broker_id', 'broker_name'])
            ->orderBy('broker_id');
        if (isset($id)) {
            $getQuery->where('broker_id', '=', $id);
            if (isset($status)) {
                $getQuery->where('status', '=', $status);
            } else {
                $getQuery->where('status', '=', 'Active');

            }
        } else {
            if (isset($status)) {
                $getQuery->where('status', '=', $status);
            } else {
                $getQuery->where('status', '=', 'Active');

            }

        }

        $getQuery = $getQuery->get();
        return response()->json(['resultData' => $getQuery], 200);
    }
}
