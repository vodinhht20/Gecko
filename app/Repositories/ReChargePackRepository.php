<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class ReChargePackRepository extends BaseRepository
{
    public function getModel()
    {
        return \App\Models\RechargePack::class;
    }

    public function getRechargePack()
    {
        return $this->model->where('status', 1)->get();
    }
}