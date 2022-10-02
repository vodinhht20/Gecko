<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogOrderPay extends Model
{
    use HasFactory;
    protected $table = 'log_order_pay';
    protected $fillable = [
        'user_id',
        'recharge_pack_id',
        'code',
        'status',
        'type_pay'
    ];

    public function status()
    {
        if ($this->status == config('logorder.pending_approval')) {
            return '<div class="font-size-13"><i class="ri-checkbox-blank-circle-fill font-size-10 text-warning align-middle me-2"></i>Đang chờ duyệt</div>';
        }
        if($this->status == config('logorder.confirmed')){
            return '<div class="font-size-13"><i class="ri-checkbox-blank-circle-fill font-size-10 text-success align-middle me-2"></i>Đã hoàn thành</div>';
        }
        if($this->status == config('logorder.cancelled')){
            return '<div class="font-size-13"><i class="ri-checkbox-blank-circle-fill font-size-10 text-danger align-middle me-2"></i>Đã hủy</div>';
        }

        return '<div class="font-size-13"><i class="ri-checkbox-blank-circle-fill font-size-10 text-secondary align-middle me-2"></i>Chưa xác nhận</div>';
    }

    public function recharge_pack() {
        return $this->belongsTo(RechargePack::class, 'rechange_pack_id', 'id');
    }

    public function users() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function money($value) {
        $moneyF = money($value, currency('VND2'))->toString();
        return '<span class="badge badge-soft-primary">'.$moneyF.'</span>';
    }
}
