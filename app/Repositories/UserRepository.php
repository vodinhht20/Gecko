<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class userRepository extends BaseRepository
{
    public function getModel()
    {
        return \App\Models\User::class;
    }

    public function register($arrData = [])
    {
        $user = new $this->model;
        $user->name = $arrData['name'];
        $user->email = $arrData['email'];
        $user->password = bcrypt($arrData['password']);
        $user->cash = 0;

        if (isset($arrData['avatar'])) {
            $user->avatar = $arrData['avatar'];
        }
        if (isset($arrData['email_verified_at'])) {
            $user->email_verified_at = $arrData['email_verified_at'];
        }
        $user->save();
        return $user;
    }

    public function updateTokenVerifyEmail($arrData = [])
    {
        $user = $this->find($arrData['id']);
        $user->email_confirm_token = $arrData['email_confirm_token'];
        $user->save();
    }

    public function getUserByEmail($email)
    {
        $user = $this->model->where('email', $email)->first();
        if ($user) {
            return $user;
        }
        return false;
    }

    public function confirmEmail($id)
    {
        $user = $this->model->find($id);
        if ($user) {
            $user->email_verified_at = now();
            $user->save();
            return $user;
        }
        return false;
    }

    public function changePasssword($newPass, $id)
    {
        $user = $this->model->find($id);
        if ($user) {
            $user->password = bcrypt($newPass);
            $user->save();
            return $user;
        }
        return false;
    }

    public function changeStatus($id)
    {
        $user = $this->model->find($id);
        if ($user) {
            empty($user->email_verified_at) ? $user->email_verified_at = Carbon::now()->format('Y-m-d H:i:s') : $user->email_verified_at = null;
            $user->save();
            return $user;
        }
        return false;
    }

    public function blockUser($id)
    {
        $user = $this->model->find($id);
        if ($user) {
            $user->status = config('user.status.block');
            $user->save();
            return $user;
        }
    }

    public function unBlockUser($id)
    {
        $user = $this->model->find($id);
        if ($user) {
            $user->status = config('user.status.active');
            $user->save();
            return $user;
        }
    }

    public function getUserBlock($take = 10)
    {
        return $this->model
            ->where('status', config('user.status.block'))
            ->orderBy('updated_at', 'desc')
            ->with('position')
            ->paginate($take);
    }

    public function getRoleByUser($id)
    {
        return $this->model->find($id)->roles->pluck('name');
    }

    public function changeRole($roles = [], $modelId)
    {
        $user = $this->model->find($modelId);
        if (!$user) {
            return false;
        }
        $user->syncRoles($roles);
        return $user;
    }

    public function checkPassword($email, $password)
    {
        $user = $this->getUserByEmail($email);
        if ($user && Hash::check($password, $user->password)) {
            return $user;
        }
        return false;
    }

    public function getMaxId(): int
    {
        return $this->model->max('id');
    }

    public function query($options = [])
    {
        $user = $this->model->query();

        if (isset($options['with'])) {
            $user->with($options['with']);
        }

        if (isset($options['id'])) {
            $user->where('id', $options['user_id']);
        }

        if (isset($options['ids'])) {
            $user->whereIn('id', $options['ids']);
        }

        if (isset($options['role'])) {
            $user->whereHas("roles", function($q) use($options) {
                $q->whereIn("name", $options['role']);
            })->get();
        }

        if (isset($options['status']) && $options['status'] != '' && !is_array($options['status'])) {
            $user->where('status', $options['status']);
        }

        if (isset($options['status']) && is_array($options['status'])) {
            $user->whereIn('status', $options['status']);
        }

        if (isset($options['position_id']) && $options['position_id'] != '' ) {
            $user->where('position_id', $options['position_id']);
        }

        if (isset($options['position_ids']) && is_array($options['position_ids']) && count($options['position_ids']) > 0) {
            $user->whereIn('position_id', $options['position_ids']);
        }

        if (isset($options['gender']) && $options['gender'] != '' ) {
            $user->where('gender', $options['gender']);
        }

        if (isset($options['user_codes'])) {
            $user->whereIn('user_code', $options['user_codes']);
        }

        if (isset($options['branch_id']) && $options['branch_id'] != '' ) {
            $user->where('branch_id', $options['branch_id']);
        }

        if (isset($options['not_in_id']) && count($options['not_in_id'])) {
            $user->whereNotIn('id', $options['not_in_id']);
        }

        if (isset($options['keyword'])) {
            $keyword = trim($options['keyword']);
            $user->where(function($query) use ($keyword){
                $query->where('fullname', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('email', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('phone', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('user_code', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('personal_email', 'LIKE', '%' . $keyword . '%');
            });
        }
        $user->orderBy('id', 'desc');
        return $user;
    }

    public function paginate($options = [], $take = 10)
    {
        return $this->query($options)->paginate($take);
    }

    /**
     * Hàm lấy ra danh sách nhân viên mới trong tháng
     *
     * @param user $user
     * @return void
     */
    public function getMemberOnboard(User $user)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $users = $this->model
            ->select('id', 'avatar', 'name')
            ->where("status", config('user.status.active'))
            ->where("created_at", ">=", $startOfMonth)
            ->get();

        $users->map(function ($user) {
            $user->avatar = $user->getAvatar();
            return $user;
        });
        return $users->toArray();
    }

    /**
     *
     * @param array $data
     * @return int
     */
    public function insertMutiple(array $data)
    {
        return $this->model->insert($data);
    }
}