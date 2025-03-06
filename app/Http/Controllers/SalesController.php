<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SaleItem;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SalesController extends Controller
{
    public function addSale(Request $request)
    {
        $validated = $request->validate([
            'customer_name'      => 'required|string|max:255',
            'customer_id_type'   => 'required|in:DNI,RUC',
            'customer_id_number' => 'required|string|max:15',
            'customer_email'     => 'nullable|email',
            'items'              => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1'
        ]);

        $totalAmount = 0;

        // Validar stocck
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            if ($product->stock < $item['quantity']) {
                return response()->json(['error' => "Stock insuficiente para {$product->name}"], 400);
            }
        }

        // Registrar venta
        $sale = Sales::create([
            'customer_name'      => $validated['customer_name'],
            'customer_id_type'   => $validated['customer_id_type'],
            'customer_id_number' => $validated['customer_id_number'],
            'customer_email'     => $validated['customer_email'] ?? null,
            'user_id'            => Auth::id(),
            'total_amount'       => 0, // Se actualizará después
        ]);

        // Registrar items y actualizar stock
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            $subtotal = $product->unit_price * $item['quantity'];
            $totalAmount += $subtotal;

            SaleItem::create([
                'sales_id'    => $sale->id,
                'product_id' => $product->id,
                'quantity'   => $item['quantity'],
                'unit_price' => $product->unit_price
            ]);

            $product->decrement('stock', $item['quantity']);
        }

        $sale->update(['total_amount' => $totalAmount]);

        return response()->json(['message' => 'Venta registrada con éxito', 'sale' => $sale], 201);
    }

    public function saludo(Request $request)
    {
        return response()->json(['message' => 'Hola mundo' . $request->name], 200);
    }

    public function report(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date'   => 'nullable|date_format:Y-m-d',
        ]);
    
        $query = Sales::with(['user', 'saleItems'])
            ->select('sales.*')
            ->withCount('saleItems as total_products') // Contamos la cantidad de productos vendidos en cada venta
            ->orderBy('sale_date', 'desc');
    
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('sale_date', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        } elseif ($request->has('start_date')) {
            $query->where('sale_date', '>=', $request->start_date . ' 00:00:00');
        } elseif ($request->has('end_date')) {
            $query->where('sale_date', '<=', $request->end_date . ' 23:59:59');
        }
    
        $sales = $query->get();
    
        return response()->json($sales);
    }
}
