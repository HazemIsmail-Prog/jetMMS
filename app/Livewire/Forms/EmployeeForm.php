<?php

namespace App\Livewire\Forms;

use Livewire\Form;

class EmployeeForm extends Form
{
    public $id;
    public $user_id;
    public $joinDate;
    public $recidencyExpirationDate;
    public $passportExpirationDate;
    public $passportIssueDate;
    public $company_id;
    public $cid;
    public $iban;
    public $nationality;
    public $gender;
    public $passport_no;
    public $startingSalary;
    public $startingLeaveBalance;
    public $lastWorkingDate;
    public $status;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'user_id' => 'required',
            'joinDate' => 'required',
            'recidencyExpirationDate' => 'required',
            'passportExpirationDate' => 'required',
            'passportIssueDate' => 'required',
            'company_id' => 'nullable',
            'cid' => 'required|min:12|max:12',
            'iban' => 'required',
            'nationality' => 'required',
            'gender' => 'required',
            'passport_no' => 'required',
            'startingSalary' => 'required',
            'startingLeaveBalance' => 'required',
            'lastWorkingDate' => 'nullable|required_unless:status,in,"active","under_test"',
            'status' => 'required',
        ];
    }
}
