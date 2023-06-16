<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Auth;


class UserProfileController extends Controller
{
	public function editUserProfile(Request $request)
	{

		if($request->user_id){

			$userIdExists = User::where('id',$request->user_id)->exists();
			if($userIdExists){

				$editProfile = User::where('id',$request->user_id)->update(array(
					"first_name" => $request->first_name,
					"last_name" => $request->last_name,
				));
				return response()->json([
					"success" => true,
					"messageCode" => 1,
					"message" => "User profile updated successfully",	
				]);

			}else{

				return response()->json([
					"message" => "user id doesn't exists",	
				]);
			}
			
		}else{
			$validator = Validator::make($request->all(), [
				'user_id' => 'required',	
				'first_name' => 'required',	
				'last_name' => 'required',	
			]);

			if($validator->fails()){
				return response(['error' => $validator->errors(), 
					'Validation Error']);
			}
		}
	}

	public function editMobileNumber(Request $request)
	{
		$userId = Auth::user()->id;
		if(isset($request->otp)){

			$validation = Validator::make($request->all(),[ 
				'mobile_no' => 'required',
				'otp' => 'required|min:6|max:6'
			]);

			if($validation->fails()){

                //Return the validation error
				$fieldsWithErrorMessagesArray = $validation->messages()->get('*');
				return $fieldsWithErrorMessagesArray;

			} else{

				$getUser = User::select()->where('id',$userId)->first();

				if($request->otp == $getUser->otp){

					$user = User::select()
					->where('id',$userId)
					->update(array('mobile_no' => $request->mobile_no));                

					$token = $getUser->createToken('API Token')->accessToken;

					return response([
						'success' => true,  
						'message'=> 'mobile number and otp updated successfully.',
						'token' => $token
					],200);	
				} else {
					return response()->json([
						'success' => false,
						'message' => "incorrect OTP",
					], 200);
				}
			}
		}
		else{
			$validation = Validator::make($request->all(), [
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
				$user = User::select()
				->where('id',$userId)
				->update(array('otp' => $otp));

				return response([
					'success' => true,
					'otp' => $otp,
					'message'=> 'OTP for Update number is send successfully.']
					,200);
			}
		}
		
	}
	public function generateOtp(){
		$pin = mt_rand(100000,999999);
            // shuffle the result
		$string = str_shuffle($pin);
		return $string;
	}

	//fetch user details
	public function userDetails(){
		$userId = Auth::user()->id;
		$userDetail = User::select()->where('id',$userId)->first();
		return response([
			'success' => true,
			'user' => $userDetail,
			'message'=> 'successfully']
			,200);
	
	}

}



