<?php

namespace App\Livewire\Vouchers;

use App\Livewire\Forms\VoucherForm as FormsVoucherForm;
use App\Models\Account;
use App\Models\CostCenter;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Support\Arr;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class VoucherForm extends Component
{
    public $showModal = false;
    public $modalTitle = '';
    public $copy_from = '';
    public Voucher $voucher;
    public FormsVoucherForm $form;

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
            //Add 2 Starting rows on Create
            $this->addRow();
            $this->addRow();
        }
        $this->form->getBalance();
    }
    
    #[On('debit')]
    #[On('credit')]
    public function getBalance() {
        $this->form->getBalance();
    }

    public function copy()
    {
        $copiedVoucher = Voucher::where('type', 'jv')->find($this->copy_from);
        if ($copiedVoucher) {
            $copiedVoucher->load('voucherDetails');
            $copiedVoucherDetailsWithoutId = $copiedVoucher->voucherDetails->map(function ($detail) {
                return Arr::except($detail, ['id']);
            });
            $this->form->details = $copiedVoucherDetailsWithoutId->toArray();
            $this->form->getBalance();
            $this->resetErrorBag();
        } else {
            $this->addError('copy_from', __('messages.invalid_voucher_number'));
        }
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
        $this->form->getBalance();
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
        $this->form->updateOrCreate();
        $this->dispatch('vouchersUpdated');
        $this->showModal = false;
    }

    public function updatedShowModal($val)
    {
        if ($val == false) {
            $this->reset('voucher');
        }
    }

    public function render()
    {
        return view('livewire.vouchers.voucher-form');
    }
}
