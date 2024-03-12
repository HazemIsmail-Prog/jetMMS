<?php

namespace App\Livewire\Suppliers;

use App\Models\Supplier;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class SupplierIndex extends Component
{
    use WithPagination;

    public $listeners = [];

    #[Computed()]
    #[On('suppliersUpdated')]
    public function suppliers()
    {
        return Supplier::query()
            ->paginate(15);
    }

    public function delete(Supplier $supplier) {
        $supplier->delete();
    }

    public function render()
    {
        return view('livewire.suppliers.supplier-index');
    }
}
