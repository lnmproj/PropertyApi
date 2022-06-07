<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AppModel;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class AppController extends Controller
{

    // To check User Status
    public function checkUserStatus(Request $request)
    {
        $app_model = new AppModel;
        $userId = $request->userId;
        $result = $app_model->checkUserStatus($userId);
        return $result;
    }

    // To check Mobile Number
    public function checkMobileNumber(Request $request)
    {
        $app_model = new AppModel;
        $mobileNumber = $request->mobileNumber;
        $result = $app_model->checkMobileNumber($mobileNumber);
        return $result;
    }

    public function saveMobileNumber(Request $request)
    {
        $app_model = new AppModel;

        $mobileNumber = $request->mobileNumber;
        $deviceType = $request->deviceType;
        $isRegisteredUser = $request->isRegisteredUser;
        $userId = $request->userId;
        $userDeviceToken = $request->userDeviceToken;
        return $app_model->saveMobileNumber($mobileNumber, $deviceType, $isRegisteredUser, $userId, $userDeviceToken);
    }
    // To update device token
    public function updateDeviceToken(Request $request)
    {
        $app_model = new AppModel;
        $userId = $request->userId;
        $userDeviceToken = $request->userDeviceToken;
        $result = $app_model->updateDeviceToken($userId, $userDeviceToken);
        return response()->json($result);
    }

    // Send Sign up OTP
    public function sendSignupOTP($mobile, $otp)
    {
        try {
            $smstext = rawurlencode("<#>  Your mobile verification code is: " . $otp . " , Please don't share this code with anyone.Regards, P Maiti nUZVlP++W2a");
            $ch = curl_init("https://www.smsgatewayhub.com/api/mt/SendSMS?APIKey=256dda14-3d85-4e46-83aa-1d12103bc1e2&senderid=PRIMAI&channel=2&DCS=0&flashsms=0&number=$mobile&text=%3C%23%3E%20Your%20mobile%20verification%20code%20is:%20" . $otp . ",%20Please%20don%27t%20share%20this%20code%20with%20anyone.%20Regards,%20P%20Maiti%20pJEbpuWf6Bj&route=31&EntityId=1301162038261824606&dlttemplateid=1307162100179461666");

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 2);
            $result = curl_exec($ch);
            if (curl_error($ch)) {
                $result = curl_errno($ch);
            }
        } catch (Exception $ex) {
            $result = $ex->getMessage();
        }

        return response()->json($result);
    }

    // to update name an gender
    public function updateNameGender(Request $request)
    {
        $app_model = new AppModel;

        $firstName = $request->firstName;
        $lastName = $request->lastName;
        $gender = $request->gender;
        $userId = $request->userId;
        return $app_model->updateNameGender($firstName, $lastName, $gender, $userId);
    }

    // to save bmi hitory
    public function saveBMIHitory(Request $request)
    {
        $app_model = new AppModel;

        $userId = $request->userId;
        $userAge = $request->userAge;
        $userWeight = $request->userWeight;
        $userHeight = $request->userHeight;
        $bmiScore = $request->bmiScore;
        return $app_model->saveBMIHitory($userId, $userAge, $userWeight, $userHeight, $bmiScore);
    }

    public function getQuestion(Request $request)
    {
        $app_model = new AppModel;
        $result = $app_model->getQuestion();
        return $result;
    }

    // to update user answer
    public function updateUserAnswer(Request $request)
    {
        $app_model = new AppModel;
        $userId = $request->userId;
        $answer = $request->answer;
        $questionId = $request->questionId;
        return $app_model->updateUserAnswer($userId, $answer, $questionId);
    }

    // to get device list
    public function getDeviceList(Request $request)
    {
        $app_model = new AppModel;
        $devicePlatform = $request->devicePlatform;

        return $app_model->getDeviceList($devicePlatform);
    }

    // save device mapping
    public function saveUserDeviceMapping(Request $request)
    {
        $app_model = new AppModel;
        $deviceId = $request->deviceId;
        $userId = $request->userId;
        return $app_model->saveUserDeviceMapping($deviceId, $userId);
    }

    //get slider
    public function getSlider(Request $request)
    {
        $app_model = new AppModel;
        $sliderPosition = $request->sliderPosition;

        $result = $app_model->getSlider($sliderPosition);
        return $result;
    }

    // get upcoming competition
    public function getUpcomingCompetition(Request $request)
    {
        $app_model = new AppModel;
        $isViewAll = $request->isViewAll;
        return $app_model->getUpcomingCompetition($isViewAll);
    }

    //web start
    // validate login
    public function webValidateLogin(Request $request)
    {
        try {
            $email = $request->email;
            $password = $request->password;


            if (Auth::attempt(['user_email' => $email, 'password' => $password, 'user_status' => 'Active'])) {
                $query=DB::table('users')->where('user_email',$email)->first();
                if($query->email_verification==false){
                    return response()->json(['result' => 'error', 'message' => 'Please Activate your email']);
                }

                // Login Success
                $token = Auth::user()->createToken(random_int(100000, 999999))->plainTextToken;
                DB::table('users')
					->join('test_menu_permission', 'test_menu_permission.role_id', '=', 'users.role_id')
					->join('test_menu', 'test_menu_permission.menu_id', '=', 'test_menu.menu_id')
                    ->where('user_id', Auth::user()->user_id);
                $userData['first_name'] = Auth::user()->first_name;
                $userData['last_name'] = Auth::user()->last_name;
                $userData['full_name'] = Auth::user()->full_name;
				$userData['user_email'] = Auth::user()->user_email;



				$menuData= DB::table('users')
					->join('test_menu_permission', 'test_menu_permission.role_id', '=', 'users.role_id')
					->join('test_menu', 'test_menu_permission.menu_id', '=', 'test_menu.menu_id')
					->join('roles', 'test_menu_permission.role_id', '=', 'roles.id')
                    ->where('user_id', Auth::user()->user_id)
					->select('test_menu.menu_name','test_menu.menu_link','test_menu.menu_icon','roles.name')->get();
               $roleData= DB::table('users')
					->join('test_menu_permission', 'test_menu_permission.role_id', '=', 'users.role_id')
					->join('test_menu', 'test_menu_permission.menu_id', '=', 'test_menu.menu_id')
					->join('roles', 'test_menu_permission.role_id', '=', 'roles.id')
                    ->where('user_id', Auth::user()->user_id)
					->select('roles.name')->distinct()->get();



                return response()->json([
                    'result' => 'success', 'message' => 'Login Success', 'token' => $token,
                    'userData' => $userData, 'menuData'=>$menuData ,'roleData'=>$roleData               ]);
            } else {

                // Login Fail
                return response()->json(['result' => 'error', 'message' => 'Invalid Login Details']);
            }
        } catch (Exception $ex) {

            return response()->json(['result' => "exception", 'message' => 'Something went wrong']);
        }
    }

    // Logged user details with roles and permissions
    public function webGetLoggedUserDetailsWithRolesPermission()
    {

        try {
            $userRoles = Auth::user()->getRoleNames();
            $userPermissions = Auth::user()->getAllPermissions()->pluck('name');
            $userData['first_name'] = Auth::user()->first_name;
            $userData['last_name'] = Auth::user()->last_name;
            $userData['full_name'] = Auth::user()->full_name;


            return response()->json([
                'result' => 'success', 'userData' => $userData, 'userRole' => $userRoles,
                'userPermission' => $userPermissions
            ]);
        } catch (Exception $ex) {

            return response()->json(['result' => "exception", 'message' => 'Something went wrong']);
        }
    }
    // Logout
    public function webLogout()
    {
        try {

            Auth::user()->tokens()->delete();
            return response()->json(['result' => 'success', 'message' => 'Logged out succesfully']);
        } catch (Exception $ex) {

            return response()->json(['result' => "exception", 'message' => 'Something went wrong']);
        }
    }
    // get all roles
    public function webGetRoles(Request $request)
    {

        $itemsPerPage = $request->itemsPerPage;
        $sortColumn = $request->sortColumn;
        $sortOrder = $request->sortOrder;
        $searchText = $request->searchText;


        $getQuery = DB::table("roles")->select(['id', 'name', DB::raw("IF(is_role_active = 1, 'Active','Inactive')as is_role_active")])

            ->where('name', 'like', '%' . $searchText . '%')->orderBy($sortColumn, $sortOrder)
            ->paginate($itemsPerPage);
        return response()->json(['roleData' =>  $getQuery], 200);
    }
    //save Role
    public function webSaveRole(Request $request)
    {


        $roleName = trim($request->roleName);
        $saveQuery = DB::table('roles')->insertGetId(
            [
                'name' => $roleName,

            ]
        );
        if ($saveQuery > 0) {
            return response()->json(['message' => 'Role saved successfully'], 200);
        }
    }

    //Update Role
    public function webUpdateRole(Request $request)
    {


        $roleName = $request->roleName;
        $roleId = $request->roleId;
        $isRoleActive = $request->isRoleActive;
        $updateQuery = DB::table('roles')
            ->where('id', $roleId)
            ->update([
                'name' => $roleName,
                'is_role_active' => $isRoleActive,
                'updated_at' => now(),

            ]);
        if ($updateQuery > 0) {

            return response()->json(['message' => 'Role updated successfully'], 200);
        }
    }

    //Delete Role
    public function webDeleteRole(Request $request)
    {

        $roleId = $request->roleId;
        $deleteQuery = DB::table('roles')->where('id', $roleId)->delete();
        if ($deleteQuery > 0) {

            return response()->json(['message' => 'Role deleted successfully'], 200);
        }
    }

     public function GetWithoutPagination(Request $request)
    {

        $id = $request->id;
        $status = $request->status;
        $getQuery = DB::table("roles")->select(['id', 'name'])
            ->orderBy('id');
        if (isset($id)) {
            $getQuery->where('id', '=', $id);
            if (isset($status)) {
                $getQuery->where('is_role_active', '=', $status);
            } else {
                $getQuery->where('is_role_active', '=', 1);

            }
        } else {
            if (isset($status)) {
                $getQuery->where('is_role_active', '=', $status);
            } else {
                $getQuery->where('is_role_active', '=', 1);

            }

        }

        $getQuery = $getQuery->get();
        return response()->json(['resultData' => $getQuery], 200);
    }
    //web end
}
