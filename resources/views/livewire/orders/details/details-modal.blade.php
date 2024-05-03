<div>
    <x-dialog-modal maxWidth="7xl" wire:model.live="showModal">
        <x-slot name="content">
            @if ($order)
            <div class=" grid grid-cols-1 md:grid-cols-2 gap-3">
                @can('view_order_progress', $order)
                <div>@livewire('orders.details.details-index', ['order' => $order], key($order->id . rand()))</div>
                @endcan
                @can('view_order_comments', $order)
                <div>@livewire('orders.comments.comment-form', ['order' => $order], key($order->id . rand()))</div>
                @endcan
                <div>@livewire('orders.statuses.status-index', ['order' => $order], key($order->id . rand()))</div>
                @if ($order->can_view_order_invoices)
                <div>@livewire('orders.invoices.invoice-index', ['order' => $order], key($order->id . rand()))</div>
                @endif
            </div>
            @endif
        </x-slot>
    </x-dialog-modal>
</div>