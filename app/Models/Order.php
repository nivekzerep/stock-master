<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['folio', 'order_date', 'total'];

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
