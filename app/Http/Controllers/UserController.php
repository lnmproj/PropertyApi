<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public $Namex = "User";

    // get all user_skills
    public function Get(Request $request)
    {

       $itemsPerPage = $request->itemsPerPage;
        $sortColumn = $request->sortColumn;
        $sortOrder = $request->sortOrder;
        $searchText = $request->searchText;
        $getQuery = DB::table('users')
            ->join('user_details', 'user_details.user_id', '=', 'users.user_id')
		
            ->join('user_skills', \DB::raw("FIND_IN_SET(user_skills.user_skills_id,user_details.user_skills)"), ">", \DB::raw("'0'"))
				 ->select(
			[
					 "user_details.user_id",
				DB::raw("(select GROUP_CONCAT(distinct(user_skills.user_skills))
				
        ) as user_skills")
					   
				
				])
            
            ->orderBy($sortColumn, $sortOrder)
            ->paginate($itemsPerPage);
        return response()->json(['resultData' => $getQuery], 200);
    }
    //save user_skills
    public function Save(Request $request)
    {

        $UserType = $request->UserType;
        $AssociatedAgency = $request->AssociatedAgency;
        $firstName = $request->firstName;
        $lastName = $request->lastName;
        $nickName = $request->nickName;
        $selectDate = $request->selectDate;
        $sameAsAgency = $request->sameAsAgency;
        $unitNumber = $request->unitNumber;
        $houseLotNumber = $request->houseLotNumber;
        $streetName = $request->streetName;
        $propertyBuildingName = $request->propertyBuildingName;
        $subdivision = $request->subdivision;
        $barangay = $request->barangay;
        $town = $request->town;
        $province = $request->province;
        $zipCode = $request->zipCode;
        $floorLevel = $request->floorLevel;
        $subdivision = $request->subdivision;
        $selectBirthDay = $request->selectBirthDay;
        $selectBirthMonth = $request->selectBirthMonth;
        $rELicence = $request->rELicence;
        $userWebsite = $request->userWebsite;
        $userSkills = $request->userSkills;
        $profileStatement = $request->profileStatement;
        $selfBroker = $request->selfBroker;
        $associatedBroker = $request->associatedBroker;
        $reasonInactive = $request->reasonInactive;
        $inactiveUser = $request->inactiveUser;
        $created_by = $request->created_by;
        $phone1 = $request->phone1;
        $phone2 = $request->phone2;
        $openPropertyLimit = $request->openPropertyLimit;
        $activeUserDateLimit = $request->activeUserDateLimit;
        $email = $request->email;

        //

        try {

            DB::beginTransaction();
            $password = random_int(100000, 999999);
            $insertedUserId = DB::table('users')->insertGetId(
                [
                    'role_id' => $UserType,
                    'password' => bcrypt($password),
                    'password_normal' => $password,
                    'user_email' => $email,
                    'first_name' => ucfirst($firstName),
                    'last_name' => ucfirst($lastName),
                    'full_name' => ucfirst($firstName) . ' ' . ucfirst($lastName),
                    'created_by' => $created_by,

                ]);

            DB::table('user_details')->insertGetId(
                [
                    'user_id' => $insertedUserId,
                    'first_name' => ucfirst($firstName),
                    'last_name' => ucfirst($lastName),
                    'nick_name' => $nickName,
                    'phone_1' => $phone1,
                    'phone_2' => $phone2,
                    'birth_month' => $selectBirthMonth,
                    'birth_day' => $selectBirthDay,
                    'website' => $userWebsite,
                    'user_skills' => $userSkills,
                    'open_property_limit' => $openPropertyLimit,
                    'active_user_date_limit' => $activeUserDateLimit,
                    'is_address_same_as_agency' => $sameAsAgency,
                    'unit_number' => $unitNumber,
                    'house_number' => $houseLotNumber,
                    'street_name' => $streetName,
                    'building_name' => $propertyBuildingName,
                    'subdivision_id' => $subdivision,
                    'barangay_id' => $barangay,
                    'town_id' => $town,
                    'province_id' => $province,
                    'zip_code' => $zipCode,
                    'floor' => $floorLevel,
                    're_license' => $rELicence,
                    'profile_statement' => $profileStatement,
                    'self_broker' => $selfBroker,
                    'associated_broker_id' => $associatedBroker,
                    'associated_agency_id' => $AssociatedAgency,

                ]);
            DB::table('user_tracking')->insertGetId(
                [
                    'user_id' => $insertedUserId,
                    'date_active' => now(),
                    'user_who_activated' => $created_by,

                ]);

            DB::commit();
            return response()->json(['message' => 'User saved successfully'], 200);

        } catch (Exception $ex) {

            DB::rollback();

        }

    }

    //Update user_skills
    public function Update(Request $request)
    {

        $Name = $request->Name;
        $Id = $request->Id;
        $isActive = $request->isActive;
        $updated_by = $request->updated_by;
        $updateQuery = DB::table('user_skills')
            ->where('user_skills_id', $Id)
            ->update([
                'user_skills' => $Name,
                'user_skills_status' => $isActive,
                'updated_at' => now(),
                'updated_by' => $updated_by,

            ]);
        if ($updateQuery > 0) {

            return response()->json(['message' => $Name . ' updated successfully'], 200);
        }
    }

    //Delete user_skills
    public function Delete(Request $request)
    {

        $Id = $request->Id;
        $deleteQuery = DB::table('user_skills')->where('user_skills_id', $Id)->delete();
        if ($deleteQuery > 0) {

            return response()->json(['message' => 'Item deleted successfully'], 200);
        }
    }
    //web end

    // get all user_skills without pagination
    public function GetWithoutPagination(Request $request)
    {

        $id = $request->id;
        $status = $request->status;
        $getQuery = DB::table("user_skills")->select(['user_skills_id', 'user_skills'])
            ->orderBy('user_skills_id');
        if (isset($id)) {
            $getQuery->where('user_skills_id', '=', $id);
            if (isset($status)) {
                $getQuery->where('user_skills_status', '=', $status);
            } else {
                $getQuery->where('user_skills_status', '=', 'Active');

            }
        } else {
            if (isset($status)) {
                $getQuery->where('user_skills_status', '=', $status);
            } else {
                $getQuery->where('user_skills_status', '=', 'Active');

            }

        }

        $getQuery = $getQuery->get();
        return response()->json(['resultData' => $getQuery], 200);
    }
	
	
	// Get Role wise permission
    public function getAssignedUnAssignedPermission(Request $request)
    {
        $roleId = $request->roleId;
        $getPermissionRoleWise = DB::select("select permissions.id as permission_id,permissions.name as 			permission_name,
        permissions.module_name as Module,if(role_has_permissions.role_id is null,0,1) as 					is_permission_assigned
         from permissions left join role_has_permissions
        on permissions.id=role_has_permissions.permission_id and role_has_permissions.role_id=$roleId");
		 return response()->json(['roleData' => ['data'=>$getPermissionRoleWise]], 200);
    }
	
	 // Assign Permission Role Wise
    public function assignPermissionRoleWise(Request $request)
    {
        $roleId = $request->roleId;
        $permissionId = $request->permissionId;

        $exception = DB::transaction(function () use ($roleId, $permissionId) {

            DB::table('role_has_permissions')->where('role_id', $roleId)->delete();
            for ($x = 0; $x < count($permissionId); $x++) {

                DB::table('role_has_permissions')->updateOrInsert(
                    [
                        'role_id' => $roleId, 'permission_id' => $permissionId[$x]

                    ],
                    [
                        'permission_id' => $permissionId[$x],
                        'role_id' => $roleId,

                    ]
                );
            }
        });
		if (is_null($exception)) {
            app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
			 return response()->json(['resultData' => $getQuery], 200);
        } else {
			 return response()->json(['resultData' => $getQuery], 300);
        }
    }
	
	// Assign Individual Permission Role Wise
    public function assignIndividualPermissionRoleWise(Request $request)
    {
        $roleId = $request->roleId;
        $permissionId = $request->permissionId;
        $is_permission_assigned = $request->is_permission_assigned;

        $exception = DB::transaction(function () use ($roleId, $permissionId, $is_permission_assigned) {

            if ($is_permission_assigned == false) {
                DB::table('role_has_permissions')
                    ->where('role_id', $roleId)
                    ->where('permission_id', $permissionId)
                    ->delete();
            } else {
                DB::table('role_has_permissions')->updateOrInsert(
                    [
                        'role_id' => $roleId, 'permission_id' => $permissionId

                    ],
                    [
                        'permission_id' => $permissionId,
                        'role_id' => $roleId,

                    ]
                );
            }
        });

        if (is_null($exception)) {
            app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
			 return response()->json(['resultData' => $getQuery], 200);
        } else {
			 return response()->json(['resultData' => $getQuery], 300);
        }
    }
}
