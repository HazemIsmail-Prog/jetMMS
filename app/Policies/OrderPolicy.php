<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('orders_menu');
    }
    
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): bool
    {
        return true;
    }
    
    public function view_order_progress(User $user, Order $order): bool
    {
        return $user->hasPermission('orders_progress');
    }
    
    public function view_order_comments(User $user, Order $order): bool
    {
        return $user->hasPermission('orders_comments');
    }

    public function view_order_invoices(User $user, Order $order): bool
    {
        return $user->hasPermission('orders_invoices');
    }
    
    public function hold_order(User $user, Order $order): bool
    {
        return $user->hasPermission('orders_hold');
    }
    
    public function cancel_order(User $user, Order $order): bool
    {
        return $user->hasPermission('orders_cancel');
    }
    
    public function create(User $user): bool
    {
        return $user->hasPermission('orders_create');
    }
    
    public function update(User $user, Order $order): bool
    {
        return $user->hasPermission('orders_edit');
    }

}
