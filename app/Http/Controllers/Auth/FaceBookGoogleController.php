<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class FaceBookGoogleController extends Controller
{

    public function faceBookGoogleRegistarion(Request $request){

        if(isset($request->email))
        {
            //validation in facebook and google API
            $validation = Validator::make($request->all(),[ 
                'email' => 'required|email|max:255',
            ]);
            
            if($validation->fails()){

                //Return the validation error
                $fieldsWithErrorMessagesArray = $validation->messages()->get('*');
                return $fieldsWithErrorMessagesArray;

            } else {
               $get_user = User::select()
               ->where('email',$request->email)
               ->first();

               if(isset($get_user))
               {
                return response()->json([
                    "data" => $get_user,
                    'success' => true,
                    'message' => "Registration is successfully done",
                ], 200);
            } 
            else {

                return response()->json([
                    'message' => "User not registrer",
                ], 200);
            }   
        }
    } else {

        $validation = Validator::make($request->all(),[ 
            'email' => 'required|email|max:255',
        ]);

        if($validation->fails()){

            //Return the validation error
            $fieldsWithErrorMessagesArray = $validation->messages()->get('*');
            return $fieldsWithErrorMessagesArray;
        }

    }
}
}
