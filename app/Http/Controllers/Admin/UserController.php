<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\userRepository;
use Illuminate\Http\Request;
use Alert;

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

    public function editUserForm($id)
    {
        $user = $this->userRepo->find($id);
        return view('cpanel.users.edit', compact('user'));
    }

    public function editUser(Request $request, $id)
    {
        $user = $this->userRepo->find($id);
        if ($request->input('roles')) {
            $user->syncRoles($request->input('roles'));
        }

        $attributes = [];
        if (isset($request->name)) {
            $attributes['name'] = $request->name;
        }

        if (isset($request->email)) {
            $attributes['email'] = $request->email;
        }

        if (isset($request->password)) {
            $attributes['password'] = $request->password;
        }

        if (isset($request->cash)) {
            $attributes['cash'] = $request->cash;
        }

        $result = $this->userRepo->update($id, $attributes);

        if ($result) {
            Alert::success('Cập nhật thành công');
            return redirect()->route('admin.users.listUsers');
        }
        Alert::warning('Cập nhật thất bại');
        return redirect()->route('admin.users.listUsers');
    }
}
