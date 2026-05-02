<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $products = Product::with('variants')->get();
        
        $low_stock_items = [];
        foreach ($products as $p) {
            if ($p->variants->count() > 0) {
                foreach ($p->variants as $v) {
                    if ($v->stock < 10) {
                        $low_stock_items[] = [
                            'name' => $p->title . ' (' . $v->variant_name . ')',
                            'stock' => $v->stock,
                            'id' => $p->id,
                            'type' => 'Variant'
                        ];
                    }
                }
            } else {
                if ($p->stock < 10) {
                    $low_stock_items[] = [
                        'name' => $p->title,
                        'stock' => $p->stock,
                        'id' => $p->id,
                        'type' => 'Product'
                    ];
                }
            }
        }

        return view('admin.inventory.index', compact('products', 'low_stock_items'));
    }

    public function updateStock(Request $request)
    {
        if ($request->type == 'product') {
            Product::where('id', $request->id)->update(['stock' => $request->stock]);
        } else {
            ProductVariant::where('id', $request->id)->update(['stock' => $request->stock]);
        }

        return response()->json(['message' => 'Stock updated successfully']);
    }
}
