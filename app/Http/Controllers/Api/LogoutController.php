<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;


class LogoutController extends Controller
{
	public function logout(Request $request){
		$user = Auth::user()->token();
		$user->revoke();
		return 'logged out';
	}
}	

?>