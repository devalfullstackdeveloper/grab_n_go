<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Banner;
use App\Models\MainCategory;
use Auth;

class HomeController extends Controller
{

	public function home(){

		$getBanner = Banner::select()->get();
		$baseUrl= \Config::get('baseurl');

		$data = array();
		
		foreach ($getBanner as $key => $value) {
			$data[] = array(
				'id' =>  $value->id,         
				'banner_image' =>  $baseUrl['base_url'].$value->banner_image,         
				'banner_offer_type' =>  $value->banner_offer_type,
			);         
		}

		$getMainCategory = MainCategory::select()->get();

		return response(['banner' => $data, 'maincategory' =>$getMainCategory,
			'message' => 'Successful',
			'status' => 200], 200);
	}
	
}