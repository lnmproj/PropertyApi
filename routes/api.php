<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\BrokerAssociationController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;
use App\Http\Controllers\TownController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\BrokerController;
use App\Http\Controllers\AgriTypeController;
use App\Http\Controllers\BarangayController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\CapabilityController;
use App\Http\Controllers\UserSkillsController;
use App\Http\Controllers\ProductModeController;
use App\Http\Controllers\SubdivisionController;






use App\Http\Controllers\ZonningCodeController;
use App\Http\Controllers\PropertyTypeController;
use App\Http\Controllers\SpecializationController;
use App\Http\Controllers\PropertyClassificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});
Route::get('/viewclear', function () {
    Artisan::call('view:clear');
});
Route::get('/routeclear', function () {
    Artisan::call('route:clear');
});
Route::get('/configcache', function () {
    Artisan::call('config:cache');
});
Route::get('/cacheclear', function () {
    Artisan::call('cache:clear');
});

Route::get('/configclear', function () {
    Artisan::call('config:clear');
});
// Check mobile number
Route::post('checkMobileNumber', [AppController::class, 'checkMobileNumber']);
// Send Signup OTP
Route::get('sendSignupOTP/{mobile}/{otp}', [AppController::class, 'sendSignupOTP']);

// Save mobile number
Route::post('saveMobileNumber', [AppController::class, 'saveMobileNumber']);

//

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('checkUserStatus', [AppController::class, 'checkUserStatus']);

    // Update Device Token
    Route::post('updateDeviceToken', [AppController::class, 'updateDeviceToken']);

    // Update Name and Gender
    Route::post('updateNameGender', [AppController::class, 'updateNameGender']);

    // Insert BMI hitory
    Route::post('saveBMIHitory', [AppController::class, 'saveBMIHitory']);

    Route::post('getQuestion', [AppController::class, 'getQuestion']);

    // update user answer
    Route::post('updateUserAnswer', [AppController::class, 'updateUserAnswer']);

    // get device list
    Route::post('getDeviceList', [AppController::class, 'getDeviceList']);

    //save device mapping
    Route::post('saveUserDeviceMapping', [AppController::class, 'saveUserDeviceMapping']);
    //get slider
    Route::post('getSlider', [AppController::class, 'getSlider']);
    //get upcoming competition
    Route::get('getUpcomingCompetition', [AppController::class, 'getUpcomingCompetition']);
});

// for testing purpose
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/sanctum/token', function (Request $request) {

    $user = User::where('user_id', $request->userId)->first();

    return $user->createToken($request->token_name)->plainTextToken;
});

Route::middleware('auth:sanctum')->get('/user/revoke', function (Request $request) {
    $user = $request->user();
    $user->tokens()->delete();
    return 'Deleted';
});

// testing purpose end


// web start
 Route::get('GetUser', [UserController::class, 'Get']);
Route::post('webValidateLogin', [AppController::class, 'webValidateLogin']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    // get logged user details with role and permission
    Route::get('webGetLoggedUserDetailsWithRolesPermission', [AppController::class, 'webGetLoggedUserDetailsWithRolesPermission']);

	 // Assign Individual Permission RoleWise
    Route::post('webAssignIndividualPermissionRoleWise', [AppController::class, 'assignIndividualPermissionRoleWise']);

	 // Assign Permission Role Wise
    Route::post('webAssignPermissionRoleWise', [AppController::class, 'assignPermissionRoleWise']);


	 // getAssignedUnAssignedPermission
    Route::get('getAssignedUnAssignedPermission', [UserController::class, 'getAssignedUnAssignedPermission']);


	//Logout
    Route::post('webLogout', [AppController::class, 'webLogout']);

    // Roles
    Route::get('webGetRoles', [AppController::class, 'webGetRoles']);
    Route::get('webGetRolesWithoutPagination', [AppController::class, 'GetWithoutPagination']);

    Route::post('webSaveRole', [AppController::class, 'webSaveRole']);
    Route::post('webUpdateRole', [AppController::class, 'webUpdateRole']);
    Route::post('webDeleteRole', [AppController::class, 'webDeleteRole']);


    // Capability
    Route::get('GetCapability', [CapabilityController::class, 'Get']);
    Route::post('SaveCapability', [CapabilityController::class, 'Save']);
    Route::post('UpdateCapability', [CapabilityController::class, 'Update']);
    Route::post('DeleteCapability', [CapabilityController::class, 'Delete']);

    // ZonningCode
    Route::get('GetZonningCode', [
        ZonningCodeController::class, 'Get'
    ]);
    Route::post('SaveZonningCode', [ZonningCodeController::class, 'Save']);
    Route::post('UpdateZonningCode', [ZonningCodeController::class, 'Update']);
    Route::post('DeleteZonningCode', [ZonningCodeController::class, 'Delete']);

    // agriType
    Route::get('GetAgriType', [AgriTypeController::class, 'Get']);
    Route::post('SaveAgriType', [AgriTypeController::class, 'Save']);
    Route::post('UpdateAgriType', [AgriTypeController::class, 'Update']);
    Route::post('DeleteAgriType', [AgriTypeController::class, 'Delete']);

    // Specialization
    Route::get('GetSpecialization', [SpecializationController::class, 'Get']);
    Route::post('SaveSpecialization', [SpecializationController::class, 'Save']);
    Route::post('UpdateSpecialization', [
        SpecializationController::class, 'Update'
    ]);
    Route::post('DeleteSpecialization', [
        SpecializationController::class, 'Delete'
    ]);
    // UserSkills
    Route::get('GetUserSkills', [UserSkillsController::class, 'Get']);
    Route::get('GetUserSkillWithoutPagination', [UserSkillsController::class, 'GetWithoutPagination']);

    Route::post('SaveUserSkills', [UserSkillsController::class, 'Save']);
    Route::post('UpdateUserSkills', [
        UserSkillsController::class, 'Update'
    ]);
    Route::post('DeleteUserSkills', [
        UserSkillsController::class, 'Delete'
    ]);
    // Subdivision
Route::get('GetSubdivisionWithoutPagination', [SubdivisionController::class, 'GetWithoutPagination']);


// Barangay
 Route::get('GetBarangayWithoutPagination', [BarangayController::class, 'GetWithoutPagination']);
    Route::get('GetBarangay', [BarangayController::class, 'Get']);

Route::post('SaveBarangay', [BarangayController::class, 'Save']);
Route::post('UpdateBarangay', [
    BarangayController::class, 'Update',
]);
Route::post('DeleteBarangay', [
    BarangayController::class, 'Delete',
]);
// Town
	  Route::get('GetTown', [TownController::class, 'Get']);
 Route::get('GetTownWithoutPagination', [TownController::class, 'GetWithoutPagination']);


Route::post('SaveTown', [TownController::class, 'Save']);
Route::post('UpdateTown', [
    TownController::class, 'Update',
]);
Route::post('DeleteTown', [
    TownController::class, 'Delete',
]);

// Province
 Route::get('GetProvinceWithoutPagination', [ProvinceController::class, 'GetWithoutPagination']);
    Route::get('GetProvince', [ProvinceController::class, 'Get']);

Route::post('SaveProvince', [ProvinceController::class, 'Save']);
Route::post('UpdateProvince', [
    ProvinceController::class, 'Update',
]);
Route::post('DeleteProvince', [
    ProvinceController::class, 'Delete',
]);


// User
Route::post('SaveUser', [UserController::class, 'Save']);


//Agency
Route::get('/GetAgencyWithoutPagination', [AgencyController::class, 'GetWithoutPagination']);
Route::get('/getagency',[AgencyController::class,'Get'])->name('Get');
Route::post('/saveagency',[AgencyController::class,'Save'])->name('saveAgency');
Route::post('/updateagency',[AgencyController::class,'Update'])->name('updateAgency');
Route::post('/deleteagency',[AgencyController::class,'Delete'])->name('deleteAgency');


//Broker
Route::get('GetBrokerWithoutPagination', [BrokerController::class, 'GetWithoutPagination']);
    Route::get('/getbroker',[BrokerController::class,'Get'])->name('GetBroker');
    Route::post('/savebroker',[BrokerController::class,'Save'])->name('savebroker');
    Route::post('/updatebroker',[BrokerController::class,'Update'])->name('updatebroker');
    Route::post('/deletebroker',[BrokerController::class,'Delete'])->name('deletebroker');
//Broker Association

    Route::get('/getbrokerassociation',[BrokerAssociationController::class,'Get'])->name('getbrokerassociation');
    Route::post('/savebrokerassociation',[BrokerAssociationController::class,'Save'])->name('savebrokerassociation');
    Route::post('/updatebrokerassociation',[BrokerAssociationController::class,'Update'])->name('updatebrokerassociation');
    Route::post('/deletebrokerassociation',[BrokerAssociationController::class,'Delete'])->name('deletebrokerassociation');

	//Product Mode

    Route::get('GetProductMode', [ProductModeController::class, 'Get']);

Route::post('SaveProductMode', [ProductModeController::class, 'Save']);
Route::post('UpdateProductMode', [
    ProductModeController::class, 'Update',
]);
Route::post('DeleteProductMode', [
    ProductModeController::class, 'Delete',
]);

//Property classification

Route::get('GetPropertyClassification', [PropertyClassificationController::class, 'Get']);

Route::post('SavePropertyClassification', [PropertyClassificationController::class, 'Save']);
Route::post('UpdatePropertyClassification', [
    PropertyClassificationController::class, 'Update',
]);
Route::post('DeletePropertyClassification', [
    PropertyClassificationController::class, 'Delete',
]);

//Property Type

Route::get('GetPropertyType', [PropertyTypeController::class, 'Get']);

Route::post('SavePropertyType', [PropertyTypeController::class, 'Save']);
Route::post('UpdatePropertyType', [
    PropertyTypeController::class, 'Update',
]);
Route::post('DeletePropertyType', [
    PropertyTypeController::class, 'Delete',
]);


//Product category

Route::get('GetProductCategory', [CategoryController::class, 'Get']);

Route::post('SaveProductCategory', [CategoryController::class, 'Save']);
Route::post('UpdateProductCategory', [
    CategoryController::class, 'Update',
]);
Route::post('DeleteProductCategory', [
    CategoryController::class, 'Delete',
]);



});


Route::get('/getfeatured',[PropertyController::class,'getProperty'])->name('getProperty');
Route::post('/saveproperty',[PropertyController::class,'saveproperty'])->name('saveProperty');
Route::get('/agentapi',[AgentController::class,'agentapi'])->name('agentapi');
Route::get('/allagents',[AgentController::class,'allagents'])->name('allagents');
Route::get('/singleagents/{slug?}',[AgentController::class,'singleagents'])->name('singleagents');
