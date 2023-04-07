<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Auth;


class UserProfileController extends Controller
{
	public function EditUserProfile(Request $request)
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
}



