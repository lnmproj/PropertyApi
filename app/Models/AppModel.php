<?php

namespace App\Models;

use App\Models\User;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Model;

class AppModel extends Model
{

    // To check User Status
    public function checkUserStatus($userId)
    {
        try {
            $getQuery = DB::table('users')
                ->where('user_id', '=', $userId)
                ->select([
                    'user_id',
                    'role_id',
                    'user_mobile',
                    'user_first_name',
                    'user_last_name',
                    'user_full_name',
                    'user_email',
                    'user_age',
                    'user_weight',
                    'user_height',
                    'user_gender',
                    'user_profile_image',
                    'user_status',
                    'is_subscribed',
                    'question_id',
                    'question_answer',
                    'device_id',
                    'bmi_score',
                    DB::raw('DATE_FORMAT(user_signup_date, "%d-%m-%Y") as user_signup_date', "%d-%m-%Y"),
                ])
                ->get();
            $getResult = $getQuery->toArray();
            if ($getQuery->count() > 0) {
                if ($getResult[0]->user_status == 1) {
                    return response()->json(['userData' => $getResult, 'result' => 'success', 'message' => 'success']);
                } else {
                    return response()->json(['userData' => [], 'result' => 'blocked', 'message' => 'Account is suspended/blocked']);
                }
            } else {
                return response()->json(['userData' => [], 'result' => 'not found', 'message' => 'User not found']);
            }

        } catch (Exception $ex) {
            return response()->json(['userData' => [], 'result' => 'error', 'message' => 'Something went wrong']);
        }
    }

    // To check Mobile Number
    public function checkMobileNumber($mobileNumber)
    {
        try {
            $getQuery = DB::table('users')
                ->where('user_mobile', '=', $mobileNumber)
                ->select([
                    'user_id',
                    'role_id',
                    'user_mobile',
                    'user_first_name',
                    'user_last_name',
                    'user_full_name',
                    'user_email',
                    'user_age',
                    'user_weight',
                    'user_height',
                    'user_gender',
                    'user_profile_image',
                    'user_status',
                    'is_subscribed',
                    'question_id',
                    'question_answer',
                    'device_id',
                    'bmi_score',
                    DB::raw('DATE_FORMAT(user_signup_date, "%d-%m-%Y") as user_signup_date', "%d-%m-%Y"),
                ])
                ->get();
            if ($getQuery->count() > 0) {
                if ($getQuery[0]->user_status == 1) {
                    return response()->json(['userData' => $getQuery, 'result' => 'success', 'message' => 'success']);
                } else {
                    return response()->json(['userData' => [], 'result' => 'blocked', 'message' => 'Account is suspended/blocked']);
                }
            } else {
                return response()->json(['userData' => [], 'result' => 'not found', 'message' => 'User not found']);
            }
        } catch (Exception $ex) {
            return response()->json(['userData' => [], 'result' => 'error', 'message' => $ex->getMessage()]);
        }

    }

    // save mobile number
    public static function saveMobileNumber($mobileNumber, $deviceType, $isRegisteredUser, $userId, $userDeviceToken)
    {
        try {
            $newUserId = '';
            DB::beginTransaction();
            $password = random_int(100000, 999999);
            if ($isRegisteredUser == "1") {
                // existing user
                $newUserId = $userId;
                $user = User::where('user_id', $userId)->first();
                $token = $user->createToken($password)->plainTextToken;
                DB::table('users')
                    ->where('user_id', $userId)
                    ->update(['auth_token' => $token, 'user_device_token' => $userDeviceToken]);

            } else {
                // New User
                $newUserId = DB::table('users')->insertGetId(
                    [

                        'user_mobile' => $mobileNumber,
                        'password' => bcrypt($password),
                        'password_normal' => $password,
                        'user_device_type' => $deviceType,
                        'role_id' => 3,
                    ]
                );
                $user = User::where('user_id', $newUserId)->first();
                $token = $user->createToken($password)->plainTextToken;
                DB::table('users')
                    ->where('user_id', $newUserId)
                    ->update(['auth_token' => $token, 'user_device_token' => $userDeviceToken]);

            }

            DB::commit();
            return response()->json(['result' => 'success', 'token' => $token, 'userId' => $newUserId]);

        } catch (Exception $ex) {

            DB::rollback();

            return response()->json(['result' => "error", 'message' => 'Something went wrong']);
        }

    }

    // Update Device Token
    public function updateDeviceToken($userId, $userDeviceToken)
    {
        DB::table('users')
            ->where('user_id', $userId)
            ->update(['user_device_token' => $userDeviceToken]);

        $getResult['result'] = "success";
        return $getResult;
    }

    // update name and gender
    public static function updateNameGender($firstName, $lastName, $gender, $userId)
    {
        try {

            DB::beginTransaction();

            DB::table('users')
                ->where('user_id', $userId)
                ->update([
                    'user_first_name' => ucfirst($firstName),
                    'user_last_name' => ucfirst($lastName),
                    'user_full_name' => ucfirst($firstName) . ' ' . ucfirst($lastName),
                    'user_gender' => $gender,
                ]);

            DB::commit();
            return response()->json(['result' => 'success', 'message' => 'Details updated successfully']);

        } catch (Exception $ex) {

            DB::rollback();

            return response()->json(['result' => "error", 'message' => 'Something went wrong']);
        }

    }

    // save bmi history
    public static function saveBMIHitory($userId, $userAge, $userWeight, $userHeight, $bmiScore)
    {
        try {

            DB::beginTransaction();
            DB::table('bmi_history')->insertGetId(
                [

                    'user_id' => $userId,
                    'user_age' => $userAge,
                    'user_weight' => $userWeight,
                    'user_height' => $userHeight,
                    'bmi_score' => $bmiScore,

                ]
            );

            DB::table('users')
                ->where('user_id', $userId)
                ->update([
                    'user_age' => $userAge,
                    'user_weight' => $userWeight,
                    'user_height' => $userHeight,
                    'bmi_score' => $bmiScore,
                ]);

            DB::commit();
            return response()->json(['result' => 'success', 'message' => 'BMI updated successfully']);

        } catch (Exception $ex) {

            DB::rollback();

            return response()->json(['result' => "error", 'message' => 'Something went wrong']);
        }

    }

    public function getQuestion()
    {
        try {
            $getQuery = DB::table('questions')
                ->where('question_status', '=', 1)
                ->select([
                    'question_id',
                    'question_text',
                    'option_1',
                    'option_2',
                    'option_3',
                    'option_4',
                ])
                ->get();
            $getResult = $getQuery->toArray();

            return response()->json(['questionData' => $getResult, 'result' => 'success', 'message' => 'success']);

        } catch (Exception $ex) {
            return response()->json(['questionData' => [], 'result' => 'error', 'message' => 'Something went wrong']);
        }
    }

    // to update user answer
    public static function updateUserAnswer($userId, $answer, $questionId)
    {
        try {

            DB::beginTransaction();

            DB::table('users')
                ->where('user_id', $userId)
                ->update([
                    'question_id' => $questionId,
                    'question_answer' => $answer,

                ]);

            DB::commit();
            return response()->json(['result' => 'success', 'message' => 'Answer updated successfully']);

        } catch (Exception $ex) {

            DB::rollback();

            return response()->json(['result' => "error", 'message' => 'Something went wrong']);
        }

    }
    // to get device list
    public static function getDeviceList($devicePlatform)
    {
        try {
            $getQuery = DB::table('device')

                ->select([
                    'device_id',
                    'device_name',
                    'device_type',
                    'device_logo',
                    'is_device_auth_required',
                    'device_auth_param',
                    'device_auth_url',
                    'is_device_app_installation_required',
                    'device_app_android_url',
                    'device_app_ios_url',
                    'android_package_name',
                    'ios_package_name',

                ])
                ->where('device_status', 1)
                ->whereRaw('FIND_IN_SET("' . $devicePlatform . '",device_os_type)')
                ->get();

            $getResult = $getQuery->toArray();

            return response()->json(['deviceData' => $getResult, 'result' => 'success', 'message' => 'success']);
        } catch (Exception $ex) {

            return response()->json(['result' => "error", 'message' => 'Something went wrong']);
        }

    }

    // save device user mapping
    public static function saveUserDeviceMapping($deviceId, $userId)
    {
        try {

            DB::beginTransaction();
            $insertId = DB::table('user_device_mapping')->insertGetId(
                [

                    'device_id' => $deviceId,
                    'user_id' => $userId,

                ]
            );
            DB::table('user_device_mapping')
                ->where('device_id', '!=', $deviceId)
                ->where('user_id', '=', $userId)
                ->update([
                    'device_mapping_status' => 0,

                ]);

            DB::table('users')
                ->where('user_id', '=', $userId)
                ->update([
                    'device_id' => $deviceId,

                ]);

            DB::commit();
            return response()->json(['result' => 'success', 'message' => 'Device details updated successfully']);

        } catch (Exception $ex) {

            DB::rollback();

            return response()->json(['result' => "error", 'message' => 'Something went wrong']);
        }

    }
    //get slider
    public function getSlider($sliderPosition)
    {
        try {
            $getQuery = DB::table('slider')
                ->where('slider_status', '=', 1)
                ->where('slider_position', '=', $sliderPosition)
                ->whereRaw('now() between slider_start_date and slider_end_date')
                ->select([
                    'slider_id',
                    'slider_name',
                    'slider_image',
                    'slider_action',
                ])
                ->get();
            $getResult = $getQuery->toArray();

            return response()->json(['sliderData' => $getResult, 'result' => 'success', 'message' => 'success']);

        } catch (Exception $ex) {
            return response()->json(['sliderData' => [], 'result' => 'error', 'message' => 'Something went wrong']);
        }
    }
    // get upcoming competition
    public function getUpcomingCompetition($isViewAll)
    {
        try {
            if ($isViewAll == "1") {
                // all
                $getQuery = DB::table('competition_master')
                    ->join('competition_type', 'competition_type.comp_type_id', '=', 'competition_master.comp_type_id')
                    ->join('competition_schedule', 'competition_schedule.comp_master_id', '=', 'competition_master.comp_master_id')
                    ->where('competition_master.comp_master_status', '=', 1)
                    ->whereRaw('competition_schedule.comp_schedule_start_date>date_add(curdate(),interval competition_schedule.comp_display_filter_days Day)')
                    ->select(['competition_master.comp_master_id',
                        'competition_master.comp_master_name',
                        'competition_master.comp_master_days',
                        'competition_master.comp_master_total_km',
                        'competition_master.comp_master_minimum_km_per_day',
                        'competition_master.comp_master_max_participant',
                        'competition_master.comp_master_min_participant',
                        'competition_master.comp_master_pot_value',
                        'competition_master.comp_master_per_partition_value',
                        'comp_master_rest_day',
                        'competition_master.comp_master_rest_day_frequency',
                        'competition_master.daily_loyalty_point',
                        'competition_master.achievers_loyalty_point',
                        'competition_type.comp_type_name',

                    ])
                    ->distinct()
                    ->paginate();

                return response()->json(['compData' => $getQuery, 'result' => 'success', 'message' => 'success']);

            } else {
                $getQuery = DB::table('competition_master')
                    ->join('competition_type', 'competition_type.comp_type_id', '=', 'competition_master.comp_type_id')
                    ->join('competition_schedule', 'competition_schedule.comp_master_id', '=', 'competition_master.comp_master_id')
                    ->where('competition_master.comp_master_status', '=', 1)
                    ->whereRaw('competition_schedule.comp_schedule_start_date>date_add(curdate(),interval competition_schedule.comp_display_filter_days Day)')
                    ->select(['competition_master.comp_master_id',
                        'competition_master.comp_master_name',
                        'competition_master.comp_master_days',
                        'competition_master.comp_master_total_km',
                        'competition_master.comp_master_minimum_km_per_day',
                        'competition_master.comp_master_max_participant',
                        'competition_master.comp_master_min_participant',
                        'competition_master.comp_master_pot_value',
                        'competition_master.comp_master_per_partition_value',
                        'competition_master.comp_master_rest_day',
                        'competition_master.comp_master_rest_day_frequency',
                        'competition_master.daily_loyalty_point',
                        'competition_master.achievers_loyalty_point',
                        'competition_type.comp_type_name',

                    ])
                    ->distinct()
                    ->paginate(10);

                return response()->json(['compData' => $getQuery, 'result' => 'success', 'message' => 'success']);

            }

        } catch (Exception $ex) {
            return response()->json(['compData' => [], 'result' => 'error', 'message' => 'Something went wrong']);
        }
    }

}
