<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function addProduct(request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'unit_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0'
        ]);
        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        Product::create([
            'name' => $request->name,
            'unit_price' => $request->unit_price,
            'stock' => $request->stock
        ]);

        return response()->json(['message' => 'Producto creado correctamente :D'], 201);
    }
    public function getProducts()
    {
        $products = Product::all();
        if ($products->isEmpty()) {
            return response()->json(['message' => 'No hay productos registrados.'], 404);
        }
        return response()->json($products, 200);
    }

    public function getProductById($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Producto no encontrado.'], 404);
        }
        return response()->json($product, 200);
    }

    public function updateProductById(request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Producto no encontrado.'], 404);
        }

        $validation = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:100',
            'unit_price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0'
        ]);
        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        if($request->has('name')) {
            $product->name = $request->name;
        }
        if($request->has('unit_price')) {
            $product->unit_price = $request->unit_price;
        }
        if($request->has('stock')) {
            $product->stock = $request->stock;
        }

        
        $product->update();

        return response()->json(['message' => 'Producto actualizado correctamente :D'], 200);
    }

    public function deleteProductById($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Producto no encontrado.'], 404);
        }

        $product->update(['status' => 0]);
        return response()->json(['message' => 'Producto desactivado correctamente :D'], 200);
    }


}
