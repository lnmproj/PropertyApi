<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgentController extends Controller
{

    public function agentapi(){
        $agents=User::
            join('roles','roles.id','=','users.role_id')
            ->join('user_details','user_details.user_id','=','users.user_id')

            ->where('users.role_id',26)
            ->where('is_role_active',1)
            ->where('user_details.isFeatured',true)
            ->select("*")
            ->get();


        return response()->json(['data'=>$agents]);
    }

    public function allagents(){
        $agents=User::
              join('roles','roles.id','=','users.role_id')
            ->join('user_details','user_details.user_id','=','users.user_id')

            ->where('users.role_id',26)
            ->where('is_role_active',1)

            ->select("*")
            ->get();


        return response()->json(['data'=>$agents]);
    }


    public function singleagents($slug){
        $agents=User::
        join('roles','roles.id','=','users.role_id')
            ->join('user_details','user_details.user_id','=','users.user_id')
            ->join('town','town.town_id','=','user_details.town_id')

            ->where('users.role_id',26)
            ->where('is_role_active',1)


            ->where('users.slug',$slug)
            ->get();


        return response()->json(['data'=>$agents]);
    }
}
