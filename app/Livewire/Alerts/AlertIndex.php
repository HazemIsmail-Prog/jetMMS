<?php

namespace App\Livewire\Alerts;

use App\Models\Attachment;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class AlertIndex extends Component
{

    #[Computed()]
    #[On('attachmentsUpdated')]
    public function upcommingExpiredAttachments() {
        return Attachment::query()
        ->where('alertable',true)
        ->whereRaw('DATEDIFF(expirationDate, CURDATE()) <= alertbefore AND DATEDIFF(expirationDate, CURDATE()) >= 0')
        ->select('*', DB::raw('DATEDIFF(expirationDate, CURDATE()) as date_difference'))
        ->with('attachable')
        ->orderBy('date_difference')
        ->orderBy('attachable_type')
        ->orderBy('description_'.app()->getLocale())
        ->get();
    }

    #[Computed()]
    #[On('attachmentsUpdated')]
    public function expiredAttachments() {
        return Attachment::query()
        ->where('alertable',true)
        ->where('expirationDate', '<', today())  // Change this line to check for expired attachments
        ->select('*', DB::raw('DATEDIFF(expirationDate, CURDATE()) as date_difference'))
        ->with('attachable')
        ->orderBy('date_difference','desc')
        ->orderBy('attachable_type')
        ->orderBy('description_'.app()->getLocale())
        ->get();
    }

    public function render()
    {
        return view('livewire.alerts.alert-index');
    }
}
