<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sales extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'customer_name',
        'customer_id_type',
        'customer_id_number',
        'customer_email',
        'user_id',
        'total_amount',
        'sale_date'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($sale) {
            do {
                $code = strtoupper(Str::random(8)); // Código aleatorio único
            } while (Sales::where('code', $code)->exists());

            $sale->code = $code;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}
