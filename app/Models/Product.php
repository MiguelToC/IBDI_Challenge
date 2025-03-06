<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name','sku','unit_price','stock', 'status'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            // Generar SKU único
            do {
                $sku = 'SKU-' . strtoupper(Str::random(8)); 
            } while (Product::where('sku', $sku)->exists()); 

            $product->sku = $sku;

            static::saving(function ($product) {
                if ($product->price < 0 || $product->stock < 0) {
                    throw new \Exception("El precio y el stock deben ser mayores o iguales a 0.");
                }
            });
        });
    }
}
