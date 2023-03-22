<?php

namespace App\Http\Controllers\Api;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;

class AddressController extends Controller
{

	public function addAddress(Request $request){

		$userId = Auth::user()->id;

		if(Auth::user()->is_admin != 2){

			if($userId && $request->lat && $request->long != '') {

				$setDefaultAdd = Address::where('user_id', $userId)->exists();

					if($setDefaultAdd == '') {
						$user = Address::create([
							'user_id' => $userId,
							'lat' => isset($request->lat) ? $request->lat : '',
							'long' => isset($request->long) ? $request->long : '', 
							'default_address' => '1',
						]); 
					}else{
						$user = Address::create([
							'user_id' => $userId,
							'lat' => isset($request->lat) ? $request->lat : '',
							'long' => isset($request->long) ? $request->long : '',
							'default_address' => '0',
						]); 
					}

				return response([
					'success' => true,
					'message'=> 'Address store successfully.']
					,200);

			}else{  

				$validation = Validator::make($request->all(), [
					'lat' => 'required',
					'long' => 'required',
				]);

				if($validation->fails()){
					$fieldsWithErrorMessagesArray = $validation->messages()->get('*');
					return $fieldsWithErrorMessagesArray;
				} 
			}
		}else{

			if($userId && $request->lat && $request->long != '') {

				$setDefaultAdd = Address::where('user_id', $userId)->exists();

					if($setDefaultAdd == '') {
						$user = Address::create([
							'user_id' => $userId,
							'lat' => isset($request->lat) ? $request->lat : '',
							'long' => isset($request->long) ? $request->long : '',
							'default_address' => '1',
						]);
					}else{
						$user = Address::where('user_id', $userId)->update([
							'lat' => isset($request->lat) ? $request->lat : '',
							'long' => isset($request->long) ? $request->long : '',
							'default_address' => '1',
            			]);
					}
				
				return response([
					'success' => true,
					'message'=> 'Guest Address store successfully.']
					,200);

			}else{  

				$validation = Validator::make($request->all(), [
					'lat' => 'required',
					'long' => 'required',
				]);

				if($validation->fails()){
					$fieldsWithErrorMessagesArray = $validation->messages()->get('*');
					return $fieldsWithErrorMessagesArray;
				} 
			}

		}
	}

	public function getAddress(Request $request){

		if(Auth::user()->is_admin != 2){

			if(isset($request->user_id)) {

				$getAddress = Address::where('user_id',$request->user_id)->get();

				return response([
					'getaddress' => $getAddress,
					'success' => true,
					'message'=> 'successfully.']
					,200);
				
			}else{
				$validation = Validator::make($request->all(), [
					'user_id' => 'required',
				]);

				if($validation->fails()){
					$fieldsWithErrorMessagesArray = $validation->messages()->get('*');
					return $fieldsWithErrorMessagesArray;
				} 
			}

		}else{
			return response([
				'message'=> 'User Unauthenticated']
				,200);

		}
		
	}
	
}