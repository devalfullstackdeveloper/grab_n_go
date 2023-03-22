<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\BaseController as BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Auth;

class GuestUserController extends BaseController
{
    public function guestUser(Request $request)
    {

        $getGuestUserData = User::select()->where('is_admin',2)->first();
        $guestData= \Config::get('guestuser');

            if($guestData['guest_email'] == $getGuestUserData->email || $guestData['guest_password'] == $getGuestUserData->password){
        
                $token = $getGuestUserData->createToken('API Token')->accessToken;

                    return response()->json([
                        'success' => true,
                        'token' => $token,
                        'message' => "Pass the guest token.",
                    ], 200);
            
                }else{
                return response()->json([
                            'success' => true,
                            'message' => "Email and password not match token.",
                ], 200);
        }
    }   

}