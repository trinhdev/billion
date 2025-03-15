<?php

namespace Modules\Address;

use Illuminate\Contracts\Support\Arrayable;

class NullAddress implements Arrayable
{
    private $null;


    public function __construct($null = null)
    {
        $this->null = $null;
    }


    public function getFirstName()
    {
        return $this->null;
    }


    public function getLastName()
    {
        return $this->null;
    }


    public function getAddress1()
    {
        return $this->null;
    }


    public function getAddress2()
    {
        return $this->null;
    }


    public function getCity()
    {
        return $this->null;
    }


    public function getState()
    {
        return $this->null;
    }


    public function getZip()
    {
        return $this->null;
    }


    public function getCountry()
    {
        return $this->null;
    }


    public function toArray()
    {
        return [
            'full_name' => $this->null,
            'phone' => $this->null,
            'address' => $this->null,
            'ward' => $this->null,
            'district' => $this->null,
            'city' => $this->null
        ];
    }
}
