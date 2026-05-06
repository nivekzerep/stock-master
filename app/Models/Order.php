<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['folio', 'order_date', 'total'];

    // Relación: Una orden tiene muchos detalles
    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
