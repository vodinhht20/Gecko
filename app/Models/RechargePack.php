<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RechargePack extends Model
{
    use HasFactory;

    protected $table = 'recharge_pack';
    protected $fillable = [
        'title',
        'status',
        'description',
        'value',
    ];

    const KEY = "gecko007@";

    public function encodeRecharge(){
        $rechargePackId = base64_encode(SELF::KEY.$this->id);
        return trim($rechargePackId, '=');
    }
    
    public static function decodeRecharge($key){
        $rechargePackId = base64_decode($key);
        return substr($rechargePackId, strlen(SELF::KEY));
    }

}
