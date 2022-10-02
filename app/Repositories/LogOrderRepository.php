<?php

namespace App\Repositories;

use App\Models\RechargePack;
use App\Models\User;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class LogOrderRepository extends BaseRepository
{
    public function getModel()
    {
        return \App\Models\LogOrderPay::class;
    }

    public function getHistoryOrder($user_id = null)
    {
        if ($user_id) {
            return $this->model->with('recharge_pack:id,value')
                ->where('user_id', $user_id)
                ->OrderBy('created_at', 'desc')
                ->paginate(8);
        }

        $listPays = $this->model->with('recharge_pack:id,value')
            ->with('users:id,name')
            ->OrderBy('created_at', 'desc')
            ->paginate(8);
        $datas = $listPays->items();
        foreach ($datas as &$item) {
            $item->status_str = $item->status();
            $item->recharge_pack->value_str = $item->money($item->recharge_pack->value);
        }
        return $listPays;
    }

    public function formatData(&$logPays)
    {
        $dataFormat = $logPays->map(function ($logPay) {
            $logPay->status_str = $logPay->status();
            $logPay->recharge_pack->value_str = $logPay->money($logPay->recharge_pack->value);
            return $logPay;
        });
        $logPays->setCollection($dataFormat);
    }

    public function paginate($options = [], $take = 8)
    {
        return $this->query($options)->paginate($take);
    }

    public function query($options = [])
    {
        $listpays = $this->model->query();

        if (isset($options['with'])) {
            $listpays->with($options['with']);
        }

        if (isset($options['id'])) {
            $listpays->where('id', $options['user_id']);
        }

        $listpays->orderBy('id', 'desc');
        return $listpays;
    }

    public function confirmedPay($id, $attributes = []){
        $result = $this->find($id);
        if ($result) {
            if ($result->user_id && $result->rechange_pack_id && $attributes && $attributes['status'] == config('logorder.confirmed')) {
                $user = User::find($result->user_id);
                $rechange_pack = RechargePack::find($result->rechange_pack_id);
                $user->cash =$user->cash + $rechange_pack->value;
                $user->save();
            }
            $result->update($attributes);
            return $result;
        }

        return false;
    }
}
