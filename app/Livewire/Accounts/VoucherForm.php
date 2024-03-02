<?php

namespace App\Livewire\Accounts;

use App\Livewire\Forms\VoucherForm as FormsVoucherForm;
use App\Models\Account;
use App\Models\CostCenter;
use App\Models\User;
use App\Models\Voucher;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class VoucherForm extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public $select_account = [];
    public $search = '';
    public $copy_from = '';
    public $balance = 0;
    public $total_debit = 0;
    public $total_credit = 0;
    public Voucher $voucher;
    public FormsVoucherForm $form;

    public function copy()
    {
        $voucher = Voucher::find($this->copy_from)->load('voucherDetails.cost_center');
        if ($voucher) {
            $this->form->details = [];
            foreach ($voucher->voucherDetails as $row) {
                $this->form->details[] = [
                    'account_id' => $row['account_id'],
                    'cost_center_id' => $row['cost_center_id'],
                    'user_id' => $row['user_id'],
                    'narration' => $row['narration'],
                    'debit' => $row['debit'],
                    'credit' => $row['credit'],
                ];
            }
            $this->getBalance();
            $this->resetErrorBag();
        } else {
            $this->addError('copy_from', __('messages.invalid_voucher_number'));
        }
    }

    #[On('showVoucherFormModal')]
    public function show(Voucher $voucher)
    {
        $this->reset('copy_from');
        $this->resetErrorBag();
        $this->form->reset();
        $this->form->date = today()->format('Y-m-d');
        $this->showModal = true;
        $this->voucher = $voucher->load('voucherDetails');
        $this->modalTitle = $this->voucher->id ? __('messages.edit_journal_voucher') . $this->voucher->id : __('messages.add_journal_voucher');
        $this->form->fill($this->voucher);
        $this->form->details = $this->voucher->voucherDetails->toArray();
        if (!$this->voucher->id) {
            //Create
            $this->form->created_by = auth()->id();
            $this->addRow();
            $this->addRow();
        }
        $this->getBalance();
    }

    #[On('debit')]
    #[On('credit')]
    public function getBalance()
    {
        $this->balance = 0;
        $this->total_debit = 0;
        $this->total_credit = 0;
        foreach ($this->form->details as $row) {
            $this->total_debit += $row['debit'] != '' ? $row['debit'] : 0;
            $this->total_credit += $row['credit'] != '' ? $row['credit'] : 0;
        }
        $this->balance = $this->total_debit - $this->total_credit;
    }

    public function addRow()
    {
        $this->form->details[] = [
            'account_id' => '',
            'cost_center_id' => null,
            'user_id' => '',
            'narration' => $this->form->notes ?? null,
            'debit' => null,
            'credit' => null,
        ];
    }

    public function deleteRow($index)
    {
        unset($this->form->details[$index]);
    }

    #[Computed()]
    public function accounts()
    {
        return Account::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->where('level', 3)
            ->get();
    }

    #[Computed()]
    public function cost_centers()
    {
        return CostCenter::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    #[Computed()]
    public function users()
    {
        return User::query()
            ->select('id', 'name_en', 'name_ar', 'name_' . app()->getLocale() . ' as name')
            ->orderBy('name')
            ->get();
    }

    public function save()
    {
        $validated = $this->form->validate();
        if (!$validated['id']) {
            $validated['created_by'] = auth()->id();
        }
        unset($validated['details']);
        $voucher = Voucher::updateOrCreate(['id' => $validated['id']], $validated);
        $voucher->voucherDetails()->delete();
        foreach ($this->form->details as $row) {
            if ($row['user_id'] == '') {
                $row['user_id'] = null;
            }
            $voucher->voucherDetails()->create($row);
        }
        $this->dispatch('vouchersUpdated');

        $this->form->reset();
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.accounts.voucher-form');
    }
}
