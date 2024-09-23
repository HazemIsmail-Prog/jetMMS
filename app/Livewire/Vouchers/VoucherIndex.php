<?php

namespace App\Livewire\Vouchers;

use App\Models\Voucher;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class VoucherIndex extends Component
{

    use WithPagination;

    public $listeners = [];

    public $creators = [];
    public string $add_button_label;
    public string $title;
    public $filters;

    public function mount()
    {
        $this->title = __('messages.journal_vouchers');
        $this->add_button_label = __('messages.add_journal_voucher');

        $this->filters = [
            'search' => '',
            'start_date' => '',
            'end_date' => '',
        ];
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    #[Computed()]
    #[On('vouchersUpdated')]
    #[On('attachmentsUpdated')]
    public function vouchers()
    {
        return Voucher::query()
            ->with('user:id,name_' . app()->getLocale())
            ->latest()
            ->when(auth()->id() != 1, function (Builder $q) {
                $q->where('type', 'jv');
            })
            ->withSum('voucherDetails', 'debit')
            ->withCount('attachments')
            ->when($this->filters['search'], function (Builder $q) {
                $q->where(function (Builder $q) {
                    $q->where('id', $this->filters['search']);
                    $q->orWhere('manual_id', $this->filters['search']);
                    $q->orWhere('notes', 'like', '%' . $this->filters['search'] . '%');
                    $q->orWhereRelation('voucherDetails', 'narration', 'like', '%' . $this->filters['search'] . '%');
                });
            })
            ->when($this->filters['start_date'], function (Builder $q) {
                $q->whereDate('date', '>=', $this->filters['start_date']);
            })
            ->when($this->filters['end_date'], function (Builder $q) {
                $q->whereDate('date', '<=', $this->filters['end_date']);
            })
            ->orderBy('id', 'desc')
            ->paginate(15);
    }

    public function delete(Voucher $voucher)
    {
        $voucher->delete();
    }

    public function render()
    {
        return view('livewire.vouchers.voucher-index')->title(__('messages.journal_vouchers'));
    }
}
