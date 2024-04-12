<?php

namespace App\Livewire\Vouchers;

use App\Models\User;
use App\Models\Voucher;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class VoucherIndex extends Component
{

    public $listeners = [];

    public $creators = [];
    public string $add_button_label;
    public string $title;
    public $filters;

    public function mount()
    {
        $this->title = __('messages.journal_vouchers');
        $this->add_button_label = __('messages.add_journal_voucher');
        $this->creators = User::whereHas('vouchers')->select('id', 'name_en', 'name_ar')->get();

        $this->filters = [];
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    #[Computed()]
    #[On('vouchersUpdated')]
    public function vouchers()
    {
        return Voucher::query()
            ->latest()
            ->where('type','jv')
            ->with('user')
            ->orderBy('id', 'desc')
            ->paginate(15);
    }

    public function delete(Voucher $voucher)
    {
        $voucher->delete();
    }

    public function render()
    {
        return view('livewire.vouchers.voucher-index');
    }
}
