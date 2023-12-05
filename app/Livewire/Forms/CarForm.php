<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Form;

class CarForm extends Form
{
    public $id;
    public $code;
    public $company_id;
    public $car_brand_id;
    public $car_type_id;
    public $driver_id;
    public $technician_id;
    public $plate_no;
    public $management_no;
    public $year;
    public $insurance_expiration_date;
    public $passengers_no;
    public $adv_expiration_date;
    public bool $has_installment = false;
    public $installment_company;
    public $notes;
    public bool $active = true;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'code' => ['required',Rule::unique('cars', 'code')->ignore($this->id)],
            'company_id' => 'required',
            'car_brand_id' => 'required',
            'car_type_id' => 'required',
            'driver_id' => 'nullable',
            'technician_id' => 'nullable',
            'plate_no' => 'required',
            'management_no' => 'required',
            'year' => 'required',
            'insurance_expiration_date' => 'required',
            'passengers_no' => 'required',
            'adv_expiration_date' => 'nullable',
            'has_installment' => 'nullable',
            'installment_company' => 'nullable',
            'notes' => 'nullable',
            'active' => 'nullable',
        ];
    }
}
