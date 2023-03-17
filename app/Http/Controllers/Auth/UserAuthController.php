<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\BaseController as BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Auth;


class UserAuthController extends BaseController
{
    public function register(Request $request)
    {
        if(isset($request->otp))
        {
            //validation in register API
            $validation = Validator::make($request->all(),[ 
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => 'required|email|max:255',
                'mobile_no' => 'required',
                'otp' => 'required|min:6|max:6'
            ]);

            if($validation->fails()){

                //Return the validation error
                $fieldsWithErrorMessagesArray = $validation->messages()->get('*');
                return $fieldsWithErrorMessagesArray;

            } else{
                $get_user = User::select()
                ->where('mobile_no',$request->mobile_no)
                ->where('otp',$request->otp)
                ->first();

                if(isset($get_user))
                {
                $token = $get_user->createToken('API Token')->accessToken;
                    
                    return response()->json([
                        'success' => true,
                         "code" => 1,
                         'token' => $token,
                         'user' => $get_user,
                        'message' => "Registration is successfully done",
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => "No data found please re-registered or OTP is in-correct",
                    ], 200);
                }
            }
        }else{
            $validation = Validator::make($request->all(), [
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'mobile_no' => 'required|unique:users',
            ]);


            if($validation->fails()){

            //Return the validation error
                $fieldsWithErrorMessagesArray = $validation->messages()->get('*');
                return $fieldsWithErrorMessagesArray;

            } else{

                /* Generate OTP */
                $otp = $this->generateOtp();

                //create the users after validate
                $user = User::create([
                    'first_name' => $request['first_name'],
                    'last_name' => $request['last_name'],
                    'email' => $request['email'],
                    'password' => bcrypt($otp),
                    'mobile_no' => $request['mobile_no'],
                    'social_id' => isset($request->social_id) ? $request->social_id : null,
                    'social_type' => isset($request->social_type) ? $request->social_type : null,
                    'otp' => $otp,
                ]);

                

                //$token = $user->createToken('API Token')->accessToken;
                
                return response([
                    'success' => true,
                    'otp' => $otp,
                    //'token' => $token,
                    'message'=> 'OTP for registration sent successfully.']
                    ,200);

            }
        }
    }   

    public function login(Request $request)
    {

       if(isset($request->otp))
       {
        $validation = Validator::make($request->all(),[ 
            'mobile_no' => 'required',
             'otp' => 'required',
        ]);

        if($validation->fails()){

                    //Return the validation error
            $fieldsWithErrorMessagesArray = $validation->messages()->get('*');
            return $fieldsWithErrorMessagesArray;
        }
        else
        {
            $user = User::where('mobile_no', $request->mobile_no)->first();

            if($user->otp == $request->otp)
            {
                //User authenticated
                Auth::login($user); 

                $token = Auth()->user()->createToken('API Token')->accessToken;

                return response([
                   'success' => true,
                   "code" => 1,
                   'message' => "Login successfully",
                   'user' => $user,
                   'token' => $token]);
                //return response(['user' => Auth()->user(), 'token' => $token]);

            }
            else
            {
                //User not authenticated otp wrong
                return response(['message' => "No data found or OTP is in-correct"]);

            }

        }
        }else{
            $validation = Validator::make($request->all(),[ 
                'mobile_no' => 'required',
            ]);

            if($validation->fails()){
                    //Return the validation error
                $fieldsWithErrorMessagesArray = $validation->messages()->get('*');
                return $fieldsWithErrorMessagesArray;
            }else{
                    $user = User::where('mobile_no', $request->mobile_no)->first();
                    if ($user) { 
                       $get_user_OTP = User::select('otp')->where('mobile_no',$request->mobile_no)->first();
                       if($get_user_OTP)
                       {

                        return response()->json([
                            'success' => true,
                            'message' => "OTP sent successfully",
                            'otp' => $get_user_OTP->otp
                        ], 200);
                    }
                    else
                    {
                        return response()->json([
                            'success' => false,
                            'message' => "Error for sending OTP",
                        ], 200);
                    }
                }else{
                    return response(['success' => false,
                        'message' => "No data was found on this mobile number please Sign-up first"]);

                }
            }

        }
    }
    
    public function generateOtp(){
        $pin = mt_rand(100000,999999);
            // shuffle the result
        $string = str_shuffle($pin);
        return $string;
    }

}