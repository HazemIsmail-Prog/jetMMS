<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CustomerForm extends Form
{
    public $id;
    public $name;
    public $notes;
    public $cid;
    public $phones = [];
    public $addresses = [];

    public function rules()
    {
        return [
            'name' => 'required',
            'phones.*.type' => 'required',
            'phones.*.number' => [
                'required',
                'numeric',
                'digits_between:8,8',
                $this->id
                    ? Rule::unique('phones')->where(function ($q) {
                        $q->where('customer_id', '!=', $this->id);
                    })
                    : 'unique:phones',
            ],

            'addresses.*.area_id' => 'required',
            'addresses.*.block' => 'required',
            'addresses.*.street' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('messages.name_required'),
            'phones.*.number.unique' => __('messages.number_already_exist'),
        ];
    }
}
