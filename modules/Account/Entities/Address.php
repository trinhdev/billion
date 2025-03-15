<?php

namespace Modules\Account\Entities;

use Modules\Support\State;
use Modules\Support\Country;
use Modules\User\Entities\User;
use Modules\Support\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['full_name', 'phone', 'address', 'city', 'district', 'ward'];


    public function customer()
    {
        return $this->belongsTo(User::class);
    }

    public function getStateNameAttribute()
    {
        return State::name($this->country, $this->state);
    }

    public function getCountryNameAttribute()
    {
        return Country::name($this->country);
    }
}
