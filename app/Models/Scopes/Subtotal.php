<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class Subtotal implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
    $sql = 'select purchases.purchase_id as id
    , item_purchase.item_purchase_id as pivot_id
    , items.price * item_purchase.quantity as subtotal
    , customers.name as customer_name
    , items.name as item_name
    , items.price as item_price
    , item_purchase.quantity
    , purchases.customer_id
    , purchases.status
    , purchases.created_at
    , purchases.updated_at
    from purchases
    left join item_purchase on purchases.purchase_id = item_purchase.purchase_id
    left join items on item_purchase.item_id = items.item_id
    left join customers on purchases.customer_id = customers.customer_id
    ';

    $builder->fromSub($sql, 'order_subtotals');
    }
}
