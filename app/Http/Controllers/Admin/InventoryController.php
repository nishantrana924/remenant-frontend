<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('variants')->latest()->paginate(20);
        
        // Stats for the top bar (all low stock items across entire DB)
        $low_stock_items = [];
        $all_low = Product::with('variants')->get();
        foreach ($all_low as $p) {
            if ($p->variants->count() > 0) {
                foreach ($p->variants as $v) {
                    if ($v->stock <= 10) {
                        $low_stock_items[] = [
                            'name' => $p->title . ' (' . ($v->size ?? $v->color ?? $v->weight) . ')',
                            'stock' => $v->stock,
                        ];
                    }
                }
            } else {
                if ($p->stock <= 10) {
                    $low_stock_items[] = [
                        'name' => $p->title,
                        'stock' => $p->stock,
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
