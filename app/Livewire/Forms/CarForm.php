<?php

namespace App\Livewire\Forms;

use App\Models\Car;
use Illuminate\Validation\Rule;
use Livewire\Form;

class CarForm extends Form
{
    public $id;
    public $code;
    public $company_id;
    public $car_brand_id;
    public $car_type_id;
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
    public $created_by;
    public $fuel_card_serial;
    public $fuel_card_number;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'code' => ['required', Rule::unique('cars', 'code')->ignore($this->id)],
            'company_id' => 'required',
            'car_brand_id' => 'required',
            'car_type_id' => 'required',
            'plate_no' => 'required',
            'management_no' => 'required',
            'year' => 'required',
            'insurance_expiration_date' => 'required',
            'passengers_no' => 'required',
            'adv_expiration_date' => 'nullable',
            'has_installment' => 'nullable',
            'installment_company' => 'required_if:has_installment,true',
            'notes' => 'nullable',
            'active' => 'nullable',
            'fuel_card_serial' => 'nullable',
            'fuel_card_number' => 'nullable',
        ];
    }

    public function updateOrCreate()
    {
        $this->validate();
        if (!$this->id) {
            $this->created_by = auth()->id();
        }
        Car::updateOrCreate(['id' => $this->id], $this->all());
    }
}
