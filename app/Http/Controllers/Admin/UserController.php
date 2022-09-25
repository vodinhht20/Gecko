<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\userRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private userRepository $userRepo)
    {

    }
    public function listUsers(){
        $users = $this->userRepo->paginate();
        return view('cpanel.users.list', compact('users'));
    }

    public function changeStatus(Request $request){
        $id = $request->get('id');
        $result = $this->userRepo->changeStatus($id);
        if ($result->email_verified_at) {
            return response()->json([
                "status" => true,
                "message" => "Đã kích hoạt tài khoản!"
            ]); 
        }
        return response()->json([
            "status" => false,
            "message" => "Đã hủy kích hoạt tài khoản!"
        ]); 
    }
}
