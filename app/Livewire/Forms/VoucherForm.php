<?php

namespace App\Livewire\Forms;

use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Livewire\Form;

class VoucherForm extends Form
{
    public $id;
    public $manual_id;
    public $type = 'jv';
    public $created_by;
    public $date;
    public $notes;
    public $details = [];

    public float $balance = 0;
    public float $total_debit = 0;
    public float $total_credit = 0;

    public function rules()
    {
        return [
            'id' => 'nullable',
            'manual_id' => 'nullable',
            'type' => 'required',
            'created_by' => 'required',
            'date' => 'required',
            'notes' => 'nullable',
            'details' => ['required', 'array'],
            'balance' => ['required','numeric','in:0'],
            'total_debit' => ['required', 'numeric','gt:0'],
            'total_credit' => ['required', 'numeric','gt:0'],
            'details.*.account_id' => 'required',

            'details.*.debit' => [
                'required_without:details.*.credit', 
                'numeric', 
                function ($attribute, $value, $fail) {
                    $creditValue = $this->details[explode('.', $attribute)[1]]['credit'] ?? null;
                    if ($value <= 0 && ($creditValue === null || $creditValue <= 0)) {
                        $fail('Either debit or credit must be greater than zero.');
                    }
                }
                    ],
                    
            'details.*.credit' => [
                'required_without:details.*.debit',
                'numeric',
                function ($attribute, $value, $fail) {
                    $debitValue = $this->details[explode('.', $attribute)[1]]['debit'] ?? null;
                    if ($value <= 0 && ($debitValue === null || $debitValue <= 0)) {
                        $fail('Either debit or credit must be greater than zero.');
                    }
                }
            ],
        ];
    }

    public function getBalance()
    {
        $this->total_debit = collect($this->details)->sum('debit');
        $this->total_credit = collect($this->details)->sum('credit');
        $this->balance = round($this->total_debit - $this->total_credit,3);
        $this->validate([
            'balance' => ['required', 'numeric', 'in:0'],
            'total_debit' => ['required', 'numeric', 'gt:0'],
            'total_credit' => ['required', 'numeric', 'gt:0'],
        ]);
    }

    public function updateOrCreate()
    {

        if (!$this->id) {
            $this->created_by = auth()->id();
        }
        $this->validate();

        // Begin Transaction
        DB::beginTransaction();
        try {

            // 1- Create New or Update the current 
            $voucher = Voucher::updateOrCreate(['id' => $this->id], $this->except('details','balance','total_debit','total_credit'));

            // 2- get remaining filtered list of details ids without nulls
            $remainingDetailsIdsWithoutNull = collect($this->details)->pluck('id')->where('id', '!=', null);

            // 3- force delete voucher details from database which are not in the remaining ids that means its not needed anymore (when user chooses delete row in edit mode)
            $voucher->voucherDetails()->whereNotIn('id', $remainingDetailsIdsWithoutNull)->forceDelete();

            // 4- loop through current remaining details
            foreach ($this->details as $row) {
                if ($row['user_id'] == '') {
                    $row['user_id'] = null;
                }

                // 5- Create New detail or Update the remaining ones
                $voucher->voucherDetails()->updateOrCreate(['id' => @$row['id']], $row);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }
}
