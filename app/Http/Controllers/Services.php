<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class Services extends Controller
{
	public function dashboard(){
		$getBillDetails = DB::table('bill_details')
		->select('bill_details.bill_no', 'bill_details.bill_gen_by','bill_details.bill_to_name')
		->join('users','users.id','bill_details.bill_gen_by')
		->get();
		return json_encode($getBillDetails);
	}

	public function login(Request $request) {
		$userName = $request->user_name;
		$userPass = $request->password;
		$resultArr = [];

		$checkUser = DB::table('users')
		->select('id', 'full_name', 'password')
		->where('user_name', $userName)
		->first();

		if($checkUser){
			$userHashPass = $checkUser->password;
		} else {
			$resultArr = ['login_status_code' => 100, 'login_status_msg' => 'User not found'];
			return json_encode($resultArr);
		}

		if(!Hash::check($userPass, $userHashPass)){
			$resultArr = ['login_status_code' => 101, 'login_status_msg' => 'Invalid Credentials'];
			return json_encode($resultArr);
		} else {
			$resultArr = ['login_status_code' => 201, 'login_status_msg' => 'Login Success', 'User_name' => $checkUser->full_name];
			return $resultArr;
		}
	}

}