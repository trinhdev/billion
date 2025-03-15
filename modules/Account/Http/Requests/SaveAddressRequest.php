<?php

namespace Modules\Account\Http\Requests;

use Modules\Core\Http\Requests\Request;

class SaveAddressRequest extends Request
{
    /**
     * Available attributes.
     *
     * @var string
     */
    protected $availableAttributes = 'account::attributes.addresses';


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'full_name' => ['required'],
            'phone' => ['required'],
            'address' => ['required'],
            'city' => ['required'],
            'district' => ['required'],
            'ward' => ['required']
        ];
    }
}
